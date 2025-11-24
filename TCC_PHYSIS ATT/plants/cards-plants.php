<?php
session_start();
require '../config/authentication.php';
require '../config/connection.php';

// Lógica de ordenação
if (isset($_GET["ordem"]) && !empty($_GET["ordem"])) {
    $ordem = filter_input(INPUT_GET, "ordem", FILTER_SANITIZE_SPECIAL_CHARS);
} else {
    $ordem = "nome";
}

// Lógica de busca
$buscaOriginal = "";
$tipo_busca = "";

if (isset($_POST["busca"]) && !empty($_POST["busca"])) {
    $busca = filter_input(INPUT_POST, "busca", FILTER_SANITIZE_SPECIAL_CHARS);
    $tipo_busca = filter_input(INPUT_POST, "tipo_busca", FILTER_SANITIZE_SPECIAL_CHARS);
    $buscaOriginal = $busca;

    if ($tipo_busca == "nome") {
        $busca = "%" . $busca . "%";
        $sql = "SELECT id, nome, urlfoto, descricao FROM plantas WHERE nome like ? ORDER BY $ordem";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$busca]);
    } elseif ($tipo_busca == "id") {
        $sql = "SELECT id, nome, urlfoto, descricao FROM plantas WHERE id = ? ORDER BY $ordem";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$busca]);
    } elseif ($tipo_busca == "descricao") {
        $busca = "%" . $busca . "%";
        $sql = "SELECT id, nome, urlfoto, descricao FROM plantas WHERE descricao like ? ORDER BY $ordem";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$busca]);
    } else {
        $buscaInt = intval($busca);
        $busca = "%" . $busca . "%";
        $sql = "SELECT id, nome, urlfoto, descricao FROM plantas WHERE nome like ? OR descricao like ? OR id = ? ORDER BY $ordem";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$busca, $busca, $buscaInt]);
    }
} else {
    $sql = "SELECT id, nome, urlfoto, descricao FROM plantas ORDER BY $ordem";
    $stmt = $conn->query($sql);
}

require '../parts/header.php';
?>


<div class="container mx-auto px-4 plant-section">
    <!-- Hero Section -->
    <section class="hero-plants">
        <div class="text-center">
            <h1>Descubra Nossa Coleção de Plantas</h1>
            <p class="text-xl opacity-90 mb-8">
                Explore nossa diversificada coleção de plantas e encontre as espécies perfeitas para o seu jardim
            </p>
        </div>

        <!-- Search Form -->
        <div class="search-container max-w-4xl mx-auto">
            <form action="" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium mb-2">Buscar por</label>
                    <select name="tipo_busca" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Todos os campos</option>
                        <option value="id" <?= $tipo_busca == 'id' ? 'selected' : '' ?>>ID</option>
                        <option value="nome" <?= $tipo_busca == 'nome' ? 'selected' : '' ?>>Nome</option>
                        <option value="descricao" <?= $tipo_busca == 'descricao' ? 'selected' : '' ?>>Descrição</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Termo de busca</label>
                    <input type="text" name="busca" value="<?= htmlspecialchars($buscaOriginal) ?>" 
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="Digite sua busca...">
                </div>
                
                <div class="md:col-span-1 flex items-end">
                    <button type="submit" class="btn-plant w-full">
                        🔍 Pesquisar
                    </button>
                </div>
            </form>
            
            <?php if (isset($_POST["busca"]) && !empty($_POST["busca"])): ?>
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-sm">Resultados para "<?= htmlspecialchars($buscaOriginal) ?>"</span>
                    <a href="cards-plants.php" class="text-sm underline hover:no-underline">
                        Limpar busca
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Stats Section -->
    <div class="stats-grid">
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:col-span-1">
            <div class="filter-sidebar">
                <h3 class="text-xl font-bold mb-6">Filtros</h3>
                
                <div class="filter-group">
                    <h4 class="font-semibold mb-3">Ordenar por</h4>
                    <select id="ordenacao" onchange="changeOrder(this.value)" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="nome" <?= $ordem == 'nome' ? 'selected' : '' ?>>Nome A-Z</option>
                        <option value="nome DESC" <?= $ordem == 'nome DESC' ? 'selected' : '' ?>>Nome Z-A</option>
                        <option value="id" <?= $ordem == 'id' ? 'selected' : '' ?>>Mais Recentes</option>
                        <option value="id DESC" <?= $ordem == 'id DESC' ? 'selected' : '' ?>>Mais Antigos</option>
                    </select>
                </div>

                <button class="btn-plant w-full mt-4">
                    Aplicar Filtros
                </button>
            </div>
        </div>

        <!-- Plants Grid -->
        <div class="lg:col-span-3">
            <div class="plant-grid">
                <?php
                $plantCount = 0;
                while ($row = $stmt->fetch()) {
                    $plantCount++;
                    $dificuldades = ['Fácil', 'Moderado', 'Difícil'];
                    $dificuldade = $dificuldades[$row['id'] % 3];
                    $dificuldadeClass = 'difficulty-' . strtolower($dificuldade);
                ?>
                    <div class="plant-card fade-in">
                        <img src="<?= htmlspecialchars($row['urlfoto']) ?>" alt="<?= htmlspecialchars($row['nome']) ?>" 
                             class="w-full h-48 object-cover">
                        <div class="plant-card-content">
                            <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($row['nome']) ?></h3>
                            <p class="text-gray-600 mb-4"><?= htmlspecialchars($row['descricao']) ?></p>
                            <div class="flex justify-between items-center">
                                <span class="difficulty-badge <?= $dificuldadeClass ?>"><?= $dificuldade ?></span>
                                <a href="plants-profile.php?id=<?= $row['id'] ?>" class="btn-plant text-sm px-4 py-2">
                                    Ver Detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                
                if ($plantCount === 0) {
                    echo '<div class="col-span-full text-center py-12 text-gray-500">Nenhuma planta encontrada.</div>';
                }
                ?>
            </div>

            <?php if ($plantCount > 0): ?>
            <div class="text-center mt-8">
                <button class="btn-plant bg-transparent border border-green-600 text-green-600 hover:bg-green-600 hover:text-white">
                    Carregar Mais Plantas
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function changeOrder(order) {
    const url = new URL(window.location);
    url.searchParams.set('ordem', order);
    window.location.href = url.toString();
}

// Scroll animation
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, observerOptions);

document.querySelectorAll('.fade-in').forEach(el => {
    observer.observe(el);
});
</script>

<?php
require '../parts/footer.php';
?>