<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Sistema de Calculo de Subestações</title>
    <link rel="stylesheet" href="teste.css">
</head>

<body>
    <h2>Sistema de Calculo de Subestações</h2>

    <p>Criar Projeto:
        <br>
        <br>
        <br>
        <a href="Criar_projeto.php">Novo</a>


    </p>

    <p>Editar Projeto existente:</p>

    <form method="get" action="listar_registros.php">
        <select name="projeto_nome">
            <?php
            // Incluir o script para obter os bancos de dados
            include 'obter_bancos_de_dados.php';
            ?>
        </select>
        <input type="submit" value="Editar">
    </form>
</body>

</html>