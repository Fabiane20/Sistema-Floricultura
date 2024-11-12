<?php
// Verifica se o usuário está autenticado
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Página Inicial</title>
</head>
<body>
    <h1>Bem-vindo ao seu sistema</h1>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
