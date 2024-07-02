<?php
// Inicia a sessão
session_start();

// Inclui o arquivo de conexão com o banco de dados
include 'conexao.php';

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Armazena o ramo selecionado na sessão
    $_SESSION['ramo_selecionado'] = $_POST['ramo'];
    // Armazena o nome do projeto na sessão
    $_SESSION['nome_projeto'] = $_POST['nome_projeto'];

    // Nome do banco de dados fornecido pelo usuário
    $dbname = $_POST['nome_projeto'];

    // Escapa caracteres especiais para evitar SQL Injection
    $dbname = $conn->real_escape_string($dbname);

    // Criação do banco de dados se não existir
    $sql_create_db = "CREATE DATABASE IF NOT EXISTS `$dbname`";

    if ($conn->query($sql_create_db) === TRUE) {
        echo "Banco de dados '$dbname' criado com sucesso ou já existe.<br>";

        // Seleciona o banco de dados
        if ($conn->select_db($dbname)) {
            // Criação da tabela de projetos
            $sql_create_table = "CREATE TABLE IF NOT EXISTS projeto (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ramo VARCHAR(255) NOT NULL,
                Grupo VARCHAR(255),
                Carga VARCHAR(255),
                Descricoes VARCHAR(255),
                QTD INT,
                Pot_W INT,
                FP DECIMAL(5, 2)
            )";

            if ($conn->query($sql_create_table) === TRUE) {
                echo "Tabela de projetos criada com sucesso ou já existe.<br>";

                // Insere o ramo na tabela de projetos
                $ramo = $_SESSION['ramo_selecionado'];
                $sql_insert_ramo = "INSERT INTO projeto (ramo) VALUES ('$ramo')";

                if ($conn->query($sql_insert_ramo) === TRUE) {
                    echo "Ramo '$ramo' inserido na tabela de projetos.<br>";
                } else {
                    echo "Erro ao inserir o ramo na tabela de projetos: " . $conn->error . "<br>";
                }

                // Redireciona para a página "tabela" com o nome do banco de dados na URL
                header("Location: tabela.php?dbname=" . urlencode($dbname));
                exit();
            } else {
                echo "Erro ao criar tabela de projetos: " . $conn->error . "<br>";
            }
        } else {
            echo "Erro ao selecionar o banco de dados: " . $conn->error . "<br>";
        }
    } else {
        echo "Erro ao criar banco de dados: " . $conn->error . "<br>";
    }

    $conn->close();
}

// Define a variável ramo_selecionado com o valor selecionado ou vazio se não estiver definido
$ramo_selecionado = isset($_SESSION['ramo_selecionado']) ? $_SESSION['ramo_selecionado'] : '';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Criar Projeto</title>
    <link rel="stylesheet" href="criarprojeto.css">
</head>

<body>
    <h2>Criar Projeto</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- Mantenha os campos existentes e adicione o campo oculto para o nome do projeto -->
        <?php if(isset($_SESSION['nome_projeto'])): ?>
        <input type="hidden" name="nome_projeto" value="<?php echo $_SESSION['nome_projeto']; ?>">
        <?php else: ?>
        <input type="hidden" name="nome_projeto" value="">
        <?php endif; ?>
        
        <label for="nome_projeto">Nome do Projeto:</label><br>
        <input type="text" id="nome_projeto" name="nome_projeto" value="<?php echo isset($_POST['nome_projeto']) ? htmlspecialchars($_POST['nome_projeto']) : ''; ?>"><br>

        <label for="ramo">Ramo:</label><br>
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

        <input type="submit" value="Salvar Projeto">
    </form>
</body>

</html>
