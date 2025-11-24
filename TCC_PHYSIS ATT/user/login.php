<?php
session_start();
require '../config/authentication.php';
require '../config/connection.php';

if (autenticado()) {
    redireciona("../index.php");
    die();
}

$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$senha = filter_input(INPUT_POST, "senha", FILTER_SANITIZE_SPECIAL_CHARS);

$sql = "SELECT id, nome, urlperfil, senha, admin FROM usuarios WHERE email = ?";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
} catch (Exception $e) {
    $result = false;
    $error = $e->getMessage();
}

$row = $stmt->fetch();

if (password_verify($senha, $row['senha'])) {
    $_SESSION["email"] = $email;
    $_SESSION["nome"] = $row['nome'];
    $_SESSION["urlperfil"] = $row['urlperfil'];
    $_SESSION["id_usuario"] = $row['id'];
    $_SESSION["admin"] = $row['admin'];

    $_SESSION["result_login"] = true;

    redireciona("../interface/home.php");
} else {
    unset($_SESSION["email"]);
    unset($_SESSION["id_usuario"]);
    unset($_SESSION["nome"]);
    unset($_SESSION["urlperfil"]);
    unset($_SESSION["admin"]);

    $_SESSION["result_login"] = false;
    $_SESSION["erro"] = "Usuário ou senha incorretos.";

    redireciona("form-login.php");
}
?>