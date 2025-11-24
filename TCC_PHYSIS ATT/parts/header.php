<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto Physis | Lado a Lado com a Natureza</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../style/css/style.css">
</head>
<body class="text-gray-800">
    <!-- Folhas flutuantes no background -->
    <div class="leaf leaf-1 floating" style="top: 10%; left: 5%;"></div>
    <div class="leaf leaf-2 floating-slow" style="top: 20%; right: 7%;"></div>
    <div class="leaf leaf-3 floating-fast" style="top: 40%; left: 8%;"></div>
    <div class="leaf leaf-4 floating-reverse" style="top: 60%; right: 10%;"></div>
    <div class="leaf leaf-5 floating-slow" style="top: 80%; left: 12%;"></div>
    
    <!-- Header/Navbar Arredondada com Margens -->
    <header class="navbar-rounded sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center max-w-6xl">
            <!-- Logo -->
            <div class="flex items-center">
                <i class="fas fa-seedling text-2xl mr-2" style="color: var(--verde-primario);"></i>
                <h1 class="text-2xl font-bold" style="color: var(--verde-primario);">Physis</h1>
            </div>
            
            <!-- Links Desktop -->
            <nav class="hidden md:flex space-x-8">
                <a href="../interface/home.php" class="nav-link font-medium">Home</a>
                <a href="../plants/cards-plants.php" class="nav-link font-medium">Plantas</a>
                <?php
                if (autenticado() || admin()) {
                ?>
                <a href="../garden/my-garden.php" class="nav-link font-medium">Seu Jardim</a>
                <?php
                }
                ?>
                <a href="../interface/FaQs.php" class="nav-link font-medium">FAQs</a>
            </nav>
            
            <!-- Botões Desktop -->
            <div class="flex items-center space-x-4">
                
                <?php
                if (!autenticado()) {
                ?>
                <div class="hidden md:flex gap-3">
                    <a href="../user/form-insert-user.php" class="btn-plant text-white px-4 py-2 rounded-full font-medium">Cadastro</a>
                    <a href="../user/form-login.php" class="btn-plant text-white px-4 py-2 rounded-full font-medium">Login</a>
                </div>
                <?php
                } else if (autenticado() && !admin()) {
                ?>
                <!-- Perfil do Usuário (Após Login) -->
                <div class="hidden md:flex gap-3">
                    <div class="relative">
                        <button id="userMenuButton" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-green-50 transition">
                            <img src="<?= foto_usuario(); ?>" alt="Usuário" class="w-8 h-8 rounded-full object-cover border-2 border-green-500">
                            <span class="font-medium text-gray-700">
                                <?= explode(' ', nome_usuario())[0]; ?>
                            </span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="userDropdown" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50 hidden">
                            <a href="../user/my-account.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-green-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Minha Conta
                            </a>

                            <a href="../garden/my-garden.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-green-50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" />
                                </svg>
                                Meu Jardim
                            </a>
                            <?php
                            if (!esp()){
                            ?>
                            <a href="../user/form-insert-user-esp.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-green-50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" />
                                </svg>
                                Virar Especialista
                            </a>
                            <?php
                            }
                            ?>

                            <div class="border-t border-gray-200 my-2"></div>

                            <a href="../config/exit.php" class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Sair
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                } else if (admin()) {
                ?>
                <!-- Perfil do Usuário Admin (Após Login) -->
                <div class="hidden md:flex gap-3">
                    <div class="relative">
                        <button id="userMenuButton" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-green-50 transition">
                            <img src="<?= foto_usuario(); ?>" alt="Usuário" class="w-8 h-8 rounded-full object-cover border-2 border-green-500">
                            <span class="font-medium text-gray-700">
                                <?= explode(' ', nome_usuario())[0]; ?>
                            </span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu Admin -->
                        <div id="userDropdown" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50 hidden">
                            <a href="../user/my-account.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-green-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Minha Conta
                            </a>

                            <a href="../plants/form-register-plants.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-green-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                </svg>
                                Inserir Plantas
                            </a>

                            <a href="../species/form-register-species.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-green-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Inserir Espécies
                            </a>
                            <a href="../requests/requests.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-green-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Requisições
                            </a>

                            <div class="border-t border-gray-200 my-2"></div>

                            <a href="../config/exit.php" class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Sair
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>

            <!-- Botão Menu Mobile - CORRIGIDO COM ID -->
            <button id="menu-toggle" class="md:hidden p-2 rounded-full hover:bg-gray-100 transition-colors">
                <i id="menu-open" class="fas fa-bars text-xl" style="color: var(--verde-primario);"></i>
                <i id="menu-close" class="fas fa-times text-xl hidden" style="color: var(--verde-primario);"></i>
            </button>
        </div>

        <!-- Menu Mobile -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200 shadow-lg">
            <nav class="container mx-auto px-4 py-4 flex flex-col space-y-3">
                <a href="../interface/home.php" class="nav-link font-medium py-2 px-4 rounded-lg hover:bg-green-50 transition">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
                <a href="../plants/cards-plants.php" class="nav-link font-medium py-2 px-4 rounded-lg hover:bg-green-50 transition">
                    <i class="fas fa-leaf mr-2"></i> Plantas
                </a>
                <?php
                if (autenticado() || admin()) {
                ?>
                <a href="../garden/my-garden.php" class="nav-link font-medium py-2 px-4 rounded-lg hover:bg-green-50 transition">
                    <i class="fas fa-seedling mr-2"></i> Seu Jardim
                </a>
                <?php
                }
                ?>
                <a href="../interface/FaQs.php" class="nav-link font-medium py-2 px-4 rounded-lg hover:bg-green-50 transition">
                    <i class="fas fa-question-circle mr-2"></i> FAQs
                </a>

                <?php
                if (!autenticado()) {
                ?>
                <div class="border-t border-gray-200 pt-3 mt-3 flex flex-col space-y-2">
                    <a href="../user/form-insert-user.php" class="btn-plant text-white px-4 py-2 rounded-full font-medium text-center">
                        Cadastro
                    </a>
                    <a href="../user/form-login.php" class="btn-plant text-white px-4 py-2 rounded-full font-medium text-center">
                        Login
                    </a>
                </div>
                <?php
                } else {
                ?>
                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="flex items-center gap-3 px-4 py-2 mb-3">
                        <img src="<?= foto_usuario(); ?>" alt="Usuário" class="w-10 h-10 rounded-full object-cover border-2 border-green-500">
                        <span class="font-medium text-gray-700"><?= nome_usuario(); ?></span>
                    </div>
                    
                    <a href="../user/my-account.php" class="nav-link font-medium py-2 px-4 rounded-lg hover:bg-green-50 transition flex items-center">
                        <i class="fas fa-user mr-2"></i> Minha Conta
                    </a>
                    
                    <?php if (admin()) { ?>
                    <a href="../plants/form-register-plants.php" class="nav-link font-medium py-2 px-4 rounded-lg hover:bg-green-50 transition flex items-center">
                        <i class="fas fa-plus mr-2"></i> Inserir Plantas
                    </a>
                    <a href="../species/form-register-species.php" class="nav-link font-medium py-2 px-4 rounded-lg hover:bg-green-50 transition flex items-center">
                        <i class="fas fa-box mr-2"></i> Inserir Espécies
                    </a>
                    <a href="../requests/requests.php" class="nav-link font-medium py-2 px-4 rounded-lg hover:bg-green-50 transition flex items-center">
                        <i class="fas fa-clipboard-list mr-2"></i> Requisições
                    </a>
                    <?php } ?>
                    
                    <?php if (!esp() && !admin()) { ?>
                    <a href="../user/form-insert-user-esp.php" class="nav-link font-medium py-2 px-4 rounded-lg hover:bg-green-50 transition flex items-center">
                        <i class="fas fa-star mr-2"></i> Virar Especialista
                    </a>
                    <?php } ?>
                    
                    <a href="../config/exit.php" class="text-red-600 font-medium py-2 px-4 rounded-lg hover:bg-red-50 transition flex items-center mt-2">
                        <i class="fas fa-sign-out-alt mr-2"></i> Sair
                    </a>
                </div>
                <?php
                }
                ?>
            </nav>
        </div>
    </header>

    <main class="pt-4">