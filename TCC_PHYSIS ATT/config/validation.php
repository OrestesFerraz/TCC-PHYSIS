<?php
/**
 * Funções de validação centralizadas
 * Salvar em: config/validation.php
 */

// Validar nome (mínimo 3, máximo 10 caracteres)
function validar_nome($nome) {
    $nome = trim($nome);
    if (empty($nome)) {
        return ["valido" => false, "erro" => "Nome é obrigatório."];
    }
    if (strlen($nome) < 3) {
        return ["valido" => false, "erro" => "Nome deve ter no mínimo 3 caracteres."];
    }
    if (strlen($nome) > 50) {
        return ["valido" => false, "erro" => "Nome deve ter no máximo 50 caracteres."];
    }
    return ["valido" => true, "valor" => $nome];
}

// Validar email
function validar_email($email) {
    $email = trim($email);
    if (empty($email)) {
        return ["valido" => false, "erro" => "Email é obrigatório."];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ["valido" => false, "erro" => "Email inválido."];
    }
    if (strlen($email) > 70) {
        return ["valido" => false, "erro" => "Email muito longo."];
    }
    return ["valido" => true, "valor" => strtolower($email)];
}

// Validar URL (com opção de campo opcional)
function validar_url($url, $obrigatorio = true) {
    $url = trim($url);
    
    if (empty($url)) {
        if ($obrigatorio) {
            return ["valido" => false, "erro" => "URL é obrigatória."];
        }
        return ["valido" => true, "valor" => null];
    }
    
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return ["valido" => false, "erro" => "URL inválida."];
    }
    
    if (strlen($url) > 500) {
        return ["valido" => false, "erro" => "URL muito longa."];
    }
    
    return ["valido" => true, "valor" => $url];
}

// Validar senha
function validar_senha($senha, $verificar_complexidade = true) {
    if (empty($senha)) {
        return ["valido" => false, "erro" => "Senha é obrigatória."];
    }
    
    if (strlen($senha) < 8) {
        return ["valido" => false, "erro" => "Senha deve ter no mínimo 8 caracteres."];
    }
    
    if (strlen($senha) > 50) { // Limite do bcrypt
        return ["valido" => false, "erro" => "Senha muito longa (máximo 50 caracteres)."];
    }
    
    if ($verificar_complexidade) {
        if (!preg_match('/[A-Z]/', $senha)) {
            return ["valido" => false, "erro" => "Senha deve conter pelo menos uma letra maiúscula."];
        }
        if (!preg_match('/[a-z]/', $senha)) {
            return ["valido" => false, "erro" => "Senha deve conter pelo menos uma letra minúscula."];
        }
        if (!preg_match('/[0-9]/', $senha)) {
            return ["valido" => false, "erro" => "Senha deve conter pelo menos um número."];
        }
    }
    
    return ["valido" => true, "valor" => $senha];
}

// Validar campo de texto genérico
function validar_texto($texto, $nome_campo, $min = 0, $max = 5000, $obrigatorio = false) {
    $texto = trim($texto);
    
    if (empty($texto)) {
        if ($obrigatorio) {
            return ["valido" => false, "erro" => "$nome_campo é obrigatório."];
        }
        return ["valido" => true, "valor" => null];
    }
    
    if (strlen($texto) < $min) {
        return ["valido" => false, "erro" => "$nome_campo deve ter no mínimo $min caracteres."];
    }
    
    if (strlen($texto) > $max) {
        return ["valido" => false, "erro" => "$nome_campo deve ter no máximo $max caracteres."];
    }
    
    return ["valido" => true, "valor" => $texto];
}

// Verificar se senha atual está correta
function verificar_senha_atual($senha_digitada, $id_usuario, $conn) {
    $sql = "SELECT senha FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        return false;
    }
    
    return password_verify($senha_digitada, $usuario['senha']);
}
?>