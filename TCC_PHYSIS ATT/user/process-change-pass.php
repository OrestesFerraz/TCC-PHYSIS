<?php
session_start();
require '../config/authentication.php';
require '../config/connection.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("../user/form-login.php");
    die();
}

// Verificar se o método é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION["result"] = false;
    $_SESSION["erro"] = "Método não permitido.";
    redireciona("change-pass.php");
    die();
}

// Validar dados recebidos
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$senha_atual = $_POST['senha_atual'] ?? '';
$nova_senha = $_POST['nova_senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';

// Verificar se o ID é válido e pertence ao usuário logado
if (!$id || $id != id_usuario()) {
    $_SESSION["result"] = false;
    $_SESSION["erro"] = "ID de usuário inválido.";
    redireciona("change-pass.php");
    die();
}

// Validar campos obrigatórios
if (empty($senha_atual) || empty($nova_senha) || empty($confirmar_senha)) {
    $_SESSION["result"] = false;
    $_SESSION["erro"] = "Todos os campos são obrigatórios.";
    redireciona("change-pass.php");
    die();
}

// Validar comprimento da nova senha
if (strlen($nova_senha) < 8) {
    $_SESSION["result"] = false;
    $_SESSION["erro"] = "A nova senha deve ter pelo menos 8 caracteres.";
    redireciona("change-pass.php");
    die();
}

// Verificar se as senhas coincidem
if ($nova_senha !== $confirmar_senha) {
    $_SESSION["result"] = false;
    $_SESSION["erro"] = "As novas senhas não coincidem.";
    redireciona("change-pass.php");
    die();
}

try {
    // Buscar usuário no banco de dados
    $sql = "SELECT id, senha FROM usuarios WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        $_SESSION["result"] = false;
        $_SESSION["erro"] = "Usuário não encontrado.";
        redireciona("change-pass.php");
        die();
    }
    
    // Verificar senha atual
    if (!password_verify($senha_atual, $usuario['senha'])) {
        $_SESSION["result"] = false;
        $_SESSION["erro"] = "Senha atual incorreta.";
        redireciona("change-pass.php");
        die();
    }
    
    // Verificar se a nova senha é diferente da atual
    if (password_verify($nova_senha, $usuario['senha'])) {
        $_SESSION["result"] = false;
        $_SESSION["erro"] = "A nova senha deve ser diferente da senha atual.";
        redireciona("change-pass.php");
        die();
    }
    
    // Hash da nova senha
    $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    
    // Atualizar senha no banco de dados
    $sql_update = "UPDATE usuarios SET senha = :senha WHERE id = :id";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':senha', $nova_senha_hash);
    $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt_update->execute()) {
        $_SESSION["result"] = true;
        $_SESSION["msg_sucesso"] = "Senha alterada com sucesso!";
        
        // Registrar atividade (opcional)
        registrar_atividade($id, "senha_alterada");
        
    } else {
        $_SESSION["result"] = false;
        $_SESSION["erro"] = "Erro ao atualizar senha no banco de dados.";
    }
    
} catch (PDOException $e) {
    $_SESSION["result"] = false;
    $_SESSION["erro"] = "Erro no banco de dados: " . $e->getMessage();
}

// Redirecionar de volta para a página de alteração de senha
redireciona("change-pass.php");
die();

/**
 * Função para registrar atividade do usuário (opcional)
 */
function registrar_atividade($usuario_id, $acao) {
    // Criar diretório de logs se não existir
    $log_dir = '../logs';
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $log_message = date('Y-m-d H:i:s') . " - Usuário ID: $usuario_id - Ação: $acao" . PHP_EOL;
    error_log($log_message, 3, $log_dir . '/atividades.log');
}
?>