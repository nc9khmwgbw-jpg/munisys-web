const fs = require('fs');
const { solutionsData } = require('./src/app/solutions/data.js');

const categories = [
  {
    id: 'core-infra',
    title: 'Core Infrastructure Technologies',
    desc: 'Setting up, managing, and securing consistent compute, storage, networking and backup infrastructure across data centers and public clouds.',
    keys: ['compute-storage', 'hyper-converged-infrastructures', 'data-resilience', 'networking', 'cabling-data-center-environment']
  },
  {
    id: 'cybersecurity',
    title: 'Cybersecurity Solutions',
    desc: 'Comprehensive protection for your enterprise. We secure your network, endpoints, and data against evolving digital threats.',
    keys: ['endpoint-security', 'network-security', 'cloud-application-security', 'identity-and-access-management', 'visibility-compliance']
  },
  {
    id: 'infra-software',
    title: 'Infrastructure Software',
    desc: 'Optimize your operations with enterprise-grade software solutions for monitoring, management, and automation.',
    keys: ['service-asset-management', 'observability-automation-orchestration', 'data-management-analytics', 'application-modernization']
  },
  {
    id: 'managed-cloud',
    title: 'Managed & Cloud Services',
    desc: 'Seamless cloud integration and managed services to accelerate your digital transformation journey.',
    keys: ['hosting-and-cloud-infrastructure-services', 'application-and-platforms-services', 'business-resilience-data-protection', 'managed-security-services', 'advisory-professional-management-services']
  },
  {
    id: 'endpoint-sol',
    title: 'Endpoint Solutions',
    desc: 'Empower your workforce with secure, high-performance computing devices and intelligent endpoint management solutions.',
    keys: ['digital-workplace', 'printing-solutions', 'end-customer-facing-devices']
  }
];

