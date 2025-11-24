<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona();
    die();
}

require '../config/connection.php';
require '../config/validation.php'; // NOVO

// ============================================
// VALIDAÇÃO DOS DADOS
// ============================================
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$nome = $_POST['nome'] ?? '';

// Array para erros
$erros = [];

// Validar ID
if ($id === false || $id === null) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "ID inválido.";
    redireciona("account.php");
    die();
}

// Verificar permissão
if ($id != id_usuario() && !admin()) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Você não tem permissão para editar este usuário.";
    $_SESSION["erro"] = "Operação não autorizada.";
    redireciona("account.php");
    die();
}

// Validar nome
$result_nome = validar_nome($nome);
if (!$result_nome['valido']) {
    $erros[] = $result_nome['erro'];
} else {
    $nome = $result_nome['valor'];
}

// Se houver erros, retorna
if (!empty($erros)) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Dados inválidos";
    $_SESSION["erro"] = implode("<br>", $erros);
    redireciona("account.php");
    die();
}

// ============================================
// ATUALIZAR NO BANCO
// ============================================
$sql = "UPDATE usuarios SET nome = ? WHERE id = ?";

try {
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([$nome, $id]);
    $count = $stmt->rowCount();
    
    if ($result == true && $count >= 1) {
        $_SESSION["result"] = true;
        $_SESSION["msg_sucesso"] = "Dados alterados com sucesso!";
        
        // Atualizar nome na sessão
        if ($id == id_usuario()) {
            $_SESSION["nome"] = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
        }
        
        // Log de sucesso
        error_log("Usuário ID {$id} atualizou seus dados.");
        
    } elseif ($result == true && $count == 0) {
        $_SESSION["result"] = true;
        $_SESSION["msg_erro"] = "Nenhum dado foi alterado.";
        $_SESSION["erro"] = "Os valores informados são idênticos aos anteriores.";
    } else {
        $_SESSION["result"] = false;
        $_SESSION["msg_erro"] = "Falha ao efetuar alteração.";
    }
    
} catch (Exception $e) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Falha ao efetuar alteração.";
    
    // Log do erro
    error_log("Erro ao atualizar usuário ID {$id}: " . $e->getMessage());
}

redireciona("account.php");
?>