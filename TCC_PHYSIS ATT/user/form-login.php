<?php
session_start();
require '../config/authentication.php';

require '../parts/header.php';
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-2xl shadow-lg p-8 transition-all duration-300 hover:shadow-xl">
            <div class="flex justify-center mb-6">
                <div class="bg-green-100 p-3 rounded-full">
                    <img src="../img/logo.png" alt="Logo" class="w-16 h-16">
                </div>
            </div>
            <h2 class="text-center text-3xl font-bold text-gray-900 mb-2">
                Entre na sua conta
            </h2>
            <p class="text-center text-gray-600 mb-8">
                Acesse sua conta para continuar
            </p>
            
            <form class="space-y-6" action="login.php" method="POST">
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                   class="pl-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm transition"
                                   placeholder="seu@email.com">
                        </div>
                    </div>
                    
                    <div>
                        <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="senha" name="senha" type="password" autocomplete="current-password" required 
                                   class="pl-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm transition"
                                   placeholder="Sua senha">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="lembrar" name="lembrar" type="checkbox" 
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="lembrar" class="ml-2 block text-sm text-gray-700">
                            Lembrar-me
                        </label>
                    </div>
                    
                    <a href="#" class="text-sm font-medium text-green-600 hover:text-green-500 transition">
                        Esqueceu a senha?
                    </a>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300 transform hover:-translate-y-0.5 shadow-md">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-green-300 group-hover:text-green-200 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </span>
                        Entrar na conta
                    </button>
                </div>

                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-gray-600 text-sm">
                        Não tem uma conta?
                        <a href="form-insert-user.php" class="font-medium text-green-600 hover:text-green-500 transition ml-1">
                            Cadastre-se aqui
                        </a>
                    </p>
                </div>
            </form>

            <?php
            if (isset($_SESSION["result"])) {
                if ($_SESSION["result"] == false) {
                    ?>
                    <div class="mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg transition-all duration-300">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-bold text-sm"><?= $_SESSION["msg_erro"] ?? 'Erro no login' ?></h4>
                                <p class="text-sm mt-1"><?= $_SESSION["erro"] ?? '' ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                    unset($_SESSION["msg_erro"]);
                    unset($_SESSION["erro"]);
                    unset($_SESSION["result"]);
                }
            }
            ?>
        </div>
        
        <div class="text-center">
            <p class="text-xs text-gray-500">
                &copy; <?= date('Y') ?> Todos os direitos reservados
            </p>
        </div>
    </div>
</div>

<?php require '../parts/footer.php'; ?>