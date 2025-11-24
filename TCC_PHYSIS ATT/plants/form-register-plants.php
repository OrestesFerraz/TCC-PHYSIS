<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("../user/form-login.php");
    die();
}

require '../config/connection.php';

// Buscar espécies do banco de dados
$sqlCat = "SELECT id, nome FROM especies ORDER BY nome";
$stmtCat = $conn->query($sqlCat);
$especies = $stmtCat->fetchAll();

require '../parts/header.php';
?>

<!-- Formulário de Plantas em Wizard -->
<section class="py-12 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-6">
      
        <!-- Cabeçalho -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Cadastrar Planta</h1>
            <p class="text-gray-600 dark:text-gray-400">Adicione novas plantas ao seu jardim virtual</p>
        </div>

        <!-- Mensagens de Feedback -->
        <?php if (isset($_SESSION["result"])): ?>
            <div class="mb-6 p-4 rounded-lg <?= $_SESSION["result"] ? 'bg-green-100 border-green-400 text-green-700 dark:bg-green-800 dark:border-green-600 dark:text-green-100' : 'bg-red-100 border-red-400 text-red-700 dark:bg-red-800 dark:border-red-600 dark:text-red-100' ?> border">
                <h4 class="font-semibold"><?= $_SESSION["result"] ? $_SESSION["msg_sucesso"] : $_SESSION["msg_erro"] ?></h4>
                <?php if (!$_SESSION["result"] && isset($_SESSION["erro"])): ?>
                    <p class="mt-1 text-sm"><?= $_SESSION["erro"] ?></p>
                <?php endif; ?>
            </div>
            <?php
            // Limpar mensagens da sessão
            unset($_SESSION["result"]);
            unset($_SESSION["msg_sucesso"]);
            unset($_SESSION["msg_erro"]);
            unset($_SESSION["erro"]);
            ?>
        <?php endif; ?>

        <!-- Wizard Navigation -->
        <div class="mb-8">
            <div class="flex justify-center">
                <div class="w-full max-w-2xl">
                    <div class="flex items-center">
                        <!-- Step 1 -->
                        <div class="flex items-center relative">
                            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold step-indicator active" data-step="1">
                                1
                            </div>
                            <div class="ml-3 text-sm font-medium text-green-600 step-title">Informações Básicas</div>
                        </div>
                        
                        <!-- Connector -->
                        <div class="flex-1 h-1 bg-gray-300 mx-4"></div>
                        
                        <!-- Step 2 -->
                        <div class="flex items-center relative">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold step-indicator" data-step="2">
                                2
                            </div>
                            <div class="ml-3 text-sm font-medium text-gray-500 step-title">Características</div>
                        </div>
                        
                        <!-- Connector -->
                        <div class="flex-1 h-1 bg-gray-300 mx-4"></div>
                        
                        <!-- Step 3 -->
                        <div class="flex items-center relative">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold step-indicator" data-step="3">
                                3
                            </div>
                            <div class="ml-3 text-sm font-medium text-gray-500 step-title">Cuidados</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulário -->
        <form action="add-plant.php" method="POST" class="bg-green-50 dark:bg-gray-800 rounded-2xl p-8 shadow-lg" id="plantForm">
            
            <!-- Step 1: Informações Básicas -->
            <div class="step-content active" data-step="1">
                <div class="space-y-6">
                    
                    <!-- Nome da Planta -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nome da Planta *
                        </label>
                        <input type="text" id="nome" name="nome" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors"
                               placeholder="Ex: Rosa Vermelha, Suculenta Echeveria"
                               value="<?= isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '' ?>"
                               maxlength="100">
                    </div>

                    <!-- Espécie -->
                    <div>
                        <label for="especie" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Espécie *
                        </label>
                        <select id="especie" name="especie" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                            <option value="">[Escolha qual a espécie da planta]</option>
                            <?php foreach ($especies as $especie): ?>
                                <option value="<?= $especie['id'] ?>" 
                                        <?= (isset($_POST['especie']) && $_POST['especie'] == $especie['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($especie['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- URL da Foto -->
                    <div>
                        <label for="urlfoto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            URL da Foto *
                        </label>
                        <input type="url" id="urlfoto" name="urlfoto" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors"
                               placeholder="https://exemplo.com/foto-da-planta.jpg"
                               value="<?= isset($_POST['urlfoto']) ? htmlspecialchars($_POST['urlfoto']) : '' ?>"
                               onchange="updatePreview()">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Endereço http de uma imagem da internet
                        </p>
                    </div>

                    <!-- Descrição Detalhada -->
                    <div>
                        <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Descrição Detalhada
                        </label>
                        <textarea id="descricao" name="descricao" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical"
                                  placeholder="Forneça uma descrição detalhada sobre a planta..."
                                  oninput="updatePreview()"
                                  maxlength="1000"><?= isset($_POST['descricao']) ? htmlspecialchars($_POST['descricao']) : '' ?></textarea>
                        <div class="text-right text-sm text-gray-500 dark:text-gray-400 mt-1">
                            <span id="descricao-counter"><?= isset($_POST['descricao']) ? strlen($_POST['descricao']) : 0 ?></span>/1000 caracteres
                        </div>
                    </div>

                </div>
            </div>

            <!-- Step 2: Características -->
            <div class="step-content hidden" data-step="2">
                <div class="space-y-6">
                    
                    <!-- Altura -->
                    <div>
                        <label for="altura" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Altura (cm)
                        </label>
                        <input type="number" id="altura" name="altura" min="0" step="0.1"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors"
                               placeholder="Ex: 50"
                               value="<?= isset($_POST['altura']) ? htmlspecialchars($_POST['altura']) : '' ?>">
                    </div>

                    <!-- Uso -->
                    <div>
                        <label for="uso" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Uso
                        </label>
                        <textarea id="uso" name="uso" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical"
                                  placeholder="Descreva os usos da planta (ornamental, medicinal, culinário, etc)..."
                                  maxlength="500"><?= isset($_POST['uso']) ? htmlspecialchars($_POST['uso']) : '' ?></textarea>
                    </div>

                    <!-- Solo -->
                    <div>
                        <label for="solo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipo de Solo
                        </label>
                        <textarea id="solo" name="solo" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical"
                                  placeholder="Descreva o tipo de solo ideal..."
                                  maxlength="500"><?= isset($_POST['solo']) ? htmlspecialchars($_POST['solo']) : '' ?></textarea>
                    </div>

                    <!-- Localização -->
                    <div>
                        <label for="locali" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Localização Ideal
                        </label>
                        <textarea id="locali" name="locali" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical"
                                  placeholder="Descreva a localização ideal (sol pleno, meia-sombra, sombra, etc)..."
                                  maxlength="500"><?= isset($_POST['locali']) ? htmlspecialchars($_POST['locali']) : '' ?></textarea>
                    </div>

                    <!-- Dificuldade -->
                    <div>
                        <label for="dificuldade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Dificuldade de Cultivo
                        </label>
                        <select id="dificuldade" name="dificuldade"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                            <option value="">Selecione a dificuldade</option>
                            <option value="Fácil" <?= (isset($_POST['dificuldade']) && $_POST['dificuldade'] == 'Fácil') ? 'selected' : '' ?>>Fácil</option>
                            <option value="Médio" <?= (isset($_POST['dificuldade']) && $_POST['dificuldade'] == 'Médio') ? 'selected' : '' ?>>Médio</option>
                            <option value="Difícil" <?= (isset($_POST['dificuldade']) && $_POST['dificuldade'] == 'Difícil') ? 'selected' : '' ?>>Difícil</option>
                        </select>
                    </div>

                </div>
            </div>

            <!-- Step 3: Cuidados -->
            <div class="step-content hidden" data-step="3">
                <div class="space-y-6">
                    
                    <!-- Plantio -->
                    <div>
                        <label for="plantio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Forma de Plantio
                        </label>
                        <textarea id="plantio" name="plantio" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical"
                                  placeholder="Descreva como realizar o plantio..."
                                  maxlength="500"><?= isset($_POST['plantio']) ? htmlspecialchars($_POST['plantio']) : '' ?></textarea>
                    </div>

                    <!-- Rega -->
                    <div>
                        <label for="rega" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Rega
                        </label>
                        <textarea id="rega" name="rega" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical"
                                  placeholder="Descreva a frequência e forma de rega..."
                                  maxlength="500"><?= isset($_POST['rega']) ? htmlspecialchars($_POST['rega']) : '' ?></textarea>
                    </div>

                    <!-- Adubação -->
                    <div>
                        <label for="adubacao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Adubação
                        </label>
                        <textarea id="adubacao" name="adubacao" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical"
                                  placeholder="Descreva a adubação recomendada..."
                                  maxlength="500"><?= isset($_POST['adubacao']) ? htmlspecialchars($_POST['adubacao']) : '' ?></textarea>
                    </div>

                    <!-- Poda -->
                    <div>
                        <label for="poda" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Poda
                        </label>
                        <textarea id="poda" name="poda" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical"
                                  placeholder="Descreva como e quando podar..."
                                  maxlength="500"><?= isset($_POST['poda']) ? htmlspecialchars($_POST['poda']) : '' ?></textarea>
                    </div>

                </div>
            </div>

            <!-- Botões de Navegação -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-8 pt-6 border-t border-gray-300 dark:border-gray-600">
                <button type="button" id="prevBtn" class="hidden px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition font-medium w-full sm:w-auto">
                    ← Anterior
                </button>
                
                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                    <button type="button" id="nextBtn" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium w-full sm:w-auto">
                        Próximo →
                    </button>
                    
                    <button type="submit" id="submitBtn" class="hidden px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium w-full sm:w-auto">
                        Cadastrar Planta
                    </button>
                    
                    <button type="reset" 
                            class="px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition font-medium w-full sm:w-auto"
                            onclick="clearPreview()">
                        Limpar
                    </button>
                </div>
            </div>
        </form>

        <!-- Preview da Planta -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Preview da Planta</h2>
            
            <div class="bg-green-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <img id="previewImage" src="" alt="Preview da planta" 
                             class="w-full h-64 object-cover rounded-lg border-2 border-gray-300 dark:border-gray-600 hidden">
                        <div id="noPreview" class="w-full h-64 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                            <span class="text-gray-500 dark:text-gray-400">Nenhuma imagem selecionada</span>
                        </div>
                    </div>
                    <div>
                        <h3 id="previewNome" class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            <?= isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : 'Nome da Planta' ?>
                        </h3>
                        <p id="previewDescricao" class="text-gray-600 dark:text-gray-300 mb-4">
                            <?= isset($_POST['descricao']) ? htmlspecialchars($_POST['descricao']) : 'Descrição aparecerá aqui...' ?>
                        </p>
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Cuidados:</h4>
                            <p id="previewCuidados" class="text-gray-600 dark:text-gray-300 text-sm">
                                <?= isset($_POST['rega']) ? htmlspecialchars($_POST['rega']) : 'Instruções de cultivo aparecerão aqui...' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Wizard functionality
let currentStep = 1;
const totalSteps = 3;

function showStep(step) {
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(content => {
        content.classList.add('hidden');
        content.classList.remove('active');
    });
    
    // Show current step
    const currentStepElement = document.querySelector(`.step-content[data-step="${step}"]`);
    if (currentStepElement) {
        currentStepElement.classList.remove('hidden');
        currentStepElement.classList.add('active');
    }
    
    // Update navigation indicators
    document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
        const stepNumber = index + 1;
        const titleElement = document.querySelectorAll('.step-title')[index];
        
        if (stepNumber <= step) {
            indicator.classList.remove('bg-gray-300', 'text-gray-600');
            indicator.classList.add('bg-green-600', 'text-white');
            if (titleElement) {
                titleElement.classList.remove('text-gray-500');
                titleElement.classList.add('text-green-600');
            }
        } else {
            indicator.classList.remove('bg-green-600', 'text-white');
            indicator.classList.add('bg-gray-300', 'text-gray-600');
            if (titleElement) {
                titleElement.classList.remove('text-green-600');
                titleElement.classList.add('text-gray-500');
            }
        }
    });
    
    // Update buttons
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    if (prevBtn) prevBtn.classList.toggle('hidden', step === 1);
    if (nextBtn) nextBtn.classList.toggle('hidden', step === totalSteps);
    if (submitBtn) submitBtn.classList.toggle('hidden', step !== totalSteps);
}

