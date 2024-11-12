<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Floricultura Divina Planta</title>
    <link rel="stylesheet" href="styles.css">
</head>
<footer>
    <div class="links">
        <a href="index.html">Divina Planta</a>
    </div>
</footer>

<body>
    <h2>Produtos em estoque</h2>
    <style>
        body {
            width: 100%;
        }


        table {
            width: 100%;
            margin-bottom: .5em;
            table-layout: fixed;
            text-align: center;
        }

        td,
        th {
            padding: .7em;
            margin: 0;
            /*border: 1px solid #ccc;*/
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="text"] {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }

        .tbody {
            padding-left: 30px;
        }

        .body2 {
            width: 15%;
            text-align: left;
            padding-left: 30px;
        }
    </style>
    </head>


    <div class="body2">
        <label for="filtro">Filtrar por Tipo:</label>
        <select id="filtro">
            <option value="todos">Todos</option>
            <option value="folhagem">Folhagem</option>
            <option value="frutifera">Frutífera</option>
            <option value="suculenta">Suculenta</option>
            <option value="cacto">Cacto</option>
            <option value="pedra">Pedra</option>
            <option value="vaso">Vaso</option>
        </select>


        <button id="consultaProdutos" class="submit" onclick="consultaProdutos()">Buscar</button>

        <script>
            function consultaProdutos() {
                var tipoDeProduto = document.getElementById('filtro').value;
                if (tipoDeProduto.trim() !== "") {
                    window.location.href = "consultaProdutos.php?tipoDeProduto=" + tipoDeProduto;
                } else {
                    document.getElementById("mensagemErro").style.display = "block";
                }
            }
        </script>
    </div>
    <br>

    <?php
    $q = isset($_GET['tipoDeProduto']) ? $_GET['tipoDeProduto'] : ''; // Captura o termo da busca
    
    // Configurações do banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "floricultura";

    // Criando a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificando a conexão
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Consulta SQL com INNER JOIN
    $sql = "SELECT p.nomeProduto, p.quantidade, p.tipoDeProduto, p.valor, (p.quantidade * p.valor) as valorTotal
FROM produtos p ";
    if ($q > 0) {
        $sql .= "where tipoDeProduto ='$q'";
    }
    // Executando a consulta
    $result = $conn->query($sql);

    // Exibindo os resultados em uma tabela HTML
    if ($result->num_rows > 0) {
        echo "<table>
      <tr>
        <th>Nome do Produto</th>
        <th>Quantidade</th>
        <th>Tipo de Produto</th>
        <th>Valor Unitário</th>
        <th>Valor Total</th>
      </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
        <td>" . $row["nomeProduto"] . "</td>
        <td>" . $row["quantidade"] . "</td>
        <td>" . $row["tipoDeProduto"] . "</td>
        <td>" . number_format($row["valor"], 2, ',', '.') . "</td>
        <td>" . number_format($row["valorTotal"], 2, ',', '.') . "</td>
      </tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum resultado encontrado.";
    }

    // Fechando a conexão
    $conn->close();

    ?>


    <br>
</body>
<footer>
    <a>&copy; 2024 Floricultura Divina Planta</a>
</footer>

</html>