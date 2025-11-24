<?php
session_start();
require '../config/authentication.php';

require '../parts/header.php';
?>

  <!-- Lista de Espécies -->
  <section class="py-12 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-6xl mx-auto px-6">
      
      <!-- Cabeçalho -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Lista de Espécies</h1>
        <p class="text-gray-600 dark:text-gray-400">Espécies de plantas cadastradas no sistema</p>
      </div>

      <!-- Tabela de Espécies -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
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
            <!-- Espécie 1 -->
            <tr class="hover:bg-green-50 dark:hover:bg-gray-700 transition">
              <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">1</td>
              <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">Rosa</td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                <div class="max-w-md truncate">Plantas ornamentais conhecidas por suas flores coloridas e aroma marcante...</div>
              </td>
              <td class="px-6 py-4">
                <div class="flex gap-2">
                  <button class="px-3 py-1 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600 transition">
                    Editar
                  </button>
                  <button class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition" 
                          onclick="if(!confirm('Tem certeza que deseja excluir?')) return false;">
                    Excluir
                  </button>
                </div>
              </td>
            </tr>
            
            <!-- Espécie 2 -->
            <tr class="hover:bg-green-50 dark:hover:bg-gray-700 transition">
              <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">2</td>
              <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">Suculenta</td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                <div class="max-w-md truncate">Plantas que armazenam água em suas folhas, caules ou raízes...</div>
              </td>
              <td class="px-6 py-4">
                <div class="flex gap-2">
                  <button class="px-3 py-1 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600 transition">
                    Editar
                  </button>
                  <button class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition" 
                          onclick="if(!confirm('Tem certeza que deseja excluir?')) return false;">
                    Excluir
                  </button>
                </div>
              </td>
            </tr>
            
            <!-- Espécie 3 -->
            <tr class="hover:bg-green-50 dark:hover:bg-gray-700 transition">
              <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">3</td>
              <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">Lavanda</td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                <div class="max-w-md truncate">Plantas aromáticas medicinais conhecidas por suas propriedades calmantes...</div>
              </td>
              <td class="px-6 py-4">
                <div class="flex gap-2">
                  <button class="px-3 py-1 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600 transition">
                    Editar
                  </button>
                  <button class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition" 
                          onclick="if(!confirm('Tem certeza que deseja excluir?')) return false;">
                    Excluir
                  </button>
                </div>
              </td>
            </tr>
            
            <!-- Espécie 4 -->
            <tr class="hover:bg-green-50 dark:hover:bg-gray-700 transition">
              <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">4</td>
              <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">Samambaia</td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                <div class="max-w-md truncate">Plantas que preferem sombra e umidade constante...</div>
              </td>
              <td class="px-6 py-4">
                <div class="flex gap-2">
                  <button class="px-3 py-1 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600 transition">
                    Editar
                  </button>
                  <button class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition" 
                          onclick="if(!confirm('Tem certeza que deseja excluir?')) return false;">
                    Excluir
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Informação de Total -->
      <div class="mt-4 text-center">
        <p class="text-gray-600 dark:text-gray-400">Total de espécies: <span class="font-semibold">4</span></p>
      </div>
    </div>
  </section>

  <?php
  require '../parts/footer.php';
  ?>
