<?php
include 'conexao.php';
session_start();

if (!isset($_POST['nome_projeto']) || empty($_POST['nome_projeto'])) {
    echo "Campo 'nome_projeto' não foi enviado ou está vazio.";
    exit;
}

if (!isset($_POST['ramo']) || empty($_POST['ramo'])) {
    echo "Campo 'ramo' não foi enviado ou está vazio.";
    exit;
}

$campos_obrigatorios = ['grupo', 'carga', 'Descricoes', 'qtd', 'pot_w', 'fp', 'id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $campos_faltando = [];
    foreach ($campos_obrigatorios as $campo) {
        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
            $campos_faltando[] = $campo;
        }
    }

    if (!empty($campos_faltando)) {
        echo "Campos faltando: " . implode(', ', $campos_faltando);
        exit;
    }

    $dbname = $_POST['nome_projeto'];
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    echo "Conexão bem-sucedida<br>";

    $ramo = $_POST['ramo'];

    foreach ($_POST['grupo'] as $key => $value) {
        $id = $_POST['id'][$key];
        $grupo = $_POST['grupo'][$key];
        $carga = $_POST['carga'][$key];
        $descricao = $_POST['Descricoes'][$key];
        $qtd = $_POST['qtd'][$key];
        $pot_w = $_POST['pot_w'][$key];
        $fp = $_POST['fp'][$key];

        if ($fp == 0) {
            $fp = null;
        }

        $sql_check = "SELECT COUNT(*) FROM projeto WHERE id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $id);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            $sql = "UPDATE projeto SET ramo = ?, grupo = ?, carga = ?, Descricoes = ?, qtd = ?, pot_w = ?, fp = ? WHERE id = ?";
        } else {
            $sql = "INSERT INTO projeto (ramo, id, grupo, carga, Descricoes, qtd, pot_w, fp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        }

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            if ($count > 0) {
                $stmt->bind_param("sssssssi", $ramo, $grupo, $carga, $descricao, $qtd, $pot_w, $fp, $id);
            } else {
                $stmt->bind_param("sisssssi", $ramo, $id, $grupo, $carga, $descricao, $qtd, $pot_w, $fp);
            }

            $stmt->execute();
            $stmt->close();
        } else {
            echo "Erro ao preparar a declaração: " . $conn->error;
        }
    }

    $_SESSION['mensagem_sucesso'] = "Dados atualizados com sucesso!";
    $conn->close();
    header("Location: tabela.php?dbname=" . urlencode($dbname));
    exit;
} else {
    echo "Método inválido!";
}
?>
