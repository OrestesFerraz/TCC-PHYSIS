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

$sql = "SELECT id, nome, email FROM usuarios ORDER BY id";
$stmt = $conn->query($sql);

$count = $stmt->rowCount();

if (isset($_SESSION["result"])) {
    if ($_SESSION["result"] == true) {
?>
        <div class="max-w-6xl mx-auto mt-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <h4 class="font-bold"><?= $_SESSION["msg_sucesso"]; ?></h4>
            </div>
        </div>
    <?php
        unset($_SESSION["msg_sucesso"]);
    } else {
    ?>
        <div class="max-w-6xl mx-auto mt-6">
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

  <!-- Lista de Usuários -->
  <section class="py-12 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-6xl mx-auto px-6">
      
      <!-- Cabeçalho -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Lista de Usuários</h1>
        <p class="text-gray-600 dark:text-gray-400">Usuários cadastrados no sistema</p>
      </div>

      <?php if ($count == 0) { ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
          <h4 class="font-bold">Atenção</h4>
          <p>Não há nenhum registro na tabela <b>usuários</b></p>
        </div>
      <?php } else { ?>

      <!-- Tabela de Usuários -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
        <table class="w-full">
          <thead class="bg-green-100 dark:bg-gray-700">
            <tr>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">ID</th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Nome</th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Email</th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Ações</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            <?php
            while ($row = $stmt->fetch()) {
            ?>
            <tr class="hover:bg-green-50 dark:hover:bg-gray-700 transition">
              <td class="px-6 py-4 text-sm text-gray-900 dark:text-white"><?= $row['id']; ?></td>
              <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white"><?= $row['nome']; ?></td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300"><?= $row['email']; ?></td>
              <td class="px-6 py-4">
                <?php
                if (autenticado()) {
                    if (id_usuario() == $row['id'] || admin()) {
                        ?>
                        <a class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition" 
                           href="../user/delete-user.php?id=<?= $row['id']; ?>"
                           onclick="return confirm('Tem certeza que deseja excluir?')">
                          Excluir
                        </a>
                        <?php
                    } else {
                        ?>
                        <button type="button" class="px-3 py-1 bg-gray-400 text-white rounded-lg text-sm cursor-not-allowed" disabled>
                          Excluir
                        </button>
                        <?php
                    }
                }
                ?>
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
        <p class="text-gray-600 dark:text-gray-400">Total de usuários: <span class="font-semibold"><?= $count; ?></span></p>
      </div>
      
      <?php } ?>
    </div>
  </section>

<?php
require '../parts/footer.php';
?>