<?php
session_start();
require '../config/authentication.php';

if (!autenticado() || !admin()) {
    $_SESSION["restrito"] = true;
    redireciona("../index.php");
    die();
}

require '../parts/header.php';
require '../config/connection.php';

if (isset($_GET["ordem"]) && !empty($_GET["ordem"])) {
    $ordem = filter_input(INPUT_GET, "ordem", FILTER_SANITIZE_SPECIAL_CHARS);
} else {
    $ordem = "p.nome";
}

if (isset($_POST["busca"]) && !empty($_POST["busca"])) {
    $busca = filter_input(INPUT_POST, "busca", FILTER_SANITIZE_SPECIAL_CHARS);
    $buscaOriginal = $busca;
    $tipo_busca = filter_input(INPUT_POST, "tipo_busca", FILTER_SANITIZE_SPECIAL_CHARS);

    if ($tipo_busca == "nome") {
        $busca = "%" . $busca . "%";
        $sql = "SELECT p.id, p.nome, p.urlfoto, p.descricao, p.cuidados, e.nome as especie_nome 
                FROM plantas p
                JOIN especies e ON e.id = p.id_especie
                WHERE p.nome like ? ORDER BY $ordem";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$busca]);
    } elseif ($tipo_busca == "id") {
        $sql = "SELECT p.id, p.nome, p.urlfoto, p.descricao, p.cuidados, e.nome as especie_nome 
                FROM plantas p
                JOIN especies e ON e.id = p.id_especie
                WHERE p.id = ? ORDER BY $ordem";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$busca]);
    } elseif ($tipo_busca == "especie") {
        $busca = "%" . $busca . "%";
        $sql = "SELECT p.id, p.nome, p.urlfoto, p.descricao, p.cuidados, e.nome as especie_nome 
                FROM plantas p
                JOIN especies e ON e.id = p.id_especie
                WHERE especie_nome like ? ORDER BY $ordem";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$busca]);
    } else {
        $buscaInt = intval($busca);
        $busca = "%" . $busca . "%";
        $sql = "SELECT p.id, p.nome, p.urlfoto, p.descricao, p.cuidados, e.nome as especie_nome
                FROM plantas p
                JOIN especies e ON e.id = p.id_especie
                WHERE p.nome like ? OR p.descricao like ? OR p.id = ? ORDER BY $ordem";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$busca, $busca, $buscaInt]);
    }
} else {
    $sql = "SELECT p.id, p.nome, p.urlfoto, p.descricao, p.cuidados, e.nome as especie_nome
            FROM plantas p
            JOIN especies e ON e.id = p.id_especie
            ORDER BY $ordem";
    $stmt = $conn->query($sql);
}
?>

  <!-- Lista de Plantas -->
  <section class="py-12 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-6">
      
      <!-- Cabeçalho -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Lista de Plantas</h1>
        <p class="text-gray-600 dark:text-gray-400">Todas as plantas cadastradas no sistema</p>
      </div>

      <!-- Barra de Busca -->
      <div class="mb-6 bg-green-50 dark:bg-gray-800 rounded-2xl p-6">
        <form method="POST" action="">
          <div class="grid md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
              <select name="tipo_busca" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="">Todos os campos</option>
                <option value="id">ID</option>
                <option value="nome">Nome</option>
              </select>
            </div>
            <div class="md:col-span-2">
              <input type="text" name="busca" placeholder="Buscar plantas..." 
                     class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            <div>
              <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-medium">
                Buscar
              </button>
            </div>
          </div>
        </form>
      </div>

      <?php
      if (isset($_POST["busca"]) && !empty($_POST["busca"])) {
      ?>
      <div class="mb-4">
        <div class="bg-blue-50 dark:bg-gray-800 rounded-lg p-4">
          <p class="text-blue-800 dark:text-blue-300">
            Você está buscando por "<span class="italic"><?= $buscaOriginal ?></span>", 
            <a href="?ordem=<?= $ordem ?>" class="text-green-600 hover:text-green-700">limpar</a>.
          </p>
        </div>
      </div>
      <?php
      }
      ?>

      <!-- Tabela de Plantas -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
        <table class="w-full">
          <thead class="bg-green-100 dark:bg-gray-700">
            <tr>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">
                <a href="?ordem=p.id" class="hover:text-green-600">ID</a>
              </th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">
                <a href="?ordem=p.nome" class="hover:text-green-600">Nome</a>
              </th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Espécie</th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Foto</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            <?php
            while ($row = $stmt->fetch()) {
            ?>
            <tr class="hover:bg-green-50 dark:hover:bg-gray-700 transition">
              <td class="px-6 py-4 text-sm text-gray-900 dark:text-white"><?= $row['id']; ?></td>
              <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white"><?= $row['nome']; ?></td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300"><?= $row['especie_nome']; ?></td>
              <td class="px-6 py-4">
                <?php if (!empty($row['urlfoto'])): ?>
                <a href="<?= $row['urlfoto']; ?>" target="_blank" class="text-green-600 hover:text-green-700 text-sm">Ver foto</a>
                <?php else: ?>
                <span class="text-gray-400 text-sm">Sem foto</span>
                <?php endif; ?>
              </td>
              <td class="px-6 py-4">
                <div class="flex gap-2">
                  <a href="../plants/form-update-plants.php?id=<?= $row['id']; ?>" class="px-3 py-1 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600 transition">
                    Editar
                  </a>
                  <a href="../plants/delete-plants.php?id=<?= $row['id']; ?>" class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition" 
                     onclick="if(!confirm('Tem certeza que deseja excluir?')) return false;">
                    Excluir
                  </a>
                </div>
              </td>
            </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>

      <!-- Informação de Total -->
      <div class="mt-4 text-center">
        <p class="text-gray-600 dark:text-gray-400">Total de plantas: <span class="font-semibold"><?= $stmt->rowCount(); ?></span></p>
      </div>
    </div>
  </section>

  <?php
  if (isset($_SESSION["result"])) {
      if ($_SESSION["result"] == true) {
  ?>
  <div class="max-w-7xl mx-auto px-6 mt-4">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
      <h4 class="font-bold"><?= $_SESSION["msg_sucesso"]; ?></h4>
    </div>
  </div>
  <?php
          unset($_SESSION["msg_sucesso"]);
      } else {
  ?>
  <div class="max-w-7xl mx-auto px-6 mt-4">
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
      <h4 class="font-bold"><?= $_SESSION["msg_erro"]; ?></h4>
      <p><?= $_SESSION["erro"]; ?></p>
    </div>
  </div>
  <?php
          unset($_SESSION["msg_erro"]);
          unset($_SESSION["erro"]);
      }
      unset($_SESSION["result"]);
  }
  ?>

  <?php
  require '../parts/footer.php';
  ?>