let html = `<!-- PAGE SOLUTIONS MUNISYS -->
<!-- Copiez TOUT ce code et collez-le dans le bloc "Shortcode" de Breakdance sur la page Solutions -->

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

#munisys-solutions-wrapper *, #munisys-solutions-wrapper *::before, #munisys-solutions-wrapper *::after { 
  box-sizing: border-box; margin: 0; padding: 0; font-family: 'Montserrat', sans-serif !important;
}

/* ── HERO ── */
#munisys-solutions-wrapper .page-hero {
  background: var(--blue-dark) !important;
  color: #fff !important;
  padding: 8rem 5rem 6rem 5rem !important;
  text-align: center !important;
  position: relative !important;
  overflow: hidden !important;
}
#munisys-solutions-wrapper .page-hero::before {
  content: ''; position: absolute !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important;
  background: radial-gradient(circle at right top, rgba(146, 43, 85, 0.2), transparent 50%) !important;
}
#munisys-solutions-wrapper .page-hero h1 {
  font-size: 3.5rem !important; font-weight: 300 !important; margin-bottom: 1.5rem !important; position: relative !important; z-index: 2 !important;
}
#munisys-solutions-wrapper .page-hero h1 strong { font-weight: 800 !important; }
#munisys-solutions-wrapper .page-hero p {
  font-size: 1.2rem !important; color: rgba(255,255,255,0.8) !important; max-width: 800px !important; margin: 0 auto !important; position: relative !important; z-index: 2 !important; line-height: 1.6 !important;
}

/* ── MAIN LAYOUT ── */
#munisys-solutions-wrapper .sol-container {
  max-width: 1440px !important; margin: 0 auto !important; padding: 5rem !important;
  display: grid !important; grid-template-columns: 350px 1fr !important; gap: 5rem !important;
}

/* ── SIDEBAR ── */
#munisys-solutions-wrapper .sol-sidebar {
  display: flex !important; flex-direction: column !important; gap: 0.5rem !important;
  position: sticky !important; top: 100px !important; height: fit-content !important;
}
#munisys-solutions-wrapper .sol-cat-btn {
  background: #f4f7f9 !important; border: none !important; padding: 1.2rem 1.5rem !important;
  text-align: left !important; font-size: 1.05rem !important; font-weight: 600 !important; color: var(--blue-dark) !important;
  cursor: pointer !important; transition: all 0.2s !important; border-left: 4px solid transparent !important;
}
#munisys-solutions-wrapper .sol-cat-btn:hover {
  background: #e9eff4 !important;
}
#munisys-solutions-wrapper .sol-cat-btn.active {
  background: #fff !important; border-left: 4px solid var(--bordeaux) !important; color: var(--bordeaux) !important;
  box-shadow: 0 4px 20px rgba(0,0,0,0.05) !important;
}

/* ── CONTENT ── */
#munisys-solutions-wrapper .sol-cat-content {
  display: none !important; animation: fadeIn 0.4s ease-in-out !important;
}
#munisys-solutions-wrapper .sol-cat-content.active {
  display: block !important;
}
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

#munisys-solutions-wrapper .cat-header { margin-bottom: 4rem !important; border-bottom: 2px solid #e9eff4 !important; padding-bottom: 2rem !important; }
#munisys-solutions-wrapper .cat-header h2 { font-size: 2.8rem !important; font-weight: 800 !important; color: var(--blue-dark) !important; margin-bottom: 1rem !important; }
#munisys-solutions-wrapper .cat-header p { font-size: 1.15rem !important; color: var(--text-gray) !important; line-height: 1.8 !important; }

#munisys-solutions-wrapper .sub-sol-grid {
  display: flex !important; flex-direction: column !important; gap: 5rem !important;
}
#munisys-solutions-wrapper .sub-sol-card {
  display: flex !important; flex-direction: column !important; gap: 1.5rem !important;
}
#munisys-solutions-wrapper .sub-sol-card h3 { font-size: 2rem !important; font-weight: 700 !important; color: var(--bordeaux) !important; }
#munisys-solutions-wrapper .sub-sol-card p.overview { font-size: 1.05rem !important; color: var(--text-dark) !important; line-height: 1.8 !important; white-space: pre-line !important; }

#munisys-solutions-wrapper .sub-sol-meta { display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 3rem !important; background: #f4f7f9 !important; padding: 2.5rem !important; border-radius: 8px !important; }
#munisys-solutions-wrapper .sub-sol-meta h4 { font-size: 1.1rem !important; color: var(--blue-dark) !important; font-weight: 800 !important; margin-bottom: 1.5rem !important; text-transform: uppercase !important; letter-spacing: 1px !important; }
#munisys-solutions-wrapper .meta-list { list-style: none !important; padding: 0 !important; margin: 0 !important; display: flex !important; flex-direction: column !important; gap: 0.8rem !important; }
#munisys-solutions-wrapper .meta-list li { display: flex !important; align-items: flex-start !important; gap: 0.8rem !important; font-size: 1rem !important; color: var(--text-gray) !important; line-height: 1.5 !important; font-weight: 500 !important;}
#munisys-solutions-wrapper .meta-list li::before { content: '■' !important; color: var(--bordeaux) !important; font-size: 0.8em !important; transform: translateY(3px) !important;}
#munisys-solutions-wrapper .partners-tags { display: flex !important; flex-wrap: wrap !important; gap: 0.8rem !important; }
#munisys-solutions-wrapper .partner-tag { background: #fff !important; border: 1px solid #d4dfe8 !important; color: var(--blue-dark) !important; padding: 0.5rem 1rem !important; font-size: 0.95rem !important; font-weight: 700 !important; border-radius: 4px !important; }

@media(max-width:1024px) {
  #munisys-solutions-wrapper .sol-container { grid-template-columns: 1fr !important; padding: 3rem !important; }
  #munisys-solutions-wrapper .sol-sidebar { position: static !important; flex-direction: row !important; overflow-x: auto !important; padding-bottom: 1rem !important; }
  #munisys-solutions-wrapper .sol-cat-btn { white-space: nowrap !important; border-left: none !important; border-bottom: 4px solid transparent !important; }
  #munisys-solutions-wrapper .sol-cat-btn.active { border-left: none !important; border-bottom: 4px solid var(--bordeaux) !important; }
  #munisys-solutions-wrapper .sub-sol-meta { grid-template-columns: 1fr !important; }
}
</style>

<div id="munisys-solutions-wrapper">

  <!-- HERO -->
  <section class="page-hero">
    <h1>Our <strong>Solutions</strong></h1>
    <p>Discover our comprehensive suite of digital infrastructure technologies tailored to empower your business operations and drive innovation.</p>
  </section>

  <!-- MAIN -->
  <section class="sol-container">
    
    <!-- SIDEBAR -->
    <div class="sol-sidebar">
`;

