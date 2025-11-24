<?php
session_start();
require '../config/authentication.php';
require '../config/connection.php';
require '../parts/header.php';

// Verificar se o usuário é admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    $_SESSION['msg_erro'] = 'Acesso negado. Apenas administradores podem acessar esta página.';
    header('Location: ../interface/home.php');
    exit;
}

// Buscar todas as requisições pendentes
$sql = "SELECT r.*, u.nome, u.email, u.urlperfil 
        FROM requisicoes_esp r
        INNER JOIN usuarios u ON r.id_usuario = u.id
        WHERE r.status = 'pendente'
        ORDER BY r.data_requisicao ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$requisicoes_pendentes = $stmt->fetchAll();

// Buscar histórico (aprovadas e rejeitadas)
$sql_historico = "SELECT r.*, u.nome, u.email, u.urlperfil,
                  a.nome as admin_nome
                  FROM requisicoes_esp r
                  INNER JOIN usuarios u ON r.id_usuario = u.id
                  LEFT JOIN usuarios a ON r.id_admin = a.id
                  WHERE r.status IN ('aprovada', 'rejeitada')
                  ORDER BY r.data_resposta DESC
                  LIMIT 20";

$stmt_hist = $conn->prepare($sql_historico);
$stmt_hist->execute();
$historico = $stmt_hist->fetchAll();
?>

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Gerenciar Requisições de Especialistas</h1>
            <p class="mt-2 text-sm text-gray-600">Aprove ou rejeite solicitações de usuários que desejam se tornar especialistas</p>
        </div>

        <?php if (isset($_SESSION["msg_sucesso"])): ?>
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                <p class="font-medium"><?= $_SESSION["msg_sucesso"] ?></p>
            </div>
            <?php unset($_SESSION["msg_sucesso"]); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION["msg_erro"])): ?>
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <p class="font-medium"><?= $_SESSION["msg_erro"] ?></p>
            </div>
            <?php unset($_SESSION["msg_erro"]); ?>
        <?php endif; ?>

        <!-- Requisições Pendentes -->
        <div class="mb-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Requisições Pendentes 
                        <span class="ml-2 bg-yellow-600 text-white text-sm px-2 py-1 rounded-full"><?= count($requisicoes_pendentes) ?></span>
                    </h2>
                </div>

                <?php if (empty($requisicoes_pendentes)): ?>
                    <div class="px-6 py-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="mt-2">Nenhuma requisição pendente no momento.</p>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($requisicoes_pendentes as $req): ?>
                            <div class="p-6 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between">
                                    <!-- Informações do Usuário -->
                                    <div class="flex-1">
                                        <div class="flex items-center mb-4">
                                            <img src="<?= htmlspecialchars($req['urlperfil']) ?>" 
                                                 alt="Foto de perfil" 
                                                 class="w-16 h-16 rounded-full object-cover mr-4">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($req['nome']) ?></h3>
                                                <p class="text-sm text-gray-600"><?= htmlspecialchars($req['email']) ?></p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Solicitado em <?= date('d/m/Y \à\s H:i', strtotime($req['data_requisicao'])) ?>
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Detalhes da Requisição -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                                            <div>
                                                <span class="text-xs font-medium text-gray-500 uppercase">Profissão</span>
                                                <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($req['profissao']) ?></p>
                                            </div>
                                            <div>
                                                <span class="text-xs font-medium text-gray-500 uppercase">Telefone</span>
                                                <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($req['telefone']) ?></p>
                                            </div>
                                            <div>
                                                <span class="text-xs font-medium text-gray-500 uppercase">CPF</span>
                                                <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($req['cpf']) ?></p>
                                            </div>
                                            <div>
                                                <span class="text-xs font-medium text-gray-500 uppercase">Certificado</span>
                                                <a href="<?= htmlspecialchars($req['certificado']) ?>" 
                                                   target="_blank" 
                                                   class="mt-1 text-sm text-blue-600 hover:text-blue-500 flex items-center">
                                                    Ver documento
                                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                                        <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="md:col-span-2">
                                                <span class="text-xs font-medium text-gray-500 uppercase">Biografia</span>
                                                <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($req['bio']) ?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botões de Ação -->
                                    <div class="ml-6 flex flex-col gap-2">
                                        <form action="process_req.php" method="POST" class="inline">
                                            <input type="hidden" name="id_requisicao" value="<?= $req['id'] ?>">
                                            <input type="hidden" name="acao" value="aprovar">
                                            <button type="submit" 
                                                    onclick="return confirm('Deseja aprovar esta requisição?')"
                                                    class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition flex items-center">
                                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Aprovar
                                            </button>
                                        </form>
                                        
                                        <button onclick="abrirModalRejeitar(<?= $req['id'] ?>)"
                                                class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition flex items-center">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            Rejeitar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Histórico -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-100 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Histórico de Requisições</h2>
            </div>

            <?php if (empty($historico)): ?>
                <div class="px-6 py-8 text-center text-gray-500">
                    <p>Nenhuma requisição processada ainda.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profissão</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Resposta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($historico as $hist): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img src="<?= htmlspecialchars($hist['urlperfil']) ?>" 
                                                 class="w-10 h-10 rounded-full object-cover mr-3">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($hist['nome']) ?></div>
                                                <div class="text-xs text-gray-500"><?= htmlspecialchars($hist['email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($hist['profissao']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($hist['status'] == 'aprovada'): ?>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Aprovada
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Rejeitada
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('d/m/Y H:i', strtotime($hist['data_resposta'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($hist['admin_nome'] ?? 'N/A') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de Rejeição -->
<div id="modalRejeitar" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Motivo da Rejeição</h3>
            <form action="process_req.php" method="POST">
                <input type="hidden" name="id_requisicao" id="requisicao_id_rejeitar">
                <input type="hidden" name="acao" value="rejeitar">
                
                <textarea name="motivo_rejeicao" 
                          rows="4" 
                          required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                          placeholder="Explique o motivo da rejeição..."></textarea>
                
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" 
                            onclick="fecharModalRejeitar()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Confirmar Rejeição
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalRejeitar(idRequisicao) {
    document.getElementById('requisicao_id_rejeitar').value = idRequisicao;
    document.getElementById('modalRejeitar').classList.remove('hidden');
}

function fecharModalRejeitar() {
    document.getElementById('modalRejeitar').classList.add('hidden');
}
</script>

<?php require '../parts/footer.php'; ?>