(function initMunisysScript() {
  // Nettoyage de l'ancien timer si le script est rechargé par Breakdance
  if (window.munisysSliderTimer) {
    clearInterval(window.munisysSliderTimer);
  }

  const tabsData = [
    { title: "Core Infrastructure Technologies", desc: "<p>Munisys leverages its unique and extensive experience to help IT organizations to setup, manage, secure and seamlessly run consistent compute, storage, networking and backup infrastructure across data centers and public clouds, through either traditional or software-defined approaches.</p><p>We focus on a range of leading data-centric, adaptable and efficient solutions that offer cloud-native operating experience, AI driven management, enhanced security and optimized performance.</p>" },
    { title: "Cybersecurity Solutions", desc: "<p>Comprehensive protection for your enterprise. We secure your network, endpoints, and data against evolving digital threats with zero-trust architectures and proactive monitoring.</p>" },
    { title: "Infrastructure Software", desc: "<p>Optimize your operations with enterprise-grade software solutions for monitoring, management, and automation across your entire IT stack.</p>" },
    { title: "Managed & Cloud Services", desc: "<p>Seamless cloud integration and managed services to accelerate your digital transformation journey while controlling costs and ensuring high availability.</p>" },
    { title: "Endpoint solutions", desc: "<p>Empower your workforce with secure, high-performance computing devices and intelligent endpoint management solutions designed for the modern hybrid workplace.</p>" }
  ];

  // Fonction pour passer au slide suivant
  function runSliderNext() {
    const hero = document.querySelector('#munisys-hero') || document.querySelector('.hero');
    if (!hero) return;
    
    const slides = Array.from(hero.querySelectorAll('.hero-slide'));
    const dots = Array.from(hero.querySelectorAll('.hero-dot'));
    if (!slides.length) return;

    let current = slides.findIndex(s => s.classList.contains('active'));
    if (current === -1) current = 0;
    
    let nextIndex = (current + 1) % slides.length;
    
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    
    if (slides[nextIndex]) slides[nextIndex].classList.add('active');
    if (dots[nextIndex]) dots[nextIndex].classList.add('active');
  }

  // Lancement automatique du slider (Toutes les 4 secondes)
  window.munisysSliderTimer = setInterval(runSliderNext, 4000);
  
  // Forcer un premier passage si jamais le DOM était figé
  setTimeout(runSliderNext, 4000);

  // On évite d'ajouter 100 écouteurs si le script est rechargé plusieurs fois
  if (!window.munisysClickListenerAdded) {
    window.munisysClickListenerAdded = true;

    document.addEventListener('click', function(e) {
      // 1. GESTION DES ONGLETS "SOLUTIONS"
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
                content.innerHTML = `<h3>${data.title}</h3>${data.desc}<a href="/solutions" class="sol-link">Read more <span style="margin-left:5px">→</span></a>`;
                content.style.opacity = '1';
              }, 250);
            }
          }
        }
      }

      // 2. GESTION DE L'ACCORDÉON "SERVICES"
      const accHead = e.target.closest('.srv-acc-head');
      if (accHead) {
        const acc = accHead.closest('.srv-acc');
        const wrapper = acc.closest('.srv-right');
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

      // 3. SMOOTH SCROLL BOUTON HERO
      const heroBtn = e.target.closest('.hero-cta-btn');
      if (heroBtn && heroBtn.getAttribute('href') === '#solutions-section') {
        const solSection = document.querySelector('#solutions-section');
        if (solSection && (window.location.pathname === '/' || window.location.pathname === '')) {
          e.preventDefault();
          solSection.scrollIntoView({ behavior: 'smooth' });
        }
      }

      // 4. CONTRÔLES DU HERO SLIDER
      const prevBtn = e.target.closest('.hero-arrow-prev');
      const nextBtn = e.target.closest('.hero-arrow-next');
      const dotBtn = e.target.closest('.hero-dot');

      if (prevBtn || nextBtn || dotBtn) {
        e.preventDefault();
        
        const hero = document.querySelector('#munisys-hero') || document.querySelector('.hero');
        if (!hero) return;
        
        const slides = Array.from(hero.querySelectorAll('.hero-slide'));
        const dots = Array.from(hero.querySelectorAll('.hero-dot'));
        if (!slides.length) return;

        let current = slides.findIndex(s => s.classList.contains('active'));
        if (current === -1) current = 0;
        
        let targetIndex = current;
        if (prevBtn) targetIndex = current - 1;
        if (nextBtn) targetIndex = current + 1;
        if (dotBtn) targetIndex = dots.indexOf(dotBtn);

        targetIndex = ((targetIndex % slides.length) + slides.length) % slides.length;

        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));
        
        if (slides[targetIndex]) slides[targetIndex].classList.add('active');
        if (dots[targetIndex]) dots[targetIndex].classList.add('active');

        // Réinitialiser le timer automatique après un clic manuel
        clearInterval(window.munisysSliderTimer);
        window.munisysSliderTimer = setInterval(runSliderNext, 4000);
      }
    }, true);
  }
})();