<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("form-login.php");
    die();
}

require '../parts/header.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (empty($id)) {
    ?>
    <div class="max-w-4xl mx-auto mt-8">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <h4 class="font-bold">Falha ao abrir formulário para edição</h4>
            <p>ID da planta está vazio</p>
        </div>
    </div>
    <?php
    exit;
}

require '../config/connection.php';

$sql = "SELECT nome, preco, urlimg, descricao, id_categoria, tipo, luz, agua, dificuldade FROM plantas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$rowPlanta = $stmt->fetch();

$sqlCat = "SELECT id, nome FROM categorias ORDER BY nome";
$stmtCat = $conn->query($sqlCat);
?>

<div class="min-h-screen bg-green-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-green-700 mb-8 text-center">Alterar Planta</h1>

            <form action="processa-alterar-planta.php" method="post">
                <input type="hidden" name="id" value="<?= $id ?>">
                
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Coluna 1 -->
                    <div class="space-y-6">
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome da Planta *</label>
                            <input type="text" id="nome" name="nome" required
                                   value="<?= $rowPlanta['nome'] ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        </div>

                        <div>
                            <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">Categoria *</label>
                            <select id="categoria" name="categoria" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                <option value="">Selecione uma categoria</option>
                                <?php while ($rowCat = $stmtCat->fetch()): ?>
                                    <option value="<?= $rowCat['id'] ?>" 
                                        <?= $rowCat['id'] == $rowPlanta['id_categoria'] ? 'selected' : '' ?>>
                                        <?= $rowCat['nome'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div>
                            <label for="preco" class="block text-sm font-medium text-gray-700 mb-2">Preço (R$) *</label>
                            <input type="number" step="0.01" id="preco" name="preco" required
                                   value="<?= $rowPlanta['preco'] ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        </div>

                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Planta</label>
                            <select id="tipo" name="tipo"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                <option value="interior" <?= $rowPlanta['tipo'] == 'interior' ? 'selected' : '' ?>>Interior</option>
                                <option value="exterior" <?= $rowPlanta['tipo'] == 'exterior' ? 'selected' : '' ?>>Exterior</option>
                                <option value="suculenta" <?= $rowPlanta['tipo'] == 'suculenta' ? 'selected' : '' ?>>Suculenta</option>
                                <option value="aromática" <?= $rowPlanta['tipo'] == 'aromática' ? 'selected' : '' ?>>Aromática</option>
                                <option value="medicinal" <?= $rowPlanta['tipo'] == 'medicinal' ? 'selected' : '' ?>>Medicinal</option>
                            </select>
                        </div>
                    </div>

                    <!-- Coluna 2 -->
                    <div class="space-y-6">
                        <div>
                            <label for="urlimg" class="block text-sm font-medium text-gray-700 mb-2">URL da Imagem *</label>
                            <input type="url" id="urlimg" name="urlimg" required
                                   value="<?= $rowPlanta['urlimg'] ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        </div>

                        <div>
                            <label for="luz" class="block text-sm font-medium text-gray-700 mb-2">Necessidade de Luz</label>
                            <select id="luz" name="luz"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                <option value="sol-pleno" <?= $rowPlanta['luz'] == 'sol-pleno' ? 'selected' : '' ?>>Sol Pleno</option>
                                <option value="meia-sombra" <?= $rowPlanta['luz'] == 'meia-sombra' ? 'selected' : '' ?>>Meia Sombra</option>
                                <option value="sombra" <?= $rowPlanta['luz'] == 'sombra' ? 'selected' : '' ?>>Sombra</option>
                                <option value="luz-indireta" <?= $rowPlanta['luz'] == 'luz-indireta' ? 'selected' : '' ?>>Luz Indireta</option>
                            </select>
                        </div>

                        <div>
                            <label for="agua" class="block text-sm font-medium text-gray-700 mb-2">Frequência de Água</label>
                            <select id="agua" name="agua"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                <option value="baixa" <?= $rowPlanta['agua'] == 'baixa' ? 'selected' : '' ?>>Baixa</option>
                                <option value="moderada" <?= $rowPlanta['agua'] == 'moderada' ? 'selected' : '' ?>>Moderada</option>
                                <option value="alta" <?= $rowPlanta['agua'] == 'alta' ? 'selected' : '' ?>>Alta</option>
                            </select>
                        </div>

                        <div>
                            <label for="dificuldade" class="block text-sm font-medium text-gray-700 mb-2">Dificuldade de Cuidado</label>
                            <select id="dificuldade" name="dificuldade"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                <option value="facil" <?= $rowPlanta['dificuldade'] == 'facil' ? 'selected' : '' ?>>Fácil</option>
                                <option value="moderado" <?= $rowPlanta['dificuldade'] == 'moderado' ? 'selected' : '' ?>>Moderado</option>
                                <option value="dificil" <?= $rowPlanta['dificuldade'] == 'dificil' ? 'selected' : '' ?>>Difícil</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Descrição -->
                <div class="mt-6">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição Detalhada</label>
                    <textarea id="descricao" name="descricao" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"><?= $rowPlanta['descricao'] ?></textarea>
                </div>

                <!-- Botões -->
                <div class="flex gap-4 mt-8">
                    <button type="submit" 
                            class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                        Salvar Alterações
                    </button>
                    <button type="reset" 
                            class="px-8 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium">
                        Restaurar
                    </button>
                    <a href="jardim.php" 
                       class="px-8 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <!-- Preview da Imagem -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview da Imagem</h3>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                <img id="img-preview" src="<?= $rowPlanta['urlimg'] ?>" alt="Preview" class="max-w-full max-h-64 mx-auto rounded-lg">
            </div>
        </div>
    </div>
</div>

<script>
    // Preview da imagem em tempo real
    document.getElementById('urlimg').addEventListener('input', function() {
        const preview = document.getElementById('img-preview');
        if (this.value) {
            preview.src = this.value;
        }
    });
</script>

<?php
if (isset($_SESSION["result"])) {
    if ($_SESSION["result"] == true) {
        ?>
        <div class="max-w-4xl mx-auto mt-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <h4 class="font-bold"><?= $_SESSION["msg_sucesso"] ?? 'Alterações salvas com sucesso!' ?></h4>
            </div>
        </div>
        <?php
        unset($_SESSION["msg_sucesso"]);
    } else {
        ?>
        <div class="max-w-4xl mx-auto mt-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <h4 class="font-bold"><?= $_SESSION["msg_erro"] ?? 'Erro ao salvar alterações!' ?></h4>
                <p><?= $_SESSION["erro"] ?? '' ?></p>
            </div>
        </div>
        <?php
        unset($_SESSION["msg_erro"]);
        unset($_SESSION["erro"]);
    }
    unset($_SESSION["result"]);
}

require '../parts/footer.php';
?>