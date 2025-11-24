<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona();
    die();
}

require '../config/connection.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
$sql = "DELETE FROM plantas WHERE id = ?";

try {
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([$id]);
    $count = $stmt->rowCount();
} catch (Exception $e) {
    $result = false;
    $count = 0;
    $error = $e->getMessage();
}

?>

<?php
if ($result == true && $count >= 1) {
    $_SESSION["result"] = $result;
    $_SESSION["msg_sucesso"] = "Registro excluído com sucesso!";
} elseif ($result == true && $count == 0) {
    $_SESSION["result"] = $result;
    $_SESSION["msg_erro"] = "Falha ao efetuar exclusão.";
    $_SESSION["erro"] = "Não foi encontrado nenhum registro com o ID = $id";
} else {
    $_SESSION["result"] = $result;
    $_SESSION["msg_erro"] = "Falha ao efetuar exclusão.";
    $_SESSION["erro"] = $error;
}

if (admin()) {
    redireciona("../admin/list-plants.php");
} else {
    redireciona("../garden/garden.php");
}
?>