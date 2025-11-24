<?php
session_start();
require '../config/authentication.php';

if (!autenticado()) {
    $_SESSION["restrito"] = true;
    redireciona("../user/form-login.php");
    die();
}

require '../parts/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Cabeçalho da Página -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                Minha Conta
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Gerencie suas informações pessoais, preferências e acompanhe seu progresso no cuidado com as plantas
            </p>
        </div>

        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Coluna Lateral - Navegação -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Card de Navegação -->
                <div class="bg-white rounded-2xl shadow-lg border border-green-100 p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        Navegação
                    </h3>
                    <nav class="space-y-2">
                        <a href="account.php" class="flex items-center px-4 py-3 text-green-700 bg-green-50 rounded-xl font-medium border border-green-200 shadow-sm">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Perfil Público
                        </a>
                        <a href="change-pass.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-all duration-200 border border-transparent hover:border-gray-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            Alterar Senha
                        </a>
                        <a href="../garden/" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-all duration-200 border border-transparent hover:border-gray-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/>
                            </svg>
                            Meu Jardim
                        </a>
                        <a href="../chat/" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-all duration-200 border border-transparent hover:border-gray-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Mensagens
                        </a>
                        <a href="../plantas/" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-all duration-200 border border-transparent hover:border-gray-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            Catálogo
                        </a>
                    </nav>
                </div>

                <!-- Card de Status Rápido -->
                <div class="bg-white rounded-2xl shadow-lg border border-green-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status da Conta
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Membro desde</span>
                            <span class="text-sm font-medium text-gray-900"><?= date('d/m/Y', strtotime('-3 months')) ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tipo de conta</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-sm">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Premium
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Verificação</span>
                            <span class="inline-flex items-center text-green-600 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Verificado
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna Principal - Conteúdo -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Card de Perfil Principal -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-green-100">
                    <!-- Header do Perfil -->
                    <div class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 px-8 py-12">
                        <div class="flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-8">
                            <div class="relative group">
                                <div class="relative">
                                    <img src="<?= foto_usuario(); ?>" alt="Foto de perfil" 
                                         class="w-32 h-32 bg-white rounded-full object-cover border-4 border-white shadow-2xl transition-all duration-300 group-hover:scale-105">
                                    <div class="absolute inset-0 rounded-full bg-gradient-to-r from-green-400 to-emerald-400 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                                </div>
                                <button onclick="abrirModalFoto()" 
                                        class="absolute -bottom-2 -right-2 bg-green-500 hover:bg-green-600 rounded-full p-3 shadow-xl transition-all duration-200 hover:scale-110 group">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="text-center md:text-left">
                                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2"><?= nome_usuario(); ?></h1>
                                <p class="text-green-100 text-lg mb-4"><?= email_usuario(); ?></p>
                                <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                                    <a href="change-pass.php" 
                                       class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-white text-sm rounded-xl hover:bg-opacity-30 transition-all duration-200 backdrop-blur-sm border border-white border-opacity-30">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                        </svg>
                                        Alterar Senha
                                    </a>
                                    <span class="inline-flex items-center px-4 py-2 bg-yellow-400 bg-opacity-90 text-yellow-900 text-sm rounded-xl font-medium shadow-lg">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Conta Premium
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário de Edição -->
                    <div class="px-8 py-10">
                        <form action="update-user.php" method="POST" id="profileForm" class="space-y-8">
                            <input type="hidden" name="id" value="<?= id_usuario(); ?>">
                            
                            <div class="grid md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label for="nome" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Nome completo
                                    </label>
                                    <input type="text" id="nome" name="nome" required 
                                           minlength="3" maxlength="100"
                                           value="<?= htmlspecialchars(nome_usuario(), ENT_QUOTES) ?>"
                                           class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-3 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <p class="text-xs text-gray-500 mt-2">Mínimo 3, máximo 100 caracteres</p>
                                </div>
                                
                                <div class="space-y-2">
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        Email
                                    </label>
                                    <input type="email" id="email" disabled
                                           value="<?= htmlspecialchars(email_usuario(), ENT_QUOTES) ?>"
                                           class="w-full px-4 py-4 border border-gray-300 rounded-xl bg-gray-50 cursor-not-allowed shadow-sm">
                                    <p class="text-xs text-gray-500 mt-2">O email não pode ser alterado</p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-8 border-t border-gray-200">
                                <button type="submit" 
                                        class="flex-1 px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Salvar Alterações
                                </button>
                                <button type="reset" 
                                        class="flex-1 px-8 py-4 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200 font-semibold shadow-sm hover:shadow border border-gray-200">
                                    Descartar Alterações
                                </button>
                                <button type="button" 
                                        onclick="abrirModalExcluir()"
                                        class="flex-1 px-8 py-4 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 transition-all duration-200 font-semibold shadow-sm hover:shadow border border-red-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Excluir Conta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Grid de Estatísticas e Recursos -->
                <div class="grid lg:grid-cols-2 gap-8">
                    <!-- Estatísticas do Usuário -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-green-100">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Minhas Estatísticas
                        </h3>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="text-3xl font-bold text-green-600 mb-2">12</div>
                                <div class="text-sm font-medium text-gray-700">Plantas</div>
                                <div class="text-xs text-gray-500 mt-1">No jardim</div>
                            </div>
                            <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl border border-blue-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="text-3xl font-bold text-blue-600 mb-2">5</div>
                                <div class="text-sm font-medium text-gray-700">Espécies</div>
                                <div class="text-xs text-gray-500 mt-1">Diferentes</div>
                            </div>
                            <div class="text-center p-6 bg-gradient-to-br from-yellow-50 to-amber-50 rounded-2xl border border-yellow-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="text-3xl font-bold text-yellow-600 mb-2">30</div>
                                <div class="text-sm font-medium text-gray-700">Dias</div>
                                <div class="text-xs text-gray-500 mt-1">Ativos</div>
                            </div>
                            <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-violet-50 rounded-2xl border border-purple-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="text-3xl font-bold text-purple-600 mb-2">8</div>
                                <div class="text-sm font-medium text-gray-700">Cuidados</div>
                                <div class="text-xs text-gray-500 mt-1">Este mês</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card de Ajuda Rápida -->
                    <div class="bg-gradient-to-br from-green-500 via-emerald-500 to-teal-500 rounded-2xl shadow-xl p-8 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white bg-opacity-10 rounded-full translate-y-12 -translate-x-12"></div>
                        
                        <div class="relative z-10">
                            <h3 class="text-2xl font-semibold mb-4">Precisa de ajuda?</h3>
                            <p class="text-green-100 text-lg mb-6 leading-relaxed">
                                Nossa equipe está disponível 24/7 para ajudar você com sua conta e plantas.
                            </p>
                            <div class="space-y-4">
                                <a href="../faqs/" class="flex items-center text-green-50 hover:text-white text-base transition-all duration-200 group">
                                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Central de Ajuda
                                </a>
                                <a href="mailto:suporte@physis.com" class="flex items-center text-green-50 hover:text-white text-base transition-all duration-200 group">
                                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Email de Suporte
                                </a>
                                <a href="../chat/" class="flex items-center text-green-50 hover:text-white text-base transition-all duration-200 group">
                                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    Chat ao Vivo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Exclusão de Conta -->
