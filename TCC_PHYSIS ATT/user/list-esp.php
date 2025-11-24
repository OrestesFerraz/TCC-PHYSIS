<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("../user/form-login.php");
    die();
}


require '../config/connection.php';

try {
    $sql = "SELECT 
    u.id,
    u.nome,
    u.urlperfil,
    ue.profissao,
    ue.bio
    FROM usuarios u
    INNER JOIN usuario_esp ue ON u.id = ue.id_usuario
    WHERE u.esp = 1
    ORDER BY u.nome ASC;";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $especialistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION["erro"] = "Erro ao buscar especialistas: " . $e->getMessage();
    $especialistas = [];
}

require '../parts/header.php';
?>

<div class="min-h-screen bg-green-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
        
        <!-- Cabeçalho da Página -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                🌿 Especialistas em Plantas
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                Conecte-se com especialistas qualificados para tirar suas dúvidas sobre cultivo e cuidados com plantas
            </p>
        </div>

        <?php if (isset($_SESSION["erro"])): ?>
            <div class="max-w-4xl mx-auto mb-6">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <p class="font-bold">Erro!</p>
                    <p><?= $_SESSION["erro"] ?></p>
                </div>
            </div>
            <?php unset($_SESSION["erro"]); ?>
        <?php endif; ?>

        <!-- Filtros e Busca -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <div class="relative">
                    <input type="text" id="searchEspecialistas" placeholder="Buscar por nome ou profissão..."
                           class="w-full px-6 py-4 rounded-xl border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <svg class="absolute right-6 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Grid de Especialistas -->
        <?php if (empty($especialistas)): ?>
            <div class="text-center py-16">
                <div class="text-6xl mb-4">🌱</div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    Nenhum especialista disponível
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Ainda não há especialistas cadastrados no momento. Volte mais tarde!
                </p>
            </div>
        <?php else: ?>
            <div id="especialistasGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($especialistas as $esp): ?>
                    <div class="especialista-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2"
                         data-nome="<?= htmlspecialchars(strtolower($esp['nome']), ENT_QUOTES) ?>"
                         data-profissao="<?= htmlspecialchars(strtolower($esp['profissao'] ?? ''), ENT_QUOTES) ?>">
                        
                        <!-- Header do Card -->
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-center">
                            <img src="<?= htmlspecialchars($esp['urlperfil'], ENT_QUOTES) ?>" 
                                 alt="Foto de <?= htmlspecialchars($esp['nome'], ENT_QUOTES) ?>"
                                 class="w-24 h-24 rounded-full mx-auto border-4 border-white shadow-lg object-cover">
                        </div>

                        <!-- Conteúdo do Card -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 text-center">
                                <?= htmlspecialchars($esp['nome'], ENT_QUOTES) ?>
                            </h3>
                            
                            <?php if (!empty($esp['profissao'])): ?>
                                <p class="text-green-600 dark:text-green-400 font-medium text-center mb-4">
                                    <?= htmlspecialchars($esp['profissao'], ENT_QUOTES) ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($esp['bio'])): ?>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">
                                    <?= htmlspecialchars($esp['bio'], ENT_QUOTES) ?>
                                </p>
                            <?php endif; ?>

                            <!-- Botões de Ação -->
                            <div class="flex gap-3 mt-6">
                                <a href="account.php?id=<?= $esp['id'] ?>" 
                                   class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition font-medium text-center text-sm">
                                    👤 Ver Perfil
                                </a>
                                <a href="../chat/chat.php?especialista_id=<?= $esp['id'] ?>" 
                                   class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium text-center text-sm">
                                    💬 Conversar
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Informações Adicionais -->
        <div class="mt-16 max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    ℹ️ Como funciona?
                </h2>
                <div class="space-y-3 text-gray-600 dark:text-gray-400">
                    <p>• <strong>Escolha um especialista:</strong> Navegue pelos perfis e encontre o profissional ideal para suas dúvidas.</p>
                    <p>• <strong>Visualize o perfil:</strong> Clique em "Ver Perfil" para conhecer mais sobre a experiência do especialista.</p>
                    <p>• <strong>Inicie uma conversa:</strong> Clique em "Conversar" para iniciar um chat em tempo real.</p>
                    <p>• <strong>Tire suas dúvidas:</strong> Converse sobre cultivo, cuidados, pragas e tudo relacionado às suas plantas!</p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Sistema de busca em tempo real
document.getElementById('searchEspecialistas').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    const cards = document.querySelectorAll('.especialista-card');
    
    cards.forEach(card => {
        const nome = card.dataset.nome;
        const profissao = card.dataset.profissao;
        
        if (nome.includes(searchTerm) || profissao.includes(searchTerm)) {
            card.style.display = '';
            card.classList.add('fade-in');
        } else {
            card.style.display = 'none';
        }
    });
    
    // Mostrar mensagem se não houver resultados
    const visibleCards = Array.from(cards).filter(card => card.style.display !== 'none');
    const grid = document.getElementById('especialistasGrid');
    
    let noResultsMsg = document.getElementById('noResultsMsg');
    if (visibleCards.length === 0 && searchTerm !== '') {
        if (!noResultsMsg) {
            noResultsMsg = document.createElement('div');
            noResultsMsg.id = 'noResultsMsg';
            noResultsMsg.className = 'col-span-full text-center py-16';
            noResultsMsg.innerHTML = `
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    Nenhum especialista encontrado
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Tente buscar por outro termo
                </p>
            `;
            grid.appendChild(noResultsMsg);
        }
    } else if (noResultsMsg) {
        noResultsMsg.remove();
    }
});

// Animação de fade-in
const style = document.createElement('style');
style.textContent = `
    .fade-in {
        animation: fadeIn 0.3s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
`;
document.head.appendChild(style);
</script>

<?php
require '../parts/footer.php';
?>