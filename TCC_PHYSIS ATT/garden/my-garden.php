<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("../user/form-login.php");
    die();
}

require '../parts/header.php';
require '../config/connection.php';

$id_usuario = id_usuario();

// Buscar plantas do jardim do usuário
$sql = "SELECT p.id, p.nome, p.urlfoto, p.descricao, 
               e.nome as especie_nome,
               j.id as jardim_id
        FROM jardim j
        JOIN plantas p ON p.id = j.id_planta
        JOIN especies e ON e.id = p.id_especie
        WHERE j.id_usuario = ?
        ORDER BY p.nome";

$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario]);
$plantas_jardim = $stmt->fetchAll();

// Contar estatísticas (simulação)
$total_plantas = count($plantas_jardim);
$precisam_rega = 0;
$precisam_adubo = 0;
$proximas_poda = 0;

// Simulação de necessidades
foreach ($plantas_jardim as $planta) {
    if (rand(0, 1)) $precisam_rega++;
    if (rand(0, 3) == 1) $precisam_adubo++;
    if (rand(0, 4) == 1) $proximas_poda++;
}

// Processar filtros
$filtro_ativo = $_GET['filtro'] ?? 'todas';
?>

<!-- Meu Jardim -->
<section class="py-12 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-6">
        
        <!-- Cabeçalho -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Meu Jardim</h1>
                <p class="text-gray-600 dark:text-gray-400">Gerencie as plantas do seu jardim virtual</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="../plants/cards-plants.php" class="px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition font-medium flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Adicionar Plantas
                </a>
            </div>
        </div>

        <!-- Estatísticas do Jardim -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-green-50 dark:bg-gray-800 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2"><?= $total_plantas ?></div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total de Plantas</div>
            </div>
            <div class="bg-yellow-50 dark:bg-gray-800 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mb-2"><?= $precisam_rega ?></div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Precisam de Rega</div>
            </div>
            <div class="bg-orange-50 dark:bg-gray-800 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <div class="text-2xl font-bold text-orange-600 dark:text-orange-400 mb-2"><?= $precisam_adubo ?></div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Precisa de Adubo</div>
            </div>
            <div class="bg-blue-50 dark:bg-gray-800 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-2"><?= $proximas_poda ?></div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Próximas da Poda</div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="flex flex-wrap gap-4 mb-8">
            <a href="?filtro=todas" 
               class="px-4 py-2 <?= $filtro_ativo === 'todas' ? 'bg-green-600 text-white' : 'bg-green-100 dark:bg-gray-700 text-green-800 dark:text-green-300' ?> rounded-full hover:bg-green-200 dark:hover:bg-gray-600 transition font-medium">
                Todas as Plantas
            </a>
            <a href="?filtro=rega" 
               class="px-4 py-2 <?= $filtro_ativo === 'rega' ? 'bg-yellow-600 text-white' : 'bg-yellow-100 dark:bg-gray-700 text-yellow-800 dark:text-yellow-300' ?> rounded-full hover:bg-yellow-200 dark:hover:bg-gray-600 transition font-medium">
                Precisam de Rega
            </a>
            <a href="?filtro=adubo" 
               class="px-4 py-2 <?= $filtro_ativo === 'adubo' ? 'bg-orange-600 text-white' : 'bg-orange-100 dark:bg-gray-700 text-orange-800 dark:text-orange-300' ?> rounded-full hover:bg-orange-200 dark:hover:bg-gray-600 transition font-medium">
                Precisa de Adubo
            </a>
            <a href="?filtro=poda" 
               class="px-4 py-2 <?= $filtro_ativo === 'poda' ? 'bg-blue-600 text-white' : 'bg-blue-100 dark:bg-gray-700 text-blue-800 dark:text-blue-300' ?> rounded-full hover:bg-blue-200 dark:hover:bg-gray-600 transition font-medium">
                Próximas da Poda
            </a>
        </div>

        <?php if ($total_plantas > 0): ?>
            <!-- Grid de Plantas do Jardim -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <?php foreach ($plantas_jardim as $planta): 
                    // Determinar status da planta (simulação)
                    $status = rand(1, 4);
                    switch($status) {
                        case 1:
                            $cor_borda = 'border-yellow-400';
                            $badge_cor = 'bg-yellow-500';
                            $badge_texto = '⚠️ Precisa de Rega';
                            $status_texto = 'Regar agora';
                            $status_cor = 'text-yellow-600 dark:text-yellow-400';
                            break;
                        case 2:
                            $cor_borda = 'border-green-400';
                            $badge_cor = 'bg-green-500';
                            $badge_texto = '🌿 Saudável';
                            $status_texto = 'Em dia';
                            $status_cor = 'text-green-600 dark:text-green-400';
                            break;
                        case 3:
                            $cor_borda = 'border-orange-400';
                            $badge_cor = 'bg-orange-500';
                            $badge_texto = '🌱 Precisa de Adubo';
                            $status_texto = 'Adubar';
                            $status_cor = 'text-orange-600 dark:text-orange-400';
                            break;
                        case 4:
                            $cor_borda = 'border-blue-400';
                            $badge_cor = 'bg-blue-500';
                            $badge_texto = '✂️ Próxima da Poda';
                            $status_texto = 'Podar em breve';
                            $status_cor = 'text-blue-600 dark:text-blue-400';
                            break;
                    }
                ?>
                
                <!-- Planta do Jardim -->
                <div class="bg-green-50 dark:bg-gray-800 rounded-2xl overflow-hidden hover:scale-105 transition cursor-pointer border-2 <?= $cor_borda ?> shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="relative">
                        <?php if (!empty($planta['urlfoto'])): ?>
                            <img src="<?= htmlspecialchars($planta['urlfoto']) ?>" alt="<?= htmlspecialchars($planta['nome']) ?>" class="w-full h-48 object-cover">
                        <?php else: ?>
                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-4xl">🌱</span>
                            </div>
                        <?php endif; ?>
                        <div class="absolute top-3 right-3 <?= $badge_cor ?> text-white px-3 py-1 rounded-full text-xs font-medium">
                            <?= $badge_texto ?>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2"><?= htmlspecialchars($planta['nome']) ?></h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-3"><?= htmlspecialchars($planta['especie_nome']) ?></p>
                        
                        <div class="flex justify-between items-center">
                            <span class="<?= $status_cor ?> font-semibold text-sm"><?= $status_texto ?></span>
                            <div class="flex gap-2">
                                <a href="../plants/plants-profile.php?id=<?= $planta['id'] ?>" class="bg-green-600 text-white px-3 py-1 rounded-full text-sm hover:bg-green-700 transition flex items-center gap-1">
                                    <i class="fas fa-heart"></i>
                                    Cuidar
                                </a>
                                <a href="delete-plant-garden.php?id=<?= $planta['jardim_id'] ?>" 
                                   class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full text-sm hover:bg-gray-400 dark:hover:bg-gray-500 transition flex items-center gap-1"
                                   onclick="return confirm('Tem certeza que deseja remover <?= htmlspecialchars(addslashes($planta['nome'])) ?> do seu jardim?')">
                                    <i class="fas fa-trash"></i>
                                    Remover
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Jardim Vazio -->
            <div class="text-center py-12">
                <div class="bg-green-50 dark:bg-gray-800 rounded-2xl p-12">
                    <div class="text-6xl mb-4">🌱</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Seu jardim está vazio</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Adicione algumas plantas para começar a cuidar do seu jardim virtual</p>
                    <a href="../plants/cards-plants.php" class="px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition font-medium">
                        Explorar Plantas
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php
require '../parts/footer.php';
?>