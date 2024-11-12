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


    <?php
    $q = isset($_GET['cliente']) ? $_GET['cliente'] : ''; // Captura o termo da busca
    
    // Conecta ao banco de dados
    $conn = new mysqli("localhost", "root", "", "floricultura");

    // Verifica erros na conexão
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Busca os clientes que correspondem ao termo
    $sqlClientes = "SELECT idCliente, nomeCliente, enderecoCliente, celular, DATE_FORMAT(dataNascimento,'%d/%m/%Y')AS dataNascimento
                    FROM Clientes
                    WHERE nomeCliente LIKE '%$q%'";
                    
    $resultClientes = $conn->query($sqlClientes);
    // var_dump ($sqlClientes);
    // Verifique se a consulta trouxe resultados:
    if ($resultClientes->num_rows > 0) {
        // Exibe os dados do cliente:
        $clientes = $resultClientes->fetch_assoc();
        ?>
        <h2> Dados do Cliente: </h2>
        <p>Nome:
            <?php echo htmlspecialchars($clientes['nomeCliente']); ?>
        </p>
        <p>Endereço:
            <?php echo htmlspecialchars($clientes['enderecoCliente']); ?>
        </p>
        <p>Celular:
            <?php echo htmlspecialchars($clientes['celular']); ?>
        </p>
        <p>Data de Nascimento:
            <?php echo htmlspecialchars($clientes['dataNascimento']); ?>
        </p>
        <?php
    } else {
        // Exibe uma mensagem de erro ou trata a situação de ausência de dados:
        echo "Não foi possível encontrar o cliente.";
    }
    ?>

    <h2>Compras</h2>

    <table border="1">
        <tr>
            <th>Nome do Produto</th>
            <th>Data da Compra</th>
            <th>Valor Unitário</th>
            <th>Quantidade</th>
            <th>Valor Total</th>

        </tr>
        <?php
        // Busca as compras do cliente
        $sqlCompras = "SELECT p.nomeProduto, DATE_FORMAT(v.data_compra,'%d/%m/%Y') AS data_compra, v.descricao, i.valorUnitario, i.quantidade, (i.quantidade * i.valorUnitario) as valor_total
        FROM vendas v
        INNER JOIN clientes c ON c.idCliente = v.idCliente
        INNER JOIN itemVenda i ON i.idVendas = v.idVendas
        INNER JOIN produtos p ON p.idProdutos = i.idProdutos
        WHERE c.nomeCliente LIKE '%$q%'
        ORDER BY STR_TO_DATE(v.data_compra, '%d/%m/%Y') DESC";
        $resultCompras = $conn->query($sqlCompras);

        //  var_dump($sqlCompras);
        // Exibe as compras
        while ($compra = $resultCompras->fetch_assoc()) {
            ?>
            <tr>
                <td>
                    <?php echo htmlspecialchars($compra['nomeProduto']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($compra['data_compra']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars(number_format($compra['valorUnitario'], 2, ',', '.')); 
                    ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($compra['quantidade']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars(number_format($compra['valor_total'], 2, ',', '.'));
                    
                    ?>
                </td>
            </tr>     
        <?php } ?>
    </table>
</body>
<footer>
    <a>&copy; 2024 Floricultura Divina Planta</a>
</footer>

</html>