<?php
session_start();
require '../config/authentication.php';

require '../parts/header.php';
?>

<body class="text-gray-800">
    
    <!-- Hero Section -->
    <section id="home" class="relative bg-gradient-to-br from-green-50 to-green-100 custom-shape py-20 md:py-32 overflow-hidden mt-4">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6" style="color: var(--verde-primario);">
                    Projeto <span class="italic">Physis</span><br>Lado a Lado com a Natureza
                </h2>
            </div>
            
            <div class="md:w-1/2 flex justify-center">
                <div class="relative w-full max-w-md">
                    <div class="bg-white rounded-2xl shadow-xl p-6 transform rotate-3 floating-slow">
                        <div class="flex items-center mb-4">
                            <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <div class="bg-green-100 rounded-lg p-4 mb-4">
                            <h3 class="font-bold text-lg mb-2" style="color: var(--verde-primario);">Cultive seu próprio jardim digital</h3>
                            <p class="text-sm text-gray-600 mb-4">Descubra, aprenda e cuide de plantas com nossa plataforma completa. Desde identificação até dicas personalizadas de cuidados.</p>
                            <a href="#sobre" class="btn-plant text-white px-4 py-2 rounded-full text-sm font-medium inline-block">
                                Explore Agora
                            </a>
                        </div>
                    </div>
                    
                    <div class="absolute -bottom-6 -left-6 bg-green-200 rounded-2xl w-32 h-32 -z-10 transform -rotate-12 floating"></div>
                    <div class="absolute -top-6 -right-6 bg-green-300 rounded-2xl w-24 h-24 -z-10 transform rotate-12 floating-fast"></div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats and Info Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">
                <!-- Stat Card -->
                <div class="bg-gradient-to-br from-green-600 to-green-500 rounded-2xl p-8 text-white relative overflow-hidden fade-in">
                    <div class="absolute top-0 right-0 w-32 h-32 stat-pattern"></div>
                    <div class="text-5xl md:text-6xl font-light mb-2">500+</div>
                    <div class="text-lg font-medium mb-1">Espécies de Plantas</div>
                    <div class="text-sm opacity-90">Sempre<br>Atualizando<br>Nosso Banco de Dados</div>
                </div>
                
                <!-- Info Card -->
                <div class="bg-green-50 rounded-2xl p-8 flex flex-col md:flex-row items-center gap-6 fade-in">
                    <div class="w-full md:w-1/3 h-48 bg-gradient-to-br from-green-200 to-green-300 rounded-xl flex items-center justify-center">
                        <i class="fas fa-leaf text-6xl opacity-30" style="color: var(--verde-primario);"></i>
                    </div>
                    <div class="w-full md:w-2/3">
                        <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--verde-primario);">Sobre o <span style="color: var(--verde-secundario);">Projeto</span></h2>
                        <p class="text-gray-600 mb-3">O Physis nasceu da paixão por plantas e tecnologia, criando uma ponte entre o conhecimento botânico e a vida moderna.</p>
                        <p class="text-gray-600">Nossa missão é tornar o cuidado com plantas acessível a todos, oferecendo ferramentas intuitivas e informações precisas para cultivar seu jardim pessoal.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Services Section -->
    <section class="py-16 bg-green-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4" style="color: var(--verde-primario);">Nossos <span style="color: var(--verde-secundario);">Serviços</span></h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Card 1 - Plantas -->
                <div class="plant-card bg-white rounded-2xl shadow-lg overflow-hidden fade-in">
                    <div class="h-48 bg-gradient-to-br from-green-100 to-green-200 relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-spa text-6xl opacity-20" style="color: var(--verde-primario);"></i>
                        </div>
                        <div class="absolute bottom-4 right-4 bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-md">
                            <i class="fas fa-leaf" style="color: var(--verde-primario);"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3" style="color: var(--verde-primario);">Plantas</h3>
                        <p class="text-gray-600 mb-4">Explore nosso catálogo completo com centenas de espécies, informações detalhadas e guias de cuidados.</p>
                        <a href="../plants/cards-plants.php" class="btn-plant text-white px-4 py-2 rounded-full text-sm font-medium inline-block">
                            Ver Plantas <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Card 2 - Jardim -->
                <div class="plant-card bg-white rounded-2xl shadow-lg overflow-hidden fade-in">
                    <div class="h-48 bg-gradient-to-br from-green-200 to-green-300 relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-seedling text-6xl opacity-20" style="color: var(--verde-primario);"></i>
                        </div>
                        <div class="absolute bottom-4 right-4 bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-md">
                            <i class="fas fa-tree" style="color: var(--verde-primario);"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3" style="color: var(--verde-primario);">Meu Jardim</h3>
                        <p class="text-gray-600 mb-4">Gerencie suas plantas favoritas, acompanhe o crescimento e receba lembretes personalizados de cuidados.</p>
                        <a href="../garden/my-garden.php" class="btn-plant text-white px-4 py-2 rounded-full text-sm font-medium inline-block">
                            Acessar Jardim <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Card 3 - Perfil -->
                <div class="plant-card bg-white rounded-2xl shadow-lg overflow-hidden fade-in">
                    <div class="h-48 bg-gradient-to-br from-green-300 to-green-400 relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-user text-6xl opacity-20" style="color: var(--verde-primario);"></i>
                        </div>
                        <div class="absolute bottom-4 right-4 bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-md">
                            <i class="fas fa-user-circle" style="color: var(--verde-primario);"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3" style="color: var(--verde-primario);">Seu Perfil</h3>
                        <p class="text-gray-600 mb-4">Personalize sua experiência, acompanhe seu progresso e conecte-se com outros amantes de plantas.</p>
                        <a href="../user/account.php" class="btn-plant text-white px-4 py-2 rounded-full text-sm font-medium inline-block">
                            Ver Perfil <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Alert Section (se necessário) -->
    <div id="alert-container" class="container mx-auto px-4 mb-8">
        <!-- O alerta será inserido aqui via JavaScript se necessário -->
    </div>


<?php
require '../parts/footer.php';
?>