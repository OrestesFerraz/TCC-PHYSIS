<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("../user/form-login.php");
    die();
}

require '../config/connection.php';
require '../config/validation.php';

// ============================================
// VALIDAÇÃO DOS DADOS
// ============================================
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$senha_confirmacao = $_POST['senha_confirmacao'] ?? '';

// Validar ID
if ($id === false || $id === null) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "ID inválido.";
    redireciona("account.php");
    die();
}

// Verificar se é o próprio usuário (não permitir admin excluir outros)
if ($id != id_usuario()) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Você só pode excluir sua própria conta.";
    $_SESSION["erro"] = "Operação não autorizada.";
    redireciona("account.php");
    die();
}

// Validar senha
if (empty($senha_confirmacao)) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Senha não fornecida.";
    $_SESSION["erro"] = "É necessário confirmar sua senha para excluir a conta.";
    redireciona("account.php");
    die();
}

// ============================================
// VERIFICAR SE A SENHA ESTÁ CORRETA
// ============================================
if (!verificar_senha_atual($senha_confirmacao, $id, $conn)) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Senha incorreta.";
    $_SESSION["erro"] = "A senha fornecida não está correta. Tente novamente.";
    redireciona("account.php");
    die();
}

// ============================================
// EXCLUIR CONTA
// ============================================
try {
    // Iniciar transação (para garantir integridade)
    $conn->beginTransaction();
    
    // Excluir o usuário
    $sql_usuario = "DELETE FROM usuarios WHERE id = ?";
    $stmt_usuario = $conn->prepare($sql_usuario);
    $result = $stmt_usuario->execute([$id]);
    
    if ($result && $stmt_usuario->rowCount() > 0) {
        // Commit da transação
        $conn->commit();
        
        // Log de exclusão
        error_log("Conta excluída: ID {$id} - " . email_usuario());
        
        // Destruir sessão
        session_unset();
        session_destroy();
        session_start();
        
        $_SESSION["result"] = true;
        $_SESSION["msg_sucesso"] = "Sua conta foi excluída com sucesso. Sentiremos sua falta!";
        
        redireciona("../index.php");
        die();
        
    } else {
        $conn->rollBack();
        $_SESSION["result"] = false;
        $_SESSION["msg_erro"] = "Falha ao excluir conta.";
        $_SESSION["erro"] = "Não foi possível excluir a conta. Tente novamente.";
    }
    
} catch (Exception $e) {
    // Rollback em caso de erro
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Erro ao excluir conta.";
    $_SESSION["erro"] = "Ocorreu um erro inesperado. Tente novamente mais tarde.";
    
    // Log do erro
    error_log("Erro ao excluir usuário ID {$id}: " . $e->getMessage());
}

redireciona("account.php");
?>