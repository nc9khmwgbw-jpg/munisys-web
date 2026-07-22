<?php
/**
 * Snippet à coller dans WPCode (Hook : wp_footer)
 * 
 * 1. Crée le shortcode [munisys_hero] pour Breakdance qui affiche TOUTE LA PAGE
 * 2. Injecte le JavaScript d'animation en pied de page
 */

// ==========================================
// 1. CRÉATION DU SHORTCODE [munisys_hero]
// ==========================================
add_shortcode('munisys_hero', 'render_munisys_hero_shortcode');
function render_munisys_hero_shortcode()
{
    // La carte pointe désormais de façon dynamique
    $link_press_release = '/press-release/';

    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800;900&display=swap');

        #munisys-wrapper {
            --bordeaux:
                <?php echo get_option('munisys_color_primary', '#922b55'); ?>
            ;
            --blue-dark:
                <?php echo get_option('munisys_color_dark', '#1d4e79'); ?>
            ;
            --blue-light:
                <?php echo get_option('munisys_color_light', '#83a5c2'); ?>
            ;
            --text-dark: #333333;
            --text-gray: #666666;
            --bg-gray: #fcfcfc;
            --white: #ffffff;
            --ease-out-expo: cubic-bezier(0.19, 1, 0.22, 1);
            --ease-in-out-expo: cubic-bezier(0.87, 0, 0.13, 1);
            font-family: 'Montserrat', sans-serif !important;
            background: #ffffff !important;
            color: var(--text-dark) !important;
            -webkit-font-smoothing: antialiased !important;
            width: 100% !important;
            text-align: left;
        }

        #munisys-wrapper *,
        #munisys-wrapper *::before,
        #munisys-wrapper *::after {
            box-sizing: border-box !important;
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        #munisys-wrapper section {
            display: block !important;
            width: 100% !important;
            text-align: left !important;
            clear: both !important;
        }

        #munisys-wrapper a {
            text-decoration: none !important;
            color: inherit !important;
        }

        #munisys-wrapper ul {
            list-style: none !important;
        }

        #munisys-wrapper button {
            font-family: inherit !important;
            cursor: pointer !important;
            border: none !important;
            background: none !important;
        }

        #munisys-wrapper img {
            display: block !important;
            max-width: 100% !important;
        }

        /* ----- BACKGROUND IMAGES ----- */
        #munisys-wrapper .hero-container {
            position: relative;
            width: 100%;
            height: 100vh;
            font-family: 'Montserrat', sans-serif;
            background: #000;
            overflow: hidden;
        }

        #munisys-wrapper .images-wrapper {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100vh;
            z-index: 1;
            background: #111;
        }

        #munisys-wrapper .bg-image {
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

        #munisys-wrapper .bg-image.active {
            clip-path: inset(0 0 0 0%) !important;
            transform: scale(1) !important;
            z-index: 2;
        }

        #munisys-wrapper .bg-image.prev {
            clip-path: inset(0 0 0 0%);
            transform: scale(1);
            z-index: 1;
        }

        #munisys-wrapper .images-wrapper::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(29, 78, 121, 0.45);
            /* Filtre Bleu foncé Munisys unificateur */
            z-index: 5;
            pointer-events: none;
        }

        #munisys-wrapper .images-wrapper::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0.4) 100%);
            z-index: 6;
            pointer-events: none;
        }

        /* ----- FRONT PANEL (TEXT) - ANIMATED ----- */
        #munisys-wrapper .text-panel {
            position: absolute !important;
            top: 56% !important;
            transform: translateY(-50%) !important;
            left: 4% !important;
            z-index: 10 !important;
            width: 44% !important;
            max-width: 650px !important;
            height: auto !important;
            background: rgba(248, 250, 252, 0.95) !important;
            backdrop-filter: blur(25px) !important;
            -webkit-backdrop-filter: blur(25px) !important;
            border-radius: 30px !important;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12) !important;
            border: 1px solid rgba(255, 255, 255, 0.6) !important;
            padding: 3rem 4rem 3rem 4rem !important;
            display: flex !important;
            flex-direction: column !important;
            transition: left 1.4s var(--ease-in-out-expo) !important;
        }

        #munisys-wrapper .text-panel.is-right {
            left: calc(96% - 44%) !important;
        }

        /* Wrapper pour les slides de texte */
        #munisys-wrapper .text-slides-wrapper {
            position: relative !important;
            width: 100% !important;
            min-height: 420px !important;
        }

        #munisys-wrapper .text-slide {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            transform: translateY(-50%);
            visibility: hidden;
            pointer-events: none;
        }

        #munisys-wrapper .text-slide.active {
            visibility: visible;
            pointer-events: auto;
            z-index: 2;
        }

        #munisys-wrapper .text-slide>* {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s var(--ease-out-expo), transform 0.8s var(--ease-out-expo);
        }

        #munisys-wrapper .text-slide.active>* {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        #munisys-wrapper .text-slide.active .meta {
            transition-delay: 0.6s !important;
        }

        #munisys-wrapper .text-slide.active .title {
            transition-delay: 0.7s !important;
        }

        #munisys-wrapper .text-slide.active .desc {
            transition-delay: 0.8s !important;
        }

        #munisys-wrapper .text-slide.active .btn-wrap {
            transition-delay: 0.9s !important;
        }

        /* Typography */
        #munisys-wrapper .meta {
            font-size: 0.75rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.3em !important;
            text-transform: uppercase !important;
            color: var(--bordeaux) !important;
            margin-bottom: 2rem !important;
            display: flex !important;
            align-items: center !important;
            gap: 1.5rem !important;
        }

        #munisys-wrapper .meta::before {
            content: '' !important;
            display: block !important;
            width: 40px !important;
            height: 2px !important;
            background: var(--bordeaux) !important;
        }

        #munisys-wrapper .title {
            font-size: clamp(1.8rem, 2.5vw, 3rem) !important;
            font-weight: 200 !important;
            line-height: 1.15 !important;
            color: var(--text-dark) !important;
            margin: 0 0 1.5rem 0 !important;
            letter-spacing: -0.02em !important;
        }

        #munisys-wrapper .title strong {
            font-weight: 700 !important;
        }

        #munisys-wrapper .desc {
            font-size: 1.05rem !important;
            line-height: 1.7 !important;
            color: #475569 !important;
            font-weight: 400 !important;
            margin: 0 0 3rem 0 !important;
            max-width: 100% !important;
        }

        /* Button */
        #munisys-wrapper .btn-wrap {
            margin-top: 1rem !important;
        }

        #munisys-wrapper .btn-elegant {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 1.2rem !important;
            background: var(--blue-dark) !important;
            color: var(--white) !important;
            padding: 1.2rem 2.8rem !important;
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
            min-height: 55px !important;
        }

        #munisys-wrapper .btn-elegant::before {
            content: '' !important;
            position: absolute !important;
            inset: 0 !important;
            background: var(--bordeaux) !important;
            transform: scaleX(0) !important;
            transform-origin: right !important;
            transition: transform 0.6s var(--ease-in-out-expo) !important;
            z-index: 1 !important;
        }

        #munisys-wrapper .btn-elegant:hover::before {
            transform: scaleX(1) !important;
            transform-origin: left !important;
        }

        #munisys-wrapper .btn-elegant span {
            position: relative !important;
            z-index: 2 !important;
            color: var(--white) !important;
            margin: 0 !important;
            transition: color 0.4s !important;
        }

        #munisys-wrapper .btn-elegant svg {
            position: relative !important;
            z-index: 2 !important;
            color: var(--white) !important;
            width: 20px !important;
            height: 20px !important;
            flex-shrink: 0 !important;
            margin: 0 !important;
            transition: transform 0.4s !important;
        }

        #munisys-wrapper .btn-elegant:hover span {
            color: var(--white) !important;
        }

        #munisys-wrapper .btn-elegant:hover svg {
            color: var(--white) !important;
            transform: translateX(5px) !important;
        }

        /* ----- CONTROLS ----- */
        #munisys-wrapper .controls-wrapper {
            position: relative !important;
            width: 100% !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-top: 2rem !important;
            padding-top: 2rem !important;
            border-top: 1px solid rgba(0, 0, 0, 0.06) !important;
        }

        #munisys-wrapper .slide-numbers {
            display: flex !important;
            align-items: center !important;
            gap: 1rem !important;
            font-size: 1.5rem !important;
            font-weight: 300 !important;

            font-family: monospace !important;
        }

        #munisys-wrapper .slide-numbers .current {
            font-weight: 700 !important;
            color: var(--bordeaux) !important;
        }

        #munisys-wrapper .slide-numbers .separator {
            font-weight: 200 !important;
            color: #94a3b8 !important;
        }

        #munisys-wrapper .slide-numbers .total {
            font-size: 1rem !important;
            color: #94a3b8 !important;
            font-weight: 500 !important;
        }

        #munisys-wrapper .nav-buttons {
            display: flex !important;
            gap: 1rem !important;
        }

        #munisys-wrapper .nav-btn {
            width: 55px !important;
            height: 55px !important;
            border-radius: 50% !important;
            background: rgba(29, 78, 121, 0.05) !important;
            border: 1px solid rgba(29, 78, 121, 0.15) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;

            transition: all 0.4s var(--ease-out-expo) !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        #munisys-wrapper .nav-btn svg {
            width: 24px !important;
            height: 24px !important;
            flex-shrink: 0 !important;

            margin: 0 !important;
        }

        #munisys-wrapper .nav-btn:hover {
            background: var(--bordeaux) !important;
            color: var(--white) !important;
            border-color: var(--bordeaux) !important;
            transform: scale(1.1) !important;
        }

        #munisys-wrapper .nav-btn:hover svg {
            color: var(--white) !important;
        }

        #munisys-wrapper .global-progress {
            position: absolute !important;
            bottom: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 6px !important;
            background: rgba(0, 0, 0, 0.05) !important;
            overflow: hidden !important;
            border-radius: 0 0 30px 30px !important;
        }

        #munisys-wrapper .global-progress .fill {
            height: 100% !important;
            background: var(--bordeaux) !important;
            width: 0% !important;
        }

        /* ── 1. SOLUTIONS SECTION (Light Background) ── */
        #munisys-wrapper .sol-wrapper {
            background: var(--bg-gray) !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: inset 0 15px 30px rgba(0, 0, 0, 0.03) !important;
            scroll-margin-top: 120px !important;
            /* Séparation douce avec le Hero */
        }

        #munisys-wrapper .sol-sec {
            background: transparent !important;
            padding: 5rem 5% !important;
            position: relative !important;
            max-width: 1440px !important;
            margin: 0 auto !important;
            z-index: 1 !important;
        }

        #munisys-wrapper .sol-title {
            font-size: 3rem !important;
            font-weight: 800 !important;
            color: #1d4e79 !important;
            margin-bottom: 3rem !important;
            text-align: center !important;
        }

        /* Bento Grid Layout */
        #munisys-wrapper .bento-grid {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            grid-auto-rows: minmax(240px, auto) !important;
            gap: 1.5rem !important;
        }

        /* Bento Cards */
        #munisys-wrapper .bento-card {
            position: relative !important;
            border-radius: 16px !important;
            overflow: hidden !important;
            background: var(--blue-dark) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: flex-end !important;
            padding: 2.5rem !important;
            text-decoration: none !important;
            color: #fff !important;
            transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1) !important;
        }

        #munisys-wrapper .bento-card:hover {
            transform: translateY(-8px) !important;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            z-index: 10 !important;
        }

        /* Background Images in Cards */
        #munisys-wrapper .bento-bg {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            z-index: 1 !important;
            transition: transform 0.6s cubic-bezier(0.19, 1, 0.22, 1) !important;
        }

        #munisys-wrapper .bento-card:hover .bento-bg {
            transform: scale(1.05) !important;
        }

        /* Glassmorphism Overlay */
        #munisys-wrapper .bento-overlay {
            position: absolute !important;
            inset: 0 !important;
            background: linear-gradient(to top, rgba(29, 78, 121, 0.95) 0%, rgba(29, 78, 121, 0.3) 60%, transparent 100%) !important;
            z-index: 2 !important;
            transition: background 0.4s ease !important;
        }

        #munisys-wrapper .bento-card:hover .bento-overlay {
            background: linear-gradient(to top, rgba(146, 43, 85, 0.95) 0%, rgba(29, 78, 121, 0.5) 70%, transparent 100%) !important;
        }

        #munisys-wrapper .bento-card.glass .bento-overlay {
            background: rgba(29, 78, 121, 0.5) !important;
            backdrop-filter: blur(6px) !important;
            -webkit-backdrop-filter: blur(6px) !important;
        }

        /* Card Content */
        #munisys-wrapper .bento-content {
            position: relative !important;
            z-index: 3 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 1rem !important;
        }

        #munisys-wrapper .bento-content h3 {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            margin: 0 !important;
            line-height: 1.2 !important;
            color: #ffffff !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5) !important;
        }

        #munisys-wrapper .bento-content p {
            font-size: 0.95rem !important;
            line-height: 1.6 !important;
            color: rgba(255, 255, 255, 0.85) !important;
            margin: 0 !important;
            opacity: 0.8 !important;
            transition: opacity 0.3s ease !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 3 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
        }

        #munisys-wrapper .bento-card:hover .bento-content p {
            opacity: 1 !important;
        }

        #munisys-wrapper .bento-link {
            font-size: 0.85rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            color: #83a5c2 !important;
            margin-top: auto !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            transition: color 0.3s ease !important;
        }

        #munisys-wrapper .bento-card:hover .bento-link {
            color: #fff !important;
        }

        #munisys-wrapper .bento-card:hover .bento-link svg {
            transform: translateX(5px) !important;
            transition: transform 0.3s ease !important;
        }

        /* Grid Spans */
        #munisys-wrapper .span-2x3 {
            grid-column: span 2 !important;
            grid-row: span 3 !important;
        }

        #munisys-wrapper .span-2x3 h3 {
            font-size: 2.2rem !important;
        }

        #munisys-wrapper .span-2x1 {
            grid-column: span 2 !important;
            grid-row: span 1 !important;
        }

        /* ── 2. OFFERINGS SECTION ── */
        #munisys-wrapper .off-sec {
            padding: 6rem 5rem !important;
            max-width: 1440px !important;
            margin: 0 auto !important;
        }

        #munisys-wrapper .off-header {
            display: flex !important;
            gap: 2rem !important;
            align-items: flex-start !important;
            margin-bottom: 4rem !important;
            flex-direction: row !important;
        }

        #munisys-wrapper .off-title {
            font-size: 3.5rem !important;
            font-weight: 300 !important;
            color: var(--bordeaux) !important;
        }

        #munisys-wrapper .off-desc {
            font-size: 0.95rem !important;
            color: var(--text-gray) !important;
            max-width: 500px !important;
            padding-top: 1rem !important;
            line-height: 1.6 !important;
        }

        #munisys-wrapper .off-flow {
            display: grid !important;
            grid-template-columns: auto 1fr auto 1fr !important;
            align-items: center !important;
            gap: 3rem !important;
            border: 1px solid var(--bordeaux) !important;
            padding: 3rem 4rem !important;
            position: relative !important;
        }

        #munisys-wrapper .off-label-vert {
            writing-mode: vertical-lr !important;
            transform: rotate(180deg) !important;
            font-size: 3rem !important;
            font-weight: 800 !important;
            text-align: center !important;
            line-height: 1 !important;
        }

        #munisys-wrapper .off-label-through {
            color: var(--bordeaux) !important;
        }

        #munisys-wrapper .off-label-achieve {}

        #munisys-wrapper .off-col {
            display: flex !important;
            flex-direction: column !important;
            gap: 2rem !important;
        }

        #munisys-wrapper .off-item {
            display: flex !important;
            align-items: center !important;
            gap: 1rem !important;
            font-weight: 700 !important;
            font-size: 1.05rem !important;
            color: var(--text-dark) !important;
        }

        #munisys-wrapper .off-check {
            color: var(--blue-light) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* ── 3. SERVICES SECTION (Premium Hover List) ── */
        #munisys-wrapper .srv-wrapper {
            background: #121c2d !important;
            /* Soft dark corporate blue */
            width: 100% !important;
            color: #fff !important;
        }

        #munisys-wrapper .srv-sec {
            scroll-margin-top: 120px !important;
            padding: 8rem 5rem !important;
            display: grid !important;
            grid-template-columns: 1fr 2fr !important;
            gap: 4rem !important;
            max-width: 1440px !important;
            margin: 0 auto !important;
        }

        #munisys-wrapper .srv-left h2 {
            font-size: 4.5rem !important;
            color: #fff !important;
            font-weight: 200 !important;
            margin-bottom: 4rem !important;
            letter-spacing: -2px !important;
        }

        #munisys-wrapper .srv-right {
            display: flex !important;
            flex-direction: column !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        #munisys-wrapper .srv-item {
            display: grid !important;
            grid-template-columns: 1fr 2fr auto !important;
            align-items: center !important;
            gap: 4rem !important;
            padding: 3rem 0 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            cursor: pointer !important;
            transition: all 0.5s var(--ease-out-expo) !important;
            position: relative !important;
            overflow: hidden !important;
            text-decoration: none !important;
        }

        #munisys-wrapper .srv-item::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 0% !important;
            height: 100% !important;
            background: rgba(146, 43, 85, 0.15) !important;
            z-index: 0 !important;
            transition: width 0.6s var(--ease-out-expo) !important;
        }

        #munisys-wrapper .srv-item:hover::before {
            width: 100% !important;
        }

        #munisys-wrapper .srv-title-wrap {
            position: relative !important;
            z-index: 2 !important;
            display: flex !important;
            align-items: center !important;
            gap: 2rem !important;
            transition: transform 0.5s var(--ease-out-expo) !important;
        }

        #munisys-wrapper .srv-item:hover .srv-title-wrap {
            transform: translateX(30px) !important;
        }

        #munisys-wrapper .srv-num {
            font-size: 1.2rem !important;
            font-weight: 300 !important;
            color: var(--bordeaux) !important;
            opacity: 0.8 !important;
        }

        #munisys-wrapper .srv-title {
            font-size: 3rem !important;
            font-weight: 300 !important;
            color: #fff !important;
            margin: 0 !important;
        }

        #munisys-wrapper .srv-desc-wrap {
            position: relative !important;
            z-index: 2 !important;
            width: 100% !important;
            opacity: 0 !important;
            transform: translateY(20px) !important;
            transition: all 0.5s var(--ease-out-expo) !important;
        }

        #munisys-wrapper .srv-item:hover .srv-desc-wrap {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        #munisys-wrapper .srv-desc-wrap p {
            font-size: 1.1rem !important;
            color: rgba(255, 255, 255, 0.7) !important;
            line-height: 1.6 !important;
            margin-bottom: 1rem !important;
        }

        #munisys-wrapper .srv-arrow {
            width: 60px !important;
            height: 60px !important;
            border-radius: 50% !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            position: relative !important;
            z-index: 2 !important;
            transition: all 0.5s var(--ease-out-expo) !important;
            color: #fff !important;
        }

        #munisys-wrapper .srv-item:hover .srv-arrow {
            background: var(--bordeaux) !important;
            border-color: var(--bordeaux) !important;
            transform: rotate(-45deg) !important;
        }

        /* ── 3.5 SOME FIGURES SECTION ── */
        #munisys-wrapper .figures-sec {
            scroll-margin-top: 120px !important;
            background: var(--bordeaux) !important;
            color: #fff !important;
            padding: 5rem 0 !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            overflow: hidden !important;
        }

        #munisys-wrapper .fig-vertical {
            writing-mode: vertical-rl !important;
            transform: rotate(180deg) !important;
            font-size: 6rem !important;
            font-weight: 900 !important;
            color: rgba(0, 0, 0, 0.15) !important;
            line-height: 0.8 !important;
            margin: 0 4rem !important;
            text-transform: uppercase !important;
            letter-spacing: -2px !important;
        }

        #munisys-wrapper .fig-grid {
            display: flex !important;
            flex: 1 !important;
            justify-content: space-around !important;
            text-align: center !important;
            flex-wrap: wrap !important;
            gap: 2rem !important;
        }

        #munisys-wrapper .fig-item h3 {
            font-size: 4.5rem !important;
            font-weight: 700 !important;
            margin: 0 !important;
            color: #fff !important;
        }

        #munisys-wrapper .fig-item p {
            font-size: 1.1rem !important;
            font-weight: 700 !important;
            margin-top: 0.5rem !important;
            color: #fff !important;
        }

        /* ── 4. INSIGHTS SECTION ── */
        #munisys-wrapper .ins-sec {
            padding: 6rem 5rem !important;
            display: flex !important;
            gap: 4rem !important;
            max-width: 1440px !important;
            margin: 0 auto !important;
            flex-direction: row !important;
        }

        #munisys-wrapper .ins-title {
            writing-mode: vertical-lr !important;
            transform: rotate(180deg) !important;
            font-size: 4.5rem !important;
            font-weight: 800 !important;

        }

        #munisys-wrapper .ins-grid {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 3rem !important;
            flex: 1 !important;
        }

        #munisys-wrapper .ins-card {
            display: flex !important;
            flex-direction: column !important;
            gap: 1.5rem !important;
            cursor: pointer !important;
        }

        #munisys-wrapper .ins-img-wrap {
            aspect-ratio: 1 !important;
            border: 1px solid #e2e8f0 !important;
            clip-path: polygon(15% 0, 100% 0, 100% 100%, 0 100%, 0 15%) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: hidden !important;
            background: #fff !important;
            padding: 2rem !important;
        }

        #munisys-wrapper .ins-img-wrap img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        #munisys-wrapper .ins-img-wrap.contain img {
            object-fit: contain !important;
        }

        #munisys-wrapper .ins-text {
            font-weight: 700 !important;
            color: var(--text-dark) !important;
            font-size: 1.15rem !important;
            line-height: 1.4 !important;
        }

        /* ── 4.5 TECHNOLOGY PARTNERS SECTION (Glassmorphism) ── */
        #munisys-wrapper .partners-sec {
            scroll-margin-top: 120px !important;
            background: url('<?php echo esc_url(get_option('munisys_part_bg_img', 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2000&auto=format&fit=crop')); ?>') center/cover !important;
            padding: 10rem 5% !important;
            display: flex !important;
            justify-content: center !important;
            position: relative !important;
        }

        #munisys-wrapper .partners-sec::before {
            content: '' !important;
            position: absolute !important;
            inset: 0 !important;
            background: rgba(29, 78, 121, 0.85) !important;
            z-index: 0 !important;
        }

        #munisys-wrapper .part-content {
            position: relative !important;
            z-index: 2 !important;
            max-width: 900px !important;
            text-align: center !important;
            background: rgba(255, 255, 255, 0.05) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            padding: 5rem !important;
            border-radius: 30px !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.3) !important;
        }

        #munisys-wrapper .part-content h2 {
            font-size: 4rem !important;
            font-weight: 200 !important;
            color: #fff !important;
            margin-bottom: 2rem !important;
            letter-spacing: -1px !important;
        }

        #munisys-wrapper .part-content p {
            font-size: 1.25rem !important;
            line-height: 1.8 !important;
            color: rgba(255, 255, 255, 0.8) !important;
            margin-bottom: 3rem !important;
            font-weight: 300 !important;
        }

        #munisys-wrapper .part-link {
            font-weight: 600 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 1rem !important;
            color: #fff !important;
            font-size: 1.1rem !important;
            text-transform: uppercase !important;
            letter-spacing: 2px !important;
            background: var(--bordeaux) !important;
            padding: 1.2rem 2.5rem !important;
            border-radius: 50px !important;
            transition: transform 0.3s !important;
        }

        #munisys-wrapper .part-link:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 10px 20px rgba(146, 43, 85, 0.4) !important;
        }

        /* ── 5. CUSTOMER REFERENCES (Infinite Marquee) ── */
        #munisys-wrapper .ref-sec {
            padding: 8rem 0 0 0 !important;
            background: #fff !important;
            overflow: hidden !important;
        }

        #munisys-wrapper .ref-top {
            text-align: center !important;
            max-width: 800px !important;
            margin: 0 auto 5rem !important;
            padding: 0 5% !important;
        }

        #munisys-wrapper .ref-title {
            font-size: 3.5rem !important;
            font-weight: 200 !important;

            margin-bottom: 1.5rem !important;
            letter-spacing: -1px !important;
        }

        #munisys-wrapper .ref-desc {
            color: var(--text-gray) !important;
            line-height: 1.8 !important;
            font-size: 1.1rem !important;
            font-weight: 300 !important;
        }

        #munisys-wrapper .marquee-wrapper {
            display: flex !important;
            width: max-content !important;
            animation: marquee 30s linear infinite !important;
        }

        #munisys-wrapper .marquee-wrapper:hover {
            animation-play-state: paused !important;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        #munisys-wrapper .marquee-grid {
            display: flex !important;
            flex-shrink: 0 !important;
            width: max-content !important;
            max-width: none !important;
            justify-content: space-around !important;
            align-items: center !important;
            gap: 4rem !important;
            padding: 0 2rem !important;
        }

        #munisys-wrapper .ref-logo {
            display: inline-block !important;
            flex-shrink: 0 !important;
            width: auto !important;
            max-width: none !important;
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 900 !important;
            font-size: 2.5rem !important;
            color: transparent !important;
            -webkit-text-stroke: 1.5px #3b4149ff !important;
            text-transform: uppercase !important;
            white-space: nowrap !important;
            transition: all 0.4s ease !important;
            cursor: default !important;
            letter-spacing: 2px !important;
        }

        #munisys-wrapper .ref-logo:hover {
            color: var(--blue-dark) !important;
            -webkit-text-stroke: 0px !important;
            transform: scale(1.1) !important;
            text-shadow: 0 10px 20px rgba(29, 78, 121, 0.2) !important;
        }

        /* ── FOOTER ── */
        #munisys-wrapper .ft-wrapper {
            background: #1a1a1a !important;
            width: 100% !important;
        }

        #munisys-wrapper .ft {
            background: #1a1a1a !important;
            color: rgba(255, 255, 255, 0.6) !important;
            max-width: 1440px !important;
            margin: 0 auto !important;
        }

        #munisys-wrapper .ft-top {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 2.5rem 5rem !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        #munisys-wrapper .ft-brand {
            font-size: 1.4rem !important;
            font-weight: 900 !important;
            color: #fff !important;
            letter-spacing: 1px !important;
        }

        #munisys-wrapper .ft-linkedin {
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: rgba(255, 255, 255, 0.7) !important;
            font-size: 0.82rem !important;
            font-weight: 600 !important;
            padding: 0.5rem 1.2rem !important;
            border-radius: 3px !important;
            transition: color 0.18s, border-color 0.18s !important;
        }

        #munisys-wrapper .ft-linkedin:hover {
            color: #fff !important;
            border-color: #fff !important;
        }

        #munisys-wrapper .ft-main {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 2rem !important;
            padding: 3rem 5rem !important;
        }

        #munisys-wrapper .ft-col h4 {
            color: #fff !important;
            font-size: 0.68rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 2px !important;
            margin-bottom: 1rem !important;
        }

        #munisys-wrapper .ft-col ul {
            display: flex !important;
            flex-direction: column !important;
            gap: 0.6rem !important;
        }

        #munisys-wrapper .ft-col a {
            font-size: 0.83rem !important;
            color: rgba(255, 255, 255, 0.45) !important;
        }

        #munisys-wrapper .ft-bottom {
            padding: 1.5rem 5rem !important;
            display: flex !important;
            justify-content: space-between !important;
            font-size: 0.75rem !important;
            color: rgba(255, 255, 255, 0.25) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.06) !important;
        }

        /* ── POINTS DE RUPTURE TABLETTE (JUSQU'À 1024PX) ── */
        @media(max-width: 1024px) {
            #munisys-wrapper .text-panel {
                width: 70% !important;
                padding: 3rem 3rem 2rem 3rem !important;
            }

            #munisys-wrapper .text-panel.is-right {
                left: calc(96% - 70%) !important;
            }

            #munisys-wrapper .bento-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            #munisys-wrapper .span-2x2,
            #munisys-wrapper .span-1x2,
            #munisys-wrapper .span-2x1 {
                grid-column: span 1 !important;
                grid-row: span 1 !important;
            }

            #munisys-wrapper .bento-card {
                min-height: 250px !important;
            }
        }

        /* ── POINTS DE RUPTURE MOBILES (ABAISSÉ À 600PX POUR EVITER BREAKDANCE) ── */
        @media(max-width:600px) {
            #munisys-wrapper .text-panel {
                width: 92% !important;
                height: auto !important;
                padding: 3rem 2rem 2rem 2rem !important;
                top: auto !important;
                bottom: 2% !important;
                transform: translateY(0) !important;
                left: 4% !important;
            }

            #munisys-wrapper .text-panel.is-right {
                left: 4% !important;
            }

            #munisys-wrapper .hero-container {
                flex-direction: column;
            }

            #munisys-wrapper .title {
                font-size: 2.2rem !important;
                margin-bottom: 1rem !important;
            }

            #munisys-wrapper .text-slides-wrapper {
                min-height: 380px !important;
            }

            #munisys-wrapper .bento-grid {
                grid-template-columns: 1fr !important;
            }

            #munisys-wrapper .off-flow {
                grid-template-columns: 1fr !important;
            }

            #munisys-wrapper .off-label-vert {
                writing-mode: initial !important;
                transform: none !important;
                text-align: left !important;
                margin-bottom: 1rem !important;
            }

            #munisys-wrapper .srv-sec {
                grid-template-columns: 1fr !important;
            }

            #munisys-wrapper .srv-item {
                grid-template-columns: 1fr auto !important;
                gap: 1.5rem !important;
            }

            #munisys-wrapper .srv-desc-wrap {
                grid-column: 1 / -1 !important;
            }

            #munisys-wrapper .figures-sec {
                flex-direction: column !important;
                padding: 4rem 1rem !important;
            }

            #munisys-wrapper .fig-vertical {
                writing-mode: horizontal-tb !important;
                transform: none !important;
                margin: 0 0 2rem 0 !important;
                font-size: 4rem !important;
                text-align: center !important;
            }

            #munisys-wrapper .fig-grid {
                flex-direction: column !important;
                gap: 3rem !important;
            }

            #munisys-wrapper .ins-sec {
                flex-direction: column !important;
                padding: 4rem 5% !important;
            }

            #munisys-wrapper .ins-title {
                writing-mode: horizontal-tb !important;
                transform: none !important;
                margin-bottom: 2rem !important;
                font-size: 3rem !important;
            }

            #munisys-wrapper .ins-grid {
                grid-template-columns: 1fr !important;
            }

            #munisys-wrapper .partners-sec {
                grid-template-columns: 1fr !important;
                gap: 2rem !important;
            }

            #munisys-wrapper .part-img-wrap {
                clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%) !important;
            }

            #munisys-wrapper .ref-top {
                grid-template-columns: 1fr !important;
            }

            #munisys-wrapper .ft-main {
                grid-template-columns: 1fr 1fr !important;
            }
        }
    </style>

    <div id="munisys-wrapper">
        <div class="hero-container">
            <?php
            $img1 = get_option('munisys_s1_img', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2560&auto=format&fit=crop');
            $img2 = get_option('munisys_s2_img', 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2560&auto=format&fit=crop');
            $img3 = get_option('munisys_s3_img', 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2560&auto=format&fit=crop');
            ?>
            <!-- BACKGROUND IMAGES (Visuels Ultra Premium) -->
            <div class="images-wrapper">
                <!-- Image 1 : Core Infrastructure (Data Network Blue) -->
                <img src="<?php echo esc_url($img1); ?>" class="bg-image active" alt="Infrastructure Datacenter">

                <!-- Image 2 : System Integration (Code & Analytics Blue/Purple) -->
                <img src="<?php echo esc_url($img2); ?>" class="bg-image" alt="System Integration">

                <!-- Image 3 : Cybersecurity (Digital Lock Blue) -->
                <img src="<?php echo esc_url($img3); ?>" class="bg-image" alt="Cybersecurity">
            </div>

            <!-- FRONT PANEL -->
            <div class="text-panel" id="main-panel">
                <div class="text-slides-wrapper">
                    <?php
                    // Detect Language (Polylang / WPML or standard WP Locale)
                    $lang_code = 'en';
                    if (function_exists('pll_current_language')) {
                        $lang_code = pll_current_language('slug') === 'fr' ? 'fr' : 'en';
                    } else {
                        $lang_code = strpos(get_locale(), 'fr') === 0 ? 'fr' : 'en';
                    }

                    $default_hero_en = [
                        1 => [
                            'meta' => 'Core Infrastructure',
                            'title' => 'We orchestrate <strong>digital infrastructure</strong> for a unique <strong>experience.</strong>',
                            'desc' => 'Setup, manage, and secure your compute, storage, and networking across data centers and public clouds with our leading data-centric solutions.',
                            'btn_text' => 'Our solutions',
                            'btn_link' => '/solutions/'
                        ],
                        2 => [
                            'meta' => 'Cybersecurity',
                            'title' => 'Protecting your <strong>digital assets</strong> with cutting-edge <strong>security.</strong>',
                            'desc' => 'Secure your network, endpoints, and data against evolving digital threats with zero-trust architectures and proactive monitoring.',
                            'btn_text' => 'Discover security',
                            'btn_link' => '/solutions/#cybersecurity'
                        ],
                        3 => [
                            'meta' => 'Managed Services',
                            'title' => 'Accelerating your <strong>cloud journey</strong> with seamless <strong>management.</strong>',
                            'desc' => 'Seamless cloud integration and managed services to accelerate your digital transformation journey while ensuring high availability.',
                            'btn_text' => 'Cloud services',
                            'btn_link' => '/managed-services/'
                        ]
                    ];

                    $default_hero_fr = [
                        1 => [
                            'meta' => 'Infrastructure de Base',
                            'title' => 'Nous orchestrons <strong>l\'infrastructure numérique</strong> pour une <strong>expérience unique.</strong>',
                            'desc' => 'Configurez, gérez et sécurisez vos calculs, stockages et réseaux avec nos solutions orientées données.',
                            'btn_text' => 'Nos solutions',
                            'btn_link' => '/solutions/'
                        ],
                        2 => [
                            'meta' => 'Cybersécurité',
                            'title' => 'Protégez vos <strong>actifs numériques</strong> avec une <strong>sécurité avancée.</strong>',
                            'desc' => 'Sécurisez votre réseau, vos terminaux et vos données contre les menaces numériques avec des architectures zero-trust.',
                            'btn_text' => 'Découvrir la sécurité',
                            'btn_link' => '/solutions/#cybersecurity'
                        ],
                        3 => [
                            'meta' => 'Services Managés',
                            'title' => 'Accélérez votre <strong>migration cloud</strong> avec une <strong>gestion fluide.</strong>',
                            'desc' => 'Intégration cloud transparente et services gérés pour accélérer votre parcours de transformation numérique.',
                            'btn_text' => 'Services Cloud',
                            'btn_link' => '/managed-services/'
                        ]
                    ];

                    $default_hero = ($lang_code === 'fr') ? $default_hero_fr : $default_hero_en;


                    for ($i = 1; $i <= 3; $i++):
                        $m = get_option('munisys_s' . $i . '_meta', $default_hero[$i]['meta']);
                        $t = get_option('munisys_s' . $i . '_title', $default_hero[$i]['title']);
                        $d = get_option('munisys_s' . $i . '_desc', $default_hero[$i]['desc']);
                        $bt = get_option('munisys_s' . $i . '_btn_text', $default_hero[$i]['btn_text']);
                        $bl = get_option('munisys_s' . $i . '_btn_link', $default_hero[$i]['btn_link']);

                        // Si l'option retourne vide (effacée par erreur), forcer le fallback
                        if (empty($m))
                            $m = $default_hero[$i]['meta'];
                        if (empty($t))
                            $t = $default_hero[$i]['title'];
                        if (empty($d))
                            $d = $default_hero[$i]['desc'];
                        if (empty($bt))
                            $bt = $default_hero[$i]['btn_text'];
                        if (empty($bl) || $bl === '#')
                            $bl = $default_hero[$i]['btn_link'];
                        ?>
                        <div class="text-slide <?php echo $i === 1 ? 'active' : ''; ?>">
                            <div class="meta"><?php echo esc_html($m); ?></div>
                            <h1 class="title"><?php echo wp_kses_post($t); ?></h1>
                            <p class="desc"><?php echo esc_html($d); ?></p>
                            <div class="btn-wrap">
                                <a href="<?php echo esc_url($bl); ?>" class="btn-elegant">
                                    <span><?php echo esc_html($bt); ?></span>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endfor; ?>
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

        <!-- ── 1. SOLUTIONS SECTION (BENTO GRID) ── -->
        <div class="sol-wrapper" id="solutions-section">
            <section class="sol-sec">
                <h2 class="sol-title"
                    style="font-size: 3rem !important; font-weight: 900 !important; color: #1d4e79 !important; margin-bottom: 3rem !important; text-align: center !important; opacity: 1 !important; visibility: visible !important; display: block !important;">
                    Our Solutions</h2>
                <div class="bento-grid">

                    <?php
                    $def_sol = [
                        1 => ['title' => 'Core Infrastructure<br/>Technologies', 'desc' => 'Compute, storage, networking and backup infrastructure...', 'btn' => 'Read more', 'img' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?q=80&w=1200&auto=format&fit=crop', 'link' => '/solutions/#core-infra'],
                        2 => ['title' => 'Cybersecurity<br/>Solutions', 'desc' => 'End-to-end security architectures to protect your digital assets...', 'btn' => 'Discover', 'img' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=800&auto=format&fit=crop', 'link' => '/solutions/#cybersecurity'],
                        3 => ['title' => 'Infrastructure<br/>Software', 'desc' => 'Software-defined approaches and robust integrations...', 'btn' => '', 'img' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop', 'link' => '/solutions/#infra-software'],
                        4 => ['title' => 'Managed &amp;<br/>Cloud Services', 'desc' => 'Seamless operations across public, private, and hybrid...', 'btn' => '', 'img' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=800&auto=format&fit=crop', 'link' => '/solutions/#managed-cloud'],
                        5 => ['title' => 'Endpoint Solutions', 'desc' => 'Empowering your workforce with secure and efficient...', 'btn' => 'View Offerings', 'img' => 'https://images.unsplash.com/photo-1573164713988-8665fc963095?q=80&w=800&auto=format&fit=crop', 'link' => '/solutions/#endpoint-sol']
                    ];
                    ?>

                    <!-- 1. Core Infrastructure (Grande carte 2x3) -->
                    <a href="<?php echo esc_url(get_option('munisys_sol_1_link', $def_sol[1]['link'])); ?>"
                        class="bento-card span-2x3">
                        <img src="<?php echo esc_url(get_option('munisys_sol_1_img', $def_sol[1]['img'])); ?>"
                            class="bento-bg" alt="Datacenter">
                        <div class="bento-overlay"></div>
                        <div class="bento-content">
                            <h3><?php echo wp_kses_post(get_option('munisys_sol_1_title', $def_sol[1]['title'])); ?></h3>
                            <p><?php echo esc_html(get_option('munisys_sol_1_desc', $def_sol[1]['desc'])); ?></p>
                            <span
                                class="bento-link"><?php echo esc_html(get_option('munisys_sol_1_btn', $def_sol[1]['btn'])); ?>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </a>

                    <!-- 2. Cybersecurity (Carte haute 2x1) -->
                    <a href="<?php echo esc_url(get_option('munisys_sol_2_link', $def_sol[2]['link'])); ?>"
                        class="bento-card span-2x1">
                        <img src="<?php echo esc_url(get_option('munisys_sol_2_img', $def_sol[2]['img'])); ?>"
                            class="bento-bg" alt="Cybersecurity">
                        <div class="bento-overlay overlay-cyber"></div>
                        <div class="bento-content">
                            <h3><?php echo wp_kses_post(get_option('munisys_sol_2_title', $def_sol[2]['title'])); ?></h3>
                            <p><?php echo esc_html(get_option('munisys_sol_2_desc', $def_sol[2]['desc'])); ?></p>
                            <span
                                class="bento-link"><?php echo esc_html(get_option('munisys_sol_2_btn', $def_sol[2]['btn'])); ?>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </a>

                    <!-- 3. Infrastructure Software (Carte normale) -->
                    <a href="<?php echo esc_url(get_option('munisys_sol_3_link', $def_sol[3]['link'])); ?>"
                        class="bento-card glass">
                        <img src="<?php echo esc_url(get_option('munisys_sol_3_img', $def_sol[3]['img'])); ?>"
                            class="bento-bg" alt="Software">
                        <div class="bento-overlay"></div>
                        <div class="bento-content">
                            <h3><?php echo wp_kses_post(get_option('munisys_sol_3_title', $def_sol[3]['title'])); ?></h3>
                            <p><?php echo esc_html(get_option('munisys_sol_3_desc', $def_sol[3]['desc'])); ?></p>
                        </div>
                    </a>

                    <!-- 4. Managed & Cloud Services (Carte normale) -->
                    <a href="<?php echo esc_url(get_option('munisys_sol_4_link', $def_sol[4]['link'])); ?>"
                        class="bento-card glass">
                        <img src="<?php echo esc_url(get_option('munisys_sol_4_img', $def_sol[4]['img'])); ?>"
                            class="bento-bg" alt="Cloud">
                        <div class="bento-overlay"></div>
                        <div class="bento-content">
                            <h3><?php echo wp_kses_post(get_option('munisys_sol_4_title', $def_sol[4]['title'])); ?></h3>
                            <p><?php echo esc_html(get_option('munisys_sol_4_desc', $def_sol[4]['desc'])); ?></p>
                        </div>
                    </a>

                    <!-- 5. Endpoint solutions (Carte large 2x1) -->
                    <a href="<?php echo esc_url(get_option('munisys_sol_5_link', $def_sol[5]['link'])); ?>"
                        class="bento-card span-2x1">
                        <img src="<?php echo esc_url(get_option('munisys_sol_5_img', $def_sol[5]['img'])); ?>"
                            class="bento-bg" alt="Endpoint">
                        <div class="bento-overlay"></div>
                        <div class="bento-content">
                            <h3><?php echo wp_kses_post(get_option('munisys_sol_5_title', $def_sol[5]['title'])); ?></h3>
                            <p><?php echo esc_html(get_option('munisys_sol_5_desc', $def_sol[5]['desc'])); ?></p>
                            <span
                                class="bento-link"><?php echo esc_html(get_option('munisys_sol_5_btn', $def_sol[5]['btn'])); ?>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </a>

                </div>
            </section>
        </div>

        <!-- ── 2. OFFERINGS SECTION ── -->
        <section class="off-sec">
            <div class="off-header">
                <h2 class="off-title"><?php echo esc_html(get_option('munisys_off_title', 'Offerings')); ?></h2>
                <p class="off-desc">
                    <?php echo esc_html(get_option('munisys_off_desc', 'An end-to-end & coherent offer of solutions & services for secure digital infrastructures')); ?>
                </p>
            </div>
            <div class="off-flow">
                <div class="off-label-vert off-label-through">Through</div>
                <div class="off-col">
                    <div class="off-item"><span class="off-check"><svg width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12l3 3 5-6" />
                            </svg></span><span>Best of breed technologies</span></div>
                    <div class="off-item"><span class="off-check"><svg width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12l3 3 5-6" />
                            </svg></span><span>Skillful integrations</span></div>
                    <div class="off-item"><span class="off-check"><svg width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12l3 3 5-6" />
                            </svg></span><span>Sound architectures</span></div>
                    <div class="off-item"><span class="off-check"><svg width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12l3 3 5-6" />
                            </svg></span><span>Automation and orchestration</span></div>
                    <div class="off-item"><span class="off-check"><svg width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12l3 3 5-6" />
                            </svg></span><span>Qualified end-to-end services</span></div>
                </div>
                <div class="off-label-vert off-label-achieve">We achieve</div>
                <div class="off-col">
                    <div class="off-item"><span class="off-check"><svg width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12l3 3 5-6" />
                            </svg></span><span>Efficient use of converging technologies</span></div>
                    <div class="off-item"><span class="off-check"><svg width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12l3 3 5-6" />
                            </svg></span><span>Control over complexity</span></div>
                    <div class="off-item"><span class="off-check"><svg width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12l3 3 5-6" />
                            </svg></span><span>Simplified IT Operations</span></div>
                    <div class="off-item"><span class="off-check"><svg width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12l3 3 5-6" />
                            </svg></span><span>Agile infrastructure resources</span></div>
                    <div class="off-item"><span class="off-check"><svg width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12l3 3 5-6" />
                            </svg></span><span>Optimal performance and true resilience</span></div>
                    <div class="off-item"><span class="off-check"><svg width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12l3 3 5-6" />
                            </svg></span><span>Deep and broad support capabilities</span></div>
                </div>
            </div>
        </section>

        <!-- ── 3. SERVICES SECTION (Premium Hover List) ── -->
        <div class="srv-wrapper">
            <section class="srv-sec" id="services">
                <div class="srv-left">
                    <h2>Our Services</h2>
                </div>
                <div class="srv-right">
                    <?php
                    $def_srv = [
                        1 => ['title' => 'IT integration', 'desc' => 'At every stage in the lifecycle of your IT Infrastructures, we ensure smooth, efficient, and robust technology alignment.', 'link' => '/services/#it-integration'],
                        2 => ['title' => 'IT support', 'desc' => 'Reactive and proactive support services to guarantee maximum uptime, security, and continuous business operations.', 'link' => '/services/#it-support'],
                        3 => ['title' => 'Managed services', 'desc' => 'Offload your daily IT operations to our certified experts, allowing your team to focus strictly on innovation and growth.', 'link' => '/services/#managed-services']
                    ];
                    for ($i = 1; $i <= 3; $i++):
                        ?>
                        <!-- Service <?php echo $i; ?> -->
                        <a href="<?php echo esc_url(get_option('munisys_srv_' . $i . '_link', $def_srv[$i]['link'])); ?>"
                            class="srv-item">
                            <div class="srv-title-wrap">
                                <span class="srv-num">0<?php echo $i; ?></span>
                                <h3 class="srv-title">
                                    <?php echo esc_html(get_option('munisys_srv_' . $i . '_title', $def_srv[$i]['title'])); ?>
                                </h3>
                            </div>
                            <div class="srv-desc-wrap">
                                <p><?php echo esc_html(get_option('munisys_srv_' . $i . '_desc', $def_srv[$i]['desc'])); ?></p>
                            </div>
                            <div class="srv-arrow">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    <?php endfor; ?>
                </div>
            </section>
        </div>

        <!-- ── 3.5 SOME FIGURES SECTION ── -->
        <section class="figures-sec" id="about-us">
            <div class="fig-vertical">SOME<br />FIGURES</div>
            <div class="fig-grid">
                <?php
                $def_fig = [
                    1 => ['num' => '34', 'text' => 'years<br/>in business'],
                    2 => ['num' => '330', 'text' => 'employees'],
                    3 => ['num' => '700', 'text' => 'MMAD Revenue'],
                    4 => ['num' => '18', 'text' => 'Support<br/>centers']
                ];
                for ($i = 1; $i <= 4; $i++):
                    ?>
                    <div class="fig-item">
                        <?php if ($i == 1): ?>
                            <h3>+<span class="counter-val"
                                    data-target="<?php echo esc_attr(get_option('munisys_fig_' . $i . '_num', $def_fig[$i]['num'])); ?>">0</span>
                            </h3>
                        <?php else: ?>
                            <h3 class="counter-val"
                                data-target="<?php echo esc_attr(get_option('munisys_fig_' . $i . '_num', $def_fig[$i]['num'])); ?>">
                                0
                            </h3>
                        <?php endif; ?>
                        <p><?php echo wp_kses_post(get_option('munisys_fig_' . $i . '_text', $def_fig[$i]['text'])); ?></p>
                    </div>
                <?php endfor; ?>
            </div>
        </section>

        <!-- ── 4. INSIGHTS SECTION ── -->
        <section class="ins-sec">
            <div class="ins-title"><?php echo esc_html(get_option('munisys_ins_title', 'Insights')); ?></div>
            <div class="ins-grid">
                <?php
                $def_ins = [
                    1 => ['text' => 'A new visual identity for Munisys'],
                    2 => ['text' => 'New headquarters']
                ];
                for ($i = 1; $i <= 2; $i++):
                    ?>
                    <a href="<?php echo esc_url(get_option('munisys_ins_' . $i . '_link', '#')); ?>" class="ins-card temp-link"
                        style="text-decoration: none;">
                        <?php if ($i == 1): ?>
                            <div class="ins-img-wrap contain"
                                style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                <span
                                    style="font-size: 2.5rem; font-weight: 800; color: #1d4e79; letter-spacing: 2px;">MUNISYS</span>
                            </div>
                        <?php else: ?>
                            <div class="ins-img-wrap">
                                <img src="<?php echo esc_url(get_option('munisys_ins_' . $i . '_img', $def_ins[$i]['img'])); ?>"
                                    alt="Insight Image" />
                            </div>
                        <?php endif; ?>
                        <div class="ins-text">
                            <?php echo esc_html(get_option('munisys_ins_' . $i . '_text', $def_ins[$i]['text'])); ?>
                        </div>
                    </a>
                <?php endfor; ?>

                <!-- CARTE PRESS RELEASE (Pointe toujours vers la page /press-release/ du site) -->
                <a href="<?php echo esc_url($link_press_release); ?>" class="ins-card" style="text-decoration: none;">
                    <div class="ins-img-wrap contain" style="flex-direction: column; gap: 1rem; text-align: center;">
                        <h2
                            style="font-size: clamp(1.2rem, 2vw, 1.8rem); font-weight: 300; color: #111; margin:0; line-height: 1.2;">
                            HELIOS <br /><span style="color: #f97316">Investment Partners</span></h2>
                        <h1 style="font-size: clamp(1.8rem, 3vw, 2.8rem); font-weight: 900; color: #1d4e79; margin:0;">FIPAR
                        </h1>
                    </div>
                    <div class="ins-text" style="text-transform: uppercase">PRESS RELEASE</div>
                </a>
            </div>
        </section>

        <!-- ── 4.5 TECHNOLOGY PARTNERS SECTION (Glassmorphism) ── -->
        <section class="partners-sec" id="technology-partners">
            <div class="part-content">
                <h2><?php echo esc_html(get_option('munisys_part_title', 'Technology partners')); ?></h2>
                <p><?php echo esc_html(get_option('munisys_part_desc', 'The IT infrastructures we design and deploy for our clients are at the heart of their business and determine their capacity to innovate and stand out. We build solid ties with our technology partners and acquire in-house expertise to ensure true resilience and agility.')); ?>
                </p>
                <a href="http://localhost:10003/technology-partners/" class="part-link temp-link">Read more <svg width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg></a>
            </div>
        </section>

        <!-- ── 5. CUSTOMER REFERENCES (Infinite Marquee) ── -->
        <section class="ref-sec" id="customer-references">
            <div class="ref-top">
                <h2 class="ref-title"><?php echo esc_html(get_option('munisys_ref_title', 'They trust us')); ?></h2>
                <p class="ref-desc">
                    <?php echo esc_html(get_option('munisys_ref_desc', 'As a trusted advisor, our contribution to the design, deployment and support of technological solutions is delivered in the best interests of our clients, prioritizing integrity and long-term partnerships.')); ?>
                </p>
            </div>

            <!-- Marquee 1 (Left to Right) -->
            <div style="overflow: hidden; margin-bottom: 2rem; position: relative;">
                <div class="marquee-wrapper">
                    <!-- Duplicate Content to ensure seamless loop -->
                    <div class="marquee-grid">
                        <div class="ref-logo">Attijariwafa bank</div>
                        <div class="ref-logo">Autoroutes du Maroc</div>
                        <div class="ref-logo">Bank Al-Maghrib</div>
                        <div class="ref-logo">BANK OF AFRICA</div>
                        <div class="ref-logo">BANQUE POPULAIRE</div>
                    </div>
                    <div class="marquee-grid">
                        <div class="ref-logo">Attijariwafa bank</div>
                        <div class="ref-logo">Autoroutes du Maroc</div>
                        <div class="ref-logo">Bank Al-Maghrib</div>
                        <div class="ref-logo">BANK OF AFRICA</div>
                        <div class="ref-logo">BANQUE POPULAIRE</div>
                    </div>
                </div>
            </div>

            <!-- Marquee 2 (Right to Left / Different speed or items) -->
            <div style="overflow: hidden; position: relative;">
                <div class="marquee-wrapper" style="animation-direction: reverse; animation-duration: 40s;">
                    <!-- Duplicate Content to ensure seamless loop -->
                    <div class="marquee-grid">
                        <div class="ref-logo">CDG</div>
                        <div class="ref-logo">EURAFRIC</div>
                        <div class="ref-logo">Maroc Telecom</div>
                        <div class="ref-logo">inwi</div>
                        <div class="ref-logo">LafargeHolcim</div>
                        <div class="ref-logo">MARBIO</div>
                        <div class="ref-logo">OCP</div>
                        <div class="ref-logo">ONCF</div>
                    </div>
                    <div class="marquee-grid">
                        <div class="ref-logo">CDG</div>
                        <div class="ref-logo">EURAFRIC</div>
                        <div class="ref-logo">Maroc Telecom</div>
                        <div class="ref-logo">inwi</div>
                        <div class="ref-logo">LafargeHolcim</div>
                        <div class="ref-logo">MARBIO</div>
                        <div class="ref-logo">OCP</div>
                        <div class="ref-logo">ONCF</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: center; width:100%; margin-top: 1.5rem; margin-bottom: 0;">
                <a href="#" class="ref-seeall temp-link"
                    style=" text-decoration: none !important; border-bottom: 2px solid var(--bordeaux) !important; padding-bottom: 5px !important; letter-spacing: 1px !important; text-transform: uppercase !important;">See
                    all references</a>









            </div>
        </section>



    </div>

    <?php
    return ob_get_clean();
}

// ==========================================
// 2. INJECTION DU JAVASCRIPT (wp_footer)
// ==========================================
add_action('wp_footer', 'munisys_inject_new_hero_script', 99);
function munisys_inject_new_hero_script()
{
    // Le script s'exécute et vérifie lui-même si le conteneur existe (permet l'utilisation du shortcode sur n'importe quelle page)
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

                document.addEventListener('click', function (e) {
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
            // La section Solutions utilise maintenant le CSS Bento Grid (sans onglets JS).

            if (!window.munisysPageEventsBound) {
                window.munisysPageEventsBound = true;

                document.addEventListener('click', function (e) {


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
                                if (b) b.style.display = 'none';
                                if (i) i.textContent = '+';
                            });
                            if (!isAlreadyOpen) {
                                acc.classList.add('open');
                                const b = acc.querySelector('.srv-acc-body');
                                const i = acc.querySelector('.acc-x');
                                if (b) b.style.display = 'block';
                                if (i) i.textContent = '−';
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

                    // D. LIENS TEMPORAIRES (Pop-up) retirés pour permettre la navigation
                });

                // F. MENU SCROLL HIJACKING (One-Page Experience) - DISABLED
                // Navigation now works normally and redirects to dedicated pages.
                // E. COMPTEURS "SOME FIGURES"
                const counters = document.querySelectorAll('.counter-val');
                if (counters.length > 0) {
                    const observer = new IntersectionObserver((entries, obs) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const target = entry.target;
                                const endVal = parseInt(target.getAttribute('data-target'));
                                let startVal = 0;
                                const duration = 2000;
                                const increment = endVal / (duration / 16);

                                const updateCounter = () => {
                                    startVal += increment;
                                    if (startVal < endVal) {
                                        target.textContent = Math.ceil(startVal);
                                        requestAnimationFrame(updateCounter);
                                    } else {
                                        target.textContent = endVal;
                                    }
                                };
                                updateCounter();
                                obs.unobserve(target); // Ne jouer l'animation qu'une seule fois
                            }
                        });
                    }, { threshold: 0.5 });

                    counters.forEach(c => observer.observe(c));
                }

            }
        })();
    </script>
    <?php
}

// ==========================================
// 3. INTERFACE D'ADMINISTRATION POUR NON-INFORMATICIENS
// ==========================================
add_action('admin_menu', 'munisys_setup_admin_menu');
function munisys_setup_admin_menu()
{
    // Main Menu
    add_menu_page(
        'Munisys CMS',              // Titre de la page
        'Munisys CMS',              // Titre dans le menu de gauche
        'edit_pages',               // Permissions
        'munisys-cms',              // Identifiant
        'munisys_cms_global_html',  // Fonction d'affichage (Global/Couleurs)
        'dashicons-admin-site-alt3',// Icône
        25                          // Position
    );

    // Submenus
    add_submenu_page('munisys-cms', 'Charte Graphique', 'Charte Graphique', 'edit_pages', 'munisys-cms', 'munisys_cms_global_html');
    add_submenu_page('munisys-cms', 'Page Accueil', 'Page Accueil', 'edit_pages', 'munisys-accueil', 'munisys_accueil_settings_html');
    add_submenu_page('munisys-cms', 'Page Solutions', 'Page Solutions', 'edit_pages', 'munisys-solutions-page', 'munisys_solutions_page_settings_html');
    add_submenu_page('munisys-cms', 'Page Contact', 'Page Contact', 'edit_pages', 'munisys-contact', 'munisys_contact_settings_html');
    add_submenu_page('munisys-cms', 'Page À Propos', 'Page À Propos', 'edit_pages', 'munisys-about', 'munisys_about_settings_html');
    add_submenu_page('munisys-cms', 'Tech Partners', 'Tech Partners', 'edit_pages', 'munisys-partners', 'munisys_partners_settings_html');
    add_submenu_page('munisys-cms', 'Customer Refs', 'Customer Refs', 'edit_pages', 'munisys-references', 'munisys_references_settings_html');
    add_submenu_page('munisys-cms', 'Services', 'Services', 'edit_pages', 'munisys-services', 'munisys_services_settings_html');
    add_submenu_page('munisys-cms', 'Footer', 'Footer', 'edit_pages', 'munisys-footer', 'munisys_footer_settings_html');
    add_submenu_page('munisys-cms', 'En-tête (Header)', 'En-tête (Header)', 'edit_pages', 'munisys-header', 'munisys_header_settings_html');
    add_submenu_page('munisys-cms', '📰 Insights', '📰 Insights', 'edit_pages', 'munisys-insights', 'munisys_insights_settings_html');
}

function munisys_cms_global_html()
{
    if (!current_user_can('edit_pages'))
        return;

    if (isset($_POST['submit_munisys_global'])) {
        if (isset($_POST['munisys_color_primary'])) {
            update_option('munisys_color_primary', sanitize_hex_color($_POST['munisys_color_primary']));
            update_option('munisys_color_dark', sanitize_hex_color($_POST['munisys_color_dark']));
            update_option('munisys_color_light', sanitize_hex_color($_POST['munisys_color_light']));
        }
        echo '<div class="notice notice-success is-dismissible"><p>✅ Charte Graphique mise à jour avec succès !</p></div>';
    }

    $c_primary = get_option('munisys_color_primary', '#922b55');
    $c_dark = get_option('munisys_color_dark', '#1d4e79');
    $c_light = get_option('munisys_color_light', '#83a5c2');
    ?>
    <div class="wrap">
        <h1>🎨 Charte Graphique du Site</h1>
        <p>Ces couleurs s'appliqueront instantanément sur TOUTES les pages du site.</p>
        <form method="POST"
            style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 600px; margin-top: 20px;">
            <input type="hidden" name="submit_munisys_global" value="1">
            <table class="form-table">
                <tr>
                    <th scope="row"><label>Couleur Primaire (Bordeaux)</label></th>
                    <td><input type="color" name="munisys_color_primary" value="<?php echo esc_attr($c_primary); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row"><label>Couleur Secondaire (Bleu Foncé)</label></th>
                    <td><input type="color" name="munisys_color_dark" value="<?php echo esc_attr($c_dark); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row"><label>Couleur Accent (Bleu Clair)</label></th>
                    <td><input type="color" name="munisys_color_light" value="<?php echo esc_attr($c_light); ?>" /></td>
                </tr>
            </table>
            <p class="submit" style="margin-top: 30px;">
                <input type="submit" class="button button-primary button-hero" value="Enregistrer les couleurs">
            </p>
        </form>
    </div>
    <?php
}

function munisys_accueil_settings_html()
{
    if (!current_user_can('edit_pages'))
        return;

    // Sauvegarde des données
    if (isset($_POST['submit_munisys_settings'])) {
        // TAB : HERO
        if (isset($_POST['munisys_press_release_url'])) {
            update_option('munisys_press_release_url', esc_url_raw($_POST['munisys_press_release_url']));
            for ($i = 1; $i <= 3; $i++) {
                if (isset($_POST['munisys_s' . $i . '_title'])) {
                    update_option('munisys_s' . $i . '_meta', sanitize_text_field($_POST['munisys_s' . $i . '_meta']));
                    update_option('munisys_s' . $i . '_title', wp_kses_post(stripslashes($_POST['munisys_s' . $i . '_title'])));
                    update_option('munisys_s' . $i . '_desc', sanitize_textarea_field($_POST['munisys_s' . $i . '_desc']));
                    update_option('munisys_s' . $i . '_btn_text', sanitize_text_field($_POST['munisys_s' . $i . '_btn_text']));
                    update_option('munisys_s' . $i . '_btn_link', esc_url_raw($_POST['munisys_s' . $i . '_btn_link']));
                    update_option('munisys_s' . $i . '_img', esc_url_raw($_POST['munisys_s' . $i . '_img']));
                }
            }
        }

        // TAB : SOLUTIONS
        for ($i = 1; $i <= 5; $i++) {
            if (isset($_POST['munisys_sol_' . $i . '_title'])) {
                update_option('munisys_sol_' . $i . '_title', wp_kses_post(stripslashes($_POST['munisys_sol_' . $i . '_title'])));
                update_option('munisys_sol_' . $i . '_desc', sanitize_textarea_field($_POST['munisys_sol_' . $i . '_desc']));
                update_option('munisys_sol_' . $i . '_btn', sanitize_text_field($_POST['munisys_sol_' . $i . '_btn']));
                update_option('munisys_sol_' . $i . '_link', esc_url_raw($_POST['munisys_sol_' . $i . '_link']));
                update_option('munisys_sol_' . $i . '_img', esc_url_raw($_POST['munisys_sol_' . $i . '_img']));
            }
        }

        // TAB : SERVICES
        if (isset($_POST['munisys_off_title'])) {
            update_option('munisys_off_title', sanitize_text_field($_POST['munisys_off_title']));
            update_option('munisys_off_desc', sanitize_textarea_field($_POST['munisys_off_desc']));
            for ($i = 1; $i <= 3; $i++) {
                update_option('munisys_srv_' . $i . '_title', sanitize_text_field($_POST['munisys_srv_' . $i . '_title']));
                update_option('munisys_srv_' . $i . '_desc', sanitize_textarea_field($_POST['munisys_srv_' . $i . '_desc']));
                update_option('munisys_srv_' . $i . '_link', esc_url_raw($_POST['munisys_srv_' . $i . '_link']));
            }
            for ($i = 1; $i <= 4; $i++) {
                update_option('munisys_fig_' . $i . '_num', sanitize_text_field($_POST['munisys_fig_' . $i . '_num']));
                update_option('munisys_fig_' . $i . '_text', wp_kses_post(stripslashes($_POST['munisys_fig_' . $i . '_text'])));
            }
        }

        // TAB : INSIGHTS
        if (isset($_POST['munisys_ins_title'])) {
            update_option('munisys_ins_title', sanitize_text_field($_POST['munisys_ins_title']));
            for ($i = 1; $i <= 2; $i++) {
                update_option('munisys_ins_' . $i . '_text', sanitize_text_field($_POST['munisys_ins_' . $i . '_text']));
                update_option('munisys_ins_' . $i . '_link', esc_url_raw($_POST['munisys_ins_' . $i . '_link']));
                update_option('munisys_ins_' . $i . '_img', esc_url_raw($_POST['munisys_ins_' . $i . '_img']));
            }
            if (isset($_POST['munisys_press_release_img'])) {
                update_option('munisys_press_release_img', esc_url_raw($_POST['munisys_press_release_img']));
            }
            update_option('munisys_part_title', sanitize_text_field($_POST['munisys_part_title']));
            update_option('munisys_part_desc', sanitize_textarea_field($_POST['munisys_part_desc']));
            update_option('munisys_part_bg_img', esc_url_raw($_POST['munisys_part_bg_img']));
            update_option('munisys_ref_title', sanitize_text_field($_POST['munisys_ref_title']));
            update_option('munisys_ref_desc', sanitize_textarea_field($_POST['munisys_ref_desc']));
        }

        echo '<div class="notice notice-success is-dismissible"><p>✅ Paramètres mis à jour avec succès !</p></div>';
    }

    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'hero';
    ?>
    <div class="wrap">
        <h1>⚙️ Paramètres de la Page Accueil</h1>
        <p>Gérez ici l'ensemble des textes et images de la page d'accueil.</p>

        <h2 class="nav-tab-wrapper">
            <a href="?page=munisys-accueil&tab=hero"
                class="nav-tab <?php echo $active_tab == 'hero' ? 'nav-tab-active' : ''; ?>">🚀 Accueil - En-tête</a>
            <a href="?page=munisys-accueil&tab=solutions"
                class="nav-tab <?php echo $active_tab == 'solutions' ? 'nav-tab-active' : ''; ?>">💼 Accueil - Grille
                Solutions (Bento)</a>
            <a href="?page=munisys-accueil&tab=services"
                class="nav-tab <?php echo $active_tab == 'services' ? 'nav-tab-active' : ''; ?>">⚙️ Accueil - Services &
                Chiffres</a>
            <a href="?page=munisys-accueil&tab=insights"
                class="nav-tab <?php echo $active_tab == 'insights' ? 'nav-tab-active' : ''; ?>">📰 Accueil - Insights &
                Parts</a>
        </h2>

        <form method="POST"
            style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 900px; margin-top: 20px;">
            <input type="hidden" name="submit_munisys_settings" value="1">


            <?php if ($active_tab == 'hero'):
                $current_url = get_option('munisys_press_release_url', '');
                $default_hero = [
                    1 => ['meta' => 'Core Infrastructure', 'title' => 'We orchestrate <strong>digital infrastructure</strong> for a unique <strong>experience.</strong>', 'desc' => 'Setup, manage, and secure your compute, storage, and networking across data centers and public clouds with our leading data-centric solutions.', 'btn_text' => 'Our solutions', 'btn_link' => '/solutions/', 'img' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2560&auto=format&fit=crop'],
                    2 => ['meta' => 'Cybersecurity', 'title' => 'Protecting your <strong>digital assets</strong> with cutting-edge <strong>security.</strong>', 'desc' => 'Secure your network, endpoints, and data against evolving digital threats with zero-trust architectures and proactive monitoring.', 'btn_text' => 'Discover security', 'btn_link' => '/solutions/', 'img' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2560&auto=format&fit=crop'],
                    3 => ['meta' => 'Managed Services', 'title' => 'Accelerating your <strong>cloud journey</strong> with seamless <strong>management.</strong>', 'desc' => 'Seamless cloud integration and managed services to accelerate your digital transformation journey while ensuring high availability.', 'btn_text' => 'Cloud services', 'btn_link' => '/managed-services/', 'img' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2560&auto=format&fit=crop']
                ];
                ?>
                <h2 style="color: #1d4e79;">📄 Document Press Release</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label>URL du PDF :</label></th>
                        <td><input type="text" name="munisys_press_release_url" value="<?php echo esc_attr($current_url); ?>"
                                style="width: 100%;" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Image de couverture (URL) :</label></th>
                        <td><input type="text" name="munisys_press_release_img"
                                value="<?php echo esc_url(get_option('munisys_press_release_img', '/wp-content/uploads/datacenter.png')); ?>"
                                style="width: 100%;" /></td>
                    </tr>
                </table>

                <h2 style="margin-top:40px; color: #1d4e79;">🚀 En-tête Animé (Hero Section)</h2>
                <?php for ($i = 1; $i <= 3; $i++):
                    $m = get_option('munisys_s' . $i . '_meta', $default_hero[$i]['meta']);
                    $t = get_option('munisys_s' . $i . '_title', $default_hero[$i]['title']);
                    $d = get_option('munisys_s' . $i . '_desc', $default_hero[$i]['desc']);
                    $bt = get_option('munisys_s' . $i . '_btn_text', $default_hero[$i]['btn_text']);
                    $bl = get_option('munisys_s' . $i . '_btn_link', $default_hero[$i]['btn_link']);
                    $img = get_option('munisys_s' . $i . '_img', $default_hero[$i]['img']);
                    ?>
                    <div style="background: #f8f9fa; border: 1px solid #ddd; padding: 15px; margin-top: 20px;">
                        <h3 style="margin-top: 0; color: #922b55;">Slide <?php echo $i; ?></h3>
                        <table class="form-table">
                            <tr>
                                <th>Surtitre</th>
                                <td><input type="text" name="munisys_s<?php echo $i; ?>_meta" value="<?php echo esc_attr($m); ?>"
                                        style="width: 100%;" /></td>
                            </tr>
                            <tr>
                                <th>Titre</th>
                                <td><textarea name="munisys_s<?php echo $i; ?>_title" rows="2"
                                        style="width: 100%;"><?php echo esc_textarea($t); ?></textarea></td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td><textarea name="munisys_s<?php echo $i; ?>_desc" rows="3"
                                        style="width: 100%;"><?php echo esc_textarea($d); ?></textarea></td>
                            </tr>
                            <tr>
                                <th>Bouton (Texte)</th>
                                <td><input type="text" name="munisys_s<?php echo $i; ?>_btn_text"
                                        value="<?php echo esc_attr($bt); ?>" style="width: 100%;" /></td>
                            </tr>
                            <tr>
                                <th>Bouton (Lien)</th>
                                <td><input type="text" name="munisys_s<?php echo $i; ?>_btn_link"
                                        value="<?php echo esc_url($bl); ?>" style="width: 100%;" /></td>
                            </tr>
                            <tr>
                                <th>Image de fond (URL)</th>
                                <td><input type="text" name="munisys_s<?php echo $i; ?>_img" value="<?php echo esc_attr($img); ?>"
                                        style="width: 100%;" /></td>
                            </tr>
                        </table>
                    </div>
                <?php endfor; ?>
            <?php endif; ?>

            <?php if ($active_tab == 'solutions'):
                $def_sol = [
                    1 => ['title' => 'Core Infrastructure<br/>Technologies', 'desc' => 'Compute, storage, networking and backup infrastructure...', 'btn' => 'Read more', 'img' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?q=80&w=1200&auto=format&fit=crop'],
                    2 => ['title' => 'Cybersecurity<br/>Solutions', 'desc' => 'End-to-end security architectures to protect your digital assets...', 'btn' => 'Discover', 'img' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=800&auto=format&fit=crop'],
                    3 => ['title' => 'Infrastructure<br/>Software', 'desc' => 'Software-defined approaches and robust integrations...', 'btn' => '', 'img' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop'],
                    4 => ['title' => 'Managed &amp;<br/>Cloud Services', 'desc' => 'Seamless operations across public, private, and hybrid...', 'btn' => '', 'img' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=800&auto=format&fit=crop'],
                    5 => ['title' => 'Endpoint Solutions', 'desc' => 'Empowering your workforce with secure and efficient...', 'btn' => 'View Offerings', 'img' => '']
                ];
                ?>
                <h2 style="color: #1d4e79;">💼 Grille des Solutions (Bento Grid)</h2>
                <?php for ($i = 1; $i <= 5; $i++):
                    $t = get_option('munisys_sol_' . $i . '_title', $def_sol[$i]['title']);
                    $d = get_option('munisys_sol_' . $i . '_desc', $def_sol[$i]['desc']);
                    $b = get_option('munisys_sol_' . $i . '_btn', $def_sol[$i]['btn']);
                    $l = get_option('munisys_sol_' . $i . '_link', $def_sol[$i]['link']);
                    $img = get_option('munisys_sol_' . $i . '_img', $def_sol[$i]['img']);
                    ?>
                    <div style="background: #f8f9fa; border: 1px solid #ddd; padding: 15px; margin-top: 20px;">
                        <h3 style="margin-top: 0; color: #922b55;">Carte Solution <?php echo $i; ?></h3>
                        <table class="form-table">
                            <tr>
                                <th>Titre (utiliser &lt;br/&gt; pour saut de ligne)</th>
                                <td><input type="text" name="munisys_sol_<?php echo $i; ?>_title"
                                        value="<?php echo esc_attr($t); ?>" style="width: 100%;" /></td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td><textarea name="munisys_sol_<?php echo $i; ?>_desc" rows="2"
                                        style="width: 100%;"><?php echo esc_textarea($d); ?></textarea></td>
                            </tr>
                            <tr>
                                <th>Texte du lien (ex: Read more)</th>
                                <td><input type="text" name="munisys_sol_<?php echo $i; ?>_btn" value="<?php echo esc_attr($b); ?>"
                                        style="width: 100%;" /></td>
                            </tr>
                            <tr>
                                <th>URL du lien</th>
                                <td><input type="text" name="munisys_sol_<?php echo $i; ?>_link" value="<?php echo esc_url($l); ?>"
                                        style="width: 100%;" /></td>
                            </tr>
                            <tr>
                                <th>URL de l'image de fond</th>
                                <td><input type="text" name="munisys_sol_<?php echo $i; ?>_img" value="<?php echo esc_url($img); ?>"
                                        style="width: 100%;" /></td>
                            </tr>
                        </table>
                    </div>
                <?php endfor; ?>
            <?php endif; ?>

            <?php if ($active_tab == 'services'):
                $o_t = get_option('munisys_off_title', 'Offerings');
                $o_d = get_option('munisys_off_desc', 'An end-to-end & coherent offer of solutions & services for secure digital infrastructures');

                $def_srv = [
                    1 => ['title' => 'IT integration', 'desc' => 'At every stage in the lifecycle of your IT Infrastructures, we ensure smooth, efficient, and robust technology alignment.'],
                    2 => ['title' => 'IT support', 'desc' => 'Reactive and proactive support services to guarantee maximum uptime, security, and continuous business operations.'],
                    3 => ['title' => 'Managed services', 'desc' => 'Offload your daily IT operations to our certified experts, allowing your team to focus strictly on innovation and growth.']
                ];

                $def_fig = [
                    1 => ['num' => '34', 'text' => 'years<br/>in business'],
                    2 => ['num' => '330', 'text' => 'employees'],
                    3 => ['num' => '700', 'text' => 'MMAD Revenue'],
                    4 => ['num' => '18', 'text' => 'Support<br/>centers']
                ];
                ?>
                <h2 style="color: #1d4e79;">📦 Offerings</h2>
                <table class="form-table">
                    <tr>
                        <th>Titre de la section</th>
                        <td><input type="text" name="munisys_off_title" value="<?php echo esc_attr($o_t); ?>"
                                style="width: 100%;" /></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><textarea name="munisys_off_desc" rows="2"
                                style="width: 100%;"><?php echo esc_textarea($o_d); ?></textarea></td>
                    </tr>
                </table>

                <h2 style="margin-top:40px; color: #1d4e79;">🛠️ Our Services</h2>
                <?php for ($i = 1; $i <= 3; $i++):
                    $t = get_option('munisys_srv_' . $i . '_title', $def_srv[$i]['title']);
                    $d = get_option('munisys_srv_' . $i . '_desc', $def_srv[$i]['desc']);
                    ?>
                    <div style="background: #f8f9fa; border: 1px solid #ddd; padding: 15px; margin-top: 20px;">
                        <h3 style="margin-top: 0; color: #922b55;">Service <?php echo $i; ?></h3>
                        <table class="form-table">
                            <tr>
                                <th>Titre</th>
                                <td><input type="text" name="munisys_srv_<?php echo $i; ?>_title"
                                        value="<?php echo esc_attr($t); ?>" style="width: 100%;" /></td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td><textarea name="munisys_srv_<?php echo $i; ?>_desc" rows="2"
                                        style="width: 100%;"><?php echo esc_textarea($d); ?></textarea></td>
                            </tr>
                            <tr>
                                <th>Lien URL (ex: /services)</th>
                                <td><input type="text" name="munisys_srv_<?php echo $i; ?>_link"
                                        value="<?php echo esc_url(get_option('munisys_srv_' . $i . '_link', $def_srv[$i]['link'])); ?>"
                                        style="width: 100%;" /></td>
                            </tr>
                        </table>
                    </div>
                <?php endfor; ?>

                <h2 style="margin-top:40px; color: #1d4e79;">📈 Some Figures (Compteurs animés)</h2>
                <?php for ($i = 1; $i <= 4; $i++):
                    $n = get_option('munisys_fig_' . $i . '_num', $def_fig[$i]['num']);
                    $t = get_option('munisys_fig_' . $i . '_text', $def_fig[$i]['text']);
                    ?>
                    <div style="background: #f8f9fa; border: 1px solid #ddd; padding: 15px; margin-top: 20px;">
                        <h3 style="margin-top: 0; color: #922b55;">Chiffre <?php echo $i; ?></h3>
                        <table class="form-table">
                            <tr>
                                <th>Nombre (ex: 34)</th>
                                <td><input type="text" name="munisys_fig_<?php echo $i; ?>_num" value="<?php echo esc_attr($n); ?>"
                                        style="width: 100%;" /></td>
                            </tr>
                            <tr>
                                <th>Texte (utiliser &lt;br/&gt; pour saut de ligne)</th>
                                <td><input type="text" name="munisys_fig_<?php echo $i; ?>_text" value="<?php echo esc_attr($t); ?>"
                                        style="width: 100%;" /></td>
                            </tr>
                        </table>
                    </div>
                <?php endfor; ?>
            <?php endif; ?>

            <?php if ($active_tab == 'insights'):
                $i_t = get_option('munisys_ins_title', 'Insights');

                $def_ins = [
                    1 => ['text' => 'A new visual identity for Munisys', 'img' => '/wp-content/uploads/datacenter.png'],
                    2 => ['text' => 'New headquarters', 'img' => '/wp-content/uploads/datacenter.png']
                ];

                $p_t = get_option('munisys_part_title', 'Technology partners');
                $p_d = get_option('munisys_part_desc', 'The IT infrastructures we design and deploy for our clients are at the heart of their business and determine their capacity to innovate and stand out. We build solid ties with our technology partners and acquire in-house expertise to ensure true resilience and agility.');

                $r_t = get_option('munisys_ref_title', 'They trust us');
                $r_d = get_option('munisys_ref_desc', 'As a trusted advisor, our contribution to the design, deployment and support of technological solutions is delivered in the best interests of our clients, prioritizing integrity and long-term partnerships.');
                ?>
                <h2 style="color: #1d4e79;">📰 Insights (Actualités)</h2>
                <table class="form-table">
                    <tr>
                        <th>Titre de la section</th>
                        <td><input type="text" name="munisys_ins_title" value="<?php echo esc_attr($i_t); ?>"
                                style="width: 100%;" /></td>
                    </tr>
                </table>

                <?php for ($i = 1; $i <= 2; $i++):
                    $t = get_option('munisys_ins_' . $i . '_text', $def_ins[$i]['text']);
                    $l = get_option('munisys_ins_' . $i . '_link', '#');
                    ?>
                    <div style="background: #f8f9fa; border: 1px solid #ddd; padding: 15px; margin-top: 20px;">
                        <h3 style="margin-top: 0; color: #922b55;">Article <?php echo $i; ?></h3>
                        <table class="form-table">
                            <tr>
                                <th>Texte</th>
                                <td><input type="text" name="munisys_ins_<?php echo $i; ?>_text" value="<?php echo esc_attr($t); ?>"
                                        style="width: 100%;" /></td>
                            </tr>
                            <tr>
                                <th>URL de l'article</th>
                                <td><input type="text" name="munisys_ins_<?php echo $i; ?>_link" value="<?php echo esc_url($l); ?>"
                                        style="width: 100%;" /></td>
                            </tr>
                            <tr>
                                <th>Image (URL)</th>
                                <td><input type="text" name="munisys_ins_<?php echo $i; ?>_img"
                                        value="<?php echo esc_url(get_option('munisys_ins_' . $i . '_img', $def_ins[$i]['img'])); ?>"
                                        style="width: 100%;" /></td>
                            </tr>
                        </table>
                    </div>
                <?php endfor; ?>

                <h2 style="margin-top:40px; color: #1d4e79;">🤝 Technology Partners</h2>
                <table class="form-table">
                    <tr>
                        <th>Titre de la section</th>
                        <td><input type="text" name="munisys_part_title" value="<?php echo esc_attr($p_t); ?>"
                                style="width: 100%;" /></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><textarea name="munisys_part_desc" rows="3"
                                style="width: 100%;"><?php echo esc_textarea($p_d); ?></textarea></td>
                    </tr>
                    <tr>
                        <th>Image de fond (URL)</th>
                        <td><input type="text" name="munisys_part_bg_img"
                                value="<?php echo esc_url(get_option('munisys_part_bg_img', 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2000&auto=format&fit=crop')); ?>"
                                style="width: 100%;" /></td>
                    </tr>
                </table>

                <h2 style="margin-top:40px; color: #1d4e79;">🏢 Customer References</h2>
                <table class="form-table">
                    <tr>
                        <th>Titre de la section</th>
                        <td><input type="text" name="munisys_ref_title" value="<?php echo esc_attr($r_t); ?>"
                                style="width: 100%;" /></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><textarea name="munisys_ref_desc" rows="3"
                                style="width: 100%;"><?php echo esc_textarea($r_d); ?></textarea></td>
                    </tr>
                </table>
            <?php endif; ?>

            <p class="submit" style="margin-top: 30px;">
                <input type="submit" name="submit" id="submit" class="button button-primary button-hero"
                    value="Enregistrer les modifications de cet onglet">
            </p>
        </form>
    </div>

    <?php
}

function munisys_solutions_page_settings_html()
{
    if (!current_user_can('edit_pages'))
        return;

    if (isset($_POST['submit_munisys_solutions_page'])) {
        $tab = isset($_POST['current_tab']) ? sanitize_text_field($_POST['current_tab']) : 'hero';

        if ($tab == 'hero') {
            update_option('munisys_solpage_hero_title', wp_kses_post(stripslashes($_POST['munisys_solpage_hero_title'])));
            update_option('munisys_solpage_hero_desc', sanitize_textarea_field(stripslashes($_POST['munisys_solpage_hero_desc'])));
        } else {
            // For categories
            $c_idx = (int) str_replace('cat', '', $tab);
            if ($c_idx >= 1 && $c_idx <= 5) {
                update_option('munisys_solpage_c' . $c_idx . '_title', sanitize_text_field(stripslashes($_POST['munisys_solpage_c' . $c_idx . '_title'])));
                update_option('munisys_solpage_c' . $c_idx . '_desc', sanitize_textarea_field(stripslashes($_POST['munisys_solpage_c' . $c_idx . '_desc'])));

                // Max 5 subcards per category
                for ($s = 1; $s <= 5; $s++) {
                    if (isset($_POST['munisys_solpage_c' . $c_idx . '_s' . $s . '_title'])) {
                        update_option('munisys_solpage_c' . $c_idx . '_s' . $s . '_title', sanitize_text_field(stripslashes($_POST['munisys_solpage_c' . $c_idx . '_s' . $s . '_title'])));
                        update_option('munisys_solpage_c' . $c_idx . '_s' . $s . '_desc', wp_kses_post(stripslashes($_POST['munisys_solpage_c' . $c_idx . '_s' . $s . '_desc'])));
                        update_option('munisys_solpage_c' . $c_idx . '_s' . $s . '_exp', sanitize_textarea_field(stripslashes($_POST['munisys_solpage_c' . $c_idx . '_s' . $s . '_exp'])));
                        update_option('munisys_solpage_c' . $c_idx . '_s' . $s . '_part', sanitize_text_field(stripslashes($_POST['munisys_solpage_c' . $c_idx . '_s' . $s . '_part'])));
                    }
                }
            }
        }
        echo '<div class="notice notice-success is-dismissible"><p>✅ Paramètres de la page Solutions mis à jour avec succès !</p></div>';
    }

    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'hero';

    // Include default array
    $def_cats = [
        1 => [
            'title' => 'Core Infrastructure Technologies',
            'desc' => 'Setting up, managing, and securing consistent compute, storage, networking and backup infrastructure across data centers and public clouds.',
            'count' => 5,
            'subcards' => [
                1 => [
                    'title' => 'Compute & Storage',
                    'desc' => 'We use proven methodologies to design and build modern compute and storage infrastructures, designed to work in edge, data center or hybrid cloud environments. Our cutting-edge solutions include powerful rack servers, composable bladed infrastructure, object, block and file storage arrays,… In order to offer consistency and meet specific needs for any workload, we select a combination of most secure mission-critical, cost sensitive or extensively scalable solutions.<br>High availability and resilience are primary criteria in the design of these infrastructure components. The use of right level of redundancy as well as the best fitting replication and automated failover technologies allow us to meet ever-increasing customer’s demands for business continuity and fast disaster recovery.',
                    'exp' => 'Standalone, Rack, and bladed Compute Servers\nHigh Performance Computing\nConverged IT/OT Systems\nEntry, Midrange and Mission Critical All-flash storage arrays\nFile, block, object storage solutions',
                    'part' => 'DELL, HPE, IBM'
                ],
                2 => [
                    'title' => 'Hyper-converged Infrastructures',
                    'desc' => 'Modern Hyper-converged Infrastructures (HCI) simplify datacenter operations, reduce dependency on proprietary hardware and provide scalability by tightly integrating individual compute servers, storage arrays and networks to yield a flexible, agile, and easily manageable software-defined datacenter.<br>Our systems architects and delivery experts harness the full power of HCI underlying virtualization technologies enabling our customers to benefit from an innovative as-a-service resources consumption model.<br>Thanks to the breadth of our partner’s HCI portfolios, we leverage many options to deploy the best fitting physical and software-defined resources, allowing our customers for choice based on their current infrastructure architecture and their in-house adopted operational processes.',
                    'exp' => 'HCI appliances\nHCI Ready-Nodes',
                    'part' => 'DELL, HPE, Nutanix, VMware'
                ],
                3 => [
                    'title' => 'Data Resilience',
                    'desc' => 'Ensuring business continuity through availability, integrity, and accessibility of data across IT infrastructure is vital for today’s organizations. No unexpected disruption, accidental deletion or unauthorized entities should be able to alter operational capabilities or business applications environments stability.<br>Our Data resilience solutions leverages a comprehensive set of in-house and cloud services as well as an automated approach that standardizes data protection through backups, encryption and object storage while providing centralized visibility and resources monitoring across all workloads and locations.',
                    'exp' => 'Data Protection Appliances and Software\nCloud Data Security\nAir gapped Immutable Storage',
                    'part' => 'DELL, HPE, Veeam, Veritas'
                ],
                4 => [
                    'title' => 'Networking',
                    'desc' => 'Conducting a successful digital transformation strategy requires seamless connectivity and access to data for both users and applications. Setting up secure and highly scalable wired, wireless, and SD-WAN networking platforms is vital to address business critical use cases.<br>Our partners’ networking portfolio spans from the edge layer to the cloud and creates a scalable, automated, service-centric and intelligent foundation for a secure connectivity of the unified infrastructure.<br>Our solutions help gaining visibility and control over the actual performance delivered by the network whilst simplifying operations and mitigating risks, all supported by AI driven management and policies enforcement capabilities.',
                    'exp' => 'Data Center Core LAN\nEdge Networks\nWireless LAN\nSD-WAN',
                    'part' => 'HPE Aruba, Cisco'
                ],
                5 => [
                    'title' => 'Cabling & Data Center Environment',
                    'desc' => 'At the most foundational level, an IT infrastructure starts with the environment that will host its most critical components and will allow them to operate in optimal and secure conditions.<br>Our cabling and data center environment team brings a proven expertise and a sound portfolio of solutions helping our customers to be totally confident that the physical IT technologies their operations rely upon will benefit from dependable, manageable and secure cabling, rack, power and monitoring systems.',
                    'exp' => 'Intelligent racks\nPower security and distribution\nHigh density cabling systems\nRFID Asset inventory and location\nData center environment monitoring',
                    'part' => 'Corning, Eaton, Legrand, R&M, RFCode, Schneider Electric'
                ],
            ]
        ],
        2 => [
            'title' => 'Cybersecurity Solutions',
            'desc' => 'Comprehensive protection for your enterprise. We secure your network, endpoints, and data against evolving digital threats.',
            'count' => 5,
            'subcards' => [
                1 => [
                    'title' => 'Endpoint Security',
                    'desc' => 'Cyber adversaries’ efforts to bypass corporate defenses start usually by targeting endpoints as they have always been company’s most vulnerable assets to attacks, regardless of where they are located inside or outside the corporate network, exploiting multiple factors such as human errors, device misconfigurations, software breaches, weak passwords, missing patches, and many more.<br>Munisys’ Endpoint Security solutions offer comprehensive monitoring and protection features to desktops, laptops, smartphones, printers, and other connected user devices. This includes detecting, preventing and responding to threats like infections by viruses, zero-day attacks, phishing and ransomware. Advanced capabilities are also leveraged to provide higher levels of protection like anomaly behavior analysis, automatic enforcement, and dynamic security controls.<br>We value also built-in hardware-based security and advanced self-healing capabilities as integrated features supported by specific endpoint devices being part of our EndPoint Solutions Business Unit’ catalog.',
                    'exp' => 'Antivirus & Ransomware protection\nEndpoint Detection & Response (EDR)\nUser and Entity Behavior Analytics\nWeb and Mail Threat protection',
                    'part' => 'HP, TrendMicro, Kaspersky, PaloAlto Networks'
                ],
                2 => [
                    'title' => 'Network Security',
                    'desc' => 'Digital assets’ availability and integrity rely greatly on seamless deployment of a highly secure network infrastructure with a scalable, automated, and manageable enforcement of a comprehensive set of rules and policies.<br>With the proliferation in the cloud and data center environments of distributed multi-sites resources, application virtualization and containerization, as well as extensive usage of encrypted traffic, IT teams are facing greater challenges to protect the network itself without compromising its performance and to secure application workloads without hampering efforts towards enablement of an outstanding user experience.<br>Combining multiple layers of defenses, our Networking Security solutions ensure an effective protection against unauthorized access with Network Access Control technologies, set up preemptive measures intended to thwart any attack coming from either the outside or circulating inside the network with Next Generation Firewalling and IDS/IPS systems, provide isolation of malware codes and objects with Sandbox technologies and responds to detected abnormal system behaviors with NDR solutions.',
                    'exp' => 'Next Generation Firewalling\nNetwork Access Control\nDDoS Protection\nNetwork Detection & Response (NDR)\nSandboxing',
                    'part' => 'F5, Fortinet, PaloAlto Networks, Forcepoint, Checkpoint, HPE Aruba, Gatewatcher'
                ],
                3 => [
                    'title' => 'Cloud & Application Security',
                    'desc' => 'Over the past few years, the world witnessed an unprecedented shift towards remote working and cloud services adoption. Strategies has been put in place to rapidly migrate applications, APIs services and related data to collaborative cloud environments to take benefit from an enhanced agility and new innovation capabilities. However, this also creates a paradigm change in digital operations and assets’ security.<br>Resolved to embrace the change, Munisys has expertly enriched his portfolio of security solutions to help extend secure access and high-performance connectivity to users regardless of their geographic locations with SASE technologies and to enable moving applications and API endpoints safely outside the organization’s network perimeter with advanced solutions designed to integrate effective security controls throughout their development lifecycle.',
                    'exp' => 'Cloud-native applications & API security\nApplication vulnerability management (SAST/DAST/IAST)\nSecure Access Service Edge (SASE) solutions\nSecure File Transfer\nDNS Security',
                    'part' => 'Axway, Broadcom, OpenText, F5, PaloAlto Networks'
                ],
                4 => [
                    'title' => 'Identity and Access Management',
                    'desc' => 'Securing a perimeter around the datacenter network is no longer enough to ensure safe access to data, applications, and business-critical services as they are increasingly spread out across multi-sites or even multi-clouds environments. Today, Organizations must deploy an Identity-centric strategy to protect every user-resource connection, mitigate risk and provide access governance, compliance, and real-time threat detection.<br>With our Identity and Access Management (IAM) solutions and services, we help you orchestrate policies across on-premises, remote, and cloud environments to control identities and give privileged access and passwordless authentication to your workforce as well as to your customers.<br>Further, Munisys helps you find a right balance between accessibility and security controls so as not to impede your ability to deliver agile, scalable, and efficient business services.',
                    'exp' => 'Multi Factor Authentication (MFA)\nLDAP Protection\nFederated Identity Management\nCloud Access Security Broker (CASB)\nData privilege Management',
                    'part' => 'Fortinet, Varonis, Opentext, F5'
                ],
                5 => [
                    'title' => 'Visibility & Compliance',
                    'desc' => 'A holistic approach to Cybersecurity encompasses rolling out security enforcement systems and controls, gaining deep visibility into valued assets and verifying continuously those assets’ compliance with business policies and regulatory requirements.<br>Our solutions drive greater visibility and compliance capabilities by providing scalable event monitoring, automation, SOC analytics, audited remote management, and more.',
                    'exp' => 'Security Information & Event Management (SIEM)\nSecurity Orchestration & Automated Response (SOAR)\nThreat Intelligence Platforms\nPrivileged Access Management\nVulnerability & Patch Management\nSecurity Policy Management',
                    'part' => 'Tenable, Wallix, OpenText, Google-Mandiant, Rapid7'
                ],
            ]
        ],
        3 => [
            'title' => 'Infrastructure Software',
            'desc' => 'Optimize your operations with enterprise-grade software solutions for monitoring, management, and automation.',
            'count' => 4,
            'subcards' => [
                1 => [
                    'title' => 'Service & Asset Management',
                    'desc' => 'Digital transformation has significantly elevated the customer experience across vertical markets, prompting every organization to strive to adopt internally similar levels of service delivery for an engaging, consumer-like, and self-service end user experience.<br>Going beyond IT-centric service delivery enablement, Munisys’ ITIL-aligned and cloud-native solutions encompass a collection of policies and processes for the management and support of business services like human resources, legal, assets, facilities, marketing, and finance.<br>Strong with its proven experience on implementing service and asset management processes, native mobile applications, machine learning and AI-powered chatbots, social collaboration, advanced analytics, and orchestration, Munisys is your privileged partner when laying down the foundation for your enterprise’s efficiency improvement strategy.',
                    'exp' => 'Enterprise Service Management\nIT Asset Management\nCMDB and Dynamic Discovery\nSoftware License Management',
                    'part' => 'OpenText'
                ],
                2 => [
                    'title' => 'Observability, Automation & Orchestration',
                    'desc' => 'Applying advanced automation and predictive analytics technologies to run, monitor and protect enterprise assets and processes has deeply transformed how IT operations are performed and managed.<br>Huge volumes of data are usually generated by continuous discovery and at scale monitoring of applications, services, networks and user experience across cloud and on-premises environments. By implementing automated and intelligent correlation solutions to analyse those big data sets, organizations can easily derive fast and useful insights as well as accurate and timely knowledge of issues impacting their business services’ performance and health levels.<br>Automation may also be enhanced with powerful orchestration engines helping IT operators to efficiently run, schedule, and actively monitor processes and workflows at enterprise scale.<br>Munisys expertly provides its customers with end-to-end observability, automation, orchestration, and proactive remediation solutions to increase process efficiency, alleviate pressure on IT teams while eliminating manual and repetitive efforts, lower IT operation costs, and minimize business services disruption.',
                    'exp' => 'Datacenter Infrastructure Monitoring\nApplication Performance Monitoring\nEnterprise-wide Automation Platforms\nRobotic Process Automation',
                    'part' => 'OpenText, Redhat, Broadcom'
                ],
                3 => [
                    'title' => 'Data Management & Analytics',
                    'desc' => 'Turning Data into meaningful and actionable insights is undoubtedly one of the key aspects to make businesses capable of staying competitive and ahead of market expectations. This is even becoming table stakes in some verticals. Indeed, no financial institutions can afford not relying on critical data applications like Frauds detection and Risks simulation. No Telecom provider can ignore the crucial importance of Network analytics and Customer behavior analytics.<br>Munisys is partnering with big players in the data platforms ecosystem to provide state-of-the-art distributed data management components to source, structure, protect and analyze data. Whether run on hybrid or mutlicloud environments, our solutions deliver quality enterprise-wide intelligence in a fast and cost-effective way.',
                    'exp' => 'Hybrid & Cloud Data platforms\nArtificial Intelligence Data Analytics\nEnterprise Data Archiving',
                    'part' => 'OpenText, Cohesity'
                ],
                4 => [
                    'title' => 'Application Modernization',
                    'desc' => 'An important feature of a sustainable digital transformation strategy is the modernization of incumbent applications to take advantage of newer virtualized infrastructure and cloud technology advances for better resources optimization, greater developments speed, frequent deployments, and improved resiliency. This approach should, on the other hand, protect older investments, value existing functionalities and minimize service disruptions.<br>Thanks to its ability to conduct a thorough application inventory and assessment, Munisys delivers innovative services set up for helping organizations select a right migration pattern towards their newly adopted cloud infrastructures. Those patterns, such as Rehosting, Replatforming and Refactoring imply different effort and complexity levels and are selected based on containerization suitability, business risks, and dependencies across new or legacy applications.<br>Munisys is definitely the right partner for businesses intending to execute an effective application modernization plan as this entails a proven expertise on numerous intersecting technologies such as Cloud application platforms, API gateways, Containers, Microservices, Security, Automation, and Orchestration.',
                    'exp' => 'Application Modernization Assessment\nKubernetes-based Container Orchestration platforms\nApplication Rehosting / Replatforming / Refactoring\nAPI Management\nZero Trust Application Security',
                    'part' => 'Redhat, GitLab, Opentext'
                ],
            ]
        ],
        4 => [
            'title' => 'Managed & Cloud Services',
            'desc' => 'Seamless cloud integration and managed services to accelerate your digital transformation journey.',
            'count' => 5,
            'subcards' => [
                1 => [
                    'title' => 'Hosting and Cloud Infrastructure Services',
                    'desc' => 'Building traditional on-premises IT infrastructure enhances agility and competitiveness but comes with significant challenges like hefty upfront capital expenditures, limited scalability, and unremitting upgrade and maintenance tasks. Having difficulties managing multi-site or multi-technology architectures, leads often to inefficiencies and increased operational costs.<br>Munisys hosting and cloud infrastructure offering addresses these challenges by providing hardware and software reliability, disaster recovery, increased scalability, cost optimization and enhanced resource management while giving access to an elite infrastructure experts team without the need to build it in-house.<br>Based on automated provisioning, unified orchestration, and API-driven infrastructure management, our solutions are delivered as a comprehensive and modular set of services for planning, preparing, and operating traditional systems and modern cloud-native environments, encompassing colocation, dedicated platforms and private, hybrid and public cloud infrastructure.<br>Our services are delivered within certified, carrier-neutral and sovereign data centers hosting enterprise-grade infrastructure. Designed and operated by MDC, a sibling company, our data centers meet strict jurisdictional requirements needed by highly regulated sectors like finance, healthcare and public services. Organizations benefit from a securely connected or air-gapped architectures, hardened access control, resilient connectivity and tailored service level agreements (SLAs) for performance, control, reporting, and availability.',
                    'exp' => 'Data Center Colocation\nManaged dedicated Private infrastructure\nManaged private & hybrid cloud Infrastructure\nInfrastructure as a Service (Virtual machines, virtual desktops, Virtual Data Centers)\nCloud Object Storage (S3)',
                    'part' => 'MDC, OVH, OCI, HPE, Redhat, VMware, Nutanix'
                ],
                2 => [
                    'title' => 'Application and Platforms Services',
                    'desc' => 'Today, continuous innovation and process modernization relies on platforms that provide an automated, API-driven, and scalable environments to build, test and deploy cloud-ready applications, cloud-native workloads, serverless, or AI/ML capabilities.<br>At Munisys, we design, implement and manage leading kubernetes platforms and containerized environments. The tools and capabilities delivered as a service, allow organizations to develop and deploy applications without the need to manage the underlying infrastructure.<br>Our offer encompasses innovative SaaS and PaaS solutions like IT service management, controlled automation, API Gateways and AI/MLOps platforms. We provide the computational resources, specialized tools and highly skilled experts for a complete visibility, dynamic scalability, and secure operations across cloud infrastructure.',
                    'exp' => 'Managed Kubernetes & Container Platforms\nPlatforms as a Service (API, AI, DEVSECOPS, …)\nManaged Application & SaaS Hosting\nIT Service Management as a Service\nSecrets Vault (VaaS)',
                    'part' => 'Redhat, VMware, Nutanix, Gitlab, HashiCorp'
                ],
                3 => [
                    'title' => 'Business Resilience & Data Protection',
                    'desc' => 'As cyberattacks on digital infrastructure and sensitive data flows continues its upward trajectory, ensuring business resilience and data protection play a pivotal role in shaping the future of cloud transition and operation strategies.<br>Munisys protects your on-premises and cloud infrastructure by setting up a distributed model, based on active-passive or active-active architectures, that inherently reduce risks by eliminating a single point of failure and improving responsiveness. Site recovery environments are set up in our data centers with adequate RPO and RTO parameters, secure networks, and encrypted datastores. Leveraging advanced observability solutions, we analyze usage trends, detect anomalies, set proactive alerts and accelerate troubleshooting and recovery operations.<br>Munisys protects also your production data by providing an enterprise-class cloud backup services for recovering critical workloads and protecting SaaS, cloud-native, and on-premises data sources. To help organizations minimize data loss or ransomware risks, our services enable swift recovery through advanced safeguards, such as secure access controls, regular backup schedules, immutable backups, air-gapped storage, and data encryption.<br>Combining core Clean Room capabilities from our data protection solutions partners with cyberattack response and managed services expertise from Munisys security teams, we are able to provide you with a clean room solution intending to establish on-demand, air-gapped response environment, along with integrated forensic analysis and recovery tools and services.',
                    'exp' => 'Backup as a Service\nDisaster Recovery as a Service (DRaaS)\nClean room service',
                    'part' => 'Cohesity, Veeam'
                ],
                4 => [
                    'title' => 'Managed Security Services',
                    'desc' => 'As cloud adoption rises, many organizations struggle to transform security across infrastructure, cloud platforms, and applications from a compliance requirement into a strategic advantage.<br>Munisys offers cloud-based, integrated, and scalable solutions that provide advanced defensive security capabilities without the need for on-premises hardware, software or expert knowledge, ensuring businesses are consistently well-prepared for any unforeseen disruptions through comprehensive and continuous protection of their digital assets.<br>Aiming to safeguard your entire data and application lifecycle, our proactive and holistic approach-based security services, combined with proven methodologies and expert-led capabilities, enable us to monitor your infrastructure 24/7, to focus on preventing incidents before they happen, and to detect and respond as quickly and effectively as possible.',
                    'exp' => 'Access Control & Privileged Access management\nPatch and Vulnerability Management\nEndpoint Detection & Response\nManaged Network Security\nManaged SOC, XDR (eXtended Detection/Response)\nAppSec aaS (Application Security Testing, cloud-native applications security, Managed DevSecOps Tooling)',
                    'part' => 'Palo Alto, Fortinet, Redhat, OpenText'
                ],
                5 => [
                    'title' => 'Advisory, Professional and Management Services',
                    'desc' => 'Our end-to-end services are designed to support you at every stage of your digital transformation journey, securing transitions and aligning with specific business goals:<br>- Assessment & Advisory services to define tailored dedicated infrastructure and cloud architecture strategies,<br>- Deployment & Migration services spanning activities from planning, data acquisition/transfer, configuration, automation to platform and application integration,<br>- Optimization & Alignment services enhancing performance, efficiency, and scalability of the running environments,<br>- Management services ensuring high availability, cost reduction, security, and observability.<br>You may entrust your selected services to our experts in full-managed mode or choose between a self-managed mode through cloud-based interface, co-managed mode or even a management mode adapted to your distinctive needs and specific context.<br>These services are delivered mainly by our own teams of cloud architects, DevOps engineers, security specialists, infrastructure experts and certified project managers.',
                    'exp' => 'Cloud & IT assessment and advisory services\nProfessional Services (Deployment, Configuration, Automation, Support)\nCloud Migration\nOperations and Infrastructure Optimization & Alignment\nComprehensive Management Services',
                    'part' => ''
                ],
            ]
        ],
        5 => [
            'title' => 'Endpoint Solutions',
            'desc' => 'Empower your workforce with secure, high-performance computing devices and intelligent endpoint management solutions.',
            'count' => 3,
            'subcards' => [
                1 => [
                    'title' => 'Digital Workplace',
                    'desc' => 'The digital workplace holds a central role in the ability of users to fully benefit from and embrace modern work and collaboration processes. The place is not what matters most, but the combination of technologies that will allow to efficiently and securely work, share and communicate, with the additional benefit of an improved work/life balance.<br>The unique combination of expertise that Munisys brings allows us to design, deploy and support workplace technologies while also answering your needs related to security & data protection, virtualization & automation, monitoring & service management, ubiquitous assistance & support.',
                    'exp' => 'Desktop and laptop computers selected based on performance needs, security requirements and environment constraints\nCollaboration solutions\nVDI solutions\nEndpoint protection\nComplete services portfolio tailored for the endpoint',
                    'part' => 'Dell, HP Inc., Jabra, Lenovo, Logitech, Poly, Samsung'
                ],
                2 => [
                    'title' => 'Printing Solutions',
                    'desc' => 'Printing is an essential part of business processes whenever paper is the most appropriate or the only possible way of sharing information.<br>Based on market leading solutions, we assist you in choosing the right technologies and device range in order to make sure producing physical documents gives an edge to your teams and positively contributes to your customers’ experience.<br>We also offer country wide Managed Printing Services, that will provide you with a flexible, scalable, no interruption and cost optimized printing solution.',
                    'exp' => 'Enterprise printing solutions\nManaged Print Services',
                    'part' => 'Canon, Epson, HP Inc'
                ],
                3 => [
                    'title' => 'End Customer Facing Devices',
                    'desc' => 'Besides computing, printing and workplace related technologies, the Endpoint encompasses a large range of devices and software aiming to offer the end customer the most suitable interface to access the many services that digital has made possible.<br>A very efficient way to engage with your customers when offering digitized and digital services is to deploy intelligent kiosks that will promote self-service. We give our customers the possibility to freely design such kiosks and to very closely match their requirements in terms of physical shape and size and to include any functionality to be offered to the end user, ranging from printer to document scanner, from ID card Reader to biometric devices and payment terminals. A well-designed kiosk solution can significantly relief the front office personnel and let them focus on value-added customer face to face interactions.<br>In the process of moving growingly toward digital services, some specific categories of devices are needed, among which digital ID acquisition or digital signature capture. In order to assist our clients in implementing these functions in an efficient and reliable way and to offer all the required integration possibilities, we have selected specialized devices from leading vendors. They offer a complete solution, especially in the banking sector, but also in general anywhere a customer ID and/or signature are necessary within a workflow.',
                    'exp' => 'Intelligent kiosks\nScanners / readers\nID card / biometry readers\nDigital signature pads',
                    'part' => 'Faytech, Secugen, Signotec'
                ],
            ]
        ],
    ];
    ?>
    <div class="wrap">
        <h1>💼 Paramètres de la Page Solutions Complète</h1>
        <p>Gérez ici le contenu de la grande page Solutions (En-tête, Catégories, et Sous-cartes).</p>

        <h2 class="nav-tab-wrapper">
            <a href="?page=munisys-solutions-page&tab=hero"
                class="nav-tab <?php echo $active_tab == 'hero' ? 'nav-tab-active' : ''; ?>">🚀 En-tête</a>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <a href="?page=munisys-solutions-page&tab=cat<?php echo $i; ?>"
                    class="nav-tab <?php echo $active_tab == 'cat' . $i ? 'nav-tab-active' : ''; ?>">Cat <?php echo $i; ?>
                    (<?php echo esc_html($def_cats[$i]['title']); ?>)</a>
            <?php endfor; ?>
        </h2>

        <form method="POST"
            style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 900px; margin-top: 20px;">
            <input type="hidden" name="submit_munisys_solutions_page" value="1">
            <input type="hidden" name="current_tab" value="<?php echo esc_attr($active_tab); ?>">

            <?php if ($active_tab == 'hero'):
                $h_t = get_option('munisys_solpage_hero_title', 'Our <strong>Solutions</strong>');
                $h_d = get_option('munisys_solpage_hero_desc', 'Discover our comprehensive suite of digital infrastructure technologies tailored to empower your business operations and drive innovation.');
                ?>
                <h2 style="color: #1d4e79;">🚀 En-tête (Hero)</h2>
                <table class="form-table">
                    <tr>
                        <th>Titre (utiliser &lt;strong&gt;)</th>
                        <td><input type="text" name="munisys_solpage_hero_title" value="<?php echo esc_attr($h_t); ?>"
                                style="width: 100%;" /></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><textarea name="munisys_solpage_hero_desc" rows="3"
                                style="width: 100%;"><?php echo esc_textarea($h_d); ?></textarea></td>
                    </tr>
                </table>

            <?php else:
                $c_idx = (int) str_replace('cat', '', $active_tab);
                if ($c_idx >= 1 && $c_idx <= 5):
                    $c_t = get_option('munisys_solpage_c' . $c_idx . '_title', $def_cats[$c_idx]['title']);
                    $c_d = get_option('munisys_solpage_c' . $c_idx . '_desc', $def_cats[$c_idx]['desc']);
                    ?>
                    <h2 style="color: #1d4e79;">📂 Catégorie <?php echo $c_idx; ?> : <?php echo esc_html($c_t); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th>Titre de la catégorie</th>
                            <td><input type="text" name="munisys_solpage_c<?php echo $c_idx; ?>_title"
                                    value="<?php echo esc_attr($c_t); ?>" style="width: 100%;" /></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><textarea name="munisys_solpage_c<?php echo $c_idx; ?>_desc" rows="2"
                                    style="width: 100%;"><?php echo esc_textarea($c_d); ?></textarea></td>
                        </tr>
                    </table>

                    <h2 style="margin-top:40px; color: #1d4e79;">📄 Sous-cartes (Solutions)</h2>
                    <p>Laissez le titre vide pour ne pas afficher une carte.</p>
                    <?php for ($s = 1; $s <= $def_cats[$c_idx]['count']; $s++):
                        $def_sub = isset($def_cats[$c_idx]['subcards'][$s]) ? $def_cats[$c_idx]['subcards'][$s] : ['title' => '', 'desc' => '', 'exp' => '', 'part' => ''];
                        $s_t = get_option('munisys_solpage_c' . $c_idx . '_s' . $s . '_title', $def_sub['title']);
                        $s_d = get_option('munisys_solpage_c' . $c_idx . '_s' . $s . '_desc', $def_sub['desc']);
                        $s_e = get_option('munisys_solpage_c' . $c_idx . '_s' . $s . '_exp', $def_sub['exp']);
                        $s_p = get_option('munisys_solpage_c' . $c_idx . '_s' . $s . '_part', $def_sub['part']);
                        ?>
                        <div style="background: #f8f9fa; border: 1px solid #ddd; padding: 15px; margin-top: 20px;">
                            <h3 style="margin-top: 0; color: #922b55;">Sous-carte <?php echo $s; ?> : <?php echo esc_html($s_t); ?></h3>
                            <table class="form-table">
                                <tr>
                                    <th>Titre de la solution</th>
                                    <td><input type="text" name="munisys_solpage_c<?php echo $c_idx; ?>_s<?php echo $s; ?>_title"
                                            value="<?php echo esc_attr($s_t); ?>" style="width: 100%;" /></td>
                                </tr>
                                <tr>
                                    <th>Description (Overview)</th>
                                    <td><textarea name="munisys_solpage_c<?php echo $c_idx; ?>_s<?php echo $s; ?>_desc" rows="6"
                                            style="width: 100%;"><?php echo esc_textarea(str_replace('<br>', "\n", $s_d)); ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Key Expertises<br><small>(1 par ligne, sans tiret)</small></th>
                                    <td><textarea name="munisys_solpage_c<?php echo $c_idx; ?>_s<?php echo $s; ?>_exp" rows="5"
                                            style="width: 100%;"><?php echo esc_textarea(str_replace('\n', "\n", $s_e)); ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Technology Partners<br><small>(Séparés par des virgules)</small></th>
                                    <td><input type="text" name="munisys_solpage_c<?php echo $c_idx; ?>_s<?php echo $s; ?>_part"
                                            value="<?php echo esc_attr($s_p); ?>" style="width: 100%;" /></td>
                                </tr>
                            </table>
                        </div>
                    <?php endfor; ?>
                <?php endif; endif; ?>

            <p class="submit" style="margin-top: 30px;">
                <input type="submit" class="button button-primary button-hero"
                    value="Enregistrer les modifications de cet onglet">
            </p>
        </form>
    </div>
    <?php
}

function munisys_contact_settings_html()
{
    if (!current_user_can('edit_pages'))
        return;

    if (isset($_POST['submit_munisys_contact'])) {
        update_option('munisys_contact_email', sanitize_email($_POST['munisys_contact_email']));
        update_option('munisys_recaptcha_site_key', sanitize_text_field($_POST['munisys_recaptcha_site_key']));
        update_option('munisys_recaptcha_secret_key', sanitize_text_field($_POST['munisys_recaptcha_secret_key']));

        update_option('munisys_contact_title', sanitize_text_field($_POST['munisys_contact_title']));
        update_option('munisys_contact_desc', sanitize_textarea_field($_POST['munisys_contact_desc']));

        update_option('munisys_contact_casa_title', sanitize_text_field($_POST['munisys_contact_casa_title']));
        update_option('munisys_contact_casa_desc', wp_kses_post(stripslashes($_POST['munisys_contact_casa_desc'])));
        update_option('munisys_contact_casa_phone', sanitize_text_field($_POST['munisys_contact_casa_phone']));

        update_option('munisys_contact_rabat_title', sanitize_text_field($_POST['munisys_contact_rabat_title']));
        update_option('munisys_contact_rabat_desc', wp_kses_post(stripslashes($_POST['munisys_contact_rabat_desc'])));
        update_option('munisys_contact_rabat_phone', sanitize_text_field($_POST['munisys_contact_rabat_phone']));

        echo '<div class="notice notice-success is-dismissible"><p>✅ Paramètres de la page Contact mis à jour avec succès !</p></div>';
    }

    $c_title = get_option('munisys_contact_title', 'Let\'s Connect');
    $c_desc = get_option('munisys_contact_desc', 'Reach out to our experts. We are ready to help you orchestrate your next digital transformation project.');

    $casa_t = get_option('munisys_contact_casa_title', 'Headquarter');
    $casa_d = get_option('munisys_contact_casa_desc', '11-12 Bouskoura Park Ville Verte Bouskoura<br>27182 Casablanca - Maroc');
    $casa_p = get_option('munisys_contact_casa_phone', '+212 5 22 00 00 00');

    $rabat_t = get_option('munisys_contact_rabat_title', 'Rabat Office');
    $rabat_d = get_option('munisys_contact_rabat_desc', '288, angle rues Khatri Joumani et Mohamed Ben Yazid OLM Souissi<br>10170 Rabat - Morocco');
    $rabat_p = get_option('munisys_contact_rabat_phone', '+212 5 37 00 00 00');

    $contact_email = get_option('munisys_contact_email', get_option('admin_email'));
    $recaptcha_site = get_option('munisys_recaptcha_site_key', '');
    $recaptcha_secret = get_option('munisys_recaptcha_secret_key', '');
    ?>
    <div class="wrap">
        <h1>📞 Paramètres de la Page Contact</h1>
        <p>Gérez ici les textes et coordonnées affichés sur la page Contact.</p>

        <form method="POST"
            style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 900px; margin-top: 20px;">
            <input type="hidden" name="submit_munisys_contact" value="1">

            <h2 style="color: #1d4e79; border-bottom: 2px solid #eee; padding-bottom: 10px;">✉️ Configuration E-mail &
                Sécurité</h2>
            <table class="form-table">
                <tr>
                    <th>Adresse E-mail de Réception<br><small style="font-weight:normal;">Où envoyer les messages du
                            formulaire ?</small></th>
                    <td><input type="email" name="munisys_contact_email" value="<?php echo esc_attr($contact_email); ?>"
                            style="width: 100%; max-width: 400px;" /></td>
                </tr>
                <tr>
                    <th>Clé de site reCAPTCHA v3 (Site Key)<br><small style="font-weight:normal;">Laissez vide pour
                            désactiver</small></th>
                    <td><input type="text" name="munisys_recaptcha_site_key"
                            value="<?php echo esc_attr($recaptcha_site); ?>" style="width: 100%; max-width: 400px;" /></td>
                </tr>
                <tr>
                    <th>Clé secrète reCAPTCHA v3 (Secret Key)</th>
                    <td><input type="text" name="munisys_recaptcha_secret_key"
                            value="<?php echo esc_attr($recaptcha_secret); ?>" style="width: 100%; max-width: 400px;" />
                    </td>
                </tr>
            </table>

            <h2 style="color: #1d4e79; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-top: 40px;">🚀 En-tête
                (Hero)</h2>
            <table class="form-table">
                <tr>
                    <th>Titre</th>
                    <td><input type="text" name="munisys_contact_title" value="<?php echo esc_attr($c_title); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><textarea name="munisys_contact_desc" rows="3"
                            style="width: 100%;"><?php echo esc_textarea($c_desc); ?></textarea></td>
                </tr>
            </table>

            <h2 style="margin-top:40px; color: #1d4e79;">🏢 Bureau de Casablanca</h2>
            <table class="form-table">
                <tr>
                    <th>Titre</th>
                    <td><input type="text" name="munisys_contact_casa_title" value="<?php echo esc_attr($casa_t); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Adresse (utiliser &lt;br&gt; pour les retours à la ligne)</th>
                    <td><textarea name="munisys_contact_casa_desc" rows="2"
                            style="width: 100%;"><?php echo esc_textarea($casa_d); ?></textarea></td>
                </tr>
                <tr>
                    <th>Téléphone</th>
                    <td><input type="text" name="munisys_contact_casa_phone" value="<?php echo esc_attr($casa_p); ?>"
                            style="width: 100%;" /></td>
                </tr>
            </table>

            <h2 style="margin-top:40px; color: #1d4e79;">🏢 Bureau de Rabat</h2>
            <table class="form-table">
                <tr>
                    <th>Titre</th>
                    <td><input type="text" name="munisys_contact_rabat_title" value="<?php echo esc_attr($rabat_t); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Adresse (utiliser &lt;br&gt; pour les retours à la ligne)</th>
                    <td><textarea name="munisys_contact_rabat_desc" rows="2"
                            style="width: 100%;"><?php echo esc_textarea($rabat_d); ?></textarea></td>
                </tr>
                <tr>
                    <th>Téléphone</th>
                    <td><input type="text" name="munisys_contact_rabat_phone" value="<?php echo esc_attr($rabat_p); ?>"
                            style="width: 100%;" /></td>
                </tr>
            </table>

            <p class="submit" style="margin-top: 30px;">
                <input type="submit" class="button button-primary button-hero" value="Enregistrer les modifications">
            </p>
        </form>
    </div>
    <?php
}

function munisys_careers_settings_html()
{
    if (!current_user_can('edit_pages'))
        return;

    if (isset($_POST['submit_munisys_careers'])) {
        update_option('munisys_careers_hero_title', wp_kses_post(stripslashes($_POST['munisys_careers_hero_title'])));
        update_option('munisys_careers_hero_desc', sanitize_textarea_field($_POST['munisys_careers_hero_desc']));
        update_option('munisys_careers_hero_img', esc_url_raw($_POST['munisys_careers_hero_img']));

        update_option('munisys_careers_why_title', sanitize_text_field($_POST['munisys_careers_why_title']));
        update_option('munisys_careers_why_desc', sanitize_textarea_field($_POST['munisys_careers_why_desc']));

        for ($i = 1; $i <= 3; $i++) {
            update_option('munisys_careers_why_' . $i . '_title', sanitize_text_field($_POST['munisys_careers_why_' . $i . '_title']));
            update_option('munisys_careers_why_' . $i . '_desc', sanitize_textarea_field($_POST['munisys_careers_why_' . $i . '_desc']));
        }

        echo '<div class="notice notice-success is-dismissible"><p>✅ Paramètres de la page Carrières mis à jour avec succès !</p></div>';
    }

    $h_t = get_option('munisys_careers_hero_title', 'Shape<br>the future');
    $h_d = get_option('munisys_careers_hero_desc', 'Join Munisys and work with cutting-edge infrastructure technologies to accelerate digital transformation for major enterprises.');

    $w_t = get_option('munisys_careers_why_title', 'Why Munisys?');
    $w_d = get_option('munisys_careers_why_desc', 'We provide an environment where excellence meets innovation. Grow your career with industry leaders.');

    $def_why = [
        1 => ['t' => 'Innovation', 'd' => 'Work with cutting-edge technologies and shape the digital transformation of major enterprises in Morocco and beyond.'],
        2 => ['t' => 'Excellence', 'd' => 'Join a team of highly certified experts and evolve in a culture of continuous learning and high performance.'],
        3 => ['t' => 'Impact', 'd' => 'Your work will directly secure and optimize the core infrastructure of the largest institutions in the region.']
    ];
    ?>
    <div class="wrap">
        <h1>💼 Paramètres de la Page Carrières</h1>
        <p>Gérez ici les textes affichés sur la page Carrières (hors liste des offres d'emploi).</p>

        <form method="POST"
            style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 900px; margin-top: 20px;">
            <input type="hidden" name="submit_munisys_careers" value="1">

            <h2 style="color: #1d4e79;">🚀 En-tête (Hero)</h2>
            <table class="form-table">
                <tr>
                    <th>Titre (utiliser &lt;br&gt; pour les sauts de ligne)</th>
                    <td><textarea name="munisys_careers_hero_title" rows="2"
                            style="width: 100%;"><?php echo esc_textarea($h_t); ?></textarea></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><textarea name="munisys_careers_hero_desc" rows="3"
                            style="width: 100%;"><?php echo esc_textarea($h_d); ?></textarea></td>
                </tr>
                <tr>
                    <th>Image de fond (URL)</th>
                    <td><input type="text" name="munisys_careers_hero_img"
                            value="<?php echo esc_url(get_option('munisys_careers_hero_img', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2000&auto=format&fit=crop')); ?>"
                            style="width: 100%;" /></td>
                </tr>
            </table>

            <h2 style="margin-top:40px; color: #1d4e79;">❓ Section "Pourquoi Munisys ?"</h2>
            <table class="form-table">
                <tr>
                    <th>Titre de la section</th>
                    <td><input type="text" name="munisys_careers_why_title" value="<?php echo esc_attr($w_t); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Description de la section</th>
                    <td><textarea name="munisys_careers_why_desc" rows="2"
                            style="width: 100%;"><?php echo esc_textarea($w_d); ?></textarea></td>
                </tr>
            </table>

            <?php for ($i = 1; $i <= 3; $i++):
                $c_t = get_option('munisys_careers_why_' . $i . '_title', $def_why[$i]['t']);
                $c_d = get_option('munisys_careers_why_' . $i . '_desc', $def_why[$i]['d']);
                ?>
                <div style="background: #f8f9fa; border: 1px solid #ddd; padding: 15px; margin-top: 20px;">
                    <h3 style="margin-top: 0; color: #922b55;">Raison <?php echo $i; ?></h3>
                    <table class="form-table">
                        <tr>
                            <th>Titre</th>
                            <td><input type="text" name="munisys_careers_why_<?php echo $i; ?>_title"
                                    value="<?php echo esc_attr($c_t); ?>" style="width: 100%;" /></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><textarea name="munisys_careers_why_<?php echo $i; ?>_desc" rows="2"
                                    style="width: 100%;"><?php echo esc_textarea($c_d); ?></textarea></td>
                        </tr>
                    </table>
                </div>
            <?php endfor; ?>

            <p class="submit" style="margin-top: 30px;">
                <input type="submit" class="button button-primary button-hero" value="Enregistrer les modifications">
            </p>
        </form>
    </div>
    <?php
}

function munisys_about_settings_html()
{
    if (!current_user_can('edit_pages'))
        return;

    if (isset($_POST['submit_munisys_about'])) {
        update_option('munisys_about_hero_title', sanitize_text_field($_POST['munisys_about_hero_title']));
        update_option('munisys_about_hero_desc', sanitize_textarea_field($_POST['munisys_about_hero_desc']));

        update_option('munisys_about_history_title', sanitize_text_field($_POST['munisys_about_history_title']));
        update_option('munisys_about_history_desc1', sanitize_textarea_field($_POST['munisys_about_history_desc1']));
        update_option('munisys_about_history_desc2', sanitize_textarea_field($_POST['munisys_about_history_desc2']));
        update_option('munisys_about_history_quote', sanitize_textarea_field($_POST['munisys_about_history_quote']));

        update_option('munisys_about_vision_title', sanitize_text_field($_POST['munisys_about_vision_title']));
        update_option('munisys_about_vision_desc1', sanitize_textarea_field($_POST['munisys_about_vision_desc1']));
        update_option('munisys_about_vision_desc2', sanitize_textarea_field($_POST['munisys_about_vision_desc2']));

        update_option('munisys_about_hero_img', esc_url_raw($_POST['munisys_about_hero_img']));
        update_option('munisys_about_vision_main_img', esc_url_raw($_POST['munisys_about_vision_main_img']));
        update_option('munisys_about_vision_img1', esc_url_raw($_POST['munisys_about_vision_img1']));
        update_option('munisys_about_vision_img2', esc_url_raw($_POST['munisys_about_vision_img2']));

        echo '<div class="notice notice-success is-dismissible"><p>✅ Paramètres de la page À Propos mis à jour avec succès !</p></div>';
    }

    $h_t = get_option('munisys_about_hero_title', 'About Munisys');
    $h_d = get_option('munisys_about_hero_desc', 'Orchestrating digital infrastructure technologies for a unique customer experience since 1991.');

    $his_t = get_option('munisys_about_history_title', 'Our Journey');
    $his_d1 = get_option('munisys_about_history_desc1', 'Our journey began in 1991, when the local subsidiary of a US IT leader became part of a privately-owned Moroccan group of enterprises. This is when our name, Munisys, was coined.');
    $his_d2 = get_option('munisys_about_history_desc2', 'From 35 people and a yearly revenue of 60 million MAD at the onset, we steadily grew by seizing partnership opportunities to extend our offer and by building solid human and skills foundations, reaching over 200 people and 500 million MAD in 2022.');
    $his_q = get_option('munisys_about_history_quote', 'We are an IT infrastructure solutions integrator, always keeping pace with the fast evolving progress in the digital realm.');

    $v_t = get_option('munisys_about_vision_title', 'Our Vision');
    $v_d1 = get_option('munisys_about_vision_desc1', 'We strive for strengthening and extending our position as a leading player in mastering and orchestrating digital infrastructure technologies, to serve private as well as public enterprises and organizations.');
    $v_d2 = get_option('munisys_about_vision_desc2', 'While working on reinforcing digitization as a leverage for innovation, growth and competitiveness of the regional economy, we make sure to be socially and environmentally responsible every step of the way.');
    ?>
    <div class="wrap">
        <h1>🏢 Paramètres de la Page À Propos</h1>
        <p>Gérez ici les textes affichés sur la page À Propos.</p>

        <form method="POST"
            style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 900px; margin-top: 20px;">
            <input type="hidden" name="submit_munisys_about" value="1">

            <h2 style="color: #1d4e79;">🚀 En-tête (Hero)</h2>
            <table class="form-table">
                <tr>
                    <th>Titre</th>
                    <td><input type="text" name="munisys_about_hero_title" value="<?php echo esc_attr($h_t); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><textarea name="munisys_about_hero_desc" rows="2"
                            style="width: 100%;"><?php echo esc_textarea($h_d); ?></textarea></td>
                </tr>
                <tr>
                    <th>Image de fond (URL)</th>
                    <td><input type="text" name="munisys_about_hero_img"
                            value="<?php echo esc_url(get_option('munisys_about_hero_img', 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2560&auto=format&fit=crop')); ?>"
                            style="width: 100%;" /></td>
                </tr>
            </table>

            <h2 style="margin-top:40px; color: #1d4e79;">⏳ Section "Notre Histoire" (Our Journey)</h2>
            <table class="form-table">
                <tr>
                    <th>Titre</th>
                    <td><input type="text" name="munisys_about_history_title" value="<?php echo esc_attr($his_t); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Paragraphe 1</th>
                    <td><textarea name="munisys_about_history_desc1" rows="3"
                            style="width: 100%;"><?php echo esc_textarea($his_d1); ?></textarea></td>
                </tr>
                <tr>
                    <th>Paragraphe 2</th>
                    <td><textarea name="munisys_about_history_desc2" rows="3"
                            style="width: 100%;"><?php echo esc_textarea($his_d2); ?></textarea></td>
                </tr>
                <tr>
                    <th>Citation en évidence</th>
                    <td><textarea name="munisys_about_history_quote" rows="2"
                            style="width: 100%;"><?php echo esc_textarea($his_q); ?></textarea></td>
                </tr>
            </table>

            <h2 style="margin-top:40px; color: #1d4e79;">👁️ Section "Notre Vision" (Our Vision)</h2>
            <table class="form-table">
                <tr>
                    <th>Titre</th>
                    <td><input type="text" name="munisys_about_vision_title" value="<?php echo esc_attr($v_t); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Paragraphe 1</th>
                    <td><textarea name="munisys_about_vision_desc1" rows="3"
                            style="width: 100%;"><?php echo esc_textarea($v_d1); ?></textarea></td>
                </tr>
                <tr>
                    <th>Paragraphe 2</th>
                    <td><textarea name="munisys_about_vision_desc2" rows="3"
                            style="width: 100%;"><?php echo esc_textarea($v_d2); ?></textarea></td>
                </tr>
                <tr>
                    <th>Image principale de section (URL)</th>
                    <td><input type="text" name="munisys_about_vision_main_img"
                            value="<?php echo esc_url(get_option('munisys_about_vision_main_img', 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2000&auto=format&fit=crop')); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Petite Image 1 (URL)</th>
                    <td><input type="text" name="munisys_about_vision_img1"
                            value="<?php echo esc_url(get_option('munisys_about_vision_img1', 'https://images.unsplash.com/photo-1551434678-e076c223a692?q=80&w=2000&auto=format&fit=crop')); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Petite Image 2 (URL)</th>
                    <td><input type="text" name="munisys_about_vision_img2"
                            value="<?php echo esc_url(get_option('munisys_about_vision_img2', 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?q=80&w=2000&auto=format&fit=crop')); ?>"
                            style="width: 100%;" /></td>
                </tr>
            </table>

            <p class="submit" style="margin-top: 30px;">
                <input type="submit" class="button button-primary button-hero" value="Enregistrer les modifications">
            </p>
        </form>
    </div>
    <?php
}

function munisys_partners_settings_html()
{
    if (!current_user_can('edit_pages'))
        return;

    if (isset($_POST['submit_munisys_partners'])) {
        update_option('munisys_partners_hero_title', wp_kses_post(stripslashes($_POST['munisys_partners_hero_title'])));
        update_option('munisys_partners_hero_desc', sanitize_textarea_field($_POST['munisys_partners_hero_desc']));
        echo '<div class="notice notice-success is-dismissible"><p>✅ Paramètres de la page Tech Partners mis à jour avec succès !</p></div>';
    }

    $h_t = get_option('munisys_partners_hero_title', 'Technology <strong>Partners</strong>');
    $h_d = get_option('munisys_partners_hero_desc', 'We build solid ties with the world\'s leading technology providers to ensure true resilience, performance, and agility for your digital infrastructure.');
    ?>
    <div class="wrap">
        <h1>🤝 Paramètres de la Page Tech Partners</h1>
        <p>Gérez ici les textes de l'en-tête de la page Partenaires Technologiques.</p>

        <form method="POST"
            style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 900px; margin-top: 20px;">
            <input type="hidden" name="submit_munisys_partners" value="1">

            <h2 style="color: #1d4e79;">🚀 En-tête (Hero)</h2>
            <table class="form-table">
                <tr>
                    <th>Titre (utiliser &lt;strong&gt; pour mettre en gras)</th>
                    <td><textarea name="munisys_partners_hero_title" rows="2"
                            style="width: 100%;"><?php echo esc_textarea($h_t); ?></textarea></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><textarea name="munisys_partners_hero_desc" rows="3"
                            style="width: 100%;"><?php echo esc_textarea($h_d); ?></textarea></td>
                </tr>
            </table>

            <p class="submit" style="margin-top: 30px;">
                <input type="submit" class="button button-primary button-hero" value="Enregistrer les modifications">
            </p>
        </form>
    </div>
    <?php
}

function munisys_references_settings_html()
{
    if (!current_user_can('edit_pages'))
        return;

    if (isset($_POST['submit_munisys_refs'])) {
        update_option('munisys_refs_hero_label', sanitize_text_field($_POST['munisys_refs_hero_label']));
        update_option('munisys_refs_hero_title', wp_kses_post(stripslashes($_POST['munisys_refs_hero_title'])));
        update_option('munisys_refs_hero_desc', sanitize_textarea_field($_POST['munisys_refs_hero_desc']));
        echo '<div class="notice notice-success is-dismissible"><p>✅ Paramètres de la page Customer Refs mis à jour avec succès !</p></div>';
    }

    $h_l = get_option('munisys_refs_hero_label', 'Client References');
    $h_t = get_option('munisys_refs_hero_title', 'Trusted by<br /><strong>Leaders.</strong>');
    $h_d = get_option('munisys_refs_hero_desc', 'Our commitment to excellence has earned us lasting and fruitful collaborations with the largest economic actors and state entities in Morocco.');
    ?>
    <div class="wrap">
        <h1>🏆 Paramètres de la Page Customer References</h1>
        <p>Gérez ici les textes de l'en-tête de la page Références Clients.</p>

        <form method="POST"
            style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 900px; margin-top: 20px;">
            <input type="hidden" name="submit_munisys_refs" value="1">

            <h2 style="color: #1d4e79;">🚀 En-tête (Hero)</h2>
            <table class="form-table">
                <tr>
                    <th>Label (ex: Client References)</th>
                    <td><input type="text" name="munisys_refs_hero_label" value="<?php echo esc_attr($h_l); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Titre (utiliser &lt;strong&gt; et &lt;br/&gt;)</th>
                    <td><textarea name="munisys_refs_hero_title" rows="2"
                            style="width: 100%;"><?php echo esc_textarea($h_t); ?></textarea></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><textarea name="munisys_refs_hero_desc" rows="3"
                            style="width: 100%;"><?php echo esc_textarea($h_d); ?></textarea></td>
                </tr>
            </table>

            <p class="submit" style="margin-top: 30px;">
                <input type="submit" class="button button-primary button-hero" value="Enregistrer les modifications">
            </p>
        </form>
    </div>
    <?php
}

function munisys_services_settings_html()
{
    if (!current_user_can('edit_pages'))
        return;

    if (isset($_POST['submit_munisys_services_page'])) {
        update_option('munisys_srv_hero_title', wp_kses_post(stripslashes($_POST['munisys_srv_hero_title'])));
        update_option('munisys_srv_hero_desc', sanitize_textarea_field($_POST['munisys_srv_hero_desc']));
        update_option('munisys_srv_intro_title', sanitize_text_field($_POST['munisys_srv_intro_title']));
        update_option('munisys_srv_intro_desc', sanitize_textarea_field($_POST['munisys_srv_intro_desc']));

        update_option('munisys_srv_intro_img1', esc_url_raw($_POST['munisys_srv_intro_img1']));
        update_option('munisys_srv_intro_img2', esc_url_raw($_POST['munisys_srv_intro_img2']));
        update_option('munisys_srv_intro_img3', esc_url_raw($_POST['munisys_srv_intro_img3']));
        echo '<div class="notice notice-success is-dismissible"><p>✅ Paramètres de la page Services mis à jour avec succès !</p></div>';
    }

    $h_t = get_option('munisys_srv_hero_title', 'Our <strong>Services</strong>');
    $h_d = get_option('munisys_srv_hero_desc', 'Professional and operational services to help you make the right technology choices, design sound architectures, and deploy them according to best practices.');
    $i_t = get_option('munisys_srv_intro_title', 'IT Integration & Support');
    $i_d = get_option('munisys_srv_intro_desc', 'At Munisys, we cover the entire lifecycle of your IT infrastructure with a comprehensive catalog of professional services designed to ensure performance, security, and continuous business operations.');
    ?>
    <div class="wrap">
        <h1>🛠️ Paramètres de la Page Services</h1>
        <p>Gérez ici les textes de l'en-tête et l'introduction de la page Services.</p>

        <form method="POST"
            style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 900px; margin-top: 20px;">
            <input type="hidden" name="submit_munisys_services_page" value="1">

            <h2 style="color: #1d4e79;">🚀 En-tête (Hero)</h2>
            <table class="form-table">
                <tr>
                    <th>Titre (utiliser &lt;strong&gt;)</th>
                    <td><textarea name="munisys_srv_hero_title" rows="2"
                            style="width: 100%;"><?php echo esc_textarea($h_t); ?></textarea></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><textarea name="munisys_srv_hero_desc" rows="3"
                            style="width: 100%;"><?php echo esc_textarea($h_d); ?></textarea></td>
                </tr>
            </table>

            <h2 style="margin-top:40px; color: #1d4e79;">👋 Section Introduction</h2>
            <table class="form-table">
                <tr>
                    <th>Titre de l'introduction</th>
                    <td><input type="text" name="munisys_srv_intro_title" value="<?php echo esc_attr($i_t); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Description de l'introduction</th>
                    <td><textarea name="munisys_srv_intro_desc" rows="3"
                            style="width: 100%;"><?php echo esc_textarea($i_d); ?></textarea></td>
                </tr>
                <tr>
                    <th>Image 1 (URL)</th>
                    <td><input type="text" name="munisys_srv_intro_img1"
                            value="<?php echo esc_url(get_option('munisys_srv_intro_img1', 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=1000&auto=format&fit=crop')); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Image 2 (URL)</th>
                    <td><input type="text" name="munisys_srv_intro_img2"
                            value="<?php echo esc_url(get_option('munisys_srv_intro_img2', 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=1000&auto=format&fit=crop')); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Image 3 (URL)</th>
                    <td><input type="text" name="munisys_srv_intro_img3"
                            value="<?php echo esc_url(get_option('munisys_srv_intro_img3', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1000&auto=format&fit=crop')); ?>"
                            style="width: 100%;" /></td>
                </tr>
            </table>

            <p class="submit" style="margin-top: 30px;">
                <input type="submit" class="button button-primary button-hero" value="Enregistrer les modifications">
            </p>
        </form>
    </div>
    <?php
}

function munisys_insights_settings_html()
{
    if (!current_user_can('edit_pages'))
        return;

    $action = isset($_GET['ins_action']) ? sanitize_key($_GET['ins_action']) : 'list';
    $edit_id = isset($_GET['ins_id']) ? intval($_GET['ins_id']) : 0;
    $page_url = admin_url('admin.php?page=munisys-insights');

    // === SUPPRESSION ===
    if ($action === 'delete' && $edit_id && check_admin_referer('munisys_del_insight_' . $edit_id)) {
        wp_delete_post($edit_id, true);
        echo '<div class="notice notice-success is-dismissible"><p>✅ Insight supprimé.</p></div>';
        $action = 'list';
        $edit_id = 0;
    }

    // === SAUVEGARDE ===
    if (isset($_POST['munisys_insight_save'])) {
        check_admin_referer('munisys_save_insight');
        $pdata = array(
            'post_type' => 'munisys_insight',
            'post_title' => sanitize_text_field($_POST['ins_title']),
            'post_content' => wp_kses_post($_POST['ins_content']),
            'post_status' => 'publish',
        );
        if ($edit_id) {
            $pdata['ID'] = $edit_id;
            wp_update_post($pdata);
            $sid = $edit_id;
        } else {
            $sid = wp_insert_post($pdata);
        }
        if ($sid) {
            if (!empty($_POST['ins_image_url']))
                update_post_meta($sid, '_munisys_insight_img_url', esc_url_raw($_POST['ins_image_url']));
            else
                delete_post_meta($sid, '_munisys_insight_img_url');
            if (isset($_POST['ins_thumb_type']))
                update_post_meta($sid, '_munisys_insight_thumb_type', sanitize_text_field($_POST['ins_thumb_type']));
            if (isset($_POST['ins_thumb_t1']))
                update_post_meta($sid, '_munisys_insight_thumb_t1', sanitize_text_field($_POST['ins_thumb_t1']));
            if (isset($_POST['ins_thumb_t2']))
                update_post_meta($sid, '_munisys_insight_thumb_t2', sanitize_text_field($_POST['ins_thumb_t2']));
            if (isset($_POST['ins_thumb_t3']))
                update_post_meta($sid, '_munisys_insight_thumb_t3', sanitize_text_field($_POST['ins_thumb_t3']));

            update_post_meta($sid, '_munisys_insight_title_color', sanitize_hex_color($_POST['ins_title_color'] ?? ''));
            update_post_meta($sid, '_munisys_insight_bg_color', sanitize_hex_color($_POST['ins_bg_color'] ?? ''));
            update_post_meta($sid, '_munisys_insight_text_color', sanitize_hex_color($_POST['ins_text_color'] ?? ''));
            update_post_meta($sid, '_munisys_insight_img_size', intval($_POST['ins_img_size'] ?? 100));
        }
        echo '<div class="notice notice-success is-dismissible"><p>✅ Insight enregistré avec succès !</p></div>';
        $action = 'list';
        $edit_id = 0;
    }

    // ================================================
    // VUE : LISTE
    // ================================================
    if ($action === 'list'):
        $posts = get_posts(array('post_type' => 'munisys_insight', 'posts_per_page' => -1, 'post_status' => 'publish'));
        ?>
        <div class="wrap">
            <h1 style="color:#1d4e79; display:flex; align-items:center; gap:12px;">
                📰 Gestion des Insights
                <a href="<?php echo esc_url($page_url . '&ins_action=add'); ?>"
                    style="font-size:13px;padding:6px 14px;background:#922b55;color:#fff;border-radius:4px;text-decoration:none;">
                    + Ajouter un Insight
                </a>
            </h1>
            <p style="color:#555;">Tous vos articles Insights. Ils s'affichent automatiquement sur la page d'accueil du site.
            </p>

            <table class="wp-list-table widefat fixed striped" style="margin-top:20px;max-width:1000px;">
                <thead>
                    <tr>
                        <th style="width:40%">Titre</th>
                        <th style="width:15%">Date</th>
                        <th style="width:15%">Image</th>
                        <th style="width:30%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($posts)): ?>
                        <tr>
                            <td colspan="4" style="text-align:center;padding:30px;color:#888;">
                                Aucun insight. <a href="<?php echo esc_url($page_url . '&ins_action=add'); ?>">Créez le premier
                                    !</a>
                            </td>
                        </tr>
                    <?php else:
                        foreach ($posts as $p):
                            $img = get_post_meta($p->ID, '_munisys_insight_img_url', true) ?: get_the_post_thumbnail_url($p->ID, 'thumbnail');
                            ?>
                            <tr>
                                <td style="vertical-align:middle;font-weight:600"><?php echo esc_html($p->post_title); ?></td>
                                <td style="vertical-align:middle"><?php echo get_the_date('d/m/Y', $p->ID); ?></td>
                                <td style="vertical-align:middle">
                                    <?php if ($img): ?><img src="<?php echo esc_url($img); ?>"
                                            style="width:65px;height:45px;object-fit:cover;border-radius:3px">
                                    <?php else: ?><span style="color:#aaa;font-size:12px">Aucune</span><?php endif; ?>
                                </td>
                                <td style="vertical-align:middle">
                                    <a href="<?php echo esc_url($page_url . '&ins_action=edit&ins_id=' . $p->ID); ?>"
                                        style="display:inline-block;padding:5px 12px;background:#1d4e79;color:#fff;border-radius:3px;text-decoration:none;font-size:12px;margin-right:5px">
                                        ✏️ Modifier
                                    </a>
                                    <a href="<?php echo esc_url(wp_nonce_url($page_url . '&ins_action=delete&ins_id=' . $p->ID, 'munisys_del_insight_' . $p->ID)); ?>"
                                        style="display:inline-block;padding:5px 12px;background:#c0392b;color:#fff;border-radius:3px;text-decoration:none;font-size:12px"
                                        onclick="return confirm('Supprimer définitivement cet insight ?')">
                                        🗑️ Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <?php

        // ================================================
        // VUE : FORMULAIRE AJOUT / MODIFICATION
        // ================================================
    else:
        $post_obj = $edit_id ? get_post($edit_id) : null;
        $f_title = $post_obj ? $post_obj->post_title : '';
        $f_content = $post_obj ? $post_obj->post_content : '';
        $f_img = $edit_id ? (get_post_meta($edit_id, '_munisys_insight_img_url', true) ?: get_the_post_thumbnail_url($edit_id, 'full')) : '';
        $f_t_color = $edit_id ? (get_post_meta($edit_id, '_munisys_insight_title_color', true) ?: '#333333') : '#333333';
        $f_bg_color = $edit_id ? (get_post_meta($edit_id, '_munisys_insight_bg_color', true) ?: '#f0f4f8') : '#f0f4f8';
        $f_txt_color = $edit_id ? (get_post_meta($edit_id, '_munisys_insight_text_color', true) ?: '#555555') : '#555555';
        $f_img_size = $edit_id ? (get_post_meta($edit_id, '_munisys_insight_img_size', true) ?: 100) : 100;
        $f_thumb_type = $edit_id ? (get_post_meta($edit_id, '_munisys_insight_thumb_type', true) ?: 'image') : 'image';
        $f_thumb_t1 = $edit_id ? get_post_meta($edit_id, '_munisys_insight_thumb_t1', true) : '';
        $f_thumb_t2 = $edit_id ? get_post_meta($edit_id, '_munisys_insight_thumb_t2', true) : '';
        $f_thumb_t3 = $edit_id ? get_post_meta($edit_id, '_munisys_insight_thumb_t3', true) : '';
        $form_h = $edit_id ? '✏️ Modifier l\'Insight' : '➕ Ajouter un Insight';
        wp_enqueue_media();
        ?>
        <div class="wrap">
            <h1 style="color:#1d4e79"><?php echo $form_h; ?></h1>
            <a href="<?php echo esc_url($page_url); ?>"
                style="display:inline-block;margin-bottom:20px;color:#922b55;text-decoration:none">
                ← Retour à la liste des Insights
            </a>

            <!-- ====================== PDF IMPORTER BOX ====================== -->
            <div id="pdf-importer-box"
                style="background:#f0f6ff;border:2px dashed #1d4e79;border-radius:8px;padding:24px;max-width:900px;margin-bottom:24px">
                <h3 style="margin:0 0 8px;color:#1d4e79;font-size:16px">📄 Importer depuis un PDF <em
                        style="font-size:12px;font-weight:400;color:#666">(optionnel)</em></h3>
                <p style="margin:0 0 14px;color:#555;font-size:13px">
                    Téléchargez votre PDF via la médiathèque, collez son URL ici, puis cliquez sur <strong>"Analyser le
                        PDF"</strong>. Le titre, les sections et les paragraphes seront remplis automatiquement.
                </p>
                <div style="display:flex;gap:8px;align-items:center">
                    <input type="text" id="pdf_url_input" placeholder="URL du PDF (ex: .../uploads/2025/01/press-release.pdf)"
                        style="flex:1;padding:8px;border:1px solid #ccc;border-radius:4px" />
                    <button type="button" id="pdf_media_btn"
                        style="padding:8px 14px;background:#1d4e79;color:#fff;border:none;border-radius:4px;cursor:pointer;white-space:nowrap">
                        📂 Choisir le PDF
                    </button>
                    <button type="button" id="pdf_parse_btn"
                        style="padding:8px 16px;background:#922b55;color:#fff;border:none;border-radius:4px;cursor:pointer;white-space:nowrap;font-weight:600">
                        ⚡ Analyser le PDF
                    </button>
                </div>
                <div id="pdf_status" style="margin-top:10px;font-size:13px;display:none"></div>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
            <script>
                // Configure PDF.js worker
                if (window.pdfjsLib) {
                    pdfjsLib.GlobalWorkerOptions.workerSrc =
                        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                }

                document.addEventListener('DOMContentLoaded', function () {
                    var pdfInput = document.getElementById('pdf_url_input');
                    var pdfStatus = document.getElementById('pdf_status');

                    function showStatus(type, html) {
                        var styles = {
                            loading: { bg: '#fffbe6', border: '#f0c040', color: '#555' },
                            error: { bg: '#fde8e8', border: '#e74c3c', color: '#c0392b' },
                            warning: { bg: '#fff8e1', border: '#f39c12', color: '#7d5a00' },
                            success: { bg: '#eafaf1', border: '#2ecc71', color: '#1d7a1d' },
                        };
                        var s = styles[type] || styles.loading;
                        pdfStatus.style.display = 'block';
                        pdfStatus.style.cssText = 'display:block;padding:10px 14px;border-radius:4px;font-size:13px;' +
                            'background:' + s.bg + ';border:1px solid ' + s.border + ';color:' + s.color;
                        pdfStatus.innerHTML = html;
                    }

                    function flashGreen(el) {
                        if (!el) return;
                        el.style.transition = 'background 0.4s';
                        el.style.background = '#d4f7d4';
                        setTimeout(function () { el.style.background = ''; }, 1500);
                    }

                    function fillFields(title, contentHtml) {
                        // Fill title
                        var titleInput = document.getElementById('ins_title');
                        if (titleInput && title) {
                            titleInput.value = title;
                            flashGreen(titleInput);
                        }
                        // Fill content
                        if (contentHtml) {
                            var filled = false;
                            if (window.tinyMCE) {
                                var ed = tinyMCE.get('ins_content');
                                if (ed && !ed.isHidden()) { ed.setContent(contentHtml); filled = true; }
                            }
                            if (!filled) {
                                var ta = document.getElementById('ins_content');
                                if (ta) { ta.value = contentHtml; flashGreen(ta); }
                            }
                            var edWrap = document.getElementById('wp-ins_content-wrap');
                            if (edWrap) {
                                edWrap.style.transition = 'outline 0.4s';
                                edWrap.style.outline = '3px solid #2ecc71';
                                setTimeout(function () { edWrap.style.outline = ''; }, 1500);
                            }
                        }
                        // Scroll to title
                        if (titleInput) titleInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }

                    // ---- Parse extracted text into title + structured HTML ----
                    function parseTextToHTML(rawText, filenameTitle) {
                        // Normalize and split into individual lines
                        var lines = rawText.split(/\r?\n/).map(function (l) { return l.trim(); }).filter(Boolean);

                        var title = '';
                        var html = '';

                        var METADATA_NOISE = [
                            'created by pillow', 'created by reportlab', 'pdf driver',
                            'wkhtmltopdf', 'ghostscript', 'cairo', 'adobe acrobat'
                        ];

                        function isMetadata(line) {
                            var l = line.toLowerCase();
                            for (var i = 0; i < METADATA_NOISE.length; i++) {
                                if (l.indexOf(METADATA_NOISE[i]) !== -1) return true;
                            }
                            return false;
                        }

                        // Filter out obvious metadata lines
                        var validLines = lines.filter(function (l) { return !isMetadata(l); });

                        if (validLines.length > 0) {
                            // First valid line is the title
                            title = validLines[0];

                            // Process the rest into HTML
                            var currentParagraph = [];

                            for (var i = 1; i < validLines.length; i++) {
                                var line = validLines[i];

                                // If line is very short and no end punctuation, treat as heading
                                var words = line.split(/\s+/).length;
                                var isHeading = words > 0 && words <= 8 && !/[.;,]$/.test(line);

                                if (isHeading) {
                                    // Flush current paragraph
                                    if (currentParagraph.length > 0) {
                                        html += '<p>' + currentParagraph.join(' ') + '</p>\n';
                                        currentParagraph = [];
                                    }
                                    html += '<h3>' + line + '</h3>\n';
                                } else {
                                    currentParagraph.push(line);
                                    // If line ends with sentence-ending punctuation, flush paragraph
                                    if (/[.;!?]$/.test(line)) {
                                        html += '<p>' + currentParagraph.join(' ') + '</p>\n';
                                        currentParagraph = [];
                                    }
                                }
                            }
                            // Flush remaining
                            if (currentParagraph.length > 0) {
                                html += '<p>' + currentParagraph.join(' ') + '</p>\n';
                            }
                        }

                        if (!title) title = filenameTitle;
                        return { title: title, content: html };
                    }

                    // --- Open media library (PDFs only) ---
                    document.getElementById('pdf_media_btn').addEventListener('click', function () {
                        var frame = wp.media({
                            title: 'Choisir un fichier PDF',
                            button: { text: 'Utiliser ce PDF' },
                            multiple: false,
                            library: { type: 'application/pdf' }
                        });
                        frame.on('select', function () {
                            var att = frame.state().get('selection').first().toJSON();
                            pdfInput.value = att.url;
                        });
                        frame.open();
                    });

                    // --- Analyse PDF ---
                    document.getElementById('pdf_parse_btn').addEventListener('click', function () {
                        var url = pdfInput.value.trim();
                        if (!url) { alert('Veuillez d\'abord choisir ou coller l\'URL du PDF.'); return; }

                        // Derive filename as title fallback
                        var pathParts = url.split('/');
                        var filename = pathParts[pathParts.length - 1].replace(/\.[^.]+$/, '');
                        var filenameTitle = filename.replace(/[-_]/g, ' ')
                            .replace(/\b\w/g, function (c) { return c.toUpperCase(); });

                        showStatus('loading', '⏳ <strong>Lecture du PDF en cours...</strong> Extraction du texte via PDF.js (côté navigateur).');

                        // Check PDF.js availability
                        if (!window.pdfjsLib) {
                            showStatus('error', '❌ <strong>PDF.js non chargé.</strong> Vérifiez votre connexion internet et réessayez.');
                            return;
                        }

                        pdfjsLib.getDocument({ url: url, verbosity: 0 }).promise
                            .then(function (pdf) {
                                showStatus('loading', '⏳ <strong>Lecture du PDF...</strong> ' + pdf.numPages + ' page(s) détectée(s).');

                                var pagePromises = [];
                                for (var p = 1; p <= pdf.numPages; p++) {
                                    pagePromises.push(
                                        pdf.getPage(p).then(function (page) {
                                            return page.getTextContent();
                                        }).then(function (tc) {
                                            // Group items into lines using Y-position
                                            var lines = {};
                                            tc.items.forEach(function (item) {
                                                var y = Math.round(item.transform[5]);
                                                if (!lines[y]) lines[y] = [];
                                                lines[y].push(item.str);
                                            });
                                            // Sort Y descending (top of page first)
                                            var ys = Object.keys(lines).map(Number).sort(function (a, b) { return b - a; });
                                            return ys.map(function (y) { return lines[y].join(''); }).join('\n');
                                        })
                                    );
                                }

                                return Promise.all(pagePromises);
                            })
                            .then(function (pageTexts) {
                                var fullText = pageTexts.join('\n\n');
                                var wordCount = fullText.trim().split(/\s+/).filter(Boolean).length;

                                if (wordCount < 5) {
                                    // Image-based PDF — nothing readable, show template
                                    showStatus('warning',
                                        '⚠️ <strong>PDF basé sur des images.</strong> Le texte ne peut pas être extrait (PDF image/scan). ' +
                                        'Le titre a été estimé depuis le nom du fichier et un modèle a été pré-rempli. ' +
                                        '<strong>Remplacez les parties en [CROCHETS] par votre vrai contenu.</strong>');
                                    var template = '<h3>Introduction</h3><p>[Rédigez ici l\'introduction de ' + filenameTitle + '.]</p>' +
                                        '<h3>Contexte et enjeux</h3><p>[Décrivez ici le contexte.]</p>' +
                                        '<h3>Analyse et résultats</h3><p>[Présentez ici les résultats.]</p>' +
                                        '<h3>Conclusion</h3><p>[Rédigez ici la conclusion.]</p>';
                                    fillFields(filenameTitle, template);
                                    return;
                                }

                                var parsed = parseTextToHTML(fullText, filenameTitle);
                                fillFields(parsed.title, parsed.content);

                                showStatus('success',
                                    '✅ <strong>Analyse terminée !</strong> ' + wordCount + ' mots extraits depuis ' +
                                    pageTexts.length + ' page(s). Titre et contenu remplis. ' +
                                    'Vérifiez et modifiez si nécessaire avant de publier.<br>' +
                                    '<em style="font-size:11px;color:#888">Note : les images du PDF ne peuvent pas être extraites automatiquement.</em>');
                            })
                            .catch(function (e) {
                                // Fallback to server-side AJAX if PDF.js fails (CORS, etc.)
                                showStatus('loading', '⏳ PDF.js ne peut pas lire ce fichier directement. Tentative via le serveur...');
                                var data = new FormData();
                                data.append('action', 'munisys_parse_pdf');
                                data.append('nonce', '<?php echo wp_create_nonce('munisys_pdf_nonce'); ?>');
                                data.append('pdf_url', url);
                                fetch('<?php echo admin_url('admin-ajax.php'); ?>', { method: 'POST', body: data })
                                    .then(function (r) { return r.json(); })
                                    .then(function (resp) {
                                        if (!resp.success) { showStatus('error', '❌ ' + resp.data); return; }
                                        var d = resp.data;
                                        fillFields(d.title, d.content);
                                        showStatus(d.warning ? 'warning' : 'success',
                                            (d.warning ? '⚠️ ' : '✅ ') + (d.note || 'Terminé.'));
                                    })
                                    .catch(function (e2) { showStatus('error', '❌ Erreur : ' + e2.message); });
                            });
                    });
                });
            </script>
            <!-- ================================================================ -->



            <form method="POST"
                action="<?php echo esc_url($page_url . ($edit_id ? '&ins_action=edit&ins_id=' . $edit_id : '&ins_action=add')); ?>"
                style="background:#fff;padding:30px;border:1px solid #ccd0d4;max-width:900px;border-radius:4px">
                <?php wp_nonce_field('munisys_save_insight'); ?>

                <input type="hidden" name="munisys_insight_save" value="1">

                <table class="form-table">
                    <tr>
                        <th style="width:160px"><label for="ins_title">Titre *</label></th>
                        <td><input type="text" id="ins_title" name="ins_title" value="<?php echo esc_attr($f_title); ?>"
                                style="width:100%;font-size:16px;padding:8px" required /></td>
                    </tr>

                    <!-- IMAGE : URL ou Médiathèque -->
                    <tr>
                        <th><label>Image</label></th>
                        <td>
                            <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px">
                                <input type="text" id="ins_image_url" name="ins_image_url"
                                    value="<?php echo esc_attr($f_img); ?>" style="flex:1;padding:6px"
                                    placeholder="https://..." />
                                <button type="button" id="ins_media_btn"
                                    style="padding:6px 14px;background:#1d4e79;color:#fff;border:none;border-radius:4px;cursor:pointer;white-space:nowrap">
                                    🖼️ Choisir depuis Médias
                                </button>
                            </div>
                            <p style="color:#888;font-size:12px;margin:0">Collez un lien URL <em>ou</em> cliquez sur le bouton
                                pour choisir une image déjà téléchargée.</p>
                            <div id="ins_img_preview" style="margin-top:10px">
                                <?php if ($f_img): ?>
                                    <img id="ins_img_thumb" src="<?php echo esc_url($f_img); ?>"
                                        style="max-width:<?php echo intval($f_img_size); ?>%;border-radius:4px;border:1px solid #ddd;display:block" />
                                <?php endif; ?>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var urlInput = document.getElementById('ins_image_url');
                                    var thumb = document.getElementById('ins_img_thumb');
                                    var preview = document.getElementById('ins_img_preview');
                                    // Live URL preview
                                    urlInput.addEventListener('input', function () {
                                        if (!thumb) {
                                            thumb = document.createElement('img');
                                            thumb.id = 'ins_img_thumb';
                                            thumb.style.cssText = 'max-width:100%;border-radius:4px;border:1px solid #ddd;display:block';
                                            preview.appendChild(thumb);
                                        }
                                        thumb.src = this.value;
                                    });
                                    // Media library picker
                                    document.getElementById('ins_media_btn').addEventListener('click', function (e) {
                                        e.preventDefault();
                                        var frame = wp.media({
                                            title: 'Choisir une image',
                                            button: { text: 'Utiliser cette image' },
                                            multiple: false
                                        });
                                        frame.on('select', function () {
                                            var att = frame.state().get('selection').first().toJSON();
                                            urlInput.value = att.url;
                                            if (!thumb) {
                                                thumb = document.createElement('img');
                                                thumb.id = 'ins_img_thumb';
                                                thumb.style.cssText = 'max-width:100%;border-radius:4px;border:1px solid #ddd;display:block';
                                                preview.appendChild(thumb);
                                            }
                                            thumb.src = att.url;
                                            // update size slider
                                            var sl = document.getElementById('ins_img_size_range');
                                            if (sl) thumb.style.maxWidth = sl.value + '%';
                                        });
                                        frame.open();
                                    });
                                });
                            </script>
                        </td>
                    </tr>

                    <!-- TYPE DE MINIATURE -->
                    <tr>
                        <th><label for="ins_thumb_type">Type d'image / logo *</label></th>
                        <td>
                            <select id="ins_thumb_type" name="ins_thumb_type"
                                style="padding:6px;font-size:14px;border-radius:4px;width:100%;max-width:400px;">
                                <option value="image" <?php selected($f_thumb_type, 'image'); ?>>Image classique (Lien ou Média)
                                </option>
                                <option value="gitex" <?php selected($f_thumb_type, 'gitex'); ?>>Texte stylisé : Type "Gitex"
                                </option>
                                <option value="fipar" <?php selected($f_thumb_type, 'fipar'); ?>>Texte stylisé : Type "Helios
                                    Fipar"</option>
                                <option value="munisys" <?php selected($f_thumb_type, 'munisys'); ?>>Texte Simple Centré
                                </option>
                            </select>
                        </td>
                    </tr>

                    <!-- CHAMPS DYNAMIQUES POUR TEXTE STYLISE -->
                    <tr id="row_thumb_t1" style="display:none;">
                        <th><label id="lbl_thumb_t1">Texte 1</label></th>
                        <td><input type="text" name="ins_thumb_t1" id="ins_thumb_t1"
                                value="<?php echo esc_attr($f_thumb_t1); ?>" style="width:100%;padding:6px;max-width:400px;">
                        </td>
                    </tr>
                    <tr id="row_thumb_t2" style="display:none;">
                        <th><label id="lbl_thumb_t2">Texte 2</label></th>
                        <td><input type="text" name="ins_thumb_t2" id="ins_thumb_t2"
                                value="<?php echo esc_attr($f_thumb_t2); ?>" style="width:100%;padding:6px;max-width:400px;">
                        </td>
                    </tr>
                    <tr id="row_thumb_t3" style="display:none;">
                        <th><label id="lbl_thumb_t3">Texte 3</label></th>
                        <td><input type="text" name="ins_thumb_t3" id="ins_thumb_t3"
                                value="<?php echo esc_attr($f_thumb_t3); ?>" style="width:100%;padding:6px;max-width:400px;">
                        </td>
                    </tr>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var typeSelect = document.getElementById('ins_thumb_type');
                            var r1 = document.getElementById('row_thumb_t1');
                            var r2 = document.getElementById('row_thumb_t2');
                            var r3 = document.getElementById('row_thumb_t3');
                            var l1 = document.getElementById('lbl_thumb_t1');
                            var l2 = document.getElementById('lbl_thumb_t2');
                            var l3 = document.getElementById('lbl_thumb_t3');

                            function updateThumbFields() {
                                var val = typeSelect.value;
                                r1.style.display = 'none'; r2.style.display = 'none'; r3.style.display = 'none';
                                if (val === 'gitex') {
                                    r1.style.display = 'table-row'; r2.style.display = 'table-row';
                                    l1.innerText = 'Gros Texte Haut (ex: GITEX)';
                                    l2.innerText = 'Texte Rouge Bas (ex: AFRICA)';
                                } else if (val === 'fipar') {
                                    r1.style.display = 'table-row'; r2.style.display = 'table-row'; r3.style.display = 'table-row';
                                    l1.innerText = 'Texte Haut (ex: HELIOS)';
                                    l2.innerText = 'Texte Orange Milieu (ex: Investment Partners)';
                                    l3.innerText = 'Gros Texte Bas (ex: FIPAR)';
                                } else if (val === 'munisys') {
                                    r1.style.display = 'table-row';
                                    l1.innerText = 'Texte Centré (ex: MUNISYS)';
                                }
                            }
                            typeSelect.addEventListener('change', updateThumbFields);
                            updateThumbFields();
                        });
                    </script>

                    <!-- TAILLE DE L'IMAGE -->
                    <tr>
                        <th><label for="ins_img_size_range">Taille de l'image</label></th>
                        <td style="vertical-align:middle">
                            <input type="range" id="ins_img_size_range" name="ins_img_size" min="10" max="100" step="5"
                                value="<?php echo intval($f_img_size); ?>" style="width:250px;vertical-align:middle" />
                            <span id="ins_img_size_val"
                                style="margin-left:10px;font-weight:600"><?php echo intval($f_img_size); ?>%</span>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var sl = document.getElementById('ins_img_size_range');
                                    var lbl = document.getElementById('ins_img_size_val');
                                    sl.addEventListener('input', function () {
                                        lbl.textContent = this.value + '%';
                                        var th = document.getElementById('ins_img_thumb');
                                        if (th) th.style.maxWidth = this.value + '%';
                                    });
                                });
                            </script>
                        </td>
                    </tr>

                    <!-- COULEURS -->
                    <tr>
                        <th>🎨 Couleur du titre</th>
                        <td>
                            <input type="color" name="ins_title_color" value="<?php echo esc_attr($f_t_color); ?>"
                                style="width:60px;height:36px;padding:2px;border:1px solid #ccc;border-radius:4px;cursor:pointer" />
                            <span style="margin-left:8px;color:#888;font-size:12px">Couleur du titre de la carte sur la page
                                d'accueil</span>
                        </td>
                    </tr>
                    <tr>
                        <th>🎨 Couleur du fond</th>
                        <td>
                            <input type="color" name="ins_bg_color" value="<?php echo esc_attr($f_bg_color); ?>"
                                style="width:60px;height:36px;padding:2px;border:1px solid #ccc;border-radius:4px;cursor:pointer" />
                            <span style="margin-left:8px;color:#888;font-size:12px">Couleur de fond de la carte (section
                                image)</span>
                        </td>
                    </tr>
                    <tr>
                        <th>🎨 Couleur du texte</th>
                        <td>
                            <input type="color" name="ins_text_color" value="<?php echo esc_attr($f_txt_color); ?>"
                                style="width:60px;height:36px;padding:2px;border:1px solid #ccc;border-radius:4px;cursor:pointer" />
                            <span style="margin-left:8px;color:#888;font-size:12px">Couleur du texte descriptif / résumé</span>
                        </td>
                    </tr>

                    <!-- CONTENU -->
                    <tr>
                        <th><label>Contenu *</label></th>
                        <td>
                            <?php wp_editor($f_content, 'ins_content', array(
                                'textarea_name' => 'ins_content',
                                'media_buttons' => false,
                                'textarea_rows' => 20,
                            )); ?>
                        </td>
                    </tr>
                </table>

                <p style="margin-top:25px;display:flex;gap:12px">
                    <input type="submit" class="button button-primary button-hero"
                        value="<?php echo $edit_id ? '💾 Enregistrer' : '✅ Publier'; ?>">
                    <a href="<?php echo esc_url($page_url); ?>" class="button button-hero">Annuler</a>
                </p>
            </form>
        </div>
        <?php
    endif;
}


function munisys_footer_settings_html()
{
    if (!current_user_can('edit_pages'))
        return;

    if (isset($_POST['submit_munisys_footer'])) {
        update_option('munisys_footer_follow_title', sanitize_text_field($_POST['munisys_footer_follow_title']));
        update_option('munisys_footer_linkedin_link', esc_url_raw($_POST['munisys_footer_linkedin_link']));
        update_option('munisys_footer_copyright', sanitize_text_field($_POST['munisys_footer_copyright']));
        echo '<div class="notice notice-success is-dismissible"><p>✅ Paramètres du Footer mis à jour avec succès !</p></div>';
    }

    $f_t = get_option('munisys_footer_follow_title', 'Follow us on');
    $f_l = get_option('munisys_footer_linkedin_link', 'https://www.linkedin.com/company/munisys/');
    $f_c = get_option('munisys_footer_copyright', '©2026 MUNISYS - ALL RIGHTS RESERVED');
    ?>
    <div class="wrap">
        <h1>🦶 Paramètres du Footer</h1>
        <p>Gérez ici les textes et liens affichés dans le pied de page du site.</p>

        <form method="POST"
            style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 900px; margin-top: 20px;">
            <input type="hidden" name="submit_munisys_footer" value="1">

            <h2 style="color: #1d4e79;">🔗 Réseaux Sociaux</h2>
            <table class="form-table">
                <tr>
                    <th>Titre (ex: Follow us on)</th>
                    <td><input type="text" name="munisys_footer_follow_title" value="<?php echo esc_attr($f_t); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th>Lien LinkedIn</th>
                    <td><input type="text" name="munisys_footer_linkedin_link" value="<?php echo esc_attr($f_l); ?>"
                            style="width: 100%;" /></td>
                </tr>
            </table>

            <h2 style="margin-top:40px; color: #1d4e79;">⚖️ Copyright</h2>
            <table class="form-table">
                <tr>
                    <th>Texte du copyright</th>
                    <td><input type="text" name="munisys_footer_copyright" value="<?php echo esc_attr($f_c); ?>"
                            style="width: 100%;" /></td>
                </tr>
            </table>

            <p class="submit" style="margin-top: 30px;">
                <input type="submit" class="button button-primary button-hero" value="Enregistrer les modifications">
            </p>
        </form>
    </div>
    <?php
}

// ==========================================
// 4. NOUVEAU SHORTCODE POUR AFFICHER LE PDF DANS UNE PAGE
// ==========================================
// Utilisation : Créez une page WordPress et mettez le shortcode [munisys_pdf_viewer]
add_shortcode('munisys_pdf_viewer', 'render_munisys_pdf_viewer');
function render_munisys_pdf_viewer()
{
    $pdf_url = get_option('munisys_press_release_url', '');

    ob_start();
    ?>
    <div class="munisys-pdf-container" style="width: 100%; max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
        <?php if (empty($pdf_url)): ?>
            <div style="background: #f8f9fa; border: 1px dashed #ccc; padding: 3rem; text-align: center; border-radius: 8px;">
                <h3 style="color: #1d4e79; margin-top: 0;">Aucun document Press Release</h3>
                <p style="color: #666;">Veuillez aller dans le menu <strong>"Page Accueil"</strong> de WordPress pour ajouter le
                    lien de votre PDF.</p>
            </div>
        <?php else: ?>
            <div
                style="width: 100%; height: 85vh; min-height: 600px; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
                <!-- Lecteur PDF universel -->
                <iframe src="<?php echo esc_url($pdf_url); ?>#view=FitH" width="100%" height="100%"
                    style="border: none;"></iframe>
            </div>
        <?php endif; ?>
    </div>

    <!-- 🛠️ SUPPRESSION TOTALE DU FOOTER SUR LA PAGE DU PDF -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                // Cherche TOUS les footers Breakdance (ou autres)
                var footers = document.querySelectorAll('footer, .bte-footer, [data-breakdance-global-footer], #breakdance-footer');

                // On les cache TOUS pour laisser le PDF en plein écran
                for (var i = 0; i < footers.length; i++) {
                    footers[i].style.display = 'none';
                    if (footers[i].parentElement) {
                        footers[i].parentElement.style.marginBottom = '0';
                        footers[i].parentElement.style.paddingBottom = '0';
                    }
                }
            }, 100);
        });
    </script>
    <?php
    return ob_get_clean();
}

// ==========================================
// 5. SHORTCODE PAGE CONTACT
// ==========================================
add_shortcode('munisys_contact', 'render_munisys_contact_shortcode');
function render_munisys_contact_shortcode()
{
    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800;900&display=swap');

        /* ── PAGE CONTACT STYLES (ARCHITECTURAL PREMIUM) ── */
        .m-contact-page,
        .m-contact-page * {
            font-family: 'Montserrat', sans-serif !important;
            box-sizing: border-box;
        }

        .m-contact-page {
            background: #f8f9fa;
            color: #333;
            overflow-x: hidden;
        }

        /* Hero Wrap (Overlap System) */
        .m-contact-hero-wrap {
            position: relative;
            padding-bottom: 150px;
            background: #f8f9fa;
        }

        .m-contact-hero {
            background: var(--blue-dark, #1d4e79);
            padding: 8rem 5% 12rem;
            text-align: center;
            color: #fff;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .m-contact-hero h1 {
            font-size: 5rem;
            font-weight: 200;
            color: #fff;
            margin: 0 0 1rem 0;
            letter-spacing: -1px;
        }

        .m-contact-hero p {
            font-size: 1.3rem;
            font-weight: 300;
            color: rgba(255, 255, 255, 0.8);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Floating Form Card */
        .m-contact-form-card {
            background: #fff;
            max-width: 1000px;
            margin: -8rem auto 0;
            position: relative;
            z-index: 10;
            border-radius: 20px;
            padding: 5rem 4rem;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .m-form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .m-form-group {
            position: relative;
        }

        .m-form-group.full {
            margin-bottom: 3rem;
        }

        /* Material-like Floating Labels */
        .m-form-group label {
            position: absolute;
            top: 1rem;
            left: 0;
            font-size: 1rem;
            color: #888;
            font-weight: 500;
            transition: all 0.3s ease;
            pointer-events: none;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .m-form-group input,
        .m-form-group textarea {
            width: 100%;
            border: none;
            border-bottom: 2px solid #eaeaea;
            padding: 1.2rem 0 0.5rem;
            font-size: 1.2rem;
            color: var(--blue-dark, #1d4e79);
            background: transparent;
            transition: border-color 0.3s;
        }

        .m-form-group input:focus,
        .m-form-group textarea:focus {
            outline: none;
            border-bottom-color: var(--bordeaux, #922b55);
        }

        .m-form-group input:focus+label,
        .m-form-group input:valid+label,
        .m-form-group textarea:focus+label,
        .m-form-group textarea:valid+label {
            top: -0.5rem;
            font-size: 0.75rem;
            color: var(--bordeaux, #922b55);
            font-weight: 700;
        }

        .m-btn-submit {
            background: var(--blue-dark, #1d4e79);
            color: #fff;
            border: none;
            padding: 1.4rem 4rem;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 50px;
            width: 100%;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .m-btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: var(--bordeaux, #922b55);
            transition: width 0.4s cubic-bezier(0.19, 1, 0.22, 1);
            z-index: -1;
        }

        .m-btn-submit:hover::before {
            width: 100%;
        }

        .m-btn-submit:hover {
            box-shadow: 0 15px 30px rgba(146, 43, 85, 0.3);
            transform: translateY(-3px);
        }

        /* Fullwidth Map with Floating Controls */
        .m-map-wrapper {
            position: relative;
            width: 100%;
            height: 700px;
            margin-top: 4rem;
        }

        .m-map-iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(20%) contrast(1.1);
        }

        .m-map-overlay {
            position: absolute;
            top: 50%;
            left: 5%;
            transform: translateY(-50%);
            width: 450px;
            z-index: 10;
        }

        .m-office-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .m-office-tabs {
            display: flex;
            background: #f4f6f8;
            padding: 0.5rem;
            border-bottom: 1px solid #eaeaea;
        }

        .m-tab-btn {
            flex: 1;
            background: transparent;
            border: none;
            padding: 1.2rem 1rem;
            font-size: 1rem;
            font-weight: 700;
            color: #888;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 12px;
        }

        .m-tab-btn.active {
            background: #fff;
            color: var(--blue-dark, #1d4e79);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .m-office-content-wrap {
            padding: 3rem;
        }

        .m-office-content {
            display: none;
            animation: mFadeIn 0.5s ease forwards;
        }

        .m-office-content.active {
            display: block;
        }

        @keyframes mFadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .m-office-content h3 {
            font-size: 2rem;
            color: var(--blue-dark, #1d4e79);
            margin-bottom: 1rem;
            font-weight: 300;
        }

        .m-office-content p {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .m-phone-link {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--bordeaux, #922b55);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .m-phone-link svg {
            width: 24px;
            height: 24px;
            stroke-width: 1.5;
        }

        @media(max-width: 1024px) {
            .m-contact-form-card {
                margin-top: -4rem;
                width: 90%;
                padding: 4rem 3rem;
            }

            .m-map-overlay {
                left: 50%;
                transform: translate(-50%, -50%);
                width: 90%;
                max-width: 450px;
            }
        }

        @media(max-width: 768px) {
            .m-contact-hero h1 {
                font-size: 3.5rem;
            }

            .m-form-row {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .m-contact-form-card {
                padding: 3rem 2rem;
            }

            .m-office-content-wrap {
                padding: 2rem;
            }
        }
    </style>

    <div class="m-contact-page">
        <!-- Hero & Floating Form -->
        <div class="m-contact-hero-wrap">
            <div class="m-contact-hero m-reveal-left">
                <h1><?php echo esc_html(get_option('munisys_contact_title', 'Let\'s Connect')); ?></h1>
                <p><?php echo esc_html(get_option('munisys_contact_desc', 'Reach out to our experts. We are ready to help you orchestrate your next digital transformation project.')); ?>
                </p>
            </div>

            <div class="m-contact-form-card m-reveal m-delay-1">
                <form class="m-contact-form" id="munisys-contact-form">
                    <div class="m-form-row">
                        <div class="m-form-group">
                            <input type="text" id="m-fname" name="fname" required>
                            <label for="m-fname">First Name</label>
                        </div>
                        <div class="m-form-group">
                            <input type="text" id="m-lname" name="lname" required>
                            <label for="m-lname">Last Name</label>
                        </div>
                    </div>
                    <div class="m-form-row">
                        <div class="m-form-group">
                            <input type="email" id="m-email" name="email" required>
                            <label for="m-email">Work Email</label>
                        </div>
                        <div class="m-form-group">
                            <input type="tel" id="m-phone" name="phone" required>
                            <label for="m-phone">Phone Number</label>
                        </div>
                    </div>
                    <div class="m-form-group full">
                        <textarea id="m-message" name="message" rows="4" required></textarea>
                        <label for="m-message">How can we help?</label>
                    </div>
                    <div id="m-contact-response" style="margin-bottom:1rem; font-weight:500;"></div>
                    <button type="submit" class="m-btn-submit" id="m-contact-submit">
                        <span>Send Message</span>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Fullwidth Map Section -->
        <div class="m-map-wrapper m-reveal">
            <iframe id="munisys-map-iframe" class="m-map-iframe"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3329.9867015403063!2d-7.625624!3d33.423588!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzPCsDI1JzI0LjkiTiA3wrAzNyczMi4yIlc!5e0!3m2!1sen!2sma!4v1700000000000!5m2!1sen!2sma"
                style="border:0;" allowfullscreen="" loading="lazy"></iframe>

            <div class="m-map-overlay">
                <div class="m-office-card">
                    <div class="m-office-tabs">
                        <button class="m-tab-btn active" data-map="casa">Casablanca</button>
                        <button class="m-tab-btn" data-map="rabat">Rabat</button>
                    </div>

                    <div class="m-office-content-wrap">
                        <div class="m-office-content active" id="content-casa">
                            <h3><?php echo esc_html(get_option('munisys_contact_casa_title', 'Headquarter')); ?></h3>
                            <p><?php echo wp_kses_post(get_option('munisys_contact_casa_desc', '11-12 Bouskoura Park Ville Verte Bouskoura<br>27182 Casablanca - Maroc')); ?>
                            </p>
                            <?php $casa_p = get_option('munisys_contact_casa_phone', '+212 5 22 00 00 00'); ?>
                            <a href="tel:<?php echo esc_attr(str_replace(' ', '', $casa_p)); ?>" class="m-phone-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <?php echo esc_html($casa_p); ?>
                            </a>
                        </div>

                        <div class="m-office-content" id="content-rabat">
                            <h3><?php echo esc_html(get_option('munisys_contact_rabat_title', 'Rabat Office')); ?></h3>
                            <p><?php echo wp_kses_post(get_option('munisys_contact_rabat_desc', '288, angle rues Khatri Joumani et Mohamed Ben Yazid OLM Souissi<br>10170 Rabat - Morocco')); ?>
                            </p>
                            <?php $rabat_p = get_option('munisys_contact_rabat_phone', '+212 5 37 00 00 00'); ?>
                            <a href="tel:<?php echo esc_attr(str_replace(' ', '', $rabat_p)); ?>" class="m-phone-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <?php echo esc_html($rabat_p); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.m-tab-btn');
            const contents = document.querySelectorAll('.m-office-content');
            const mapIframe = document.getElementById('munisys-map-iframe');

            const mapsUrl = {
                'casa': 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3329.9867015403063!2d-7.625624!3d33.423588!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzPCsDI1JzI0LjkiTiA3wrAzNyczMi4yIlc!5e0!3m2!1sen!2sma!4v1700000000000!5m2!1sen!2sma',
                'rabat': 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3307.78168289456!2d-6.835492!3d33.985558!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzPCsDU5JzA4LjAiTiA2wrA1MCcwNy44Ilc!5e0!3m2!1sen!2sma!4v1700000000000!5m2!1sen!2sma'
            };

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.remove('active'));

                    tab.classList.add('active');
                    const target = tab.getAttribute('data-map');
                    document.getElementById('content-' + target).classList.add('active');

                    if (mapsUrl[target]) {
                        mapIframe.src = mapsUrl[target];
                    }
                });
            });

            // AJAX Form Submission
            const contactForm = document.getElementById('munisys-contact-form');
            if (contactForm) {
                contactForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const submitBtn = document.getElementById('m-contact-submit');
                    const responseDiv = document.getElementById('m-contact-response');
                    const btnText = submitBtn.querySelector('span');

                    const originalText = btnText.innerText;
                    btnText.innerText = 'Sending...';
                    submitBtn.style.opacity = '0.7';
                    submitBtn.disabled = true;
                    responseDiv.innerHTML = '';

                    const formData = new FormData(contactForm);
                    formData.append('action', 'munisys_send_contact');
                    formData.append('security', '<?php echo wp_create_nonce("munisys_contact_nonce"); ?>');

                    const sendAjax = function (token) {
                        if (token) {
                            formData.append('recaptcha_token', token);
                        }

                        fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                            method: 'POST',
                            body: formData
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    responseDiv.style.color = '#28a745';
                                    responseDiv.innerHTML = '✅ ' + data.data.message;
                                    contactForm.reset();
                                } else {
                                    responseDiv.style.color = '#dc3545';
                                    responseDiv.innerHTML = '❌ ' + (data.data.message || 'Unknown error.');
                                }
                            })
                            .catch(err => {
                                responseDiv.style.color = '#dc3545';
                                responseDiv.innerHTML = '❌ Server connection error.';
                            })
                            .finally(() => {
                                btnText.innerText = originalText;
                                submitBtn.style.opacity = '1';
                                submitBtn.disabled = false;
                            });
                    };

                    <?php $site_key = get_option('munisys_recaptcha_site_key', ''); ?>
                    <?php if (!empty($site_key)): ?>
                        if (typeof grecaptcha !== 'undefined') {
                            grecaptcha.ready(function () {
                                grecaptcha.execute('<?php echo esc_js($site_key); ?>', { action: 'submit' }).then(function (token) {
                                    sendAjax(token);
                                });
                            });
                        } else {
                            sendAjax('');
                        }
                    <?php else: ?>
                        sendAjax('');
                    <?php endif; ?>
                });
            }
        });
    </script>
    <?php if (!empty($site_key)): ?>
        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo esc_attr($site_key); ?>"></script>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}

// ==========================================
// 6. SHORTCODE PAGE CAREERS
// ==========================================
add_shortcode('munisys_careers', 'render_munisys_careers_shortcode');
function render_munisys_careers_shortcode()
{
    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800;900&display=swap');

        /* ── PAGE CAREERS STYLES (ARCHITECTURAL PREMIUM) ── */
        .m-car-page,
        .m-car-page * {
            font-family: 'Montserrat', sans-serif !important;
            box-sizing: border-box;
        }

        .m-car-page {
            background: #fff;
            color: #333;
            overflow-x: hidden;
        }

        /* Split Hero */
        .m-car-hero {
            display: flex;
            min-height: 80vh;
            background: #fff;
        }

        .m-car-hero-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 6rem 5% 6rem 10%;
            background: #f8f9fa;
            position: relative;
        }

        .m-car-hero-left::after {
            content: '';
            position: absolute;
            right: -50px;
            top: 0;
            width: 100px;
            height: 100%;
            background: #f8f9fa;
            transform: skewX(-10deg);
            z-index: 2;
        }

        .m-car-hero-left h1 {
            font-size: 5rem;
            font-weight: 200;
            color: var(--blue-dark, #1d4e79);
            margin-bottom: 2rem;
            line-height: 1.1;
            letter-spacing: -1px;
        }

        .m-car-hero-left p {
            font-size: 1.4rem;
            color: #555;
            max-width: 500px;
            line-height: 1.8;
            font-weight: 300;
        }

        .m-car-hero-right {
            flex: 1;
            background: url('<?php echo esc_url(get_option('munisys_careers_hero_img', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2000&auto=format&fit=crop')); ?>') center/cover;
            position: relative;
        }

        /* Hover Cards "Why Munisys" */
        .m-car-why {
            padding: 8rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .m-car-why-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 5rem;
        }

        .m-car-why-header h2 {
            font-size: 4rem;
            color: var(--blue-dark, #1d4e79);
            font-weight: 200;
            margin: 0;
            letter-spacing: -1px;
        }

        .m-car-why-header p {
            font-size: 1.2rem;
            color: #666;
            max-width: 400px;
            margin: 0;
            text-align: right;
        }

        .m-car-why-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3rem;
        }

        .m-car-why-card {
            background: #fcfcfc;
            padding: 4rem 3rem;
            border: 1px solid #eaeaea;
            transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
            position: relative;
            overflow: hidden;
            border-radius: 4px;
        }

        .m-car-why-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 2px solid var(--bordeaux, #922b55);
            opacity: 0;
            transform: scale(0.95);
            transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
            pointer-events: none;
            border-radius: 4px;
        }

        .m-car-why-card:hover {
            background: #fff;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
            transform: translateY(-10px);
        }

        .m-car-why-card:hover::before {
            opacity: 1;
            transform: scale(1);
        }

        .m-car-why-card h3 {
            font-size: 2.2rem;
            color: var(--blue-dark, #1d4e79);
            margin-bottom: 1.5rem;
            font-weight: 300;
            transition: color 0.3s;
        }

        .m-car-why-card p {
            font-size: 1.15rem;
            color: #555;
            line-height: 1.8;
        }

        /* Interactive Job Lines */
        .m-car-jobs {
            background: var(--blue-dark, #1d4e79);
            padding: 8rem 5%;
            color: #fff;
        }

        .m-jobs-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .m-jobs-header h2 {
            font-size: 4rem;
            font-weight: 200;
            color: #fff;
            margin-bottom: 4rem;
            letter-spacing: -1px;
        }

        .m-job-line {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 3rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s ease;
            position: relative;
            cursor: pointer;
        }

        .m-job-line::after {
            content: '';
            position: absolute;
            left: -5%;
            width: 110%;
            height: 100%;
            background: rgba(255, 255, 255, 0.03);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 1;
            pointer-events: none;
        }

        .m-job-line:hover::after {
            opacity: 1;
        }

        .m-job-title {
            flex: 2;
            z-index: 2;
            transition: transform 0.4s ease;
        }

        .m-job-title h3 {
            font-size: 2.5rem;
            font-weight: 300;
            margin: 0 0 0.5rem 0;
            color: #fff;
        }

        .m-job-meta {
            flex: 1;
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 2;
        }

        .m-job-action {
            flex: 1;
            text-align: right;
            z-index: 2;
            overflow: hidden;
            display: flex;
            justify-content: flex-end;
        }

        .m-btn-apply {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--bordeaux, #922b55);
            color: #fff;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
            transform: translateX(50px);
            opacity: 0;
        }

        .m-btn-apply svg {
            width: 24px;
            height: 24px;
            transition: transform 0.4s;
        }

        .m-job-line:hover .m-job-title {
            transform: translateX(20px);
        }

        .m-job-line:hover .m-btn-apply {
            transform: translateX(0);
            opacity: 1;
        }

        .m-btn-apply:hover {
            transform: scale(1.1) !important;
            box-shadow: 0 10px 20px rgba(146, 43, 85, 0.4);
        }

        .m-btn-apply:hover svg {
            transform: rotate(-45deg);
        }

        @media(max-width: 1024px) {
            .m-car-hero {
                flex-direction: column;
            }

            .m-car-hero-left {
                padding: 6rem 5%;
            }

            .m-car-hero-left::after {
                display: none;
            }

            .m-car-hero-right {
                min-height: 400px;
            }

            .m-car-why-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 2rem;
            }

            .m-car-why-header p {
                text-align: left;
            }

            .m-car-why-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media(max-width: 768px) {
            .m-car-hero-left h1 {
                font-size: 3.5rem;
            }

            .m-car-why-grid {
                grid-template-columns: 1fr;
            }

            .m-job-line {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.5rem;
            }

            .m-job-title h3 {
                font-size: 2rem;
            }

            .m-btn-apply {
                transform: translateX(0);
                opacity: 1;
                border-radius: 5px;
                width: auto;
                height: auto;
                padding: 1rem 2rem;
            }

            .m-btn-apply span {
                display: inline;
                margin-right: 10px;
            }

            .m-job-line:hover .m-job-title {
                transform: none;
            }
        }
    </style>

    <div class="m-car-page">
        <!-- Split Hero -->
        <div class="m-car-hero">
            <div class="m-car-hero-left m-reveal-left">
                <h1><?php echo wp_kses_post(get_option('munisys_careers_hero_title', 'Shape<br>the future')); ?></h1>
                <p><?php echo esc_html(get_option('munisys_careers_hero_desc', 'Join Munisys and work with cutting-edge infrastructure technologies to accelerate digital transformation for major enterprises.')); ?>
                </p>
            </div>
            <div class="m-car-hero-right"></div>
        </div>

        <!-- Hover Cards -->
        <div class="m-car-why">
            <div class="m-car-why-header m-reveal">
                <h2><?php echo esc_html(get_option('munisys_careers_why_title', 'Why Munisys?')); ?></h2>
                <p><?php echo esc_html(get_option('munisys_careers_why_desc', 'We provide an environment where excellence meets innovation. Grow your career with industry leaders.')); ?>
                </p>
            </div>

            <div class="m-car-why-grid">
                <?php
                $def_why = [
                    1 => ['t' => 'Innovation', 'd' => 'Work with cutting-edge technologies and shape the digital transformation of major enterprises in Morocco and beyond.'],
                    2 => ['t' => 'Excellence', 'd' => 'Join a team of highly certified experts and evolve in a culture of continuous learning and high performance.'],
                    3 => ['t' => 'Impact', 'd' => 'Your work will directly secure and optimize the core infrastructure of the largest institutions in the region.']
                ];
                for ($i = 1; $i <= 3; $i++):
                    $c_t = get_option('munisys_careers_why_' . $i . '_title', $def_why[$i]['t']);
                    $c_d = get_option('munisys_careers_why_' . $i . '_desc', $def_why[$i]['d']);
                    ?>
                    <div class="m-car-why-card m-reveal m-delay-<?php echo $i; ?>">
                        <h3><?php echo esc_html($c_t); ?></h3>
                        <p><?php echo esc_html($c_d); ?></p>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Interactive Job Lines -->
        <div class="m-car-jobs">
            <div class="m-jobs-container">
                <div class="m-jobs-header m-reveal">
                    <h2>Open Positions</h2>
                </div>

                <div class="m-job-list">
                    <?php
                    $career_args = array(
                        'post_type' => 'munisys_career',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                    );
                    $career_query = new WP_Query($career_args);

                    if ($career_query->have_posts()):
                        $job_delay = 1;
                        while ($career_query->have_posts()):
                            $career_query->the_post();
                            // Retrieve custom fields or excerpt for location/type if needed
                            // For now we'll use the excerpt to store "Casablanca • Full-Time"
                            $meta = get_the_excerpt();
                            if (empty($meta)) {
                                $meta = 'Full-Time';
                            }
                            ?>
                            <div class="m-job-line m-reveal m-delay-<?php echo min($job_delay, 5); ?>">
                                <div class="m-job-title">
                                    <h3><?php the_title(); ?></h3>
                                </div>
                                <div class="m-job-meta">
                                    <?php echo esc_html($meta); ?>
                                </div>
                                <div class="m-job-action">
                                    <a href="<?php the_permalink(); ?>" class="m-btn-apply">
                                        <span class="d-md-none" style="display:none;">Apply</span>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <?php
                            $job_delay++;
                        endwhile;
                        wp_reset_postdata();
                    else:
                        ?>
                        <div class="m-job-line m-reveal m-delay-1">
                            <div class="m-job-title">
                                <h3>Senior Cloud Architect</h3>
                            </div>
                            <div class="m-job-meta">
                                Rabat • Full-Time
                            </div>
                            <div class="m-job-action">
                                <a href="#" class="m-btn-apply temp-apply">
                                    <span class="d-md-none" style="display:none;">Apply</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="m-job-line m-reveal m-delay-2">
                            <div class="m-job-title">
                                <h3>Cybersecurity Consultant</h3>
                            </div>
                            <div class="m-job-meta">
                                Casablanca • Full-Time
                            </div>
                            <div class="m-job-action">
                                <a href="#" class="m-btn-apply temp-apply">
                                    <span class="d-md-none" style="display:none;">Apply</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="m-job-line m-reveal m-delay-3">
                            <div class="m-job-title">
                                <h3>System Administrator</h3>
                            </div>
                            <div class="m-job-meta">
                                Rabat • Full-Time
                            </div>
                            <div class="m-job-action">
                                <a href="#" class="m-btn-apply temp-apply">
                                    <span class="d-md-none" style="display:none;">Apply</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.temp-apply').forEach(btn => {
            btn.addEventListener('click', e => {
                // Envoi de CV désactivé temporairement ou formulaire classique
            });
        });
    </script>
    <?php
    return ob_get_clean();
}

// ==========================================
// 7. SHORTCODE PAGE ABOUT US
// ==========================================
add_shortcode('munisys_about', 'render_munisys_about_shortcode');
function render_munisys_about_shortcode()
{
    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800;900&display=swap');

        /* ── PAGE ABOUT US STYLES (ARCHITECTURAL PREMIUM) ── */
        .m-about-page,
        .m-about-page * {
            font-family: 'Montserrat', sans-serif !important;
            box-sizing: border-box;
        }

        .m-about-page {
            background: #fcfcfc;
            color: #333;
            overflow-x: hidden;
        }

        /* Hero Banner (Overlap) */
        .m-about-hero-wrap {
            position: relative;
            min-height: 85vh;
            display: flex;
            align-items: center;
            background: #000;
            overflow: hidden;
        }

        .m-about-hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.6;
            filter: grayscale(50%);
            transform: scale(1.05);
            transition: transform 10s ease;
        }

        .m-about-hero-wrap:hover .m-about-hero-bg {
            transform: scale(1);
        }

        .m-about-hero-content {
            position: relative;
            z-index: 2;
            padding: 0 10%;
            max-width: 1000px;
        }

        .m-about-hero-content h1 {
            font-size: 5.5rem;
            font-weight: 200;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 2rem;
            letter-spacing: -2px;
        }

        .m-about-hero-content p {
            font-size: 1.5rem;
            font-weight: 300;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            border-left: 4px solid var(--bordeaux, #922b55);
            padding-left: 2rem;
        }

        /* History Timeline */
        .m-about-history-sec {
            padding: 10rem 5%;
            background: #fff;
            position: relative;
        }

        .m-history-watermark {
            position: absolute;
            top: 5%;
            left: 5%;
            font-size: 15rem;
            font-weight: 900;
            color: rgba(29, 78, 121, 0.03);
            line-height: 1;
            pointer-events: none;
        }

        .m-history-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .m-history-text {
            padding-top: 5rem;
        }

        .m-history-text h2 {
            font-size: 3.5rem;
            color: var(--blue-dark, #1d4e79);
            font-weight: 200;
            margin-bottom: 3rem;
            letter-spacing: -1px;
        }

        .m-history-text p {
            font-size: 1.25rem;
            color: #555;
            line-height: 1.8;
            margin-bottom: 2rem;
            font-weight: 300;
        }

        .m-history-quote {
            margin-top: 4rem;
            padding: 3rem;
            background: #f8f9fa;
            border-radius: 20px;
            font-size: 1.5rem;
            font-weight: 500;
            color: var(--blue-dark, #1d4e79);
            line-height: 1.6;
            border: 1px solid #eaeaea;
        }

        .m-history-images {
            position: relative;
        }

        .m-history-img-1 {
            width: 80%;
            height: 500px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
        }

        .m-history-img-2 {
            width: 60%;
            height: 400px;
            object-fit: cover;
            position: absolute;
            bottom: -50px;
            right: 0;
            border-radius: 20px;
            border: 15px solid #fff;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.15);
        }

        /* Figures (Elevated) */
        .m-about-figures-wrap {
            margin-top: -8rem;
            position: relative;
            z-index: 10;
            padding: 0 5%;
        }

        .m-about-figures {
            background: var(--bordeaux, #922b55);
            max-width: 1400px;
            margin: 0 auto;
            border-radius: 20px;
            padding: 5rem;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 4rem;
            box-shadow: 0 30px 60px rgba(146, 43, 85, 0.2);
        }

        .m-fig-box {
            text-align: center;
            color: #fff;
        }

        .m-fig-num {
            font-size: 4.5rem;
            font-weight: 200;
            display: block;
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .m-fig-text {
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
            opacity: 0.8;
        }

        /* Management Team (Reveal Grid) */
        .m-about-team {
            padding: 10rem 5%;
            background: #f8f9fa;
        }

        .m-team-header {
            text-align: center;
            margin-bottom: 6rem;
        }

        .m-team-header h2 {
            font-size: 4rem;
            color: var(--blue-dark, #1d4e79);
            font-weight: 200;
            letter-spacing: -1px;
        }

        .m-team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .m-team-card {
            background: #fff;
            border-radius: 16px;
            padding: 3rem 2rem;
            text-align: center;
            border: 1px solid #eaeaea;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .m-team-card::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0;
            background: var(--blue-dark, #1d4e79);
            transition: height 0.4s ease;
            z-index: 0;
        }

        .m-team-card:hover {
            transform: translateY(-10px);
            border-color: transparent;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        .m-team-card:hover::before {
            height: 100%;
        }

        .m-team-content {
            position: relative;
            z-index: 2;
            transition: color 0.4s ease;
        }

        .m-team-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--blue-dark, #1d4e79);
            margin-bottom: 0.5rem;
            transition: color 0.4s ease;
        }

        .m-team-role {
            font-size: 1rem;
            color: var(--bordeaux, #922b55);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: color 0.4s ease;
        }

        .m-team-subrole {
            font-size: 0.9rem;
            color: #777;
            margin-top: 0.5rem;
            transition: color 0.4s ease;
        }

        .m-team-card:hover .m-team-name,
        .m-team-card:hover .m-team-subrole {
            color: #fff;
        }

        .m-team-card:hover .m-team-role {
            color: #83a5c2;
        }

        /* Vision Split */
        .m-about-vision {
            display: flex;
            min-height: 600px;
        }

        .m-vision-img {
            flex: 1;
            background: url('<?php echo esc_url(get_option('munisys_about_vision_main_img', 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2000&auto=format&fit=crop')); ?>') center/cover;
            position: relative;
        }

        .m-vision-text {
            flex: 1;
            background: var(--blue-dark, #1d4e79);
            padding: 8rem 8%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #fff;
        }

        .m-vision-text h2 {
            font-size: 4rem;
            font-weight: 200;
            margin-bottom: 3rem;
            letter-spacing: -1px;
        }

        .m-vision-text p {
            font-size: 1.35rem;
            line-height: 1.8;
            font-weight: 300;
            margin-bottom: 1.5rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Values (Asymmetric Bento Grid) */
        .m-about-values {
            padding: 10rem 5%;
            background: #fff;
        }

        .m-val-header {
            text-align: center;
            margin-bottom: 6rem;
        }

        .m-val-header h2 {
            font-size: 4rem;
            color: var(--blue-dark, #1d4e79);
            font-weight: 200;
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .m-val-header p {
            font-size: 1.3rem;
            color: #666;
            font-weight: 300;
        }

        .m-bento-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: 250px;
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .m-bento-card {
            background: #f8f9fa;
            border-radius: 24px;
            padding: 3rem;
            display: flex;
            align-items: flex-end;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            border: 1px solid #eaeaea;
        }

        .m-bento-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
            background: #fff;
        }

        /* Asymmetry */
        .m-val-1 {
            grid-column: span 2;
            grid-row: span 2;
            background: var(--blue-dark, #1d4e79);
            color: #fff;
        }

        .m-val-2 {
            grid-column: span 2;
            grid-row: span 1;
        }

        .m-val-3 {
            grid-column: span 1;
            grid-row: span 1;
        }

        .m-val-4 {
            grid-column: span 1;
            grid-row: span 1;
        }

        .m-val-5 {
            grid-column: span 2;
            grid-row: span 1;
            background: var(--bordeaux, #922b55);
            color: #fff;
        }

        .m-val-6 {
            grid-column: span 2;
            grid-row: span 1;
        }

        .m-bento-card h3 {
            font-size: 2rem;
            font-weight: 300;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .m-val-1 h3 {
            font-size: 3.5rem;
        }

        /* Number Watermarks in Bento */
        .m-bento-num {
            position: absolute;
            top: 1rem;
            right: 2rem;
            font-size: 8rem;
            font-weight: 900;
            opacity: 0.05;
            line-height: 1;
            z-index: 1;
            transition: transform 0.4s ease;
        }

        .m-bento-card:hover .m-bento-num {
            transform: scale(1.1) rotate(5deg);
            opacity: 0.08;
        }

        .m-val-1 .m-bento-num,
        .m-val-5 .m-bento-num {
            opacity: 0.1;
            color: #fff;
        }

        @media(max-width: 1200px) {
            .m-history-grid {
                grid-template-columns: 1fr;
                gap: 4rem;
            }

            .m-history-images {
                min-height: 600px;
            }

            .m-bento-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-auto-rows: auto;
                min-height: 250px;
            }

            .m-val-1,
            .m-val-2,
            .m-val-3,
            .m-val-4,
            .m-val-5,
            .m-val-6 {
                grid-column: span 2;
                grid-row: span 1;
                min-height: 250px;
            }
        }

        @media(max-width: 768px) {
            .m-about-hero-content h1 {
                font-size: 4rem;
            }

            .m-about-vision {
                flex-direction: column;
            }

            .m-vision-img {
                min-height: 400px;
            }

            .m-history-img-1 {
                width: 100%;
                height: 300px;
            }

            .m-history-img-2 {
                width: 80%;
                height: 250px;
                bottom: -30px;
            }

            .m-about-figures {
                padding: 3rem 2rem;
                gap: 3rem;
            }

            .m-fig-num {
                font-size: 3.5rem;
            }
        }
    </style>

    <div class="m-about-page">
        <!-- 1. Immersive Hero -->
        <div class="m-about-hero-wrap">
            <img src="<?php echo esc_url(get_option('munisys_about_hero_img', 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2560&auto=format&fit=crop')); ?>"
                class="m-about-hero-bg" alt="Munisys Office">
            <div class="m-about-hero-content m-reveal-left">
                <h1><?php echo esc_html(get_option('munisys_about_hero_title', 'About Munisys')); ?></h1>
                <p><?php echo esc_html(get_option('munisys_about_hero_desc', 'Orchestrating digital infrastructure technologies for a unique customer experience since 1991.')); ?>
                </p>
            </div>
        </div>

        <!-- 2. Figures (Elevated Overlap) -->
        <div class="m-about-figures-wrap" id="m-about-stats">
            <div class="m-about-figures">
                <div class="m-fig-box m-reveal m-delay-1">
                    <span class="m-fig-num abt-counter" data-target="34">0</span>
                    <span class="m-fig-text">Years of Ex.</span>
                </div>
                <div class="m-fig-box m-reveal m-delay-2">
                    <span class="m-fig-num abt-counter" data-target="330">0</span>
                    <span class="m-fig-text">Employees</span>
                </div>
                <div class="m-fig-box m-reveal m-delay-3">
                    <span class="m-fig-num abt-counter" data-target="700">0</span>
                    <span class="m-fig-text">MMAD Revenue</span>
                </div>
                <div class="m-fig-box m-reveal m-delay-4">
                    <span class="m-fig-num abt-counter" data-target="18">0</span>
                    <span class="m-fig-text">Service Centers</span>
                </div>
            </div>
        </div>

        <!-- 3. History Timeline -->
        <div class="m-about-history-sec">
            <div class="m-history-watermark">1991</div>
            <div class="m-history-grid">
                <div class="m-history-text m-reveal-left">
                    <h2><?php echo esc_html(get_option('munisys_about_history_title', 'Our Journey')); ?></h2>
                    <p><?php echo esc_html(get_option('munisys_about_history_desc1', 'Our journey began in 1991, when the local subsidiary of a US IT leader became part of a privately-owned Moroccan group of enterprises. This is when our name, Munisys, was coined.')); ?>
                    </p>
                    <p><?php echo esc_html(get_option('munisys_about_history_desc2', 'From 35 people and a yearly revenue of 60 million MAD at the onset, we steadily grew by seizing partnership opportunities to extend our offer and by building solid human and skills foundations, reaching over 200 people and 500 million MAD in 2022.')); ?>
                    </p>

                    <div class="m-history-quote">
                        <?php echo esc_html(get_option('munisys_about_history_quote', 'We are an IT infrastructure solutions integrator, always keeping pace with the fast evolving progress in the digital realm.')); ?>
                    </div>
                </div>
                <div class="m-history-images m-reveal m-delay-2">
                    <img src="<?php echo esc_url(get_option('munisys_about_vision_img1', 'https://images.unsplash.com/photo-1551434678-e076c223a692?q=80&w=2000&auto=format&fit=crop')); ?>"
                        class="m-history-img-1" alt="History 1">
                    <img src="<?php echo esc_url(get_option('munisys_about_vision_img2', 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?q=80&w=2000&auto=format&fit=crop')); ?>"
                        class="m-history-img-2" alt="History 2">
                </div>
            </div>
        </div>

        <!-- 4. Management Team Reveal Grid -->
        <div class="m-about-team">
            <div class="m-team-header m-reveal">
                <h2>Leadership</h2>
            </div>
            <div class="m-team-grid">
                <!-- Team Cards -->
                <div class="m-team-card m-reveal m-delay-1">
                    <div class="m-team-content">
                        <div class="m-team-name">Younes Peter AMINE</div>
                        <div class="m-team-role">CEO</div>
                    </div>
                </div>
                <div class="m-team-card m-reveal m-delay-2">
                    <div class="m-team-content">
                        <div class="m-team-name">Said OUDGHIRI</div>
                        <div class="m-team-role">Managing Director</div>
                    </div>
                </div>
                <div class="m-team-card m-reveal m-delay-3">
                    <div class="m-team-content">
                        <div class="m-team-name">Mohammed MARRAKCHI</div>
                        <div class="m-team-role">CTO</div>
                        <div class="m-team-subrole">Deputy Managing Director</div>
                    </div>
                </div>
                <div class="m-team-card">
                    <div class="m-team-content">
                        <div class="m-team-name">Khalid ROQAI CHAOUI</div>
                        <div class="m-team-role">COO</div>
                        <div class="m-team-subrole">Deputy Managing Director</div>
                    </div>
                </div>
                <div class="m-team-card">
                    <div class="m-team-content">
                        <div class="m-team-name">Abdellatif RHARIB</div>
                        <div class="m-team-role">CFO</div>
                    </div>
                </div>
                <div class="m-team-card">
                    <div class="m-team-content">
                        <div class="m-team-name">Mounir BELAOUCHI</div>
                        <div class="m-team-role">BU Director</div>
                        <div class="m-team-subrole">Infrastructure software</div>
                    </div>
                </div>
                <div class="m-team-card">
                    <div class="m-team-content">
                        <div class="m-team-name">Tarik BENCHEKROUN</div>
                        <div class="m-team-role">BU Director</div>
                        <div class="m-team-subrole">Cybersecurity</div>
                    </div>
                </div>
                <div class="m-team-card">
                    <div class="m-team-content">
                        <div class="m-team-name">Arrabab BRIK</div>
                        <div class="m-team-role">BU Director</div>
                        <div class="m-team-subrole">Managed & Cloud Services</div>
                    </div>
                </div>
                <div class="m-team-card">
                    <div class="m-team-content">
                        <div class="m-team-name">Mohamed KARCHE</div>
                        <div class="m-team-role">Director</div>
                        <div class="m-team-subrole">Customer service</div>
                    </div>
                </div>
                <div class="m-team-card">
                    <div class="m-team-content">
                        <div class="m-team-name">Mohamed Amine KHAOULANI</div>
                        <div class="m-team-role">BU Director</div>
                        <div class="m-team-subrole">Core Infrastructure</div>
                    </div>
                </div>
                <div class="m-team-card">
                    <div class="m-team-content">
                        <div class="m-team-name">Fatima Zahra SABER</div>
                        <div class="m-team-role">BU Director</div>
                        <div class="m-team-subrole">Endpoint solutions</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Vision Split -->
        <div class="m-about-vision">
            <div class="m-vision-img"></div>
            <div class="m-vision-text">
                <h2><?php echo esc_html(get_option('munisys_about_vision_title', 'Our Vision')); ?></h2>
                <p><?php echo esc_html(get_option('munisys_about_vision_desc1', 'We strive for strengthening and extending our position as a leading player in mastering and orchestrating digital infrastructure technologies, to serve private as well as public enterprises and organizations.')); ?>
                </p>
                <p><?php echo esc_html(get_option('munisys_about_vision_desc2', 'While working on reinforcing digitization as a leverage for innovation, growth and competitiveness of the regional economy, we make sure to be socially and environmentally responsible every step of the way.')); ?>
                </p>
            </div>
        </div>

        <!-- 6. Values Bento Grid -->
        <div class="m-about-values">
            <div class="m-val-header">
                <h2>Core Values</h2>
                <p>Values define culture, culture shapes actions & allows performance.</p>
            </div>

            <div class="m-bento-grid">
                <div class="m-bento-card m-val-1">
                    <span class="m-bento-num">01</span>
                    <h3>We care for people</h3>
                </div>
                <div class="m-bento-card m-val-2">
                    <span class="m-bento-num">02</span>
                    <h3>We act with integrity</h3>
                </div>
                <div class="m-bento-card m-val-3">
                    <span class="m-bento-num">03</span>
                    <h3 style="color:var(--blue-dark);">Excel at what we do</h3>
                </div>
                <div class="m-bento-card m-val-4">
                    <span class="m-bento-num">04</span>
                    <h3 style="color:var(--blue-dark);">Drive innovation</h3>
                </div>
                <div class="m-bento-card m-val-5">
                    <span class="m-bento-num">05</span>
                    <h3>Commit to customers</h3>
                </div>
                <div class="m-bento-card m-val-6">
                    <span class="m-bento-num">06</span>
                    <h3 style="color:var(--blue-dark);">We succeed together</h3>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Animation des compteurs pour la page About Us
            const counters = document.querySelectorAll('.abt-counter');
            const speed = 50;

            const animateCounters = () => {
                counters.forEach(counter => {
                    const updateCount = () => {
                        const target = +counter.getAttribute('data-target');
                        const count = +counter.innerText;
                        const inc = target / speed;
                        if (count < target) {
                            counter.innerText = Math.ceil(count + inc);
                            setTimeout(updateCount, 30);
                        } else {
                            counter.innerText = target;
                        }
                    };
                    updateCount();
                });
            };

            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    animateCounters();
                    observer.disconnect();
                }
            }, { threshold: 0.5 });

            const statsSection = document.getElementById('m-about-stats');
            if (statsSection) {
                observer.observe(statsSection);
            }
        });
    </script>
    <?php
    return ob_get_clean();
}

// ==========================================
// 6. SHORTCODE [munisys_technology_partners]
// ==========================================
add_shortcode('munisys_technology_partners', 'render_munisys_technology_partners_shortcode');
function render_munisys_technology_partners_shortcode()
{
    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;700;900&display=swap');

        #munisys-tp-wrapper {
            --bordeaux: #922b55;
            --blue-dark: #1d4e79;
            --blue-light: #83a5c2;
            --text-dark: #111827;
            --text-gray: #6b7280;
            --bg-gray: #f3f4f6;
            font-family: 'Montserrat', sans-serif !important;
            background: #ffffff !important;
            width: 100% !important;
            overflow-x: hidden !important;
            -webkit-font-smoothing: antialiased !important;
        }

        #munisys-tp-wrapper *,
        #munisys-tp-wrapper *::before,
        #munisys-tp-wrapper *::after {
            box-sizing: border-box !important;
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        #munisys-tp-wrapper .tp-hero {
            padding: 8rem 5% 4rem !important;
            text-align: center !important;
            background: var(--bg-gray) !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        #munisys-tp-wrapper .tp-hero-content {
            max-width: 900px !important;
            margin: 0 auto !important;
        }

        #munisys-tp-wrapper .tp-hero h1 {
            font-size: clamp(3rem, 5vw, 5rem) !important;
            font-weight: 200 !important;
            color: var(--blue-dark) !important;
            margin-bottom: 1.5rem !important;
            letter-spacing: -2px !important;
        }

        #munisys-tp-wrapper .tp-hero h1 strong {
            font-weight: 800 !important;
            color: var(--bordeaux) !important;
        }

        #munisys-tp-wrapper .tp-hero p {
            font-size: 1.25rem !important;
            color: var(--text-gray) !important;
            max-width: 800px !important;
            margin: 0 auto !important;
            line-height: 1.8 !important;
            font-weight: 300 !important;
        }

        #munisys-tp-wrapper .tp-grid-sec {
            padding: 4rem 5% 10rem !important;
            max-width: 1440px !important;
            margin: 0 auto !important;
        }

        /* Filter Buttons */
        #munisys-tp-wrapper .tp-filters {
            display: flex !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
            gap: 1rem !important;
            margin-bottom: 3rem !important;
        }

        #munisys-tp-wrapper .tp-filter-btn {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: var(--text-gray) !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 30px !important;
            font-size: 0.85rem !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02) !important;
        }

        #munisys-tp-wrapper .tp-filter-btn:hover {
            border-color: var(--blue-light) !important;
            color: var(--blue-dark) !important;
        }

        #munisys-tp-wrapper .tp-filter-btn.active {
            background: var(--blue-dark) !important;
            color: #ffffff !important;
            border-color: var(--blue-dark) !important;
            box-shadow: 0 5px 15px rgba(29, 78, 121, 0.3) !important;
        }

        #munisys-tp-wrapper .tp-grid {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)) !important;
            gap: 2rem !important;
        }

        #munisys-tp-wrapper .tp-card {
            background: #ffffff !important;
            border: 1px solid #f3f4f6 !important;
            border-radius: 16px !important;
            padding: 3rem 2rem 2.5rem !important;
            text-align: center !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: space-between !important;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
            cursor: pointer !important;
            min-height: 240px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.01) !important;
            text-decoration: none !important;
            position: relative !important;
            overflow: hidden !important;
        }

        #munisys-tp-wrapper .tp-card.hidden {
            display: none !important;
        }

        #munisys-tp-wrapper .tp-card::after {
            content: '' !important;
            position: absolute !important;
            bottom: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 4px !important;
            background: var(--blue-light) !important;
            transform: scaleX(0) !important;
            transform-origin: right !important;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        #munisys-tp-wrapper .tp-card:hover {
            transform: translateY(-10px) !important;
            box-shadow: 0 20px 40px -5px rgba(0, 0, 0, 0.08), 0 10px 20px -5px rgba(0, 0, 0, 0.04) !important;
            border-color: #e5e7eb !important;
        }

        #munisys-tp-wrapper .tp-card:hover::after {
            transform: scaleX(1) !important;
            transform-origin: left !important;
            background: var(--bordeaux) !important;
        }

        #munisys-tp-wrapper .tp-logo-img {
            height: 60px !important;
            width: auto !important;
            max-width: 180px !important;
            object-fit: contain !important;
            margin: auto 0 !important;
            transition: all 0.4s ease !important;
        }

        #munisys-tp-wrapper .tp-logo-text {
            font-size: 2rem !important;
            font-weight: 900 !important;
            margin: auto 0 !important;
        }

        #munisys-tp-wrapper .tp-card:hover .tp-logo-img {
            transform: scale(1.08) !important;
        }

        #munisys-tp-wrapper .tp-cat {
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            color: #9ca3af !important;
            text-transform: uppercase !important;
            letter-spacing: 2px !important;
            margin-top: 2rem !important;
            transition: color 0.4s ease !important;
        }

        #munisys-tp-wrapper .tp-card:hover .tp-cat {
            color: var(--blue-dark) !important;
        }
    </style>
    <div id="munisys-tp-wrapper">
        <section class="tp-hero">
            <div class="tp-hero-content">
                <h1><?php echo wp_kses_post(get_option('munisys_partners_hero_title', 'Technology <strong>Partners</strong>')); ?>
                </h1>
                <p><?php echo esc_html(get_option('munisys_partners_hero_desc', 'We build solid ties with the world\'s leading technology providers to ensure true resilience, performance, and agility for your digital infrastructure.')); ?>
                </p>
            </div>
        </section>

        <section class="tp-grid-sec">

            <div class="tp-filters">
                <button class="tp-filter-btn active" data-filter="all">All Specialties</button>
                <button class="tp-filter-btn" data-filter="Application Security">Application Security</button>
                <button class="tp-filter-btn" data-filter="Cloud & Database">Cloud & Database</button>
                <button class="tp-filter-btn" data-filter="Cloud Computing">Cloud Computing</button>
                <button class="tp-filter-btn" data-filter="Cybersecurity">Cybersecurity</button>
                <button class="tp-filter-btn" data-filter="Data Management">Data Management</button>
                <button class="tp-filter-btn" data-filter="Data Resilience">Data Resilience</button>
                <button class="tp-filter-btn" data-filter="Digital Workplace">Digital Workplace</button>
                <button class="tp-filter-btn" data-filter="Endpoints & Printing">Endpoints & Printing</button>
                <button class="tp-filter-btn" data-filter="Infrastructure">Infrastructure</button>
                <button class="tp-filter-btn" data-filter="Infrastructure & Cloud">Infrastructure & Cloud</button>
                <button class="tp-filter-btn" data-filter="Networking & Security">Networking & Security</button>
                <button class="tp-filter-btn" data-filter="Software & Cloud">Software & Cloud</button>
                <button class="tp-filter-btn" data-filter="Virtualization">Virtualization</button>
            </div>

            <div class="tp-grid">

                <a href="#" class="tp-card" data-category="Networking & Security">
                    <svg class="tp-logo-img" style="fill: #049fd9;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>Cisco</title>
                        <path
                            d="M16.331 18.171V17.06l-.022.01c-.25.121-.522.19-.801.203a1.186 1.186 0 01-.806-.237 1.038 1.038 0 01-.352-.498 1.21 1.21 0 01-.023-.667c.052-.225.178-.426.357-.569.16-.134.355-.218.562-.242a1.85 1.85 0 011.061.198l.024.013v-1.117l-.051-.014a2.862 2.862 0 00-1.011-.132 2.34 2.34 0 00-.903.206c-.287.132-.54.327-.739.571a2.221 2.221 0 00-.04 2.705c.295.378.709.645 1.175.756.491.12 1.006.102 1.487-.052l.082-.023M5.336 18.171V17.06l-.022.01c-.25.121-.522.19-.801.203a1.183 1.183 0 01-.806-.237 1.03 1.03 0 01-.351-.498 1.202 1.202 0 01-.024-.667c.052-.225.177-.426.357-.569.16-.134.355-.218.562-.242a1.85 1.85 0 011.061.198l.024.013v-1.117l-.051-.014a2.862 2.862 0 00-1.011-.132 2.344 2.344 0 00-.903.206 2.08 2.08 0 00-.74.571 2.224 2.224 0 00-.041 2.705 2.11 2.11 0 001.176.756c.491.12 1.005.102 1.487-.052l.083-.023M9.26 17.249l-.004.957.07.012c.22.041.441.069.664.085.195.019.391.022.587.012.187-.014.372-.049.551-.104.21-.06.405-.163.571-.305a1.16 1.16 0 00.333-.478 1.31 1.31 0 00-.007-.96 1.068 1.068 0 00-.298-.414 1.261 1.261 0 00-.438-.255l-.722-.268a.388.388 0 01-.197-.188.245.245 0 01.008-.219.382.382 0 01.154-.142.798.798 0 01.257-.074c.153-.022.308-.021.46.005.18.02.358.051.533.096l.038.008v-.883l-.069-.015a4.749 4.749 0 00-.543-.097 2.844 2.844 0 00-.714-.003c-.3.027-.585.143-.821.33-.16.126-.281.293-.351.484-.104.29-.105.608 0 .899.054.145.14.274.252.381.097.093.207.173.327.236.157.084.324.149.497.195.057.017.114.035.17.054l.085.031.024.01c.084.03.162.078.226.14.045.042.08.094.101.151a.325.325 0 01.001.161.339.339 0 01-.166.198.856.856 0 01-.275.086 2.032 2.032 0 01-.427.021 5.208 5.208 0 01-.557-.074 9.195 9.195 0 01-.287-.067l-.033-.006zm-2.475.995h1.05v-4.167h-1.05v4.167zm12.162-2.936a1.095 1.095 0 011.541.158 1.094 1.094 0 01-.157 1.541l-.017.014a1.096 1.096 0 01-1.367-1.713m-1.525.854a2.193 2.193 0 002.666 2.107 2.139 2.139 0 00.701-3.937 2.207 2.207 0 00-3.367 1.83M22.961 10.728a.52.52 0 001.039 0V9.573a.52.52 0 00-1.039 0v1.155M20.117 10.728a.522.522 0 001.041 0V8.139a.521.521 0 00-1.04 0v2.589M17.231 11.771a.521.521 0 001.039 0V6.17a.52.52 0 00-1.039 0v5.601M14.393 10.728a.521.521 0 001.04 0V8.139a.52.52 0 00-1.039 0v2.589M11.494 10.728a.522.522 0 001.039 0V9.573a.52.52 0 00-1.039 0v1.155M8.624 10.728a.52.52 0 001.039 0V8.139a.52.52 0 00-1.039 0v2.589M5.737 11.771a.52.52 0 001.039 0V6.17a.52.52 0 00-1.039 0v5.601M2.876 10.728a.522.522 0 001.04 0V8.139a.52.52 0 00-1.039 0v2.589M0 10.728a.521.521 0 001.039 0V9.573a.52.52 0 00-1.039 0v1.155" />
                    </svg>
                    <div class="tp-cat">Networking & Security</div>
                </a>
                <a href="#" class="tp-card" data-category="Cloud & Database">
                    <svg class="tp-logo-img" style="fill: #f80000;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>Oracle</title>
                        <path
                            d="M16.412 4.412h-8.82a7.588 7.588 0 0 0-.008 15.176h8.828a7.588 7.588 0 0 0 0-15.176zm-.193 12.502H7.786a4.915 4.915 0 0 1 0-9.828h8.433a4.914 4.914 0 1 1 0 9.828z" />
                    </svg>
                    <div class="tp-cat">Cloud & Database</div>
                </a>
                <a href="#" class="tp-card" data-category="Software & Cloud">
                    <svg class="tp-logo-img" style="fill: #00a4ef;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>Microsoft</title>
                        <path
                            d="M0 0v11.408h11.408V0zm12.594 0v11.408H24V0zM0 12.594V24h11.408V12.594zm12.594 0V24H24V12.594z" />
                    </svg>
                    <div class="tp-cat">Software & Cloud</div>
                </a>
                <a href="#" class="tp-card" data-category="Infrastructure">
                    <svg class="tp-logo-img" style="fill: #007db8;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>Dell</title>
                        <path
                            d="M17.963 14.6V9.324h1.222v4.204h2.14v1.07h-3.362zm-9.784-3.288l2.98-2.292c.281.228.56.458.841.687l-2.827 2.14.611.535 2.827-2.216c.281.228.56.458.841.688a295.83 295.83 0 0 1-2.827 2.216l.61.536 2.83-2.295-.001-1.986h1.223v4.204h2.216v1.07h-3.362v-1.987c-.995.763-1.987 1.529-2.981 2.292l-2.981-2.292c-.144.729-.653 1.36-1.312 1.694-.285.147-.597.24-.915.276-.183.022-.367.017-.551.017H3.516V9.325H5.69a2.544 2.544 0 0 1 1.563.557c.454.36.778.872.927 1.43m-3.516-.917v3.21l.953-.001a1.377 1.377 0 0 0 1.036-.523 1.74 1.74 0 0 0 .182-1.889 1.494 1.494 0 0 0-.976-.766c-.166-.04-.338-.03-.507-.032h-.688zM11.82 0h.337a11.94 11.94 0 0 1 5.405 1.373 12.101 12.101 0 0 1 4.126 3.557A11.93 11.93 0 0 1 24 11.82v.36a11.963 11.963 0 0 1-3.236 8.033A11.967 11.967 0 0 1 12.182 24h-.361a11.993 11.993 0 0 1-4.145-.806 12.04 12.04 0 0 1-4.274-2.836A12.057 12.057 0 0 1 .576 15.67 12.006 12.006 0 0 1 0 12.181v-.361a11.924 11.924 0 0 1 1.992-6.396 12.211 12.211 0 0 1 4.71-4.172A11.875 11.875 0 0 1 11.82 0m-.153 1.23a10.724 10.724 0 0 0-6.43 2.375 10.78 10.78 0 0 0-3.319 4.573 10.858 10.858 0 0 0 .193 8.12 10.788 10.788 0 0 0 3.546 4.421 10.698 10.698 0 0 0 4.786 1.946c1.456.209 2.955.124 4.376-.26a10.756 10.756 0 0 0 5.075-3.062 10.742 10.742 0 0 0 2.686-5.28 10.915 10.915 0 0 0-.122-4.682 10.77 10.77 0 0 0-7.098-7.626 10.78 10.78 0 0 0-3.693-.525z" />
                    </svg>
                    <div class="tp-cat">Infrastructure</div>
                </a>
                <a href="#" class="tp-card" data-category="Cloud Computing">
                    <svg class="tp-logo-img" style="fill: #232f3e;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>Amazon AWS</title>
                        <path
                            d="M6.763 10.036c0 .296.032.535.088.71.064.176.144.368.256.576.04.063.056.127.056.183 0 .08-.048.16-.152.24l-.503.335a.383.383 0 0 1-.208.072c-.08 0-.16-.04-.239-.112a2.47 2.47 0 0 1-.287-.375 6.18 6.18 0 0 1-.248-.471c-.622.734-1.405 1.101-2.347 1.101-.67 0-1.205-.191-1.596-.574-.391-.384-.59-.894-.59-1.533 0-.678.239-1.23.726-1.644.487-.415 1.133-.623 1.955-.623.272 0 .551.024.846.064.296.04.6.104.918.176v-.583c0-.607-.127-1.03-.375-1.277-.255-.248-.686-.367-1.3-.367-.28 0-.568.031-.863.103-.295.072-.583.16-.862.272a2.287 2.287 0 0 1-.28.104.488.488 0 0 1-.127.023c-.112 0-.168-.08-.168-.247v-.391c0-.128.016-.224.056-.28a.597.597 0 0 1 .224-.167c.279-.144.614-.264 1.005-.36a4.84 4.84 0 0 1 1.246-.151c.95 0 1.644.216 2.091.647.439.43.662 1.085.662 1.963v2.586zm-3.24 1.214c.263 0 .534-.048.822-.144.287-.096.543-.271.758-.51.128-.152.224-.32.272-.512.047-.191.08-.423.08-.694v-.335a6.66 6.66 0 0 0-.735-.136 6.02 6.02 0 0 0-.75-.048c-.535 0-.926.104-1.19.32-.263.215-.39.518-.39.917 0 .375.095.655.295.846.191.2.47.296.838.296zm6.41.862c-.144 0-.24-.024-.304-.08-.064-.048-.12-.16-.168-.311L7.586 5.55a1.398 1.398 0 0 1-.072-.32c0-.128.064-.2.191-.2h.783c.151 0 .255.025.31.08.065.048.113.16.16.312l1.342 5.284 1.245-5.284c.04-.16.088-.264.151-.312a.549.549 0 0 1 .32-.08h.638c.152 0 .256.025.32.08.063.048.12.16.151.312l1.261 5.348 1.381-5.348c.048-.16.104-.264.16-.312a.52.52 0 0 1 .311-.08h.743c.127 0 .2.065.2.2 0 .04-.009.08-.017.128a1.137 1.137 0 0 1-.056.2l-1.923 6.17c-.048.16-.104.263-.168.311a.51.51 0 0 1-.303.08h-.687c-.151 0-.255-.024-.32-.08-.063-.056-.119-.16-.15-.32l-1.238-5.148-1.23 5.14c-.04.16-.087.264-.15.32-.065.056-.177.08-.32.08zm10.256.215c-.415 0-.83-.048-1.229-.143-.399-.096-.71-.2-.918-.32-.128-.071-.215-.151-.247-.223a.563.563 0 0 1-.048-.224v-.407c0-.167.064-.247.183-.247.048 0 .096.008.144.024.048.016.12.048.2.08.271.12.566.215.878.279.319.064.63.096.95.096.502 0 .894-.088 1.165-.264a.86.86 0 0 0 .415-.758.777.777 0 0 0-.215-.559c-.144-.151-.416-.287-.807-.415l-1.157-.36c-.583-.183-1.014-.454-1.277-.813a1.902 1.902 0 0 1-.4-1.158c0-.335.073-.63.216-.886.144-.255.335-.479.575-.654.24-.184.51-.32.83-.415.32-.096.655-.136 1.006-.136.175 0 .359.008.535.032.183.024.35.056.518.088.16.04.312.08.455.127.144.048.256.096.336.144a.69.69 0 0 1 .24.2.43.43 0 0 1 .071.263v.375c0 .168-.064.256-.184.256a.83.83 0 0 1-.303-.096 3.652 3.652 0 0 0-1.532-.311c-.455 0-.815.071-1.062.223-.248.152-.375.383-.375.71 0 .224.08.416.24.567.159.152.454.304.877.44l1.134.358c.574.184.99.44 1.237.767.247.327.367.702.367 1.117 0 .343-.072.655-.207.926-.144.272-.336.511-.583.703-.248.2-.543.343-.886.447-.36.111-.734.167-1.142.167zM21.698 16.207c-2.626 1.94-6.442 2.969-9.722 2.969-4.598 0-8.74-1.7-11.87-4.526-.247-.223-.024-.527.272-.351 3.384 1.963 7.559 3.153 11.877 3.153 2.914 0 6.114-.607 9.06-1.852.439-.2.814.287.383.607zM22.792 14.961c-.336-.43-2.22-.207-3.074-.103-.255.032-.295-.192-.063-.36 1.5-1.053 3.967-.75 4.254-.399.287.36-.08 2.826-1.485 4.007-.215.184-.423.088-.327-.151.32-.79 1.03-2.57.695-2.994z" />
                    </svg>
                    <div class="tp-cat">Cloud Computing</div>
                </a>
                <a href="#" class="tp-card" data-category="Cybersecurity">
                    <svg class="tp-logo-img" style="fill: #c81326;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>Fortinet</title>
                        <path
                            d="M0 9.785h6.788v4.454H0zm8.666-6.33h6.668v4.453H8.666zm0 12.637h6.668v4.454H8.666zm8.522-6.307H24v4.454h-6.812zM2.792 3.455C1.372 3.814.265 5.404 0 7.425v.506h6.788V3.454zM0 16.091v.554c.24 1.926 1.276 3.466 2.624 3.9h4.188v-4.454zm24-8.184v-.506c-.265-1.998-1.372-3.587-2.792-3.972h-4.02v4.454H24zM21.376 20.57c1.324-.458 2.36-1.974 2.624-3.9v-.554h-6.812v4.454Z" />
                    </svg>
                    <div class="tp-cat">Cybersecurity</div>
                </a>
                <a href="#" class="tp-card" data-category="Virtualization">
                    <svg class="tp-logo-img" style="fill: #0972b5;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>VMware</title>
                        <path
                            d="M.5 10.1a.505.505 0 00-.197.048.497.497 0 00-.25.68l1.138 2.475c.179.38.38.592.721.592.342 0 .542-.22.72-.592l1.003-2.186a.144.144 0 01.144-.092.16.16 0 01.157.16v2.118a.535.535 0 101.066 0v-1.73a.531.531 0 01.566-.552.52.52 0 01.541.551v1.73a.531.531 0 00.53.593.539.539 0 00.535-.592v-1.73a.531.531 0 01.564-.552.52.52 0 01.543.551v1.73a.531.531 0 00.528.593.535.535 0 00.535-.592v-1.969a1.234 1.234 0 00-1.283-1.23 1.647 1.647 0 00-1.14.486 1.26 1.26 0 00-1.095-.483 1.807 1.807 0 00-1.074.483 1.287 1.287 0 00-.961-.483 1.177 1.177 0 00-1.158.786l-.729 1.716-.933-2.203.011-.004A.505.505 0 00.5 10.1zm18.43.06a.27.27 0 00-.266.274h.002v3.142a.27.27 0 10.535 0v-1.222c0-1.037.571-1.56 1.27-1.643a.266.266 0 00.238-.274.258.258 0 00-.266-.269 1.465 1.465 0 00-1.242.88v-.614a.266.266 0 00-.271-.274zm-6.735.008a.273.273 0 00-.25.217l-.912 2.627-.902-2.62a.28.28 0 00-.274-.22.266.266 0 00-.27.258.493.493 0 00.034.144l1.09 3.037.02-.007a.319.319 0 00.298.242.3.3 0 00.293-.242l.903-2.583.896 2.583a.3.3 0 00.293.242h.018a.319.319 0 00.293-.242l1.097-3.038a.512.512 0 00.033-.144.258.258 0 00-.265-.25.262.262 0 00-.258.209l-.918 2.63-.904-2.626a.285.285 0 00-.278-.217h-.025a.273.273 0 00-.012 0zm10.168.008a1.75 1.75 0 00-1.691 1.851 1.765 1.765 0 001.76 1.858l-.008.013a1.784 1.784 0 001.33-.539.228.228 0 00.082-.17.228.228 0 00-.379-.168 1.435 1.435 0 01-1.018.415 1.237 1.237 0 01-1.24-1.207h2.555a.247.247 0 00.246-.247c0-.945-.593-1.806-1.637-1.806zm-5.744.002a1.571 1.571 0 00-.158.006 2.384 2.384 0 00-1.078.205.22.22 0 00-.143.222.24.24 0 00.235.229.266.266 0 00.095-.024 1.822 1.822 0 01.834-.162c.691 0 1.07.334 1.07.979v.125a3.796 3.796 0 00-1.103-.15c-.892 0-1.52.4-1.52 1.16l-.003-.004c0 .736.671 1.117 1.34 1.117a1.575 1.575 0 001.298-.62v.343a.247.247 0 00.254.25.254.254 0 00.258-.262v-1.983a1.416 1.416 0 00-.379-1.046 1.571 1.571 0 00-1-.385zm5.719.43c.714 0 1.085.565 1.139 1.214h-2.278a1.222 1.222 0 011.139-1.215zm-5.885 1.382a3.75 3.75 0 011.057.153V12.49c0 .57-.539.973-1.2.973-.485 0-.904-.261-.904-.713 0-.467.375-.76 1.047-.76Z" />
                    </svg>
                    <div class="tp-cat">Virtualization</div>
                </a>
                <a href="#" class="tp-card" data-category="Cybersecurity">
                    <svg class="tp-logo-img" style="fill: #f04e23;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>Palo Alto Networks</title>
                        <path
                            d="m10.278 15.443 1.705 1.705-3.426 3.426-3.427-3.426 8.592-8.591-1.705-1.705 3.426-3.426 3.427 3.426-8.592 8.591zM0 12.017l3.426 3.426 8.591-8.59-3.426-3.427L0 12.017zm11.983 5.13 3.426 3.427L24 11.983l-3.426-3.426-8.591 8.59z" />
                    </svg>
                    <div class="tp-cat">Cybersecurity</div>
                </a>
                <a href="#" class="tp-card" data-category="Data Resilience">
                    <svg class="tp-logo-img" style="fill: #00b336;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>Veeam</title>
                        <path
                            d="M6.63 9.842c-1.104 0-2 .945-2 2.101v.108c0 1.322.846 2.038 1.974 2.1.051.007.108.007.16.007.435 0 .794-.115 1.195-.361.04-.023.201-.132.361-.338.132-.166.138-.411 0-.543-.2-.189-.412-.086-.595.074-.292.246-.516.442-1.1.442-.55 0-1.064-.316-1.19-.94 0-.023.01-.045.034-.045H8.16a.473.473 0 0 0 .47-.504c0-1.156-.894-2.1-1.999-2.1zm4.466 0c-1.1 0-1.996.945-1.996 2.101v.108c0 1.322.846 2.038 1.974 2.1.052.007.11.007.16.007.435 0 .795-.115 1.196-.361.04-.023.2-.132.361-.338.132-.166.137-.411 0-.543-.2-.189-.413-.086-.596.074-.292.246-.515.442-1.1.442-.549 0-1.063-.316-1.189-.94-.006-.023.01-.045.028-.045h2.691a.473.473 0 0 0 .47-.504c-.007-1.156-.9-2.1-1.999-2.1zm-6.89.04a.4.4 0 0 0-.344.223l-1.42 3.063c-.034.063-.062.11-.136.11-.075 0-.105-.047-.14-.11l-1.42-3.057a.38.38 0 0 0-.53-.177.428.428 0 0 0-.172.56l1.39 2.828.16.32a.8.8 0 0 0 .712.46.791.791 0 0 0 .709-.46l.16-.32 1.385-2.834a.423.423 0 0 0-.166-.56.376.376 0 0 0-.188-.045zm11.362 0a.762.762 0 0 0-.504.188.808.808 0 0 0-.207.27l-1.59 3.137c0 .005-.007.006-.007.01v.007a.434.434 0 0 0 .03.442c0 .005.005.006.005.01 0 .007.006.005.006.01a.372.372 0 0 0 .516.077s.005 0 .005-.006a.083.083 0 0 0 .024-.017l.015-.018.006-.006a.485.485 0 0 0 .076-.103l.057-.125.332-.7c.006-.017.028-.027.045-.027h1.484c.195 0 .354-.167.354-.379 0-.206-.159-.377-.354-.377h-1.099a.033.033 0 0 1-.03-.046l.08-.172.602-1.207v-.006s0-.006.006-.006c.011-.023.04-.108.098-.125.034-.012.063-.012.086 0 .063.017.084.091.113.148l1.455 3.022a.393.393 0 0 0 .48.195s.006 0 .006-.006l.035-.017c.006 0 .007-.006.012-.006 0 0 .006 0 .006-.006.006 0 .006-.006.012-.006s.01-.004.01-.01c.006 0 .006-.006.011-.006.006-.006.012-.012.018-.012l.017-.017.006-.006a.408.408 0 0 0 .092-.498l-1.598-3.154a.79.79 0 0 0-.709-.45zm3.508 0c-.39 0-.709.332-.709.778v3.018c0 .183.104.354.264.406.263.086.486-.143.486-.412l-.058-2.938a.03.03 0 0 1 .052-.017l1.442 2.93c.137.28.367.449.625.449.257 0 .49-.167.623-.448l1.449-2.935c.01-.029.05-.019.05.016l-.056 2.955c0 .234.177.423.389.412.206-.006.367-.207.367-.43V10.66c.01-.452-.31-.77-.7-.777a.684.684 0 0 0-.61.394c-.361.739-1.426 2.93-1.426 2.936-.006.01-.03.064-.075.064-.04 0-.068-.047-.074-.064-.35-.727-1.047-2.164-1.408-2.908-.143-.287-.38-.422-.63-.422zm-12.45.647c.697 0 1.14.527 1.208 1.145 0 .023-.012.045-.035.045H5.47c-.023-.006-.033-.022-.033-.045.074-.607.49-1.145 1.19-1.145zm4.47 0h.002c.697.001 1.138.527 1.207 1.145 0 .023-.013.045-.035.045H9.94c-.023-.006-.04-.022-.034-.045.075-.606.493-1.144 1.19-1.145z" />
                    </svg>
                    <div class="tp-cat">Data Resilience</div>
                </a>
                <a href="#" class="tp-card" data-category="Data Management">
                    <svg class="tp-logo-img" style="fill: #b5121b;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>Veritas</title>
                        <path
                            d="M0 9.746l1.248 3.8712c.1053.3243.4072.5332.7344.5332a.7684.7684 0 00.7324-.5332l1.248-3.8711h-.6933L2.086 13.412a.1081.1081 0 01-.1035.0762c-.0467 0-.0881-.028-.1054-.0762L.6934 9.7461H0zm5.3125 0c-.6081 0-1.0996.4939-1.0996 1.1017v2.2011c0 .6078.4915 1.1016 1.0996 1.1016h2.0918v-.6621H5.3125a.439.439 0 01-.4395-.4395v-.7754h2.0918v-.6504H4.873v-.7753c0-.2431.1964-.4414.4395-.4414h2.0918V9.746H5.3125zm3.7598 0a.9898.9898 0 00-.9903.9903v3.414h.6602v-3.414c0-.1825.1475-.33.33-.33h1.4317a.5505.5505 0 01.5137.3535.55.55 0 01-.3164.7109l-1.1094.4082c-.4025.139-.615.578-.4766.9805a.7665.7665 0 00.4141.4531l2.0742.9414v-.7226l-1.8144-.8028a.1306.1306 0 01-.0703-.0781.1302.1302 0 01.082-.166l1.1367-.3965c.6243-.2396.9366-.9402.6973-1.5645-.1851-.4815-.6442-.7773-1.1309-.7773H9.0723zm3.3086 0v4.4044h.662V9.746h-.662zm1.2109 0v.6602h1.2773v3.7442h.6602v-3.7441h1.2773V9.746h-3.2148zm4.707 0a.7683.7683 0 00-.7324.5333l-1.25 3.871h.6953l1.1836-3.6679a.108.108 0 01.1035-.0762c.0467 0 .0882.0283.1055.0762l1.1816 3.668h.6954l-1.25-3.8711a.7702.7702 0 00-.7325-.5332zm3.334 0c-.6992 0-1.2656.5665-1.2656 1.2657 0 .6993.5664 1.2656 1.2656 1.2656l1.1016.002c.3344 0 .6054.2693.6054.6035a.6054.6054 0 01-.6054.6055h-2.1465v.662h2.1465c.6992 0 1.2656-.5683 1.2656-1.2675s-.5664-1.2656-1.2656-1.2656h-1.1016a.6054.6054 0 01-.6055-.6055c0-.3344.271-.6054.6055-.6054h2.1465V9.746h-2.1465Z" />
                    </svg>
                    <div class="tp-cat">Data Management</div>
                </a>
                <a href="#" class="tp-card" data-category="Cloud Computing">
                    <svg class="tp-logo-img" style="fill: #4a3a89;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>Nutanix</title>
                        <path
                            d="M.394 3.617a.395.395 0 0 0-.393.395c0 .12.054.225.14.297l8.506 7.404a.39.39 0 0 1-.013.588l-8.52 7.412a.393.393 0 0 0 .28.67h4.86a.39.39 0 0 0 .265-.104l9.17-7.98a.396.396 0 0 0 0-.596L5.52 3.721a.386.386 0 0 0-.264-.104zm18.358 0a.389.389 0 0 0-.273.113l-4.716 4.106a.392.392 0 0 0-.04.564l2.427 2.114a.393.393 0 0 0 .291.13.394.394 0 0 0 .278-.119l7.127-6.203a.389.389 0 0 0 .154-.31.395.395 0 0 0-.393-.395zm-2.31 9.742c-.116 0-.22.05-.292.13l-2.426 2.113a.392.392 0 0 0 .039.564l4.716 4.104c.07.07.166.113.273.113h4.855a.393.393 0 0 0 .239-.705l-7.127-6.203a.393.393 0 0 0-.278-.116z" />
                    </svg>
                    <div class="tp-cat">Cloud Computing</div>
                </a>
                <a href="#" class="tp-card" data-category="Infrastructure">
                    <svg class="tp-logo-img" style="fill: #e2231a;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>Lenovo</title>
                        <path
                            d="M21.044 12.288c0 .5-.343.867-.815.867-.464 0-.827-.38-.827-.867 0-.51.343-.868.815-.868.464 0 .827.381.827.868zm-14.305-.92a.787.787 0 0 0-.651.307.991.991 0 0 0-.172.738l1.479-.614a.708.708 0 0 0-.656-.43zm6.963.052c-.472 0-.816.358-.816.868 0 .486.364.867.828.867.472 0 .815-.368.815-.867 0-.487-.363-.868-.827-.868zM24 7.997v8.006H0V7.997h24zM5.01 13.05H3.088V9.825H2.23v4.003h2.78v-.777zm1.137-.094l2.163-.897a1.667 1.667 0 0 0-.37-.86c-.284-.33-.704-.505-1.216-.505-.931 0-1.633.686-1.633 1.593 0 .93.704 1.593 1.726 1.593.572 0 1.158-.272 1.432-.589l-.535-.411c-.357.264-.56.326-.885.326-.292 0-.52-.09-.682-.25zm5.57-1.039c0-.709-.507-1.223-1.252-1.223a1.28 1.28 0 0 0-1.005.494v-.442h-.846v3.081h.846v-1.753c0-.316.245-.651.698-.651.35 0 .712.243.712.651v1.753h.847v-1.91zm3.647.37c0-.904-.725-1.593-1.65-1.593-.933 0-1.663.7-1.663 1.593 0 .903.726 1.592 1.651 1.592.932 0 1.662-.7 1.662-1.592zm2.066 1.54l1.268-3.081h-.967l-.765 2.099-.765-2.1h-.966l1.268 3.081h.927zm4.449-1.54c0-.904-.725-1.593-1.65-1.593-.932 0-1.662.7-1.662 1.593 0 .903.725 1.592 1.65 1.592.932 0 1.662-.7 1.662-1.592z" />
                    </svg>
                    <div class="tp-cat">Infrastructure</div>
                </a>
                <a href="#" class="tp-card" data-category="Endpoints & Printing">
                    <svg class="tp-logo-img" style="fill: #0096d6;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>HP</title>
                        <path
                            d="M12.0069 24h-.3572l2.459-6.7453h3.3796c.5907 0 1.2364-.4533 1.4424-1.0166l2.6652-7.3085c.4396-1.1952-.2473-2.1706-1.525-2.1706h-4.6983l-3.929 10.798-2.2255 6.127C3.929 22.434 0 17.6806 0 12.007 0 6.498 3.7092 1.8546 8.7647.4396L6.4705 6.759 2.6514 17.2547h2.5415L8.4488 8.339h1.9095l-3.2558 8.9158H9.644l3.0223-8.3251c.4396-1.1952-.2473-2.1706-1.525-2.1706h-2.143l2.459-6.7453C11.636 0 11.8145 0 11.9931 0 18.6285 0 24 5.3715 24 12.007c.0137 6.6216-5.3578 11.993-11.9931 11.993zM19.2742 8.325h-1.9096l-2.6789 7.336h1.9096l2.6789-7.336z" />
                    </svg>
                    <div class="tp-cat">Endpoints & Printing</div>
                </a>
                <a href="#" class="tp-card" data-category="Infrastructure">
                    <div class="tp-logo-text" style="color: #01a982">HPE</div>
                    <div class="tp-cat">Infrastructure</div>
                </a>
                <a href="#" class="tp-card" data-category="Digital Workplace">
                    <svg class="tp-logo-img" style="fill: #000000;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>Citrix</title>
                        <path
                            d="M11.983 0a1.78 1.78 0 0 0-1.78 1.78 1.78 1.78 0 0 0 1.78 1.78 1.78 1.78 0 0 0 1.78-1.78A1.78 1.78 0 0 0 11.983 0zM5.17 5.991a1.026 1.026 0 0 0-1.095 1.027c0 .308.136.616.376.822l6.162 7.086-6.401 7.258a1.084 1.084 0 0 0-.309.787c0 .582.48 1.027 1.062 1.027.342 0 .684-.17.89-.444l6.128-7.19 6.162 7.19c.205.274.547.444.89.444.582.035 1.062-.445 1.062-1.027a1.14 1.14 0 0 0-.309-.787l-6.402-7.258 6.162-7.086c.24-.206.377-.514.377-.822v-.034c0-.582-.513-1.027-1.095-.993-.343 0-.65.171-.856.445l-5.957 7.018L6.06 6.436a1.07 1.07 0 0 0-.855-.445z" />
                    </svg>
                    <div class="tp-cat">Digital Workplace</div>
                </a>
                <a href="#" class="tp-card" data-category="Cybersecurity">
                    <div class="tp-logo-text" style="color: #cd2026">Forcepoint</div>
                    <div class="tp-cat">Cybersecurity</div>
                </a>
                <a href="#" class="tp-card" data-category="Infrastructure & Cloud">
                    <svg class="tp-logo-img" style="fill: #0530ad;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>IBM</title>
                        <path
                            d="M23.544 15.993c.038 0 .06-.017.06-.053v-.036c0-.035-.022-.052-.06-.052h-.09v.14zm-.09.262h-.121v-.498h.225c.112 0 .169.066.169.157 0 .079-.036.129-.09.15l.111.19h-.133l-.092-.17h-.07zm.434-.222v-.062c0-.2-.157-.357-.363-.357a.355.355 0 00-.363.357v.062c0 .2.156.358.363.358a.355.355 0 00.363-.358zm-.838-.03c0-.28.212-.492.475-.492.264 0 .475.213.475.491 0 .279-.211.491-.475.491a.477.477 0 01-.475-.49zM16.21 8.13l-.216-.624h-3.56v.624zm.413 1.19l-.216-.623h-3.973v.624zm2.65 7.147h3.107v-.624h-3.108zm0-1.192h3.107v-.623h-3.108zm0-1.19h1.864v-.624h-1.865zm0-1.191h1.864v-.624h-1.865zm0-1.191h1.864v-.624h-3.555l-.175.504-.175-.504h-3.555v.624h1.865v-.574l.2.574h3.33l.2-.574zm1.864-1.815h-3.142l-.217.624h3.359zm-7.46 3.006h1.865v-.624h-1.865zm0 1.19h1.865v-.623h-1.865zm-1.243 1.191h3.108v-.623h-3.108zm0 1.192h3.108v-.624h-3.108zm6.386-8.961l-.216.624h3.776v-.624zm-.629 1.815h4.19v-.624h-3.974zm-4.514 1.19h3.359l-.216-.623h-3.143zm2.482 2.383h2.496l.218-.624h-2.932zm.417 1.19h1.662l.218-.623h-2.098zm.416 1.191h.83l.218-.623h-1.266zm.414 1.192l.217-.624h-.432zm-12.433-.006l4.578.006c.622 0 1.18-.237 1.602-.624h-6.18zm4.86-3v.624h2.092c0-.216-.03-.425-.083-.624zm-3.616.624h1.865v-.624H6.217zm3.617-3.573h2.008c.053-.199.083-.408.083-.624H9.834zm-3.617 0h1.865v-.624H6.217zM9.55 7.507H4.973v.624h6.18a2.36 2.36 0 00-1.602-.624zm2.056 1.191H4.973v.624h6.884a2.382 2.382 0 00-.25-.624zm-5.39 2.382v.624h4.87c.207-.176.382-.387.519-.624zm4.87 1.191h-4.87v.624h5.389a2.39 2.39 0 00-.519-.624zm-6.114 3.006h6.634c.11-.193.196-.402.25-.624H4.973zM0 8.13h4.352v-.624H0zm0 1.191h4.352v-.624H0zm1.243 1.191h1.865v-.624H1.243zm0 1.191h1.865v-.624H1.243zm0 1.19h1.865v-.623H1.243zm0 1.192h1.865v-.624H1.243zM0 15.276h4.352v-.623H0zm0 1.192h4.352v-.624H0Z" />
                    </svg>
                    <div class="tp-cat">Infrastructure & Cloud</div>
                </a>
                <a href="#" class="tp-card" data-category="Application Security">
                    <svg class="tp-logo-img" style="fill: #e41c2b;" width="100%" height="60px" role="img"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <title>F5</title>
                        <path
                            d="M12 0C5.373 0 0 5.373 0 12a11.943 11.943 0 002.336 7.1113c.509.004.8594-.111.8984-.33.046-.229.0068-.5825-.0332-.9395a55.067 55.067 0 01-.2344-7.4473c-.609.027-1.1547.055-1.6777.086.02-.471.046-.9188.084-1.3828.517-.05 1.064-.0936 1.666-.1446.026-.406.0568-.7983.0918-1.1953.27-2.43 2.828-3.9162 4.959-4.4902.946-.23 1.5245-.3022 1.9785-.3262.164-.005.3405-.0117.5175-.0117.442 0 .8899.0414 1.1739.2344.46.345.9135.6873 1.3965 1.0683.048.065.1024.1678-.0176.3438-.222.26-.4371.5084-.6621.7754-.13.157-.3454.1164-.5274.0664-.38-.194-.7462-.3728-1.1172-.5528-.672-.299-1.3666-.6061-2.1406-.5761-.483.039-.951.532-1 1.209a101.41 101.41 0 00-.1504 3.2285c1.343-.038 2.6837-.0624 4.0957-.0684l-.002.9453c-.46.206-.8954.413-1.3574.623-.953.011-1.8595.0202-2.7715.0352a125.13 125.13 0 00.1192 7.9317c.024.378.0424.7605.1504 1.0175.13.322.8798.5701 2.5098.6621.007.284.0153.5532.0253.8282-2.655-.077-5.205-.3302-7.248-.6992A11.962 11.962 0 0012 24c6.628 0 12-5.373 12-12a11.942 11.942 0 00-2.0957-6.7754c-.147.607-.2252 1.2378-.3672 1.8828-1.8-.234-3.9131-.4053-6.2871-.4883-.191.602-.3711 1.192-.5781 1.836 3.973.245 5.9048 1.2924 7.0508 2.5254 1.113 1.248 1.3501 2.6262 1.2851 3.9062-.143 2.081-1.0613 3.3971-2.3203 4.3711-1.274.96-2.8139 1.436-4.0469 1.539-1.819.137-4.2515-.2962-4.7695-.6132a178.03 178.03 0 01-.9492-2.2012c-.08-.166-.1294-.3371.0976-.5351.354-.339.6928-.6667 1.0508-1.0137.158-.155.3338-.2991.4668-.0781.489.755.9473 1.4477 1.4063 2.1367.522.77 1.3167 1.4695 3.0527 1.3535 1.459-.13 2.5675-1.2333 2.6855-2.4473.128-2.246-2.1446-3.8396-8.0546-4.3496a2571.27 2571.27 0 013.123-9.371c1.404.065 2.7043.1798 3.9453.3398.919.116 1.772.3287 2.627.4277A11.973 11.973 0 0012 0zm10.0195 21.1113c-.5006 0-.9082.4076-.9082.9082 0 .5007.4076.9082.9082.9082.5007 0 .9082-.4075.9082-.9082 0-.5006-.4075-.9082-.9082-.9082zm0 .127c.4318 0 .7793.3495.7793.7812a.7776.7776 0 01-.7793.7793c-.4317 0-.7812-.3475-.7812-.7793a.7809.7809 0 01.7812-.7812zm-.3652.2773v.9961h.1738v-.3926h.1387c.092 0 .1583.0112.1953.0332.062.037.0938.1146.0938.2286v.08l.002.0293a.07.07 0 01.0039.0118c0 .005-.0001.0068.0039.0098h.162l-.0058-.0137a.106.106 0 01-.0078-.0488.6846.6846 0 01-.004-.0743v-.0742a.276.276 0 00-.0527-.1543c-.037-.053-.0948-.0846-.1718-.0976A.408.408 0 0022.328 22c.066-.042.0977-.1103.0977-.1973 0-.124-.0503-.21-.1543-.252a.752.752 0 00-.2695-.035zm.1738.123h.1504a.45.45 0 01.211.0372c.042.024.0664.0735.0664.1445a.153.153 0 01-.1036.1563.451.451 0 01-.166.0214h-.1582z" />
                    </svg>
                    <div class="tp-cat">Application Security</div>
                </a>
            </div>
        </section>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterBtns = document.querySelectorAll('#munisys-tp-wrapper .tp-filter-btn');
            const cards = document.querySelectorAll('#munisys-tp-wrapper .tp-card');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    const filterValue = btn.getAttribute('data-filter');

                    cards.forEach(card => {
                        if (filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                            card.classList.remove('hidden');
                            card.style.opacity = '0';
                            setTimeout(() => { card.style.opacity = '1'; }, 50);
                        } else {
                            card.classList.add('hidden');
                        }
                    });
                });
            });
        });
    </script>
    <?php
    return ob_get_clean();
}

// ==========================================
// 7. SHORTCODE [munisys_customer_references]
// ==========================================
add_shortcode('munisys_customer_references', 'render_munisys_customer_references_shortcode');
function render_munisys_customer_references_shortcode()
{
    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;700;900&display=swap');

        #munisys-cr-wrapper {
            --bordeaux: #922b55;
            --blue-dark: #1d4e79;
            --bg-light: #f9fafb;
            --text-dark: #111827;
            --ease-out-expo: cubic-bezier(0.19, 1, 0.22, 1);
            font-family: 'Montserrat', sans-serif !important;
            background: var(--bg-light) !important;
            color: var(--text-dark) !important;
            width: 100% !important;
            overflow-x: hidden !important;
            -webkit-font-smoothing: antialiased !important;
        }

        #munisys-cr-wrapper *,
        #munisys-cr-wrapper *::before,
        #munisys-cr-wrapper *::after {
            box-sizing: border-box !important;
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        /* Hero */
        #munisys-cr-wrapper .cr-hero {
            padding: 10rem 5% 6rem !important;
            max-width: 1440px !important;
            margin: 0 auto !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
        }

        #munisys-cr-wrapper .cr-hero-label {
            font-size: 1rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 4px !important;
            color: var(--bordeaux) !important;
            margin-bottom: 2rem !important;
        }

        #munisys-cr-wrapper .cr-hero h1 {
            font-size: clamp(4rem, 8vw, 8rem) !important;
            font-weight: 200 !important;
            line-height: 1 !important;

            margin-bottom: 3rem !important;
            letter-spacing: -3px !important;
        }

        #munisys-cr-wrapper .cr-hero p {
            font-size: 1.25rem !important;
            font-weight: 300 !important;
            color: #4b5563 !important;
            line-height: 1.8 !important;
            max-width: 800px !important;
        }

        /* Bento Grid */
        #munisys-cr-wrapper .cr-grid-sec {
            padding: 0 15px 10rem !important;
            max-width: 100% !important;
            margin: 0 auto !important;
        }

        #munisys-cr-wrapper .cr-bento {
            display: grid !important;
            grid-template-columns: repeat(5, 1fr) !important;
            grid-auto-rows: 240px !important;
            gap: 20px !important;
        }

        #munisys-cr-wrapper .cr-card {
            background: #fff !important;
            border-radius: 20px !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 2rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
            transition: all 0.6s var(--ease-out-expo) !important;
            cursor: default !important;
            position: relative !important;
            overflow: hidden !important;
            text-align: center !important;
        }

        /* Black & White to Color Hover */
        #munisys-cr-wrapper .cr-card::after {
            content: '' !important;
            position: absolute !important;
            inset: 0 !important;
            border-radius: 20px !important;
            border: 2px solid transparent !important;
            transition: all 0.6s var(--ease-out-expo) !important;
        }

        #munisys-cr-wrapper .cr-card:hover {
            transform: translateY(-10px) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
        }

        #munisys-cr-wrapper .cr-card:hover::after {
            border-color: var(--bordeaux) !important;
        }

        #munisys-cr-wrapper .cr-name {
            font-size: 1.1rem !important;
            font-weight: 800 !important;
            color: var(--blue-dark) !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            text-align: center !important;
            margin-top: 15px !important;
            transition: color 0.6s var(--ease-out-expo) !important;
            position: relative !important;
            z-index: 2 !important;
        }

        #munisys-cr-wrapper .cr-logo-img {
            max-width: 60px !important;
            max-height: 60px !important;
            object-fit: contain !important;
            transition: all 0.6s var(--ease-out-expo) !important;
            z-index: 2 !important;
        }

        #munisys-cr-wrapper .cr-card:hover .cr-logo-img {
            transform: scale(1.1) !important;
        }

        #munisys-cr-wrapper .cr-sector {
            position: absolute !important;
            bottom: 20px !important;
            font-size: 0.8rem !important;
            font-weight: 700 !important;
            color: var(--bordeaux) !important;
            text-transform: uppercase !important;
            letter-spacing: 2px !important;
            opacity: 0 !important;
            transform: translateY(10px) !important;
            transition: all 0.6s var(--ease-out-expo) !important;
        }

        #munisys-cr-wrapper .cr-card:hover .cr-sector {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        /* Spans (Keeping for fallback but no longer used in HTML) */
        #munisys-cr-wrapper .span-2x2,
        #munisys-cr-wrapper .span-2x1,
        #munisys-cr-wrapper .span-1x2 {
            /* Disabled to enforce same-size cards */
        }

        @media(max-width: 1200px) {
            #munisys-cr-wrapper .cr-bento {
                grid-template-columns: repeat(4, 1fr) !important;
            }
        }

        @media(max-width: 1024px) {
            #munisys-cr-wrapper .cr-bento {
                grid-template-columns: repeat(3, 1fr) !important;
            }
        }

        @media(max-width: 768px) {
            #munisys-cr-wrapper .cr-bento {
                grid-template-columns: repeat(2, 1fr) !important;
                grid-auto-rows: 180px !important;
            }
        }

        @media(max-width: 480px) {
            #munisys-cr-wrapper .cr-bento {
                grid-template-columns: 1fr !important;
                grid-auto-rows: 160px !important;
            }
        }
    </style>

    <div id="munisys-cr-wrapper">
        <section class="cr-hero">
            <span
                class="cr-hero-label"><?php echo esc_html(get_option('munisys_refs_hero_label', 'Client References')); ?></span>
            <h1><?php echo wp_kses_post(get_option('munisys_refs_hero_title', 'Trusted by<br /><strong>Leaders.</strong>')); ?>
            </h1>
            <p><?php echo esc_html(get_option('munisys_refs_hero_desc', 'Our commitment to excellence has earned us lasting and fruitful collaborations with the largest economic actors and state entities in Morocco.')); ?>
            </p>
        </section>

        <section class="cr-grid-sec">
            <div class="cr-bento">
                <div class="cr-card">
                    <img class="cr-logo-img"
                        src="https://upload.wikimedia.org/wikipedia/commons/9/93/Bank_Al-Maghrib_Logo.png"
                        alt="Bank Al-Maghrib">
                    <h3 class="cr-name">Bank<br />Al-Maghrib</h3>
                    <span class="cr-sector">Finance</span>
                </div>
                <div class="cr-card">
                    <img class="cr-logo-img"
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1c/OCP_Group.svg/330px-OCP_Group.svg.png"
                        alt="OCP">
                    <h3 class="cr-name">OCP</h3>
                    <span class="cr-sector">Industry</span>
                </div>
                <div class="cr-card">
                    <img class="cr-logo-img"
                        src="https://upload.wikimedia.org/wikipedia/fr/thumb/1/1d/Logo_AWB.svg/330px-Logo_AWB.svg.png"
                        alt="Attijariwafa Bank">
                    <h3 class="cr-name">Attijariwafa<br />Bank</h3>
                    <span class="cr-sector">Finance</span>
                </div>
                <div class="cr-card">
                    <img class="cr-logo-img"
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Maroc_Telecom.svg/330px-Maroc_Telecom.svg.png"
                        alt="Maroc Telecom">
                    <h3 class="cr-name">Maroc<br />Telecom</h3>
                    <span class="cr-sector">Telecom</span>
                </div>
                <div class="cr-card">
                    <img class="cr-logo-img" src="https://upload.wikimedia.org/wikipedia/commons/a/a8/Logo_de_ADM.jpg"
                        alt="Autoroutes du Maroc">
                    <h3 class="cr-name">Autoroutes du Maroc</h3>
                    <span class="cr-sector">Infrastructure</span>
                </div>
                <div class="cr-card">
                    <img class="cr-logo-img"
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ef/Logo_inwi.svg/330px-Logo_inwi.svg.png"
                        alt="inwi">
                    <h3 class="cr-name">inwi</h3>
                    <span class="cr-sector">Telecom</span>
                </div>
                <div class="cr-card">
                    <img class="cr-logo-img"
                        src="https://upload.wikimedia.org/wikipedia/commons/0/0d/LafargeHolcim_logo.png"
                        alt="LafargeHolcim">
                    <h3 class="cr-name">LafargeHolcim</h3>
                    <span class="cr-sector">Industry</span>
                </div>
                <div class="cr-card">
                    <img class="cr-logo-img"
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/CDG.svg/330px-CDG.svg.png" alt="CDG">
                    <h3 class="cr-name">CDG</h3>
                    <span class="cr-sector">Finance</span>
                </div>
                <div class="cr-card">
                    <img class="cr-logo-img"
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Logo-oncf.png/330px-Logo-oncf.png"
                        alt="ONCF">
                    <h3 class="cr-name">ONCF</h3>
                    <span class="cr-sector">Transport</span>
                </div>
                <div class="cr-card">
                    <img class="cr-logo-img" src="https://icon.horse/icon/bankofafrica.ma" alt="Bank of Africa">
                    <h3 class="cr-name">BANK OF AFRICA</h3>
                    <span class="cr-sector">Finance</span>
                </div>
            </div>
        </section>

    </div>

    <?php
    return ob_get_clean();
}

// ==========================================
// 8. SHORTCODE [munisys_services]
// ==========================================
add_shortcode('munisys_services', 'render_munisys_services_shortcode');
function render_munisys_services_shortcode()
{
    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;700;900&display=swap');

        #munisys-srv-wrapper {
            --bordeaux: #922b55;
            --blue-dark: #1d4e79;
            --blue-light: #83a5c2;
            --bg-light: #f9fafb;
            --text-dark: #111827;
            --text-muted: #4b5563;
            --ease-out-expo: cubic-bezier(0.19, 1, 0.22, 1);
            font-family: 'Montserrat', sans-serif !important;
            background: var(--bg-light) !important;
            color: var(--text-dark) !important;
            width: 100% !important;
            overflow-x: hidden !important;
            -webkit-font-smoothing: antialiased !important;
        }

        #munisys-srv-wrapper *,
        #munisys-srv-wrapper *::before,
        #munisys-srv-wrapper *::after {
            box-sizing: border-box !important;
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        #munisys-srv-wrapper .srv-hero {
            padding: 10rem 5% 6rem !important;
            max-width: 1440px !important;
            margin: 0 auto !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            background: #ffffff !important;
        }

        #munisys-srv-wrapper .srv-hero-label {
            font-size: 1rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 4px !important;
            color: var(--bordeaux) !important;
            margin-bottom: 2rem !important;
        }

        #munisys-srv-wrapper .srv-hero h1 {
            font-size: clamp(4rem, 8vw, 8rem) !important;
            font-weight: 200 !important;
            line-height: 1 !important;
            margin-bottom: 3rem !important;
            letter-spacing: -3px !important;
            color: var(--text-dark) !important;
        }

        #munisys-srv-wrapper .srv-hero h1 strong {
            font-weight: 900 !important;
        }

        #munisys-srv-wrapper .srv-hero p {
            font-size: 1.25rem !important;
            font-weight: 300 !important;
            color: #4b5563 !important;
            line-height: 1.8 !important;
            max-width: 800px !important;
        }

        /* Intro Section */
        #munisys-srv-wrapper .srv-intro {
            padding: 8rem 5% 4rem !important;
            max-width: 1200px !important;
            margin: 0 auto !important;
            text-align: center !important;
        }

        #munisys-srv-wrapper .srv-intro h2 {
            font-size: clamp(2rem, 4vw, 3.5rem) !important;
            font-weight: 300 !important;

            margin-bottom: 1.5rem !important;
            line-height: 1.2 !important;
            letter-spacing: -1px !important;
        }

        #munisys-srv-wrapper .srv-intro p {
            font-size: 1.2rem !important;
            color: var(--text-muted) !important;
            font-weight: 400 !important;
            line-height: 1.8 !important;
            max-width: 800px !important;
            margin: 0 auto !important;
        }

        /* Alternate Layout Section */
        #munisys-srv-wrapper .srv-details {
            padding: 4rem 5% 10rem !important;
            max-width: 1440px !important;
            margin: 0 auto !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 8rem !important;
        }

        #munisys-srv-wrapper .srv-row {
            display: flex !important;
            align-items: center !important;
            gap: 6rem !important;
            scroll-margin-top: 120px !important;
        }

        #munisys-srv-wrapper .srv-row.reverse {
            flex-direction: row-reverse !important;
        }

        #munisys-srv-wrapper .srv-img-col {
            flex: 1 !important;
            position: relative !important;
            border-radius: 20px !important;
            overflow: hidden !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1) !important;
            aspect-ratio: 4/3 !important;
        }

        #munisys-srv-wrapper .srv-img-col img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            transition: transform 1s var(--ease-out-expo) !important;
        }

        #munisys-srv-wrapper .srv-row:hover .srv-img-col img {
            transform: scale(1.05) !important;
        }

        #munisys-srv-wrapper .srv-img-col::after {
            content: '' !important;
            position: absolute !important;
            inset: 0 !important;
            background: linear-gradient(to top, rgba(29, 78, 121, 0.4) 0%, transparent 100%) !important;
        }

        #munisys-srv-wrapper .srv-text-col {
            flex: 1 !important;
        }

        #munisys-srv-wrapper .srv-num {
            font-size: 1.2rem !important;
            font-weight: 900 !important;
            color: var(--bordeaux) !important;
            margin-bottom: 1rem !important;
            display: block !important;
        }

        #munisys-srv-wrapper .srv-text-col h3 {
            font-size: clamp(2rem, 3vw, 3rem) !important;
            font-weight: 700 !important;

            margin-bottom: 1.5rem !important;
            line-height: 1.2 !important;
            letter-spacing: -1px !important;
        }

        #munisys-srv-wrapper .srv-text-col p {
            font-size: 1.1rem !important;
            color: var(--text-muted) !important;
            line-height: 1.8 !important;
            margin-bottom: 2rem !important;
        }

        #munisys-srv-wrapper .srv-list {
            list-style: none !important;
            margin-bottom: 2rem !important;
        }

        #munisys-srv-wrapper .srv-list li {
            position: relative !important;
            padding-left: 2rem !important;
            margin-bottom: 1rem !important;
            font-size: 1.05rem !important;
            color: var(--text-dark) !important;
            font-weight: 500 !important;
        }

        #munisys-srv-wrapper .srv-list li::before {
            content: '' !important;
            position: absolute !important;
            left: 0 !important;
            top: 8px !important;
            width: 8px !important;
            height: 8px !important;
            background: var(--bordeaux) !important;
            border-radius: 50% !important;
        }

        #munisys-srv-wrapper .srv-btn {
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            padding: 1rem 2rem !important;
            background: transparent !important;

            font-weight: 700 !important;
            text-decoration: none !important;
            border: 2px solid var(--blue-dark) !important;
            border-radius: 50px !important;
            transition: all 0.4s var(--ease-out-expo) !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            font-size: 0.9rem !important;
        }

        #munisys-srv-wrapper .srv-btn:hover {
            background: var(--blue-dark) !important;
            color: #fff !important;
            box-shadow: 0 10px 20px rgba(29, 78, 121, 0.2) !important;
        }

        @media(max-width: 1024px) {

            #munisys-srv-wrapper .srv-row,
            #munisys-srv-wrapper .srv-row.reverse {
                flex-direction: column !important;
                gap: 3rem !important;
            }

            #munisys-srv-wrapper .srv-img-col {
                width: 100% !important;
                aspect-ratio: 16/9 !important;
            }
        }
    </style>

    <div id="munisys-srv-wrapper">
        <section class="srv-hero">
            <span
                class="srv-hero-label"><?php echo esc_html(get_option('munisys_srv_hero_label', 'PROFESSIONAL SERVICES')); ?></span>
            <h1><?php echo wp_kses_post(get_option('munisys_srv_hero_title', 'Our<br /><strong>Services.</strong>')); ?>
            </h1>
            <p><?php echo esc_html(get_option('munisys_srv_hero_desc', 'Professional and operational services to help you make the right technology choices, design sound architectures, and deploy them according to best practices.')); ?>
            </p>
        </section>

        <section class="srv-intro">
            <h2><?php echo esc_html(get_option('munisys_srv_intro_title', 'IT Integration & Support')); ?></h2>
            <p><?php echo esc_html(get_option('munisys_srv_intro_desc', 'At Munisys, we cover the entire lifecycle of your IT infrastructure with a comprehensive catalog of professional services designed to ensure performance, security, and continuous business operations.')); ?>
            </p>
        </section>

        <section class="srv-details">
            <!-- Row 1 -->
            <div class="srv-row" id="it-integration">
                <div class="srv-img-col">
                    <img src="<?php echo esc_url(get_option('munisys_srv_intro_img1', 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=1000&auto=format&fit=crop')); ?>"
                        alt="Integration Services">
                </div>
                <div class="srv-text-col">
                    <span class="srv-num">01 //</span>
                    <h3>Integration Services</h3>
                    <p>At every stage in the lifecycle of your IT Infrastructures, our professional and operational services
                        will help you make the right technology choices, design the soundest architectures and deploy them
                        according to the rules of the art.</p>
                    <ul class="srv-list">
                        <li>Architecture Design & Validation</li>
                        <li>Technology Deployment</li>
                        <li>System Migration & Upgrades</li>
                    </ul>
                    <a href="<?php echo esc_url(get_option('munisys_srv_1_link', home_url('/integration-services/'))); ?>"
                        class="srv-btn">Learn More <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg></a>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="srv-row reverse" id="it-support">
                <div class="srv-img-col">
                    <img src="<?php echo esc_url(get_option('munisys_srv_intro_img2', 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=1000&auto=format&fit=crop')); ?>"
                        alt="IT Support Services">
                </div>
                <div class="srv-text-col">
                    <span class="srv-num">02 //</span>
                    <h3>IT Support Services</h3>
                    <p>Our reactive and proactive support services ensure maximum availability and continuity of your
                        technological assets. We mitigate risks through strict SLAs and dedicated technical expertise.</p>
                    <ul class="srv-list">
                        <li>24/7 Incident Management</li>
                        <li>Proactive Health Checks</li>
                        <li>Hardware Replacement (RMA)</li>
                    </ul>
                    <a href="<?php echo esc_url(get_option('munisys_srv_2_link', home_url('/it-support-services/'))); ?>"
                        class="srv-btn">Learn More <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg></a>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="srv-row" id="managed-services">
                <div class="srv-img-col">
                    <img src="<?php echo esc_url(get_option('munisys_srv_intro_img3', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1000&auto=format&fit=crop')); ?>"
                        alt="Managed Services">
                </div>
                <div class="srv-text-col">
                    <span class="srv-num">03 //</span>
                    <h3>Managed Services</h3>
                    <p>Offload your daily IT operations to our certified experts, allowing your internal teams to focus on
                        strategic business initiatives. We provide 24/7 monitoring and proactive maintenance.</p>
                    <ul class="srv-list">
                        <li>Infrastructure Monitoring</li>
                        <li>Security Operations Center (SOC)</li>
                        <li>Cloud & Backup Management</li>
                    </ul>
                    <a href="<?php echo esc_url(get_option('munisys_srv_3_link', home_url('/managed-services/'))); ?>"
                        class="srv-btn">Learn More <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg></a>
                </div>









            </div>
        </section>

    </div>

    <?php
    return ob_get_clean();
}

// ==========================================
// 9. SHORTCODE [munisys_footer]
// ==========================================
add_shortcode('munisys_footer', 'render_munisys_footer_shortcode');
function render_munisys_footer_shortcode()
{
    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');

        #munisys-real-footer {
            background: #25658f !important;
            font-family: 'Montserrat', sans-serif !important;
            padding: 40px 0 15px !important;
            width: 100vw !important;
            position: relative !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            -webkit-font-smoothing: antialiased !important;
        }

        #munisys-real-footer *,
        #munisys-real-footer *::before,
        #munisys-real-footer *::after {
            box-sizing: border-box !important;
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        #munisys-real-footer .ft-container {
            max-width: 1140px !important;
            margin: 0 auto !important;
            padding: 0 15px !important;
        }

        /* Top: Get in touch */
        #munisys-real-footer .get-in-touch {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-bottom: 50px !important;
        }

        #munisys-real-footer .ft-logo {
            height: 40px !important;
        }

        #munisys-real-footer .follow-us {
            display: flex !important;
            align-items: center !important;
            gap: 15px !important;
        }

        #munisys-real-footer .follow-us h3 {
            color: #fff !important;
            font-size: 18px !important;
            font-weight: 600 !important;
            margin: 0 !important;
        }

        #munisys-real-footer .follow-us svg {
            width: 24px !important;
            height: 24px !important;
            fill: #fff !important;
            transition: opacity 0.2s !important;
        }

        #munisys-real-footer .follow-us a:hover svg {
            opacity: 0.8 !important;
        }

        /* Main Menu */
        #munisys-real-footer .footer-menu {
            display: flex !important;
            justify-content: space-between !important;
            border-bottom: 1px solid #70a2c4 !important;
            padding-bottom: 30px !important;
            margin-bottom: 25px !important;
            list-style: none !important;
            flex-wrap: wrap !important;
        }

        #munisys-real-footer .footer-menu>li {
            color: #fff !important;
            font-size: 15px !important;
            font-weight: 600 !important;
        }

        #munisys-real-footer .footer-menu>li>a {
            color: #fff !important;
            text-decoration: none !important;
            font-size: 15px !important;
        }

        #munisys-real-footer .footer-menu ul {
            list-style: none !important;
            margin-top: 25px !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 12px !important;
        }

        #munisys-real-footer .footer-menu ul li a {
            color: #99cdef !important;
            font-size: 14px !important;
            text-decoration: none !important;
            font-weight: 400 !important;
            transition: color 0.2s !important;
        }

        #munisys-real-footer .footer-menu ul li a:hover {
            color: #fff !important;
        }

        /* Rights */
        #munisys-real-footer .rights {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
        }

        #munisys-real-footer .rights p,
        #munisys-real-footer .rights a {
            color: #fff !important;
            font-size: 11px !important;
            text-decoration: none !important;
            margin: 0 !important;
            font-weight: 400 !important;
        }

        #munisys-real-footer .rights a:hover {
            text-decoration: underline !important;
        }

        @media (max-width: 991px) {
            #munisys-real-footer .footer-menu {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 20px 10px !important;
            }

            #munisys-real-footer .footer-menu>li:first-child {
                grid-column: span 2 !important;
                /* Solutions prend toute la largeur */
                margin-bottom: 20px !important;
            }

            #munisys-real-footer .get-in-touch {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 20px !important;
            }

            #munisys-real-footer .rights {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 10px !important;
            }
        }
    </style>

    <footer id="munisys-real-footer">
        <div class="ft-container">

            <div class="get-in-touch m-reveal">
                <a href="/">
                    <!-- Placeholder logo text si l'image n'est pas dispo, on va utiliser le texte par defaut ou une icone -->
                    <svg width="150" height="40" viewBox="0 0 200 50" fill="none"><text x="0" y="35" fill="white"
                            font-size="32" font-weight="900" font-family="Montserrat">MUNISYS</text></svg>
                </a>
                <div class="follow-us">
                    <h3><?php echo esc_html(get_option('munisys_footer_follow_title', 'Follow us on')); ?></h3>
                    <a href="<?php echo esc_url(get_option('munisys_footer_linkedin_link', 'https://www.linkedin.com/company/munisys/')); ?>"
                        target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                        </svg>
                    </a>
                </div>
            </div>

            <ul class="footer-menu m-reveal m-delay-1">
                <li>
                    Solutions
                    <ul>
                        <?php
                        $def_sol = [
                            1 => ['title' => 'Core Infrastructure Technologies', 'link' => '/solutions/#core-infra'],
                            2 => ['title' => 'Cybersecurity', 'link' => '/solutions/#cybersecurity'],
                            3 => ['title' => 'Infrastructure software', 'link' => '/solutions/#infra-software'],
                            4 => ['title' => 'Managed & Cloud Services', 'link' => '/solutions/#managed-cloud'],
                            5 => ['title' => 'Endpoint solutions', 'link' => '/solutions/#endpoint-sol']
                        ];
                        for ($i = 1; $i <= 5; $i++):
                            $raw_title = get_option('munisys_sol_' . $i . '_title', $def_sol[$i]['title']);
                            // Enlever le tag <br/> éventuel pour le footer
                            $clean_title = str_replace('<br/>', ' ', $raw_title);
                            $clean_title = str_replace('<br>', ' ', $clean_title);
                            $link = get_option('munisys_sol_' . $i . '_link', $def_sol[$i]['link']);
                            ?>
                            <li><a href="<?php echo esc_url($link); ?>"><?php echo wp_kses_post($clean_title); ?></a></li>
                        <?php endfor; ?>
                    </ul>
                </li>
                <li><a
                        href="<?php echo esc_url(get_option('munisys_menu_serv_link', '/services/')); ?>"><?php echo esc_html(get_option('munisys_menu_serv_text', 'Services')); ?></a>
                </li>
                <li><a
                        href="<?php echo esc_url(get_option('munisys_menu_ref_link', '/customer-references/')); ?>"><?php echo esc_html(get_option('munisys_menu_ref_text', 'Customer references')); ?></a>
                </li>
                <li><a
                        href="<?php echo esc_url(get_option('munisys_menu_part_link', '/technology-partners/')); ?>"><?php echo esc_html(get_option('munisys_menu_part_text', 'Technology partners')); ?></a>
                </li>
                <li><a
                        href="<?php echo esc_url(get_option('munisys_menu_ins_link', '/insights/')); ?>"><?php echo esc_html(get_option('munisys_menu_ins_text', 'Insights')); ?></a>
                </li>
                <li><a
                        href="<?php echo esc_url(get_option('munisys_menu_car_link', '/careers/')); ?>"><?php echo esc_html(get_option('munisys_menu_car_text', 'Careers')); ?></a>
                </li>
                <li><a
                        href="<?php echo esc_url(get_option('munisys_menu_about_link', '/about-us/')); ?>"><?php echo esc_html(get_option('munisys_menu_about_text', 'About us')); ?></a>
                </li>
                <li><a
                        href="<?php echo esc_url(get_option('munisys_menu_contact_link', '/contact/')); ?>"><?php echo esc_html(get_option('munisys_menu_contact_text', 'Contact')); ?></a>
                </li>
            </ul>

            <div class="rights m-reveal m-delay-2">
                <p><?php echo esc_html(get_option('munisys_footer_copyright', '©2026 MUNISYS - ALL RIGHTS RESERVED')); ?>
                </p>
                <a href="/en/legal">LEGAL</a>
            </div>

        </div>
    </footer>
    <?php
    return ob_get_clean();

}
// ==========================================
// MIGRATION DES ANCIENS INSIGHTS STATIQUES
// ==========================================
function munisys_migrate_old_insights_to_posts()
{
    if (get_option('munisys_old_insights_migrated_v3')) {
        return;
    }

    $insights_to_create = [
        [
            'title' => get_option('munisys_ins_card1_title', 'PRESS RELEASE'),
            'excerpt' => get_option('munisys_ins_card1_desc', 'Helios Investment Partners and Fipar-Holding, Launch Morocco’s Leading Digital Infrastructure Platform with the Acquisition of Maroc Datacenter, Munisys and Medasys.'),
            'content' => '',
            'image' => get_option('munisys_press_release_img', ''),
        ],
        [
            'title' => get_option('munisys_ins_2_title', 'New headquarters'),
            'excerpt' => get_option('munisys_ins_2_desc', 'Munisys is excited to announce that in a few days our headquarters in Casablanca are going to move to our newly built address in the heart of the Green City of Bouskoura.'),
            'content' => '',
            'image' => get_option('munisys_ins_2_img', '/wp-content/uploads/datacenter.png'),
        ],
        [
            'title' => get_option('munisys_ins_3_title', 'A new visual identity for Munisys'),
            'excerpt' => get_option('munisys_ins_3_desc', 'After more than 30 years of innovation, commitment and excellence shared with our valued clients, it is time to adopt a new visual identity, to carry our values, mark our singularities, and translate our ambitions.'),
            'content' => '',
            'image' => get_option('munisys_ins_3_img', ''),
        ],
        [
            'title' => get_option('munisys_ins_4_title', 'Come and visit us at Gitex Africa'),
            'excerpt' => get_option('munisys_ins_4_desc', 'Munisys is exhibiting at Gitex Africa 2023, happening in Marrakech from 31 May to 02 June. This first edition is a tremendous opportunity to showcase the immense potential and capacity of the African digital ecosystem...'),
            'content' => '',
            'image' => get_option('munisys_ins_4_img', ''),
        ]
    ];

    foreach ($insights_to_create as $ins) {
        $post_id = wp_insert_post([
            'post_title' => $ins['title'],
            'post_excerpt' => $ins['excerpt'],
            'post_content' => $ins['content'],
            'post_status' => 'publish',
            'post_type' => 'munisys_insight'
        ]);
        if ($post_id && !is_wp_error($post_id) && !empty($ins['image'])) {
            update_post_meta($post_id, '_munisys_insight_img_url', $ins['image']);
        }
    }

    update_option('munisys_old_insights_migrated_v3', true);
}
add_action('init', 'munisys_migrate_old_insights_to_posts');

function munisys_migrate_old_insights_custom_html_v5()
{
    if (get_option('munisys_old_insights_custom_html_v5'))
        return;

    // FIPAR
    $p = get_posts(['post_type' => 'munisys_insight', 'title' => 'PRESS RELEASE', 'post_status' => 'any', 'numberposts' => 1]);
    if (!empty($p)) {
        update_post_meta($p[0]->ID, '_munisys_insight_thumb_type', 'fipar');
        update_post_meta($p[0]->ID, '_munisys_insight_thumb_t1', 'HELIOS');
        update_post_meta($p[0]->ID, '_munisys_insight_thumb_t2', 'Investment Partners');
        update_post_meta($p[0]->ID, '_munisys_insight_thumb_t3', 'FIPAR');
    }

    // MUNISYS
    $p = get_posts(['post_type' => 'munisys_insight', 'title' => 'A new visual identity for Munisys', 'post_status' => 'any', 'numberposts' => 1]);
    if (!empty($p)) {
        update_post_meta($p[0]->ID, '_munisys_insight_thumb_type', 'munisys');
        update_post_meta($p[0]->ID, '_munisys_insight_thumb_t1', 'MUNISYS');
    }

    // GITEX
    $p = get_posts(['post_type' => 'munisys_insight', 'title' => 'Come and visit us at Gitex Africa', 'post_status' => 'any', 'numberposts' => 1]);
    if (!empty($p)) {
        update_post_meta($p[0]->ID, '_munisys_insight_thumb_type', 'gitex');
        update_post_meta($p[0]->ID, '_munisys_insight_thumb_t1', 'GITEX');
        update_post_meta($p[0]->ID, '_munisys_insight_thumb_t2', 'AFRICA');
    }

    update_option('munisys_old_insights_custom_html_v5', true);
}
add_action('init', 'munisys_migrate_old_insights_custom_html_v5');

// ==========================================
// 10. SHORTCODE: MUNISYS INSIGHTS
// ==========================================
add_shortcode('munisys_insights', 'render_munisys_insights_shortcode');
function render_munisys_insights_shortcode()
{
    $link_press_release = '/press-release/';

    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800;900&display=swap');

        #munisys-insights-wrapper {
            font-family: 'Montserrat', sans-serif !important;
            width: 100%;
            background: #ffffff;
        }

        #munisys-insights-wrapper .ins-sec {
            padding: 8rem 5% !important;
            display: flex !important;
            gap: 5rem !important;
            max-width: 1440px !important;
            margin: 0 auto !important;
            flex-direction: row !important;
            align-items: center !important;
        }

        #munisys-insights-wrapper .ins-title-col {
            flex-shrink: 0 !important;
            display: flex !important;
            align-items: center !important;
        }

        #munisys-insights-wrapper .ins-title {
            writing-mode: vertical-lr !important;
            transform: rotate(180deg) !important;
            font-size: 5.5rem !important;
            font-weight: 900 !important;
            color: #1d4e79 !important;
            margin: 0 !important;
            line-height: 0.8 !important;
            letter-spacing: -2px !important;
            text-transform: uppercase !important;
        }

        #munisys-insights-wrapper .ins-grid {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 2rem !important;
            flex: 1 !important;
        }

        #munisys-insights-wrapper .ins-card {
            display: flex !important;
            flex-direction: column !important;
            gap: 1.5rem !important;
            cursor: pointer !important;
            text-decoration: none !important;
            transition: transform 0.4s cubic-bezier(0.19, 1, 0.22, 1) !important;
        }

        #munisys-insights-wrapper .ins-card:hover {
            transform: translateY(-8px) !important;
        }

        #munisys-insights-wrapper .ins-img-wrap {
            aspect-ratio: 1 !important;
            border: 1px solid rgba(29, 78, 121, 0.1) !important;
            clip-path: polygon(15% 0, 100% 0, 100% 100%, 0 100%, 0 15%) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: hidden !important;
            background: #fcfcfc !important;
            padding: 2.5rem !important;
            position: relative !important;
            transition: all 0.4s ease !important;
        }

        #munisys-insights-wrapper .ins-card:hover .ins-img-wrap {
            box-shadow: inset 0 0 0 2px rgba(146, 43, 85, 0.3) !important;
            background: #fff !important;
        }

        #munisys-insights-wrapper .ins-img-wrap.no-pad {
            padding: 0 !important;
        }

        #munisys-insights-wrapper .ins-img-wrap img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            transition: transform 0.8s ease !important;
        }

        #munisys-insights-wrapper .ins-card:hover .ins-img-wrap img {
            transform: scale(1.05) !important;
        }

        #munisys-insights-wrapper .ins-text {
            font-weight: 700 !important;
            color: #111 !important;
            font-size: 1.25rem !important;
            line-height: 1.5 !important;
            margin: 0 !important;
            padding-right: 1rem !important;
            transition: color 0.3s ease !important;
        }

        #munisys-insights-wrapper .ins-card:hover .ins-text {
            color: #922b55 !important;
        }

        #munisys-insights-wrapper .ins-desc {
            font-size: 0.9rem !important;
            color: #6b7280 !important;
            line-height: 1.6 !important;
            margin: 0 !important;
            padding-right: 1rem !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 4 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
        }

        #munisys-insights-wrapper .ins-learn-more {
            font-size: 0.9rem !important;
            font-weight: 700 !important;
            color: #1d4e79 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            margin-top: auto !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            transition: color 0.3s ease !important;
        }

        #munisys-insights-wrapper .ins-card:hover .ins-learn-more {
            color: #922b55 !important;
        }

        @media (max-width: 1024px) {
            #munisys-insights-wrapper .ins-sec {
                flex-direction: column !important;
                padding: 5rem 5% !important;
                gap: 3rem !important;
                align-items: flex-start !important;
            }

            #munisys-insights-wrapper .ins-title-col {
                width: 100% !important;
            }

            #munisys-insights-wrapper .ins-title {
                writing-mode: horizontal-tb !important;
                transform: none !important;
                font-size: 4rem !important;
            }

            #munisys-insights-wrapper .ins-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                width: 100% !important;
            }
        }

        @media (max-width: 600px) {
            #munisys-insights-wrapper .ins-grid {
                grid-template-columns: 1fr !important;
            }

            #munisys-insights-wrapper .ins-title {
                font-size: 3rem !important;
            }
        }
    </style>

    <div id="munisys-insights-wrapper">
        <section class="ins-sec" id="insights">
            <div class="ins-title-col m-reveal-left">
                <div class="ins-title"><?php echo esc_html(get_option('munisys_ins_title', 'Insights')); ?></div>
            </div>
            <div class="ins-grid">
                <?php
                $insight_args = array(
                    'post_type' => 'munisys_insight',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                );
                $insight_query = new WP_Query($insight_args);

                if ($insight_query->have_posts()):
                    $delay = 1;
                    while ($insight_query->have_posts()):
                        $insight_query->the_post();
                        $img_url = get_post_meta(get_the_ID(), '_munisys_insight_img_url', true) ?: get_the_post_thumbnail_url(get_the_ID(), 'large');
                        $has_thumb = !empty($img_url);
                        $thumb_type = get_post_meta(get_the_ID(), '_munisys_insight_thumb_type', true) ?: 'image';
                        $t1 = get_post_meta(get_the_ID(), '_munisys_insight_thumb_t1', true);
                        $t2 = get_post_meta(get_the_ID(), '_munisys_insight_thumb_t2', true);
                        $t3 = get_post_meta(get_the_ID(), '_munisys_insight_thumb_t3', true);
                        ?>
                        <a href="<?php the_permalink(); ?>" class="ins-card m-reveal m-delay-<?php echo $delay; ?>">
                            <div class="ins-img-wrap <?php echo ($has_thumb && $thumb_type === 'image') ? 'no-pad' : 'contain'; ?>"
                                style="<?php echo ($has_thumb && $thumb_type === 'image') ? '' : 'flex-direction: column; gap: 0.8rem; text-align: center;'; ?>">
                                <?php if ($thumb_type === 'gitex'): ?>
                                    <div
                                        style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px; width: 100%;">
                                        <h2 style="font-size: 2rem; font-weight: 900; color: #1d4e79; margin:0; letter-spacing: -1px;">
                                            <?php echo esc_html($t1); ?></h2>
                                        <span
                                            style="font-weight: 700; color: #922b55; font-size: 1.1rem; letter-spacing: 2px;"><?php echo esc_html($t2); ?></span>
                                    </div>
                                <?php elseif ($thumb_type === 'fipar'): ?>
                                    <h2
                                        style="font-size: 1.2rem; font-weight: 300; color: #333; margin:0; line-height: 1.2; letter-spacing: -0.5px;">
                                        <?php echo esc_html($t1); ?> <br /><span
                                            style="color: #f97316; font-size: 0.9rem; font-weight: 500; text-transform: none;"><?php echo esc_html($t2); ?></span>
                                    </h2>
                                    <h1 style="font-size: 2.2rem; font-weight: 900; color: #1d4e79; margin:0; letter-spacing: -1px;">
                                        <?php echo esc_html($t3); ?></h1>
                                <?php elseif ($thumb_type === 'munisys'): ?>
                                    <div
                                        style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                                        <span
                                            style="font-weight: 800; color: #1d4e79; font-size: 2.5rem; letter-spacing: 2px; line-height: 1;"><?php echo esc_html($t1 ?: 'MUNISYS'); ?></span>
                                    </div>
                                <?php elseif ($has_thumb): ?>
                                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" />
                                <?php else: ?>
                                    <div
                                        style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; background: #f8f9fa;">
                                        <span style="color: #a0aec0; font-size: 0.9rem; font-style: italic;">Aucune image</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="ins-text" style="text-transform: uppercase; font-size: 1.1rem; letter-spacing: 1px;">
                                <?php the_title(); ?>
                            </p>
                            <p class="ins-desc">
                                <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                            </p>
                            <span class="ins-learn-more">Learn more →</span>
                        </a>
                        <?php
                        $delay++;
                    endwhile;
                    wp_reset_postdata();
                endif;

                // --- AJOUT DES ANCIENS INSIGHTS (Cartes Statiques) ---
                // Ces cartes s'affichent toujours à la suite de vos nouveaux articles
                ?>







            </div>
        </section>
    </div>
    <?php
    return ob_get_clean();
}

// ==========================================
// 11. SHORTCODE: MUNISYS SOLUTIONS (BENTO GRID)
// ==========================================
add_shortcode('munisys_solutions', 'render_munisys_solutions_shortcode');
function render_munisys_solutions_shortcode()
{
    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800;900&display=swap');

        #munisys-sol-wrapper {
            font-family: 'Montserrat', sans-serif !important;
        }

        #munisys-sol-wrapper .sol-title {
            font-size: 2.5rem !important;
            font-weight: 800 !important;
            color: #1d4e79 !important;
            margin-bottom: 2.5rem !important;
            text-align: left !important;
        }

        #munisys-sol-wrapper .bento-grid {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            grid-auto-rows: minmax(240px, auto) !important;
            gap: 1.5rem !important;
        }

        /* Bento Cards */
        #munisys-sol-wrapper .bento-card {
            position: relative !important;
            border-radius: 16px !important;
            overflow: hidden !important;
            background: #1d4e79 !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: flex-end !important;
            padding: 2.5rem !important;
            text-decoration: none !important;
            color: #fff !important;
            transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1) !important;
        }

        #munisys-sol-wrapper .bento-bg {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            transition: transform 0.6s cubic-bezier(0.19, 1, 0.22, 1) !important;
            z-index: 1 !important;
        }

        #munisys-sol-wrapper .bento-overlay {
            position: absolute !important;
            inset: 0 !important;
            background: linear-gradient(to top, rgba(29, 78, 121, 0.95) 0%, rgba(29, 78, 121, 0.3) 60%, transparent 100%) !important;
            z-index: 2 !important;
            transition: background 0.4s ease !important;
        }

        #munisys-sol-wrapper .bento-card:hover .bento-bg {
            transform: scale(1.05) !important;
        }

        #munisys-sol-wrapper .bento-card:hover {
            transform: translateY(-8px) !important;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            z-index: 10 !important;
        }

        #munisys-sol-wrapper .bento-card:hover .bento-overlay {
            background: linear-gradient(to top, rgba(146, 43, 85, 0.95) 0%, rgba(29, 78, 121, 0.5) 70%, transparent 100%) !important;
        }

        #munisys-sol-wrapper .bento-content {
            position: relative !important;
            z-index: 3 !important;
        }

        #munisys-sol-wrapper .bento-link svg {
            transition: transform 0.3s ease !important;
            margin-left: 5px !important;
        }

        #munisys-sol-wrapper .bento-card:hover .bento-link svg {
            transform: translateX(6px) !important;
        }

        /* Sizes */
        #munisys-sol-wrapper .span-2x3 {
            grid-column: span 2 !important;
            grid-row: span 3 !important;
        }

        #munisys-sol-wrapper .span-2x1 {
            grid-column: span 2 !important;
            grid-row: span 1 !important;
        }

        #munisys-sol-wrapper .bento-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
        }

        /* Sizes */
        #munisys-sol-wrapper .span-2x2 {
            grid-column: span 2 !important;
            grid-row: span 2 !important;
        }

        #munisys-sol-wrapper .span-1x2 {
            grid-row: span 2 !important;
        }

        #munisys-sol-wrapper .span-4x1 {
            grid-column: span 4 !important;
            min-height: 220px !important;
        }

        /* Image & Overlay */
        #munisys-sol-wrapper .bento-bg {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            z-index: 1 !important;
            transition: transform 1s cubic-bezier(0.19, 1, 0.22, 1) !important;
        }

        #munisys-sol-wrapper .bento-card:hover .bento-bg {
            transform: scale(1.05) !important;
        }

        #munisys-sol-wrapper .bento-overlay {
            position: absolute !important;
            inset: 0 !important;
            background: linear-gradient(to top, rgba(29, 78, 121, 0.95) 0%, rgba(29, 78, 121, 0.1) 100%) !important;
            z-index: 2 !important;
            transition: background 0.4s ease !important;
        }

        #munisys-sol-wrapper .bento-card:hover .bento-overlay {
            background: linear-gradient(to top, rgba(29, 78, 121, 0.98) 0%, rgba(29, 78, 121, 0.3) 100%) !important;
        }

        /* Specific Overlays */
        #munisys-sol-wrapper .overlay-cyber {
            background: linear-gradient(to top, rgba(146, 43, 85, 0.95) 0%, rgba(146, 43, 85, 0.1) 100%) !important;
        }

        #munisys-sol-wrapper .bento-card:hover .overlay-cyber {
            background: linear-gradient(to top, rgba(146, 43, 85, 0.98) 0%, rgba(146, 43, 85, 0.3) 100%) !important;
        }

        #munisys-sol-wrapper .bento-content {
            position: relative !important;
            z-index: 3 !important;
            display: flex !important;
            flex-direction: column !important;
        }

        #munisys-sol-wrapper .bento-content h3 {
            font-size: clamp(1.4rem, 2vw, 2.2rem) !important;
            font-weight: 700 !important;
            margin: 0 0 1rem 0 !important;
            line-height: 1.2 !important;
            letter-spacing: -0.5px !important;
            color: #fff !important;
        }

        #munisys-sol-wrapper .bento-content p {
            font-size: 1rem !important;
            color: rgba(255, 255, 255, 0.85) !important;
            line-height: 1.6 !important;
            margin-bottom: 2rem !important;
            font-weight: 300 !important;
        }

        #munisys-sol-wrapper .bento-link {
            font-weight: 700 !important;
            font-size: 0.9rem !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            margin-top: auto !important;
            color: #fff !important;
        }

        #munisys-sol-wrapper .bento-link svg {
            transition: transform 0.3s ease !important;
        }

        #munisys-sol-wrapper .bento-card:hover .bento-link svg {
            transform: translateX(5px) !important;
        }

        /* Endpoint Card specific styles */
        #munisys-sol-wrapper .endpoint-btn {
            padding: 1rem 2.5rem !important;
            background: #fff !important;
            color: #1d4e79 !important;
            border-radius: 50px !important;
            font-weight: 700 !important;
            margin-top: 0 !important;
            display: flex !important;
            flex-shrink: 0 !important;
            transition: all 0.3s ease !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            font-size: 0.9rem !important;
        }

        #munisys-sol-wrapper .bento-card:hover .endpoint-btn {
            background: #1d4e79 !important;
            color: #fff !important;
            box-shadow: 0 0 0 2px #fff !important;
        }

        /* Responsive Breakpoints */
        @media(max-width: 1024px) {
            #munisys-sol-wrapper .bento-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            #munisys-sol-wrapper .span-4x1 {
                grid-column: span 2 !important;
            }

            #munisys-sol-wrapper .endpoint-content {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 1.5rem !important;
            }

            #munisys-sol-wrapper .endpoint-text {
                max-width: 100% !important;
            }
        }

        @media(max-width: 600px) {
            #munisys-sol-wrapper .bento-grid {
                grid-template-columns: 1fr !important;
            }

            #munisys-sol-wrapper .span-2x2,
            #munisys-sol-wrapper .span-1x2,
            #munisys-sol-wrapper .span-4x1 {
                grid-column: span 1 !important;
                grid-row: span 1 !important;
            }

            #munisys-sol-wrapper .bento-content p {
                display: none !important;
                /* Hide long descriptions on mobile */
            }
        }
    </style>

    <?php
    $def_sol = [
        1 => ['title' => 'Core Infrastructure<br/>Technologies', 'desc' => 'Compute, storage, networking and backup infrastructure across data centers and public clouds, offering cloud-native experience and AI-driven management.', 'btn' => 'Read more', 'img' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?q=80&w=1200&auto=format&fit=crop'],
        2 => ['title' => 'Cybersecurity<br/>Solutions', 'desc' => 'End-to-end security architectures to protect your digital assets against evolving threats.', 'btn' => 'Discover', 'img' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=800&auto=format&fit=crop'],
        3 => ['title' => 'Infrastructure<br/>Software', 'desc' => 'Software-defined approaches and robust integrations for a modern IT stack.', 'btn' => '', 'img' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop'],
        4 => ['title' => 'Managed &amp;<br/>Cloud Services', 'desc' => 'Seamless operations across public, private, and hybrid environments.', 'btn' => '', 'img' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=800&auto=format&fit=crop'],
        5 => ['title' => 'Endpoint Solutions', 'desc' => 'Empowering your workforce with secure and efficient workplace technologies. High-end integrations for hybrid work environments.', 'btn' => 'View Offerings', 'img' => 'https://images.unsplash.com/photo-1573164713988-8665fc963095?q=80&w=800&auto=format&fit=crop']
    ];

    $sol = [];
    for ($i = 1; $i <= 5; $i++) {
        $sol[$i] = [
            'title' => get_option('munisys_sol_' . $i . '_title', $def_sol[$i]['title']),
            'desc' => get_option('munisys_sol_' . $i . '_desc', $def_sol[$i]['desc']),
            'btn' => get_option('munisys_sol_' . $i . '_btn', $def_sol[$i]['btn']),
            'link' => get_option('munisys_sol_' . $i . '_link', '#'),
            'img' => get_option('munisys_sol_' . $i . '_img', $def_sol[$i]['img']),
        ];
    }
    ?>
    <div id="munisys-sol-wrapper">
        <div class="bento-grid-container">
            <h2 class="sol-title"
                style="font-size: 3rem !important; font-weight: 900 !important; color: #1d4e79 !important; margin-bottom: 3rem !important; text-align: center !important; opacity: 1 !important; visibility: visible !important; display: block !important;">
                Our Solutions</h2>
            <div class="bento-grid">

                <!-- 1. Core Infrastructure (Grande carte 2x3) -->
                <a href="<?php echo esc_url($sol[1]['link']); ?>" class="bento-card span-2x3 temp-link m-reveal m-delay-1">
                    <img src="<?php echo esc_url($sol[1]['img']); ?>" class="bento-bg" alt="Datacenter">
                    <div class="bento-overlay"></div>
                    <div class="bento-content">
                        <h3><?php echo wp_kses_post($sol[1]['title']); ?></h3>
                        <p><?php echo esc_html($sol[1]['desc']); ?></p>
                        <?php if (!empty($sol[1]['btn'])): ?>
                            <span class="bento-link"><?php echo esc_html($sol[1]['btn']); ?> <svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg></span>
                        <?php endif; ?>
                    </div>
                </a>

                <!-- 2. Cybersecurity (Carte haute 2x1) -->
                <a href="<?php echo esc_url($sol[2]['link']); ?>" class="bento-card span-2x1 temp-link m-reveal m-delay-2">
                    <img src="<?php echo esc_url($sol[2]['img']); ?>" class="bento-bg" alt="Cybersecurity">
                    <div class="bento-overlay overlay-cyber"></div>
                    <div class="bento-content">
                        <h3><?php echo wp_kses_post($sol[2]['title']); ?></h3>
                        <p><?php echo esc_html($sol[2]['desc']); ?></p>
                        <?php if (!empty($sol[2]['btn'])): ?>
                            <span class="bento-link"><?php echo esc_html($sol[2]['btn']); ?> <svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg></span>
                        <?php endif; ?>
                    </div>
                </a>

                <!-- 3. Infrastructure Software (Carte normale) -->
                <a href="<?php echo esc_url($sol[3]['link']); ?>" class="bento-card temp-link m-reveal m-delay-3">
                    <img src="<?php echo esc_url($sol[3]['img']); ?>" class="bento-bg" alt="Software">
                    <div class="bento-overlay"></div>
                    <div class="bento-content">
                        <h3><?php echo wp_kses_post($sol[3]['title']); ?></h3>
                        <p><?php echo esc_html($sol[3]['desc']); ?></p>
                    </div>
                </a>

                <!-- 4. Managed & Cloud Services (Carte normale) -->
                <a href="<?php echo esc_url($sol[4]['link']); ?>" class="bento-card temp-link m-reveal m-delay-4">
                    <img src="<?php echo esc_url($sol[4]['img']); ?>" class="bento-bg" alt="Cloud">
                    <div class="bento-overlay"></div>
                    <div class="bento-content">
                        <h3><?php echo wp_kses_post($sol[4]['title']); ?></h3>
                        <p><?php echo esc_html($sol[4]['desc']); ?></p>
                    </div>
                </a>

                <!-- 5. Endpoint solutions (Carte large 2x1) -->
                <a href="<?php echo esc_url($sol[5]['link']); ?>" class="bento-card span-2x1 temp-link m-reveal m-delay-5">
                    <img src="<?php echo esc_url($sol[5]['img']); ?>" class="bento-bg" alt="Endpoint">
                    <div class="bento-overlay"></div>
                    <div class="bento-content">
                        <h3><?php echo wp_kses_post($sol[5]['title']); ?></h3>
                        <p><?php echo esc_html($sol[5]['desc']); ?></p>
                        <?php if (!empty($sol[5]['btn'])): ?>
                            <span class="bento-link"><?php echo esc_html($sol[5]['btn']); ?> <svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg></span>
                        <?php endif; ?>
                    </div>
                </a>

            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function render_munisys_solutions_page_shortcode()
{
    ob_start();

    // Include defaults
    $def_cats = [
        1 => [
            'title' => 'Core Infrastructure Technologies',
            'desc' => 'Setting up, managing, and securing consistent compute, storage, networking and backup infrastructure across data centers and public clouds.',
            'count' => 5,
            'subcards' => [
                1 => [
                    'title' => 'Compute & Storage',
                    'desc' => 'We use proven methodologies to design and build modern compute and storage infrastructures, designed to work in edge, data center or hybrid cloud environments. Our cutting-edge solutions include powerful rack servers, composable bladed infrastructure, object, block and file storage arrays,… In order to offer consistency and meet specific needs for any workload, we select a combination of most secure mission-critical, cost sensitive or extensively scalable solutions.<br>High availability and resilience are primary criteria in the design of these infrastructure components. The use of right level of redundancy as well as the best fitting replication and automated failover technologies allow us to meet ever-increasing customer’s demands for business continuity and fast disaster recovery.',
                    'exp' => 'Standalone, Rack, and bladed Compute Servers\nHigh Performance Computing\nConverged IT/OT Systems\nEntry, Midrange and Mission Critical All-flash storage arrays\nFile, block, object storage solutions',
                    'part' => 'DELL, HPE, IBM'
                ],
                2 => [
                    'title' => 'Hyper-converged Infrastructures',
                    'desc' => 'Modern Hyper-converged Infrastructures (HCI) simplify datacenter operations, reduce dependency on proprietary hardware and provide scalability by tightly integrating individual compute servers, storage arrays and networks to yield a flexible, agile, and easily manageable software-defined datacenter.<br>Our systems architects and delivery experts harness the full power of HCI underlying virtualization technologies enabling our customers to benefit from an innovative as-a-service resources consumption model.<br>Thanks to the breadth of our partner’s HCI portfolios, we leverage many options to deploy the best fitting physical and software-defined resources, allowing our customers for choice based on their current infrastructure architecture and their in-house adopted operational processes.',
                    'exp' => 'HCI appliances\nHCI Ready-Nodes',
                    'part' => 'DELL, HPE, Nutanix, VMware'
                ],
                3 => [
                    'title' => 'Data Resilience',
                    'desc' => 'Ensuring business continuity through availability, integrity, and accessibility of data across IT infrastructure is vital for today’s organizations. No unexpected disruption, accidental deletion or unauthorized entities should be able to alter operational capabilities or business applications environments stability.<br>Our Data resilience solutions leverages a comprehensive set of in-house and cloud services as well as an automated approach that standardizes data protection through backups, encryption and object storage while providing centralized visibility and resources monitoring across all workloads and locations.',
                    'exp' => 'Data Protection Appliances and Software\nCloud Data Security\nAir gapped Immutable Storage',
                    'part' => 'DELL, HPE, Veeam, Veritas'
                ],
                4 => [
                    'title' => 'Networking',
                    'desc' => 'Conducting a successful digital transformation strategy requires seamless connectivity and access to data for both users and applications. Setting up secure and highly scalable wired, wireless, and SD-WAN networking platforms is vital to address business critical use cases.<br>Our partners’ networking portfolio spans from the edge layer to the cloud and creates a scalable, automated, service-centric and intelligent foundation for a secure connectivity of the unified infrastructure.<br>Our solutions help gaining visibility and control over the actual performance delivered by the network whilst simplifying operations and mitigating risks, all supported by AI driven management and policies enforcement capabilities.',
                    'exp' => 'Data Center Core LAN\nEdge Networks\nWireless LAN\nSD-WAN',
                    'part' => 'HPE Aruba, Cisco'
                ],
                5 => [
                    'title' => 'Cabling & Data Center Environment',
                    'desc' => 'At the most foundational level, an IT infrastructure starts with the environment that will host its most critical components and will allow them to operate in optimal and secure conditions.<br>Our cabling and data center environment team brings a proven expertise and a sound portfolio of solutions helping our customers to be totally confident that the physical IT technologies their operations rely upon will benefit from dependable, manageable and secure cabling, rack, power and monitoring systems.',
                    'exp' => 'Intelligent racks\nPower security and distribution\nHigh density cabling systems\nRFID Asset inventory and location\nData center environment monitoring',
                    'part' => 'Corning, Eaton, Legrand, R&M, RFCode, Schneider Electric'
                ],
            ]
        ],
        2 => [
            'title' => 'Cybersecurity Solutions',
            'desc' => 'Comprehensive protection for your enterprise. We secure your network, endpoints, and data against evolving digital threats.',
            'count' => 5,
            'subcards' => [
                1 => [
                    'title' => 'Endpoint Security',
                    'desc' => 'Cyber adversaries’ efforts to bypass corporate defenses start usually by targeting endpoints as they have always been company’s most vulnerable assets to attacks, regardless of where they are located inside or outside the corporate network, exploiting multiple factors such as human errors, device misconfigurations, software breaches, weak passwords, missing patches, and many more.<br>Munisys’ Endpoint Security solutions offer comprehensive monitoring and protection features to desktops, laptops, smartphones, printers, and other connected user devices. This includes detecting, preventing and responding to threats like infections by viruses, zero-day attacks, phishing and ransomware. Advanced capabilities are also leveraged to provide higher levels of protection like anomaly behavior analysis, automatic enforcement, and dynamic security controls.<br>We value also built-in hardware-based security and advanced self-healing capabilities as integrated features supported by specific endpoint devices being part of our EndPoint Solutions Business Unit’ catalog.',
                    'exp' => 'Antivirus & Ransomware protection\nEndpoint Detection & Response (EDR)\nUser and Entity Behavior Analytics\nWeb and Mail Threat protection',
                    'part' => 'HP, TrendMicro, Kaspersky, PaloAlto Networks'
                ],
                2 => [
                    'title' => 'Network Security',
                    'desc' => 'Digital assets’ availability and integrity rely greatly on seamless deployment of a highly secure network infrastructure with a scalable, automated, and manageable enforcement of a comprehensive set of rules and policies.<br>With the proliferation in the cloud and data center environments of distributed multi-sites resources, application virtualization and containerization, as well as extensive usage of encrypted traffic, IT teams are facing greater challenges to protect the network itself without compromising its performance and to secure application workloads without hampering efforts towards enablement of an outstanding user experience.<br>Combining multiple layers of defenses, our Networking Security solutions ensure an effective protection against unauthorized access with Network Access Control technologies, set up preemptive measures intended to thwart any attack coming from either the outside or circulating inside the network with Next Generation Firewalling and IDS/IPS systems, provide isolation of malware codes and objects with Sandbox technologies and responds to detected abnormal system behaviors with NDR solutions.',
                    'exp' => 'Next Generation Firewalling\nNetwork Access Control\nDDoS Protection\nNetwork Detection & Response (NDR)\nSandboxing',
                    'part' => 'F5, Fortinet, PaloAlto Networks, Forcepoint, Checkpoint, HPE Aruba, Gatewatcher'
                ],
                3 => [
                    'title' => 'Cloud & Application Security',
                    'desc' => 'Over the past few years, the world witnessed an unprecedented shift towards remote working and cloud services adoption. Strategies has been put in place to rapidly migrate applications, APIs services and related data to collaborative cloud environments to take benefit from an enhanced agility and new innovation capabilities. However, this also creates a paradigm change in digital operations and assets’ security.<br>Resolved to embrace the change, Munisys has expertly enriched his portfolio of security solutions to help extend secure access and high-performance connectivity to users regardless of their geographic locations with SASE technologies and to enable moving applications and API endpoints safely outside the organization’s network perimeter with advanced solutions designed to integrate effective security controls throughout their development lifecycle.',
                    'exp' => 'Cloud-native applications & API security\nApplication vulnerability management (SAST/DAST/IAST)\nSecure Access Service Edge (SASE) solutions\nSecure File Transfer\nDNS Security',
                    'part' => 'Axway, Broadcom, OpenText, F5, PaloAlto Networks'
                ],
                4 => [
                    'title' => 'Identity and Access Management',
                    'desc' => 'Securing a perimeter around the datacenter network is no longer enough to ensure safe access to data, applications, and business-critical services as they are increasingly spread out across multi-sites or even multi-clouds environments. Today, Organizations must deploy an Identity-centric strategy to protect every user-resource connection, mitigate risk and provide access governance, compliance, and real-time threat detection.<br>With our Identity and Access Management (IAM) solutions and services, we help you orchestrate policies across on-premises, remote, and cloud environments to control identities and give privileged access and passwordless authentication to your workforce as well as to your customers.<br>Further, Munisys helps you find a right balance between accessibility and security controls so as not to impede your ability to deliver agile, scalable, and efficient business services.',
                    'exp' => 'Multi Factor Authentication (MFA)\nLDAP Protection\nFederated Identity Management\nCloud Access Security Broker (CASB)\nData privilege Management',
                    'part' => 'Fortinet, Varonis, Opentext, F5'
                ],
                5 => [
                    'title' => 'Visibility & Compliance',
                    'desc' => 'A holistic approach to Cybersecurity encompasses rolling out security enforcement systems and controls, gaining deep visibility into valued assets and verifying continuously those assets’ compliance with business policies and regulatory requirements.<br>Our solutions drive greater visibility and compliance capabilities by providing scalable event monitoring, automation, SOC analytics, audited remote management, and more.',
                    'exp' => 'Security Information & Event Management (SIEM)\nSecurity Orchestration & Automated Response (SOAR)\nThreat Intelligence Platforms\nPrivileged Access Management\nVulnerability & Patch Management\nSecurity Policy Management',
                    'part' => 'Tenable, Wallix, OpenText, Google-Mandiant, Rapid7'
                ],
            ]
        ],
        3 => [
            'title' => 'Infrastructure Software',
            'desc' => 'Optimize your operations with enterprise-grade software solutions for monitoring, management, and automation.',
            'count' => 4,
            'subcards' => [
                1 => [
                    'title' => 'Service & Asset Management',
                    'desc' => 'Digital transformation has significantly elevated the customer experience across vertical markets, prompting every organization to strive to adopt internally similar levels of service delivery for an engaging, consumer-like, and self-service end user experience.<br>Going beyond IT-centric service delivery enablement, Munisys’ ITIL-aligned and cloud-native solutions encompass a collection of policies and processes for the management and support of business services like human resources, legal, assets, facilities, marketing, and finance.<br>Strong with its proven experience on implementing service and asset management processes, native mobile applications, machine learning and AI-powered chatbots, social collaboration, advanced analytics, and orchestration, Munisys is your privileged partner when laying down the foundation for your enterprise’s efficiency improvement strategy.',
                    'exp' => 'Enterprise Service Management\nIT Asset Management\nCMDB and Dynamic Discovery\nSoftware License Management',
                    'part' => 'OpenText'
                ],
                2 => [
                    'title' => 'Observability, Automation & Orchestration',
                    'desc' => 'Applying advanced automation and predictive analytics technologies to run, monitor and protect enterprise assets and processes has deeply transformed how IT operations are performed and managed.<br>Huge volumes of data are usually generated by continuous discovery and at scale monitoring of applications, services, networks and user experience across cloud and on-premises environments. By implementing automated and intelligent correlation solutions to analyse those big data sets, organizations can easily derive fast and useful insights as well as accurate and timely knowledge of issues impacting their business services’ performance and health levels.<br>Automation may also be enhanced with powerful orchestration engines helping IT operators to efficiently run, schedule, and actively monitor processes and workflows at enterprise scale.<br>Munisys expertly provides its customers with end-to-end observability, automation, orchestration, and proactive remediation solutions to increase process efficiency, alleviate pressure on IT teams while eliminating manual and repetitive efforts, lower IT operation costs, and minimize business services disruption.',
                    'exp' => 'Datacenter Infrastructure Monitoring\nApplication Performance Monitoring\nEnterprise-wide Automation Platforms\nRobotic Process Automation',
                    'part' => 'OpenText, Redhat, Broadcom'
                ],
                3 => [
                    'title' => 'Data Management & Analytics',
                    'desc' => 'Turning Data into meaningful and actionable insights is undoubtedly one of the key aspects to make businesses capable of staying competitive and ahead of market expectations. This is even becoming table stakes in some verticals. Indeed, no financial institutions can afford not relying on critical data applications like Frauds detection and Risks simulation. No Telecom provider can ignore the crucial importance of Network analytics and Customer behavior analytics.<br>Munisys is partnering with big players in the data platforms ecosystem to provide state-of-the-art distributed data management components to source, structure, protect and analyze data. Whether run on hybrid or mutlicloud environments, our solutions deliver quality enterprise-wide intelligence in a fast and cost-effective way.',
                    'exp' => 'Hybrid & Cloud Data platforms\nArtificial Intelligence Data Analytics\nEnterprise Data Archiving',
                    'part' => 'OpenText, Cohesity'
                ],
                4 => [
                    'title' => 'Application Modernization',
                    'desc' => 'An important feature of a sustainable digital transformation strategy is the modernization of incumbent applications to take advantage of newer virtualized infrastructure and cloud technology advances for better resources optimization, greater developments speed, frequent deployments, and improved resiliency. This approach should, on the other hand, protect older investments, value existing functionalities and minimize service disruptions.<br>Thanks to its ability to conduct a thorough application inventory and assessment, Munisys delivers innovative services set up for helping organizations select a right migration pattern towards their newly adopted cloud infrastructures. Those patterns, such as Rehosting, Replatforming and Refactoring imply different effort and complexity levels and are selected based on containerization suitability, business risks, and dependencies across new or legacy applications.<br>Munisys is definitely the right partner for businesses intending to execute an effective application modernization plan as this entails a proven expertise on numerous intersecting technologies such as Cloud application platforms, API gateways, Containers, Microservices, Security, Automation, and Orchestration.',
                    'exp' => 'Application Modernization Assessment\nKubernetes-based Container Orchestration platforms\nApplication Rehosting / Replatforming / Refactoring\nAPI Management\nZero Trust Application Security',
                    'part' => 'Redhat, GitLab, Opentext'
                ],
            ]
        ],
        4 => [
            'title' => 'Managed & Cloud Services',
            'desc' => 'Seamless cloud integration and managed services to accelerate your digital transformation journey.',
            'count' => 5,
            'subcards' => [
                1 => [
                    'title' => 'Hosting and Cloud Infrastructure Services',
                    'desc' => 'Building traditional on-premises IT infrastructure enhances agility and competitiveness but comes with significant challenges like hefty upfront capital expenditures, limited scalability, and unremitting upgrade and maintenance tasks. Having difficulties managing multi-site or multi-technology architectures, leads often to inefficiencies and increased operational costs.<br>Munisys hosting and cloud infrastructure offering addresses these challenges by providing hardware and software reliability, disaster recovery, increased scalability, cost optimization and enhanced resource management while giving access to an elite infrastructure experts team without the need to build it in-house.<br>Based on automated provisioning, unified orchestration, and API-driven infrastructure management, our solutions are delivered as a comprehensive and modular set of services for planning, preparing, and operating traditional systems and modern cloud-native environments, encompassing colocation, dedicated platforms and private, hybrid and public cloud infrastructure.<br>Our services are delivered within certified, carrier-neutral and sovereign data centers hosting enterprise-grade infrastructure. Designed and operated by MDC, a sibling company, our data centers meet strict jurisdictional requirements needed by highly regulated sectors like finance, healthcare and public services. Organizations benefit from a securely connected or air-gapped architectures, hardened access control, resilient connectivity and tailored service level agreements (SLAs) for performance, control, reporting, and availability.',
                    'exp' => 'Data Center Colocation\nManaged dedicated Private infrastructure\nManaged private & hybrid cloud Infrastructure\nInfrastructure as a Service (Virtual machines, virtual desktops, Virtual Data Centers)\nCloud Object Storage (S3)',
                    'part' => 'MDC, OVH, OCI, HPE, Redhat, VMware, Nutanix'
                ],
                2 => [
                    'title' => 'Application and Platforms Services',
                    'desc' => 'Today, continuous innovation and process modernization relies on platforms that provide an automated, API-driven, and scalable environments to build, test and deploy cloud-ready applications, cloud-native workloads, serverless, or AI/ML capabilities.<br>At Munisys, we design, implement and manage leading kubernetes platforms and containerized environments. The tools and capabilities delivered as a service, allow organizations to develop and deploy applications without the need to manage the underlying infrastructure.<br>Our offer encompasses innovative SaaS and PaaS solutions like IT service management, controlled automation, API Gateways and AI/MLOps platforms. We provide the computational resources, specialized tools and highly skilled experts for a complete visibility, dynamic scalability, and secure operations across cloud infrastructure.',
                    'exp' => 'Managed Kubernetes & Container Platforms\nPlatforms as a Service (API, AI, DEVSECOPS, …)\nManaged Application & SaaS Hosting\nIT Service Management as a Service\nSecrets Vault (VaaS)',
                    'part' => 'Redhat, VMware, Nutanix, Gitlab, HashiCorp'
                ],
                3 => [
                    'title' => 'Business Resilience & Data Protection',
                    'desc' => 'As cyberattacks on digital infrastructure and sensitive data flows continues its upward trajectory, ensuring business resilience and data protection play a pivotal role in shaping the future of cloud transition and operation strategies.<br>Munisys protects your on-premises and cloud infrastructure by setting up a distributed model, based on active-passive or active-active architectures, that inherently reduce risks by eliminating a single point of failure and improving responsiveness. Site recovery environments are set up in our data centers with adequate RPO and RTO parameters, secure networks, and encrypted datastores. Leveraging advanced observability solutions, we analyze usage trends, detect anomalies, set proactive alerts and accelerate troubleshooting and recovery operations.<br>Munisys protects also your production data by providing an enterprise-class cloud backup services for recovering critical workloads and protecting SaaS, cloud-native, and on-premises data sources. To help organizations minimize data loss or ransomware risks, our services enable swift recovery through advanced safeguards, such as secure access controls, regular backup schedules, immutable backups, air-gapped storage, and data encryption.<br>Combining core Clean Room capabilities from our data protection solutions partners with cyberattack response and managed services expertise from Munisys security teams, we are able to provide you with a clean room solution intending to establish on-demand, air-gapped response environment, along with integrated forensic analysis and recovery tools and services.',
                    'exp' => 'Backup as a Service\nDisaster Recovery as a Service (DRaaS)\nClean room service',
                    'part' => 'Cohesity, Veeam'
                ],
                4 => [
                    'title' => 'Managed Security Services',
                    'desc' => 'As cloud adoption rises, many organizations struggle to transform security across infrastructure, cloud platforms, and applications from a compliance requirement into a strategic advantage.<br>Munisys offers cloud-based, integrated, and scalable solutions that provide advanced defensive security capabilities without the need for on-premises hardware, software or expert knowledge, ensuring businesses are consistently well-prepared for any unforeseen disruptions through comprehensive and continuous protection of their digital assets.<br>Aiming to safeguard your entire data and application lifecycle, our proactive and holistic approach-based security services, combined with proven methodologies and expert-led capabilities, enable us to monitor your infrastructure 24/7, to focus on preventing incidents before they happen, and to detect and respond as quickly and effectively as possible.',
                    'exp' => 'Access Control & Privileged Access management\nPatch and Vulnerability Management\nEndpoint Detection & Response\nManaged Network Security\nManaged SOC, XDR (eXtended Detection/Response)\nAppSec aaS (Application Security Testing, cloud-native applications security, Managed DevSecOps Tooling)',
                    'part' => 'Palo Alto, Fortinet, Redhat, OpenText'
                ],
                5 => [
                    'title' => 'Advisory, Professional and Management Services',
                    'desc' => 'Our end-to-end services are designed to support you at every stage of your digital transformation journey, securing transitions and aligning with specific business goals:<br>- Assessment & Advisory services to define tailored dedicated infrastructure and cloud architecture strategies,<br>- Deployment & Migration services spanning activities from planning, data acquisition/transfer, configuration, automation to platform and application integration,<br>- Optimization & Alignment services enhancing performance, efficiency, and scalability of the running environments,<br>- Management services ensuring high availability, cost reduction, security, and observability.<br>You may entrust your selected services to our experts in full-managed mode or choose between a self-managed mode through cloud-based interface, co-managed mode or even a management mode adapted to your distinctive needs and specific context.<br>These services are delivered mainly by our own teams of cloud architects, DevOps engineers, security specialists, infrastructure experts and certified project managers.',
                    'exp' => 'Cloud & IT assessment and advisory services\nProfessional Services (Deployment, Configuration, Automation, Support)\nCloud Migration\nOperations and Infrastructure Optimization & Alignment\nComprehensive Management Services',
                    'part' => ''
                ],
            ]
        ],
        5 => [
            'title' => 'Endpoint Solutions',
            'desc' => 'Empower your workforce with secure, high-performance computing devices and intelligent endpoint management solutions.',
            'count' => 3,
            'subcards' => [
                1 => [
                    'title' => 'Digital Workplace',
                    'desc' => 'The digital workplace holds a central role in the ability of users to fully benefit from and embrace modern work and collaboration processes. The place is not what matters most, but the combination of technologies that will allow to efficiently and securely work, share and communicate, with the additional benefit of an improved work/life balance.<br>The unique combination of expertise that Munisys brings allows us to design, deploy and support workplace technologies while also answering your needs related to security & data protection, virtualization & automation, monitoring & service management, ubiquitous assistance & support.',
                    'exp' => 'Desktop and laptop computers selected based on performance needs, security requirements and environment constraints\nCollaboration solutions\nVDI solutions\nEndpoint protection\nComplete services portfolio tailored for the endpoint',
                    'part' => 'Dell, HP Inc., Jabra, Lenovo, Logitech, Poly, Samsung'
                ],
                2 => [
                    'title' => 'Printing Solutions',
                    'desc' => 'Printing is an essential part of business processes whenever paper is the most appropriate or the only possible way of sharing information.<br>Based on market leading solutions, we assist you in choosing the right technologies and device range in order to make sure producing physical documents gives an edge to your teams and positively contributes to your customers’ experience.<br>We also offer country wide Managed Printing Services, that will provide you with a flexible, scalable, no interruption and cost optimized printing solution.',
                    'exp' => 'Enterprise printing solutions\nManaged Print Services',
                    'part' => 'Canon, Epson, HP Inc'
                ],
                3 => [
                    'title' => 'End Customer Facing Devices',
                    'desc' => 'Besides computing, printing and workplace related technologies, the Endpoint encompasses a large range of devices and software aiming to offer the end customer the most suitable interface to access the many services that digital has made possible.<br>A very efficient way to engage with your customers when offering digitized and digital services is to deploy intelligent kiosks that will promote self-service. We give our customers the possibility to freely design such kiosks and to very closely match their requirements in terms of physical shape and size and to include any functionality to be offered to the end user, ranging from printer to document scanner, from ID card Reader to biometric devices and payment terminals. A well-designed kiosk solution can significantly relief the front office personnel and let them focus on value-added customer face to face interactions.<br>In the process of moving growingly toward digital services, some specific categories of devices are needed, among which digital ID acquisition or digital signature capture. In order to assist our clients in implementing these functions in an efficient and reliable way and to offer all the required integration possibilities, we have selected specialized devices from leading vendors. They offer a complete solution, especially in the banking sector, but also in general anywhere a customer ID and/or signature are necessary within a workflow.',
                    'exp' => 'Intelligent kiosks\nScanners / readers\nID card / biometry readers\nDigital signature pads',
                    'part' => 'Faytech, Secugen, Signotec'
                ],
            ]
        ],
    ];


    $h_l = get_option('munisys_solpage_hero_label', 'DIGITAL SOLUTIONS');
    $h_t = get_option('munisys_solpage_hero_title', 'Our<br /><strong>Solutions.</strong>');
    $h_d = get_option('munisys_solpage_hero_desc', 'Discover our comprehensive suite of digital infrastructure technologies tailored to empower your business operations and drive innovation.');

    $ids = [
        1 => 'core-infra',
        2 => 'cybersecurity',
        3 => 'infra-software',
        4 => 'managed-cloud',
        5 => 'endpoint-sol'
    ];
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap');

        #munisys-solutions-wrapper {
            --bordeaux: #922b55;
            --blue-dark: #1d4e79;
            --blue-light: #83a5c2;
            --text-dark: #333333;
            --text-gray: #666666;
            --bg-gray: #fcfcfc;
            font-family: 'Montserrat', sans-serif !important;
            background: #ffffff;
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
            width: 100vw !important;
            position: relative;
            left: 50% !important;
            right: 50% !important;
            margin-left: -50vw !important;
            margin-right: -50vw !important;
            text-align: left;
        }

        #munisys-solutions-wrapper *,
        #munisys-solutions-wrapper *::before,
        #munisys-solutions-wrapper *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif !important;
        }

        /* ── HERO ── */
        #munisys-solutions-wrapper .sol-hero {
            padding: 10rem 5% 6rem !important;
            max-width: 1440px !important;
            margin: 0 auto !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            background: #ffffff !important;
        }

        #munisys-solutions-wrapper .sol-hero-label {
            font-size: 1rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 4px !important;
            color: var(--bordeaux) !important;
            margin-bottom: 2rem !important;
            display: block !important;
        }

        #munisys-solutions-wrapper .sol-hero h1 {
            font-size: clamp(4rem, 8vw, 8rem) !important;
            font-weight: 200 !important;
            line-height: 1 !important;
            margin-bottom: 3rem !important;
            letter-spacing: -3px !important;
            color: var(--text-dark) !important;
        }

        #munisys-solutions-wrapper .sol-hero h1 strong {
            font-weight: 900 !important;
        }

        #munisys-solutions-wrapper .sol-hero p {
            font-size: 1.25rem !important;
            font-weight: 300 !important;
            color: #4b5563 !important;
            line-height: 1.8 !important;
            max-width: 800px !important;
        }

        /* ── MAIN LAYOUT ── */
        #munisys-solutions-wrapper .sol-container {
            max-width: 1440px !important;
            margin: 0 auto !important;
            padding: 5rem !important;
            display: grid !important;
            grid-template-columns: 350px 1fr !important;
            gap: 5rem !important;
            scroll-margin-top: 150px !important;
        }

        /* ── SIDEBAR ── */
        #munisys-solutions-wrapper .sol-sidebar {
            display: flex !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
            position: sticky !important;
            top: 100px !important;
            height: fit-content !important;
        }

        #munisys-solutions-wrapper .sol-cat-btn {
            background: #f4f7f9 !important;
            border: none !important;
            padding: 1.2rem 1.5rem !important;
            text-align: left !important;
            font-size: 1.05rem !important;
            font-weight: 600 !important;
            color: var(--blue-dark) !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
            border-left: 4px solid transparent !important;
        }

        #munisys-solutions-wrapper .sol-cat-btn:hover {
            background: #e9eff4 !important;
        }

        #munisys-solutions-wrapper .sol-cat-btn.active {
            background: #fff !important;
            border-left: 4px solid var(--bordeaux) !important;
            color: var(--bordeaux) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
        }

        /* ── CONTENT ── */
        #munisys-solutions-wrapper .sol-cat-content {
            display: none !important;
            animation: fadeIn 0.4s ease-in-out !important;
            scroll-margin-top: 120px !important;
        }

        #munisys-solutions-wrapper .sol-cat-content.active {
            display: block !important;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #munisys-solutions-wrapper .cat-header {
            margin-bottom: 4rem !important;
            border-bottom: 2px solid #e9eff4 !important;
            padding-bottom: 2rem !important;
        }

        #munisys-solutions-wrapper .cat-header h2 {
            font-size: 2.8rem !important;
            font-weight: 800 !important;
            color: var(--blue-dark) !important;
            margin-bottom: 1rem !important;
        }

        #munisys-solutions-wrapper .cat-header p {
            font-size: 1.15rem !important;
            color: var(--text-gray) !important;
            line-height: 1.8 !important;
        }

        #munisys-solutions-wrapper .sub-sol-grid {
            display: flex !important;
            flex-direction: column !important;
            gap: 5rem !important;
        }

        #munisys-solutions-wrapper .sub-sol-card {
            display: flex !important;
            flex-direction: column !important;
            gap: 1.5rem !important;
        }

        #munisys-solutions-wrapper .sub-sol-card h3 {
            font-size: 2rem !important;
            font-weight: 700 !important;
            color: var(--bordeaux) !important;
        }

        #munisys-solutions-wrapper .sub-sol-card p.overview {
            font-size: 1.05rem !important;
            color: var(--text-dark) !important;
            line-height: 1.8 !important;
            white-space: pre-line !important;
        }

        #munisys-solutions-wrapper .sub-sol-meta {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 3rem !important;
            background: #f4f7f9 !important;
            padding: 2.5rem !important;
            border-radius: 8px !important;
        }

        #munisys-solutions-wrapper .sub-sol-meta h4 {
            font-size: 1.1rem !important;
            color: var(--blue-dark) !important;
            font-weight: 800 !important;
            margin-bottom: 1.5rem !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
        }

        #munisys-solutions-wrapper .meta-list {
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 0.8rem !important;
        }

        #munisys-solutions-wrapper .meta-list li {
            display: flex !important;
            align-items: flex-start !important;
            gap: 0.8rem !important;
            font-size: 1rem !important;
            color: var(--text-gray) !important;
            line-height: 1.5 !important;
            font-weight: 500 !important;
        }

        #munisys-solutions-wrapper .meta-list li::before {
            content: '■' !important;
            color: var(--bordeaux) !important;
            font-size: 0.8em !important;
            transform: translateY(3px) !important;
        }

        #munisys-solutions-wrapper .partners-tags {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 0.8rem !important;
        }

        #munisys-solutions-wrapper .partner-tag {
            background: #fff !important;
            border: 1px solid #d4dfe8 !important;
            color: var(--blue-dark) !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            border-radius: 4px !important;
        }

        @media(max-width:1024px) {
            #munisys-solutions-wrapper .sol-container {
                grid-template-columns: 1fr !important;
                padding: 3rem !important;
            }

            #munisys-solutions-wrapper .sol-sidebar {
                position: static !important;
                flex-direction: row !important;
                overflow-x: auto !important;
                padding-bottom: 1rem !important;
            }

            #munisys-solutions-wrapper .sol-cat-btn {
                white-space: nowrap !important;
                border-left: none !important;
                border-bottom: 4px solid transparent !important;
            }

            #munisys-solutions-wrapper .sol-cat-btn.active {
                border-left: none !important;
                border-bottom: 4px solid var(--bordeaux) !important;
            }

            #munisys-solutions-wrapper .sub-sol-meta {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

    <div id="munisys-solutions-wrapper">
        <!-- HERO -->
        <section class="sol-hero" style="background: #ffffff !important;">
            <span class="sol-hero-label" style="color: #922b55 !important;"><?php echo esc_html($h_l); ?></span>
            <h1 style="color: #333333 !important;"><?php echo wp_kses_post($h_t); ?></h1>
            <p style="color: #4b5563 !important;"><?php echo esc_html($h_d); ?></p>
        </section>

        <!-- MAIN -->
        <section class="sol-container">

            <!-- SIDEBAR -->
            <div class="sol-sidebar">
                <?php for ($i = 1; $i <= 5; $i++):
                    $c_t = get_option('munisys_solpage_c' . $i . '_title', $def_cats[$i]['title']);
                    ?>
                    <button class="sol-cat-btn <?php echo $i == 1 ? 'active' : ''; ?>"
                        data-target="<?php echo esc_attr($ids[$i]); ?>"><?php echo esc_html($c_t); ?></button>
                <?php endfor; ?>
            </div>

            <!-- CONTENT AREA -->
            <div class="sol-content-area">
                <?php for ($i = 1; $i <= 5; $i++):
                    $c_t = get_option('munisys_solpage_c' . $i . '_title', $def_cats[$i]['title']);
                    $c_d = get_option('munisys_solpage_c' . $i . '_desc', $def_cats[$i]['desc']);
                    ?>
                    <div class="sol-cat-content <?php echo $i == 1 ? 'active' : ''; ?>" id="<?php echo esc_attr($ids[$i]); ?>">
                        <div class="cat-header">
                            <h2><?php echo esc_html($c_t); ?></h2>
                            <p><?php echo esc_html($c_d); ?></p>
                        </div>
                        <div class="sub-sol-grid">
                            <?php for ($s = 1; $s <= $def_cats[$i]['count']; $s++):
                                $def_sub = isset($def_cats[$i]['subcards'][$s]) ? $def_cats[$i]['subcards'][$s] : ['title' => '', 'desc' => '', 'exp' => '', 'part' => ''];
                                $s_t = get_option('munisys_solpage_c' . $i . '_s' . $s . '_title', $def_sub['title']);
                                $s_d = get_option('munisys_solpage_c' . $i . '_s' . $s . '_desc', $def_sub['desc']);
                                $s_e = get_option('munisys_solpage_c' . $i . '_s' . $s . '_exp', $def_sub['exp']);
                                $s_p = get_option('munisys_solpage_c' . $i . '_s' . $s . '_part', $def_sub['part']);

                                if (empty($s_t))
                                    continue;
                                ?>
                                <div class="sub-sol-card"
                                    id="<?php echo esc_attr(sanitize_title($def_sub['title']) . '-' . $i); ?>">
                                    <h3><?php echo esc_html($s_t); ?></h3>
                                    <p class="overview"><?php echo wp_kses_post(nl2br($s_d)); ?></p>
                                    <div class="sub-sol-meta">
                                        <div>
                                            <h4>Key Expertises</h4>
                                            <ul class="meta-list">
                                                <?php
                                                $exps = explode("\n", $s_e);
                                                foreach ($exps as $exp):
                                                    $exp = trim($exp);
                                                    if (!empty($exp)):
                                                        ?>
                                                        <li><?php echo esc_html($exp); ?></li>
                                                    <?php endif; endforeach; ?>
                                            </ul>
                                        </div>
                                        <div>
                                            <?php if (!empty($s_p)): ?>
                                                <h4>Technology Partners</h4>
                                                <div class="partners-tags">
                                                    <?php
                                                    $parts = explode(",", $s_p);
                                                    foreach ($parts as $part):
                                                        $part = trim($part);
                                                        if (!empty($part)):
                                                            ?>
                                                            <span class="partner-tag"><?php echo esc_html($part); ?></span>
                                                        <?php endif; endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </section>
    </div>

    <script>
        (function initSolutions() {
            function run() {
                const btns = document.querySelectorAll('#munisys-solutions-wrapper .sol-cat-btn');
                const contents = document.querySelectorAll('#munisys-solutions-wrapper .sol-cat-content');

                if (btns.length === 0) return;

                function handleHash(delay = 300) {
                    const hash = window.location.hash.replace('#', '');
                    if (hash) {
                        let targetBtn = document.querySelector('.sol-cat-btn[data-target="' + hash + '"]');
                        let scrollTarget = null;

                        // If not a main category, check if it's a subcard
                        if (!targetBtn) {
                            const subCard = document.getElementById(hash);
                            if (subCard) {
                                const parentTab = subCard.closest('.sol-cat-content');
                                if (parentTab) {
                                    targetBtn = document.querySelector('.sol-cat-btn[data-target="' + parentTab.id + '"]');
                                    scrollTarget = subCard;
                                }
                            }
                        }

                        if (targetBtn && !targetBtn.classList.contains('active')) {
                            setTimeout(() => {
                                // Switch tab programmatically to avoid triggering the default button click scroll
                                const freshBtns = document.querySelectorAll('#munisys-solutions-wrapper .sol-cat-btn');
                                const freshContents = document.querySelectorAll('#munisys-solutions-wrapper .sol-cat-content');

                                freshBtns.forEach(b => b.classList.remove('active'));
                                freshContents.forEach(c => c.classList.remove('active'));

                                targetBtn.classList.add('active');
                                const targetContent = document.getElementById(targetBtn.getAttribute('data-target'));
                                if (targetContent) {
                                    targetContent.classList.add('active');
                                }

                                // Now scroll smoothly
                                setTimeout(() => {
                                    let elToScroll = scrollTarget || document.querySelector('.sol-container');
                                    if (elToScroll) {
                                        const headerOffset = document.querySelector('#munisys-global-header') ? document.querySelector('#munisys-global-header').offsetHeight : 80;
                                        const extraOffset = scrollTarget ? 40 : 220;
                                        window.scrollTo({
                                            top: elToScroll.getBoundingClientRect().top + window.scrollY - headerOffset - extraOffset,
                                            behavior: 'smooth'
                                        });
                                    }
                                }, 50);

                            }, delay);
                        } else if (targetBtn && targetBtn.classList.contains('active')) {
                            // Already active, just scroll
                            setTimeout(() => {
                                let elToScroll = scrollTarget || document.querySelector('.sol-container');
                                if (elToScroll) {
                                    const headerOffset = document.querySelector('#munisys-global-header') ? document.querySelector('#munisys-global-header').offsetHeight : 80;
                                    const extraOffset = scrollTarget ? 40 : 220;
                                    window.scrollTo({
                                        top: elToScroll.getBoundingClientRect().top + window.scrollY - headerOffset - extraOffset,
                                        behavior: 'smooth'
                                    });
                                }
                            }, delay);
                        }
                    }
                }

                // Initial load
                handleHash(300);

                // On hash change (e.g. clicking footer links while already on the page)
                window.addEventListener('hashchange', () => handleHash(50));

                btns.forEach(btn => {
                    // Pour éviter la duplication d'événements dans Breakdance
                    const newBtn = btn.cloneNode(true);
                    btn.replaceWith(newBtn);

                    newBtn.addEventListener('click', (e) => {
                        // Prevent manual click from executing if it was triggered by code
                        if (!e.isTrusted) return;

                        const freshBtns = document.querySelectorAll('#munisys-solutions-wrapper .sol-cat-btn');
                        const freshContents = document.querySelectorAll('#munisys-solutions-wrapper .sol-cat-content');

                        freshBtns.forEach(b => b.classList.remove('active'));
                        freshContents.forEach(c => c.classList.remove('active'));

                        newBtn.classList.add('active');
                        const targetId = newBtn.getAttribute('data-target');
                        const targetContent = document.getElementById(targetId);
                        if (targetContent) {
                            targetContent.classList.add('active');
                            // Smooth scroll to top of container with reliable offset
                            const container = document.querySelector('.sol-container');
                            if (container) {
                                const offset = 220; // Margin from top
                                window.scrollTo({
                                    top: container.getBoundingClientRect().top + window.scrollY - offset,
                                    behavior: 'smooth'
                                });
                            }
                        }
                    });
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', run);
            } else {
                run();
            }
        })();
    </script>
    <div id="google_translate_element" style="display:none;"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({ pageLanguage: 'en', autoDisplay: false }, 'google_translate_element');
        }
        function setLanguage(lang, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            document.cookie = "googtrans=/en/" + lang + "; path=/; domain=" + window.location.hostname;
            document.cookie = "googtrans=/en/" + lang + "; path=/;";
            window.location.reload();
        }
        document.addEventListener('DOMContentLoaded', function () {
            let cookie = document.cookie.split('; ').find(row => row.startsWith('googtrans='));
            let currentLang = 'en';
            if (cookie) {
                let parts = cookie.split('=')[1].split('/');
                if (parts.length > 2) {
                    currentLang = parts[2];
                }
            }
            if (currentLang === 'en' || currentLang === 'fr') {
                let swContainer = document.querySelector('.custom-lang-switcher');
                if (swContainer) {
                    swContainer.setAttribute('data-active', currentLang);
                }
                document.querySelectorAll('.lang-btn').forEach(btn => {
                    if (btn.innerText.toLowerCase() === currentLang) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            }
        });
    </script>
    <script type="text/javascript"
        src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <?php
    return ob_get_clean();
}
add_shortcode('munisys_solutions_page', 'render_munisys_solutions_page_shortcode');

// ==========================================
// 13. SHORTCODE: MUNISYS INSIGHT DETAIL PAGE
// ==========================================
add_shortcode('munisys_insight_detail', 'render_munisys_insight_detail_shortcode');
function render_munisys_insight_detail_shortcode($atts, $content = null)
{
    global $post;
    $slug = $post ? $post->post_name : '';

    // Si on est dans Breakdance preview, on peut utiliser des attributs pour forcer la page
    $a = shortcode_atts(array(
        'id' => ''
    ), $atts);

    if (!empty($a['id'])) {
        $slug = $a['id'];
    }

    $title = '';
    $date = '';
    $image = '';
    $html_content = '';

    if (is_singular('munisys_insight') || get_post_type() === 'munisys_insight') {
        // Mode Dynamique : on récupère le contenu du vrai article "Insight"
        $title = get_the_title();
        $date = get_the_date('d F Y');
        $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        $html_content = apply_filters('the_content', get_post_field('post_content', get_the_ID()));
    } elseif (strpos($slug, 'press-release') !== false) {
        $title = 'PRESS RELEASE';
        $date = '08 December 2025';
        $image = '/wp-content/uploads/datacenter.png';
        $html_content = '<p align="center"><b>Helios Investment Partners and Fipar-Holding, Launch Morocco\'s Leading Digital Infrastructure Platform with the Acquisition of Maroc Datacenter, Munisys and Medasys</b></p>
<p><b>[08 December 2025].</b> Helios Investment Partners ("Helios", acting on behalf of funds it advises), the leading Africa-focused private investment firm, and Fipar-Holding, a subsidiary of CDG Invest, are pleased to announce the creation of 3MDC, a new digital infrastructure platform. This platform aims to accelerate Morocco\'s digital transformation and strengthen its position as a regional technology hub.</p>
<p>The new platform brings together Maroc Datacenter (MDC), Munisys, and Medasys, three of Morocco\'s most established players in cloud, data, cybersecurity and digital services. Their combination creates a unique, fully integrated hybrid-cloud platform serving enterprise and public-sector clients across Morocco and Southern Europe.</p>
<p>The new platform leverages MDC\'s fast-growing carrier-neutral Tier III datacenter together with Munisys\' longstanding expertise in on-premises cloud, cybersecurity, systems integration and digital solutions. Medasys adds strong complementary technological capabilities and a deep understanding of the Moroccan IT landscape.</p>
<p>Through strategic partnerships with several world-leading technology providers, the platform will offer advanced, secure and sovereign digital solutions tailored to the evolving needs of organizations.</p>
<p>3MDC company will be jointly owned by Helios IV, which will hold 44%, Fipar, which will hold 40%, and the founders and management teams, who will collectively hold 16%. This balanced ownership structure underscores the strong alignment between international investment expertise and national institutional support and ensures strong local ownership and alignment with Morocco\'s institutional ecosystem.</p>
<p>Helios has a proven track record of delivering large-scale digital infrastructure projects across Africa. Through this partnership, Morocco will gain greater digital security and self-reliance by expanding critical in-country capacity. Helios and Fipar are united in their vision to build scalable, future-ready digital platforms, with Fipar providing deep local knowledge and close ties to Moroccan institutions, and Helios bringing extensive expertise in digital infrastructure and a long-term investment perspective.</p>
<p><b>Babatunde Soyoye, Co-Founder and Managing Partner, Helios Investment Partners, commented:</b> <i>"This investment builds on our conviction in Africa\'s digital future and our track record of creating national champions in partnership with leading local institutions. The combination of Maroc Datacenter, Munisys and Medasys creates a unique Moroccan platform with the scale and expertise to enable secure, sovereign and sustainable digital growth."</i></p>
<p><b>Khalid Ziane, General Manager of Fipar, said:</b> <i>"This partnership reflects our ambition to strengthen Morocco\'s technological foundations and support the emergence of a modern, resilient and inclusive digital ecosystem. The new platform will play a pivotal role in accelerating the country\'s digital transition."</i></p>
<p><b>Younes Peter Amine, CEO of Munisys, commented:</b> <i>"Helios and Fipar\'s backing will allow us to expand our cloud and cybersecurity offerings while enhancing the breadth and reliability of services our customers rely on. Together with MDC and Medasys, we will deliver a truly end-to-end, locally hosted and secure platform for Morocco\'s digital economy."</i></p>
<p><b>Abderrahmane Mounir, President and CEO of Maroc Datacenter, added:</b> <i>"Becoming part of 3MDC is an important step in MDC\'s evolution, and we look forward to help build Morocco\'s leading digital infrastructure group and support the country\'s digital transformation."</i></p>
<p align="center"><b>ENDS</b></p>
<p><b>For further information please contact:</b></p>
<p><b>For Helios:</b><br>
Teneo<br>
<a href="mailto:heliosmedia@teneo.com">heliosmedia@teneo.com</a><br>
+44 (0)20 7260 2700</p>
<p><b>For Fipar-Holding:</b><br>
Salma Benjalloun<br>
<a href="mailto:salma.benjalloun@cdginvest.ma">salma.benjalloun@cdginvest.ma</a><br>
+212 6 61 50 51 24</p>
<p><b>About Helios Investment Partners</b><br>
Established in 2004, Helios Investment Partners is the largest Africa-focused private investment firm, with a record that spans creating start-ups to providing expanding companies with growth capital and expertise. The firm is led and managed by a predominantly African team based in London, Paris, Lagos and Nairobi, with the language skills and cultural affinity to engage with local entrepreneurs, managers and intermediaries on the continent.</p>
<p><b>About Fipar-Holding</b><br>
Fipar-Holding is an investment firm owned by CDG Invest, the investment arm of the Caisse de Dépôt et de Gestion (CDG) Group. As a proactive and committed minority financial investor, Fipar-Holding strives to initiate or accelerate the growth and development of major projects in Morocco. Its mission is to support both private and public Moroccan companies in becoming key players domestically and internationally.</p>
<p><b>About Munisys</b><br>
As a premier digital infrastructure integrator, Munisys empowers public and private enterprises throughout Morocco. With over three decades of expertise, the company has cultivated a unique skill set in deploying and managing private and hybrid infrastructure environments. Munisys supports its clients through the entire lifecycle of their digital assets—from design and deployment to evolution and operations—covering the spectrum from critical core architectures to end-user solutions.</p>';
    } elseif (strpos($slug, 'new-headquarters') !== false) {
        $title = 'New headquarters';
        $date = '12 May 2023';
        $image = 'https://www.munisys.ma/uploads/actualites/27ddbef45a9b70352bcd44a4eb7aee76f0258295.png';
        $html_content = '<p>Munisys is excited to announce that in a few days our headquarters in Casablanca are going to move to our newly built address in the heart of the Green City of Bouskoura.</p>
<p>Easily accessible and surrounded by forest and greenery, these new offices will provide our customers, partners and Munisys\' teams the ideal environment for thinking, innovating, working and sharing. With about 2000 square meters of office space, everything has been designed to align with our values and culture and to achieve the right balance between privacy and connection, enclosure and perspective, serenity and energy, thus efficiently supporting our daily mission to transform great ideas into successful projects.</p>
<p>We hope we can welcome you soon in our new headquarters!</p>';
    } elseif (strpos($slug, 'a-new-visual-identity-for-munisys') !== false) {
        $title = 'A new visual identity for Munisys';
        $date = '31 May 2023';
        $image = 'https://www.munisys.ma/uploads/actualites/718cff8dc6ac06408b39c9f129b20e33c44d7d10.png';
        $html_content = '<p>After more than 30 years of innovation, commitment and excellence shared with our valued clients, it is time to adopt a new visual identity, to carry our values, mark our singularities, and translate our ambitions.</p>
<p>Starting from 31 May 2023, we will wear new colors and use a new logo and typography to better reflect who we are.</p>
<p>This (r)evolution will have an impact on how we are seen from the outside, but only reinforce what makes the essence of Munisys, our culture and how we serve our clients: integrity, excellence and long-term thinking. This is what makes us different and recognizable</p>';
    } elseif (strpos($slug, 'gitex-africa') !== false) {
        $title = 'Come and visit us at Gitex Africa';
        $date = '31 May 2023';
        $image = 'https://www.munisys.ma/uploads/actualites/612051b523703aefc2cb0fcaee22afe42f93eb2d.png';
        $html_content = '<p>Munisys is exhibiting at Gitex Africa 2023, happening in Marrakech from 31 May to 02 June. This first edition is a tremendous opportunity to showcase the immense potential and capacity of the African digital ecosystem, to learn about the infinite possibilities offered when technology meets human skills and creativity, and to share insights about how digital can contribute to elevate our countries and continent.</p>
<p>Come and visit us on booth 2A-5 where we will be sharing the latest IT infrastructure technology solutions and services we offer to help you deploy innovative and value added digital services</p>';
    } else {
        $title = 'Insight Details';
        $date = '...';
        $image = '';
        $html_content = '<p>Veuillez vérifier le permalien de la page. Le permalien doit contenir "press-release", "new-headquarters", "a-new-visual-identity-for-munisys" ou "gitex-africa". 
        <br>Sinon, vous pouvez forcer le contenu en tapant par exemple : [munisys_insight_detail id="new-headquarters"]</p>';
    }

    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap');

        #munisys-wrapper {
            font-family: 'Montserrat', sans-serif !important;
            background: #ffffff;
            color: #333333;
            -webkit-font-smoothing: antialiased;
            width: 100vw !important;
            position: relative;
            left: 50% !important;
            right: 50% !important;
            margin-left: -50vw !important;
        }

        #munisys-wrapper .pr-banner {
            background: #1d4e79;
            color: #fff;
            padding: 6rem 10%;
            text-align: left;
        }

        #munisys-wrapper .pr-banner h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -1px;
        }

        #munisys-wrapper .pr-content {
            padding: 5rem 10%;
            max-width: 1000px;
            margin: 0 auto;
        }

        #munisys-wrapper .pr-date {
            display: block;
            color: #922b55;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        #munisys-wrapper .pr-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1d4e79;
            margin-top: 0;
            margin-bottom: 3rem;
            line-height: 1.2;
            letter-spacing: -1px;
        }

        #munisys-wrapper .pr-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        #munisys-wrapper .pr-body {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
        }

        #munisys-wrapper .pr-body p {
            margin-bottom: 1.5rem;
        }

        #munisys-wrapper .pr-body b {
            color: #1d4e79;
        }

        @media(max-width:768px) {
            #munisys-wrapper .pr-banner {
                padding: 4rem 5%;
            }

            #munisys-wrapper .pr-banner h1 {
                font-size: 2.5rem;
            }

            #munisys-wrapper .pr-content {
                padding: 3rem 5%;
            }

            #munisys-wrapper .pr-title {
                font-size: 2rem;
            }
        }
    </style>

    <div id="munisys-wrapper">
        <!-- BANNER -->
        <div class="pr-banner">
            <h1>Insights</h1>
        </div>

        <!-- ARTICLE CONTENT -->
        <div class="pr-content">
            <span class="pr-date"><?php echo esc_html($date); ?></span>
            <h2 class="pr-title"><?php echo esc_html($title); ?></h2>

            <?php if (!empty($image)): ?>
                <img class="pr-image" src="<?php echo esc_url($image); ?>" alt="Insight Image">
            <?php endif; ?>

            <div class="pr-body">
                <?php echo $html_content; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// ==========================================
// SHORTCODE [munisys_header]
// ==========================================
add_shortcode('munisys_header', 'render_munisys_header_shortcode');
function render_munisys_header_shortcode()
{
    $c_primary = get_option('munisys_color_primary', '#922b55');
    $c_dark = get_option('munisys_color_dark', '#1d4e79');
    $c_light = get_option('munisys_color_light', '#83a5c2');

    $logo_base64 = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+Cjxzdmcgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDg5MyAyMDYiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM6c2VyaWY9Imh0dHA6Ly93d3cuc2VyaWYuY29tLyIgc3R5bGU9ImZpbGwtcnVsZTpldmVub2RkO2NsaXAtcnVsZTpldmVub2RkO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoyOyI+CiAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgxLDAsMCwxLC00NDEuMzM5LC03OC4zNDU4KSI+CiAgICAgICAgPGcgdHJhbnNmb3JtPSJtYXRyaXgoMSwwLDAsMSwxMy44NTc5LC0xMDMuMzc2KSI+CiAgICAgICAgICAgIDxnIHRyYW5zZm9ybT0ibWF0cml4KDIuMTUyMDksMCwwLDIuMTE3NTYsLTExOC40MzcsLTYwMDQuMTUpIj4KICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik00MjMuOTc3LDI5NzAuMjZMNDIzLjk3NywyOTIyLjQ1TDQwNy41NjUsMjkyMi40NUwzOTcuMzMzLDI5NTMuMUwzOTcuMDY3LDI5NTMuMUwzODYuMzcsMjkyMi40NUwzNzAuMjI0LDI5MjIuNDVMMzcwLjIyNCwyOTcwLjI2TDM4MC45MjEsMjk3MC4yNkwzODAuNjU1LDI5MzYuNDJMMzgwLjg1NSwyOTM2LjQyTDM5Mi43NDgsMjk3MC4yNkw0MDAuNzIxLDI5NzAuMjZMNDEyLjk0NywyOTM2LjQyTDQxMy4xNDYsMjkzNi40Mkw0MTIuODgxLDI5NzAuMjZMNDIzLjk3NywyOTcwLjI2WiIgc3R5bGU9ImZpbGw6cmdiKDM3LDEwMSwxNDMpO2ZpbGwtcnVsZTpub256ZXJvOyIvPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTQ3My41NDQsMjk1Mi4yMkw0NzMuNTQ0LDI5MjIuNDVMNDYyLjE4MiwyOTIyLjQ1TDQ2Mi4xODIsMjk1MS4yOEM0NjIuMTgyLDI5NTYuNDEgNDU5Ljc5LDI5NjEuMTQgNDUzLjM0NSwyOTYxLjE0QzQ0Ni45NjYsMjk2MS4xNCA0NDQuNTA4LDI5NTYuNDEgNDQ0LjUwOCwyOTUxLjI4TDQ0NC41MDgsMjkyMi40NUw0MzMuMjEyLDI5MjIuNDVMNDMzLjIxMiwyOTUyLjIyQzQzMy4yMTIsMjk2My43IDQ0MC45MiwyOTcxLjU0IDQ1My4yNzksMjk3MS41NEM0NjUuNTcxLDI5NzEuNTQgNDczLjU0NCwyOTYzLjcgNDczLjU0NCwyOTUyLjIyWiIgc3R5bGU9ImZpbGw6cmdiKDM3LDEwMSwxNDMpO2ZpbGwtcnVsZTpub256ZXJvOyIvPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTUyNS41MDMsMjk3MC4yNkw1MjUuNTAzLDI5MjIuNDVMNTE0LjQ3MywyOTIyLjQ1TDUxNC43MzksMjk1My42NEw1MTQuNTQsMjk1My42NEw0OTUuNjcsMjkyMi40NUw0ODIuNzEzLDI5MjIuNDVMNDgyLjcxMywyOTcwLjI2TDQ5My43NDMsMjk3MC4yNkw0OTMuNDc3LDI5MzguOTlMNDkzLjY3NiwyOTM4Ljk5TDUxMi42MTMsMjk3MC4yNkw1MjUuNTAzLDI5NzAuMjZaIiBzdHlsZT0iZmlsbDpyZ2IoMzcsMTAxLDE0Myk7ZmlsbC1ydWxlOm5vbnplcm87Ii8+CiAgICAgICAgICAgICAgICA8cmVjdCB4PSI1MzUuMjA0IiB5PSIyOTIyLjQ1IiB3aWR0aD0iMTEuNDI4IiBoZWlnaHQ9IjQ3LjgwOSIgc3R5bGU9ImZpbGw6cmdiKDM3LDEwMSwxNDMpO2ZpbGwtcnVsZTpub256ZXJvOyIvPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTU4OC40MjUsMjkyNy4xMUM1ODQuMzA2LDI5MjMuMjYgNTc4LjMyNiwyOTIxLjIzIDU3My4wMSwyOTIxLjIzQzU2NC4yNCwyOTIxLjIzIDU1NC44MDUsMjkyNS42MiA1NTQuODA1LDI5MzYuMjJDNTU0LjgwNSwyOTQ0Ljg3IDU2MC44NTEsMjk0Ny45NyA1NjYuODMxLDI5NDkuOTNDNTczLjAxLDI5NTEuOTUgNTc2LjU5OCwyOTUzLjEgNTc2LjU5OCwyOTU2Ljc1QzU3Ni41OTgsMjk2MC42IDU3My41NDIsMjk2MS45NSA1NzAuMDg3LDI5NjEuOTVDNTY2LjM2NiwyOTYxLjk1IDU2Mi4xOCwyOTU5Ljc5IDU1OS45MjEsMjk1Ni44OUw1NTIuNDc5LDI5NjQuNThDNTU2LjU5OSwyOTY4Ljk3IDU2My4zNzYsMjk3MS40NyA1NzAuMDg3LDI5NzEuNDdDNTc5LjM4OSwyOTcxLjQ3IDU4OC4wOTMsMjk2Ni41NCA1ODguMDkzLDI5NTUuNTNDNTg4LjA5MywyOTQ2LjAxIDU3OS44NTQsMjk0My4yNCA1NzMuNDc1LDI5NDEuMTVDNTY5LjAyNCwyOTM5LjczIDU2Ni4yMzMsMjkzOC43MiA1NjYuMjMzLDI5MzUuNTVDNTY2LjIzMywyOTMxLjc2IDU2OS44ODcsMjkzMC43NSA1NzIuODExLDI5MzAuNzVDNTc1LjczNCwyOTMwLjc1IDU3OS4yNTYsMjkzMi4zNyA1ODEuMTgzLDI5MzQuODdMNTg4LjQyNSwyOTI3LjExWiIgc3R5bGU9ImZpbGw6cmdiKDM3LDEwMSwxNDMpO2ZpbGwtcnVsZTpub256ZXJvOyIvPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTYzNS41MzQsMjkyMi40NUw2MjIuMjQ1LDI5MjIuNDVMNjEyLjIxMiwyOTQwLjE0TDYwMi4xNzksMjkyMi40NUw1ODguNDI1LDI5MjIuNDVMNjA2LjA5OSwyOTUwTDYwNi4wOTksMjk3MC4yNkw2MTcuNDYxLDI5NzAuMjZMNjE3LjQ2MSwyOTUwTDYzNS41MzQsMjkyMi40NVoiIHN0eWxlPSJmaWxsOnJnYigzNywxMDEsMTQzKTtmaWxsLXJ1bGU6bm9uemVybzsiLz4KICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik02NjguNDksMjkyNy4xMUM2NjQuMzcsMjkyMy4yNiA2NTguMzkxLDI5MjEuMjMgNjUzLjA3NSwyOTIxLjIzQzY0NC4zMDQsMjkyMS4yMyA2MzQuODY5LDI5MjUuNjIgNjM0Ljg2OSwyOTM2LjIyQzYzNC44NjksMjk0NC44NyA2NDAuOTE2LDI5NDcuOTcgNjQ2Ljg5NiwyOTQ5LjkzQzY1My4wNzUsMjk1MS45NSA2NTYuNjYzLDI5NTMuMSA2NTYuNjYzLDI5NTYuNzVDNjU2LjY2MywyOTYwLjYgNjUzLjYwNywyOTYxLjk1IDY1MC4xNTEsMjk2MS45NUM2NDYuNDMxLDI5NjEuOTUgNjQyLjI0NSwyOTU5Ljc5IDYzOS45ODYsMjk1Ni44OUw2MzIuNTQ0LDI5NjQuNThDNjM2LjY2MywyOTY4Ljk3IDY0My40NDEsMjk3MS40NyA2NTAuMTUxLDI5NzEuNDdDNjU5LjQ1NCwyOTcxLjQ3IDY2OC4xNTgsMjk2Ni41NCA2NjguMTU4LDI5NTUuNTNDNjY4LjE1OCwyOTQ2LjAxIDY1OS45MTksMjk0My4yNCA2NTMuNTQsMjk0MS4xNUM2NDkuMDg4LDI5MzkuNzMgNjQ2LjI5OCwyOTM4LjcyIDY0Ni4yOTgsMjkzNS41NUM2NDYuMjk4LDI5MzEuNzYgNjQ5Ljk1MiwyOTMwLjc1IDY1Mi44NzYsMjkzMC43NUM2NTUuNzk5LDI5MzAuNzUgNjU5LjMyMSwyOTMyLjM3IDY2MS4yNDgsMjkzNC44N0w2NjguNDksMjkyNy4xMVoiIHN0eWxlPSJmaWxsOnJnYigzNywxMDEsMTQzKTtmaWxsLXJ1bGU6bm9uemVybzsiLz4KICAgICAgICAgICAgPC9nPgogICAgICAgICAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgwLjk1MDY3MywwLDAsMC45NTA2NzMsMTcwLjQ3LC04MjcuNzAzKSI+CiAgICAgICAgICAgICAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgwLjg5MTUwMSwwLDAsMC44OTE1MDEsOTMuNjIwOSwtMTkzLjAwNikiPgogICAgICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0xOTguMjMzLDE1MzAuMDVMMzE3LjcyOCwxNjQ5LjU1TDE5OC4yNTIsMTY0OS41NUwxOTguMjMzLDE1MzAuMDVaIiBzdHlsZT0iZmlsbDpyZ2IoMTEzLDE2MywxOTYpOyIvPgogICAgICAgICAgICAgICAgPC9nPgogICAgICAgICAgICAgICAgPGcgdHJhbnNmb3JtPSJtYXRyaXgoLTAuODkxNTAxLDAsMCwtMC44OTE1MDEsNTUzLjYsMjUzNS4wOCkiPgogICAgICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0xOTguMjMzLDE1MzAuMDVMMzE3LjcyOCwxNjQ5LjU1TDE5OC4yNTIsMTY0OS41NUwxOTguMjMzLDE1MzAuMDVaIiBzdHlsZT0iZmlsbDpyZ2IoMTQzLDM3LDEwMSk7Ii8+CiAgICAgICAgICAgICAgICA8L2c+CiAgICAgICAgICAgICAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgwLjg5MTUwMSwwLDAsMC44OTE1MDEsOTMuNjIwOSwtMTkzLjAwNikiPgogICAgICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0zMTcuNzI4LDE1MzAuMDVMMzE3LjcyOCwxNjQ5LjU1TDQzNy4yMjMsMTUzMC4wNUw0MzcuMjIzLDE0MTAuNTZMMzE3LjcyOCwxNTMwLjA1WiIgc3R5bGU9ImZpbGw6cmdiKDM3LDEwMSwxNDMpOyIvPgogICAgICAgICAgICAgICAgPC9nPgogICAgICAgICAgICA8L2c+CiAgICAgICAgPC9nPgogICAgPC9nPgo8L3N2Zz4K';
    update_option('munisys_header_logo', $logo_base64);
    $logo = get_option('munisys_header_logo', $logo_base64);
    $contact_txt = get_option('munisys_header_contact_text', 'Contact');
    $contact_lnk = get_option('munisys_header_contact_link', '/contact');

    // Services
    $srv_txt = get_option('munisys_menu_srv_text', 'Services');
    $srv_lnk = get_option('munisys_menu_srv_link', '/services');

    // Customer ref
    $ref_txt = get_option('munisys_menu_ref_text', 'Customer references');
    $ref_lnk = get_option('munisys_menu_ref_link', '/customer-references');

    // Tech partners
    $part_txt = get_option('munisys_menu_part_text', 'Technology partners');
    $part_lnk = get_option('munisys_menu_part_link', '/technology-partners');

    // Insights
    $ins_txt = get_option('munisys_menu_ins_text', 'Insights');
    $ins_lnk = get_option('munisys_menu_ins_link', '/insights');

    // Careers
    $car_txt = get_option('munisys_menu_car_text', 'Careers');
    $car_lnk = get_option('munisys_menu_car_link', '/careers');

    // About us
    $abt_txt = get_option('munisys_menu_abt_text', 'About us');
    $abt_lnk = get_option('munisys_menu_abt_link', '/about-us');

    // Solutions (Mega Menu)
    $def_sol = [
        [
            'title' => 'Core Infrastructure Technologies',
            'link' => '/solutions#cloud-and-data-center-solutions',
            'items' => [
                'Compute & storage' => '/solutions#compute-storage-1',
                'Hyper converged infrastructures' => '/solutions#hyper-converged-infrastructures-1',
                'Data resilience' => '/solutions#data-resilience-1',
                'Networking' => '/solutions#networking-1',
                'Cabling & data center environment' => '/solutions#cabling-data-center-environment-1',
                'Technology partners' => '/solutions#technology-partners-1'
            ]
        ],
        [
            'title' => 'Cybersecurity',
            'link' => '/solutions#cybersecurity',
            'items' => [
                'Endpoint Security' => '/solutions#endpoint-security-2',
                'Network Security' => '/solutions#network-security-2',
                'Cloud & Application Security' => '/solutions#cloud-application-security-2',
                'Identity and Access Management' => '/solutions#identity-and-access-management-2',
                'Visibility & Compliance' => '/solutions#visibility-compliance-2',
                'Technology partners' => '/solutions#technology-partners-2'
            ]
        ],
        [
            'title' => 'Infrastructure software',
            'link' => '/solutions#infrastructure-software',
            'items' => [
                'Service & Asset Management' => '/solutions#service-asset-management-3',
                'Observability, Automation & Orchestration' => '/solutions#observability-automation-orchestration-3',
                'Data Management & Analytics' => '/solutions#data-management-analytics-3',
                'Application modernization' => '/solutions#application-modernization-3',
                'Data resilience' => '/solutions#data-resilience-3',
                'Technology partners' => '/solutions#technology-partners-3'
            ]
        ],
        [
            'title' => 'Managed & Cloud Services',
            'link' => '/solutions#mcs',
            'items' => [
                'Hosting and Cloud Infrastructure Services' => '/solutions#hosting-and-cloud-infrastructure-services-4',
                'Application and Platforms Services' => '/solutions#application-and-platforms-services-4',
                'Business Resilience & Data Protection' => '/solutions#business-resilience-data-protection-4',
                'Managed Security Services' => '/solutions#managed-security-services-4',
                'Advisory, Professional and Management Services' => '/solutions#advisory-professional-and-management-services-4'
            ]
        ],
        [
            'title' => 'Endpoint solutions',
            'link' => '/solutions#endpoint-solutions',
            'items' => [
                'Digital workplace' => '/solutions#digital-workplace-5',
                'Printing solutions' => '/solutions#printing-solutions-5',
                'End customer facing devices' => '/solutions#end-customer-facing-devices-5',
                'Technology partners' => '/solutions#technology-partners-5'
            ]
        ]
    ];

    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap');

        /* ========================================== */
        /* ELIMINATE WHITE GAPS AROUND HEADER         */
        /* ========================================== */
        #munisys-global-header {
            margin: 0 !important;
            padding: 0 !important;
            display: block !important;
        }

        /* If Breakdance wraps our shortcode in a section, kill its padding completely */
        .bde-section:has(#munisys-global-header),
        header:has(#munisys-global-header),
        .bde-shortcode:has(#munisys-global-header),
        div:has(> #munisys-global-header) {
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
        }

        /* Kill WordPress wpautop empty tags near our header */
        #munisys-global-header~p:empty,
        p:has(> #munisys-global-header) {
            display: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* ------------------------------------- */
        /* ELIMINATE GOOGLE TRANSLATE WHITE GAP  */
        /* ------------------------------------- */
        body {
            top: 0 !important;
        }

        .skiptranslate {
            display: none !important;
            height: 0 !important;
        }

        #goog-gt-tt {
            display: none !important;
        }

        /* ========================================== */

        #munisys-global-header {
            --bordeaux:
                <?php echo esc_attr($c_primary); ?>
            ;
            --blue-dark:
                <?php echo esc_attr($c_dark); ?>
            ;
            --blue-light:
                <?php echo esc_attr($c_light); ?>
            ;
            --text-dark: #333333;
            --text-gray: #666666;
            --bg-gray: #fcfcfc;
            font-family: 'Montserrat', sans-serif !important;
        }

        #munisys-global-header *,
        #munisys-global-header *::before,
        #munisys-global-header *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif !important;
        }

        #munisys-global-header a {
            text-decoration: none;
            color: inherit;
        }

        #munisys-global-header ul {
            list-style: none;
        }

        #munisys-global-header button {
            font-family: inherit;
            cursor: pointer;
            border: none;
            background: none;
        }

        #munisys-global-header .nav {
            position: sticky !important;
            top: 0 !important;
            z-index: 999999 !important;
            background: #ffffff !important;
            height: 70px !important;
            display: flex !important;
            align-items: stretch !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05) !important;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        #munisys-global-header .nav.nav-hidden {
            transform: translateY(-100%) !important;
        }

        #munisys-global-header .nav-container {
            max-width: 1440px !important;
            margin: 0 auto !important;
            padding: 0 1rem !important;
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 0.5rem !important;
            position: relative !important;
        }

        #munisys-global-header .nav-logo {
            flex-shrink: 0 !important;
            display: flex !important;
            align-items: center !important;
            z-index: 100 !important;
        }

        #munisys-global-header .nav-logo img {
            height: 38px !important;
            width: auto !important;
        }

        /* Desktop Nav Links */
        #munisys-global-header .nav-links {
            display: flex !important;
            align-items: stretch !important;
            height: 100% !important;
            gap: 0.2rem !important;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.4s ease !important;
        }

        #munisys-global-header .nav-item {
            position: static !important;
            display: flex !important;
            align-items: center !important;
            height: 100% !important;
            font-size: 0.65rem !important;
            font-weight: 600 !important;
            color: var(--text-dark) !important;
            white-space: nowrap !important;
            transition: color 0.3s ease !important;
            letter-spacing: 0 !important;
        }

        #munisys-global-header .nav-item:hover,
        #munisys-global-header .nav-item.active-page {
            color: var(--bordeaux) !important;
        }

        /* Animated Underline */
        #munisys-global-header .nav-item>a,
        #munisys-global-header .nav-item>button.mega-toggle {
            position: relative !important;
        }

        #munisys-global-header .nav-item>a::after,
        #munisys-global-header .nav-item>button.mega-toggle::after {
            content: '' !important;
            position: absolute !important;
            bottom: -4px !important;
            left: 0 !important;
            width: 0 !important;
            height: 2px !important;
            background: var(--bordeaux) !important;
            transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        #munisys-global-header .nav-item:hover>a::after,
        #munisys-global-header .nav-item:hover>button.mega-toggle::after,
        #munisys-global-header .nav-item.active-page>a::after {
            width: 100% !important;
        }

        /* ------------------------------------- */
        /* PREMIUM PILL LANGUAGE SWITCHER IN HEADER */
        /* ------------------------------------- */
        #munisys-global-header .custom-lang-switcher {
            display: inline-flex !important;
            align-items: center !important;
            background: #f1f3f5 !important;
            border-radius: 50px !important;
            padding: 3px !important;
            position: relative !important;
            border: 1px solid #e9ecef !important;
            box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.05) !important;
            margin-right: 5px !important;
        }

        #munisys-global-header .custom-lang-switcher .switcher-bg {
            position: absolute !important;
            top: 3px !important;
            left: 3px !important;
            bottom: 3px !important;
            width: 40px !important;
            background: #ffffff !important;
            border-radius: 50px !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
            transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1) !important;
            z-index: 1 !important;
        }

        #munisys-global-header .custom-lang-switcher[data-active="fr"] .switcher-bg {
            transform: translateX(100%) !important;
        }

        #munisys-global-header .custom-lang-switcher .lang-btn {
            position: relative !important;
            z-index: 2 !important;
            background: transparent !important;
            border: none !important;
            color: #868e96 !important;
            cursor: pointer !important;
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 700 !important;
            font-size: 0.65rem !important;
            letter-spacing: 0.5px !important;
            padding: 5px 0 !important;
            width: 40px !important;
            flex-shrink: 0 !important;
            text-align: center !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease !important;
        }

        #munisys-global-header .custom-lang-switcher .lang-btn:hover {
            color: #343a40 !important;
        }

        #munisys-global-header .custom-lang-switcher .lang-btn.active {
            color: #922b55 !important;
        }

        /* ------------------------------------- */

        #munisys-global-header .nav-item>a,
        #munisys-global-header .nav-item>button {
            display: flex !important;
            align-items: center !important;
            height: 100% !important;
            padding: 0 0.2rem !important;
            color: inherit;
            font-size: inherit;
            font-weight: inherit;
        }

        #munisys-global-header .nav-item>button {
            gap: 0.25rem !important;
        }

        #munisys-global-header .nav-item>button svg {
            width: 14px;
            height: 14px;
            transition: transform 0.3s ease !important;
        }

        #munisys-global-header .nav-item:hover>button svg {
            transform: rotate(180deg) !important;
        }

        /* Mega Menu */
        #munisys-global-header .mega-menu {
            position: absolute !important;
            top: 100% !important;
            left: 50% !important;
            transform: translateX(-50%) translateY(10px) !important;
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08) !important;
            padding: 2rem !important;
            width: 1050px !important;
            max-width: 90vw !important;
            opacity: 0 !important;
            visibility: hidden !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            display: grid !important;
            grid-template-columns: repeat(5, 1fr) !important;
            gap: 1.5rem !important;
            border-top: 3px solid var(--bordeaux) !important;
            white-space: normal !important;
        }

        #munisys-global-header .nav-item:hover .mega-menu {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateX(-50%) translateY(0) !important;
        }

        #munisys-global-header .mega-col {
            display: flex !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }

        #munisys-global-header .mega-col-title {
            font-size: 0.9rem !important;
            font-weight: 700 !important;
            color: var(--blue-dark) !important;
            text-decoration: none !important;
            margin-bottom: 0.5rem !important;
            display: block !important;
            line-height: 1.2 !important;
        }

        #munisys-global-header .mega-col-title:hover {
            color: var(--bordeaux) !important;
        }

        #munisys-global-header .mega-col-link {
            font-size: 0.75rem !important;
            color: var(--text-gray) !important;
            text-decoration: none !important;
            transition: color 0.3s ease !important;
            line-height: 1.4 !important;
            padding: 0.15rem 0 !important;
        }

        #munisys-global-header .mega-col-link:hover {
            color: var(--bordeaux) !important;
        }

        /* Language Switcher */
        #munisys-global-header .lang-switcher {
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            color: var(--text-dark) !important;
        }

        #munisys-global-header .lang-btn {
            background: transparent !important;
            border: none !important;
            color: var(--text-gray) !important;
            cursor: pointer !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            padding: 0 !important;
            transition: color 0.3s ease !important;
        }

        #munisys-global-header .lang-btn:hover,
        #munisys-global-header .lang-btn.active {
            color: var(--bordeaux) !important;
        }

        #munisys-global-header .lang-sep {
            color: var(--text-gray) !important;
            opacity: 0.5 !important;
        }

        /* Hide default Google Translate UI elements */
        body {
            top: 0 !important;
        }

        .skiptranslate {
            display: none !important;
        }

        #goog-gt-tt {
            display: none !important;
        }

        /* Nav Actions */
        #munisys-global-header .nav-actions {
            display: flex !important;
            align-items: center !important;
            gap: 0.3rem !important;
            z-index: 100 !important;
        }

        #munisys-global-header .search-btn {
            color: var(--text-dark) !important;
            transition: color 0.3s ease !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        #munisys-global-header .search-btn:hover {
            color: var(--bordeaux) !important;
        }

        #munisys-global-header .nav-cta {
            display: flex !important;
            align-items: center !important;
            gap: 0.3rem !important;
            background: var(--bordeaux) !important;
            color: #ffffff !important;
            padding: 0.4rem 0.8rem !important;
            border-radius: 50px !important;
            font-size: 0.65rem !important;
            font-weight: 700 !important;
            transition: all 0.3s ease !important;
        }

        #munisys-global-header .nav-cta:hover {
            background: var(--blue-dark) !important;
            color: #ffffff !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 15px rgba(29, 78, 121, 0.2) !important;
        }
        
        #munisys-global-header .nav-cta:hover span,
        #munisys-global-header .nav-cta:hover svg {
            color: #ffffff !important;
        }

        /* Mobile Hamburger Button */
        #munisys-global-header .mobile-toggle {
            display: none !important;
            color: var(--text-dark) !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Mobile Overlay */
        #munisys-global-header .mobile-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background: rgba(0, 0, 0, 0.5) !important;
            z-index: 99 !important;
            opacity: 0 !important;
            visibility: hidden !important;
            transition: opacity 0.3s ease !important;
            backdrop-filter: blur(4px) !important;
        }

        #munisys-global-header.menu-open .mobile-overlay {
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Search Overlay */
        .munisys-global-search-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background: rgba(255, 255, 255, 0.85) !important;
            z-index: 9999999 !important;
            opacity: 0 !important;
            visibility: hidden !important;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        body.munisys-search-active .munisys-global-search-overlay,
        .munisys-global-search-overlay.show-search {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }

        .munisys-global-search-overlay .search-close {
            position: absolute !important;
            top: 2rem !important;
            right: 2rem !important;
            background: none !important;
            border: none !important;
            color: #333 !important;
            cursor: pointer !important;
            padding: 1rem !important;
            transition: transform 0.3s ease !important;
        }

        .munisys-global-search-overlay .search-close:hover {
            transform: scale(1.1) rotate(90deg) !important;
            color: #922b55 !important;
        }

        .munisys-global-search-overlay .search-form {
            width: 100% !important;
            max-width: 800px !important;
            padding: 0 2rem !important;
            position: relative !important;
            transform: translateY(20px) !important;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        body.munisys-search-active .munisys-global-search-overlay .search-form {
            transform: translateY(0) !important;
        }

        .munisys-global-search-overlay .search-input {
            width: 100% !important;
            border: none !important;
            border-bottom: 2px solid #ddd !important;
            background: transparent !important;
            font-size: 3rem !important;
            font-weight: 700 !important;
            color: #333 !important;
            padding: 1rem 4rem 1rem 0 !important;
            outline: none !important;
            font-family: inherit !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .munisys-global-search-overlay .search-input:focus {
            border-bottom-color: #922b55 !important;
        }

        .munisys-global-search-overlay .search-submit {
            position: absolute !important;
            right: 2rem !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            background: none !important;
            border: none !important;
            color: #922b55 !important;
            cursor: pointer !important;
        }

        /* Live Search Results */
        .live-search-results {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            width: 100% !important;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            border-radius: 0 0 12px 12px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08) !important;
            max-height: 400px !important;
            overflow-y: auto !important;
            opacity: 0 !important;
            visibility: hidden !important;
            transform: translateY(10px) !important;
            transition: all 0.3s ease !important;
            text-align: left !important;
        }

        .live-search-results.active {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
        }

        .live-search-result-item {
            display: block !important;
            padding: 1rem 2rem !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
            transition: background 0.3s ease !important;
            text-decoration: none !important;
        }

        .live-search-result-item:hover {
            background: rgba(0, 0, 0, 0.02) !important;
        }

        .live-search-result-title {
            font-size: 1rem !important;
            font-weight: 600 !important;
            color: var(--blue-dark) !important;
            margin-bottom: 0.25rem !important;
        }

        .live-search-result-type {
            font-size: 0.75rem !important;
            color: var(--bordeaux) !important;
            text-transform: uppercase !important;
            font-weight: 700 !important;
        }

        .live-search-spinner {
            padding: 2rem !important;
            text-align: center !important;
            color: var(--text-gray) !important;
            font-size: 0.9rem !important;
        }

        @media (max-width: 768px) {
            .munisys-global-search-overlay .search-input {
                font-size: 2rem !important;
            }
        }

        /* Responsive Mobile CSS */
        @media (max-width: 1300px) {
            #munisys-global-header .nav-actions {
                margin-left: auto !important;
            }

            #munisys-global-header .nav-cta span {
                display: none !important;
            }

            #munisys-global-header .nav-cta {
                padding: 0.6rem !important;
            }

            #munisys-global-header .mobile-toggle {
                display: flex !important;
            }

            #munisys-global-header .nav-links {
                position: fixed !important;
                top: 0 !important;
                right: 0 !important;
                width: 85vw !important;
                max-width: 400px !important;
                height: 100vh !important;
                background: rgba(255, 255, 255, 0.95) !important;
                backdrop-filter: blur(16px) !important;
                -webkit-backdrop-filter: blur(16px) !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                padding: 6rem 2rem 2rem !important;
                margin: 0 !important;
                gap: 0 !important;
                z-index: 999 !important;
                transform: translateX(100%) !important;
                box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1) !important;
                overflow-y: auto !important;
            }

            #munisys-global-header.menu-open .nav-links {
                transform: translateX(0) !important;
            }

            #munisys-global-header .nav-item {
                width: 100% !important;
                height: auto !important;
                border-bottom: 1px solid #eee !important;
            }

            #munisys-global-header .nav-item>a,
            #munisys-global-header .nav-item>button {
                width: 100% !important;
                padding: 1.25rem 0 !important;
                justify-content: space-between !important;
            }

            /* Mobile Accordion for Mega Menu */
            #munisys-global-header .mega-menu {
                position: static !important;
                transform: none !important;
                box-shadow: none !important;
                border: none !important;
                padding: 0 0 1rem 1rem !important;
                width: 100% !important;
                min-width: 0 !important;
                opacity: 1 !important;
                visibility: visible !important;
                display: none !important;
                grid-template-columns: 1fr !important;
                gap: 0.5rem !important;
            }

            #munisys-global-header .nav-item.accordion-open .mega-menu {
                display: grid !important;
            }

            #munisys-global-header .nav-item.accordion-open>button svg {
                transform: rotate(180deg) !important;
            }

            #munisys-global-header .mega-menu-item {
                padding: 0.75rem !important;
            }

            #munisys-global-header .mega-icon {
                width: 32px !important;
                height: 32px !important;
            }

            #munisys-global-header .mega-icon svg {
                width: 16px !important;
                height: 16px !important;
            }

            #munisys-global-header .nav-item:hover .mega-menu {
                transform: none !important;
            }
        }
    </style>

    <div id="munisys-global-header">
        <div class="mobile-overlay"></div>



        <nav class="nav">
            <div class="nav-container">
                <a href="/" class="nav-logo">
                    <img src="<?php echo esc_attr($logo); ?>" alt="Munisys Logo">
                </a>
                <ul class="nav-links">
                    <li class="nav-item">
                        <button class="mega-toggle" aria-expanded="false">Solutions <svg viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6" />
                            </svg></button>
                        <div class="mega-menu">
                            <?php foreach ($def_sol as $col): ?>
                                <div class="mega-col">
                                    <a href="<?php echo esc_url($col['link']); ?>" class="mega-col-title">
                                        <?php echo esc_html($col['title']); ?>
                                    </a>
                                    <?php foreach ($col['items'] as $label => $link): ?>
                                        <a href="<?php echo esc_url($link); ?>" class="mega-col-link">
                                            <?php echo esc_html($label); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </li>
                    <li class="nav-item"><a href="<?php echo esc_url($srv_lnk); ?>"><?php echo esc_html($srv_txt); ?></a>
                    </li>
                    <li class="nav-item"><a href="<?php echo esc_url($ref_lnk); ?>"><?php echo esc_html($ref_txt); ?></a>
                    </li>
                    <li class="nav-item"><a href="<?php echo esc_url($part_lnk); ?>"><?php echo esc_html($part_txt); ?></a>
                    </li>
                    <li class="nav-item"><a href="<?php echo esc_url($ins_lnk); ?>"><?php echo esc_html($ins_txt); ?></a>
                    </li>
                    <li class="nav-item"><a href="<?php echo esc_url($car_lnk); ?>"><?php echo esc_html($car_txt); ?></a>
                    </li>
                    <li class="nav-item"><a href="<?php echo esc_url($abt_lnk); ?>"><?php echo esc_html($abt_txt); ?></a>
                    </li>
                </ul>
                <div class="nav-actions">
                    <button class="search-btn" aria-label="Search" id="munisys-header-search-btn">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8" />
                            <path d="M21 21l-4.35-4.35" />
                        </svg>
                    </button>
                    <div class="custom-lang-switcher" data-active="en">
                        <div class="switcher-bg"></div>
                        <button onclick="setLanguage('en', event)" class="lang-btn active">EN</button>
                        <button onclick="setLanguage('fr', event)" class="lang-btn">FR</button>
                    </div>
                    <a href="<?php echo esc_url($contact_lnk); ?>" class="nav-cta">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <span><?php echo esc_html($contact_txt); ?></span>
                    </a>
                    <button class="mobile-toggle" aria-label="Menu" aria-expanded="false">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>
    </div>

    <script>
        // ==========================================
        // GAP ANNIHILATOR: Forcefully remove Breakdance wrapper spacing
        // ==========================================
        document.addEventListener("DOMContentLoaded", function () {
            var header = document.getElementById('munisys-global-header');
            if (header) {
                var parent = header.parentElement;
                // If wrapped in a Breakdance section or div, kill its spacing
                if (parent && parent.tagName !== 'BODY') {
                    parent.style.setProperty('padding', '0', 'important');
                    parent.style.setProperty('margin', '0', 'important');
                    parent.style.setProperty('min-height', '0', 'important');
                    parent.style.setProperty('border', 'none', 'important');

                    var grandParent = parent.parentElement;
                    if (grandParent && grandParent.tagName !== 'BODY' && grandParent.classList.contains('bde-section')) {
                        grandParent.style.setProperty('padding', '0', 'important');
                        grandParent.style.setProperty('margin', '0', 'important');
                        grandParent.style.setProperty('min-height', '0', 'important');
                    }
                }

                // Check for adjacent empty P tags generated by WordPress
                var sibling = header.nextElementSibling;
                while (sibling) {
                    if (sibling.tagName === 'P' && sibling.innerHTML.trim() === '') {
                        sibling.style.display = 'none';
                    }
                    if (sibling.tagName === 'BR') {
                        sibling.style.display = 'none';
                    }
                    sibling = sibling.nextElementSibling;
                }
            }
        });
    </script>

    <script>
        // Active Page Indicator
        var currentPath = window.location.pathname;
        if (currentPath !== '/' && currentPath !== '') {
            var navLinks = document.querySelectorAll('#munisys-global-header .nav-item > a');
            navLinks.forEach(function (link) {
                var linkUrl = new URL(link.href);
                if (linkUrl.pathname === currentPath || linkUrl.pathname === currentPath + '/') {
                    link.parentElement.classList.add('active-page');
                }
            });
        }

        // Smart Sticky Menu (Headroom effect)
        var lastScrollTop = 0;
        window.addEventListener('scroll', function () {
            var nav = document.querySelector('#munisys-global-header .nav');
            if (!nav) return;
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            if (scrollTop > lastScrollTop && scrollTop > 70) {
                // Downscroll
                nav.classList.add('nav-hidden');
            } else {
                // Upscroll
                nav.classList.remove('nav-hidden');
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        }, { passive: true });

        // Live Search Logic
        var searchInput = document.querySelector('.search-input');
        var searchResults = document.querySelector('.live-search-results');
        var searchTimeout;
        if (searchInput && searchResults) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                var query = this.value.trim();

                if (query.length < 3) {
                    searchResults.classList.remove('active');
                    searchResults.innerHTML = '';
                    return;
                }

                searchResults.innerHTML = '<div class="live-search-spinner">Recherche en cours...</div>';
                searchResults.classList.add('active');

                searchTimeout = setTimeout(function () {
                    Promise.all([
                        fetch('/wp-json/wp/v2/pages?search=' + encodeURIComponent(query) + '&_fields=id,title,link&per_page=3'),
                        fetch('/wp-json/wp/v2/posts?search=' + encodeURIComponent(query) + '&_fields=id,title,link&per_page=3')
                    ]).then(function (responses) {
                        return Promise.all(responses.map(function (r) { return r.json(); }));
                    }).then(function (data) {
                        var pages = data[0] || [];
                        var posts = data[1] || [];
                        var html = '';

                        pages.forEach(function (item) {
                            html += '<a href="' + item.link + '" class="live-search-result-item"><div class="live-search-result-type">Page</div><div class="live-search-result-title">' + item.title.rendered + '</div></a>';
                        });
                        posts.forEach(function (item) {
                            html += '<a href="' + item.link + '" class="live-search-result-item"><div class="live-search-result-type">Article</div><div class="live-search-result-title">' + item.title.rendered + '</div></a>';
                        });

                        if (html === '') {
                            html = '<div class="live-search-spinner">Aucun résultat trouvé pour "' + query + '"</div>';
                        }

                        searchResults.innerHTML = html;
                    }).catch(function (err) {
                        searchResults.innerHTML = '<div class="live-search-spinner">Erreur lors de la recherche.</div>';
                    });
                }, 300);
            });

            // Allow form submission for standard search
            var searchBtnSubmit = document.querySelector('.search-submit');
            if (searchBtnSubmit) {
                searchBtnSubmit.addEventListener('click', function (e) {
                    if (searchInput.value.trim().length > 0) {
                        window.location.href = '/?s=' + encodeURIComponent(searchInput.value.trim());
                    }
                });
            }

            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    if (searchInput.value.trim().length > 0) {
                        window.location.href = '/?s=' + encodeURIComponent(searchInput.value.trim());
                    }
                }
            });
        }

        document.addEventListener('click', function (e) {
            // Search Open
            var searchBtn = e.target.closest('.search-btn');
            if (searchBtn) {
                e.preventDefault();
                document.body.classList.add('munisys-search-active');

                var overlay = document.querySelector('.munisys-global-search-overlay');
                if (overlay && overlay.parentElement !== document.body) {
                    document.body.appendChild(overlay);
                }

                var searchInput = overlay ? overlay.querySelector('.search-input') : null;
                if (searchInput) {
                    setTimeout(function () { searchInput.focus(); }, 100);
                }
                return;
            }

            // Search Close
            var searchClose = e.target.closest('.search-close');
            if (searchClose) {
                e.preventDefault();
                document.body.classList.remove('munisys-search-active');
                return;
            }

            // Mobile Menu Toggle
            var mobileToggle = e.target.closest('.mobile-toggle');
            if (mobileToggle) {
                e.preventDefault();
                var header = mobileToggle.closest('[id="munisys-global-header"]');
                if (header) {
                    header.classList.toggle('menu-open');
                    var isOpen = header.classList.contains('menu-open');
                    mobileToggle.setAttribute('aria-expanded', isOpen);
                    if (isOpen) {
                        mobileToggle.innerHTML = '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
                    } else {
                        mobileToggle.innerHTML = '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>';
                    }
                }
                return;
            }

            // Mobile Overlay Close
            var overlayClick = e.target.closest('.mobile-overlay');
            if (overlayClick) {
                var header = overlayClick.closest('[id="munisys-global-header"]');
                if (header) {
                    header.classList.remove('menu-open');
                    var mToggle = header.querySelector('.mobile-toggle');
                    if (mToggle) {
                        mToggle.setAttribute('aria-expanded', 'false');
                        mToggle.innerHTML = '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>';
                    }
                }
                return;
            }

            // Mega Menu Toggle
            var megaToggle = e.target.closest('.mega-toggle');
            if (megaToggle && window.innerWidth <= 1300) {
                e.preventDefault();
                var navItem = megaToggle.closest('.nav-item');
                if (navItem) {
                    navItem.classList.toggle('accordion-open');
                }
                return;
            }
        }, true);

        // Close search on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                document.body.classList.remove('munisys-search-active');
            }
        }, true);

        // --- Google Translate Integration ---
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({ pageLanguage: 'en', autoDisplay: false }, 'google_translate_element');
        }

        // Check language on page load
        document.addEventListener('DOMContentLoaded', function () {
            var match = document.cookie.match(new RegExp('(^| )googtrans=([^;]+)'));
            var currentLang = 'en'; // default
            if (match && match[2]) {
                var parts = match[2].split('/');
                if (parts.length > 2) {
                    currentLang = parts[2]; // e.g. /en/fr -> fr
                }
            }

            var switcher = document.querySelector('.custom-lang-switcher');
            if (switcher) {
                var buttons = switcher.querySelectorAll('.lang-btn');
                buttons.forEach(function (btn) { btn.classList.remove('active'); });
                var activeBtn = switcher.querySelector('.lang-btn[onclick*="' + currentLang + '"]');
                if (activeBtn) activeBtn.classList.add('active');
                switcher.setAttribute('data-active', currentLang);
            }
        });

        function setLanguage(lang, e) {
            if (e) e.preventDefault();

            // Update active button UI immediately (visual feedback before reload)
            var switcher = document.querySelector('.custom-lang-switcher');
            if (switcher) {
                switcher.querySelectorAll('.lang-btn').forEach(function(btn) {
                    btn.classList.remove('active');
                });
                var activeBtn = switcher.querySelector('.lang-btn[onclick*="' + lang + '"]');
                if (activeBtn) activeBtn.classList.add('active');
                switcher.setAttribute('data-active', lang);
            }

            // Save cookie for the whole domain
            var domain = window.location.hostname;
            if (lang === 'en') {
                // Clear translation cookie to restore original English
                document.cookie = 'googtrans=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; domain=' + domain;
                document.cookie = 'googtrans=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            } else {
                document.cookie = 'googtrans=/en/' + lang + '; path=/; domain=' + domain;
                document.cookie = 'googtrans=/en/' + lang + '; path=/;';
            }

            // Reload to apply translation cleanly and instantly
            window.location.reload();
        }
    </script>
    <div id="google_translate_element" style="display:none;"></div>
    <script type="text/javascript"
        src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <?php
    return ob_get_clean();
}

function munisys_header_settings_html()
{
    if (!current_user_can('edit_pages'))
        return;

    if (isset($_POST['submit_munisys_header'])) {
        update_option('munisys_header_logo', esc_url_raw($_POST['munisys_header_logo']));
        update_option('munisys_header_contact_text', sanitize_text_field($_POST['munisys_header_contact_text']));
        update_option('munisys_header_contact_link', esc_url_raw($_POST['munisys_header_contact_link']));

        $fields = ['srv', 'ref', 'part', 'ins', 'car', 'abt'];
        foreach ($fields as $f) {
            update_option('munisys_menu_' . $f . '_text', sanitize_text_field($_POST['munisys_menu_' . $f . '_text']));
            update_option('munisys_menu_' . $f . '_link', esc_url_raw($_POST['munisys_menu_' . $f . '_link']));
        }
        echo '<div class="notice notice-success is-dismissible"><p>✅ En-tête mis à jour avec succès !</p></div>';
    }

    $logo_base64 = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+Cjxzdmcgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDg5MyAyMDYiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM6c2VyaWY9Imh0dHA6Ly93d3cuc2VyaWYuY29tLyIgc3R5bGU9ImZpbGwtcnVsZTpldmVub2RkO2NsaXAtcnVsZTpldmVub2RkO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoyOyI+CiAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgxLDAsMCwxLC00NDEuMzM5LC03OC4zNDU4KSI+CiAgICAgICAgPGcgdHJhbnNmb3JtPSJtYXRyaXgoMSwwLDAsMSwxMy44NTc5LC0xMDMuMzc2KSI+CiAgICAgICAgICAgIDxnIHRyYW5zZm9ybT0ibWF0cml4KDIuMTUyMDksMCwwLDIuMTE3NTYsLTExOC40MzcsLTYwMDQuMTUpIj4KICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik00MjMuOTc3LDI5NzAuMjZMNDIzLjk3NywyOTIyLjQ1TDQwNy41NjUsMjkyMi40NUwzOTcuMzMzLDI5NTMuMUwzOTcuMDY3LDI5NTMuMUwzODYuMzcsMjkyMi40NUwzNzAuMjI0LDI5MjIuNDVMMzcwLjIyNCwyOTcwLjI2TDM4MC45MjEsMjk3MC4yNkwzODAuNjU1LDI5MzYuNDJMMzgwLjg1NSwyOTM2LjQyTDM5Mi43NDgsMjk3MC4yNkw0MDAuNzIxLDI5NzAuMjZMNDEyLjk0NywyOTM2LjQyTDQxMy4xNDYsMjkzNi40Mkw0MTIuODgxLDI5NzAuMjZMNDIzLjk3NywyOTcwLjI2WiIgc3R5bGU9ImZpbGw6cmdiKDM3LDEwMSwxNDMpO2ZpbGwtcnVsZTpub256ZXJvOyIvPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTQ3My41NDQsMjk1Mi4yMkw0NzMuNTQ0LDI5MjIuNDVMNDYyLjE4MiwyOTIyLjQ1TDQ2Mi4xODIsMjk1MS4yOEM0NjIuMTgyLDI5NTYuNDEgNDU5Ljc5LDI5NjEuMTQgNDUzLjM0NSwyOTYxLjE0QzQ0Ni45NjYsMjk2MS4xNCA0NDQuNTA4LDI5NTYuNDEgNDQ0LjUwOCwyOTUxLjI4TDQ0NC41MDgsMjkyMi40NUw0MzMuMjEyLDI5MjIuNDVMNDMzLjIxMiwyOTUyLjIyQzQzMy4yMTIsMjk2My43IDQ0MC45MiwyOTcxLjU0IDQ1My4yNzksMjk3MS41NEM0NjUuNTcxLDI5NzEuNTQgNDczLjU0NCwyOTYzLjcgNDczLjU0NCwyOTUyLjIyWiIgc3R5bGU9ImZpbGw6cmdiKDM3LDEwMSwxNDMpO2ZpbGwtcnVsZTpub256ZXJvOyIvPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTUyNS41MDMsMjk3MC4yNkw1MjUuNTAzLDI5MjIuNDVMNTE0LjQ3MywyOTIyLjQ1TDUxNC43MzksMjk1My42NEw1MTQuNTQsMjk1My42NEw0OTUuNjcsMjkyMi40NUw0ODIuNzEzLDI5MjIuNDVMNDgyLjcxMywyOTcwLjI2TDQ5My43NDMsMjk3MC4yNkw0OTMuNDc3LDI5MzguOTlMNDkzLjY3NiwyOTM4Ljk5TDUxMi42MTMsMjk3MC4yNkw1MjUuNTAzLDI5NzAuMjZaIiBzdHlsZT0iZmlsbDpyZ2IoMzcsMTAxLDE0Myk7ZmlsbC1ydWxlOm5vbnplcm87Ii8+CiAgICAgICAgICAgICAgICA8cmVjdCB4PSI1MzUuMjA0IiB5PSIyOTIyLjQ1IiB3aWR0aD0iMTEuNDI4IiBoZWlnaHQ9IjQ3LjgwOSIgc3R5bGU9ImZpbGw6cmdiKDM3LDEwMSwxNDMpO2ZpbGwtcnVsZTpub256ZXJvOyIvPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTU4OC40MjUsMjkyNy4xMUM1ODQuMzA2LDI5MjMuMjYgNTc4LjMyNiwyOTIxLjIzIDU3My4wMSwyOTIxLjIzQzU2NC4yNCwyOTIxLjIzIDU1NC44MDUsMjkyNS42MiA1NTQuODA1LDI5MzYuMjJDNTU0LjgwNSwyOTQ0Ljg3IDU2MC44NTEsMjk0Ny45NyA1NjYuODMxLDI5NDkuOTNDNTczLjAxLDI5NTEuOTUgNTc2LjU5OCwyOTUzLjEgNTc2LjU5OCwyOTU2Ljc1QzU3Ni41OTgsMjk2MC42IDU3My41NDIsMjk2MS45NSA1NzAuMDg3LDI5NjEuOTVDNTY2LjM2NiwyOTYxLjk1IDU2Mi4xOCwyOTU5Ljc5IDU1OS45MjEsMjk1Ni44OUw1NTIuNDc5LDI5NjQuNThDNTU2LjU5OSwyOTY4Ljk3IDU2My4zNzYsMjk3MS40NyA1NzAuMDg3LDI5NzEuNDdDNTc5LjM4OSwyOTcxLjQ3IDU4OC4wOTMsMjk2Ni41NCA1ODguMDkzLDI5NTUuNTNDNTg4LjA5MywyOTQ2LjAxIDU3OS44NTQsMjk0My4yNCA1NzMuNDc1LDI5NDEuMTVDNTY5LjAyNCwyOTM5LjczIDU2Ni4yMzMsMjkzOC43MiA1NjYuMjMzLDI5MzUuNTVDNTY2LjIzMywyOTMxLjc2IDU2OS44ODcsMjkzMC43NSA1NzIuODExLDI5MzAuNzVDNTc1LjczNCwyOTMwLjc1IDU3OS4yNTYsMjkzMi4zNyA1ODEuMTgzLDI5MzQuODdMNTg4LjQyNSwyOTI3LjExWiIgc3R5bGU9ImZpbGw6cmdiKDM3LDEwMSwxNDMpO2ZpbGwtcnVsZTpub256ZXJvOyIvPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTYzNS41MzQsMjkyMi40NUw2MjIuMjQ1LDI5MjIuNDVMNjEyLjIxMiwyOTQwLjE0TDYwMi4xNzksMjkyMi40NUw1ODguNDI1LDI5MjIuNDVMNjA2LjA5OSwyOTUwTDYwNi4wOTksMjk3MC4yNkw2MTcuNDYxLDI5NzAuMjZMNjE3LjQ2MSwyOTUwTDYzNS41MzQsMjkyMi40NVoiIHN0eWxlPSJmaWxsOnJnYigzNywxMDEsMTQzKTtmaWxsLXJ1bGU6bm9uemVybzsiLz4KICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik02NjguNDksMjkyNy4xMUM2NjQuMzcsMjkyMy4yNiA2NTguMzkxLDI5MjEuMjMgNjUzLjA3NSwyOTIxLjIzQzY0NC4zMDQsMjkyMS4yMyA2MzQuODY5LDI5MjUuNjIgNjM0Ljg2OSwyOTM2LjIyQzYzNC44NjksMjk0NC44NyA2NDAuOTE2LDI5NDcuOTcgNjQ2Ljg5NiwyOTQ5LjkzQzY1My4wNzUsMjk1MS45NSA2NTYuNjYzLDI5NTMuMSA2NTYuNjYzLDI5NTYuNzVDNjU2LjY2MywyOTYwLjYgNjUzLjYwNywyOTYxLjk1IDY1MC4xNTEsMjk2MS45NUM2NDYuNDMxLDI5NjEuOTUgNjQyLjI0NSwyOTU5Ljc5IDYzOS45ODYsMjk1Ni44OUw2MzIuNTQ0LDI5NjQuNThDNjM2LjY2MywyOTY4Ljk3IDY0My40NDEsMjk3MS40NyA2NTAuMTUxLDI5NzEuNDdDNjU5LjQ1NCwyOTcxLjQ3IDY2OC4xNTgsMjk2Ni41NCA2NjguMTU4LDI5NTUuNTNDNjY4LjE1OCwyOTQ2LjAxIDY1OS45MTksMjk0My4yNCA2NTMuNTQsMjk0MS4xNUM2NDkuMDg4LDI5MzkuNzMgNjQ2LjI5OCwyOTM4LjcyIDY0Ni4yOTgsMjkzNS41NUM2NDYuMjk4LDI5MzEuNzYgNjQ5Ljk1MiwyOTMwLjc1IDY1Mi44NzYsMjkzMC43NUM2NTUuNzk5LDI5MzAuNzUgNjU5LjMyMSwyOTMyLjM3IDY2MS4yNDgsMjkzNC44N0w2NjguNDksMjkyNy4xMVoiIHN0eWxlPSJmaWxsOnJnYigzNywxMDEsMTQzKTtmaWxsLXJ1bGU6bm9uemVybzsiLz4KICAgICAgICAgICAgPC9nPgogICAgICAgICAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgwLjk1MDY3MywwLDAsMC45NTA2NzMsMTcwLjQ3LC04MjcuNzAzKSI+CiAgICAgICAgICAgICAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgwLjg5MTUwMSwwLDAsMC44OTE1MDEsOTMuNjIwOSwtMTkzLjAwNikiPgogICAgICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0xOTguMjMzLDE1MzAuMDVMMzE3LjcyOCwxNjQ5LjU1TDE5OC4yNTIsMTY0OS41NUwxOTguMjMzLDE1MzAuMDVaIiBzdHlsZT0iZmlsbDpyZ2IoMTEzLDE2MywxOTYpOyIvPgogICAgICAgICAgICAgICAgPC9nPgogICAgICAgICAgICAgICAgPGcgdHJhbnNmb3JtPSJtYXRyaXgoLTAuODkxNTAxLDAsMCwtMC44OTE1MDEsNTUzLjYsMjUzNS4wOCkiPgogICAgICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0xOTguMjMzLDE1MzAuMDVMMzE3LjcyOCwxNjQ5LjU1TDE5OC4yNTIsMTY0OS41NUwxOTguMjMzLDE1MzAuMDVaIiBzdHlsZT0iZmlsbDpyZ2IoMTQzLDM3LDEwMSk7Ii8+CiAgICAgICAgICAgICAgICA8L2c+CiAgICAgICAgICAgICAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgwLjg5MTUwMSwwLDAsMC44OTE1MDEsOTMuNjIwOSwtMTkzLjAwNikiPgogICAgICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0zMTcuNzI4LDE1MzAuMDVMMzE3LjcyOCwxNjQ5LjU1TDQzNy4yMjMsMTUzMC4wNUw0MzcuMjIzLDE0MTAuNTZMMzE3LjcyOCwxNTMwLjA1WiIgc3R5bGU9ImZpbGw6cmdiKDM3LDEwMSwxNDMpOyIvPgogICAgICAgICAgICAgICAgPC9nPgogICAgICAgICAgICA8L2c+CiAgICAgICAgPC9nPgogICAgPC9nPgo8L3N2Zz4K';
    $logo = get_option('munisys_header_logo', $logo_base64);
    $c_txt = get_option('munisys_header_contact_text', 'Contact');
    $c_lnk = get_option('munisys_header_contact_link', '/contact');

    $menus = [
        'srv' => ['label' => 'Services', 'txt' => get_option('munisys_menu_srv_text', 'Services'), 'lnk' => get_option('munisys_menu_srv_link', '/services')],
        'ref' => ['label' => 'Customer references', 'txt' => get_option('munisys_menu_ref_text', 'Customer references'), 'lnk' => get_option('munisys_menu_ref_link', '/customer-references')],
        'part' => ['label' => 'Technology partners', 'txt' => get_option('munisys_menu_part_text', 'Technology partners'), 'lnk' => get_option('munisys_menu_part_link', '/technology-partners')],
        'ins' => ['label' => 'Insights', 'txt' => get_option('munisys_menu_ins_text', 'Insights'), 'lnk' => get_option('munisys_menu_ins_link', '/insights')],
        'car' => ['label' => 'Careers', 'txt' => get_option('munisys_menu_car_text', 'Careers'), 'lnk' => get_option('munisys_menu_car_link', '/careers')],
        'abt' => ['label' => 'About us', 'txt' => get_option('munisys_menu_abt_text', 'About us'), 'lnk' => get_option('munisys_menu_abt_link', '/about-us')],
    ];
    ?>
    <div class="wrap">
        <h1>⚙️ Paramètres de l'En-tête (Header)</h1>
        <p>Ces paramètres contrôlent le shortcode <strong>[munisys_header]</strong>. Les couleurs utilisent la "Charte
            Graphique". Les solutions utilisent la "Page Accueil".</p>

        <form method="POST"
            style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 800px; margin-top: 20px;">
            <input type="hidden" name="submit_munisys_header" value="1">

            <h2 style="color: #1d4e79;">🖼️ Logo Principal</h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label>URL de l'image (Logo)</label></th>
                    <td><input type="text" name="munisys_header_logo" value="<?php echo esc_url($logo); ?>"
                            style="width: 100%;" /></td>
                </tr>
            </table>

            <h2 style="color: #1d4e79; margin-top: 40px;">🔘 Bouton Action (CTA)</h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label>Texte du bouton</label></th>
                    <td><input type="text" name="munisys_header_contact_text" value="<?php echo esc_attr($c_txt); ?>"
                            style="width: 100%;" /></td>
                </tr>
                <tr>
                    <th scope="row"><label>Lien du bouton</label></th>
                    <td><input type="text" name="munisys_header_contact_link" value="<?php echo esc_url($c_lnk); ?>"
                            style="width: 100%;" /></td>
                </tr>
            </table>

            <h2 style="color: #1d4e79; margin-top: 40px;">🔗 Liens du Menu de Navigation</h2>
            <p><em>Note: Le lien "Solutions" est un Mega Menu généré automatiquement à partir de la Page Accueil.</em></p>
            <table class="form-table">
                <?php foreach ($menus as $key => $m): ?>
                    <tr>
                        <th scope="row"><label><?php echo esc_html($m['label']); ?></label></th>
                        <td>
                            <input type="text" name="munisys_menu_<?php echo $key; ?>_text"
                                value="<?php echo esc_attr($m['txt']); ?>" placeholder="Texte affiché"
                                style="width: 45%; margin-right: 5%;" />
                            <input type="text" name="munisys_menu_<?php echo $key; ?>_link"
                                value="<?php echo esc_url($m['lnk']); ?>" placeholder="Lien de la page" style="width: 45%;" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <p class="submit" style="margin-top: 30px;">
                <input type="submit" class="button button-primary button-hero"
                    value="Enregistrer les paramètres de l'En-tête">
            </p>
        </form>
    </div>
    <?php
}

// ==========================================
// AJAX CONTACT FORM HANDLER
// ==========================================
add_action('wp_ajax_munisys_send_contact', 'munisys_handle_contact_submission');
add_action('wp_ajax_nopriv_munisys_send_contact', 'munisys_handle_contact_submission');

function munisys_handle_contact_submission()
{
    check_ajax_referer('munisys_contact_nonce', 'security');

    // reCAPTCHA verification
    $secret_key = get_option('munisys_recaptcha_secret_key', '');
    if (!empty($secret_key) && !empty($_POST['recaptcha_token'])) {
        $verify_response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
            'body' => array(
                'secret' => $secret_key,
                'response' => sanitize_text_field($_POST['recaptcha_token'])
            )
        ));

        $response_body = wp_remote_retrieve_body($verify_response);
        $result = json_decode($response_body);

        if (!$result || !$result->success || $result->score < 0.5) {
            wp_send_json_error(array('message' => 'Erreur reCAPTCHA. Êtes-vous un robot ?'));
            exit;
        }
    }

    $fname = isset($_POST['fname']) ? sanitize_text_field($_POST['fname']) : '';
    $lname = isset($_POST['lname']) ? sanitize_text_field($_POST['lname']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

    if (empty($fname) || empty($email) || empty($message)) {
        wp_send_json_error(array('message' => 'Veuillez remplir les champs obligatoires.'));
        exit;
    }

    $to = get_option('munisys_contact_email', get_option('admin_email'));
    $subject = 'Nouveau message de contact: ' . $fname . ' ' . $lname;

    $headers = array('Content-Type: text/html; charset=UTF-8');
    $headers[] = 'Reply-To: ' . $fname . ' <' . $email . '>';

    $mail_body = "<h2>Nouveau message depuis le site Munisys</h2>";
    $mail_body .= "<p><strong>Prénom:</strong> {$fname}</p>";
    $mail_body .= "<p><strong>Nom:</strong> {$lname}</p>";
    $mail_body .= "<p><strong>Email:</strong> {$email}</p>";
    $mail_body .= "<p><strong>Téléphone:</strong> {$phone}</p>";
    $mail_body .= "<p><strong>Message:</strong><br/>" . nl2br($message) . "</p>";

    $sent = wp_mail($to, $subject, $mail_body, $headers);

    if ($sent) {
        wp_send_json_success(array('message' => 'Votre message a été envoyé avec succès !'));
    } else {
        wp_send_json_error(array('message' => 'Erreur lors de l\'envoi de l\'e-mail. Veuillez vérifier la configuration de votre serveur.'));
    }
    exit;
}

// ==========================================
// CUSTOM POST TYPES REGISTRATION (Insights & Careers)
// ==========================================
add_action('init', 'munisys_register_custom_post_types');
function munisys_register_custom_post_types()
{
    // Insights (Articles)
    $insight_labels = array(
        'name' => 'Insights',
        'singular_name' => 'Insight',
        'menu_name' => 'Insights',
        'name_admin_bar' => 'Insight',
        'add_new' => 'Ajouter un Insight',
        'add_new_item' => 'Ajouter un nouvel Insight',
        'new_item' => 'Nouvel Insight',
        'edit_item' => 'Modifier l\'Insight',
        'view_item' => 'Voir l\'Insight',
        'all_items' => 'Tous les Insights',
        'search_items' => 'Rechercher des Insights',
        'not_found' => 'Aucun insight trouvé.',
    );

    $insight_args = array(
        'labels' => $insight_labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'insight'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_icon' => 'dashicons-lightbulb',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    );
    register_post_type('munisys_insight', $insight_args);

    // Careers (Offres d'emploi)
    $career_labels = array(
        'name' => 'Offres d\'emploi',
        'singular_name' => 'Offre d\'emploi',
        'menu_name' => 'Offres d\'emploi',
        'name_admin_bar' => 'Offre d\'emploi',
        'add_new' => 'Ajouter une offre',
        'add_new_item' => 'Ajouter une nouvelle offre',
        'new_item' => 'Nouvelle offre',
        'edit_item' => 'Modifier l\'offre',
        'view_item' => 'Voir l\'offre',
        'all_items' => 'Toutes les offres',
        'search_items' => 'Rechercher des offres',
        'not_found' => 'Aucune offre trouvée.',
    );

    $career_args = array(
        'labels' => $career_labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'career'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_icon' => 'dashicons-businessman',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest' => true,
    );
    register_post_type('munisys_career', $career_args);
}

// ==========================================
// SCROLL REVEAL ANIMATIONS (GLOBAL)
// ==========================================
add_action('wp_footer', 'munisys_global_scroll_reveal_assets');
function munisys_global_scroll_reveal_assets()
{
    ?>
    <style>
        .m-reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1) !important;
            will-change: opacity, transform;
        }

        .m-reveal-left {
            opacity: 0;
            transform: translateX(-40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1) !important;
            will-change: opacity, transform;
        }

        .m-reveal-visible {
            opacity: 1 !important;
            transform: translate(0, 0) !important;
        }

        .m-delay-1 {
            transition-delay: 0.1s !important;
        }

        .m-delay-2 {
            transition-delay: 0.2s !important;
        }

        .m-delay-3 {
            transition-delay: 0.3s !important;
        }

        .m-delay-4 {
            transition-delay: 0.4s !important;
        }

        .m-delay-5 {
            transition-delay: 0.5s !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observerOptions = { root: null, rootMargin: '0px', threshold: 0.1 };
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('m-reveal-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.m-reveal, .m-reveal-left').forEach(el => observer.observe(el));
        });
    </script>
    <?php
}

// ==========================================
// PDF IMPORTER — AJAX handler
// ==========================================
add_action('wp_ajax_munisys_parse_pdf', 'munisys_parse_pdf_ajax');
function munisys_parse_pdf_ajax()
{
    check_ajax_referer('munisys_pdf_nonce', 'nonce');
    if (!current_user_can('edit_pages'))
        wp_send_json_error('Non autorisé');

    $pdf_url = isset($_POST['pdf_url']) ? esc_url_raw($_POST['pdf_url']) : '';
    if (!$pdf_url)
        wp_send_json_error('Aucune URL de PDF fournie.');

    // Resolve PDF URL to filesystem path — handle localhost with port (Local by Flywheel)
    $upload_dir = wp_upload_dir();
    $base_url = $upload_dir['baseurl'];
    $base_dir = $upload_dir['basedir'];
    $pdf_path = str_replace($base_url, $base_dir, $pdf_url);

    if (!is_file($pdf_path)) {
        // Strip scheme+host+port and map to ABSPATH (works for localhost:PORT)
        $parsed = parse_url($pdf_url);
        $url_path = isset($parsed['path']) ? ltrim($parsed['path'], '/') : '';
        $pdf_path = rtrim(ABSPATH, '/') . '/' . $url_path;
    }

    if (!is_file($pdf_path)) {
        // Last resort: try home_url mapping
        $pdf_path = ABSPATH . ltrim(str_replace(home_url('/'), '', $pdf_url), '/');
    }

    if (!is_file($pdf_path)) {
        wp_send_json_error('Fichier PDF introuvable sur le serveur : ' . esc_html($pdf_path));
    }

    // --- Attempt text extraction ---
    $raw_text = '';
    $pdf_title = '';      // Title from PDF metadata
    $is_img_based = false;

    // 1) Try to get real document title from PDF metadata via pdfinfo
    $info_output = (string) @shell_exec('pdfinfo ' . escapeshellarg($pdf_path) . ' 2>/dev/null');
    if ($info_output) {
        if (preg_match('/^Title:\s*(.+)$/m', $info_output, $m)) {
            $candidate = trim($m[1]);
            // Only use if it doesn't look like tool metadata
            $noise_patterns = [
                'pillow',
                'pdf driver',
                'reportlab',
                'fpdf',
                'wkhtmltopdf',
                'libreoffice',
                'microsoft',
                'adobe',
                'ghostscript',
                'itext'
            ];
            $is_noise = false;
            foreach ($noise_patterns as $np) {
                if (stripos($candidate, $np) !== false) {
                    $is_noise = true;
                    break;
                }
            }
            if (!$is_noise && strlen($candidate) > 2) {
                $pdf_title = $candidate;
            }
        }
    }

    // 2) Extract text with pdftotext
    $raw_text = (string) @shell_exec('pdftotext ' . escapeshellarg($pdf_path) . ' - 2>/dev/null');

    // 3) Clean metadata noise and detect image-based PDFs
    $raw_text = munisys_pdf_clean_noise($raw_text);
    $text_cleaned = preg_replace('/\s+/', ' ', trim($raw_text));
    $word_count = str_word_count($text_cleaned);

    if ($word_count <= 8) {
        $is_img_based = true;
        $raw_text = '';
    }

    // 4) Fallback: basic PHP extractor for simple PDFs
    if (empty(trim($raw_text))) {
        $raw_text = (string) munisys_pdf_basic_extract($pdf_path);
        $raw_text = munisys_pdf_clean_noise($raw_text);
        $text2 = preg_replace('/\s+/', ' ', trim($raw_text));
        if (str_word_count($text2) <= 8) {
            $raw_text = '';
        }
    }

    // 5) If truly image-based with no text: return title + editable template
    if (empty(trim($raw_text))) {
        $title_guess = $pdf_title ?: pathinfo($pdf_path, PATHINFO_FILENAME);
        $title_guess = ucwords(str_replace(['-', '_', '.'], ' ', $title_guess));
        $template_content = munisys_pdf_content_template($title_guess);
        wp_send_json_success([
            'title' => $title_guess,
            'content' => $template_content,
            'warning' => true,
            'note' => '⚠️ Ce PDF est basé sur des images — le texte ne peut pas être extrait automatiquement. Le titre a été estimé depuis le nom du fichier et un modèle de contenu a été pré-rempli. Remplacez les parties en [CROCHETS] avec votre vrai contenu.',
        ]);
        return;
    }

    // --- Parse structure ---
    $result = munisys_pdf_parse_structure($raw_text);

    // Safety: if title is still empty or noisy after parsing, use filename
    $title_from_file = ucwords(str_replace(['-', '_', '.'], ' ', pathinfo($pdf_path, PATHINFO_FILENAME)));
    if ($pdf_title && (!$result['title'] || strlen(trim($result['title'])) < 3)) {
        $result['title'] = $pdf_title;
    }
    if (!$result['title'] || strlen(trim(munisys_pdf_clean_noise($result['title']))) < 3) {
        $result['title'] = $title_from_file;
        $result['warning'] = true;
        $result['note'] = '⚠️ Le titre n\'a pas pu être détecté automatiquement dans le PDF. Un titre a été suggéré depuis le nom du fichier. Modifiez-le si nécessaire.';
    }

    // Safety: if content is empty even after parsing, inject the template
    if (empty(trim(strip_tags($result['content'] ?? '')))) {
        $title_guess = $result['title'] ?: ucwords(str_replace(['-', '_', '.'], ' ', pathinfo($pdf_path, PATHINFO_FILENAME)));
        $result['content'] = munisys_pdf_content_template($title_guess);
        $result['warning'] = true;
        $result['note'] = '⚠️ Le contenu du PDF n’a pas pu être lu (PDF image ou format non supporté). Un modèle a été pré-rempli. Remplacez les parties en [CROCHETS] par votre vrai contenu.';
    }

    wp_send_json_success($result);

}

/**
 * Generate a ready-to-edit HTML content template for image-based PDFs.
 * The user replaces [CROCHETS] with their actual content.
 */
function munisys_pdf_content_template($doc_title = '')
{
    $title_hint = $doc_title ? esc_html($doc_title) : 'votre document';
    return <<<HTML
<h3>Introduction</h3>
<p>[Rédigez ici le paragraphe d'introduction de {$title_hint}. Présentez le sujet principal et le contexte.]</p>

<h3>Contexte et enjeux</h3>
<p>[Décrivez ici le contexte, les problématiques identifiées et les enjeux liés au sujet.]</p>

<h3>Analyse et résultats</h3>
<p>[Présentez ici les principaux résultats, chiffres clés, ou analyses de votre document.]</p>

<h3>Recommandations</h3>
<p>[Listez ici les recommandations, conclusions ou actions proposées.]</p>

<h3>Conclusion</h3>
<p>[Rédigez ici la conclusion de {$title_hint}. Résumez les points essentiels.]</p>
HTML;
}

/**
 * Fallback: extract readable text from raw PDF bytes.
 * Only uses the BT...ET operator blocks (actual text rendering commands).
 * Does NOT scan raw UTF-8 runs — that picks up metadata noise.
 */
function munisys_pdf_basic_extract($path)
{

    $data = @file_get_contents($path);
    if (!$data)
        return '';

    $text = '';
    if (preg_match_all('/BT[\s\S]+?ET/', $data, $bt_blocks)) {
        foreach ($bt_blocks[0] as $block) {
            if (preg_match_all('/\(([^)\\\\]*(?:\\\\.[^)\\\\]*)*)\)\s*(?:Tj|TJ)/', $block, $m)) {
                foreach ($m[1] as $t) {
                    $decoded = $t ? stripslashes($t) : '';
                    if ($decoded && strlen(trim($decoded)) > 1)
                        $text .= $decoded . ' ';
                }
            }
        }
    }
    return $text;
}

/**
 * Remove PDF metadata/tool noise from extracted text lines.
 * Returns cleaned text, or empty string if nothing meaningful remains.
 */
function munisys_pdf_clean_noise($text)
{
    $noise_phrases = [
        'created by',
        'pdf driver',
        'pillow',
        'reportlab',
        'fpdf',
        'wkhtmltopdf',
        'libreoffice',
        'pdfcreator',
        'ghostscript',
        'itext',
        'adobe acrobat',
        'microsoft word',
        'microsoft print',
        'cairo',
        'version ',
        'producer:',
        'creator:',
        'author:',
        'subject:',
    ];

    $lines = preg_split('/\r?\n/', $text);
    $cleaned = [];
    foreach ($lines as $line) {
        $lower = strtolower(trim($line));
        if (!$lower)
            continue;
        $is_noise = false;
        foreach ($noise_phrases as $np) {
            if (strpos($lower, $np) !== false) {
                $is_noise = true;
                break;
            }
        }
        if (!$is_noise)
            $cleaned[] = trim($line);
    }
    return implode("\n", $cleaned);
}

/**
 * Parse raw text into a structured array: title, main_image_hint, content (HTML with sections).
 */
function munisys_pdf_parse_structure($raw)
{
    // Normalize: remove form-feeds, standardize line endings
    $raw = str_replace(["\r\n", "\r", "\f"], ["\n", "\n", "\n\n"], $raw);

    // Split into logical "blocks" separated by blank lines
    $blocks = preg_split('/\n{2,}/', trim($raw));
    $blocks = array_values(array_filter(array_map('trim', $blocks)));

    $title = '';
    $sections = []; // [ ['heading' => string, 'paras' => [string]] ]
    $current_sec = ['heading' => '', 'paras' => []];

    foreach ($blocks as $i => $block) {
        $lines = array_values(array_filter(array_map('trim', explode("\n", $block))));
        $first_line = $lines[0] ?? '';
        $word_count = str_word_count($first_line);
        $char_count = mb_strlen($first_line);

        // Is this block a heading?
        // Heuristics: ≤ 12 words, ends without period, is the only line OR
        // followed by more content, and it doesn't look like a sentence.
        $looks_like_heading = (
            $word_count >= 1 &&
            $word_count <= 12 &&
            $char_count <= 120 &&
            !preg_match('/\.$/', $first_line) &&           // no trailing period
            !preg_match('/^[\-\*\•]/', $first_line) &&     // not a bullet
            (count($lines) === 1 || (strtoupper($first_line) === $first_line && $word_count <= 8))
        );

        // First non-empty block → title of the whole article
        if ($i === 0) {
            $title = $first_line;
            // If the block has more lines after the heading-like first line, treat rest as intro paragraph
            $rest = implode(' ', array_slice($lines, 1));
            if ($rest) {
                $current_sec['paras'][] = $rest;
            }
            continue;
        }

        if ($looks_like_heading) {
            // Save previous section
            if (!empty($current_sec['paras']) || $current_sec['heading']) {
                $sections[] = $current_sec;
            }
            // Start new section
            $current_sec = ['heading' => $first_line, 'paras' => []];
            // Lines after the heading in same block = first paragraph of this section
            $rest = implode(' ', array_slice($lines, 1));
            if ($rest)
                $current_sec['paras'][] = $rest;
        } else {
            // Regular paragraph — concatenate all lines into one
            $current_sec['paras'][] = implode(' ', $lines);
        }
    }

    // Flush last section
    if (!empty($current_sec['paras']) || $current_sec['heading']) {
        $sections[] = $current_sec;
    }

    // --- Build HTML ---
    $html = '';
    foreach ($sections as $sec) {
        if ($sec['heading']) {
            $html .= '<h3>' . esc_html($sec['heading']) . '</h3>' . "\n";
        }
        foreach ($sec['paras'] as $p) {
            if (trim($p)) {
                $html .= '<p>' . esc_html(trim($p)) . '</p>' . "\n";
            }
        }
    }

    return [
        'title' => esc_html($title),
        'content' => $html,
        'note' => 'Images du PDF : les images intégrées dans un PDF ne peuvent pas être extraites automatiquement. Veuillez les ajouter manuellement via la Médiathèque.',
    ];
}

// --- Design par défaut pour la page Single Insight ---
function munisys_insight_single_design($content)
{
    if (!is_singular('munisys_insight') || !in_the_loop() || !is_main_query()) {
        return $content;
    }

    $title = get_the_title();
    $date = get_the_date('l d F Y'); // ex: Monday 08 December 2025
    $image = get_post_meta(get_the_ID(), '_munisys_insight_img_url', true) ?: get_the_post_thumbnail_url();

    // L'image à la une avec une classe spéciale pour le design premium flottant
    $featured_img_html = '';
    if ($image) {
        $featured_img_html = '<img class="image_view_insigte" src="' . esc_url($image) . '" alt="' . esc_attr($title) . '">';
    }

    // Requête pour les "Articles Similaires" (3 derniers Insights)
    $related_query = new WP_Query([
        'post_type' => 'munisys_insight',
        'posts_per_page' => 3,
        'post__not_in' => [get_the_ID()],
        'orderby' => 'date',
        'order' => 'DESC'
    ]);

    $related_html = '';
    if ($related_query->have_posts()) {
        $related_html .= '<div class="ins-related-section">';
        $related_html .= '<h3 class="ins-related-title">À lire aussi</h3>';
        $related_html .= '<div class="ins-related-grid">';
        while ($related_query->have_posts()) {
            $related_query->the_post();
            $rel_title = get_the_title();
            $rel_link = get_permalink();
            $rel_date = get_the_date('M Y');
            $rel_img = get_post_meta(get_the_ID(), '_munisys_insight_img_url', true) ?: get_the_post_thumbnail_url();
            if (!$rel_img) {
                $rel_img = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="250" style="background:%23edf2f7"></svg>';
            }

            $related_html .= '
            <a href="' . esc_url($rel_link) . '" class="ins-related-card">
                <div class="ins-rel-img" style="background-image: url(' . esc_url($rel_img) . ');"></div>
                <div class="ins-rel-content">
                    <span class="ins-rel-date">' . esc_html($rel_date) . '</span>
                    <h4>' . esc_html(wp_trim_words($rel_title, 8, '...')) . '</h4>
                    <span class="ins-rel-read">Lire l\'article &rarr;</span>
                </div>
            </a>';
        }
        wp_reset_postdata();
        $related_html .= '</div></div>';
    }

    // On supprime le fond bleu basique pour un design clair et premium type "Tech Partners"
    $html = '
    <style>
        /* Bannière Hero Ultra Premium (Inspiré de Technology Partners) */
        .munisys-switcher-page {
            background-color: #fcfcfd;
            padding: 100px 20px 60px;
            position: relative;
            margin-left: calc(-50vw + 50%);
            margin-right: calc(-50vw + 50%);
            width: 100vw;
            margin-top: -30px; 
            text-align: center;
            border-bottom: 1px solid #edf2f7;
        }
        .munisys-switcher-page h1 {
            color: #3b5a7a; /* Bleu plus doux, fin */
            font-size: 64px;
            font-weight: 300;
            margin: 0;
            font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            text-transform: none;
            line-height: 1.1;
            letter-spacing: -1px;
        }
        .munisys-switcher-page h1 strong {
            color: #922b55; /* Rouge Munisys en gras */
            font-weight: 800;
        }
        .munisys-switcher-page p.hero-subtitle {
            color: #a0aec0;
            font-size: 20px;
            font-weight: 400;
            max-width: 650px;
            margin: 25px auto 0;
            line-height: 1.6;
        }
        
        /* Conteneur principal Premium (Inspiré de Tech Partners) */
        .munisys-insight-view {
            padding: 40px 0 100px;
            background: #f8fafc; /* Fond très légèrement gris pour faire ressortir la carte blanche */
            font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #444444;
            text-align: left;
            margin-left: calc(-50vw + 50%);
            margin-right: calc(-50vw + 50%);
            width: 100vw;
        }
        
        /* Carte de l\'article type Blog Premium */
        .munisys-insight-view .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 60px 70px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.04);
        }
        
        /* Date et Titre ultra clean */
        .munisys-insight-view label {
            color: #a0aec0;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 15px;
            display: block;
            border-bottom: 2px solid #edf2f7;
            padding-bottom: 15px;
        }
        .munisys-insight-view h5 {
            color: #1a4064;
            font-size: 38px;
            font-weight: 800;
            margin-bottom: 40px;
            margin-top: 20px;
            line-height: 1.3;
            letter-spacing: -0.5px;
        }
        
        /* Contenu du PDF */
        .munisys-insight-content {
            font-size: 17px;
            line-height: 1.85;
            color: #4a5568;
        }
        
        /* Clearfix pour contenir l\'image flottante */
        .munisys-insight-content::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .munisys-insight-content p {
            margin-bottom: 25px;
        }
        
        /* Sous-titres élégants en bordeaux Munisys */
        .munisys-insight-content h3 {
            color: #b01b54; 
            font-size: 24px;
            font-weight: 700;
            margin-top: 45px;
            margin-bottom: 20px;
        }
        
        /* IMAGE À LA UNE FLOTTANTE À DROITE (Prend moins de 50%) */
        .munisys-insight-content img.image_view_insigte {
            float: right;
            max-width: 45%; 
            height: auto;
            margin: 0 0 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            object-fit: contain;
            background: #f8fafc;
        }
        
        /* Images standards dans le texte */
        .munisys-insight-content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 30px auto;
            border-radius: 8px;
        }
        
        /* Breadcrumbs */
        .ins-breadcrumbs {
            font-size: 13px;
            font-weight: 500;
            color: #718096;
            margin-bottom: 25px;
            font-family: "Segoe UI", Roboto, sans-serif;
        }
        .ins-breadcrumbs a {
            color: #3182ce;
            text-decoration: none;
            transition: color 0.2s;
        }
        .ins-breadcrumbs a:hover {
            color: #2b6cb0;
            text-decoration: underline;
        }
        
        /* Related Insights Section */
        .ins-related-section {
            margin-top: 70px;
            padding-top: 50px;
            border-top: 1px solid #edf2f7;
            clear: both;
        }
        .ins-related-title {
            font-size: 26px !important;
            font-weight: 800 !important;
            color: #1a4064 !important;
            margin-top: 0 !important;
            margin-bottom: 35px !important;
            letter-spacing: -0.5px;
        }
        .ins-related-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }
        .ins-related-card {
            display: flex;
            flex-direction: column;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .ins-related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
            border-color: #cbd5e0;
        }
        .ins-rel-img {
            width: 100%;
            height: 150px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #f7fafc;
            border-bottom: 1px solid #edf2f7;
        }
        .ins-rel-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .ins-rel-date {
            font-size: 11px;
            color: #a0aec0;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .ins-rel-content h4 {
            font-size: 16px !important;
            color: #2d3748 !important;
            font-weight: 700 !important;
            line-height: 1.4 !important;
            margin: 0 0 20px 0 !important;
            flex-grow: 1;
        }
        .ins-rel-read {
            font-size: 13px;
            color: #922b55;
            font-weight: 700;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .munisys-insight-view .container {
                padding: 30px 20px;
                border-radius: 0;
            }
            .munisys-insight-view h5 {
                font-size: 28px;
            }
            .munisys-insight-content img.image_view_insigte {
                float: none;
                max-width: 100%;
                margin: 0 0 30px 0;
            }
            .ins-related-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <div class="munisys-switcher-page">
        <div class="container">
            <h1><strong>Insights</strong></h1>
        </div>
    </div>
    
    <div class="munisys-insight-view">
        <div class="container"> 
            <div class="ins-breadcrumbs">
                <a href="/">Accueil</a> &nbsp;&rsaquo;&nbsp; <a href="/insights/">Insights</a> &nbsp;&rsaquo;&nbsp; <span>' . esc_html(wp_trim_words($title, 5, '...')) . '</span>
            </div>
            <label>' . esc_html($date) . '</label>
            <h5>' . esc_html($title) . '</h5>  
            <div class="munisys-insight-content"> 
                ' . $featured_img_html . '
                ' . $content . '
            </div>
            ' . $related_html . '
        </div>
    </div>
    ';

    return $html;
}
add_filter('the_content', 'munisys_insight_single_design', 99);

// ==========================================
// 24. LANGUAGE SWITCHER SHORTCODE
// ==========================================
add_shortcode('munisys_lang_switcher', 'render_munisys_lang_switcher');
function render_munisys_lang_switcher()
{
    // Intentionally left blank to disable the old standalone shortcode
    // which was causing the language switcher to teleport and leave empty gaps.
    return '';
}

// ==========================================
// 25. FIX GLOBAL SEARCH (Include Custom Post Types)
// ==========================================
add_action('pre_get_posts', 'munisys_include_cpt_in_search');
function munisys_include_cpt_in_search($query)
{
    if ($query->is_search && !is_admin() && $query->is_main_query()) {
        $query->set('post_type', array('post', 'page', 'munisys_solution', 'munisys_service', 'munisys_insight', 'munisys_reference', 'munisys_partner'));
    }
}

// ==========================================
// 26. ULTRA-PREMIUM FULLSCREEN SEARCH MODAL
// ==========================================
add_action('wp_footer', 'munisys_fullscreen_search_modal');
function munisys_fullscreen_search_modal()
{
    ?>
    <style>
        #munisys-search-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.6);
            /* Dark translucent background */
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            z-index: 999999;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 15vh;
            /* Drop down a bit from top */
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        #munisys-search-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .munisys-search-form {
            width: 90%;
            max-width: 650px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            position: relative;
            transform: scale(0.95) translateY(-20px);
            transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
            display: flex;
            align-items: center;
            padding: 5px 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        #munisys-search-overlay.active .munisys-search-form {
            transform: scale(1) translateY(0);
        }

        .munisys-search-icon-svg {
            color: #922b55;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .munisys-search-input {
            flex-grow: 1;
            border: none;
            background: transparent;
            font-size: 22px;
            font-weight: 500;
            color: #1a4064;
            padding: 20px 0;
            outline: none;
            font-family: 'Segoe UI', Roboto, sans-serif;
            box-shadow: none !important;
        }

        .munisys-search-input::placeholder {
            color: #cbd5e0;
            font-weight: 400;
        }

        .munisys-search-close-btn {
            background: #f1f5f9;
            border: none;
            border-radius: 8px;
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 10px;
            cursor: pointer;
            transition: background 0.2s;
            margin-left: 15px;
        }

        .munisys-search-close-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
    </style>

    <div id="munisys-search-overlay">
        <form role="search" method="get" class="munisys-search-form" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="munisys-search-icon-svg">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <input type="search" class="munisys-search-input" placeholder="Rechercher..."
                value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" autofocus />
            <button type="button" id="munisys-search-close-btn" class="munisys-search-close-btn">ESC</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const overlay = document.getElementById('munisys-search-overlay');
            const closeBtn = document.getElementById('munisys-search-close-btn');
            const searchInput = overlay ? overlay.querySelector('.munisys-search-input') : null;
            const headerSearchBtn = document.getElementById('munisys-header-search-btn');

            function openSearch() {
                if (!overlay) return;
                overlay.classList.add('active');
                setTimeout(() => { if (searchInput) searchInput.focus(); }, 100);
            }

            function closeSearch() {
                if (!overlay) return;
                overlay.classList.remove('active');
                if (searchInput) searchInput.value = '';
            }

            // Open on header search button click
            if (headerSearchBtn) {
                headerSearchBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openSearch();
                });
            }


            // Also support old nav-search class and href=#search links
            document.body.addEventListener('click', function (e) {
                let target = e.target.closest('.nav-search, a[href="#search"]');
                if (target) {
                    e.preventDefault();
                    openSearch();
                }
            });

            // Close on ESC button click
            if (closeBtn) {
                closeBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    closeSearch();
                });
            }

            // Close on ESC key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && overlay && overlay.classList.contains('active')) {
                    closeSearch();
                }
            });

            // Close when clicking OUTSIDE the search form (on the backdrop)
            if (overlay) {
                overlay.addEventListener('click', function (e) {
                    if (!e.target.closest('.munisys-search-form')) {
                        closeSearch();
                    }
                });
            }

            // ======= LIVE SEARCH SUGGESTIONS (AJAX) =======
            if (searchInput) {
                let suggestBox = document.createElement('div');
                suggestBox.id = 'munisys-suggest-box';
                suggestBox.style.cssText = [
                    'position:absolute',
                    'top:calc(100% + 10px)',
                    'left:0',
                    'right:0',
                    'background:#ffffff',
                    'border-radius:14px',
                    'box-shadow:0 20px 60px rgba(0,0,0,0.18)',
                    'overflow:hidden',
                    'z-index:99999',
                    'display:none',
                    'max-height:380px',
                    'overflow-y:auto'
                ].join(';');

                // Wrap form in relative container
                const form = overlay.querySelector('.munisys-search-form');
                if (form) {
                    form.style.position = 'relative';
                    form.appendChild(suggestBox);
                }

                let debounceTimer;
                searchInput.addEventListener('input', function () {
                    clearTimeout(debounceTimer);
                    const q = this.value.trim();
                    if (q.length < 2) { suggestBox.style.display = 'none'; return; }
                    debounceTimer = setTimeout(() => {
                        const homeUrl = '<?php echo esc_url(home_url('/')); ?>';
                        fetch(homeUrl + '?s=' + encodeURIComponent(q) + '&munisys_ajax_suggest=1')
                            .then(r => r.json())
                            .then(data => {
                                if (!data || !data.length) { suggestBox.style.display = 'none'; return; }
                                suggestBox.innerHTML = data.map(item => {
                                    const labelColors = {
                                        'Solution': '#1d4e79', 'Service': '#7c3aed', 'Insight': '#dc2626',
                                        'Référence': '#16a34a', 'Partenaire': '#d97706', 'Actualité': '#0891b2', 'Page': '#922b55'
                                    };
                                    const c = labelColors[item.type] || '#922b55';
                                    return `<a href="${item.url}" style="display:flex;align-items:center;gap:14px;padding:14px 20px;text-decoration:none;border-bottom:1px solid #f1f5f9;transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                                        <div style="width:36px;height:36px;border-radius:8px;background:${c}15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="${c}" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                                        </div>
                                        <div style="flex:1;min-width:0;">
                                            <div style="font-weight:700;font-size:14px;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${item.title}</div>
                                            <div style="font-size:11px;font-weight:700;color:${c};text-transform:uppercase;letter-spacing:1px;margin-top:2px;">${item.type}</div>
                                        </div>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                    </a>`;
                                }).join('');
                                suggestBox.innerHTML += `<div style="padding:10px 20px;font-size:12px;color:#94a3b8;text-align:center;">Appuyez sur Entrée pour voir tous les résultats</div>`;
                                suggestBox.style.display = 'block';
                            })
                            .catch(() => { suggestBox.style.display = 'none'; });
                    }, 250);
                });

                // Hide suggestions on blur (with small delay for link clicks)
                searchInput.addEventListener('blur', () => setTimeout(() => { suggestBox.style.display = 'none'; }, 200));
                searchInput.addEventListener('focus', () => { if (searchInput.value.length >= 2) suggestBox.style.display = 'block'; });
            }
        });
    </script>
    <?php
}

// ==========================================
// 27. PREMIUM CUSTOM SEARCH RESULTS PAGE
// ==========================================
add_action('wp_footer', 'munisys_render_custom_search_results', 1);
function munisys_render_custom_search_results()
{
    if (!is_search())
        return;

    global $wp_query;
    $s = get_search_query();

    $html = '<div class="munisys-premium-search-results">';

    // === HERO SECTION (editorial style) ===
    $html .= '<div class="sr-hero">';
    $html .= '<div class="sr-hero-inner">';
    $html .= '<div class="sr-hero-tag">R&Eacute;SULTATS DE RECHERCHE</div>';
    $html .= '<h1><span class="sr-h1-thin">R&eacute;sultats pour&nbsp;</span><span class="sr-h1-bold">&laquo;&nbsp;' . esc_html($s) . '&nbsp;&raquo;.</span></h1>';
    $html .= '<p>' . $wp_query->found_posts . ' r&eacute;sultat' . ($wp_query->found_posts > 1 ? 's' : '') . ' trouv&eacute;' . ($wp_query->found_posts > 1 ? 's' : '') . ' pour votre recherche.</p>';
    // Inline search bar
    $html .= '<form role="search" method="get" class="sr-search-form" action="' . esc_url(home_url('/')) . '">';
    $html .= '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>';
    $html .= '<input type="search" name="s" value="' . esc_attr($s) . '" placeholder="Affiner votre recherche..." class="sr-search-input" autocomplete="off" />';
    $html .= '<button type="submit" class="sr-search-submit">Rechercher</button>';
    $html .= '</form>';
    $html .= '</div></div>';

    $html .= '<div class="sr-container">';
    if (have_posts()) {
        $html .= '<div class="sr-grid">';
        while (have_posts()) {
            the_post();
            $type = get_post_type();
            $title = get_the_title();
            $link = get_permalink();
            $excerpt = wp_trim_words(get_the_excerpt() ?: wp_strip_all_tags(get_the_content()), 22, '...');
            $thumb = get_the_post_thumbnail_url(null, 'medium');

            // Format labels and colors
            $label = 'Page';
            $colorAccent = '#922b55';
            $colorBg = 'rgba(146,43,85,0.08)';
            if ($type == 'munisys_solution') { $label = 'Solution'; $colorAccent = '#1d4e79'; $colorBg = 'rgba(29,78,121,0.08)'; }
            if ($type == 'munisys_service')  { $label = 'Service';  $colorAccent = '#7c3aed'; $colorBg = 'rgba(124,58,237,0.08)'; }
            if ($type == 'munisys_insight')  { $label = 'Insight';  $colorAccent = '#dc2626'; $colorBg = 'rgba(220,38,38,0.08)'; }
            if ($type == 'munisys_reference'){ $label = 'Référence'; $colorAccent = '#16a34a'; $colorBg = 'rgba(22,163,74,0.08)'; }
            if ($type == 'munisys_partner')  { $label = 'Partenaire'; $colorAccent = '#d97706'; $colorBg = 'rgba(217,119,6,0.08)'; }
            if ($type == 'post')             { $label = 'Actualité'; $colorAccent = '#0891b2'; $colorBg = 'rgba(8,145,178,0.08)'; }

            $html .= '<a href="' . esc_url($link) . '" class="sr-card" style="--accent:' . $colorAccent . '; --accent-bg:' . $colorBg . ';">';
            if ($thumb) $html .= '<div class="sr-card-thumb" style="background-image:url(' . esc_url($thumb) . ')"></div>';
            $html .= '<div class="sr-card-body">';
            $html .= '<span class="sr-badge">' . $label . '</span>';
            $html .= '<h3>' . esc_html($title) . '</h3>';
            $html .= '<p>' . esc_html($excerpt) . '</p>';
            $html .= '<span class="sr-readmore">Découvrir <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>';
            $html .= '</div></a>';
        }
        $html .= '</div>';
    } else {
        $html .= '<div class="sr-no-results">';
        $html .= '<div class="sr-no-icon"><svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg></div>';
        $html .= '<h3>Aucun résultat trouvé</h3>';
        $html .= '<p>Essayez avec d\'autres mots-clés ou naviguez via notre menu.</p>';
        $html .= '<a href="/" class="sr-home-btn">Retour à l\'accueil</a>';
        $html .= '</div>';
    }
    $html .= '</div></div>';;

    ?>
    <style>
        body.search main, body.search #main, body.search #content,
        body.search .site-main, body.search .site-content,
        body.search .breakdance, body.search .archive-container { display: none !important; }

        .munisys-premium-search-results {
            background: #f8fafc;
            min-height: 80vh;
            font-family: 'Montserrat', sans-serif;
            width: 100%;
        }

        /* === EDITORIAL HERO (light) === */
        .sr-hero {
            background: #f5f6f8;
            padding: 90px 60px 70px;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        .sr-hero-inner {
            max-width: 820px;
        }
        .sr-hero-tag {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 3px;
            color: #922b55;
            text-transform: uppercase;
            margin-bottom: 28px;
            display: block;
        }
        .sr-hero h1 {
            font-size: clamp(48px, 7vw, 88px);
            line-height: 1.0;
            margin: 0 0 28px;
            letter-spacing: -2px;
            color: #111827;
        }
        /* "Résultats pour" part — thin */
        .sr-h1-thin {
            font-weight: 300;
            display: block;
        }
        /* The search term — bold */
        .sr-h1-bold {
            font-weight: 800;
            display: block;
        }
        .sr-hero-inner > p {
            color: #6b7280;
            font-size: 17px;
            line-height: 1.65;
            max-width: 540px;
            margin: 0 0 40px;
            font-weight: 400;
        }
        /* Search bar — light style */
        .sr-search-form {
            display: flex;
            align-items: center;
            max-width: 540px;
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            padding: 6px 6px 6px 18px;
            transition: border-color 0.25s, box-shadow 0.25s;
            gap: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }
        .sr-search-form:focus-within {
            border-color: #922b55;
            box-shadow: 0 0 0 3px rgba(146,43,85,0.08);
        }
        .sr-search-form svg { color: #9ca3af; flex-shrink: 0; }
        .sr-search-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: #111827;
            font-size: 15px;
            font-family: 'Montserrat', sans-serif;
            padding: 8px 0;
        }
        .sr-search-input::placeholder { color: #9ca3af; }
        .sr-search-submit {
            background: #111827;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 11px 22px;
            font-size: 13px;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            letter-spacing: 0.3px;
            transition: background 0.25s;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .sr-search-submit:hover { background: #922b55; }

        /* === CARDS === */
        .sr-container { max-width: 1280px; margin: 0 auto; padding: 60px 20px 100px; }
        .sr-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 28px;
        }
        .sr-card {
            background: #fff;
            border: 1px solid rgba(226,232,240,0.8);
            border-radius: 20px;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
            transition: all 0.4s cubic-bezier(0.175,0.885,0.32,1.275);
            box-shadow: 0 8px 24px rgba(0,0,0,0.04);
        }
        .sr-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 3px;
            background: var(--accent, #922b55);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }
        .sr-card:hover { transform: translateY(-8px); box-shadow: 0 24px 48px rgba(0,0,0,0.09); border-color: transparent; }
        .sr-card:hover::before { transform: scaleX(1); }
        .sr-card-thumb {
            width: 100%; height: 180px;
            background-size: cover;
            background-position: center;
            flex-shrink: 0;
        }
        .sr-card-body { padding: 28px 32px 32px; display: flex; flex-direction: column; flex: 1; }
        .sr-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 4px 12px;
            border-radius: 6px;
            margin-bottom: 14px;
            background: var(--accent-bg, rgba(146,43,85,0.08));
            color: var(--accent, #922b55);
            align-self: flex-start;
        }
        .sr-card h3 {
            color: #0f172a;
            font-size: 20px;
            font-weight: 800;
            margin: 0 0 12px;
            line-height: 1.3;
            letter-spacing: -0.5px;
            transition: color 0.3s;
        }
        .sr-card:hover h3 { color: var(--accent, #922b55); }
        .sr-card p { color: #64748b; font-size: 14px; line-height: 1.7; flex: 1; margin: 0 0 24px; }
        .sr-readmore {
            color: var(--accent, #922b55);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: gap 0.3s;
            margin-top: auto;
        }
        .sr-card:hover .sr-readmore { gap: 14px; }

        /* === NO RESULTS === */
        .sr-no-results {
            text-align: center;
            padding: 80px 40px;
            background: #fff;
            border-radius: 20px;
            border: 1px solid rgba(226,232,240,0.6);
        }
        .sr-no-icon {
            width: 80px; height: 80px;
            background: #f8fafc;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #cbd5e1;
            margin: 0 auto 24px;
        }
        .sr-no-results h3 { color: #0f172a; font-size: 26px; font-weight: 800; margin-bottom: 12px; }
        .sr-no-results p { color: #64748b; font-size: 16px; margin-bottom: 32px; }
        .sr-home-btn {
            display: inline-block;
            background: #922b55;
            color: #fff;
            padding: 14px 36px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .sr-home-btn:hover { background: #7a2347; }

        @media (max-width: 768px) {
            .sr-grid { grid-template-columns: 1fr; }
            .sr-card-body { padding: 22px; }
            .sr-search-form { border-radius: 14px; flex-wrap: wrap; }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inject the premium UI right below the header
            let newContent = <?php echo json_encode($html); ?>;
            let header = document.getElementById('munisys-global-header');
            if (header) {
                header.insertAdjacentHTML('afterend', newContent);
            } else {
                document.body.insertAdjacentHTML('afterbegin', newContent);
            }

            // Force hide the original breakdance archive again just in case JS loaded it
            let oldMains = document.querySelectorAll('main, #main, #content, .site-main, .archive-container, section.bde-section');
            oldMains.forEach(el => {
                // Don't hide if it contains our header
                if (!el.querySelector('#munisys-global-header')) {
                    el.style.setProperty('display', 'none', 'important');
                }
            });

            // Hide the "Archives" title explicitly
            let headings = document.querySelectorAll('h1');
            headings.forEach(h => {
                if (h.innerText.includes('Archives')) h.style.display = 'none';
            });
        });
    </script>
    <?php
}

// ==========================================
// 27b. AJAX ENDPOINT FOR LIVE SEARCH SUGGESTIONS
// ==========================================
add_action('template_redirect', 'munisys_ajax_search_suggest');
function munisys_ajax_search_suggest()
{
    if (!isset($_GET['munisys_ajax_suggest']) || !isset($_GET['s']))
        return;

    $q = sanitize_text_field($_GET['s']);
    if (strlen($q) < 2) {
        wp_send_json([]);
    }

    $results = [];
    $type_labels = [
        'page'               => 'Page',
        'post'               => 'Actualité',
        'munisys_solution'   => 'Solution',
        'munisys_service'    => 'Service',
        'munisys_insight'    => 'Insight',
        'munisys_reference'  => 'Référence',
        'munisys_partner'    => 'Partenaire',
    ];

    $posts = get_posts([
        's'              => $q,
        'post_type'      => array_keys($type_labels),
        'post_status'    => 'publish',
        'posts_per_page' => 7,
    ]);

    foreach ($posts as $post) {
        $results[] = [
            'title' => get_the_title($post),
            'url'   => get_permalink($post),
            'type'  => $type_labels[$post->post_type] ?? 'Page',
        ];
    }

    wp_send_json($results);
}

// ==========================================
// 28. SCROLL REVEAL & PROGRESS BAR (PREMIUM UX)
// ==========================================
add_action('wp_footer', 'munisys_premium_scrolling_features');
function munisys_premium_scrolling_features()
{
    ?>
    <!-- Scroll Progress Bar -->
    <div id="munisys-scroll-progress"></div>

    <!-- Back to top button -->
    <button id="munisys-back-to-top" aria-label="Retour en haut">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 15l-6-6-6 6" />
        </svg>
    </button>

    <style>
        /* Progress Bar */
        #munisys-scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background: linear-gradient(90deg, #922b55, #c13a70);
            z-index: 9999999;
            transition: width 0.1s ease;
            box-shadow: 0 1px 5px rgba(146, 43, 85, 0.4);
        }

        /* Back to top */
        #munisys-back-to-top {
            position: fixed;
            bottom: -60px;
            /* Hidden by default */
            right: 30px;
            width: 50px;
            height: 50px;
            background: #ffffff;
            color: #1a4064;
            border: none;
            border-radius: 50%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 99999;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
        }

        #munisys-back-to-top.visible {
            bottom: 30px;
            opacity: 1;
            visibility: visible;
        }

        #munisys-back-to-top:hover {
            transform: translateY(-5px);
            background: #922b55;
            color: #ffffff;
            box-shadow: 0 15px 35px rgba(146, 43, 85, 0.3);
        }

        /* Scroll Reveal Global Styles */
        .munisys-reveal {
            opacity: 0;
            transform: translateY(40px) scale(0.98);
            filter: blur(8px);
            transition: all 1s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: opacity, transform, filter;
        }

        .munisys-reveal-active {
            opacity: 1;
            transform: translateY(0) scale(1);
            filter: blur(0px);
        }

        /* Smooth reset for magnetic buttons */
        .nav-cta,
        .bde-button,
        #munisys-back-to-top {
            transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1), background 0.3s, box-shadow 0.3s !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- 1. READING PROGRESS & BACK TO TOP ---
            const progressBar = document.getElementById('munisys-scroll-progress');
            const backToTop = document.getElementById('munisys-back-to-top');

            window.addEventListener('scroll', () => {
                let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                let scrolled = (height > 0) ? (winScroll / height) * 100 : 0;
                if (progressBar) progressBar.style.width = scrolled + '%';

                if (winScroll > 400) {
                    backToTop.classList.add('visible');
                } else {
                    backToTop.classList.remove('visible');
                }
            });

            if (backToTop) {
                backToTop.addEventListener('click', () => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            // --- 2. APPLE-STYLE SCROLL REVEAL (WITH STAGGER CASCADE) ---
            const elementsToAnimate = document.querySelectorAll('h1, h2, h3, .bde-image, .bde-icon, .sr-card, .ins-related-card');
            elementsToAnimate.forEach(el => {
                if (!el.closest('#munisys-global-header') && !el.closest('#munisys-search-overlay')) {
                    el.classList.add('munisys-reveal');
                }
            });

            let delayCounter = 0;
            let delayTimer = null;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Apply a staggered delay if multiple elements appear at once
                        entry.target.style.transitionDelay = `${delayCounter * 100}ms`;
                        entry.target.classList.add('munisys-reveal-active');
                        observer.unobserve(entry.target);

                        delayCounter++;
                        clearTimeout(delayTimer);
                        delayTimer = setTimeout(() => { delayCounter = 0; }, 150); // Reset after wave completes
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -30px 0px'
            });

            document.querySelectorAll('.munisys-reveal').forEach(el => {
                observer.observe(el);
            });

            // --- 3. MAGNETIC BUTTONS (PHYSICS INTERACTION) ---
            // Find main CTA buttons
            const magneticElements = document.querySelectorAll('.nav-cta, .bde-button, #munisys-back-to-top');
            magneticElements.forEach(btn => {
                btn.addEventListener('mousemove', (e) => {
                    const rect = btn.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;

                    // Move button slightly towards mouse (Magnet pull strength = 0.2)
                    btn.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
                });
                btn.addEventListener('mouseleave', () => {
                    // Snap back to original position smoothly
                    btn.style.transform = `translate(0px, 0px)`;
                });
            });
        });
    </script>
    <?php
}

// ==========================================
// 29. SMART SEARCH REDIRECT (ROUTING INTELLIGENT)
// ==========================================
add_action('init', 'munisys_smart_search_redirect_early', 1);
function munisys_smart_search_redirect_early()
{
    if (isset($_GET['s']) && !is_admin()) {
        $query = strtolower(trim(urldecode($_GET['s'])));

        // Dictionnaire des mots-clés exacts vers les pages correspondantes
        $redirects = [
            'insight' => '/insights/',
            'insights' => '/insights/',
            'solution' => '/solutions/',
            'solutions' => '/solutions/',
            'service' => '/services/',
            'services' => '/services/',
            'contact' => '/contact/',
            'partenaire' => '/technology-partners/',
            'partenaires' => '/technology-partners/',
            'partner' => '/technology-partners/',
            'partners' => '/technology-partners/',
            'reference' => '/customer-references/',
            'references' => '/customer-references/',
            'client' => '/customer-references/',
            'clients' => '/customer-references/',
            'carrieres' => '/careers/',
            'carriere' => '/careers/',
            'career' => '/careers/',
            'careers' => '/careers/',
            'about' => '/about-us/',
            'propos' => '/about-us/'
        ];

        if (array_key_exists($query, $redirects)) {
            wp_redirect(home_url($redirects[$query]));
            exit();
        }
    }
}
// ==========================================
// 30. SHORTCODE: MUNISYS SERVICE DETAIL
// ==========================================
add_shortcode('munisys_service_detail', 'render_munisys_service_detail_shortcode');
function render_munisys_service_detail_shortcode($atts)
{
    $atts = shortcode_atts(['type' => 'integration'], $atts);
    $type = strtolower($atts['type']);

    $data = [
        'integration' => [
            'title' => 'Integration Services',
            'desc' => 'At every stage in the lifecycle of your IT Infrastructures, our professional and operational services will help you make the right technology choices, design the soundest architectures and deploy them according to the rules of the art.',
            'img' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2560&auto=format&fit=crop',
            'points' => [
                ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>', 'title' => 'Architecture Design & Validation', 'desc' => 'We design robust, scalable, and secure architectures tailored to your business needs, ensuring full compliance and optimal performance.'],
                ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>', 'title' => 'Technology Deployment', 'desc' => 'Our certified experts deploy complex technologies seamlessly, minimizing disruption to your daily operations while meeting strict timelines.'],
                ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M2.13 15.57a9 9 0 1 0 3.05-10.74l4.24 4.24"/></svg>', 'title' => 'System Migration & Upgrades', 'desc' => 'Safely migrate your legacy systems to modern platforms with our proven methodologies, minimizing risks and zero-data-loss guarantee.']
            ]
        ],
        'support' => [
            'title' => 'IT Support Services',
            'desc' => 'Our reactive and proactive support services ensure maximum availability and continuity of your technological assets. We mitigate risks through strict SLAs and dedicated technical expertise.',
            'img' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=2560&auto=format&fit=crop',
            'points' => [
                ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>', 'title' => '24/7 Incident Management', 'desc' => 'Round-the-clock monitoring and rapid incident response to keep your critical systems online at all times.'],
                ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>', 'title' => 'Proactive Health Checks', 'desc' => 'Regular system audits and health checks to identify and resolve potential issues before they impact your business.'],
                ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>', 'title' => 'Hardware Replacement (RMA)', 'desc' => 'Fast and reliable hardware replacement services backed by our strong partnerships with industry-leading vendors.']
            ]
        ],
        'managed' => [
            'title' => 'Managed Services',
            'desc' => 'Offload your daily IT operations to our certified experts, allowing your internal teams to focus on strategic business initiatives. We provide 24/7 monitoring and proactive maintenance.',
            'img' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2560&auto=format&fit=crop',
            'points' => [
                ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>', 'title' => 'Infrastructure Monitoring', 'desc' => 'Continuous real-time monitoring of your entire IT infrastructure to guarantee optimal performance and uptime.'],
                ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>', 'title' => 'Security Operations Center', 'desc' => 'Advanced threat detection and response capabilities to protect your organization from sophisticated cyber attacks.'],
                ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>', 'title' => 'Cloud & Backup Management', 'desc' => 'Comprehensive management of your cloud environments and backup routines, ensuring data integrity and rapid recovery.']
            ]
        ]
    ];

    if (!isset($data[$type])) {
        $type = 'integration';
    }
    $srv = $data[$type];

    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap');

        .port-wrapper {
            font-family: 'Outfit', sans-serif !important;
            background: #0f172a !important;
            color: #fff !important;
            width: 100% !important;
            overflow: hidden !important;
        }

        .port-hero {
            position: relative !important;
            min-height: 80vh !important;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            padding: 0 8vw !important;
        }

        .port-hero-bg {
            position: absolute !important;
            top: 0 !important;
            right: 0 !important;
            width: 55% !important;
            height: 100% !important;
            background-image: url('<?php echo esc_url($srv['img']); ?>') !important;
            background-size: cover !important;
            background-position: center !important;
            z-index: 1 !important;
            border-bottom-left-radius: 150px !important;
            opacity: 0.8 !important;
            filter: grayscale(10%) contrast(1.1) !important;
            box-shadow: -20px 0 50px rgba(0, 0, 0, 0.5) !important;
        }

        .port-hero-bg::after {
            content: '' !important;
            position: absolute !important;
            inset: 0 !important;
            background: linear-gradient(to right, #0f172a 0%, rgba(15, 23, 42, 0) 100%) !important;
        }

        .port-hero-text {
            position: relative !important;
            z-index: 2 !important;
            max-width: 650px !important;
            padding-right: 50px !important;
        }

        .port-subtitle {
            font-size: 1rem !important;
            font-weight: 700 !important;
            letter-spacing: 5px !important;
            color: #922b55 !important;
            text-transform: uppercase !important;
            display: block !important;
            margin-bottom: 25px !important;
        }

        .port-title {
            font-size: 5.5rem !important;
            font-weight: 900 !important;
            line-height: 1.05 !important;
            margin-bottom: 35px !important;
            color: #ffffff !important;
            letter-spacing: -2px !important;
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
        }

        .port-desc {
            font-size: 1.3rem !important;
            font-weight: 300 !important;
            line-height: 1.8 !important;
            color: #cbd5e1 !important;
            border-left: 4px solid #922b55 !important;
            padding-left: 25px !important;
        }

        .port-capabilities {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            padding: 120px 5vw !important;
            background: #ffffff !important;
            background-image: radial-gradient(circle at 50% 50%, rgba(29, 78, 121, 0.05) 2px, transparent 2px) !important;
            background-size: 30px 30px !important;
            color: #0f172a !important;
            position: relative !important;
            border-top-right-radius: 100px !important;
            border-top-left-radius: 100px !important;
            margin-top: -50px !important;
            z-index: 10 !important;
        }

        .port-capabilities::before {
            content: '' !important;
            position: absolute !important;
            inset: 0 !important;
            background: radial-gradient(circle at center, transparent 0%, #ffffff 80%) !important;
            border-top-right-radius: 100px !important;
            border-top-left-radius: 100px !important;
            z-index: -1 !important;
            pointer-events: none !important;
        }

        .port-cap-left {
            text-align: center !important;
            margin-bottom: 60px !important;
            max-width: 800px !important;
            position: relative !important;
            top: 0 !important;
            flex: none !important;
        }

        .port-cap-left h2 {
            font-size: 4rem !important;
            font-weight: 900 !important;
            color: #1d4e79 !important;
            line-height: 1 !important;
            margin-bottom: 20px !important;
            letter-spacing: -1.5px !important;
        }

        .port-cap-left p {
            font-size: 1.3rem !important;
            color: #64748b !important;
            font-weight: 400 !important;
        }

        .port-cap-right {
            width: 100% !important;
            max-width: 1400px !important;
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 30px !important;
            flex: none !important;
        }

        .port-cap-item {
            display: flex !important;
            flex-direction: column !important;
            gap: 20px !important;
            background: linear-gradient(145deg, #ffffff, #f4f7f9) !important;
            padding: 50px 40px !important;
            border-radius: 30px !important;
            border: 1px solid rgba(29, 78, 121, 0.08) !important;
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) !important;
            align-items: flex-start !important;
            text-align: left !important;
            position: relative !important;
            overflow: hidden !important;
            z-index: 1 !important;
        }

        .port-cap-num {
            position: absolute !important;
            bottom: -30px !important;
            right: -20px !important;
            font-size: 15rem !important;
            font-weight: 900 !important;
            color: rgba(29, 78, 121, 0.03) !important;
            z-index: 0 !important;
            transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) !important;
            line-height: 0.8 !important;
            pointer-events: none !important;
        }

        .port-cap-item:hover {
            transform: translateY(-10px) !important;
            box-shadow: 0 30px 60px rgba(29, 78, 121, 0.12) !important;
            border-color: rgba(146, 43, 85, 0.2) !important;
        }

        .port-cap-item:hover .port-cap-num {
            color: rgba(146, 43, 85, 0.05) !important;
            transform: scale(1.05) translateY(-15px) !important;
        }

        .port-icon {
            flex: 0 0 60px !important;
            height: 60px !important;
            background: rgba(146, 43, 85, 0.08) !important;
            color: #922b55 !important;
            border-radius: 20px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.4s ease !important;
            position: relative !important;
            z-index: 2 !important;
        }

        .port-cap-item:hover .port-icon {
            background: #922b55 !important;
            color: #ffffff !important;
            transform: rotate(-10deg) scale(1.1) !important;
        }

        .port-icon svg {
            width: 30px !important;
            height: 30px !important;
        }

        .port-cap-text {
            position: relative !important;
            z-index: 2 !important;
        }

        .port-cap-text h3 {
            font-size: 1.5rem !important;
            font-weight: 800 !important;
            color: #1d4e79 !important;
            margin-bottom: 8px !important;
            letter-spacing: -0.5px !important;
        }

        .port-cap-text p {
            font-size: 1.1rem !important;
            line-height: 1.6 !important;
            color: #475569 !important;
            margin: 0 !important;
        }

        @media (max-width: 992px) {
            .port-hero {
                padding: 150px 5vw 100px !important;
            }

            .port-hero-bg {
                width: 100% !important;
                border-radius: 0 !important;
            }

            .port-hero-bg::after {
                background: linear-gradient(to top, #0f172a 0%, rgba(15, 23, 42, 0.3) 100%) !important;
            }

            .port-title {
                font-size: 3.5rem !important;
            }

            .port-capabilities {
                padding: 80px 5vw !important;
                border-top-right-radius: 50px !important;
                border-top-left-radius: 50px !important;
            }

            .port-cap-left {
                position: relative !important;
                top: 0 !important;
                margin-bottom: 40px !important;
            }

            .port-cap-left h2 {
                font-size: 3rem !important;
            }

            .port-cap-item {
                flex-direction: column !important;
                align-items: flex-start !important;
                padding: 40px 30px !important;
                border-radius: 25px !important;
            }

            .port-cap-item:hover {
                transform: translateY(-10px) !important;
            }
        }
    </style>

    <div class="port-wrapper">
        <div class="port-hero">
            <div class="port-hero-bg m-reveal"></div>
            <div class="port-hero-text">
                <span class="port-subtitle m-reveal">SERVICE DETAIL</span>
                <h1 class="port-title m-reveal m-delay-1"><?php echo esc_html($srv['title']); ?></h1>
                <p class="port-desc m-reveal m-delay-2"><?php echo esc_html($srv['desc']); ?></p>
            </div>
        </div>

        <div class="port-capabilities">
            <div class="port-cap-left m-reveal-left">
                <h2>Capabilities</h2>
                <p>What we deliver to our partners to ensure continuity, security, and extreme performance.</p>
            </div>
            <div class="port-cap-right">
                <?php foreach ($srv['points'] as $index => $point): ?>
                    <div class="port-cap-item m-reveal m-delay-<?php echo min(5, $index + 1); ?>">
                        <span class="port-cap-num">0<?php echo $index + 1; ?></span>
                        <div class="port-icon"><?php echo $point['icon']; ?></div>
                        <div class="port-cap-text">
                            <h3><?php echo esc_html($point['title']); ?></h3>
                            <p><?php echo esc_html($point['desc']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// ==========================================
// ABOUT US SHORTCODE [munisys_about_premium]
// ==========================================
add_shortcode('munisys_about_premium', 'render_munisys_about_shortcode_premium');
function render_munisys_about_shortcode_premium()
{
    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap');

        .abt-wrapper {
            font-family: 'Outfit', sans-serif !important;
            background: #ffffff !important;
            color: #0f172a !important;
            width: 100% !important;
            overflow: hidden !important;
        }

        /* Hero Parallax */
        .abt-hero {
            position: relative !important;
            min-height: 90vh !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            background: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2560&auto=format&fit=crop') center/cover fixed !important;
            color: #ffffff !important;
        }

        .abt-hero::before {
            content: '' !important;
            position: absolute !important;
            inset: 0 !important;
            background: rgba(29, 78, 121, 0.75) !important;
        }

        .abt-hero-content {
            position: relative !important;
            z-index: 2 !important;
            max-width: 900px !important;
            padding: 0 5vw !important;
        }

        .abt-subtitle {
            font-size: 1.2rem !important;
            font-weight: 700 !important;
            letter-spacing: 6px !important;
            text-transform: uppercase !important;
            color: #922b55 !important;
            margin-bottom: 20px !important;
            display: block !important;
        }

        .abt-title {
            font-size: 6rem !important;
            font-weight: 900 !important;
            line-height: 1.1 !important;
            margin-bottom: 30px !important;
            color: #ffffff !important;
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
        }

        /* Story Section */
        .abt-story {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            padding: 120px 5vw !important;
            background: #f8fafc !important;
        }

        .abt-story-left {
            margin-bottom: 40px !important;
        }

        .abt-story-left h2 {
            font-size: 4.5rem !important;
            font-weight: 900 !important;
            color: #1d4e79 !important;
            line-height: 1 !important;
            letter-spacing: -1.5px !important;
        }

        .abt-story-right {
            max-width: 900px !important;
            margin: 0 auto !important;
        }

        .abt-story-right p {
            font-size: 1.6rem !important;
            line-height: 1.8 !important;
            color: #475569 !important;
            margin-bottom: 30px !important;
            font-weight: 300 !important;
            text-align: left !important;
        }

        .abt-story-right p:first-child::first-letter {
            float: left !important;
            font-size: 7.5rem !important;
            line-height: 0.8 !important;
            font-weight: 900 !important;
            color: #922b55 !important;
            margin-right: 8px !important;
            margin-bottom: 5px !important;
            margin-top: 5px !important;
        }

        /* Timeline Section */
        .abt-timeline-sec {
            padding: 100px 10vw 150px !important;
            background: #ffffff !important;
        }

        .abt-timeline-title {
            text-align: center !important;
            font-size: 3.5rem !important;
            font-weight: 900 !important;
            color: #1d4e79 !important;
            margin-bottom: 100px !important;
        }

        .abt-timeline {
            position: relative !important;
            max-width: 1000px !important;
            margin: 0 auto !important;
        }

        .abt-timeline::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            bottom: 0 !important;
            left: 50% !important;
            width: 4px !important;
            background: #e2e8f0 !important;
            transform: translateX(-50%) !important;
            border-radius: 4px !important;
        }

        .abt-tl-item {
            position: relative !important;
            width: 50% !important;
            padding: 40px !important;
            opacity: 0.9 !important;
            transition: all 0.5s ease !important;
        }

        .abt-tl-item:hover {
            opacity: 1 !important;
            transform: translateY(-5px) !important;
        }

        .abt-tl-item:nth-child(odd) {
            left: 0 !important;
            text-align: right !important;
            padding-right: 60px !important;
        }

        .abt-tl-item:nth-child(even) {
            left: 50% !important;
            text-align: left !important;
            padding-left: 60px !important;
        }

        .abt-tl-dot {
            position: absolute !important;
            top: 50% !important;
            width: 24px !important;
            height: 24px !important;
            background: #922b55 !important;
            border: 4px solid #fff !important;
            border-radius: 50% !important;
            transform: translateY(-50%) !important;
            box-shadow: 0 0 0 4px rgba(146, 43, 85, 0.2) !important;
            z-index: 2 !important;
        }

        .abt-tl-item:nth-child(odd) .abt-tl-dot {
            right: -12px !important;
        }

        .abt-tl-item:nth-child(even) .abt-tl-dot {
            left: -12px !important;
        }

        .abt-tl-year {
            font-size: 3rem !important;
            font-weight: 900 !important;
            color: #1d4e79 !important;
            margin-bottom: 10px !important;
            line-height: 1 !important;
        }

        .abt-tl-text {
            font-size: 1.15rem !important;
            color: #64748b !important;
            line-height: 1.6 !important;
        }

        @media (max-width: 992px) {
            .abt-title {
                font-size: 3.5rem !important;
            }

            .abt-story {
                padding: 80px 5vw !important;
            }

            .abt-story-left h2 {
                font-size: 3.5rem !important;
            }

            .abt-timeline::before {
                left: 30px !important;
            }

            .abt-tl-item {
                width: 100% !important;
                text-align: left !important;
                padding-left: 80px !important;
                padding-right: 20px !important;
            }

            .abt-tl-item:nth-child(odd) {
                left: 0 !important;
                text-align: left !important;
                padding-right: 20px !important;
                padding-left: 80px !important;
            }

            .abt-tl-item:nth-child(even) {
                left: 0 !important;
            }

            .abt-tl-item:nth-child(odd) .abt-tl-dot {
                right: auto !important;
                left: 18px !important;
            }

            .abt-tl-item:nth-child(even) .abt-tl-dot {
                left: 18px !important;
            }
        }
    </style>
    <div class="abt-wrapper">
        <div class="abt-hero">
            <div class="abt-hero-content m-reveal">
                <span class="abt-subtitle">Our Heritage</span>
                <h1 class="abt-title">Empowering Digital Transformation</h1>
            </div>
        </div>

        <div class="abt-story">
            <div class="abt-story-left m-reveal-left">
                <h2>Who We Are</h2>
            </div>
            <div class="abt-story-right m-reveal">
                <p>Since our inception, Munisys has been at the forefront of technological innovation. We believe that
                    technology is the ultimate equalizer, enabling businesses to scale, secure their assets, and streamline
                    operations in ways never before possible.</p>
                <p>Our team of certified experts works relentlessly to architect and deploy IT solutions that are not just
                    robust, but future-proof. By combining deep industry expertise with cutting-edge tools, we transform
                    complex challenges into seamless digital experiences.</p>
            </div>
        </div>

        <div class="abt-timeline-sec">
            <h2 class="abt-timeline-title m-reveal">Our Journey</h2>
            <div class="abt-timeline">
                <div class="abt-tl-item m-reveal m-delay-1">
                    <div class="abt-tl-dot"></div>
                    <div class="abt-tl-year">1993</div>
                    <div class="abt-tl-text">Founded with a vision to provide exceptional IT infrastructure services to
                        local enterprises.</div>
                </div>
                <div class="abt-tl-item m-reveal m-delay-2">
                    <div class="abt-tl-dot"></div>
                    <div class="abt-tl-year">2005</div>
                    <div class="abt-tl-text">Expanded our portfolio to include advanced Cybersecurity and Network
                        Integration.</div>
                </div>
                <div class="abt-tl-item m-reveal m-delay-3">
                    <div class="abt-tl-dot"></div>
                    <div class="abt-tl-year">2015</div>
                    <div class="abt-tl-text">Launched our Managed Services division, providing 24/7 proactive monitoring and
                        SOC.</div>
                </div>
                <div class="abt-tl-item m-reveal m-delay-4">
                    <div class="abt-tl-dot"></div>
                    <div class="abt-tl-year">2024</div>
                    <div class="abt-tl-text">Leading the Cloud-Native transformation era, partnering with global tech
                        giants.</div>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// ==========================================
// CONTACT US SHORTCODE [munisys_contact_premium]
// ==========================================
add_shortcode('munisys_contact_premium', 'render_munisys_contact_shortcode_premium');
function render_munisys_contact_shortcode_premium()
{
    ob_start();
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@200;300;400;600;800;900&display=swap');

        .cnt-wrapper {
            font-family: 'Outfit', sans-serif !important;
            width: 100% !important;
            min-height: 100vh !important;
            background: #060d1a !important;
            position: relative !important;
            overflow: hidden !important;
        }

        /* ── Animated gradient orbs ── */
        .cnt-orb {
            position: absolute !important;
            border-radius: 50% !important;
            pointer-events: none !important;
            filter: blur(100px) !important;
            opacity: 0.35 !important;
        }

        .cnt-orb-1 {
            width: 700px !important;
            height: 700px !important;
            background: radial-gradient(circle, #1d4e79 0%, transparent 70%) !important;
            top: -200px !important;
            left: -200px !important;
            animation: cnt-drift 12s ease-in-out infinite alternate !important;
        }

        .cnt-orb-2 {
            width: 600px !important;
            height: 600px !important;
            background: radial-gradient(circle, #922b55 0%, transparent 70%) !important;
            bottom: -200px !important;
            right: -150px !important;
            animation: cnt-drift 15s ease-in-out infinite alternate-reverse !important;
        }

        .cnt-orb-3 {
            width: 400px !important;
            height: 400px !important;
            background: radial-gradient(circle, rgba(29, 78, 121, 0.5) 0%, transparent 70%) !important;
            top: 50% !important;
            left: 40% !important;
            animation: cnt-drift 18s ease-in-out infinite alternate !important;
        }

        @keyframes cnt-drift {
            from {
                transform: translate(0px, 0px) scale(1);
            }

            to {
                transform: translate(40px, 30px) scale(1.1);
            }
        }

        /* ── Page header inside the dark section ── */
        .cnt-header {
            position: relative !important;
            z-index: 2 !important;
            text-align: center !important;
            padding: 120px 5vw 80px !important;
        }

        .cnt-eyebrow {
            display: inline-block !important;
            font-size: 0.85rem !important;
            font-weight: 700 !important;
            letter-spacing: 5px !important;
            text-transform: uppercase !important;
            color: #922b55 !important;
            margin-bottom: 20px !important;
            border: 1px solid rgba(146, 43, 85, 0.4) !important;
            padding: 6px 18px !important;
            border-radius: 50px !important;
        }

        .cnt-headline {
            font-size: clamp(3rem, 7vw, 7rem) !important;
            font-weight: 900 !important;
            line-height: 1 !important;
            letter-spacing: -3px !important;
            color: #ffffff !important;
            margin-bottom: 25px !important;
        }

        .cnt-headline span {
            background: linear-gradient(135deg, #922b55, #c04578) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
        }

        .cnt-subline {
            font-size: 1.25rem !important;
            font-weight: 300 !important;
            color: rgba(255, 255, 255, 0.55) !important;
            max-width: 600px !important;
            margin: 0 auto !important;
            line-height: 1.7 !important;
        }

        /* ── Two-column body ── */
        .cnt-body {
            position: relative !important;
            z-index: 2 !important;
            display: grid !important;
            grid-template-columns: 1fr 1.6fr !important;
            gap: 60px !important;
            max-width: 1300px !important;
            margin: 0 auto !important;
            padding: 0 5vw 140px !important;
            align-items: start !important;
        }

        /* ── Left info panel ── */
        .cnt-info-panel {
            display: flex !important;
            flex-direction: column !important;
            gap: 30px !important;
        }

        .cnt-info-card {
            background: rgba(255, 255, 255, 0.04) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 24px !important;
            padding: 30px !important;
            display: flex !important;
            align-items: flex-start !important;
            gap: 20px !important;
            transition: background 0.3s ease, border-color 0.3s ease !important;
        }

        .cnt-info-card:hover {
            background: rgba(255, 255, 255, 0.07) !important;
            border-color: rgba(146, 43, 85, 0.35) !important;
        }

        .cnt-icon-wrap {
            flex-shrink: 0 !important;
            width: 52px !important;
            height: 52px !important;
            background: rgba(146, 43, 85, 0.15) !important;
            border-radius: 16px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #c04578 !important;
            transition: background 0.3s ease !important;
        }

        .cnt-info-card:hover .cnt-icon-wrap {
            background: rgba(146, 43, 85, 0.3) !important;
        }

        .cnt-icon-wrap svg {
            width: 22px !important;
            height: 22px !important;
        }

        .cnt-info-text h4 {
            font-size: 0.8rem !important;
            font-weight: 700 !important;
            letter-spacing: 3px !important;
            text-transform: uppercase !important;
            color: rgba(255, 255, 255, 0.4) !important;
            margin-bottom: 6px !important;
        }

        .cnt-info-text p {
            font-size: 1.1rem !important;
            font-weight: 400 !important;
            color: #ffffff !important;
            margin: 0 !important;
            line-height: 1.5 !important;
        }

        /* social links row */
        .cnt-social-row {
            display: flex !important;
            gap: 14px !important;
            margin-top: 10px !important;
        }

        .cnt-social-btn {
            width: 44px !important;
            height: 44px !important;
            border-radius: 12px !important;
            background: rgba(255, 255, 255, 0.06) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: rgba(255, 255, 255, 0.6) !important;
            text-decoration: none !important;
            transition: all 0.3s ease !important;
        }

        .cnt-social-btn:hover {
            background: #922b55 !important;
            border-color: #922b55 !important;
            color: #fff !important;
            transform: translateY(-3px) !important;
        }

        .cnt-social-btn svg {
            width: 18px !important;
            height: 18px !important;
        }

        /* ── Right: glassmorphism form ── */
        .cnt-form-glass {
            background: rgba(255, 255, 255, 0.05) !important;
            backdrop-filter: blur(24px) !important;
            -webkit-backdrop-filter: blur(24px) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 32px !important;
            padding: 60px !important;
        }

        .cnt-form-title {
            font-size: 2rem !important;
            font-weight: 800 !important;
            color: #ffffff !important;
            margin-bottom: 10px !important;
        }

        .cnt-form-sub {
            font-size: 1rem !important;
            color: rgba(255, 255, 255, 0.45) !important;
            margin-bottom: 40px !important;
        }

        .cnt-row-2 {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 0 30px !important;
        }

        .cnt-field {
            position: relative !important;
            margin-bottom: 35px !important;
        }

        .cnt-field input,
        .cnt-field textarea {
            width: 100% !important;
            background: transparent !important;
            border: none !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
            padding: 14px 0 !important;
            font-size: 1.1rem !important;
            color: #ffffff !important;
            font-family: 'Outfit', sans-serif !important;
            transition: border-color 0.3s ease !important;
            resize: none !important;
            box-sizing: border-box !important;
        }

        .cnt-field input:focus,
        .cnt-field textarea:focus {
            outline: none !important;
            border-bottom-color: #922b55 !important;
        }

        .cnt-field input::placeholder,
        .cnt-field textarea::placeholder {
            color: rgba(255, 255, 255, 0.3) !important;
            font-size: 1rem !important;
        }

        .cnt-field label {
            display: block !important;
            font-size: 0.78rem !important;
            font-weight: 700 !important;
            letter-spacing: 2px !important;
            text-transform: uppercase !important;
            color: rgba(255, 255, 255, 0.4) !important;
            margin-bottom: 6px !important;
        }

        .cnt-submit-row {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            margin-top: 20px !important;
            flex-wrap: wrap !important;
            gap: 15px !important;
        }

        .cnt-note {
            font-size: 0.9rem !important;
            color: rgba(255, 255, 255, 0.3) !important;
        }

        .cnt-btn {
            display: inline-flex !important;
            align-items: center !important;
            gap: 12px !important;
            background: #922b55 !important;
            color: #fff !important;
            border: none !important;
            padding: 18px 40px !important;
            font-size: 1rem !important;
            font-weight: 700 !important;
            border-radius: 50px !important;
            cursor: pointer !important;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
            font-family: 'Outfit', sans-serif !important;
            text-transform: uppercase !important;
            letter-spacing: 1.5px !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .cnt-btn::before {
            content: '' !important;
            position: absolute !important;
            inset: 0 !important;
            background: linear-gradient(135deg, #c04578, #922b55) !important;
            opacity: 0 !important;
            transition: opacity 0.4s ease !important;
        }

        .cnt-btn:hover::before {
            opacity: 1 !important;
        }

        .cnt-btn:hover {
            transform: translateY(-4px) !important;
            box-shadow: 0 20px 50px rgba(146, 43, 85, 0.4) !important;
        }

        .cnt-btn span,
        .cnt-btn svg {
            position: relative !important;
            z-index: 1 !important;
        }

        .cnt-btn svg {
            transition: transform 0.3s ease !important;
        }

        .cnt-btn:hover svg {
            transform: translateX(5px) !important;
        }

        @media (max-width: 1024px) {
            .cnt-body {
                grid-template-columns: 1fr !important;
                gap: 40px !important;
            }

            .cnt-info-panel {
                flex-direction: row !important;
                flex-wrap: wrap !important;
            }

            .cnt-info-card {
                flex: 1 1 260px !important;
            }
        }

        @media (max-width: 768px) {
            .cnt-headline {
                letter-spacing: -1px !important;
            }

            .cnt-form-glass {
                padding: 35px 25px !important;
            }

            .cnt-row-2 {
                grid-template-columns: 1fr !important;
            }

            .cnt-info-panel {
                flex-direction: column !important;
            }

            .cnt-submit-row {
                flex-direction: column !important;
                align-items: flex-start !important;
            }
        }
    </style>

    <div class="cnt-wrapper">
        <!-- Animated orbs -->
        <div class="cnt-orb cnt-orb-1"></div>
        <div class="cnt-orb cnt-orb-2"></div>
        <div class="cnt-orb cnt-orb-3"></div>

        <!-- Header -->
        <div class="cnt-header">
            <span class="cnt-eyebrow">Contact</span>
            <h1 class="cnt-headline">Let's Build<br><span>Together.</span></h1>
            <p class="cnt-subline">Ready to accelerate your digital transformation? Our experts are here, available, and
                eager to help you shape the future.</p>
        </div>

        <!-- Body -->
        <div class="cnt-body">

            <!-- Left: info cards -->
            <div class="cnt-info-panel">
                <div class="cnt-info-card">
                    <div class="cnt-icon-wrap">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <div class="cnt-info-text">
                        <h4>Headquarters</h4>
                        <p>123 Innovation Drive,<br>Tech District, Casablanca</p>
                    </div>
                </div>
                <div class="cnt-info-card">
                    <div class="cnt-icon-wrap">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="cnt-info-text">
                        <h4>Email Us</h4>
                        <p>contact@munisys.com</p>
                    </div>
                </div>
                <div class="cnt-info-card">
                    <div class="cnt-icon-wrap">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <div class="cnt-info-text">
                        <h4>Call Us</h4>
                        <p>+212 5 22 00 00 00</p>
                    </div>
                </div>

                <!-- Social links -->
                <div class="cnt-social-row">
                    <a href="#" class="cnt-social-btn" aria-label="LinkedIn">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z" />
                            <circle cx="4" cy="4" r="2" fill="currentColor" />
                        </svg>
                    </a>
                    <a href="#" class="cnt-social-btn" aria-label="Twitter/X">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                        </svg>
                    </a>
                    <a href="#" class="cnt-social-btn" aria-label="Facebook">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right: glassmorphism form -->
            <div class="cnt-form-glass">
                <h2 class="cnt-form-title">Send a Message</h2>
                <p class="cnt-form-sub">Fill in the form below and we'll get back to you within 24 hours.</p>
                <form action="#" method="POST"
                    onsubmit="event.preventDefault(); this.innerHTML='<p style=\'color:#fff;font-size:1.5rem;font-weight:700;text-align:center;padding:40px 0;\'>✅ Message sent! We\'ll be in touch shortly.</p>';">
                    <div class="cnt-row-2">
                        <div class="cnt-field">
                            <label for="cnt-name">Full Name</label>
                            <input type="text" id="cnt-name" placeholder="John Doe" required>
                        </div>
                        <div class="cnt-field">
                            <label for="cnt-email">Email Address</label>
                            <input type="email" id="cnt-email" placeholder="john@company.com" required>
                        </div>
                    </div>
                    <div class="cnt-row-2">
                        <div class="cnt-field">
                            <label for="cnt-company">Company</label>
                            <input type="text" id="cnt-company" placeholder="Acme Corp">
                        </div>
                        <div class="cnt-field">
                            <label for="cnt-phone">Phone Number</label>
                            <input type="tel" id="cnt-phone" placeholder="+212 6 00 00 00 00">
                        </div>
                    </div>
                    <div class="cnt-field">
                        <label for="cnt-msg">How can we help you?</label>
                        <textarea id="cnt-msg" rows="4" placeholder="Tell us about your project or challenge..."
                            required></textarea>
                    </div>
                    <div class="cnt-submit-row">
                        <span class="cnt-note">🔒 Your data is 100% secure &amp; never shared.</span>
                        <button type="submit" class="cnt-btn">
                            <span>Send Message</span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <?php
    return ob_get_clean();
}

