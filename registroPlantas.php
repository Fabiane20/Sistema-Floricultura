<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Floricultura Divina Planta</title>
  <link rel="stylesheet" href="styles.css">
  <footer>
    <div class="links">
      <a href="index.html">Divina Planta</a>
    </div>
  </footer>
</head>

<body>
  <form action="" method="post">
    <h2>Adicionar Produto: </h2>
    <div class="form-group">
      <label for="nomeProduto">Nome do produto</label>
      <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" placeholder="Nome do produto"
        required>

      <label for="valor">Valor</label>
      <input type="number" step="0.01" class="form-control" id="valor" name="valor" placeholder="Valor" required>
    

      <label for="tipoDeProduto">Tipo de produto</label>
      <select class="form-control select2" id="tipoDeProduto" name="tipoDeProduto" required>
      <option value="">Selecione</option>
        <option value="folhagem">Folhagem</option>
        <option value="frutifera">Frutífera</option>
        <option value="suculenta">Suculenta</option>
        <option value="cacto">Cacto</option>
        <option value="pedra">Pedra</option>
        <option value="vaso">Vaso</option>
      </select>
 
 
      <label for="quantidade">Quantidade</label>
      <input type="text" class="form-control" id="quantidade" name="quantidade" placeholder="Quantidade" required>
    </div>




    <button type="submit" class="btn btn-primary">Enviar</button>

  </form>
  <br>

  <?php
// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Coleta os dados do formulário
  $nomeProduto = $_POST['nomeProduto'];
  $valor = $_POST['valor'];
  $tipoDeProduto = $_POST['tipoDeProduto'];
  $quantidade = $_POST['quantidade'];

  // Aqui você pode realizar a validação dos dados se necessário

  // Conexão com o banco de dados (substitua pelos seus dados de conexão)
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "floricultura";

  // Cria a conexão
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Verifica a conexão
  if ($conn->connect_error) {
      die("Erro na conexão: " . $conn->connect_error);
  }

  // Verificar se o produto já existe no banco de dados
  $result = mysqli_query($conn, "SELECT * FROM produtos WHERE nomeProduto='$nomeProduto' AND tipoDeProduto='$tipoDeProduto'");
  if (mysqli_num_rows($result) > 0) {
      // Produto já existe, atualiza a quantidade
      $row = mysqli_fetch_assoc($result);
      $novaQuantidade = $row['quantidade'] + $quantidade;
      mysqli_query($conn, "UPDATE produtos SET quantidade=$novaQuantidade WHERE nomeProduto='$nomeProduto' AND tipoDeProduto='$tipoDeProduto'");
      echo "Produto atualizado com sucesso!";
  } else {
      // Produto não existe, insere um novo registro
      $sql = "INSERT INTO produtos (nomeProduto, valor, tipoDeProduto, quantidade)
              VALUES ('$nomeProduto', '$valor', '$tipoDeProduto', '$quantidade')";

      if ($conn->query($sql) === TRUE) {
          echo "Produto registrado com sucesso!";
      } else {
          echo "Erro ao registrar o produto: " . $conn->error;
      }
  }

  // Fecha a conexão
  $conn->close();
}

  ?>

</body>

<footer>
  <a>&copy; 2024 Floricultura Divina Planta</a>
</footer>

</html>