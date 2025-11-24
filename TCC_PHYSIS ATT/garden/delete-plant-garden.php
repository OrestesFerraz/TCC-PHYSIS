<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("../login.php");
    die();
}

require '../config/connection.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION["msg_erro"] = "ID não informado";
    redireciona("my-garden.php");
    die();
}

$id_jardim = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_jardim) {
    $_SESSION["msg_erro"] = "ID inválido";
    redireciona("my-garden.php");
    die();
}

// Verificar se o registro pertence ao usuário
$sql_verifica = "SELECT id FROM jardim WHERE id = ? AND id_usuario = ?";
$stmt_verifica = $conn->prepare($sql_verifica);
$stmt_verifica->execute([$id_jardim, id_usuario()]);
$pertence_usuario = $stmt_verifica->fetch();

if (!$pertence_usuario) {
    $_SESSION["msg_erro"] = "Esta planta não pertence ao seu jardim";
    redireciona("my-garden.php");
    die();
}

// Remover do jardim
try {
    $sql_remover = "DELETE FROM jardim WHERE id = ?";
    $stmt_remover = $conn->prepare($sql_remover);
    $stmt_remover->execute([$id_jardim]);
    
    $_SESSION["result"] = true;
    $_SESSION["msg_sucesso"] = "Planta removida do seu jardim com sucesso!";
} catch (Exception $e) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Erro ao remover planta do jardim";
    $_SESSION["erro"] = $e->getMessage();
}

redireciona("my-garden.php");
?>