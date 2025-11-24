<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("../user/form-login.php");
    die();
}

require '../parts/header.php';

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (empty($id) || $id === false) {
    ?>
    <div class="max-w-4xl mx-auto mt-8 px-6">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <h4 class="font-bold">Falha ao abrir formulário para edição</h4>
            <p>ID da planta inválido ou não fornecido</p>
        </div>
        <div class="text-center mt-4">
            <a href="minhas-plantas.php" class="text-green-600 hover:text-green-800 font-medium">
                ← Voltar para Minhas Plantas
            </a>
        </div>
    </div>
    <?php
    require '../parts/footer.php';
    exit;
}

require '../config/connection.php';

// Buscar dados da planta
$sql = "SELECT nome, urlfoto, descricao, altura, uso, solo, locali, plantio, rega, adubacao, poda, dificuldade, id_especie 
        FROM plantas 
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$rowPlanta = $stmt->fetch();

if (!$rowPlanta) {
    ?>
    <div class="max-w-4xl mx-auto mt-8 px-6">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <h4 class="font-bold">Planta não encontrada</h4>
            <p>A planta solicitada não existe ou foi removida</p>
        </div>
        <div class="text-center mt-4">
            <a href="minhas-plantas.php" class="text-green-600 hover:text-green-800 font-medium">
                ← Voltar para Minhas Plantas
            </a>
        </div>
    </div>
    <?php
    require '../parts/footer.php';
    exit;
}

// Buscar espécies
$sqlEsp = "SELECT id, nome FROM especies ORDER BY nome";
$stmtEsp = $conn->query($sqlEsp);
$especies = $stmtEsp->fetchAll();
?>

