<?php
session_start();

// Verifica se o nome do banco de dados foi passado como parâmetro na URL
if (isset($_GET['projeto_nome'])) {
    $dbname = urldecode($_GET['projeto_nome']); // Decodifica o nome do banco de dados

    // Define nome_projeto na sessão
    $_SESSION['nome_projeto'] = $dbname;

    // Conecta ao banco de dados
    include 'conexao.php';

    // Seleciona o banco de dados
    if (!$conn->select_db($dbname)) {
        die("Erro ao selecionar o banco de dados: " . $conn->error);
    }

    // Consulta para obter o ramo atual
    $sql_ramo = "SELECT ramo FROM projeto LIMIT 1";
    $result_ramo = $conn->query($sql_ramo);

    if ($result_ramo->num_rows > 0) {
        $row_ramo = $result_ramo->fetch_assoc();
        $ramo_selecionado = $row_ramo['ramo'];
    } else {
        $ramo_selecionado = 'Nenhum ramo selecionado';
    }

    // Armazena o ramo na sessão para ser usado posteriormente
    $_SESSION['ramo_selecionado'] = $ramo_selecionado;
} else {
    echo "Nome do banco de dados não fornecido!";
    exit();
}

// Obtém a mensagem de sucesso da sessão, se houver
$mensagem_sucesso = isset($_SESSION['mensagem_sucesso']) ? $_SESSION['mensagem_sucesso'] : '';
unset($_SESSION['mensagem_sucesso']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar e Editar Registros</title>
    <link rel="stylesheet" href="tabela.css">
    <style>
        #ramo-atual {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Tabela do Projeto - <?php echo htmlspecialchars($_SESSION['nome_projeto']); ?></h2>

    <div id="mensagem-sucesso"><?php echo htmlspecialchars($mensagem_sucesso); ?></div>
    <div id="mensagem-erro"></div>

    <label for="ramo"><strong>Ramo atual:</strong> <span id="ramo-atual"><?php echo htmlspecialchars($ramo_selecionado); ?></span></label><br>
    <form id="form-tabela" method="post" action="saida.php">
        <select id="ramo" name="ramo">
            <option value="escola" <?php if ($ramo_selecionado == 'escola') echo 'selected'; ?>>Escola</option>
            <option value="hospitais" <?php if ($ramo_selecionado == 'hospitais') echo 'selected'; ?>>Hospitais</option>
            <option value="auditorio" <?php if ($ramo_selecionado == 'auditorio') echo 'selected'; ?>>Auditório</option>
            <option value="bancos" <?php if ($ramo_selecionado == 'bancos') echo 'selected'; ?>>Bancos</option>
            <option value="clubes" <?php if ($ramo_selecionado == 'clubes') echo 'selected'; ?>>Clubes</option>
            <option value="escritorios" <?php if ($ramo_selecionado == 'escritorios') echo 'selected'; ?>>Escritórios</option>
            <option value="barbearias" <?php if ($ramo_selecionado == 'barbearias') echo 'selected'; ?>>Barbearias</option>
            <option value="garagem" <?php if ($ramo_selecionado == 'garagem') echo 'selected'; ?>>Garagens Comerciais</option>
            <option value="hoteis" <?php if ($ramo_selecionado == 'hoteis') echo 'selected'; ?>>Hotéis</option>
            <option value="igreja" <?php if ($ramo_selecionado == 'igreja') echo 'selected'; ?>>Igreja</option>
            <option value="residencias" <?php if ($ramo_selecionado == 'residencias') echo 'selected'; ?>>Residências</option>
            <option value="restaurantes" <?php if ($ramo_selecionado == 'restaurantes') echo 'selected'; ?>>Restaurantes</option>
        </select><br><br>

        <?php
        // Consulta para obter os dados da tabela 'projeto'
        $sql = "SELECT `id`, `Grupo`, `Carga`, `Descricoes`, `QTD`, `Pot_W`, `FP` FROM `projeto`";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<input type='hidden' name='nome_projeto' value='" . htmlspecialchars($_SESSION['nome_projeto']) . "'>";
            echo "<table id='tabela-projeto' border='1'>
                <tr>
                    <th>Grupo</th>
                    <th>Carga</th>
                    <th>Descrições</th>
                    <th>QTD</th>
                    <th>Pot W</th>
                    <th>FP</th>
                    <th>Ações</th>
                </tr>";
            // Itera sobre os resultados e exibe os dados na tabela
            while ($row = $result->fetch_assoc()) {
                echo "<tr id='row-" . $row['id'] . "'>
                    <td>
                        <input type='hidden' name='id[]' value='" . $row['id'] . "'>
                        <select name='grupo[]' onchange='atualizarOpcoes(this)' data-carga='" . htmlspecialchars($row['Carga']) . "'>
                            <option value='A'" . ($row['Grupo'] == 'A' ? ' selected' : '') . ">A</option>
                            <option value='B'" . ($row['Grupo'] == 'B' ? ' selected' : '') . ">B</option>
                            <option value='C'" . ($row['Grupo'] == 'C' ? ' selected' : '') . ">C</option>
                            <option value='D'" . ($row['Grupo'] == 'D' ? ' selected' : '') . ">D</option>
                            <option value='E'" . ($row['Grupo'] == 'E' ? ' selected' : '') . ">E</option>
                        </select>
                    </td>
                    <td>
                        <select name='carga[]' data-carga='" . htmlspecialchars($row['Carga']) . "'>";
                
                $cargas = [];
                if ($row['Grupo'] == 'A') {
                    $cargas = ['iluminacao com compensacao' => 'Iluminação com compensação', 'iluminacao sem compensacao' => 'Iluminação sem compensação', 'tomada' => 'Tomada de Uso Geral'];
                } else if ($row['Grupo'] == 'B') {
                    $cargas = [
                        'chuveiro eletrico' => 'Chuveiro Elétrico',
                        'torneira eletrica' => 'Torneira Elétrica',
                        'Aquecedor passagem' => 'Aquecedor de Passagem',
                        'ferro eletrico' => 'Ferro Elétrico',
                        'fogao eletrico' => 'Fogão Elétrico',
                        'maquina secar roupa' => 'Máquina de Secar Roupa',
                        'maquina lavar louca' => 'Máquina de Lavar Louça',
                        'forno eletrico' => 'Forno Elétrico',
                        'microondas' => 'Microondas',
                        'tomada especifica' => 'Tomada de Uso específico'
                    ];
                } else if ($row['Grupo'] == 'C') {
                    $cargas = ['condicionador de Ar janela' => 'Condicionador de Ar janela', 'condicionador de Ar split' => 'Condicionador de Ar split'];
                } else if ($row['Grupo'] == 'D') {
                    $cargas = ['motores' => 'Motores Elétricos', 'maquinas_solda' => 'Máquinas de Solda'];
                } else if ($row['Grupo'] == 'E') {
                    $cargas = ['equipamentos' => 'Equipamentos Especiais'];
                }

                foreach ($cargas as $value => $label) {
                    echo "<option value='" . htmlspecialchars($value) . "'" . ($row['Carga'] == $value ? " selected" : "") . ">" . htmlspecialchars($label) . "</option>";
                }

                echo "</select>
                    </td>
                    <td><input type='text' name='Descricoes[]' value='" . htmlspecialchars($row['Descricoes']) . "'></td>
                    <td><input type='number' name='qtd[]' value='" . htmlspecialchars($row['QTD']) . "'></td>
                    <td><input type='number' name='pot_w[]' value='" . htmlspecialchars($row['Pot_W']) . "'></td>
                    <td><input type='number' step='0.01' name='fp[]' class='fp-input' value='" . htmlspecialchars($row['FP']) . "'></td>
                    <td><button type='button' onclick='removerLinha(this, " . $row['id'] . ")'>Remover</button></td>
                </tr>";
            }
            echo "</table>";
            echo "<br><button type='button' onclick='adicionarLinha()'>+</button>";
            echo "<button type='button' onclick='duplicarSalvarDados()'>Salvar</button>";
            echo "<button type='button' id='botao-calcular' style='display:none;' onclick='calcular()'>Calcular</button>";
            echo "</form>";
        } else {
            echo "Nenhum dado encontrado na tabela 'projeto'.";
        }

        // Fecha a conexão
        $conn->close();
        ?>

        <script>
            var linhaCount = <?php echo $result->num_rows; ?>;
            var linhasRemover = [];

            document.addEventListener("DOMContentLoaded", function() {
                // Inicializa as opções de cargas para cada linha existente
                var grupoSelects = document.querySelectorAll('select[name="grupo[]"]');
                grupoSelects.forEach(function(select) {
                    atualizarOpcoes(select, false);
                });
            });

            // Função para adicionar uma nova linha à tabela
            function adicionarLinha() {
                linhaCount++;
                var table = document.getElementById('tabela-projeto');
                var newRow = table.insertRow(-1);
                var cells = ["grupo", "carga", "Descricoes", "qtd", "pot_w", "fp"];

                for (var i = 0; i < cells.length; i++) {
                    var cell = newRow.insertCell(i);
                    var input;

                    if (cells[i] === "grupo") {
                        input = document.createElement("select");
                        input.name = cells[i] + "[]";
                        input.innerHTML = `
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        `;
                        input.onchange = function() {
                            atualizarOpcoes(this, true);
                        };
                    } else if (cells[i] === "carga") {
                        input = document.createElement("select");
                        input.name = cells[i] + "[]";
                        input.onchange = function() {
                            atualizarFpPorCarga(this);
                        };
                    } else if (cells[i] === "fp") {
                        input = document.createElement("input");
                        input.type = "number";
                        input.name = cells[i] + "[]";
                        input.step = "0.01";
                        input.className = "fp-input";
                    } else {
                        input = document.createElement("input");
                        input.type = "text";
                        input.name = cells[i] + "[]";
                    }

                    cell.appendChild(input);
                }

                // Adicionar campo oculto ID com valor de linhaCount
                var cellId = newRow.insertCell(-1);
                var idInput = document.createElement("input");
                idInput.type = "hidden";
                idInput.name = "id[]";
                idInput.value = linhaCount;
                cellId.appendChild(idInput);

                // Adicionar botão de remoção de linha
                var cellRemoveButton = newRow.insertCell(-1);
                var removeButton = document.createElement("button");
                removeButton.textContent = "-";
                removeButton.type = "button";
                removeButton.onclick = function() {
                    removerLinha(this, idInput.value);
                };
                cellRemoveButton.appendChild(removeButton);
            }

            // Função para remover uma linha da tabela
            function removerLinha(button, id) {
                var row = button.parentNode.parentNode;
                row.parentNode.removeChild(row);
                linhaCount--;
                linhasRemover.push(id); // Adiciona o ID da linha removida ao array
            }

            // Função para atualizar as opções do campo Tipo de Carga e FP com base no Grupo selecionado
            function atualizarOpcoes(selectElement, updateCarga = true) {
                var grupo = selectElement.value;
                var row = selectElement.parentNode.parentNode;
                var cargaSelect = row.querySelector('select[name="carga[]"]');
                var fpInput = row.querySelector('input[name="fp[]"]');
                var cargaAnterior = cargaSelect.getAttribute('data-carga');

                if (!updateCarga) {
                    cargaAnterior = cargaSelect.value;
                }

                cargaSelect.innerHTML = ''; // Limpa as opções atuais

                if (grupo === "A") {
                    cargaSelect.innerHTML = `
                        <option value="iluminacao com compensacao">Iluminação com compensação</option>
                        <option value="iluminacao sem compensacao">Iluminação sem compensação</option>
                        <option value="tomada">Tomada de Uso Geral</option>
                    `;
                    if (fpInput.value === "") {
                        fpInput.value = "0.92"; // Define o valor padrão de FP se estiver vazio
                    }
                } else if (grupo === "B") {
                    cargaSelect.innerHTML = `
                        <option value="chuveiro eletrico">Chuveiro Elétrico</option>
                        <option value="torneira eletrica">Torneira Elétrica</option>
                        <option value="Aquecedor passagem">Aquecedor de Passagem</option>
                        <option value="ferro eletrico">Ferro Elétrico</option>
                        <option value="fogao eletrico">Fogão Elétrico</option>
                        <option value="maquina secar roupa">Máquina de Secar Roupa</option>
                        <option value="maquina lavar louca">Máquina de Lavar Louça</option>
                        <option value="forno eletrico">Forno Elétrico</option>
                        <option value="microondas">Microondas</option>
                        <option value="tomada especifica">Tomada de Uso específico</option>
                    `;
                    if (fpInput.value === "") {
                        fpInput.value = "1"; // Define o valor padrão de FP se estiver vazio
                    }
                } else if (grupo === "C") {
                    cargaSelect.innerHTML = `
                        <option value="condicionador de Ar janela">Condicionador de Ar janela</option>
                        <option value="condicionador de Ar split">Condicionador de Ar split</option>
                    `;
                    if (fpInput.value === "") {
                        fpInput.value = "1"; // Define o valor padrão de FP se estiver vazio
                    }
                } else if (grupo === "D") {
                    cargaSelect.innerHTML = `
                        <option value="motores">Motores Elétricos</option>
                        <option value="maquinas_solda">Máquinas de Solda</option>
                    `;
                    if (fpInput.value === "") {
                        fpInput.value = "1"; // Define o valor padrão de FP se estiver vazio
                    }
                } else if (grupo === "E") {
                    cargaSelect.innerHTML = `
                        <option value="equipamentos">Equipamentos Especiais</option>
                    `;
                    if (fpInput.value === "") {
                        fpInput.value = "0.5"; // Define o valor padrão de FP se estiver vazio
                    }
                }

                // Seleciona a carga correta da linha
                if (cargaAnterior) {
                    cargaSelect.value = cargaAnterior;
                }
            }

            // Função para atualizar o FP com base na carga selecionada
            function atualizarFpPorCarga(selectElement) {
                var row = selectElement.parentNode.parentNode;
                var grupoSelect = row.querySelector('select[name="grupo[]"]');
                var fpInput = row.querySelector('input[name="fp[]"]');

                if (grupoSelect.value === "A") {
                    if (selectElement.value === "iluminacao sem compensacao") {
                        fpInput.value = "0.5";
                    } else if (selectElement.value === "iluminacao com compensacao") {
                        fpInput.value = "0.92";
                    } else if (selectElement.value === "tomada") {
                        fpInput.value = "1";
                    }
                }
            }

            // Função para salvar os dados usando AJAX
            function salvarDados() {
                var formData = new FormData(document.getElementById('form-tabela'));
                formData.append('linhasRemover', JSON.stringify(linhasRemover)); // Adiciona os IDs das linhas removidas
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "salvar_alteracoes.php?dbname=<?php echo urlencode($dbname); ?>", true);

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        document.getElementById('mensagem-sucesso').innerHTML = "Dados atualizados com sucesso!";
                        document.getElementById('mensagem-erro').innerHTML = "";
                        document.getElementById('botao-calcular').style.display = "inline"; // Exibe o botão Calcular
                        var ramoSelecionado = document.getElementById('ramo').value;
                        document.getElementById('ramo-atual').textContent = ramoSelecionado; // Atualiza o valor exibido do ramo
                    } else {
                        document.getElementById('mensagem-erro').innerHTML = "Erro ao atualizar os dados.";
                    }
                };

                xhr.onerror = function() {
                    document.getElementById('mensagem-erro').innerHTML = "Erro ao atualizar os dados.";
                };

                xhr.send(formData);
            }

            // Função para duplicar a chamada de salvar dados
            function duplicarSalvarDados() {
                salvarDados();
                salvarDados();
            }

            // Função para calcular
            function calcular() {
                var formTabela = document.getElementById('form-tabela');
                formTabela.submit();
            }
        </script>
</body>
</html>
