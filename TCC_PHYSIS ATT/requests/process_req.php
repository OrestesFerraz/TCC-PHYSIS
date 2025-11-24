<?php
session_start();
require '../config/authentication.php';
require '../config/connection.php';

// Verificar se o usuário é admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    $_SESSION['msg_erro'] = 'Acesso negado.';
    header('Location: ../interface/home.php');
    exit;
}

$id_requisicao = $_POST['id_requisicao'] ?? null;
$acao = $_POST['acao'] ?? null;
$motivo_rejeicao = trim($_POST['motivo_rejeicao'] ?? '');
$id_admin = $_SESSION['id_usuario'];

if (!$id_requisicao || !$acao) {
    $_SESSION['msg_erro'] = 'Dados inválidos.';
    header('Location: requests.php');
    exit;
}

try {
    // Buscar dados da requisição
    $sql_req = "SELECT * FROM requisicoes_esp WHERE id = ? AND status = 'pendente'";
    $stmt_req = $conn->prepare($sql_req);
    $stmt_req->execute([$id_requisicao]);
    $requisicao = $stmt_req->fetch();

    if (!$requisicao) {
        $_SESSION['msg_erro'] = 'Requisição não encontrada ou já processada.';
        header('Location: requests.php');
        exit;
    }

    $conn->beginTransaction();

    if ($acao === 'aprovar') {
        // ========================================
        // APROVAR REQUISIÇÃO
        // ========================================
        
        // 1. Inserir dados na tabela usuario_esp
        $sql_insert_esp = "INSERT INTO usuario_esp (id_usuario, profissao, bio, telefone, cpf, certificado) 
                          VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert_esp);
        $stmt_insert->execute([
            $requisicao['id_usuario'],
            $requisicao['profissao'],
            $requisicao['bio'],
            $requisicao['telefone'],
            $requisicao['cpf'],
            $requisicao['certificado']
        ]);

        // 2. Atualizar usuário para esp = 1
        $sql_update_user = "UPDATE usuarios SET esp = 1 WHERE id = ?";
        $stmt_update_user = $conn->prepare($sql_update_user);
        $stmt_update_user->execute([$requisicao['id_usuario']]);

        // 3. Atualizar status da requisição
        $sql_update_req = "UPDATE requisicoes_esp 
                          SET status = 'aprovada', 
                              data_resposta = NOW(), 
                              id_admin = ? 
                          WHERE id = ?";
        $stmt_update_req = $conn->prepare($sql_update_req);
        $stmt_update_req->execute([$id_admin, $id_requisicao]);

        $conn->commit();

        // Atualizar sessão se for o próprio usuário logado
        if (isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] == $requisicao['id_usuario']) {
            $_SESSION['esp'] = 1;
        }

        $_SESSION['msg_sucesso'] = 'Requisição aprovada com sucesso! O usuário agora é um especialista.';
        
        // Log de sucesso
        error_log("Requisição #{$id_requisicao} aprovada pelo admin ID {$id_admin}");

    } elseif ($acao === 'rejeitar') {
        // ========================================
        // REJEITAR REQUISIÇÃO
        // ========================================
        
        if (empty($motivo_rejeicao)) {
            $_SESSION['msg_erro'] = 'É necessário informar o motivo da rejeição.';
            header('Location: requests.php');
            exit;
        }

        // Atualizar status da requisição
        $sql_update_req = "UPDATE requisicoes_esp 
                          SET status = 'rejeitada', 
                              data_resposta = NOW(), 
                              id_admin = ?,
                              motivo_rejeicao = ?
                          WHERE id = ?";
        $stmt_update_req = $conn->prepare($sql_update_req);
        $stmt_update_req->execute([$id_admin, $motivo_rejeicao, $id_requisicao]);

        $conn->commit();

        $_SESSION['msg_sucesso'] = 'Requisição rejeitada.';
        
        // Log de rejeição
        error_log("Requisição #{$id_requisicao} rejeitada pelo admin ID {$id_admin}");

    } else {
        $_SESSION['msg_erro'] = 'Ação inválida.';
    }

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    
    $_SESSION['msg_erro'] = 'Erro ao processar requisição.';
    error_log("Erro ao processar requisição #{$id_requisicao}: " . $e->getMessage());
}

header('Location: requests.php');
exit;
?>