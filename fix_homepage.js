const fs = require('fs');

let html = fs.readFileSync('PAGE_ACCUEIL_COMPLETE.html', 'utf8');

// Ensure we strip out any existing script tag and undefined trailing text
html = html.split('<script>')[0];
let cleanHtml = html.split('<div class=\"ft-wrapper\">')[0];
if (cleanHtml.includes('undefined')) {
    cleanHtml = cleanHtml.split('undefined')[0];
}
cleanHtml = cleanHtml.trim();

if (!cleanHtml.endsWith('</div>')) {
    cleanHtml += '\n</div>'; // close #munisys-wrapper
}

const script = `
<script>
(function initMunisysScript() {
  function runInteractions() {
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

    const menuItems = document.querySelectorAll('.sol-menu-item');
    const solContent = document.querySelector('.sol-content');

    if(menuItems.length > 0 && solContent) {
      solContent.style.transition = 'opacity 0.25s ease-in-out';
      
      menuItems.forEach((item, index) => {
        const newItem = item.cloneNode(true);
        item.replaceWith(newItem);
        
        newItem.addEventListener('click', () => {
          const freshMenuItems = document.querySelectorAll('.sol-menu-item');
          freshMenuItems.forEach(i => i.classList.remove('active'));
          newItem.classList.add('active');
          
          solContent.style.opacity = '0';
          setTimeout(() => {
            const data = tabsData[index];
            solContent.innerHTML = \`
              <h3>\${data.title}</h3>
              \${data.desc}
              <a href=\"/solutions\" class=\"sol-link\">Read more <span style=\"margin-left:5px\">→</span></a>
            \`;
            solContent.style.opacity = '1';
          }, 250);
        });
      });
    }

    const srvAccs = document.querySelectorAll('.srv-acc');
    srvAccs.forEach(acc => {
      const head = acc.querySelector('.srv-acc-head');
      const body = acc.querySelector('.srv-acc-body');
      const icon = acc.querySelector('.acc-x');
      
      if(head) {
        const newHead = head.cloneNode(true);
        head.replaceWith(newHead);
        
        newHead.addEventListener('click', () => {
          const freshAccs = document.querySelectorAll('.srv-acc');
          const isAlreadyOpen = acc.classList.contains('open');
          
          freshAccs.forEach(c => {
            c.classList.remove('open');
            const b = c.querySelector('.srv-acc-body');
            const i = c.querySelector('.acc-x');
            if(b) b.style.display = 'none';
            if(i) i.textContent = '+';
          });
          
          if(!isAlreadyOpen) {
            acc.classList.add('open');
            if(body) body.style.display = 'block';
            if(icon) icon.textContent = '−';
          }
        });
      }
    });

    const heroBtn = document.querySelector('.hero-cta-btn');
    const solSection = document.querySelector('#solutions-section');
    if(heroBtn && solSection) {
      const newBtn = heroBtn.cloneNode(true);
      heroBtn.replaceWith(newBtn);
      
      newBtn.addEventListener('click', (e) => {
        if(window.location.pathname === '/' || window.location.pathname === '') {
          e.preventDefault();
          solSection.scrollIntoView({ behavior: 'smooth' });
        }
      });
    }
  }
  
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', runInteractions);
  } else {
    runInteractions();
  }

  const observer = new MutationObserver((mutations) => {
    for(let mutation of mutations) {
      if(mutation.addedNodes.length > 0) {
        mutation.addedNodes.forEach(node => {
          if(node.id === 'munisys-wrapper' || (node.querySelector && node.querySelector('#munisys-wrapper'))) {
            setTimeout(runInteractions, 100);
          }
        });
      }
    }
  });

  observer.observe(document.body, {
    childList: true,
    subtree: true
  });
})();
</script>
`;

cleanHtml += '\n' + script;
fs.writeFileSync('PAGE_ACCUEIL_COMPLETE.html', cleanHtml);

// Minify safely
let minified = cleanHtml.replace(/\n/g, ' ').replace(/\s{2,}/g, ' ').replace(/>\s+</g, '><').trim();
fs.writeFileSync('PAGE_ACCUEIL_MINIFIED.html', minified);
console.log('Homepage fixed, footer removed completely, JS restored!');