<!-- Formulário de Edição de Plantas -->
<section class="py-12 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-6xl mx-auto px-6">
        
        <!-- Cabeçalho -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Editar Planta</h1>
            <p class="text-gray-600 dark:text-gray-400">Atualize as informações da sua planta</p>
        </div>

        <!-- Mensagens de Feedback -->
        <?php if (isset($_SESSION["result"])): ?>
            <div class="max-w-4xl mx-auto mb-6">
                <div class="<?= $_SESSION["result"] ? 'bg-green-100 border-green-400 text-green-700 dark:bg-green-800 dark:border-green-600 dark:text-green-100' : 'bg-red-100 border-red-400 text-red-700 dark:bg-red-800 dark:border-red-600 dark:text-red-100' ?> border px-4 py-3 rounded">
                    <h4 class="font-semibold"><?= $_SESSION["result"] ? $_SESSION["msg_sucesso"] : $_SESSION["msg_erro"] ?></h4>
                    <?php if (!$_SESSION["result"] && isset($_SESSION["erro"])): ?>
                        <p class="mt-1 text-sm"><?= $_SESSION["erro"] ?></p>
                    <?php endif; ?>
                </div>
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
        <form action="update-plants.php" method="POST" id="plantForm" class="bg-green-50 dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
            <input type="hidden" name="id" id="id" value="<?= $id ?>">
            
            <!-- Step 1: Informações Básicas -->
            <div class="step-content active" data-step="1">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Coluna 1 -->
                    <div class="space-y-6">
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nome da Planta *
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors" 
                                   id="nome" name="nome" required 
                                   value="<?= htmlspecialchars($rowPlanta['nome']) ?>"
                                   maxlength="100">
                        </div>

                        <div>
                            <label for="especie" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Espécie *
                            </label>
                            <select class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors" 
                                    name="especie" id="especie" required>
                                <option value="">Selecione a espécie da planta</option>
                                <?php foreach ($especies as $especie): ?>
                                    <option value="<?= $especie['id'] ?>" 
                                            <?= $especie['id'] == $rowPlanta['id_especie'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($especie['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="altura" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Altura (cm)
                            </label>
                            <input type="number" 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors" 
                                   id="altura" name="altura" 
                                   value="<?= htmlspecialchars($rowPlanta['altura']) ?>" 
                                   placeholder="Ex: 50"
                                   min="0" step="0.1">
                        </div>

                        <div>
                            <label for="dificuldade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Dificuldade de Cultivo
                            </label>
                            <select class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors" 
                                    name="dificuldade" id="dificuldade">
                                <option value="">Selecione a dificuldade</option>
                                <option value="Fácil" <?= $rowPlanta['dificuldade'] == 'Fácil' ? 'selected' : '' ?>>Fácil</option>
                                <option value="Médio" <?= $rowPlanta['dificuldade'] == 'Médio' ? 'selected' : '' ?>>Médio</option>
                                <option value="Difícil" <?= $rowPlanta['dificuldade'] == 'Difícil' ? 'selected' : '' ?>>Difícil</option>
                            </select>
                        </div>
                    </div>

                    <!-- Coluna 2 -->
                    <div class="space-y-6">
                        <div>
                            <label for="urlfoto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                URL da Foto *
                            </label>
                            <input type="url" 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors" 
                                   id="urlfoto" name="urlfoto" 
                                   value="<?= htmlspecialchars($rowPlanta['urlfoto']) ?>" required
                                   onchange="updateImagePreview()">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Endereço http de uma imagem da internet</p>
                        </div>

                        <div>
                            <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Descrição Detalhada
                            </label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical" 
                                      id="descricao" name="descricao" rows="4" 
                                      placeholder="Descreva as características da planta..."
                                      maxlength="1000"><?= htmlspecialchars($rowPlanta['descricao']) ?></textarea>
                            <div class="text-right text-sm text-gray-500 dark:text-gray-400 mt-1">
                                <span id="descricao-counter"><?= strlen($rowPlanta['descricao']) ?></span>/1000 caracteres
                            </div>
                        </div>

                        <!-- Preview da Imagem -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview da Imagem</h3>
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center">
                                <img class="max-w-full max-h-48 mx-auto rounded-lg" 
                                     src="<?= htmlspecialchars($rowPlanta['urlfoto']) ?>" 
                                     alt="<?= htmlspecialchars($rowPlanta['nome']) ?>" 
                                     id="img-preview"
                                     onerror="this.style.display='none'; document.getElementById('preview-text').style.display='block';">
                                <p id="preview-text" class="text-gray-500 dark:text-gray-400 text-sm mt-2 <?= !empty($rowPlanta['urlfoto']) ? 'hidden' : '' ?>">
                                    A imagem aparecerá aqui
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Características -->
            <div class="step-content hidden" data-step="2">
                <div class="space-y-6">
                    <div>
                        <label for="uso" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Uso
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical" 
                                  id="uso" name="uso" rows="3" 
                                  placeholder="Descreva os usos da planta..."
                                  maxlength="500"><?= htmlspecialchars($rowPlanta['uso']) ?></textarea>
                    </div>

                    <div>
                        <label for="solo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipo de Solo
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical" 
                                  id="solo" name="solo" rows="3" 
                                  placeholder="Descreva o tipo de solo ideal..."
                                  maxlength="500"><?= htmlspecialchars($rowPlanta['solo']) ?></textarea>
                    </div>

                    <div>
                        <label for="locali" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Localização Ideal
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical" 
                                  id="locali" name="locali" rows="3" 
                                  placeholder="Descreva a localização ideal..."
                                  maxlength="500"><?= htmlspecialchars($rowPlanta['locali']) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Step 3: Cuidados -->
            <div class="step-content hidden" data-step="3">
                <div class="space-y-6">
                    <div>
                        <label for="plantio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Forma de Plantio
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical" 
                                  id="plantio" name="plantio" rows="3" 
                                  placeholder="Descreva como realizar o plantio..."
                                  maxlength="500"><?= htmlspecialchars($rowPlanta['plantio']) ?></textarea>
                    </div>

                    <div>
                        <label for="rega" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Rega
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical" 
                                  id="rega" name="rega" rows="3" 
                                  placeholder="Descreva a frequência e forma de rega..."
                                  maxlength="500"><?= htmlspecialchars($rowPlanta['rega']) ?></textarea>
                    </div>

                    <div>
                        <label for="adubacao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Adubação
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical" 
                                  id="adubacao" name="adubacao" rows="3" 
                                  placeholder="Descreva a adubação recomendada..."
                                  maxlength="500"><?= htmlspecialchars($rowPlanta['adubacao']) ?></textarea>
                    </div>

                    <div>
                        <label for="poda" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Poda
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical" 
                                  id="poda" name="poda" rows="3" 
                                  placeholder="Descreva como e quando podar..."
                                  maxlength="500"><?= htmlspecialchars($rowPlanta['poda']) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Botões de Navegação -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-8 pt-6 border-t border-gray-300 dark:border-gray-600">
                <button type="button" id="prevBtn" class="hidden px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition font-medium w-full sm:w-auto flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Anterior
                </button>
                
                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                    <button type="button" id="nextBtn" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium w-full sm:w-auto flex items-center justify-center gap-2">
                        Próximo
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    
                    <button type="submit" id="submitBtn" class="hidden px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium w-full sm:w-auto flex items-center justify-center gap-2">
                        <i class="fas fa-check"></i>
                        Salvar Alterações
                    </button>
                    
                    <button type="reset" 
                            class="px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium w-full sm:w-auto flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Limpar
                    </button>
                    
                    <a href="minhas-plantas.php" 
                       class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium w-full sm:w-auto flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Voltar
                    </a>
                </div>
            </div>
        </form>
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

// Image preview functionality
function updateImagePreview() {
    const imgPreview = document.getElementById('img-preview');
    const previewText = document.getElementById('preview-text');
    const urlInput = document.getElementById('urlfoto');
    
    if (urlInput.value) {
        imgPreview.src = urlInput.value;
        imgPreview.style.display = 'block';
        previewText.style.display = 'none';
    } else {
        imgPreview.style.display = 'none';
        previewText.style.display = 'block';
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Wizard
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
    
    // Validação do formulário
    const form = document.getElementById('plantForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const nome = document.getElementById('nome').value.trim();
            const especie = document.getElementById('especie').value;
            const urlfoto = document.getElementById('urlfoto').value.trim();
            
            if (!nome || !especie || !urlfoto) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
                return false;
            }
        });
    }
});

// Initialize image preview
updateImagePreview();
</script>

<?php
require '../parts/footer.php';
?>