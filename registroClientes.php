<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Floricultura Divina Planta</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
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
  $sql_clientes = "SELECT idCliente, nomeCliente FROM clientes";
  $sql_produtos = "SELECT idProdutos, nomeProduto, valor FROM produtos";
  $result_produtos = $conn->query($sql_produtos);
  $result_clientes = $conn->query($sql_clientes);
  ?>
  <div class="produto-container">
    <form action="" method="post">
      <h3>Digite o nome do Cliente</h3>
      <select name="idCliente" id="idCliente" required>
        <option value="">Selecione</option>
        <?php
        while ($row = $result_clientes->fetch_assoc()) {
          echo "<option value='" . $row["idCliente"] . "'>" . $row["nomeCliente"] . "</option>";
        }
        ?>
      </select>
      <h3> Adicione os itens da compra </h3>
      <label for="nomeProduto">Nome do Produto:</label>
      <select name="nomeProduto" id="nomeProduto">
        <option value="">Selecione</option>
        <?php
        while ($row = $result_produtos->fetch_assoc()) {
          echo "<option value='" . $row["idProdutos"] . "' data-valor='" . $row["valor"] . "'>" . $row["nomeProduto"] . "</option>";
        }
        ?>
      </select>
      <!-- futuramente ser possível de adicionar a quantidade por KG-->
      <label for="quantidade">Quantidade:</label>
      <input type="number" name="quantidade" id="quantidade" placeholder="Digite a quantidade de itens">
      <label for="valorUnitario">Valor Unitário:</label>
      <input type="text" name="valorUnitario" id="valorUnitario" readonly>
      <button type="button" class="btn" onclick="adicionarProdutoETotal()">Adicionar Produto</button>
      <h2>Produtos Adicionados</h2>
      <table id="tabelaProdutos">
        <thead>
          <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Preço Unitário</th>
            <th>Total</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody id="corpoTabelaProdutos"></tbody>
        <tfoot>
          <tr>
            <td colspan="3" style="text-align: right;">Valor Total:</td>
            <td id="valor_total" colspan="2"></td>
          </tr>
        </tfoot>
      </table>
      <label for="data_compra">Data da Compra:</label>
      <input type="date" id="data_compra" name="data_compra" value="<?php echo date("Y-m-d"); ?>" required>
      <br><br>


      <button type="submit" class="btn">Enviar Compra</button>
    </form>
  </div>
  <script>
    document.getElementById('nomeProduto').addEventListener('change', function () {
      var selectedOption = this.options[this.selectedIndex];
      document.getElementById('valorUnitario').value = selectedOption.getAttribute('data-valor');
    });
    function adicionarProdutoETotal() {
      var select = document.getElementById("nomeProduto");
      const produto = select.options[select.selectedIndex].text;
      const produtoId = select.options[select.selectedIndex].value;
      const quantidade = parseFloat(document.getElementById('quantidade').value);
      const preco = parseFloat(document.getElementById('valorUnitario').value);
      const total = quantidade * preco;
      const newRow = document.createElement('tr');
      newRow.innerHTML = `
           <td>${produto} <input hidden type="text" name="idProduto[]" value="${produtoId}"></td>
           <td>
             <button onclick="aumentarQuantidade(this)">+</button>
             <span>${quantidade}</span>
             <button onclick="diminuirQuantidade(this)">-</button>
           </td>
           <td>${preco.toFixed(2)}</td>
           <td class="total">${total.toFixed(2)}</td>
           <td><button onclick="removerProduto(this)">Remover</button></td>
         `;
      // Adiciona o input hidden com a quantidade
      const inputQuantidade = document.createElement('input');
      inputQuantidade.type = 'hidden';
      inputQuantidade.name = 'quantidade[]';
      inputQuantidade.value = quantidade;
      newRow.querySelector('td').appendChild(inputQuantidade);
      const inputIdProduto = document.createElement('input');
      inputIdProduto.type = 'hidden';
      inputIdProduto.name = 'idProdutos[]'; // Use 'idProdutos[]' para enviar um array de IDs
      inputIdProduto.value = produtoId;
      newRow.querySelector('td').appendChild(inputIdProduto);
      document.getElementById('corpoTabelaProdutos').appendChild(newRow);
      limparFormulario();
      atualizarValorTotal();
    }
    function limparFormulario() {
      document.getElementById('quantidade').value = '';
      document.getElementById('valorUnitario').value = '';
    }
    function aumentarQuantidade(button) {
      const quantidadeSpan = button.parentNode.querySelector('span');
      quantidadeSpan.textContent = parseInt(quantidadeSpan.textContent) + 1;
      atualizarTotalProduto(button);
    }
    function diminuirQuantidade(button) {
      const quantidadeSpan = button.parentNode.querySelector('span');
      const quantidade = parseInt(quantidadeSpan.textContent);
      if (quantidade > 1) {
        quantidadeSpan.textContent = quantidade - 1;
        atualizarTotalProduto(button);
      }
    }
    function atualizarTotalProduto(button) {
      const row = button.parentNode.parentNode;
      const preco = parseFloat(row.cells[2].textContent);
      const quantidade = parseInt(row.cells[1].querySelector('span').textContent);
      row.cells[3].textContent = (quantidade * preco).toFixed(2);
      atualizarValorTotal();
    }
    function removerProduto(button) {
      const row = button.parentNode.parentNode;
      row.parentNode.removeChild(row);
      atualizarValorTotal();
    }
    function atualizarValorTotal() {
      const linhasProdutos = document.querySelectorAll('#corpoTabelaProdutos tr');
      let total = 0;
      linhasProdutos.forEach((linhaProduto) => {
        const quantidade = parseInt(linhaProduto.cells[1].querySelector('span').textContent);
        const preco = parseFloat(linhaProduto.cells[2].textContent);
        total += quantidade * preco;
      });
      document.getElementById('valor_total').textContent = total.toFixed(2);
    }
  </script>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Coletar dados do formulário
  $idCliente = $_POST['idCliente'];
  $dataCompra = $_POST['data_compra'];
  // Calcular valor total
  $totalVenda = 0;
  $estoqueSuficiente = true;
  for ($i = 0; $i < count($_POST['idProdutos']); $i++) {
    $produtoId = $_POST['idProdutos'][$i];
    $quantidade = $_POST['quantidade'][$i];
    // Verificar se há estoque suficiente
    $sqlEstoque = "SELECT quantidade FROM produtos WHERE idProdutos = '$produtoId'";
    $resultEstoque = $conn->query($sqlEstoque);
    if ($resultEstoque->num_rows > 0) {
      $rowEstoque = $resultEstoque->fetch_assoc();
      $estoqueAtual = $rowEstoque['quantidade'];
      if ($estoqueAtual < $quantidade) {
        echo "Erro: quantidade insuficiente em estoque para o produto ID: $produtoId";
        $estoqueSuficiente = false;
        break; // Sair do loop se não houver estoque suficiente para um produto
      }
    } else {
      echo "Erro ao obter quantidade em estoque para o produto ID: $produtoId";
      $estoqueSuficiente = false;
      break; // Sair do loop se não for possível obter a quantidade em estoque
    }
    // Obter o preço do produto
    $sqlPreco = "SELECT valor FROM produtos WHERE idProdutos = '$produtoId'";
    $resultPreco = $conn->query($sqlPreco);
    if ($resultPreco->num_rows > 0) {
      $rowPreco = $resultPreco->fetch_assoc();
      $preco = $rowPreco['valor'];
      // Calcular o total do item
      $totalItem = $quantidade * $preco;
      // Adicionar o total do item ao total da venda
      $totalVenda += $totalItem;
    } else {
      echo "Erro ao obter preço do produto ID: $produtoId";
      $estoqueSuficiente = false;
      break; // Sair do loop se não for possível obter o preço do produto
    }
  }
  if ($estoqueSuficiente) {
    // Inserir venda na tabela de vendas
    $sqlVenda = "INSERT INTO vendas (idCliente, valor_total, data_compra) VALUES ('$idCliente', '$totalVenda', '$dataCompra')";
    if ($conn->query($sqlVenda) === TRUE) {
      // Obter o ID da venda inserida
      $idVenda = $conn->insert_id;
      // Inserir itens da venda na tabela itemVenda
      for ($i = 0; $i < count($_POST['idProduto']); $i++) {
        $produtoId = $_POST['idProduto'][$i];
        $quantidade = $_POST['quantidade'][$i];
        $sqlItemVenda = "INSERT INTO itemVenda (valorUnitario, quantidade, idProdutos, idVendas) VALUES ('$preco', '$quantidade', '$produtoId', '$idVenda')";
        if ($conn->query($sqlItemVenda) !== TRUE) {
          echo "Erro ao registrar o item da compra: " . $conn->error;
        }
        $sql = "UPDATE produtos SET quantidade = quantidade - $quantidade WHERE idProdutos = $produtoId";
        if ($conn->query($sql) === TRUE) {
        }
      }
      // Exibir mensagem de sucesso
      echo "Compra registrada com sucesso!";
    } else {
      // Exibir mensagem de erro
      echo "Erro ao registrar a compra: " . $conn->error;
    }
  }

}

$conn->close();
?>

</body>
<footer>
  <a>&copy; 2024 Floricultura Divina Planta</a>
</footer>

</html>