<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("../user/form-login.php");
    die();
}

require '../config/connection.php';
require '../parts/header.php';

$usuario_id = id_usuario();
?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-4xl mx-auto">
        
        <!-- Cabeçalho -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                💬 Suas Conversas
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400">
                Continue suas conversas ou inicie uma nova
            </p>
        </div>

        <!-- Botão para Nova Conversa -->
        <div class="mb-8 text-center">
            <a href="../esp/list-esp.php" 
               class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nova Conversa com Especialista
            </a>
        </div>

        <!-- Lista de Conversas -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
            <div id="conversasList">
                <!-- Conversas serão carregadas via AJAX -->
            </div>
        </div>

    </div>
</div>

<script>
// Carregar conversas
function carregarConversas() {
    fetch('ajax/get-conversas.php?full=1')
        .then(response => response.text())
        .then(data => {
            document.getElementById('conversasList').innerHTML = data;
        })
        .catch(error => console.error('Erro:', error));
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    carregarConversas();
    setInterval(carregarConversas, 3000); // Atualizar a cada 3 segundos
});
</script>

<?php
require '../parts/footer.php';
?>