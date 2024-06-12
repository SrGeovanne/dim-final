<?php
$servername = "localhost"; // Endereço do servidor MySQL
$username = "root"; // Nome de usuário do MySQL
$password = "ceuma"; // Senha do MySQL

// Estabelecer conexão
$conn = new mysqli($servername, $username, $password);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para selecionar o banco de dados
function selecionarBanco($conn, $dbname) {
    if (!$conn->select_db($dbname)) {
        die("Erro ao selecionar o banco de dados: " . $conn->error);
    }
}
?>