function nextStep() {
    if (currentStep < totalSteps) {
        // Validate current step before proceeding
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
}

function validateStep(step) {
    let isValid = true;
    
    switch(step) {
        case 1:
            const nome = document.getElementById('nome');
            const especie = document.getElementById('especie');
            const urlfoto = document.getElementById('urlfoto');
            
            if (!nome.value.trim()) {
                alert('Por favor, preencha o nome da planta.');
                nome.focus();
                isValid = false;
            } else if (!especie.value) {
                alert('Por favor, selecione uma espécie.');
                especie.focus();
                isValid = false;
            } else if (!urlfoto.value.trim()) {
                alert('Por favor, forneça a URL da foto.');
                urlfoto.focus();
                isValid = false;
            }
            break;
    }
    
    return isValid;
}

// Initialize wizard
document.addEventListener('DOMContentLoaded', function() {
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    
    if (nextBtn) nextBtn.addEventListener('click', nextStep);
    if (prevBtn) prevBtn.addEventListener('click', prevStep);
    
    // Contador de caracteres para descrição
    const descricaoTextarea = document.getElementById('descricao');
    const descricaoCounter = document.getElementById('descricao-counter');
    
    if (descricaoTextarea && descricaoCounter) {
        descricaoTextarea.addEventListener('input', function() {
            descricaoCounter.textContent = this.value.length;
        });
    }
});

// Preview functionality
function updatePreview() {
    const nome = document.getElementById('nome').value;
    const descricao = document.getElementById('descricao').value;
    const urlfoto = document.getElementById('urlfoto').value;
    const rega = document.getElementById('rega') ? document.getElementById('rega').value : '';
    
    const previewNome = document.getElementById('previewNome');
    const previewDescricao = document.getElementById('previewDescricao');
    const previewImage = document.getElementById('previewImage');
    const noPreview = document.getElementById('noPreview');
    const previewCuidados = document.getElementById('previewCuidados');
    
    if (previewNome) previewNome.textContent = nome || 'Nome da Planta';
    if (previewDescricao) previewDescricao.textContent = descricao || 'Descrição aparecerá aqui...';
    if (previewCuidados) previewCuidados.textContent = rega || 'Instruções de cultivo aparecerão aqui...';
    
    if (urlfoto && previewImage && noPreview) {
        previewImage.src = urlfoto;
        previewImage.classList.remove('hidden');
        noPreview.classList.add('hidden');
    } else if (previewImage && noPreview) {
        previewImage.classList.add('hidden');
        noPreview.classList.remove('hidden');
    }
}

function clearPreview() {
    document.getElementById('previewNome').textContent = 'Nome da Planta';
    document.getElementById('previewDescricao').textContent = 'Descrição aparecerá aqui...';
    document.getElementById('previewCuidados').textContent = 'Instruções de cultivo aparecerão aqui...';
    document.getElementById('previewImage').classList.add('hidden');
    document.getElementById('noPreview').classList.remove('hidden');
}

// Initialize preview
document.addEventListener('DOMContentLoaded', updatePreview);
</script>

<?php
require '../parts/footer.php';
?>