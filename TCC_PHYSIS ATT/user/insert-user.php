<?php
session_start();
require '../config/authentication.php';
require '../config/connection.php';
require '../config/validation.php';

// Pegar dados do POST
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$urlperfil = $_POST['urlperfil'] ?? '';
$senha = $_POST['senha'] ?? '';
$confsenha = $_POST['confsenha'] ?? '';

// Array para armazenar erros
$erros = [];

// ============================================
// VALIDAÇÕES USANDO FUNÇÕES CENTRALIZADAS
// ============================================

// Validar nome
$result_nome = validar_nome($nome);
if (!$result_nome['valido']) {
    $erros[] = $result_nome['erro'];
} else {
    $nome = $result_nome['valor'];
}

// Validar email
$result_email = validar_email($email);
if (!$result_email['valido']) {
    $erros[] = $result_email['erro'];
} else {
    $email = $result_email['valor'];
}

// Validar URL do perfil (obrigatória)
$result_url = validar_url($urlperfil, true);
if (!$result_url['valido']) {
    $erros[] = $result_url['erro'];
} else {
    $urlperfil = $result_url['valor'];
}

// Validar senha
$result_senha = validar_senha($senha, true);
if (!$result_senha['valido']) {
    $erros[] = $result_senha['erro'];
}

// Validar confirmação de senha
if ($senha !== $confsenha) {
    $erros[] = "As senhas não coincidem.";
}

// Se houver erros, retorna
if (!empty($erros)) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Erro no cadastro";
    $_SESSION["erro"] = implode("<br>", $erros);
    redireciona("form-insert-user.php");
    die();
}

// ============================================
// VERIFICAR SE EMAIL JÁ EXISTE
// ============================================
$sql_check = "SELECT id FROM usuarios WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->execute([$email]);

if ($stmt_check->fetch()) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Erro no cadastro";
    $_SESSION["erro"] = "Este email já está cadastrado. <a href='form-login.php' class='underline'>Fazer login</a>";
    redireciona("form-insert-user.php");
    die();
}

// ============================================
// CRIAR HASH DA SENHA
// ============================================
if (defined('PASSWORD_ARGON2ID')) {
    $senha_hash = password_hash($senha, PASSWORD_ARGON2ID);
} else {
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
}

// ============================================
// INSERIR NO BANCO
// ============================================
$sql = "INSERT INTO usuarios(nome, email, urlperfil, senha) VALUES (?, ?, ?, ?)";

try {
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([$nome, $email, $urlperfil, $senha_hash]);
    
    if ($result == true) {
        // Buscar dados do usuário recém-criado
        $sql_select = "SELECT id, nome, urlperfil, admin FROM usuarios WHERE email = ?";
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->execute([$email]);
        $usuario = $stmt_select->fetch();
        
        // Regenera session ID antes de login automático
        session_regenerate_id(true);
        
        // Login automático (com escape HTML)
        $_SESSION["email"] = $email;
        $_SESSION["nome"] = htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8');
        $_SESSION["urlperfil"] = htmlspecialchars($usuario['urlperfil'], ENT_QUOTES, 'UTF-8');
        $_SESSION["id_usuario"] = (int)$usuario['id'];
        $_SESSION["admin"] = (bool)$usuario['admin'];
        
        $_SESSION["result"] = true;
        $_SESSION["msg_sucesso"] = "Cadastro realizado com sucesso! Você está logado.";
        
        // Log de sucesso
        error_log("Novo usuário cadastrado: ID {$usuario['id']} - {$email}");
        
        redireciona("../interface/home.php");
        die();
    }
    
} catch (Exception $e) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Erro ao processar cadastro.";
    $_SESSION["erro"] = $e;
    
    // Log do erro real (não mostrar ao usuário)
    error_log("Erro no cadastro: " . $e->getMessage());
    
    redireciona("form-insert-user.php");
    die();
}
?>

PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column 'data_cadastro' in 'field list' in C:\xampp\htdocs\TCC_PHYSIS\user\insert-user.php:96 Stack trace: #0 C:\xampp\htdocs\TCC_PHYSIS\user\insert-user.php(96): PDOStatement->execute(Array) #1 {main}n C:\xampp\htdocs\TCC_PHYSIS\user\insert-user.php:96 Stack trace: #0 C:\xampp\htdocs\TCC_PHYSIS\user\insert-user.php(96): PDOStatement->execute(Array) #1 {main}