categories.forEach((cat, index) => {
  html += `      <button class="sol-cat-btn ${index === 0 ? 'active' : ''}" data-target="${cat.id}">${cat.title}</button>\n`;
});

html += `    </div>\n\n    <!-- CONTENT AREA -->\n    <div class="sol-content-area">\n`;

categories.forEach((cat, index) => {
  html += `      <div class="sol-cat-content ${index === 0 ? 'active' : ''}" id="${cat.id}">\n`;
  html += `        <div class="cat-header">\n          <h2>${cat.title}</h2>\n          <p>${cat.desc}</p>\n        </div>\n`;
  html += `        <div class="sub-sol-grid">\n`;

  cat.keys.forEach(key => {
    const data = solutionsData[key];
    if (data) {
      html += `          <div class="sub-sol-card">\n`;
      html += `            <h3>${data.title}</h3>\n`;
      html += `            <p class="overview">${data.overview}</p>\n`;
      
      html += `            <div class="sub-sol-meta">\n`;
      
      // Expertises
      if (data.expertises && data.expertises.length > 0) {
        html += `              <div>\n                <h4>Key Expertises</h4>\n                <ul class="meta-list">\n`;
        data.expertises.forEach(exp => {
          html += `                  <li>${exp}</li>\n`;
        });
        html += `                </ul>\n              </div>\n`;
      } else {
        html += `              <div></div>\n`;
      }

      // Partners
      if (data.partners && data.partners.length > 0) {
        html += `              <div>\n                <h4>Technology Partners</h4>\n                <div class="partners-tags">\n`;
        data.partners.forEach(partner => {
          html += `                  <span class="partner-tag">${partner}</span>\n`;
        });
        html += `                </div>\n              </div>\n`;
      } else {
        html += `              <div></div>\n`;
      }

      html += `            </div>\n`; // End sub-sol-meta
      html += `          </div>\n`; // End sub-sol-card
    }
  });

  html += `        </div>\n      </div>\n`;
});

html += `    </div>
  </section>
</div>

<script>
(function initSolutions() {
  function run() {
    const btns = document.querySelectorAll('#munisys-solutions-wrapper .sol-cat-btn');
    const contents = document.querySelectorAll('#munisys-solutions-wrapper .sol-cat-content');
    
    if(btns.length === 0) return;

    btns.forEach(btn => {
      // Pour éviter la duplication d'événements dans Breakdance
      const newBtn = btn.cloneNode(true);
      btn.replaceWith(newBtn);
      
      newBtn.addEventListener('click', () => {
        const freshBtns = document.querySelectorAll('#munisys-solutions-wrapper .sol-cat-btn');
        const freshContents = document.querySelectorAll('#munisys-solutions-wrapper .sol-cat-content');
        
        freshBtns.forEach(b => b.classList.remove('active'));
        freshContents.forEach(c => c.classList.remove('active'));
        
        newBtn.classList.add('active');
        const targetId = newBtn.getAttribute('data-target');
        const targetContent = document.getElementById(targetId);
        if(targetContent) {
          targetContent.classList.add('active');
          // Smooth scroll to top of content on mobile
          if (window.innerWidth <= 1024) {
            targetContent.scrollIntoView({behavior: 'smooth', block: 'start'});
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

  const observer = new MutationObserver((mutations) => {
    for(let mutation of mutations) {
      if(mutation.addedNodes.length > 0) {
        mutation.addedNodes.forEach(node => {
          if(node.id === 'munisys-solutions-wrapper' || (node.querySelector && node.querySelector('#munisys-solutions-wrapper'))) {
            setTimeout(run, 100);
          }
        });
      }
    }
  });
  
  observer.observe(document.body, { childList: true, subtree: true });
})();
</script>
`;

fs.writeFileSync('PAGE_SOLUTIONS_COMPLETE.html', html);
console.log('Successfully generated PAGE_SOLUTIONS_COMPLETE.html');