<div id="modalExcluir" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-3xl p-10 max-w-md w-full mx-4 shadow-2xl transform transition-all duration-300 scale-95">
        <div class="flex items-center mb-6">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-red-600">Excluir Conta</h2>
                <p class="text-gray-600 text-sm mt-1">Ação permanente e irreversível</p>
            </div>
        </div>
        <p class="text-gray-700 mb-6 leading-relaxed">
            <strong class="text-red-600">Atenção:</strong> Esta ação não pode ser desfeita. Todos os seus dados, plantas, 
            jardins e histórico serão permanentemente excluídos do sistema.
        </p>
        
        <form action="delete-user.php" method="POST" id="deleteForm">
            <input type="hidden" name="id" value="<?= id_usuario(); ?>">
            
            <div class="mb-6">
                <label for="senha_confirmacao" class="block text-sm font-semibold text-gray-700 mb-3">
                    Digite sua senha para confirmar *
                </label>
                <input type="password" 
                       id="senha_confirmacao" 
                       name="senha_confirmacao" 
                       required
                       minlength="8"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 shadow-sm"
                       placeholder="Sua senha atual">
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl flex items-center justify-center">
                    Confirmar Exclusão
                </button>
                <button type="button" 
                        onclick="fecharModalExcluir()"
                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-200 font-semibold shadow-sm hover:shadow border border-gray-300">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Alteração de Foto -->
