// ============================================
// CORREÇÕES APLICADAS:
// 1. Dropdown usando hidden/block do Tailwind
// 2. Menu mobile com fallback caso elementos não existam
// 3. Logs de debug removidos após testes
// ============================================

let currentStep = 1;
const totalSteps = 3;

function nextStep(step) {
    if (validateStep(currentStep)) {
        document.getElementById(`step-${currentStep}`).classList.add('hidden');
        currentStep = step;
        document.getElementById(`step-${currentStep}`).classList.remove('hidden');
        updateProgress();
    }
}

function prevStep(step) {
    document.getElementById(`step-${currentStep}`).classList.add('hidden');
    currentStep = step;
    document.getElementById(`step-${currentStep}`).classList.remove('hidden');
    updateProgress();
}

function updateProgress() {
    document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
        if (index + 1 < currentStep) {
            indicator.classList.add('bg-green-600', 'text-white');
            indicator.classList.remove('bg-gray-200', 'text-gray-500');
        } else if (index + 1 === currentStep) {
            indicator.classList.add('bg-green-500', 'text-white');
            indicator.classList.remove('bg-gray-200', 'text-gray-500');
        } else {
            indicator.classList.add('bg-gray-200', 'text-gray-500');
            indicator.classList.remove('bg-green-600', 'bg-green-500', 'text-white');
        }
    });

    document.getElementById('prev-btn')?.classList.toggle('hidden', currentStep === 1);
    document.getElementById('next-btn')?.classList.toggle('hidden', currentStep === totalSteps);
    document.getElementById('submit-btn')?.classList.toggle('hidden', currentStep !== totalSteps);
}

function validateStep(step) {
    let isValid = true;
    
    if (step === 1) {
        const nome = document.getElementById('nome');
        const email = document.getElementById('email');
        
        if (nome && (!nome.value || nome.value.length < 3)) {
            nome.classList.add('border-red-500');
            isValid = false;
        }
        
        if (email && (!email.value || !isValidEmail(email.value))) {
            email.classList.add('border-red-500');
            isValid = false;
        }
    } else if (step === 2) {
        const urlperfil = document.getElementById('urlperfil');
        if (urlperfil && !urlperfil.value) {
            urlperfil.classList.add('border-red-500');
            isValid = false;
        }
    }
    
    return isValid;
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

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

// Floating leaves animation
document.addEventListener('DOMContentLoaded', function () {
    const leavesContainer = document.body;
    const leafTypes = ['leaf-1', 'leaf-2', 'leaf-3', 'leaf-4', 'leaf-5'];
    const animations = ['floating', 'floating-slow', 'floating-fast', 'floating-reverse'];

    for (let i = 0; i < 15; i++) {
        const leaf = document.createElement('div');
        leaf.className = `leaf ${leafTypes[Math.floor(Math.random() * leafTypes.length)]} ${animations[Math.floor(Math.random() * animations.length)]}`;

        const top = Math.random() * 100;
        const left = Math.random() * 100;

        leaf.style.top = `${top}%`;
        leaf.style.left = `${left}%`;

        const size = 25 + Math.random() * 30;
        leaf.style.width = `${size}px`;
        leaf.style.height = `${size}px`;

        leaf.style.animationDelay = `${Math.random() * 5}s`;
        leaf.style.opacity = 0.4 + Math.random() * 0.4;

        leavesContainer.appendChild(leaf);
    }
});

// ============================================
// CORREÇÃO: User Dropdown Menu
// ============================================
function initializeUserDropdown() {
    const userMenuButton = document.getElementById('userMenuButton');
    const userDropdown = document.getElementById('userDropdown');
    
    if (!userMenuButton || !userDropdown) {
        return; // Elementos não existem nesta página
    }

    userMenuButton.addEventListener('click', function(e) {
    e.stopPropagation();
    userDropdown.classList.toggle('hidden');
    userMenuButton.classList.toggle('active');
});

    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(e) {
        if (!userDropdown.contains(e.target) && !userMenuButton.contains(e.target)) {
            userDropdown.classList.add('hidden');
        }
    });

    // Prevenir fechamento ao clicar dentro do dropdown
    userDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });
}

