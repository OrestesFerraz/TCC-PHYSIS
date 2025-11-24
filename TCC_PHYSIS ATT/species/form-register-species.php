<?php
session_start();
require '../config/authentication.php';

/** Tratamento de permissões */
if (!autenticado() || !admin()) {
    $_SESSION["restrito"] = true;
    redireciona("../index.php");
    die();
}
/** Tratamento de permissões */

require '../config/connection.php';

// Inicializar variáveis
$id = null;
$nome = '';
$descricao = '';
$action = "register-species.php";

if (isset($_GET["id"])) {
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    
    if ($id) {
        $sql = "SELECT id, nome, descricao FROM especies WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        $rowEspecie = $stmt->fetch();

        if ($rowEspecie) {
            $nome = $rowEspecie["nome"];
            $descricao = $rowEspecie["descricao"];
            $action = "update-species.php";
        } else {
            $_SESSION["result"] = false;
            $_SESSION["msg_erro"] = "Espécie não encontrada!";
            redireciona("form-register-species.php");
            die();
        }
    }
}

require '../parts/header.php';

// Exibir mensagens de feedback
if (isset($_SESSION["result"])) {
    $result = $_SESSION["result"];
    ?>
    <div class="max-w-4xl mx-auto mt-6 px-6">
        <div class="<?= $result ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700' ?> border px-4 py-3 rounded">
            <h4 class="font-bold"><?= $result ? $_SESSION["msg_sucesso"] : $_SESSION["msg_erro"] ?></h4>
            <?php if (!$result && isset($_SESSION["erro"])): ?>
                <p class="mt-1"><?= $_SESSION["erro"] ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    
    // Limpar sessões de mensagem
    unset($_SESSION["result"]);
    unset($_SESSION["msg_sucesso"]);
    unset($_SESSION["msg_erro"]);
    unset($_SESSION["erro"]);
}
?>

<!-- Formulário de Espécies -->
<section class="py-12 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-6">
      
        <!-- Cabeçalho -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                <?= $id ? "Editar Espécie" : "Cadastrar Espécie" ?>
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                <?= $id ? "Edite os dados da espécie" : "Adicione novas espécies de plantas ao sistema" ?>
            </p>
        </div>

        <!-- Formulário -->
        <form action="<?= $action ?>" method="POST" class="bg-green-50 dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
            <?php if ($id): ?>
                <input type="hidden" name="id" value="<?= $id ?>">
            <?php endif; ?>
            
            <div class="space-y-6">
              
                <!-- Nome da Espécie -->
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nome da Espécie *
                    </label>
                    <input type="text" id="nome" name="nome" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors"
                           placeholder="Ex: Rosa, Suculenta, Lavanda"
                           value="<?= htmlspecialchars($nome) ?>"
                           maxlength="100">
                </div>

                <!-- Descrição -->
                <div>
                    <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descrição
                    </label>
                    <textarea id="descricao" name="descricao" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-vertical"
                              placeholder="Informe uma breve descrição para esta espécie. Exemplo: 'Plantas ornamentais conhecidas por suas flores coloridas e aroma marcante.'"
                              maxlength="500"><?= htmlspecialchars($descricao) ?></textarea>
                    <div class="text-right text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <span id="descricao-counter"><?= strlen($descricao) ?></span>/500 caracteres
                    </div>
                </div>

            </div>

            <!-- Botões -->
            <div class="flex flex-col sm:flex-row gap-4 mt-8">
                <button type="submit" 
                        class="flex-1 bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition font-medium shadow-md hover:shadow-lg">
                    <?= $id ? "Atualizar Espécie" : "Cadastrar Espécie" ?>
                </button>
                <button type="reset" 
                        class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 py-3 px-6 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition font-medium">
                    Limpar
                </button>
                <a href="form-register-species.php"
                   class="flex-1 bg-yellow-500 text-white py-3 px-6 rounded-lg hover:bg-yellow-600 transition font-medium text-center inline-flex items-center justify-center">
                    Nova Espécie
                </a>
            </div>
        </form>

        <!-- Lista de Espécies Cadastradas -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Espécies Cadastradas</h2>
            
            <?php
            $sql = "SELECT id, nome, descricao FROM especies ORDER BY nome";
            $stmt = $conn->query($sql);
            $especies = $stmt->fetchAll();

            function limitarDescricao($descricao, $limite = 130) {
                if (strlen($descricao) > $limite) {
                    return substr($descricao, 0, $limite) . '[...]';
                }
                return $descricao;
            }
            ?>
            
            <?php if (count($especies) > 0): ?>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-green-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">ID</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Nome</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Descrição</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                <?php foreach ($especies as $row): ?>
                                <tr class="hover:bg-green-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-mono">#<?= $row['id'] ?></td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($row['nome']) ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                        <?= htmlspecialchars(limitarDescricao($row['descricao'])) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="form-register-species.php?id=<?= $row['id'] ?>" 
                                               class="px-3 py-1 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600 transition shadow-sm inline-flex items-center gap-1">
                                                <i class="fas fa-edit text-xs"></i>
                                                Editar
                                            </a>
                                            <a href="excluir-especie.php?id=<?= $row['id'] ?>" 
                                               onclick="return confirm('Tem certeza que deseja excluir a espécie \"<?= addslashes($row['nome']) ?>\"?')"
                                               class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition shadow-sm inline-flex items-center gap-1">
                                                <i class="fas fa-trash text-xs"></i>
                                                Excluir
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-green-50 dark:bg-gray-800 rounded-2xl p-8 text-center">
                    <div class="text-4xl mb-4">🌱</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Nenhuma espécie cadastrada</h3>
                    <p class="text-gray-600 dark:text-gray-400">Comece cadastrando a primeira espécie usando o formulário acima.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    // Contador de caracteres para a descrição
    document.addEventListener('DOMContentLoaded', function() {
        const descricaoTextarea = document.getElementById('descricao');
        const counter = document.getElementById('descricao-counter');
        
        if (descricaoTextarea && counter) {
            descricaoTextarea.addEventListener('input', function() {
                counter.textContent = this.value.length;
            });
        }
        
        // Validação do formulário
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const nome = document.getElementById('nome').value.trim();
            if (!nome) {
                e.preventDefault();
                alert('Por favor, preencha o nome da espécie.');
                return false;
            }
        });
    });
</script>

<?php
require '../parts/footer.php';
?>