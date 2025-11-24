<?php
session_start();
require '../config/authentication.php';
require '../config/connection.php';

// Verificar se o ID da planta foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
  $_SESSION["msg_erro"] = "Planta não encontrada";
  redireciona("../admin/list-plants.php");
  die();
}

$id_planta = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_planta) {
  $_SESSION["msg_erro"] = "ID inválido";
  redireciona("../admin/list-plants.php");
  die();
}

// Buscar dados da planta
$sql = "SELECT p.*, 
               e.nome as especie_nome, e.descricao as especie_descricao,
               u.nome as usuario_nome
        FROM plantas p
        JOIN especies e ON e.id = p.id_especie
        JOIN usuarios u ON u.id = p.id_usuario
        WHERE p.id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$id_planta]);
$planta = $stmt->fetch();

// Verificar se a planta existe
if (!$planta) {
  $_SESSION["msg_erro"] = "Planta não encontrada";
  redireciona("../admin/list-plants.php");
  die();
}

require '../parts/header.php';
?>

<style>
/* Estilos da interface EcoVerde */
.ecoverde-container {
    max-width: 1400px;
    margin: 0 auto;
    background: white;
    box-shadow: 0 0 40px rgba(0,0,0,0.1);
}

.ecoverde-hero {
    position: relative;
    height: 500px;
    background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=1600&q=80');
    background-size: cover;
    background-position: center;
    border-radius: 20px;
    margin: 20px;
    display: flex;
    align-items: center;
    padding: 0 60px;
    overflow: hidden;
}

.ecoverde-hero-content {
    max-width: 600px;
    color: white;
    z-index: 2;
}

.ecoverde-hero h1 {
    font-size: 48px;
    line-height: 1.2;
    margin-bottom: 20px;
    font-weight: 300;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.ecoverde-hero h1 span {
    font-weight: 600;
    color: #7fb069;
}

.ecoverde-card {
    background: #f8f9f5;
    padding: 40px;
    border-radius: 20px;
    margin: 20px;
}

.ecoverde-stat-card {
    background: linear-gradient(135deg, #7fb069, #9fc482);
    padding: 40px;
    border-radius: 20px;
    color: white;
    position: relative;
    overflow: hidden;
}

.ecoverde-stat-card::before {
    content: '';
    position: absolute;
    top: 20px;
    right: 20px;
    width: 80px;
    height: 80px;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M20,50 Q30,30 40,50 T60,50 T80,50" stroke="rgba(255,255,255,0.2)" stroke-width="3" fill="none"/><path d="M20,60 Q30,40 40,60 T60,60 T80,60" stroke="rgba(255,255,255,0.2)" stroke-width="3" fill="none"/><path d="M20,70 Q30,50 40,70 T60,70 T80,70" stroke="rgba(255,255,255,0.2)" stroke-width="3" fill="none"/></svg>');
    opacity: 0.3;
}

.ecoverde-btn-primary {
    background: #7fb069;
    color: white;
    padding: 12px 28px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    text-decoration: none;
    display: inline-block;
}

.ecoverde-btn-primary:hover {
    background: #6a9956;
}

.ecoverde-btn-secondary {
    background: white;
    color: #7fb069;
    padding: 12px 24px;
    border: 2px solid #7fb069;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    text-decoration: none;
    display: inline-block;
}

.ecoverde-btn-secondary:hover {
    background: #7fb069;
    color: white;
}

.process-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin: 40px 0;
}

.process-item {
    background: #f8f9f5;
    border-radius: 20px;
    padding: 40px;
    position: relative;
}

.process-number {
    font-size: 80px;
    font-weight: 300;
    color: #e8f3e8;
    position: absolute;
    top: 20px;
    right: 30px;
    line-height: 1;
}

.process-content h3 {
    font-size: 24px;
    color: #2d5016;
    margin-bottom: 20px;
    font-weight: 600;
}

.process-content h3 span {
    color: #7fb069;
    font-weight: 300;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin: 30px 0;
}

.info-card {
    background: #f8f9f5;
    padding: 30px;
    border-radius: 15px;
}

.plant-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 20px;
}

