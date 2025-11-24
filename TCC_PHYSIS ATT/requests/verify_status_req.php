<?php
session_start();
require '../config/authentication.php';
require '../config/connection.php';
require '../parts/header.php';

// Buscar todas as requisições do usuário
$sql = "SELECT * FROM requisicoes_esp 
        WHERE id_usuario = ? 
        ORDER BY data_requisicao DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['id_usuario']]);
$requisicoes = $stmt->fetchAll();

// Verificar se usuário já é especialista
$usuario_esp = isset($_SESSION['esp']) && $_SESSION['esp'] == 1;
?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Minhas Solicitações</h1>
            <p class="mt-2 text-sm text-gray-600">Acompanhe o status das suas solicitações para se tornar especialista</p>
        </div>

        <?php if ($usuario_esp): ?>
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-bold">Você já é um Especialista!</p>
                        <p class="text-sm">Sua conta foi aprovada e você tem acesso a todos os recursos de especialista.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($requisicoes)): ?>
            <div class="bg-white shadow-md rounded-lg p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-4 text-gray-600">Você ainda não fez nenhuma solicitação.</p>
                <?php if (!$usuario_esp): ?>
                    <a href="form-insert-user-esp.php" class="mt-4 inline-block px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition">
                        Solicitar ser Especialista
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($requisicoes as $req): ?>
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-4">
                                        <?php if ($req['status'] == 'pendente'): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span>
                                                Pendente
                                            </span>
                                        <?php elseif ($req['status'] == 'aprovada'): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Aprovada
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                Rejeitada
                                            </span>
                                        <?php endif; ?>
                                        <span class="ml-4 text-sm text-gray-500">
                                            Solicitado em <?= date('d/m/Y \à\s H:i', strtotime($req['data_requisicao'])) ?>
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                                        <div>
                                            <span class="text-xs font-medium text-gray-500 uppercase">Profissão</span>
                                            <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($req['profissao']) ?></p>
                                        </div>
                                        <div>
                                            <span class="text-xs font-medium text-gray-500 uppercase">Telefone</span>
                                            <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($req['telefone']) ?></p>
                                        </div>
                                        <div class="md:col-span-2">
                                            <span class="text-xs font-medium text-gray-500 uppercase">Biografia</span>
                                            <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($req['bio']) ?></p>
                                        </div>
                                    </div>

                                    <?php if ($req['status'] == 'aprovada' && $req['data_resposta']): ?>
                                        <div class="mt-4 bg-green-50 border-l-4 border-green-400 p-4">
                                            <p class="text-sm text-green-700">
                                                <strong>Aprovada em:</strong> <?= date('d/m/Y \à\s H:i', strtotime($req['data_resposta'])) ?>
                                            </p>
                                        </div>
                                    <?php elseif ($req['status'] == 'rejeitada'): ?>
                                        <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-4">
                                            <p class="text-sm text-red-700 mb-2">
                                                <strong>Rejeitada em:</strong> <?= date('d/m/Y \à\s H:i', strtotime($req['data_resposta'])) ?>
                                            </p>
                                            <?php if ($req['motivo_rejeicao']): ?>
                                                <p class="text-sm text-red-700">
                                                    <strong>Motivo:</strong> <?= htmlspecialchars($req['motivo_rejeicao']) ?>
                                                </p>
                                            <?php endif; ?>
                                            <p class="text-xs text-red-600 mt-2">
                                                Você pode fazer uma nova solicitação corrigindo os pontos mencionados.
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (!$usuario_esp): ?>
                <?php 
                // Verificar se a última requisição foi rejeitada
                $ultima_req = $requisicoes[0];
                $pode_solicitar_novamente = $ultima_req['status'] == 'rejeitada';
                ?>
                
                <?php if ($pode_solicitar_novamente): ?>
                    <div class="mt-6 text-center">
                        <a href="form-insert-user-esp.php" class="inline-block px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition">
                            Fazer Nova Solicitação
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        <div class="mt-8 text-center">
            <a href="../interface/home.php" class="text-sm font-medium text-green-600 hover:text-green-500 transition">
                ← Voltar para a página inicial
            </a>
        </div>
    </div>
</div>

<?php require '../parts/footer.php'; ?>