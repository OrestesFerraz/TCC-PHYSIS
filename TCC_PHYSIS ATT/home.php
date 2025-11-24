<?php
session_start();
require 'config/authentication.php';

require 'parts/header.php';
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

    .main-container {
        max-width: 1400px;
        margin: 0 auto;
        background: white;
        box-shadow: 0 0 40px rgba(0,0,0,0.1);
    }

    /* Hero Section */
    .hero-physis {
        position: relative;
        height: 500px;
        background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('../img/hero-background.jpg');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        margin: 20px;
        display: flex;
        align-items: center;
        padding: 0 80px;
        overflow: hidden;
    }

    .hero-content-physis {
        max-width: 600px;
        color: white;
        z-index: 2;
    }

    .hero-physis h1 {
        font-size: 48px;
        line-height: 1.2;
        margin-bottom: 20px;
        font-weight: 300;
    }

    .hero-physis h1 span {
        font-weight: 600;
    }

    .hero-description-physis {
        position: absolute;
        right: 80px;
        background: rgba(255,255,255,0.95);
        padding: 30px;
        border-radius: 12px;
        max-width: 350px;
        color: #333;
    }

    .hero-description-physis h3 {
        color: #2d5016;
        margin-bottom: 15px;
        font-size: 18px;
    }

    .hero-description-physis p {
        font-size: 14px;
        line-height: 1.6;
        color: #666;
        margin-bottom: 20px;
    }

    .hero-btn-physis {
        background: white;
        color: #16a34a;
        padding: 12px 24px;
        border: 2px solid #16a34a;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .hero-btn-physis:hover {
        background: #16a34a;
        color: white;
    }

    /* Stats Section */
    .stats-section-physis {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        padding: 60px 40px;
    }

    .stat-card-physis {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        padding: 50px;
        border-radius: 20px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .stat-card-physis::before {
        content: '';
        position: absolute;
        top: 20px;
        right: 20px;
        width: 100px;
        height: 100px;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M20,50 Q30,30 40,50 T60,50 T80,50" stroke="rgba(255,255,255,0.2)" stroke-width="3" fill="none"/><path d="M20,60 Q30,40 40,60 T60,60 T80,60" stroke="rgba(255,255,255,0.2)" stroke-width="3" fill="none"/><path d="M20,70 Q30,50 40,70 T60,70 T80,70" stroke="rgba(255,255,255,0.2)" stroke-width="3" fill="none"/></svg>');
        opacity: 0.3;
    }

    .stat-number-physis {
        font-size: 56px;
        font-weight: 300;
        margin-bottom: 10px;
    }

    .stat-label-physis {
        font-size: 14px;
        opacity: 0.9;
    }

    .stat-sublabel-physis {
        font-size: 12px;
        opacity: 0.8;
        margin-top: 5px;
    }

    .info-card-physis {
        background: #f8f9f5;
        padding: 50px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 40px;
    }

    .info-card-physis img {
        width: 250px;
        height: 200px;
        object-fit: cover;
        border-radius: 12px;
    }

    .info-content-physis h2 {
        font-size: 28px;
        margin-bottom: 20px;
        color: #2d5016;
    }

    .info-content-physis h2 span {
        color: #16a34a;
    }

    .info-content-physis p {
        font-size: 14px;
        line-height: 1.8;
        color: #666;
        margin-bottom: 15px;
    }

    /* Services Section */
    .services-section-physis {
        padding: 60px 40px;
    }

    .section-title-physis {
        font-size: 42px;
        margin-bottom: 50px;
        font-weight: 300;
        text-align: center;
        color: #2d5016;
    }

    .section-title-physis span {
        font-weight: 600;
        color: #16a34a;
    }

    .services-grid-physis {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .service-card-physis {
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        height: 400px;
        transition: transform 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .service-card-physis:hover {
        transform: translateY(-10px);
    }

    .service-card-physis img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-overlay-physis {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);
        padding: 40px 30px;
        color: white;
    }

    .service-overlay-physis h3 {
        font-size: 24px;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .service-overlay-physis p {
        font-size: 14px;
        line-height: 1.6;
        opacity: 0.95;
        margin-bottom: 20px;
    }

    .service-btn-physis {
        background: rgba(255,255,255,0.9);
        color: #16a34a;
        padding: 10px 24px;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        font-weight: 600;
    }

    .service-btn-physis:hover {
        background: white;
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(255,255,255,0.3);
    }

    /* Alert Section */
    .alert-physis {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin: 40px;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    }

    .alert-physis h4 {
        font-size: 24px;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .alert-physis p {
        font-size: 14px;
        opacity: 0.95;
    }

    @media (max-width: 968px) {
        .hero-physis {
            height: auto;
            flex-direction: column;
            padding: 40px 30px;
        }

        .hero-description-physis {
            position: static;
            margin-top: 30px;
            right: auto;
        }

        .stats-section-physis {
            grid-template-columns: 1fr;
        }

        .info-card-physis {
            flex-direction: column;
        }

        .services-grid-physis {
            grid-template-columns: 1fr;
        }

        .hero-physis h1 {
            font-size: 36px;
        }

        .section-title-physis {
            font-size: 32px;
        }
    }

    /* Animation */
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="main-container">
    <!-- Hero Section -->
    <section id="home" class="hero-physis">
        <div class="hero-content-physis">
            <h1>Projeto <span>Physis</span><br>Lado a Lado com a Natureza</h1>
        </div>
        <div class="hero-description-physis">
            <h3>Cultive seu próprio jardim digital</h3>
            <p>Descubra, aprenda e cuide de plantas com nossa plataforma completa. Desde identificação até dicas personalizadas de cuidados.</p>
            <a href="#sobre" class="hero-btn-physis">Explore Agora</a>
        </div>
    </section>

    <!-- Stats and Info Section -->
    <section class="stats-section-physis">
        <div class="stat-card-physis fade-in">
            <div class="stat-number-physis">500+</div>
            <div class="stat-label-physis">Espécies de Plantas</div>
            <div class="stat-sublabel-physis">Sempre<br>Atualizando<br>Nosso Banco de Dados</div>
        </div>
        <div class="info-card-physis fade-in">
            <img src="../img/plantas.jpg" alt="Natureza">
            <div class="info-content-physis">
                <h2>Sobre o <span>Projeto</span></h2>
                <p>O Physis nasceu da paixão por plantas e tecnologia, criando uma ponte entre o conhecimento botânico e a vida moderna.</p>
                <p>Nossa missão é tornar o cuidado com plantas acessível a todos, oferecendo ferramentas intuitivas e informações precisas para cultivar seu jardim pessoal.</p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section-physis">
        <h2 class="section-title-physis">Nossos <span>Serviços</span></h2>
        
        <div class="services-grid-physis">
            <!-- Card 1 - Plantas -->
            <div class="service-card-physis fade-in">
                <img src="../img/plantas.jpg" alt="Plantas">
                <div class="service-overlay-physis">
                    <h3>Plantas</h3>
                    <p>Explore nosso catálogo completo com centenas de espécies, informações detalhadas e guias de cuidados.</p>
                    <a href="../plants/cards-plants.php" class="service-btn-physis">Ver Plantas →</a>
                </div>
            </div>

            <!-- Card 2 - Jardim -->
            <div class="service-card-physis fade-in">
                <img src="../img/jardim.jpg" alt="Jardim">
                <div class="service-overlay-physis">
                    <h3>Meu Jardim</h3>
                    <p>Gerencie suas plantas favoritas, acompanhe o crescimento e receba lembretes personalizados de cuidados.</p>
                    <a href="../garden/my-garden.php" class="service-btn-physis">Acessar Jardim →</a>
                </div>
            </div>

            <!-- Card 3 - Perfil -->
            <div class="service-card-physis fade-in">
                <img src="../img/perfil.jpg" alt="Perfil">
                <div class="service-overlay-physis">
                    <h3>Seu Perfil</h3>
                    <p>Personalize sua experiência, acompanhe seu progresso e conecte-se com outros amantes de plantas.</p>
                    <a href="../user/account.php" class="service-btn-physis">Ver Perfil →</a>
                </div>
            </div>
        </div>
    </section>

    <?php
    if (isset($_SESSION["restrito"]) && $_SESSION["restrito"]) {
    ?>
        <div class="alert-physis" role="alert">
            <h4>⚠️ Esta é uma página PROTEGIDA!</h4>
            <p>Você está tentando acessar um conteúdo restrito. Por favor, faça login para continuar.</p>
        </div>
    <?php
        unset($_SESSION["restrito"]);
    }
    ?>
</div>

<script>
    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Scroll animation
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });
</script>

<?php
require 'parts/footer.php';
?>