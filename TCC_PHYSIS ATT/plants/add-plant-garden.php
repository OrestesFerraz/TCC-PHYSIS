<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("../login.php");
    die();
}

require '../config/connection.php';

// Verificar se o ID da planta foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION["msg_erro"] = "Planta não selecionada";
    redireciona("../plants/cards-plants.php");
    die();
}

$id_planta = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$id_usuario = id_usuario();

if (!$id_planta) {
    $_SESSION["msg_erro"] = "ID de planta inválido";
    redireciona("../garden/my-garden.php");
    die();
}

// Verificar se a planta existe
$sql_verifica_planta = "SELECT id, nome FROM plantas WHERE id = ?";
$stmt_verifica_planta = $conn->prepare($sql_verifica_planta);
$stmt_verifica_planta->execute([$id_planta]);
$planta = $stmt_verifica_planta->fetch();

if (!$planta) {
    $_SESSION["msg_erro"] = "Planta não encontrada";
    redireciona("../plants/cards-plants.php");
    die();
}

// Verificar se a planta já está no jardim do usuário
$sql_verifica_jardim = "SELECT id FROM jardim WHERE id_planta = ? AND id_usuario = ?";
$stmt_verifica_jardim = $conn->prepare($sql_verifica_jardim);
$stmt_verifica_jardim->execute([$id_planta, $id_usuario]);
$ja_no_jardim = $stmt_verifica_jardim->fetch();

if ($ja_no_jardim) {
    $_SESSION["msg_erro"] = "Esta planta já está no seu jardim!";
    redireciona("../garden/my-garden.php");
    die();
}

// Adicionar ao jardim
try {
    $sql_adicionar = "INSERT INTO jardim (id_planta, id_usuario) VALUES (?, ?)";
    $stmt_adicionar = $conn->prepare($sql_adicionar);
    $stmt_adicionar->execute([$id_planta, $id_usuario]);
    
    $_SESSION["result"] = true;
    $_SESSION["msg_sucesso"] = "Planta '{$planta['nome']}' adicionada ao seu jardim com sucesso!";
} catch (Exception $e) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Erro ao adicionar planta ao jardim";
    $_SESSION["erro"] = $e->getMessage();
}

// Redirecionar de volta para a lista de plantas
redireciona("../garden/my-garden.php");
?>