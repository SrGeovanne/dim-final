<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Tabela do Projeto</title>
    <link rel="stylesheet" href="tabela.css">
</head>

<body>
    <?php
    session_start();

    if (isset($_GET['dbname'])) {
        $dbname = $_GET['dbname'];
        include 'conexao.php';
    } else {
        echo "Nome do banco de dados não fornecido!";
        exit();
    }

    $nome_projeto = isset($_SESSION['nome_projeto']) ? $_SESSION['nome_projeto'] : 'Nenhum nome de projeto fornecido';
    $ramo_selecionado = isset($_SESSION['ramo_selecionado']) ? $_SESSION['ramo_selecionado'] : 'Nenhum ramo selecionado';
    $mensagem_sucesso = isset($_SESSION['mensagem_sucesso']) ? $_SESSION['mensagem_sucesso'] : '';
    unset($_SESSION['mensagem_sucesso']);
    ?>

    <h2>Tabela do Projeto - <?php echo htmlspecialchars($nome_projeto); ?></h2>
    <div id="mensagem-sucesso"><?php echo htmlspecialchars($mensagem_sucesso); ?></div>
    <div id="mensagem-erro"></div>

    <?php
    $campos_faltando = [];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $campos_obrigatorios = ['grupo', 'carga', 'Descricoes', 'qtd', 'pot_w', 'fp'];

        foreach ($campos_obrigatorios as $campo) {
            if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                $campos_faltando[] = $campo;
            }
        }

        if (!empty($campos_faltando)) {
            echo "Todos os campos são necessários! Campos faltando: " . implode(', ', $campos_faltando);
            echo "<br>";
        } else {
            echo "Dados atualizados com sucesso!<br>";
        }
    }
    ?>

    <?php if (empty($campos_faltando)) : ?>
        <form id="form-tabela">
            <input type="hidden" name="nome_projeto" value="<?php echo htmlspecialchars($nome_projeto); ?>">
            <input type="hidden" name="ramo" value="<?php echo htmlspecialchars($ramo_selecionado); ?>">
            <table id="tabela-projeto" border="1">
                <tr>
                    <th>Grupo</th>
                    <th>Carga</th>
                    <th>Descrições</th>
                    <th>QTD</th>
                    <th>Pot W</th>
                    <th>FP</th>
                    <th></th>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" name="id[]" value="1">
                        <select name="grupo[]" onchange="atualizarOpcoes(this)">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                    </td>
                    <td>
                        <select name="carga[]" onchange="atualizarFpPorCarga(this)">
                            <option value="iluminacao com compensacao">Iluminação com compensação</option>
                            <option value="iluminacao sem compensacao">Iluminação sem compensação</option>
                            <option value="tomada">Tomada de Uso Geral</option>
                        </select>
                    </td>
                    <td><input type="text" name="Descricoes[]"></td>
                    <td><input type="text" name="qtd[]" class="number-input"></td>
                    <td><input type="number" name="pot_w[]"></td>
                    <td><input type="number" step="0.01" name="fp[]" class="fp-input"></td>
                    <td></td>
                </tr>
            </table>
            <br>
            <button type="button" onclick="adicionarLinha()">+</button>
            <button type="button" onclick="salvarDados()">Salvar</button>
            <button type="button" id="botao-calcular" style="display:none;" onclick="calcular('<?php echo htmlspecialchars($nome_projeto); ?>')">Calcular</button>
        </form>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.fp-input').value = "0.92";
        });

        var linhaCount = 1;

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
                        atualizarOpcoes(this);
                    };
                } else if (cells[i] === "carga") {
                    input = document.createElement("select");
                    input.name = cells[i] + "[]";
                    input.innerHTML = `
                        <option value="iluminacao com compensacao">Iluminação com compensação</option>
                        <option value="iluminacao sem compensacao">Iluminação sem compensação</option>
                        <option value="tomada">Tomada de Uso Geral</option>
                    `;
                    input.onchange = function() {
                        atualizarFpPorCarga(this);
                    };
                } else if (cells[i] === "fp") {
                    input = document.createElement("input");
                    input.type = "number";
                    input.name = cells[i] + "[]";
                    input.step = "0.01";
                    input.className = "fp-input";
                    input.value = "0.92";
                } else {
                    input = document.createElement("input");
                    input.type = "text";
                    input.name = cells[i] + "[]";
                    if (cells[i] === "qtd") {
                        input.className = "number-input";
                    }
                }

                cell.appendChild(input);
            }

            var cellId = newRow.insertCell(-1);
            var idInput = document.createElement("input");
            idInput.type = "hidden";
            idInput.name = "id[]";
            idInput.value = linhaCount;
            cellId.appendChild(idInput);

            var cellRemoveButton = newRow.insertCell(-1);
            var removeButton = document.createElement("button");
            removeButton.textContent = "-";
            removeButton.type = "button";
            removeButton.onclick = function() {
                removerLinha(this);
            };
            cellRemoveButton.appendChild(removeButton);
        }

        function removerLinha(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            linhaCount--;
        }

        function atualizarOpcoes(selectElement) {
            var grupo = selectElement.value;
            var row = selectElement.parentNode.parentNode;
            var cargaSelect = row.querySelector('select[name="carga[]"]');
            var fpInput = row.querySelector('input[name="fp[]"]');

            if (grupo === "A") {
                cargaSelect.innerHTML = `
                    <option value="iluminacao com compensacao">Iluminação com compensação</option>
                    <option value="iluminacao sem compensacao">Iluminação sem compensação</option>
                    <option value="tomada">Tomada de Uso Geral</option>
                `;
                fpInput.value = "0.92";
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
                `;
                fpInput.value = "1";
            } else if (grupo === "C") {
                cargaSelect.innerHTML = `
                    <option value="condicionador de Ar janela">Condicionador de Ar janela</option>
                    <option value="condicionador de Ar split">Condicionador de Ar split</option>
                `;
                fpInput.value = "1";
            } else if (grupo === "D") {
                cargaSelect.innerHTML = `
                    <option value="motores">Motores Elétricos</option>
                    <option value="maquinas_solda">Máquinas de Solda</option>
                `;
                fpInput.value = "1";
            } else if (grupo === "E") {
                cargaSelect.innerHTML = `
                    <option value="equipamentos">Equipamentos Especiais</option>
                `;
                fpInput.value = "0.5";
            }
        }

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

        function validarFormulario() {
            var inputsNumericos = document.querySelectorAll('.number-input, [name="pot_w[]"], [name="fp[]"]');
            var erro = false;
            var mensagemErro = "";

            inputsNumericos.forEach(function(input) {
                if (isNaN(input.value) || input.value.trim() === "") {
                    erro = true;
                    mensagemErro += "Valor inválido no campo " + input.name + "<br>";
                    input.style.borderColor = "red";
                } else {
                    input.style.borderColor = "";
                }
            });

            if (erro) {
                document.getElementById('mensagem-erro').innerHTML = mensagemErro;
                return false;
            }

            document.getElementById('mensagem-erro').innerHTML = "";
            return true;
        }

        function salvarDados() {
            if (!validarFormulario()) {
                return;
            }

            var formData = new FormData(document.getElementById('form-tabela'));
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "salvar_dados.php?dbname=<?php echo urlencode($dbname); ?>", true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('mensagem-sucesso').innerHTML = "Dados atualizados com sucesso!";
                    document.getElementById('mensagem-erro').innerHTML = "";
                    document.getElementById('botao-calcular').style.display = "inline";
                } else {
                    document.getElementById('mensagem-erro').innerHTML = "Erro ao atualizar os dados.";
                }
            };

            xhr.onerror = function() {
                document.getElementById('mensagem-erro').innerHTML = "Erro ao atualizar os dados.";
            };

            xhr.send(formData);
        }

        function calcular(nomeProjeto) {
            if (linhaCount < 1) {
                alert("Adicione pelo menos uma linha à tabela.");
                return;
            }

            var table = document.getElementById('tabela-projeto');
            var rows = Array.from(table.rows).slice(1);

            rows.sort(function(a, b) {
                var grupoA = a.querySelector('select[name="grupo[]"]').value;
                var grupoB = b.querySelector('select[name="grupo[]"]').value;
                return grupoA.localeCompare(grupoB);
            });

            rows.forEach(function(row) {
                table.deleteRow(row.rowIndex);
            });

            rows.forEach(function(row) {
                table.appendChild(row);
            });

            var formData = new FormData();
            var tableRows = document.querySelectorAll('#tabela-projeto tr');

            for (var i = 1; i < tableRows.length; i++) {
                var row = tableRows[i];
                var rowData = {};

                var inputs = row.querySelectorAll('input, select');

                inputs.forEach(function(input) {
                    rowData[input.name] = input.value;
                });

                for (var key in rowData) {
                    formData.append(key, rowData[key]);
                }
            }

            var url = "saida.php?projeto=" + encodeURIComponent(nomeProjeto) + "&" + new URLSearchParams(formData).toString();
            window.location.href = url;
        }
    </script>
</body>

</html>
