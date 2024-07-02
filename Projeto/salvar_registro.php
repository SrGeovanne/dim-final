<?php
// Inclui o arquivo de conexão
include 'conexao.php';

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $id = $_POST['id'];
    $dbname = $_POST['projeto_nome'];
    $grupo = $_POST['grupo'];
    $carga = $_POST['carga'];
    $descricoes = $_POST['descricoes'];
    $qtd = $_POST['qtd'];
    $pot_w = $_POST['pot_w'];
    $fp = $_POST['fp'];

    // Seleciona o banco de dados
    if (!$conn->select_db($dbname)) {
        die("Erro ao selecionar o banco de dados: " . $conn->error);
    }

    // Atualiza os dados do registro
    $sql = "UPDATE `projeto` SET `Grupo` = ?, `Carga` = ?, `Descricoes` = ?, `QTD` = ?, `Pot_W` = ?, `FP` = ? WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiiii", $grupo, $carga, $descricoes, $qtd, $pot_w, $fp, $id);

    if ($stmt->execute()) {
        echo "Registro atualizado com sucesso.";
    } else {
        echo "Erro ao atualizar o registro: " . $stmt->error;
    }

    // Fecha a declaração
    $stmt->close();

    // Redireciona para a lista de registros
    header("Location: listar_registros.php?projeto_nome=" . urlencode($dbname));
    exit();
} else {
    echo "Método de requisição inválido!";
    exit();
}

// Fecha a conexão
$conn->close();
?>
