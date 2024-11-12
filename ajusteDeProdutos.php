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

  // Consultar os produtos disponíveis
  $sql_produtos = "SELECT idProdutos, nomeProduto, valor FROM produtos";
  $result_produtos = $conn->query($sql_produtos);
  ?>

  <form action="" method="post">
    <h2>Ajuste de Estoque</h2>

    <label for="nomeProduto">Nome do Produto:</label>
    <select name="nomeProduto" id="nomeProduto">
      <?php
      while ($row = $result_produtos->fetch_assoc()) {
        echo "<option value='" . $row["idProdutos"] . "' data-valor='" . $row["valor"] . "'>" . $row["nomeProduto"] . "</option>";
      }
      ?>
    </select>

    <label for="quantidade">Quantidade:</label>
    <input type="number" name="quantidade" id="quantidade" placeholder="Digite a quantidade de produtos" required>

    <label for="valorUnitario">Valor Unitário:</label>
    <input type="text" name="valorUnitario" id="valorUnitario" required readonly>

    <label for="dataCompra">Data da Compra:</label>
    <input type="date" id="dataCompra" name="dataCompra" value="<?php echo date("Y-m-d"); ?>" required>

    <label for="tipo">Tipo:</label>
    <select name="tipo" id="tipo" required>
      <option value="">Selecione</option>
      <option value="entrada">Entrada</option>
      <option value="saida">Saída</option>
    </select>
<br>
    <button type="submit">Enviar</button>
  </form>
  <br>
  <script>
    document.getElementById('nomeProduto').addEventListener('change', function () {
      var selectedOption = this.options[this.selectedIndex];
      document.getElementById('valorUnitario').value = selectedOption.getAttribute('data-valor');
    });
  </script>
  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Processar o formulário e fazer as alterações no banco de dados
    $idProduto = $_POST['nomeProduto'];
    $quantidade = $_POST['quantidade'];
    $valorUnitario = $_POST['valorUnitario'];
    $dataCompra = $_POST['dataCompra'];
    $tipo = $_POST['tipo'];

    if ($tipo == 'entrada') {
      // Adicionar a quantidade ao estoque
      $sql = "UPDATE produtos SET quantidade = quantidade + $quantidade WHERE idProdutos = $idProduto";
      if ($conn->query($sql) === TRUE) {
      }
    } elseif ($tipo == 'saida') {
      // Verificar se há estoque suficiente
      $sql = "SELECT quantidade FROM produtos WHERE idProdutos = $idProduto";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $quantidadeAtual = $row['quantidade'];
        if ($quantidadeAtual >= $quantidade) {
          // Subtrair a quantidade do estoque
          $sql = "UPDATE produtos SET quantidade = quantidade - $quantidade WHERE idProdutos = $idProduto";
          if ($conn->query($sql) === TRUE) {
          }
        } else {
          echo "Erro: quantidade insuficiente em estoque.";
        }
      } else {
        echo "Erro ao obter quantidade em estoque.";
      }
    }

    // Adicionar o ajuste à tabela de ajustes
    $sql = "INSERT INTO ajusteestoque (idProdutos, quantidade, valorAjuste, dataCompra, tipo) VALUES ($idProduto, $quantidade, $valorUnitario, '$dataCompra', '$tipo')";
    if ($conn->query($sql) === TRUE) {
      echo " Ajuste de estoque registrado com sucesso.";
    } else {
      echo "Erro ao registrar ajuste de estoque: " . $conn->error;
    }
  }

  $conn->close();
  ?>

</body>

<footer>
  <a>&copy; 2024 Floricultura Divina Planta</a>
</footer>

</html>