<?php
session_start();
require '../config/authentication.php';

require '../parts/header.php';
?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f5f0;
        color: #333;
    }

    .faqs-container {
        max-width: 1400px;
        margin: 0 auto;
        background: white;
        box-shadow: 0 0 40px rgba(0,0,0,0.1);
    }

    /* Hero FAQs */
    .hero-faqs {
        position: relative;
        background: linear-gradient(135deg, #16a34a, #22c55e);
        padding: 80px 40px;
        text-align: center;
        color: white;
        border-radius: 0 0 40px 40px;
    }

    .hero-faqs::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="60" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="80" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
        opacity: 0.3;
        pointer-events: none;
    }

    .hero-faqs h1 {
        font-size: 48px;
        margin-bottom: 20px;
        font-weight: 300;
        position: relative;
        z-index: 1;
    }

    .hero-faqs h1 span {
        font-weight: 600;
    }

    .hero-faqs p {
        font-size: 18px;
        margin-bottom: 40px;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }

    /* Search Bar */
    .search-container {
        max-width: 700px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .search-input {
        width: 100%;
        padding: 18px 60px 18px 25px;
        border-radius: 50px;
        border: none;
        font-size: 15px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        transition: all 0.3s;
    }

    .search-input:focus {
        outline: none;
        box-shadow: 0 12px 40px rgba(0,0,0,0.2);
        transform: translateY(-2px);
    }

    .search-icon {
        position: absolute;
        right: 25px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        color: #999;
    }

    /* Categories */
    .categories-section {
        padding: 60px 40px 40px;
        text-align: center;
    }

    .categories-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        max-width: 1000px;
        margin: 0 auto;
    }

    .category-btn-faqs {
        padding: 14px 32px;
        border-radius: 30px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        letter-spacing: 0.5px;
    }

    .category-btn-faqs.active {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: white;
        box-shadow: 0 4px 15px rgba(22, 163, 74, 0.3);
    }

    .category-btn-faqs:not(.active) {
        background: #f8f9f5;
        color: #2d5016;
    }

    .category-btn-faqs:not(.active):hover {
        background: #e8f3e8;
        transform: translateY(-2px);
    }

    /* FAQ Section */
    .faqs-content {
        padding: 40px;
    }

    .faq-category-section {
        margin-bottom: 60px;
    }

    .category-title {
        font-size: 32px;
        margin-bottom: 30px;
        color: #2d5016;
        font-weight: 300;
        padding-left: 20px;
        border-left: 4px solid #16a34a;
    }

    .category-title span {
        font-weight: 600;
    }

    .faq-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .faq-item-card {
        background: #f8f9f5;
        border-radius: 20px;
        padding: 30px;
        transition: all 0.3s;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .faq-item-card:hover {
        border-color: #16a34a;
        box-shadow: 0 8px 25px rgba(22, 163, 74, 0.1);
    }

    .faq-question-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .faq-question-text {
        font-size: 18px;
        font-weight: 600;
        color: #2d5016;
        flex: 1;
    }

    .faq-icon-toggle {
        width: 24px;
        height: 24px;
        color: #16a34a;
        transition: transform 0.3s;
        flex-shrink: 0;
    }

    .faq-item-card.open .faq-icon-toggle {
        transform: rotate(180deg);
    }

    .faq-answer-wrapper {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease;
    }

    .faq-item-card.open .faq-answer-wrapper {
        max-height: 500px;
    }

    .faq-answer-text {
        font-size: 15px;
        line-height: 1.8;
        color: #666;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    .faq-answer-text strong {
        color: #16a34a;
        font-weight: 600;
    }

    /* Contact Section */
    .contact-section {
        background: linear-gradient(135deg, #2d5016, #3d6b1f);
        padding: 60px 40px;
        margin: 60px 40px 40px;
        border-radius: 25px;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .contact-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .contact-section::after {
        content: '';
        position: absolute;
        bottom: -50%;
        left: -50%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .contact-section h2 {
        font-size: 36px;
        margin-bottom: 15px;
        font-weight: 300;
        position: relative;
        z-index: 1;
    }

    .contact-section h2 span {
        font-weight: 600;
    }

    .contact-section p {
        font-size: 16px;
        margin-bottom: 30px;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }

    .contact-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }

    .contact-btn {
        padding: 15px 35px;
        border-radius: 30px;
        border: none;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .contact-btn.primary {
        background: white;
        color: #16a34a;
    }

    .contact-btn.primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(255,255,255,0.3);
    }

    .contact-btn.secondary {
        background: rgba(255,255,255,0.2);
        color: white;
        backdrop-filter: blur(10px);
    }

    .contact-btn.secondary:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-3px);
    }

    @media (max-width: 768px) {
        .hero-faqs h1 {
            font-size: 32px;
        }

        .hero-faqs {
            padding: 60px 30px;
        }

        .categories-grid {
            justify-content: flex-start;
        }

        .category-title {
            font-size: 24px;
        }

        .faq-question-text {
            font-size: 16px;
        }

        .contact-section h2 {
            font-size: 28px;
        }

        .contact-buttons {
            flex-direction: column;
        }

        .contact-btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Animation */
    .fade-in-faq {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in-faq.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="faqs-container">
    <!-- Hero Section -->
    <section class="hero-faqs">
        <h1>Perguntas <span>Frequentes</span></h1>
        <p>Encontre respostas para as dúvidas mais comuns sobre o Projeto Physis</p>
        
        <div class="search-container">
            <input type="text" id="searchFAQs" class="search-input" placeholder="Buscar nas perguntas...">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories-section">
        <div class="categories-grid">
            <button class="category-btn-faqs active" data-category="all">📋 Todas as Perguntas</button>
            <button class="category-btn-faqs" data-category="conta">👤 Conta e Cadastro</button>
            <button class="category-btn-faqs" data-category="plantas">🌱 Plantas e Cultivo</button>
            <button class="category-btn-faqs" data-category="jardim">🏡 Meu Jardim</button>
        </div>
    </section>

    <!-- FAQs Content -->
    <section class="faqs-content">
        
        <!-- Categoria: Conta e Cadastro -->
        <div class="faq-category-section fade-in-faq" data-category="conta">
            <h2 class="category-title"><span>Conta</span> e Cadastro</h2>
            
            <div class="faq-list">
                <div class="faq-item-card">
                    <div class="faq-question-wrapper">
                        <span class="faq-question-text">Como criar uma conta no Projeto Physis?</span>
                        <svg class="faq-icon-toggle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer-wrapper">
                        <p class="faq-answer-text">
                            Para criar uma conta, clique no botão "Cadastro" no canto superior direito do site. 
                            Preencha o formulário com seu nome completo, email e senha. Após o cadastro, você 
                            receberá um email de confirmação. Basta clicar no link do email para ativar sua conta.
                        </p>
                    </div>
                </div>

                <div class="faq-item-card">
                    <div class="faq-question-wrapper">
                        <span class="faq-question-text">Esqueci minha senha, o que fazer?</span>
                        <svg class="faq-icon-toggle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer-wrapper">
                        <p class="faq-answer-text">
                            Na página de login, clique em "Esqueci minha senha". Digite o email cadastrado e 
                            enviaremos um link para redefinir sua senha. O link é válido por 24 horas.
                        </p>
                    </div>
                </div>

                <div class="faq-item-card">
                    <div class="faq-question-wrapper">
                        <span class="faq-question-text">Posso alterar meu email cadastrado?</span>
                        <svg class="faq-icon-toggle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer-wrapper">
                        <p class="faq-answer-text">
                            Por questões de segurança, o email principal não pode ser alterado. Se precisar 
                            atualizar seu email, entre em contato com nosso suporte através do email 
                            <strong>suporte@physis.com</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categoria: Plantas e Cultivo -->
        <div class="faq-category-section fade-in-faq" data-category="plantas">
            <h2 class="category-title"><span>Plantas</span> e Cultivo</h2>
            
            <div class="faq-list">
                <div class="faq-item-card">
                    <div class="faq-question-wrapper">
                        <span class="faq-question-text">Como adicionar plantas ao meu jardim?</span>
                        <svg class="faq-icon-toggle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer-wrapper">
                        <p class="faq-answer-text">
                            Após fazer login, vá até a página "Plantas" e clique no botão "Adicionar ao meu jardim" 
                            na planta desejada. Você também pode cadastrar plantas personalizadas através do 
                            formulário "Cadastrar Plantas".
                        </p>
                    </div>
                </div>

                <div class="faq-item-card">
                    <div class="faq-question-wrapper">
                        <span class="faq-question-text">Onde encontro informações sobre cuidados com as plantas?</span>
                        <svg class="faq-icon-toggle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer-wrapper">
                        <p class="faq-answer-text">
                            Cada planta em nosso catálogo possui uma página detalhada com informações completas 
                            sobre cultivo, rega, adubação, poda e cuidados específicos. Basta clicar em "Ver detalhes" 
                            na planta desejada.
                        </p>
                    </div>
                </div>

                <div class="faq-item-card">
                    <div class="faq-question-wrapper">
                        <span class="faq-question-text">Posso sugerir novas plantas para o catálogo?</span>
                        <svg class="faq-icon-toggle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer-wrapper">
                        <p class="faq-answer-text">
                            Sim! Adoramos sugestões da comunidade. Envie suas sugestões para 
                            <strong>sugestoes@physis.com</strong> com o nome da planta e informações básicas. 
                            Nossa equipe analisará e poderá incluí-la no catálogo.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categoria: Meu Jardim -->
        <div class="faq-category-section fade-in-faq" data-category="jardim">
            <h2 class="category-title"><span>Meu</span> Jardim</h2>
            
            <div class="faq-list">
                <div class="faq-item-card">
                    <div class="faq-question-wrapper">
                        <span class="faq-question-text">Quantos jardins posso criar?</span>
                        <svg class="faq-icon-toggle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer-wrapper">
                        <p class="faq-answer-text">
                            Você pode criar até 5 jardins diferentes. Isso permite organizar suas plantas por 
                            ambientes (sala, quarto, varanda) ou por tipos (suculentas, ervas, flores).
                        </p>
                    </div>
                </div>

                <div class="faq-item-card">
                    <div class="faq-question-wrapper">
                        <span class="faq-question-text">Como recebo lembretes de cuidados?</span>
                        <svg class="faq-icon-toggle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer-wrapper">
                        <p class="faq-answer-text">
                            Configure os lembretes nas configurações de cada planta no seu jardim. Você pode 
                            receber notificações por email ou no aplicativo sobre rega, adubação e outros 
                            cuidados necessários.
                        </p>
                    </div>
                </div>

                <div class="faq-item-card">
                    <div class="faq-question-wrapper">
                        <span class="faq-question-text">Posso compartilhar meu jardim com amigos?</span>
                        <svg class="faq-icon-toggle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer-wrapper">
                        <p class="faq-answer-text">
                            Sim! Na página do seu jardim, clique em "Compartilhar" e escolha entre gerar um 
                            link público ou convidar amigos específicos por email. Eles poderão ver suas 
                            plantas mas não editar informações.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <h2>Ainda com <span>Dúvidas?</span></h2>
        <p>Nossa equipe de suporte está pronta para ajudar você!</p>
        
        <div class="contact-buttons">
            <a href="mailto:suporte@physis.com" class="contact-btn primary">
                📧 Enviar Email
            </a>
            <?php if (autenticado()): ?>
                <a href="../user/list-esp.php" class="contact-btn secondary">
                    💬 Chat com Especialista
                </a>
            <?php else: ?>
                <a href="../user/form-login.php" class="contact-btn secondary">
                    💬 Fazer Login para Chat
                </a>
            <?php endif; ?>
        </div>
    </section>
</div>

<script>
    // Search functionality
    document.getElementById('searchFAQs').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const faqItems = document.querySelectorAll('.faq-item-card');
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question-text').textContent.toLowerCase();
            const answer = item.querySelector('.faq-answer-text').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Category filter
    document.querySelectorAll('.category-btn-faqs').forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active button
            document.querySelectorAll('.category-btn-faqs').forEach(b => {
                b.classList.remove('active');
            });
            this.classList.add('active');
            
            // Filter categories
            const categories = document.querySelectorAll('.faq-category-section');
            categories.forEach(cat => {
                if (category === 'all' || cat.dataset.category === category) {
                    cat.style.display = '';
                } else {
                    cat.style.display = 'none';
                }
            });
        });
    });

    // Accordion functionality
    document.querySelectorAll('.faq-item-card').forEach(card => {
        card.addEventListener('click', function() {
            const isOpen = this.classList.contains('open');
            
            // Close all cards
            document.querySelectorAll('.faq-item-card').forEach(c => {
                c.classList.remove('open');
            });
            
            // Open clicked card if it was closed
            if (!isOpen) {
                this.classList.add('open');
            }
        });
    });

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

    document.querySelectorAll('.fade-in-faq').forEach(el => {
        observer.observe(el);
    });
</script>

<?php
require '../parts/footer.php';
?>