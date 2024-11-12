<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="">
        <label for="username">Usuário:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Senha:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
<?php
// Simulação de banco de dados
$usuarios = array(
    "usuario1" => "senha1",
    "usuario2" => "senha2"
);

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Verifica se o usuário existe e a senha está correta
    if (isset($usuarios[$username]) && $usuarios[$username] == $password) {
        // Redireciona para a página de boas-vindas
        header("Location: welcome.php");
        exit;
    } else {
        // Exibe mensagem de erro
        echo "Usuário ou senha inválidos.";
    }
}
?>
