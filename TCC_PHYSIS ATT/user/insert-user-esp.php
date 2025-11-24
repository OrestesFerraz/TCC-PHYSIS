<?php
session_start();
require '../config/authentication.php';
require '../config/connection.php';

// Verificar se usuário já é especialista
if (isset($_SESSION['esp']) && $_SESSION['esp'] == 1) {
    $_SESSION['msg_erro'] = 'Você já é um especialista!';
    header('Location: ../interface/home.php');
    exit;
}

// Verificar se já tem requisição pendente
$sql_check = "SELECT id FROM requisicoes_esp WHERE id_usuario = ? AND status = 'pendente'";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->execute([$_SESSION['id_usuario']]);

if ($stmt_check->fetch()) {
    $_SESSION['msg_erro'] = 'Você já possui uma solicitação pendente.';
    header('Location:  ../requests/waiting_accept.php');
    exit;
}

// Pegar dados do POST
$profissao = trim($_POST['profissao'] ?? '');
$bio = trim($_POST['bio'] ?? '');
$telefone = $_POST['telefone'] ?? '';
$cpf = preg_replace('/[^\d]/', '', $_POST['cpf'] ?? ''); // Remove formatação
$certificado = trim($_POST['certificado'] ?? '');
$id_usuario = $_SESSION['id_usuario'];

// Array para armazenar erros
$erros = [];

// ============================================
// VALIDAÇÕES
// ============================================

// Validar profissão
if (empty($profissao) || strlen($profissao) < 3 || strlen($profissao) > 250) {
    $erros[] = "A profissão deve ter entre 3 e 250 caracteres.";
}

// Validar bio
if (empty($bio) || strlen($bio) < 20 || strlen($bio) > 250) {
    $erros[] = "A biografia deve ter entre 20 e 250 caracteres.";
}

// Validar telefone
$telefone_limpo = preg_replace('/[^\d]/', '', $telefone);
if (empty($telefone_limpo) || strlen($telefone_limpo) < 10 || strlen($telefone_limpo) > 11) {
    $erros[] = "Telefone inválido. Use o formato (00) 00000-0000.";
}

// Validar CPF
if (empty($cpf) || strlen($cpf) != 11) {
    $erros[] = "CPF inválido. Deve conter 11 dígitos.";
}

// Validar URL do certificado
if (empty($certificado) || !filter_var($certificado, FILTER_VALIDATE_URL)) {
    $erros[] = "URL do certificado inválida.";
}

// Se houver erros, retorna
if (!empty($erros)) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Erro na solicitação";
    $_SESSION["erro"] = implode("<br>", $erros);
    header('Location: form-insert-user-esp.php');
    exit;
}

// ============================================
// VERIFICAR SE CPF JÁ ESTÁ CADASTRADO
// ============================================
$sql_cpf_check = "SELECT id_usuario FROM usuario_esp WHERE cpf = ?";
$stmt_cpf = $conn->prepare($sql_cpf_check);
$stmt_cpf->execute([$cpf]);

if ($stmt_cpf->fetch()) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Erro na solicitação";
    $_SESSION["erro"] = "Este CPF já está cadastrado no sistema.";
    header('Location: form-insert-user-esp.php');
    exit;
}

// Verificar também nas requisições pendentes
$sql_cpf_req = "SELECT id FROM requisicoes_esp WHERE cpf = ? AND status = 'pendente'";
$stmt_cpf_req = $conn->prepare($sql_cpf_req);
$stmt_cpf_req->execute([$cpf]);

if ($stmt_cpf_req->fetch()) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Erro na solicitação";
    $_SESSION["erro"] = "Já existe uma solicitação pendente com este CPF.";
    header('Location: form-insert-user-esp.php');
    exit;
}

// ============================================
// INSERIR REQUISIÇÃO NO BANCO
// ============================================
$sql = "INSERT INTO requisicoes_esp (id_usuario, profissao, bio, telefone, cpf, certificado, status) 
        VALUES (?, ?, ?, ?, ?, ?, 'pendente')";

try {
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        $id_usuario,
        $profissao,
        $bio,
        $telefone,
        $cpf,
        $certificado
    ]);
    
    if ($result) {
        $_SESSION["result"] = true;
        $_SESSION["msg_sucesso"] = "Solicitação enviada com sucesso!";
        
        // Log de sucesso
        error_log("Nova requisição de especialista: Usuário ID {$id_usuario}");
        
        header('Location: ../requests/waiting_accept.php');
        exit;
    }
    
} catch (Exception $e) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Erro ao processar solicitação.";
    $_SESSION["erro"] = "Ocorreu um erro inesperado. Tente novamente mais tarde.";
    
    // Log do erro real (não mostrar ao usuário)
    error_log("Erro na requisição de especialista: " . $e->getMessage());
    
    header('Location: form-insert-user-esp.php');
    exit;
}
?>