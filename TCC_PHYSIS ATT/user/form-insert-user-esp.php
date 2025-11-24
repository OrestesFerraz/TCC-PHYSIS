<?php
session_start();
require '../config/authentication.php';
require '../parts/header.php';

// Verificar se usuário já é especialista
if (isset($_SESSION['esp']) && $_SESSION['esp'] == 1) {
    $_SESSION['msg_erro'] = 'Você já é um especialista!';
    header('Location: ../interface/home.php');
    exit;
}

// Verificar se já tem requisição pendente
require '../config/connection.php';
$sql_check = "SELECT status FROM requisicoes_esp WHERE id_usuario = ? AND status = 'pendente'";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->execute([$_SESSION['id_usuario']]);
$requisicao_pendente = $stmt_check->fetch();

if ($requisicao_pendente) {
    header('Location: aguardando-aprovacao.php');
    exit;
}
?>

<script>
    function validarCPF() {
        var cpf = document.getElementById("cpf").value.replace(/[^\d]/g, '');
        var feedback = document.getElementById("cpf-feedback");
        
        if (cpf.length === 11) {
            feedback.innerHTML = '<span class="text-green-600 text-xs">✓ CPF válido</span>';
        } else if (cpf.length > 0) {
            feedback.innerHTML = '<span class="text-red-600 text-xs">CPF deve ter 11 dígitos</span>';
        } else {
            feedback.innerHTML = '';
        }
    }
    
    function formatarTelefone(input) {
        var telefone = input.value.replace(/[^\d]/g, '');
        if (telefone.length <= 11) {
            telefone = telefone.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
            telefone = telefone.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
        }
        input.value = telefone;
    }
</script>

<div class="min-h-screen flex items-center justify-center bg-green-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8">
        <div>
            <div class="flex justify-center">
                <img src="../img/logo.png" alt="Logo" class="w-16 h-16">
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Solicitação para se tornar Especialista
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Preencha os dados abaixo. Sua solicitação será analisada por um administrador.
            </p>
        </div>

        <form class="mt-8 space-y-6" action="insert-user-esp.php" method="POST">
            <div class="bg-white shadow-md rounded-lg p-6 space-y-4">
                
                <div>
                    <label for="profissao" class="block text-sm font-medium text-gray-700">Profissão *</label>
                    <input id="profissao" name="profissao" type="text" required 
                           minlength="3" maxlength="250"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" 
                           placeholder="Ex: Nutricionista, Personal Trainer, Fisioterapeuta"
                           value="<?= htmlspecialchars($_POST['profissao'] ?? '', ENT_QUOTES) ?>">
                </div>
                
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700">Biografia Profissional *</label>
                    <textarea id="bio" name="bio" required 
                              minlength="20" maxlength="250" rows="4"
                              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" 
                              placeholder="Descreva sua experiência e especialização..."><?= htmlspecialchars($_POST['bio'] ?? '', ENT_QUOTES) ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">Mínimo 20 caracteres, máximo 250</p>
                </div>
                
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone *</label>
                    <input id="telefone" name="telefone" type="tel" required 
                           maxlength="15"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" 
                           placeholder="(00) 00000-0000"
                           oninput="formatarTelefone(this)"
                           value="<?= htmlspecialchars($_POST['telefone'] ?? '', ENT_QUOTES) ?>">
                </div>
                
                <div>
                    <label for="cpf" class="block text-sm font-medium text-gray-700">CPF *</label>
                    <input id="cpf" name="cpf" type="text" required 
                           maxlength="14" pattern="\d{11}"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" 
                           placeholder="00000000000"
                           oninput="validarCPF()"
                           value="<?= htmlspecialchars($_POST['cpf'] ?? '', ENT_QUOTES) ?>">
                    <div id="cpf-feedback" class="mt-1"></div>
                    <p class="text-xs text-gray-500 mt-1">Apenas números (11 dígitos)</p>
                </div>
                
                <div>
                    <label for="certificado" class="block text-sm font-medium text-gray-700">Certificado/Diploma (URL) *</label>
                    <input id="certificado" name="certificado" type="url" required 
                           maxlength="500"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" 
                           placeholder="https://exemplo.com/certificado.pdf"
                           value="<?= htmlspecialchars($_POST['certificado'] ?? '', ENT_QUOTES) ?>">
                    <p class="text-xs text-gray-500 mt-1">Link para seu certificado ou diploma profissional</p>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Importante:</strong> Sua solicitação será analisada por nossa equipe. Você receberá uma notificação quando for aprovada ou rejeitada.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                    Enviar Solicitação
                </button>
                <a href="../interface/home.php" class="flex-1 py-3 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition text-center">
                    Cancelar
                </a>
            </div>
        </form>

        <?php
        if (isset($_SESSION["result"])) {
            if ($_SESSION["result"] == false) {
        ?>
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <h4 class="font-bold"><?= $_SESSION["msg_erro"] ?? 'Erro na solicitação' ?></h4>
                    <p><?= $_SESSION["erro"] ?? '' ?></p>
                </div>
        <?php
                unset($_SESSION["msg_erro"]);
                unset($_SESSION["erro"]);
            }
            unset($_SESSION["result"]);
        }
        ?>
    </div>
</div>

<?php require '../parts/footer.php'; ?>