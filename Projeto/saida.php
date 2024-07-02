<?php
session_start();

// Inclui os arquivos de funções de cálculo dos fatores de demanda
require_once 'calcular_fatores_demanda.php';
require_once 'recomendacoes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
    $potencias = [];
    $cargas = [];
    $descricoes = [];
    $qtds = [];
    $fps = [];

    foreach ($_POST['grupo'] as $index => $grupo) {
        $grupo = htmlspecialchars($grupo, ENT_QUOTES, 'UTF-8');
        $carga = htmlspecialchars($_POST['carga'][$index], ENT_QUOTES, 'UTF-8');
        $descricao = htmlspecialchars($_POST['Descricoes'][$index], ENT_QUOTES, 'UTF-8');
        $qtd = intval($_POST['qtd'][$index]);
        $pot_w = floatval($_POST['pot_w'][$index]);
        $fp = floatval($_POST['fp'][$index]);

        $potencias[$grupo][] = $pot_w;
        $cargas[$grupo][] = $carga;
        $descricoes[$grupo][] = $descricao; // Adiciona as descrições corretamente
        $qtds[$grupo][] = $qtd;
        $fps[$grupo][] = $fp;
    }

    $ramo_selecionado = isset($_SESSION['ramo_selecionado']) ? htmlspecialchars($_SESSION['ramo_selecionado'], ENT_QUOTES, 'UTF-8') : '';

    // Calcula os fatores de demanda com base nas potências, cargas, quantidades e no ramo selecionado
    $fatores_demanda = calcularFatoresDemanda($potencias, $cargas, $qtds, $ramo_selecionado);
} else {
    // Se não houver dados enviados por POST, redireciona de volta para a página anterior
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Saída dos Dados</title>
    <link rel="stylesheet" href="saida.css">
</head>

<body>
    <?php
    // Exibe o nome do projeto
    if (isset($_SESSION['nome_projeto'])) {
        echo "<h2>Nome do Projeto: " . htmlspecialchars($_SESSION['nome_projeto']) . "</h2>";
    } else {
        echo "<h2>Nome do Projeto não encontrado</h2>";
    }

    // Verifica se o ramo selecionado está definido na sessão
    if (isset($_SESSION['ramo_selecionado'])) {
        echo "<h2>Ramo selecionado: " . htmlspecialchars($_SESSION['ramo_selecionado']) . "</h2>";
    } else {
        echo "<h2>Nenhum ramo selecionado</h2>";
    }

    // Verifica se existem dados enviados por POST
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
        $total_w_fd = 0;

        // Itera sobre cada grupo para exibir uma tabela separada e calcular isoladamente
        foreach (['A', 'B', 'C', 'D', 'E'] as $grupo) {
            if (isset($potencias[$grupo])) {
                echo "<h3>Grupo: " . htmlspecialchars($grupo) . "</h3>";
                echo "<table border='1'>";
                echo "<tr>
                    <th>Grupo</th>
                    <th>Tipo de Carga</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Potência (W)</th>
                    <th>Fator de Potência (FP)</th>
                    <th>Potência VA</th>
                    <th>Potência W Total</th>
                    <th>Potência VA Total</th>
                    <th>Fator de Demanda (FD)</th>
                    <th>Demanda Total</th>
                </tr>";

                $total_pot_fd_grupo = 0;
                $total_pot_va_grupo = 0;
                $total_pot_w_grupo = 0;

                foreach ($potencias[$grupo] as $index => $pot_w) {
                    $qtd = $qtds[$grupo][$index];
                    $fp = $fps[$grupo][$index] == 0 ? 1 : $fps[$grupo][$index];
                    $pot_va = $pot_w / $fp;
                    $pot_w_total = $pot_w * $qtd;
                    $pot_va_total = $pot_va * $qtd;
                    $fator_demanda_grupo = $fatores_demanda[$grupo][$index];

                    $potencia_total_com_fd = $pot_va_total * $fator_demanda_grupo;

                    $total_pot_fd_grupo += $potencia_total_com_fd;
                    $total_pot_va_grupo += $pot_va_total;
                    $total_pot_w_grupo += $pot_w_total;

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($grupo) . "</td>";
                    echo "<td>" . htmlspecialchars($cargas[$grupo][$index]) . "</td>";
                    echo "<td>" . htmlspecialchars($descricoes[$grupo][$index]) . "</td>"; // Exibe as descrições corretamente
                    echo "<td>" . htmlspecialchars($qtd) . "</td>";
                    echo "<td>" . htmlspecialchars($pot_w) . "</td>";
                    echo "<td>" . htmlspecialchars($fp) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($pot_va, 2, '.', '')) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($pot_w_total, 2, '.', '')) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($pot_va_total, 2, '.', '')) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($fator_demanda_grupo, 2, '.', '')) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($potencia_total_com_fd, 2, '.', '')) . "</td>";
                    echo "</tr>";
                }

                echo "<tr>";
                echo "<td colspan='7'>Demanda do Grupo $grupo</td>";
                echo "<td>" . htmlspecialchars(number_format($total_pot_w_grupo, 2, '.', '')) . "</td>";
                echo "<td>" . htmlspecialchars(number_format($total_pot_va_grupo, 2, '.', '')) . "</td>";
                echo "<td></td>";
                echo "<td>" . htmlspecialchars(number_format($total_pot_fd_grupo, 2, '.', '')) . "</td>";
                echo "</tr>";

                echo "</table><br>";

                // Adiciona o total do grupo ao total geral
                $total_w_fd += $total_pot_fd_grupo;
            }
        }

        // Calcula recomendações
        $transformador_recomendado = obterTransformadorRecomendado($total_w_fd);

        // Verifica se há transformador recomendável
        if ($transformador_recomendado === "não há transformador recomendável para essa potência") {
            echo "<h3>Total Geral</h3>";
            echo "<table border='1'>";
            echo "<tr>";
            echo "<td colspan='10'>Demanda Total (kVA)</td>";
            echo "<td>" . htmlspecialchars(number_format($total_w_fd, 2, '.', '')) . "</td>";
            echo "</tr>";
            echo "<tr><td colspan='10'>Transformador Recomendado</td><td>" . htmlspecialchars($transformador_recomendado) . "</td></tr>";
            echo "</table>";
        } else {
            $fusivel_elo_recomendado = obterFusivelElo($transformador_recomendado);
            $fusivel_hh_recomendado = obterFusivelHH($transformador_recomendado);
            $barramento_primario_recomendado = obterBarramentoPrimario($total_w_fd);
            $poste_recomendado = obterPoste($transformador_recomendado);
            $circuito_secundario_recomendado = obterCircuitoSecundario($transformador_recomendado);

            // Exibe a linha de total geral e as recomendações
            echo "<h3>Total Geral</h3>";
            echo "<table border='1'>";
            echo "<tr>";
            echo "<td colspan='10'>Demanda Total (kVA)</td>";
            echo "<td>" . htmlspecialchars(number_format($total_w_fd, 2, '.', '')) . "</td>";
            echo "</tr>";

            // Recomendações
            echo "<tr><td colspan='10'>Transformador Recomendado</td><td>" . htmlspecialchars($transformador_recomendado) . "</td></tr>";
            echo "<tr><td colspan='10'>Fusível Elo Recomendado (kVA)</td><td>" . htmlspecialchars($fusivel_elo_recomendado) . "</td></tr>";
            echo "<tr><td colspan='10'>Fusível HH Recomendado (A)</td><td>" . htmlspecialchars($fusivel_hh_recomendado) . "</td></tr>";
            echo "<tr><td colspan='10'>Barramento Primário Recomendado</td><td>" . htmlspecialchars($barramento_primario_recomendado) . "</td></tr>";
            echo "<tr><td colspan='10'>Poste Recomendado</td><td>" . htmlspecialchars($poste_recomendado) . "</td></tr>";
            echo "<tr><td colspan='10'>Circuito Secundário Recomendado</td><td>" . htmlspecialchars($circuito_secundario_recomendado) . "</td></tr>";
            echo "</table>";
        }
    } else {
        echo "<tr><td colspan='11'>Nenhum dado recebido.</td></tr>";
    }
    ?>

    <!-- Botões Home e Voltar centralizados -->
    <div style="text-align: center; margin-top: 20px;">
        <a href="index.php?projeto_nome=<?php echo urlencode($_SESSION['nome_projeto']); ?>" class="button">Home</a>
        <a href="listar_registros.php?projeto_nome=<?php echo urlencode($_SESSION['nome_projeto']); ?>" class="button">Voltar</a>
    </div>
</body>

</html>
