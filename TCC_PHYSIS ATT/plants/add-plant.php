<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("../index.php");
    die();
}


require '../config/connection.php';
require '../config/validation.php'; // NOVO

// ============================================
// COLETA E VALIDAÇÃO DOS DADOS
// ============================================
$nome = $_POST['nome'] ?? '';
$urlfoto = $_POST['urlfoto'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$especie = $_POST['especie'] ?? '';
$altura = $_POST['altura'] ?? '';
$uso = $_POST['uso'] ?? '';
$solo = $_POST['solo'] ?? '';
$locali = $_POST['locali'] ?? '';
$plantio = $_POST['plantio'] ?? '';
$rega = $_POST['rega'] ?? '';
$adubacao = $_POST['adubacao'] ?? '';
$poda = $_POST['poda'] ?? '';
$dificuldade = $_POST['dificuldade'] ?? '';

$erros = [];

// Validar nome (obrigatório, 3-100 caracteres)
$result_nome = validar_texto($nome, "Nome da planta", 3, 100, true);
if (!$result_nome['valido']) {
    $erros[] = $result_nome['erro'];
} else {
    $nome = $result_nome['valor'];
}

// Validar URL da foto (obrigatória)
$result_url = validar_url($urlfoto, true);
if (!$result_url['valido']) {
    $erros[] = $result_url['erro'];
} else {
    $urlfoto = $result_url['valor'];
}

// Validar espécie (obrigatória, deve ser inteiro válido)
$especie = filter_var($especie, FILTER_VALIDATE_INT);
if ($especie === false || $especie === null || $especie <= 0) {
    $erros[] = "Selecione uma espécie válida.";
}

// Validar descrição (opcional, até 1000 caracteres)
$result_desc = validar_texto($descricao, "Descrição", 0, 1000, false);
if (!$result_desc['valido']) {
    $erros[] = $result_desc['erro'];
} else {
    $descricao = $result_desc['valor'];
}

// Validar altura (opcional, até 50 caracteres)
$result_altura = validar_texto($altura, "Altura", 0, 50, false);
if (!$result_altura['valido']) {
    $erros[] = $result_altura['erro'];
} else {
    $altura = $result_altura['valor'];
}

// Validar uso (opcional, até 500 caracteres)
$result_uso = validar_texto($uso, "Uso", 0, 500, false);
if (!$result_uso['valido']) {
    $erros[] = $result_uso['erro'];
} else {
    $uso = $result_uso['valor'];
}

// Validar solo (opcional, até 500 caracteres)
$result_solo = validar_texto($solo, "Solo", 0, 500, false);
if (!$result_solo['valido']) {
    $erros[] = $result_solo['erro'];
} else {
    $solo = $result_solo['valor'];
}

// Validar localização (opcional, até 500 caracteres)
$result_locali = validar_texto($locali, "Localização", 0, 500, false);
if (!$result_locali['valido']) {
    $erros[] = $result_locali['erro'];
} else {
    $locali = $result_locali['valor'];
}

// Validar plantio (opcional, até 500 caracteres)
$result_plantio = validar_texto($plantio, "Plantio", 0, 500, false);
if (!$result_plantio['valido']) {
    $erros[] = $result_plantio['erro'];
} else {
    $plantio = $result_plantio['valor'];
}

// Validar rega (opcional, até 500 caracteres)
$result_rega = validar_texto($rega, "Rega", 0, 500, false);
if (!$result_rega['valido']) {
    $erros[] = $result_rega['erro'];
} else {
    $rega = $result_rega['valor'];
}

// Validar adubação (opcional, até 500 caracteres)
$result_adubacao = validar_texto($adubacao, "Adubação", 0, 500, false);
if (!$result_adubacao['valido']) {
    $erros[] = $result_adubacao['erro'];
} else {
    $adubacao = $result_adubacao['valor'];
}

// Validar poda (opcional, até 500 caracteres)
$result_poda = validar_texto($poda, "Poda", 0, 500, false);
if (!$result_poda['valido']) {
    $erros[] = $result_poda['erro'];
} else {
    $poda = $result_poda['valor'];
}

// Validar dificuldade (opcional, valores permitidos)
$dificuldadesPermitidas = ['Fácil', 'Médio', 'Difícil'];
if (!empty($dificuldade) && !in_array($dificuldade, $dificuldadesPermitidas)) {
    $dificuldade = null;
}

// Se houver erros, retorna
if (!empty($erros)) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Erro no cadastro da planta";
    $_SESSION["erro"] = implode("<br>", $erros);
    redireciona("form-register-plants.php");
    die();
}

// ============================================
// INSERIR NO BANCO
// ============================================
$sql = "INSERT INTO plantas(nome, urlfoto, descricao, altura, uso, solo, locali, plantio, rega, adubacao, poda, dificuldade, id_especie, id_usuario) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

try {
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        $nome, $urlfoto, $descricao, $altura, $uso, $solo, $locali, 
        $plantio, $rega, $adubacao, $poda, $dificuldade, $especie, id_usuario()
    ]);
    
    if ($result) {
        $_SESSION["result"] = true;
        $_SESSION["msg_sucesso"] = "Planta '$nome' cadastrada com sucesso!";
        
        // Log de sucesso
        error_log("Nova planta cadastrada: '$nome' por usuário ID " . id_usuario());
    } else {
        $_SESSION["result"] = false;
        $_SESSION["msg_erro"] = "Falha ao cadastrar planta.";
    }
} catch (Exception $e) {
    $_SESSION["result"] = false;
    $_SESSION["msg_erro"] = "Falha ao cadastrar planta.";
    
    // Log do erro
    error_log("Erro ao cadastrar planta: " . $e->getMessage());
}

redireciona("form-register-plants.php");
?>