.tags-container {
    display: flex;
    gap: 10px;
    margin: 20px 0;
}

.tag {
    background: #e8f3e8;
    color: #2d5016;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
}

.care-section {
    background: linear-gradient(135deg, #7fb069, #9fc482);
    border-radius: 20px;
    padding: 50px;
    margin: 40px 20px;
    color: white;
}

.care-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-top: 30px;
}

.care-column h3 {
    font-size: 24px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.care-step {
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
    color: #333;
}

.care-step h4 {
    color: #2d5016;
    margin-bottom: 10px;
    font-size: 16px;
}

.care-step p {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
}

.tips-box {
    background: rgba(255,255,255,0.2);
    border-radius: 15px;
    padding: 30px;
    margin-top: 30px;
    border-left: 4px solid #ffd700;
}

.action-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 40px 20px;
    padding-top: 30px;
    border-top: 1px solid #f0f0f0;
}

@media (max-width: 768px) {
    .ecoverde-hero {
        height: auto;
        padding: 40px 30px;
        text-align: center;
    }
    
    .ecoverde-hero h1 {
        font-size: 32px;
    }
    
    .process-grid,
    .info-grid,
    .care-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 20px;
        align-items: stretch;
    }
}
</style>

<div class="ecoverde-container">
    <!-- Basic Information -->
    <section class="ecoverde-card">
        <div class="info-grid">
            <!-- Plant Image -->
            <div>
                <?php if (!empty($planta['urlfoto'])): ?>
                    <img src="<?= htmlspecialchars($planta['urlfoto']) ?>" alt="<?= htmlspecialchars($planta['nome']) ?>" class="plant-image">
                <?php else: ?>
                    <div class="plant-image" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
                        Sem imagem disponível
                    </div>
                <?php endif; ?>
            </div>

            <!-- Plant Information -->
            <div>
                <h2 style="font-size: 32px; color: #2d5016; margin-bottom: 20px; font-weight: 600;"><?= htmlspecialchars($planta['nome']) ?></h2>
                <p style="color: #7fb069; font-size: 18px; margin-bottom: 20px;"><?= htmlspecialchars($planta['especie_nome']) ?></p>
                
                <!-- Tags -->
                <div class="tags-container">
                    <span class="tag"><?= htmlspecialchars($planta['especie_nome']) ?></span>
                    <?php if (!empty($planta['dificuldade'])): ?>
                        <span class="tag"><?= htmlspecialchars($planta['dificuldade']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <div style="margin: 30px 0;">
                    <h3 style="color: #2d5016; margin-bottom: 15px; font-size: 20px;">Sobre esta planta</h3>
                    <p style="color: #666; line-height: 1.7;">
                        <?= !empty($planta['descricao']) ? nl2br(htmlspecialchars($planta['descricao'])) : 'Descrição não disponível.' ?>
                    </p>
                </div>

                <!-- Quick Info -->
                <div class="ecoverde-stat-card">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <div style="font-size: 14px; opacity: 0.9;">Espécie</div>
                            <div style="font-size: 18px; font-weight: 600;"><?= htmlspecialchars($planta['especie_nome']) ?></div>
                        </div>
                        <div>
                            <div style="font-size: 14px; opacity: 0.9;">Uso</div>
                            <div style="font-size: 16px;"><?= !empty($planta['uso']) ? htmlspecialchars($planta['uso']) : 'Não informado' ?></div>
                        </div>
                        <div>
                            <div style="font-size: 14px; opacity: 0.9;">Altura</div>
                            <div style="font-size: 16px;"><?= !empty($planta['altura']) ? htmlspecialchars($planta['altura']) : 'Não informada' ?></div>
                        </div>
                        <div>
                            <div style="font-size: 14px; opacity: 0.9;">Dificuldade</div>
                            <div style="font-size: 16px; font-weight: 600;"><?= !empty($planta['dificuldade']) ? htmlspecialchars($planta['dificuldade']) : 'Não informada' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Care Guide -->
    <section class="care-section">
        <h2 style="text-align: center; font-size: 36px; margin-bottom: 10px; font-weight: 300;">🌱 Como Plantar e Cuidar</h2>
        <p style="text-align: center; opacity: 0.9; margin-bottom: 40px;">Guia completo para o cultivo saudável da sua planta</p>
        
        <div class="care-grid">
            <!-- Planting -->
            <div>
                <h3>🪴 Plantio</h3>
                
                <div class="care-step">
                    <h4>1. Preparação do Solo</h4>
                    <p><?= !empty($planta['solo']) ? htmlspecialchars($planta['solo']) : 'Informação não disponível.' ?></p>
                </div>

                <div class="care-step">
                    <h4>2. Localização</h4>
                    <p><?= !empty($planta['locali']) ? htmlspecialchars($planta['locali']) : 'Informação não disponível.' ?></p>
                </div>

                <div class="care-step">
                    <h4>3. Plantio</h4>
                    <p><?= !empty($planta['plantio']) ? htmlspecialchars($planta['plantio']) : 'Informação não disponível.' ?></p>
                </div>
            </div>

            <!-- Care -->
            <div>
                <h3>💧 Cuidados Diários</h3>
                
                <div class="care-step">
                    <h4>1. Rega</h4>
                    <p><?= !empty($planta['rega']) ? htmlspecialchars($planta['rega']) : 'Informação não disponível.' ?></p>
                </div>

                <div class="care-step">
                    <h4>2. Adubação</h4>
                    <p><?= !empty($planta['adubacao']) ? htmlspecialchars($planta['adubacao']) : 'Informação não disponível.' ?></p>
                </div>

                <div class="care-step">
                    <h4>3. Poda</h4>
                    <p><?= !empty($planta['poda']) ? htmlspecialchars($planta['poda']) : 'Informação não disponível.' ?></p>
                </div>

                <div class="care-step">
                    <h4>4. Cuidados Gerais</h4>
                    <p><?= !empty($planta['cuidados']) ? htmlspecialchars($planta['cuidados']) : 'Informação não disponível.' ?></p>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="tips-box">
            <h4 style="color: #ffd700; margin-bottom: 15px;">💡 Dicas Importantes</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; font-size: 14px;">
                <div>• Observe regularmente a planta</div>
                <div>• Adapte os cuidados conforme as estações</div>
                <div>• Mantenha boa drenagem do solo</div>
                <div>• Considere as condições do ambiente</div>
            </div>
        </div>
    </section>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="cards-plants.php" class="ecoverde-btn-secondary">
            ← Voltar para a Lista de Plantas
        </a>

        <?php if (autenticado() || admin()): ?>
            <div style="display: flex; gap: 15px;">
                <!-- Add to Garden -->
                <a href="add-plant-garden.php?id=<?= $planta['id'] ?>" class="ecoverde-btn-primary"
                   onclick="return confirm('Adicionar <?= htmlspecialchars($planta['nome']) ?> ao seu jardim?')">
                    🌿 Adicionar ao Meu Jardim
                </a>

                <?php if (admin()): ?>
                    <a href="form-update-plants.php?id=<?= $planta['id'] ?>" class="ecoverde-btn-secondary">
                        ✏️ Editar Planta
                    </a>
                    <a href="delete-plants.php?id=<?= $planta['id'] ?>" class="ecoverde-btn-secondary" style="background: #dc3545; border-color: #dc3545; color: white;"
                       onclick="return confirm('Tem certeza que deseja excluir esta planta?')">
                        🗑️ Excluir Planta
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Scroll animation
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.care-step, .info-card, .ecoverde-stat-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});
</script>

<?php
require '../parts/footer.php';
?>