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
    <form action="" method="post" id="formCliente">
        <h2>Adicionar Cliente</h2>
        <div class="form-group">
            <label for="nomeCliente">Nome</label>
            <input type="text" id="nomeCliente" name="nomeCliente" placeholder="Nome do cliente" required>

            <label for="enderecoCliente">Endereço</label>
            <input type="text" class="form-control" id="enderecoCliente" name="enderecoCliente"
                placeholder="Endereço do cliente" required>

            <label for="celular">Celular</label>
            <input type="tel" class="form-control" id="celular" name="celular" placeholder="Celular" required>

            <label for="dataNascimento">Data de nascimento</label>
            <input type="date" class="form-control" id="dataNascimento" name="dataNascimento" required>
            <button type="submit" class="btn btn-primary">Adicionar Cliente</button>
            <br>
        </div>
    </form>
    <br>


    <?php
    // Verificar se o formulário foi submetido
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recuperar os valores dos campos do formulário
        $nomeCliente = $_POST['nomeCliente'];
        $enderecoCliente = $_POST['enderecoCliente'];
        $celular = $_POST['celular'];
        $dataNascimento = $_POST['dataNascimento'];

        // Conectar ao banco de dados (substitua os valores conforme necessário)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "floricultura";

        // Criar conexão
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar a conexão
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        // Consultar se o cliente já está cadastrado
        $sql_check = "SELECT * FROM Clientes WHERE nomeCliente = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $nomeCliente);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            echo "O cliente '$nomeCliente' já está cadastrado.";
        } else {
            // Preparar e executar a consulta SQL para inserir os dados na tabela de clientes
            $sql_insert = "INSERT INTO Clientes (nomeCliente, enderecoCliente, celular, dataNascimento) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssss", $nomeCliente, $enderecoCliente, $celular, $dataNascimento);
            $stmt_insert->execute();

            if ($stmt_insert->affected_rows > 0) {
                // Redirecionar para outra página
                header("Location: registroClientes.php");
                exit(); // Certifique-se de sair do script após o redirecionamento
            } else {
                echo "Erro ao adicionar cliente: " . $stmt_insert->error;
            }
        }

        // Fechar os statements
        $stmt_check->close();
        $conn->close();
    }
    ?>


</body>
<footer>
    <a>&copy; 2024 Floricultura Divina Planta</a>
</footer>

</html>