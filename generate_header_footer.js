const fs = require('fs');
const dbFull = require('./src/data/db.json');
const db = dbFull.fr || dbFull;

function minify(html) {
  html = html.replace(/<!--[\s\S]*?-->/g, '');
  html = html.replace(/<style>([\s\S]*?)<\/style>/ig, (match, css) => {
    return '<style>' + css.replace(/\s+/g, ' ').replace(/\/\*[\s\S]*?\*\//g, '').trim() + '</style>';
  });
  html = html.replace(/>\s+</g, '><').trim();
  return html;
}

const headerCSS = `
<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap');
#munisys-global-header {
  --bordeaux: #922b55;
  --blue-dark: #1d4e79;
  --blue-light: #83a5c2;
  --text-dark: #333333;
  --text-gray: #666666;
  --bg-gray: #fcfcfc;
  font-family: 'Montserrat', sans-serif !important;
}
#munisys-global-header *, #munisys-global-header *::before, #munisys-global-header *::after { 
  box-sizing: border-box; margin: 0; padding: 0; font-family: 'Montserrat', sans-serif !important;
}
#munisys-global-header a { text-decoration: none; color: inherit; }
#munisys-global-header ul { list-style: none; }
#munisys-global-header button { font-family: inherit; cursor: pointer; border: none; background: none; }

#munisys-global-header .nav {
  position: sticky !important; top: 0 !important; z-index: 999999 !important;
  background: #ffffff !important; height: 90px !important;
  display: flex !important; align-items: stretch !important;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05) !important;
}
#munisys-global-header .nav-inner {
  display: flex !important; align-items: stretch !important; width: 100% !important; max-width: 1440px !important; margin: 0 auto !important;
}
#munisys-global-header .nav-logo {
  display: flex !important; align-items: center !important; gap: 12px !important;
  padding: 0 2rem !important; flex-shrink: 0 !important;
}
#munisys-global-header .nav-logo-mark {
  font-size: 1.6rem !important; font-weight: 800 !important; color: var(--blue-dark) !important; letter-spacing: 0px !important;
}
#munisys-global-header .nav-links {
  display: flex !important; align-items: center !important; flex: 1 !important; padding: 0 0.5rem !important; gap: 0.2rem !important;
}
#munisys-global-header .nav-item {
  position: relative !important; height: 100% !important; display: flex !important; align-items: center !important;
}
#munisys-global-header .nav-link {
  font-size: 0.65rem !important; font-weight: 600 !important; color: #111 !important;
  padding: 0 0.2rem !important; height: 100% !important; display: flex !important; align-items: center !important; gap: 2px !important;
  transition: color 0.2s !important; letter-spacing: 0px !important; text-transform: uppercase !important;
}
#munisys-global-header .nav-link:hover, #munisys-global-header .nav-item:hover .nav-link {
  color: var(--blue-dark) !important;
}

#munisys-global-header .mega-menu {
  position: absolute !important; top: 90px !important; left: -100px !important;
  background: #fff !important; width: 1100px !important;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
  border-radius: 4px !important; border-top: 2px solid var(--bg-gray) !important;
  display: none !important; opacity: 0 !important; transform: translateY(10px) !important;
  transition: opacity 0.3s, transform 0.3s !important;
  padding: 2.5rem !important; grid-template-columns: repeat(5, 1fr) !important; gap: 2rem !important;
  pointer-events: none !important;
}
#munisys-global-header .nav-item:hover .mega-menu {
  display: grid !important; opacity: 1 !important; transform: translateY(0) !important; pointer-events: auto !important;
}
#munisys-global-header .mega-col h3 {
  font-size: 1.05rem !important; font-weight: 700 !important; color: var(--blue-dark) !important;
  margin-bottom: 1.5rem !important; line-height: 1.4 !important;
}
#munisys-global-header .mega-col ul { display: flex !important; flex-direction: column !important; gap: 1rem !important; }
#munisys-global-header .mega-col a {
  font-size: 0.8rem !important; color: var(--text-gray) !important; font-weight: 500 !important;
  line-height: 1.4 !important; transition: color 0.2s !important;
}
#munisys-global-header .mega-col a:hover { color: var(--bordeaux) !important; }

#munisys-global-header .nav-right {
  display: flex !important; align-items: stretch !important; margin-left: auto !important;
}
#munisys-global-header .nav-search {
  padding: 0 0.5rem !important; color: var(--blue-dark) !important; display: flex !important; align-items: center !important;
}
#munisys-global-header .nav-cta {
  display: flex !important; align-items: center !important; gap: 0.3rem !important;
  background: var(--bordeaux) !important; color: #fff !important;
  font-size: 0.65rem !important; font-weight: 500 !important;
  padding: 0 0.8rem !important; height: 100% !important;
  transition: background 0.2s !important; text-transform: uppercase !important;
}
#munisys-global-header .nav-cta:hover { background: #7a2245 !important; }
@media(max-width:1024px) {
  #munisys-global-header .nav-links { display: none !important; }
}
</style>
`;

const headerHTML = `
<div id="munisys-global-header">
  <nav class="nav">
    <div class="nav-inner">
      <a href="/" class="nav-logo">
        <svg width="45" height="45" viewBox="0 0 100 100" fill="none">
          <polygon points="10,20 40,50 10,80" fill="#83a5c2" />
          <polygon points="10,20 40,50 40,20" fill="#1d4e79" />
          <polygon points="40,50 70,20 70,80" fill="#922b55" />
          <polygon points="70,20 100,20 70,50" fill="#1d4e79" />
        </svg>
        <span class="nav-logo-mark">${db.navigation.brandName}</span>
      </a>
      <div class="nav-links">
        <div class="nav-item">
          <a href="/solutions" class="nav-link">
            <span>Solutions</span>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M6 9l6 6 6-6"/>
            </svg>
          </a>
          <div class="mega-menu">
            ${db.navigation.megaMenu.map(col => `
              <div class="mega-col">
                <h3><a href="${col.href}">${col.title}</a></h3>
                <ul>
                  ${col.items.map(item => `
                    <li><a href="${item.href}">${item.label}</a></li>
                  `).join('')}
                </ul>
              </div>
            `).join('')}
          </div>
        </div>
        ${db.navigation.mainLinks.map(link => `
          <div class="nav-item">
            <a href="${link.href}" class="nav-link"><span>${link.label}</span></a>
          </div>
        `).join('')}
      </div>
      <div class="nav-right">
        <button class="nav-search" aria-label="Search">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/>
            <path d="M21 21l-4.35-4.35"/>
          </svg>
        </button>
        <a href="/contact" class="nav-cta">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
          <span>${db.navigation.ctaLabel}</span>
        </a>
      </div>
    </div>
  </nav>
</div>
`;
fs.writeFileSync('PAGE_HEADER_MINIFIED.html', minify(headerCSS + headerHTML));

const footerCSS = `
<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap');
#munisys-global-footer {
  --bordeaux: #922b55;
  --blue-dark: #1d4e79;
  --blue-light: #83a5c2;
  --text-dark: #333333;
  --text-gray: #666666;
  --bg-gray: #fcfcfc;
  font-family: 'Montserrat', sans-serif !important;
  background: #1a1a1a !important;
  color: rgba(255,255,255,0.6) !important;
  width: 100vw !important;
  position: relative !important;
  left: 50% !important;
  right: 50% !important;
  margin-left: -50vw !important;
  margin-right: -50vw !important;
}
#munisys-global-footer *, #munisys-global-footer *::before, #munisys-global-footer *::after { 
  box-sizing: border-box; margin: 0; padding: 0; font-family: 'Montserrat', sans-serif !important;
}
#munisys-global-footer a { text-decoration: none; color: inherit; }
#munisys-global-footer ul { list-style: none; }

#munisys-global-footer .ft-inner { max-width: 1440px !important; margin: 0 auto !important; }
#munisys-global-footer .ft-top {
  display: flex !important; justify-content: space-between !important; align-items: center !important;
  padding: 2.5rem 5rem !important; border-bottom: 1px solid rgba(255,255,255,0.08) !important;
}
#munisys-global-footer .ft-brand { font-size: 1.4rem !important; font-weight: 900 !important; color: #fff !important; letter-spacing: 1px !important; }
#munisys-global-footer .ft-linkedin {
  border: 1px solid rgba(255,255,255,0.3) !important; color: rgba(255,255,255,0.7) !important;
  font-size: 0.82rem !important; font-weight: 600 !important; padding: 0.5rem 1.2rem !important; border-radius: 3px !important;
  transition: color 0.18s, border-color 0.18s !important;
}
#munisys-global-footer .ft-linkedin:hover { color: #fff !important; border-color: #fff !important; }
#munisys-global-footer .ft-main { display: grid !important; grid-template-columns: repeat(4, 1fr) !important; gap: 2rem !important; padding: 3rem 5rem !important; }
#munisys-global-footer .ft-col h4 {
  color: #fff !important; font-size: 0.68rem !important; font-weight: 700 !important; text-transform: uppercase !important;
  letter-spacing: 2px !important; margin-bottom: 1rem !important;
}
#munisys-global-footer .ft-col ul { display: flex !important; flex-direction: column !important; gap: 0.6rem !important; }
#munisys-global-footer .ft-col a { font-size: 0.83rem !important; color: rgba(255,255,255,0.45) !important; transition: color 0.18s !important; }
#munisys-global-footer .ft-col a:hover { color: #fff !important; }
#munisys-global-footer .ft-bottom {
  padding: 1.5rem 5rem !important; display: flex !important; justify-content: space-between !important;
  font-size: 0.75rem !important; color: rgba(255,255,255,0.25) !important; border-top: 1px solid rgba(255,255,255,0.06) !important;
}
@media(max-width:1024px) {
  #munisys-global-footer .ft-main { grid-template-columns: 1fr 1fr !important; }
}
</style>
`;

const footerHTML = `
<div id="munisys-global-footer">
  <div class="ft-inner">
    <div class="ft-top">
      <div class="ft-brand">${db.footer.brandName}</div>
      <a href="${db.footer.linkedinUrl}" target="_blank" rel="noopener noreferrer" class="ft-linkedin">${db.footer.linkedinLabel}</a>
    </div>
    <div class="ft-main">
      ${db.footer.columns.map(col => `
        <div class="ft-col">
          <h4>${col.title}</h4>
          <ul>
            ${col.links.map(link => `
              <li><a href="${link.href}">${link.label}</a></li>
            `).join('')}
          </ul>
        </div>
      `).join('')}
    </div>
    <div class="ft-bottom">
      <div>${db.footer.copyright}</div>
      <div>${db.footer.legal}</div>
    </div>
  </div>
</div>
`;
fs.writeFileSync('PAGE_FOOTER_MINIFIED.html', minify(footerCSS + footerHTML));

console.log('Header and Footer generated.');
