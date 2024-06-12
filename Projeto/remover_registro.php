<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        
        // Remove o registro do banco de dados
        $sql = "DELETE FROM projeto WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "Registro removido com sucesso!";
        } else {
            echo "Erro ao remover o registro: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "ID do registro não fornecido!";
    }
} else {
    echo "Método de requisição inválido!";
}

$conn->close();
?>
