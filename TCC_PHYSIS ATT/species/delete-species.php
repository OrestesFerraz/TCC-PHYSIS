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

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
 
// echo "<p>ID: $id</p>";

$sql = "delete from especies where id = ?";

try {
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([$id]);
} catch (Exception $e) {
    $result = false;
    $error = $e->getMessage();
}

$count = $stmt->rowCount();

if ($result == true && $count >= 1) {   
    $_SESSION["result"] = true; 
    $_SESSION["msg_sucesso"] = "Categoria excluída com sucesso!";
} else {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Falha ao efetuar exclusão.";
    $_SESSION["erro"] = $error;
}
redireciona("form-register-species.php");