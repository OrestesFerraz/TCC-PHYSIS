<?php
session_start();
require '../config/authentication.php';
require '../config/connection.php';
require '../parts/header.php';

// Buscar informações da requisição
$sql = "SELECT r.*, u.nome, u.email 
        FROM requisicoes_esp r
        INNER JOIN usuarios u ON r.id_usuario = u.id
        WHERE r.id_usuario = ? AND r.status = 'pendente'
        ORDER BY r.data_requisicao DESC
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['id_usuario']]);
$requisicao = $stmt->fetch();

// Se não tiver requisição pendente, redireciona
if (!$requisicao) {
    header('Location: ../interface/home.php');
    exit;
}

$data_formatada = date('d/m/Y \à\s H:i', strtotime($requisicao['data_requisicao']));
?>

<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        
        <?php if (isset($_SESSION["msg_sucesso"])): ?>
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium"><?= $_SESSION["msg_sucesso"] ?></span>
                </div>
            </div>
            <?php unset($_SESSION["msg_sucesso"]); ?>
        <?php endif; ?>

        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-8 text-center">
                <div class="mx-auto w-20 h-20 bg-white rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Solicitação em Análise</h1>
                <p class="text-green-100">Sua requisição está sendo avaliada pela nossa equipe</p>
            </div>

            <!-- Content -->
            <div class="px-6 py-8">
                <div class="space-y-6">
                    
                    <!-- Status -->
                    <div class="flex items-center justify-center">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span>
                            Aguardando Aprovação
                        </span>
                    </div>

                    <!-- Informação da Requisição -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalhes da Solicitação</h3>
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Profissão</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($requisicao['profissao']) ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Data da Solicitação</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?= $data_formatada ?></dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Biografia</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($requisicao['bio']) ?></dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Timeline -->
                    <div class="relative">
                        <div class="absolute top-0 bottom-0 left-4 w-0.5 bg-gray-200"></div>
                        
                        <div class="relative flex items-start mb-6">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-500 text-white z-10">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold text-gray-900">Solicitação Enviada</p>
                                <p class="text-xs text-gray-500"><?= $data_formatada ?></p>
                            </div>
                        </div>
                        
                        <div class="relative flex items-start mb-6">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-yellow-400 text-white z-10 animate-pulse">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold text-gray-900">Em Análise</p>
                                <p class="text-xs text-gray-500">Aguardando aprovação do administrador</p>
                            </div>
                        </div>
                        
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-white z-10">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-400">Aprovação Final</p>
                                <p class="text-xs text-gray-400">Você será notificado por email</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informações Importantes -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Próximos Passos</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Nossa equipe analisará seus documentos em até 48 horas</li>
                                        <li>Você receberá um email com o resultado da análise</li>
                                        <li>Se aprovado, você terá acesso aos recursos de especialista</li>
                                        <li>Em caso de dúvidas, entre em contato conosco</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <a href="../interface/home.php" class="text-sm font-medium text-green-600 hover:text-green-500 transition">
                    ← Voltar para a página inicial
                </a>
                <button onclick="location.reload()" class="text-sm font-medium text-gray-600 hover:text-gray-500 transition flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    Atualizar Status
                </button>
            </div>
        </div>
    </div>
</div>

<?php require '../parts/footer.php'; ?>