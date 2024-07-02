<?php
// arquivo: salvar_alteracoes.php
include 'conexao.php';

// Obtém o nome do banco de dados a partir do parâmetro da URL
if (isset($_GET['dbname'])) {
    $dbname = $_GET['dbname'];
    $conn->select_db($dbname);
} else {
    echo "Nome do banco de dados não fornecido!";
    exit();
}

// Obtém os dados enviados pelo formulário
$ids = $_POST['id'];
$grupos = $_POST['grupo'];
$cargas = $_POST['carga'];
$descricoes = $_POST['Descricoes'];
$qtds = $_POST['qtd'];
$pot_w = $_POST['pot_w'];
$fps = $_POST['fp'];
$linhasRemover = json_decode($_POST['linhasRemover'], true);

// Atualiza os registros no banco de dados
foreach ($ids as $index => $id) {
    $grupo = $conn->real_escape_string($grupos[$index]);
    $carga = $conn->real_escape_string($cargas[$index]);
    $descricao = $conn->real_escape_string($descricoes[$index]);
    $qtd = intval($qtds[$index]);
    $potencia_w = floatval($pot_w[$index]);
    $fp = floatval($fps[$index]);

    $sql = "UPDATE projeto SET Grupo='$grupo', Carga='$carga', Descricoes='$descricao', QTD='$qtd', Pot_W='$potencia_w', FP='$fp' WHERE id='$id'";
    $conn->query($sql);
}

// Remove os registros no banco de dados
if (!empty($linhasRemover)) {
    foreach ($linhasRemover as $id) {
        $sql = "DELETE FROM projeto WHERE id='$id'";
        $conn->query($sql);
    }
}

// Atualiza o ramo selecionado
if (isset($_POST['ramo'])) {
    $novo_ramo = $conn->real_escape_string($_POST['ramo']);
    $sql = "UPDATE configuracoes SET valor='$novo_ramo' WHERE nome='ramo_selecionado'";
    $conn->query($sql);
    $_SESSION['ramo_selecionado'] = $novo_ramo; // Atualiza a sessão
}

// Define a mensagem de sucesso
$_SESSION['mensagem_sucesso'] = 'Dados atualizados com sucesso!';

// Fecha a conexão
$conn->close();
?>
