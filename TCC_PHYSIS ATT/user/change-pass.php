<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("../user/form-login.php");
    die();
}

require '../parts/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-green-100">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-6">
                <div class="flex items-center justify-center">
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-white text-center mt-4">Alterar Senha</h1>
                <p class="text-green-100 text-center mt-2">Atualize sua senha de acesso</p>
            </div>

            <!-- Formulário -->
            <div class="px-6 py-8">
                <form action="process-change-pass.php" method="POST" id="passwordForm">
                    <input type="hidden" name="id" value="<?= id_usuario(); ?>">
                    
                    <div class="space-y-6">
                        <div>
                            <label for="senha_atual" class="block text-sm font-medium text-gray-700 mb-2">Senha Atual *</label>
                            <div class="relative">
                                <input type="password" 
                                       id="senha_atual" 
                                       name="senha_atual" 
                                       required
                                       minlength="8"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm pr-10">
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                        onclick="togglePasswordVisibility('senha_atual')">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <label for="nova_senha" class="block text-sm font-medium text-gray-700 mb-2">Nova Senha *</label>
                            <div class="relative">
                                <input type="password" 
                                       id="nova_senha" 
                                       name="nova_senha" 
                                       required
                                       minlength="8"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm pr-10">
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                        onclick="togglePasswordVisibility('nova_senha')">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres</p>
                        </div>
                        
                        <div>
                            <label for="confirmar_senha" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nova Senha *</label>
                            <div class="relative">
                                <input type="password" 
                                       id="confirmar_senha" 
                                       name="confirmar_senha" 
                                       required
                                       minlength="8"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm pr-10">
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                        onclick="togglePasswordVisibility('confirmar_senha')">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-8">
                        <button type="submit" 
                                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 font-medium shadow-md hover:shadow-lg flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Alterar Senha
                        </button>
                        <a href="account.php" 
                           class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium text-center shadow-sm hover:shadow">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Dicas de segurança -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4 shadow-sm">
            <h3 class="text-sm font-semibold text-blue-800 mb-2 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                Dicas para uma senha segura
            </h3>
            <ul class="text-xs text-blue-700 space-y-1">
                <li>• Use pelo menos 8 caracteres</li>
                <li>• Combine letras maiúsculas e minúsculas</li>
                <li>• Inclua números e caracteres especiais</li>
                <li>• Evite informações pessoais óbvias</li>
            </ul>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
    field.setAttribute('type', type);
}

document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const novaSenha = document.getElementById('nova_senha').value;
    const confirmarSenha = document.getElementById('confirmar_senha').value;
    
    if (novaSenha.length < 8) {
        e.preventDefault();
        alert('A nova senha deve ter pelo menos 8 caracteres.');
        document.getElementById('nova_senha').focus();
        return;
    }
    
    if (novaSenha !== confirmarSenha) {
        e.preventDefault();
        alert('As senhas não coincidem. Por favor, verifique.');
        document.getElementById('confirmar_senha').focus();
        return;
    }
})
</script>

<?php
require '../parts/footer.php';
?>


