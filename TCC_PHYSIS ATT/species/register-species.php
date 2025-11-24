<?php
session_start();
require("../config/authentication.php");

/** Tratamento de permissões  */
if (!autenticado() || !admin()) {
    $_SESSION["restrito"] = true;
    redireciona("../index.php");
    die();
}
/** Tratamento de permissões  */

require "../config/connection.php";

$nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS);
$descricao = filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_SPECIAL_CHARS);

$sql = "insert into especies (nome, descricao)
        values (?, ?)";

try {
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([$nome, $descricao]);
} catch (Exception $e) {
    $result = false;
    $error = $e->getMessage();
}

if ($result == true) {
    $_SESSION["result"] = true;
    $_SESSION["msg_sucesso"] = "Dados gravados com sucesso!";
} else {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Falha ao efetuar gravação.";
    $_SESSION["erro"] = $error;
}
redireciona("form-register-species.php");