// ============================================
// CORREÇÃO: Mobile Menu
// ============================================
function initializeMobileMenu() {
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (!menuToggle || !mobileMenu) {
        return; // Elementos não existem, nada a fazer
    }

    const menuOpen = document.getElementById('menu-open');
    const menuClose = document.getElementById('menu-close');

    menuToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        const isHidden = mobileMenu.classList.contains('hidden');

        if (isHidden) {
            mobileMenu.classList.remove('hidden');
            if (menuOpen) menuOpen.classList.add('hidden');
            if (menuClose) menuClose.classList.remove('hidden');
        } else {
            mobileMenu.classList.add('hidden');
            if (menuOpen) menuOpen.classList.remove('hidden');
            if (menuClose) menuClose.classList.add('hidden');
        }
    });

    // Fechar menu ao clicar em um link
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            if (menuOpen) menuOpen.classList.remove('hidden');
            if (menuClose) menuClose.classList.add('hidden');
        });
    });

    // Fechar menu ao clicar fora
    document.addEventListener('click', function(e) {
        if (!mobileMenu.contains(e.target) && !menuToggle.contains(e.target)) {
            mobileMenu.classList.add('hidden');
            if (menuOpen) menuOpen.classList.remove('hidden');
            if (menuClose) menuClose.classList.add('hidden');
        }
    });
}

// FAQ Accordion Functionality
function initializeFAQs() {
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const icon = this.querySelector('.faq-icon');
            
            // Fechar outras respostas
            faqQuestions.forEach(otherQuestion => {
                if (otherQuestion !== this) {
                    const otherAnswer = otherQuestion.nextElementSibling;
                    const otherIcon = otherQuestion.querySelector('.faq-icon');
                    otherAnswer?.classList.remove('open');
                    if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
                }
            });
            
            // Alternar resposta atual
            answer?.classList.toggle('open');
            if (icon) {
                icon.style.transform = answer?.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        });
    });

    // FAQ Category Filter
    const categoryButtons = document.querySelectorAll('.category-btn');
    const faqCategories = document.querySelectorAll('.faq-category');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            categoryButtons.forEach(btn => {
                if (btn.getAttribute('data-category') === category) {
                    btn.classList.add('bg-green-600', 'text-white');
                    btn.classList.remove('bg-green-100', 'dark:bg-gray-700', 'text-green-800', 'dark:text-green-300');
                } else {
                    btn.classList.remove('bg-green-600', 'text-white');
                    btn.classList.add('bg-green-100', 'dark:bg-gray-700', 'text-green-800', 'dark:text-green-300');
                }
            });
            
            faqCategories.forEach(cat => {
                if (category === 'all' || cat.getAttribute('data-category') === category) {
                    cat.style.display = 'block';
                } else {
                    cat.style.display = 'none';
                }
            });
        });
    });

    // FAQ Search
    const searchInput = document.getElementById('searchFAQs');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question span')?.textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer')?.textContent.toLowerCase();
                
                if ((question && question.includes(searchTerm)) || (answer && answer.includes(searchTerm))) {
                    item.style.display = 'block';
                    const category = item.closest('.faq-category');
                    if (category) category.style.display = 'block';
                    
                    if (answer && answer.includes(searchTerm) && !item.querySelector('.faq-answer')?.classList.contains('open')) {
                        item.querySelector('.faq-question')?.click();
                    }
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
}

function updatePreview() {
    const nome = document.getElementById('nome')?.value || '';
    const descricao = document.getElementById('descricao')?.value || '';
    const cuidados = document.getElementById('cuidados')?.value || '';
    const urlfoto = document.getElementById('urlfoto')?.value || '';
    const previewImage = document.getElementById('previewImage');
    const noPreview = document.getElementById('noPreview');
    
    const previewNome = document.getElementById('previewNome');
    const previewDescricao = document.getElementById('previewDescricao');
    const previewCuidados = document.getElementById('previewCuidados');
    
    if (previewNome) previewNome.textContent = nome || 'Nome da Planta';
    if (previewDescricao) previewDescricao.textContent = descricao || 'Descrição aparecerá aqui...';
    if (previewCuidados) previewCuidados.textContent = cuidados || 'Instruções de cultivo aparecerão aqui...';
    
    if (previewImage && noPreview) {
        if (urlfoto) {
            previewImage.src = urlfoto;
            previewImage.classList.remove('hidden');
            noPreview.classList.add('hidden');
        } else {
            previewImage.classList.add('hidden');
            noPreview.classList.remove('hidden');
        }
    }
}

