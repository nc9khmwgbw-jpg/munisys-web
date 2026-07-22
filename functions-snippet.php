<?php
/**
 * Snippet à coller dans WPCode (Hook : wp_footer)
 * 
 * 1. Crée le shortcode [munisys_hero] pour Breakdance
 * 2. Injecte le JavaScript d'animation en pied de page
 */

// ==========================================
// 1. CRÉATION DU SHORTCODE [munisys_hero]
// ==========================================
add_shortcode('munisys_hero', 'render_munisys_hero_shortcode');
function render_munisys_hero_shortcode() {
    // On utilise la temporisation de sortie pour capturer le HTML proprement
    ob_start();
    ?>
    <style>
    /* ----- BREAKDANCE BUILDER FIX ----- */
    /* Le CSS est injecté directement avec le shortcode pour contourner les blocages du constructeur */
    :root {
        --bordeaux: #922b55;
        --blue-dark: #1d4e79;
        --text-dark: #333333;
        --white: #ffffff;
        --ease-out-expo: cubic-bezier(0.19, 1, 0.22, 1);
        --ease-in-out-expo: cubic-bezier(0.87, 0, 0.13, 1);
    }

    .hero-container {
        position: relative;
        width: 100%;
        height: 100vh;
        font-family: 'Montserrat', sans-serif;
        background: #000;
        overflow: hidden;
    }

    /* ----- BACKGROUND IMAGES ----- */
    .images-wrapper {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100vh;
        z-index: 1;
        background: #111;
    }

    .bg-image {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100vh;
        object-fit: cover;
        clip-path: inset(0 0 0 100%);
        transform: scale(1.1);
        transition: clip-path 1.4s var(--ease-in-out-expo), transform 2s var(--ease-out-expo);
        z-index: 1;
    }

    .bg-image.active {
        clip-path: inset(0 0 0 0%) !important;
        transform: scale(1);
        z-index: 2;
    }

    .bg-image.prev {
        clip-path: inset(0 0 0 0%);
        transform: scale(1);
        z-index: 1;
    }

    .images-wrapper::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.1) 50%, rgba(0, 0, 0, 0.4) 100%);
        z-index: 5;
        pointer-events: none;
    }

    /* ----- FRONT PANEL (TEXT) - ANIMATED ----- */
    .text-panel {
        position: absolute !important;
        top: 2% !important;
        left: 2% !important;
        z-index: 10 !important;
        width: 42% !important;
        height: 96% !important;
        background: rgba(248, 250, 252, 0.95) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        padding: 0 6% !important;
        box-sizing: border-box !important;

        border-radius: 40px !important;
        box-shadow: 20px 20px 60px rgba(0, 0, 0, 0.15) !important;

        transition: left 1.4s var(--ease-in-out-expo), box-shadow 1.4s var(--ease-in-out-expo) !important;
    }

    .text-panel.is-right {
        left: 56% !important;
        box-shadow: -20px 20px 60px rgba(0, 0, 0, 0.15) !important;
    }

    /* Wrapper pour les slides de texte */
    .text-slides-wrapper {
        position: relative !important;
        width: 100% !important;
        height: 400px !important;
    }

    .text-slide {
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        transform: translateY(-50%);
        visibility: hidden;
        pointer-events: none;
    }

    .text-slide.active {
        visibility: visible;
        pointer-events: auto;
        z-index: 2;
    }

    .text-slide > * {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s var(--ease-out-expo), transform 0.8s var(--ease-out-expo);
    }

    .text-slide.active > * {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }

    .text-slide.active .meta { transition-delay: 0.6s !important; }
    .text-slide.active .title { transition-delay: 0.7s !important; }
    .text-slide.active .desc { transition-delay: 0.8s !important; }
    .text-slide.active .btn-wrap { transition-delay: 0.9s !important; }

    /* Typography */
    .meta {
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.3em;
        text-transform: uppercase;
        color: var(--bordeaux);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .meta::before {
        content: '';
        display: block;
        width: 40px;
        height: 2px;
        background: var(--bordeaux);
    }

    .title {
        font-size: clamp(2.5rem, 3.5vw, 4rem);
        font-weight: 200;
        line-height: 1.1;
        color: var(--text-dark);
        margin: 0 0 1.5rem 0;
        letter-spacing: -0.02em;
    }

    .title strong {
        font-weight: 700;
    }

    .desc {
        font-size: 1rem;
        line-height: 1.8;
        color: #475569;
        font-weight: 400;
        margin: 0 0 3rem 0;
        max-width: 95%;
    }

    /* Button */
    .btn-elegant {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 1.2rem !important;
        background: var(--blue-dark) !important;
        color: var(--white) !important;
        padding: 1.1rem 2.5rem !important;
        border-radius: 50px !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
        text-decoration: none !important;
        transition: all 0.4s var(--ease-out-expo) !important;
        position: relative !important;
        overflow: hidden !important;
        border: none !important;
        width: max-content !important;
        min-height: 50px !important;
    }

    .btn-elegant::before {
        content: '' !important;
        position: absolute !important;
        inset: 0 !important;
        background: var(--bordeaux) !important;
        transform: scaleX(0) !important;
        transform-origin: right !important;
        transition: transform 0.6s var(--ease-in-out-expo) !important;
        z-index: 1 !important;
    }

    .btn-elegant:hover::before {
        transform: scaleX(1) !important;
        transform-origin: left !important;
    }

    .btn-elegant span {
        position: relative !important;
        z-index: 2 !important;
        color: var(--white) !important;
        margin: 0 !important;
    }
    
    .btn-elegant svg {
        position: relative !important;
        z-index: 2 !important;
        color: var(--white) !important;
        width: 20px !important;
        height: 20px !important;
        flex-shrink: 0 !important;
        margin: 0 !important;
    }

    .btn-elegant:hover svg {
        transform: translateX(5px) !important;
    }

    /* ----- CONTROLS ----- */
    .controls-wrapper {
        position: absolute;
        bottom: 4vw;
        left: 7vw;
        right: 7vw;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .slide-numbers {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 1.5rem;
        font-weight: 300;
        color: var(--blue-dark);
        font-family: monospace;
    }

    .slide-numbers .current {
        font-weight: 700;
        color: var(--bordeaux);
    }

    .slide-numbers .separator {
        font-weight: 200;
        color: #94a3b8;
    }

    .slide-numbers .total {
        font-size: 1rem;
        color: #94a3b8;
        font-weight: 500;
    }

    .nav-buttons {
        display: flex !important;
        gap: 1rem !important;
    }

    .nav-btn {
        width: 55px !important;
        height: 55px !important;
        border-radius: 50% !important;
        background: rgba(29, 78, 121, 0.05) !important;
        border: 1px solid rgba(29, 78, 121, 0.15) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        color: var(--blue-dark) !important;
        transition: all 0.4s var(--ease-out-expo) !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .nav-btn svg {
        width: 24px !important;
        height: 24px !important;
        flex-shrink: 0 !important;
        color: var(--blue-dark) !important;
        margin: 0 !important;
    }

    .nav-btn:hover {
        background: var(--bordeaux) !important;
        color: var(--white) !important;
        border-color: var(--bordeaux) !important;
        transform: scale(1.1) !important;
    }
    
    .nav-btn:hover svg {
        color: var(--white) !important;
    }

    .global-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border-radius: 0 0 40px 40px;
    }

    .global-progress .fill {
        height: 100%;
        background: var(--bordeaux);
        width: 0%;
    }

    @media(max-width: 1024px) {
        .text-panel {
            width: 96%;
            border-radius: 30px !important;
            height: 55%;
            padding: 0 8%;
            top: auto;
            bottom: 2%;
        }

        .text-panel.is-right {
            transform: translateX(0);
        }

        .hero-container {
            flex-direction: column;
        }

        .title {
            font-size: 2.2rem;
            margin-bottom: 1rem;
        }

        .images-wrapper {
            height: 45vh;
        }

        .controls-wrapper {
            bottom: 2vh;
            left: 8vw;
            right: 8vw;
        }
    }
    </style>
    <div class="hero-container">
        <!-- BACKGROUND IMAGES (Visuels Ultra Premium) -->
        <div class="images-wrapper">
            <!-- Core Infra : Serveurs modernes -->
            <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?q=80&w=2560&auto=format&fit=crop"
                class="bg-image active" alt="Datacenter">
            <!-- Cyber : Cadenas de sécurité sur clavier (Premium et fiable) -->
            <img src="https://images.unsplash.com/photo-1563986768609-322da13575f3?q=80&w=2560&auto=format&fit=crop"
                class="bg-image" alt="Cybersecurity">
            <!-- Cloud : Réseau global lumineux -->
            <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2560&auto=format&fit=crop"
                class="bg-image" alt="Cloud">
        </div>

        <!-- FRONT PANEL -->
        <div class="text-panel" id="main-panel">
            <div class="text-slides-wrapper">
                <!-- Text 1 -->
                <div class="text-slide active">
                    <div class="meta">Core Infrastructure</div>
                    <h1 class="title">We orchestrate <strong>digital infrastructure</strong> for a unique
                        <strong>experience.</strong>
                    </h1>
                    <p class="desc">Setup, manage, and secure your compute, storage, and networking across data centers
                        and public clouds with our leading data-centric solutions.</p>
                    <div class="btn-wrap">
                        <a href="#" class="btn-elegant">
                            <span>Our solutions</span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Text 2 -->
                <div class="text-slide">
                    <div class="meta">Cybersecurity</div>
                    <h1 class="title">Protecting your <strong>digital assets</strong> with cutting-edge
                        <strong>security.</strong>
                    </h1>
                    <p class="desc">Secure your network, endpoints, and data against evolving digital threats with
                        zero-trust architectures and proactive monitoring.</p>
                    <div class="btn-wrap">
                        <a href="#" class="btn-elegant">
                            <span>Discover security</span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Text 3 -->
                <div class="text-slide">
                    <div class="meta">Managed Services</div>
                    <h1 class="title">Accelerating your <strong>cloud journey</strong> with seamless
                        <strong>management.</strong>
                    </h1>
                    <p class="desc">Seamless cloud integration and managed services to accelerate your digital
                        transformation journey while ensuring high availability.</p>
                    <div class="btn-wrap">
                        <a href="#" class="btn-elegant">
                            <span>Cloud services</span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- CONTROLS -->
            <div class="controls-wrapper">
                <div class="slide-numbers">
                    <span class="current" id="num-current">01</span>
                    <span class="separator">/</span>
                    <span class="total">03</span>
                </div>
                <div class="nav-buttons">
                    <button class="nav-btn" id="btn-prev">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M15 18l-6-6 6-6" />
                        </svg>
                    </button>
                    <button class="nav-btn" id="btn-next">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M9 18l6-6-6-6" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="global-progress">
                <div class="fill" id="progress-fill"></div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}


// ==========================================
// 2. INJECTION DU JAVASCRIPT (wp_footer)
// ==========================================
add_action('wp_footer', 'munisys_inject_new_hero_script', 99);
function munisys_inject_new_hero_script() {
    if ( is_front_page() || is_home() ) {
        ?>
        <script id="munisys-hero-logic">
        (function initMunisysHero() {
            if (window.munisysHeroTimer) {
                clearInterval(window.munisysHeroTimer);
            }

            const images = document.querySelectorAll('.bg-image');
            const texts = document.querySelectorAll('.text-slide');
            const numCurrent = document.getElementById('num-current');
            const progressFill = document.getElementById('progress-fill');
            const mainPanel = document.getElementById('main-panel');

            if (!mainPanel || images.length === 0) return;

            let currentIndex = 0;
            let isAnimating = false;
            const TIME_MS = 6000;

            function changeSlide(newIndex, direction = 'next') {
                if (isAnimating || newIndex === currentIndex) return;
                isAnimating = true;

                const oldIndex = currentIndex;
                currentIndex = newIndex;

                if (currentIndex % 2 !== 0) {
                    mainPanel.classList.add('is-right');
                } else {
                    mainPanel.classList.remove('is-right');
                }

                if (texts[oldIndex]) texts[oldIndex].classList.remove('active');
                if (texts[currentIndex]) texts[currentIndex].classList.add('active');

                if (numCurrent) numCurrent.textContent = '0' + (currentIndex + 1);

                images.forEach(img => {
                    img.classList.remove('active', 'prev');
                    img.style.zIndex = '';
                    img.style.clipPath = '';
                });

                if (images[oldIndex]) images[oldIndex].classList.add('prev');
                const newImg = images[currentIndex];

                if (newImg) {
                    newImg.style.transition = 'none';

                    if (currentIndex % 2 !== 0) {
                        newImg.style.clipPath = 'inset(0 100% 0 0)';
                    } else {
                        newImg.style.clipPath = 'inset(0 0 0 100%)';
                    }

                    void newImg.offsetWidth; // Force reflow

                    newImg.style.transition = '';
                    newImg.style.clipPath = 'inset(0 0 0 0%)';
                    newImg.classList.add('active');
                }

                if (progressFill) {
                    progressFill.style.transition = 'none';
                    progressFill.style.width = '0%';

                    setTimeout(() => {
                        progressFill.style.transition = `width ${TIME_MS}ms linear`;
                        progressFill.style.width = '100%';
                        isAnimating = false;
                    }, 1400); 
                } else {
                    setTimeout(() => { isAnimating = false; }, 1400);
                }
            }

            function autoPlay() {
                changeSlide((currentIndex + 1) % images.length, 'next');
            }

            function resetTimer() {
                clearInterval(window.munisysHeroTimer);
                window.munisysHeroTimer = setInterval(autoPlay, TIME_MS);
            }

            if (!window.munisysHeroEventsBound) {
                window.munisysHeroEventsBound = true;
                
                document.addEventListener('click', function(e) {
                    const btnPrev = e.target.closest('#btn-prev');
                    const btnNext = e.target.closest('#btn-next');

                    if (btnPrev) {
                        e.preventDefault();
                        resetTimer();
                        changeSlide((currentIndex - 1 + images.length) % images.length, 'prev');
                    }
                    if (btnNext) {
                        e.preventDefault();
                        resetTimer();
                        changeSlide((currentIndex + 1) % images.length, 'next');
                    }
                });
            }

            if (progressFill) {
                setTimeout(() => {
                    progressFill.style.transition = `width ${TIME_MS}ms linear`;
                    progressFill.style.width = '100%';
                }, 50);
            }
            resetTimer();

        })();

        // ==========================================
        // 3. JAVASCRIPT DU RESTE DE LA PAGE
        // (Onglets, Accordéons, Smooth Scroll)
        // ==========================================
        (function initMunisysPageInteractions() {
            const tabsData = [
                {
                    title: "Core Infrastructure Technologies",
                    desc: "<p>Munisys leverages its unique and extensive experience to help IT organizations to setup, manage, secure and seamlessly run consistent compute, storage, networking and backup infrastructure across data centers and public clouds, through either traditional or software-defined approaches.</p><p>We focus on a range of leading data-centric, adaptable and efficient solutions that offer cloud-native operating experience, AI driven management, enhanced security and optimized performance.</p>"
                },
                {
                    title: "Cybersecurity Solutions",
                    desc: "<p>Comprehensive protection for your enterprise. We secure your network, endpoints, and data against evolving digital threats with zero-trust architectures and proactive monitoring.</p>"
                },
                {
                    title: "Infrastructure Software",
                    desc: "<p>Optimize your operations with enterprise-grade software solutions for monitoring, management, and automation across your entire IT stack.</p>"
                },
                {
                    title: "Managed & Cloud Services",
                    desc: "<p>Seamless cloud integration and managed services to accelerate your digital transformation journey while controlling costs and ensuring high availability.</p>"
                },
                {
                    title: "Endpoint solutions",
                    desc: "<p>Empower your workforce with secure, high-performance computing devices and intelligent endpoint management solutions designed for the modern hybrid workplace.</p>"
                }
            ];

            if (!window.munisysPageEventsBound) {
                window.munisysPageEventsBound = true;

                document.addEventListener('click', function(e) {
                    
                    // A. ONGLETS "SOLUTIONS"
                    const tabItem = e.target.closest('.sol-menu-item');
                    if (tabItem) {
                        const grid = tabItem.closest('.sol-grid');
                        if (grid) {
                            const allItems = Array.from(grid.querySelectorAll('.sol-menu-item'));
                            const index = allItems.indexOf(tabItem);
                            if (index > -1) {
                                allItems.forEach(i => i.classList.remove('active'));
                                tabItem.classList.add('active');
                                const content = grid.querySelector('.sol-content');
                                if (content) {
                                    content.style.opacity = '0';
                                    setTimeout(() => {
                                        const data = tabsData[index];
                                        content.innerHTML = `<h3>${data.title}</h3>${data.desc}<a href="#" class="sol-link temp-link">Read more <span style="margin-left:5px">→</span></a>`;
                                        content.style.opacity = '1';
                                    }, 250);
                                }
                            }
                        }
                    }

                    // B. ACCORDÉON "SERVICES"
                    const accHead = e.target.closest('.srv-acc-head');
                    if (accHead) {
                        const acc = accHead.closest('.srv-acc');
                        const wrapper = acc.closest('.srv-right') || document.querySelector('.services-section');
                        if (wrapper && acc) {
                            const isAlreadyOpen = acc.classList.contains('open');
                            wrapper.querySelectorAll('.srv-acc').forEach(c => {
                                c.classList.remove('open');
                                const b = c.querySelector('.srv-acc-body');
                                const i = c.querySelector('.acc-x');
                                if(b) b.style.display = 'none';
                                if(i) i.textContent = '+';
                            });
                            if (!isAlreadyOpen) {
                                acc.classList.add('open');
                                const b = acc.querySelector('.srv-acc-body');
                                const i = acc.querySelector('.acc-x');
                                if(b) b.style.display = 'block';
                                if(i) i.textContent = '−';
                            }
                        }
                    }

                    // C. SMOOTH SCROLL (Boutons Our Solutions)
                    const heroBtn = e.target.closest('.btn-elegant');
                    if (heroBtn && heroBtn.textContent.includes('Our solutions')) {
                        const solSection = document.querySelector('.sol-wrapper') || document.querySelector('#solutions');
                        if (solSection) {
                            e.preventDefault();
                            solSection.scrollIntoView({ behavior: 'smooth' });
                        }
                    }

                    // D. LIENS TEMPORAIRES (Pop-up)
                    const tempLink = e.target.closest('.temp-link, .sol-link, .srv-readmore, .ref-seeall, .ins-card');
                    if (tempLink && tempLink.getAttribute('href') === '#') {
                        e.preventDefault();
                        alert("Action réussie ! 🚀\nCe bouton fonctionne bien, la page de destination sera créée ultérieurement sur WordPress.");
                    }

                });
            }
        })();
        </script>
        <?php
    }
}
