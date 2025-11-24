<?php
session_start();
require '../config/authentication.php';
require '../parts/header.php';
?>

<script>

    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function verifica_senhas() {
        var senha = document.getElementById("senha");
        var confsenha = document.getElementById("confsenha");

        if (senha.value && confsenha.value) {
            if (senha.value != confsenha.value) {
                senha.classList.add("border-red-500");
                confsenha.classList.add("border-red-500");
                document.getElementById("senha-error").classList.remove("hidden");
                confsenha.value = "";
            } else {
                senha.classList.remove("border-red-500");
                confsenha.classList.remove("border-red-500");
                document.getElementById("senha-error").classList.add("hidden");
            }
        }
    }
    
    function validar_forca_senha() {
        var senha = document.getElementById("senha").value;
        var feedback = document.getElementById("senha-feedback");
        var requisitos = {
            maiuscula: /[A-Z]/.test(senha),
            minuscula: /[a-z]/.test(senha),
            numero: /[0-9]/.test(senha),
            tamanho: senha.length >= 8
        };
        
        var html = '<ul class="text-xs space-y-1 mt-2">';
        html += '<li class="' + (requisitos.tamanho ? 'text-green-600' : 'text-red-600') + '">✓ Mínimo 8 caracteres</li>';
        html += '<li class="' + (requisitos.maiuscula ? 'text-green-600' : 'text-red-600') + '">✓ Uma letra maiúscula</li>';
        html += '<li class="' + (requisitos.minuscula ? 'text-green-600' : 'text-red-600') + '">✓ Uma letra minúscula</li>';
        html += '<li class="' + (requisitos.numero ? 'text-green-600' : 'text-red-600') + '">✓ Um número</li>';
        html += '</ul>';
        
        feedback.innerHTML = html;
    }

    // Inicializar
    document.addEventListener('DOMContentLoaded', function() {
        updateProgress();
    });
</script>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <i class="fas fa-seedling text-4xl" style="color: var(--verde-primario);"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">
                Junte-se à Comunidade Physis
            </h2>
            <p class="text-gray-600">Crie sua conta em 3 passos simples</p>
        </div>

        <!-- Progress Steps -->
        <div class="flex justify-between mb-8">
            <?php for ($i = 1; $i <= 3; $i++): ?>
                <div class="flex flex-col items-center flex-1">
                    <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300 <?= $i === 1 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' ?>">
                        <?= $i ?>
                    </div>
                    <span class="text-xs mt-2 text-gray-600 font-medium">
                        <?= 
                            $i === 1 ? 'Informações Pessoais' : 
                            ($i === 2 ? 'Foto de Perfil' : 'Segurança')
                        ?>
                    </span>
                </div>
                <?php if ($i < 3): ?>
                    <div class="flex-1 flex items-center">
                        <div class="w-full h-1 bg-gray-200"></div>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form class="space-y-6" action="insert-user.php" method="POST" id="registration-form">
                
                <!-- Step 1: Informações Pessoais -->
                <div id="step-1" class="space-y-6">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">Informações Pessoais</h3>
                        <p class="text-gray-600 mt-2">Comece nos contando sobre você</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-green-600"></i>Nome completo
                            </label>
                            <input id="nome" name="nome" type="text" required 
                                   minlength="3" maxlength="50"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" 
                                   placeholder="Seu nome completo"
                                   value="<?= htmlspecialchars($_POST['nome'] ?? '', ENT_QUOTES) ?>">
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>Mínimo 3, máximo 50 caracteres
                            </p>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-green-600"></i>Email
                            </label>
                            <input id="email" name="email" type="email" required 
                                   maxlength="70"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" 
                                   placeholder="seu@email.com"
                                   value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES) ?>">
                        </div>
                    </div>
                </div>

                <!-- Step 2: Foto de Perfil -->
                <div id="step-2" class="space-y-6 hidden">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">Foto de Perfil</h3>
                        <p class="text-gray-600 mt-2">Escolha uma foto para seu perfil</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="urlperfil" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-camera mr-2 text-green-600"></i>URL da Foto de Perfil
                            </label>
                            <input id="urlperfil" name="urlperfil" type="url" required 
                                   maxlength="500"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" 
                                   placeholder="https://exemplo.com/foto.jpg"
                                   value="<?= htmlspecialchars($_POST['urlperfil'] ?? '', ENT_QUOTES) ?>">
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-link mr-1"></i>URL completa da imagem (ex: https://...)
                            </p>
                        </div>

                        <!-- Preview da imagem -->
                        <div class="mt-4 text-center">
                            <div id="image-preview" class="w-32 h-32 mx-auto bg-gray-100 rounded-full flex items-center justify-center border-2 border-dashed border-gray-300">
                                <i class="fas fa-user text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Preview da sua foto de perfil</p>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Segurança -->
                <div id="step-3" class="space-y-6 hidden">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">Segurança</h3>
                        <p class="text-gray-600 mt-2">Crie uma senha segura</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="senha" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-green-600"></i>Senha
                            </label>
                            <input id="senha" name="senha" type="password" required 
                                   minlength="8" maxlength="50"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"  
                                   placeholder="Sua senha"
                                   oninput="validar_forca_senha()">
                            <div id="senha-feedback" class="mt-3 p-3 bg-gray-50 rounded-lg"></div>
                        </div>
                        
                        <div>
                            <label for="confsenha" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-green-600"></i>Confirmar senha
                            </label>
                            <input id="confsenha" name="confsenha" type="password" required 
                                   minlength="8" maxlength="50"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" 
                                   placeholder="Confirme sua senha" 
                                   onblur="verifica_senhas();">
                            <div id="senha-error" class="hidden text-red-600 text-sm mt-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>As senhas não coincidem.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <button type="button" id="prev-btn" 
                            onclick="prevStep(currentStep - 1)"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar
                    </button>

                    <button type="button" id="next-btn" 
                            onclick="nextStep(currentStep + 1)"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                        Continuar<i class="fas fa-arrow-right ml-2"></i>
                    </button>

                    <button type="submit" id="submit-btn" 
                            class="hidden px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                        <i class="fas fa-check mr-2"></i>Finalizar Cadastro
                    </button>
                </div>

                <div class="text-center pt-4 border-t border-gray-200">
                    <a href="form-login.php" class="text-green-600 hover:text-green-500 transition font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Já tem uma conta? Faça login
                    </a>
                </div>
            </form>

            <?php
            if (isset($_SESSION["result"])) {
                if ($_SESSION["result"] == false) {
            ?>
                    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>
                            <div>
                                <h4 class="font-semibold text-red-800"><?= $_SESSION["msg_erro"] ?? 'Erro no cadastro' ?></h4>
                                <p class="text-red-600 text-sm mt-1"><?= $_SESSION["erro"] ?? '' ?></p>
                            </div>
                        </div>
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
</div>

<script>
    // Preview da imagem em tempo real
    document.getElementById('urlperfil').addEventListener('input', function() {
        const preview = document.getElementById('image-preview');
        const url = this.value;
        
        if (url) {
            preview.innerHTML = `<img src="${url}" alt="Preview" class="w-full h-full rounded-full object-cover" onerror="this.style.display='none'; preview.innerHTML='<i class=\\'fas fa-user text-gray-400 text-2xl\\'></i>'">`;
        } else {
            preview.innerHTML = '<i class="fas fa-user text-gray-400 text-2xl"></i>';
        }
    });
</script>

<?php require '../parts/footer.php'; ?>