<div id="modalFoto" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-3xl p-10 max-w-md w-full mx-4 shadow-2xl transform transition-all duration-300 scale-95">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Alterar Foto de Perfil</h2>
        <p class="text-gray-600 mb-6">Escolha uma nova imagem para seu perfil</p>
        
        <form action="update-photo.php" method="POST" enctype="multipart/form-data" id="photoForm">
            <input type="hidden" name="id" value="<?= id_usuario(); ?>">
            
            <div class="mb-6">
                <div class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-dashed border-gray-300 rounded-2xl hover:border-green-400 transition-all duration-200 bg-gray-50 hover:bg-green-50">
                    <div class="space-y-3 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex flex-col sm:flex-row text-sm text-gray-600 justify-center items-center space-y-2 sm:space-y-0 sm:space-x-2">
                            <label for="foto_perfil" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none px-4 py-2 border border-green-200 rounded-lg hover:bg-green-50 transition-colors duration-200">
                                <span>Escolher arquivo</span>
                                <input id="foto_perfil" name="foto_perfil" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                            </label>
                            <p class="text-gray-500">ou arraste e solte</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF até 10MB</p>
                    </div>
                </div>
                <div id="imagePreview" class="mt-4 hidden text-center">
                    <img id="preview" class="mx-auto h-32 w-32 rounded-full object-cover border-4 border-green-200 shadow-md">
                </div>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                    Atualizar Foto
                </button>
                <button type="button" 
                        onclick="fecharModalFoto()"
                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-200 font-semibold shadow-sm hover:shadow border border-gray-300">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModalExcluir() {
    const modal = document.getElementById('modalExcluir');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.transform').classList.remove('scale-95');
        modal.querySelector('.transform').classList.add('scale-100');
    }, 10);
}

function fecharModalExcluir() {
    const modal = document.getElementById('modalExcluir');
    modal.querySelector('.transform').classList.remove('scale-100');
    modal.querySelector('.transform').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('senha_confirmacao').value = '';
    }, 200);
}

function abrirModalFoto() {
    const modal = document.getElementById('modalFoto');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.transform').classList.remove('scale-95');
        modal.querySelector('.transform').classList.add('scale-100');
    }, 10);
}

function fecharModalFoto() {
    const modal = document.getElementById('modalFoto');
    modal.querySelector('.transform').classList.remove('scale-100');
    modal.querySelector('.transform').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('foto_perfil').value = '';
        document.getElementById('imagePreview').classList.add('hidden');
    }, 200);
}

function previewImage(input) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Fechar modais ao clicar fora
document.getElementById('modalExcluir').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModalExcluir();
    }
});

document.getElementById('modalFoto').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModalFoto();
    }
});

// Validação do formulário
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const nome = document.getElementById('nome').value.trim();
    
    if (nome.length < 3 || nome.length > 100) {
        e.preventDefault();
        alert('O nome deve ter entre 3 e 100 caracteres.');
        document.getElementById('nome').focus();
        return;
    }
});

// Drag and drop para upload de imagem
const dropArea = document.querySelector('.border-dashed');
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false);
});

function highlight() {
    dropArea.classList.add('border-green-400', 'bg-green-50');
}

function unhighlight() {
    dropArea.classList.remove('border-green-400', 'bg-green-50');
}

dropArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    document.getElementById('foto_perfil').files = files;
    previewImage(document.getElementById('foto_perfil'));
}

// Auto-hide notifications
document.addEventListener('DOMContentLoaded', function() {
    const notifications = document.querySelectorAll('.fixed');
    notifications.forEach(notification => {
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    });
});
</script>

<?php
if (isset($_SESSION["result"])) {
    if ($_SESSION["result"] == true) {
        ?>
        <div class="fixed top-4 right-4 z-50 max-w-sm w-full animate-fade-in">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-xl shadow-2xl">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="font-bold text-green-900"><?= $_SESSION["msg_sucesso"] ?? 'Sucesso!' ?></h4>
                        <p class="text-sm text-green-700 mt-1">Alterações salvas com sucesso</p>
                    </div>
                </div>
            </div>
        </div>
        <?php
        unset($_SESSION["msg_sucesso"]);
    } else {
        ?>
        <div class="fixed top-4 right-4 z-50 max-w-sm w-full animate-fade-in">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-xl shadow-2xl">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="font-bold text-red-900"><?= $_SESSION["msg_erro"] ?? 'Erro!' ?></h4>
                        <p class="text-sm text-red-700 mt-1"><?= $_SESSION["erro"] ?? 'Ocorreu um erro ao processar sua solicitação.' ?></p>
                    </div>
                </div>
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