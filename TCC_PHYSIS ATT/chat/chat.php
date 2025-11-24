<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("../user/form-login.php");
    die();
}

require '../config/connection.php';

$especialista_id = $_GET['especialista_id'] ?? null;
$usuario_id = id_usuario();

// Buscar informações do usuário atual
try {
    $sql_usuario = "SELECT id, nome, urlperfil, esp FROM usuarios WHERE id = ?";
    $stmt_usuario = $conn->prepare($sql_usuario);
    $stmt_usuario->execute([$usuario_id]);
    $usuario_atual = $stmt_usuario->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erro ao buscar dados do usuário");
}

// Verificar se é uma conversa válida
if ($especialista_id) {
    try {
        $sql = "SELECT u.id, u.nome, u.urlperfil, u.esp 
                FROM usuarios u 
                WHERE u.id = ? AND u.esp = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$especialista_id]);
        $especialista = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$especialista) {
            $_SESSION["erro"] = "Especialista não encontrado";
            redireciona("list-conversas.php");
            die();
        }
    } catch (Exception $e) {
        $_SESSION["erro"] = "Erro ao buscar especialista";
        redireciona("list-conversas.php");
        die();
    }
}

require '../parts/header.php';
?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="flex h-screen">
        
        <!-- Lista de Conversas (Sidebar) -->
        <div class="w-1/3 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col">
            <!-- Header Sidebar -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Conversas</h2>
                    <a href="list-conversas.php" class="text-green-600 hover:text-green-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Lista de Conversas -->
            <div class="flex-1 overflow-y-auto" id="listaConversas">
                <!-- As conversas serão carregadas via AJAX -->
            </div>
        </div>

        <!-- Área do Chat -->
        <div class="flex-1 flex flex-col">
            <?php if ($especialista_id): ?>
                <!-- Header do Chat -->
                <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center space-x-3">
                        <img src="<?= htmlspecialchars($especialista['urlperfil']) ?>" 
                             alt="<?= htmlspecialchars($especialista['nome']) ?>"
                             class="w-10 h-10 rounded-full">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                <?= htmlspecialchars($especialista['nome']) ?>
                            </h3>
                            <p class="text-sm text-green-600">Especialista</p>
                        </div>
                        <div class="ml-auto">
                            <span id="statusUsuario" class="flex items-center text-sm text-gray-500">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                Online
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Mensagens -->
                <div class="flex-1 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-900" id="mensagensContainer">
                    <div id="mensagens">
                        <!-- Mensagens serão carregadas via AJAX -->
                    </div>
                </div>

                <!-- Input de Mensagem -->
                <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4">
                    <form id="formMensagem" class="flex space-x-4">
                        <input type="hidden" name="destinatario_id" value="<?= $especialista_id ?>">
                        <input type="text" 
                               name="mensagem" 
                               id="inputMensagem"
                               placeholder="Digite sua mensagem..."
                               class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               required>
                        <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                            Enviar
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <!-- Tela quando nenhum chat está selecionado -->
                <div class="flex-1 flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-6xl mb-4">💬</div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Selecione uma conversa
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Escolha uma conversa na lista ou inicie uma nova
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Variáveis globais
let currentChat = <?= $especialista_id ?: 'null' ?>;
let pollingInterval;

// Carregar conversas
function carregarConversas() {
    fetch('ajax/get-conversas.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('listaConversas').innerHTML = data;
        })
        .catch(error => console.error('Erro:', error));
}

// Carregar mensagens
function carregarMensagens() {
    if (!currentChat) return;
    
    fetch(`ajax/get-mensagens.php?destinatario_id=${currentChat}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('mensagens').innerHTML = data;
            scrollToBottom();
        })
        .catch(error => console.error('Erro:', error));
}

// Enviar mensagem
document.getElementById('formMensagem')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('ajax/send-mensagem.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('inputMensagem').value = '';
            carregarMensagens();
        }
    })
    .catch(error => console.error('Erro:', error));
});

// Scroll automático para baixo
function scrollToBottom() {
    const container = document.getElementById('mensagensContainer');
    container.scrollTop = container.scrollHeight;
}

// Polling para novas mensagens
function iniciarPolling() {
    pollingInterval = setInterval(() => {
        if (currentChat) {
            carregarMensagens();
        }
        carregarConversas();
    }, 2000); // Atualizar a cada 2 segundos
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    carregarConversas();
    if (currentChat) {
        carregarMensagens();
    }
    iniciarPolling();
});

// Limpar intervalo quando a página for fechada
window.addEventListener('beforeunload', function() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
</script>

<?php
require '../parts/footer.php';
?>