<?php
// Inclui o arquivo de conexão
include 'conexao.php';

// Consulta para obter todos os bancos de dados
$sql = "SHOW DATABASES";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Percorre todos os bancos de dados e cria opções de seleção
    while ($row = $result->fetch_assoc()) {
        $dbname = $row['Database'];
        echo "<option value='" . htmlspecialchars($dbname) . "'>" . htmlspecialchars($dbname) . "</option>";
    }
} else {
    echo "<option value=''>Nenhum banco de dados encontrado</option>";
}

// Fecha a conexão
$conn->close();
?>