function clearPreview() {
    const previewNome = document.getElementById('previewNome');
    const previewDescricao = document.getElementById('previewDescricao');
    const previewCuidados = document.getElementById('previewCuidados');
    const previewImage = document.getElementById('previewImage');
    const noPreview = document.getElementById('noPreview');
    
    if (previewNome) previewNome.textContent = 'Nome da Planta';
    if (previewDescricao) previewDescricao.textContent = 'Descrição aparecerá aqui...';
    if (previewCuidados) previewCuidados.textContent = 'Instruções de cultivo aparecerão aqui...';
    if (previewImage) previewImage.classList.add('hidden');
    if (noPreview) noPreview.classList.remove('hidden');
}

function initializeGardenFilters() {
    const filterButtons = document.querySelectorAll('.flex-wrap button');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => {
                btn.classList.remove('bg-green-600', 'text-white');
                btn.classList.add('bg-green-100', 'dark:bg-gray-700', 'text-green-800', 'dark:text-green-300');
            });
            
            this.classList.remove('bg-green-100', 'dark:bg-gray-700', 'text-green-800', 'dark:text-green-300');
            this.classList.add('bg-green-600', 'text-white');
        });
    });
}

function initializeAutoRemoveMessages() {
    const errorMessages = document.querySelector('.bg-red-100');
    if (errorMessages) {
        setTimeout(() => {
            errorMessages.style.opacity = '0';
            errorMessages.style.transition = 'opacity 0.5s ease';
            setTimeout(() => errorMessages.remove(), 500);
        }, 5000);
    }
}

function initializeAnimationsAndMessages() {
    initializeScrollAnimations();
    initializeAutoRemoveMessages();
    
    if (!supportsIntersectionObserver()) {
        llAnimationsFallback();
    }
}

// ============================================
// CORREÇÃO: Sistema de Animação Scroll Fade-In
// ============================================

function initializeScrollAnimations() {
    const fadeElements = document.querySelectorAll('.fade-in');
    
    if (!fadeElements.length) return;
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                // Opcional: deixar de observar após a animação
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1, // Dispara quando 10% do elemento está visível
        rootMargin: '0px 0px -50px 0px' // Dispara um pouco antes do elemento entrar na tela
    });
    
    fadeElements.forEach(element => {
        observer.observe(element);
    });
}

// Alternativa mais simples (caso IntersectionObserver não seja suportado)
function initializeScrollAnimationsFallback() {
    const fadeElements = document.querySelectorAll('.fade-in');
    
    function checkScroll() {
        fadeElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementTop < windowHeight - 100) {
                element.classList.add('visible');
            }
        });
    }
    
    // Verificar na carga inicial
    checkScroll();
    
    // Verificar no scroll
    window.addEventListener('scroll', checkScroll);
}

// Função para detectar suporte a IntersectionObserver
function supportsIntersectionObserver() {
    return 'IntersectionObserver' in window &&
           'IntersectionObserverEntry' in window &&
           'intersectionRatio' in window.IntersectionObserverEntry.prototype;
}

// Utility Functions
function changeOrder(ordem) {
    const url = new URL(window.location.href);
    url.searchParams.set('ordem', ordem);
    window.location.href = url.toString();
}

function clearFilters() {
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    document.querySelectorAll('input[type="text"]').forEach(input => {
        input.value = '';
    });
    
    window.location.href = 'cards-plants.php';
}

function confirmDelete() {
    return confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.');
}

// Main initialization
document.addEventListener('DOMContentLoaded', function() {
    initializeUserDropdown();
    initializeMobileMenu();
    initializeFAQs();
    initializeGardenFilters();
    initializeAnimationsAndMessages();
});

// Global functions
window.changeOrder = changeOrder;
window.clearFilters = clearFilters;
window.confirmDelete = confirmDelete;
window.updatePreview = updatePreview;
window.clearPreview = clearPreview;