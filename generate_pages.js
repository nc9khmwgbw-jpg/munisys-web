const fs = require('fs');
const db = require('./src/data/db.json');

function minify(html) {
  // Remove comments
  html = html.replace(/<!--[\s\S]*?-->/g, '');
  // Minify CSS
  html = html.replace(/<style>([\s\S]*?)<\/style>/ig, (match, css) => {
    return '<style>' + css.replace(/\s+/g, ' ').replace(/\/\*[\s\S]*?\*\//g, '').trim() + '</style>';
  });
  // Minify HTML
  html = html.replace(/>\s+</g, '><').trim();
  return html;
}

const baseCSS = `
<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap');
#munisys-page-wrapper {
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
#munisys-page-wrapper *, #munisys-page-wrapper *::before, #munisys-page-wrapper *::after { 
  box-sizing: border-box; margin: 0; padding: 0; font-family: 'Montserrat', sans-serif !important;
}
#munisys-page-wrapper .page-hero {
  background: var(--blue-dark) !important;
  color: #fff !important;
  padding: 8rem 5rem 6rem 5rem !important;
  text-align: center !important;
  position: relative !important;
  overflow: hidden !important;
}
#munisys-page-wrapper .page-hero::before {
  content: ''; position: absolute !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important;
  background: radial-gradient(circle at right top, rgba(146, 43, 85, 0.2), transparent 50%) !important;
}
#munisys-page-wrapper .page-hero h1 { font-size: 3.5rem !important; font-weight: 800 !important; margin-bottom: 1.5rem !important; position: relative !important; z-index: 2 !important; }
#munisys-page-wrapper .page-hero p { font-size: 1.2rem !important; color: rgba(255,255,255,0.8) !important; max-width: 800px !important; margin: 0 auto !important; position: relative !important; z-index: 2 !important; line-height: 1.6 !important; }
#munisys-page-wrapper .page-content { max-width: 1200px !important; margin: 0 auto !important; padding: 5rem 2rem !important; }
#munisys-page-wrapper .section-title { font-size: 2.5rem !important; font-weight: 800 !important; color: var(--blue-dark) !important; margin-bottom: 1.5rem !important; text-align: center !important; }
#munisys-page-wrapper .section-desc { font-size: 1.1rem !important; color: var(--text-gray) !important; line-height: 1.8 !important; margin-bottom: 4rem !important; text-align: center !important; max-width: 800px !important; margin-left: auto !important; margin-right: auto !important; }
</style>
`;

// 1. SERVICES
let htmlServices = baseCSS + `
<style>
#munisys-page-wrapper .services-grid { display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 3rem !important; }
#munisys-page-wrapper .service-card { background: #f4f7f9 !important; padding: 3rem !important; border-radius: 8px !important; border-top: 4px solid var(--bordeaux) !important; }
#munisys-page-wrapper .service-card h3 { font-size: 1.5rem !important; font-weight: 800 !important; color: var(--blue-dark) !important; margin-bottom: 1.5rem !important; }
#munisys-page-wrapper .service-card p { font-size: 1.05rem !important; color: var(--text-gray) !important; line-height: 1.8 !important; }
@media(max-width: 768px) { #munisys-page-wrapper .services-grid { grid-template-columns: 1fr !important; } }
</style>
<div id="munisys-page-wrapper">
  <section class="page-hero">
    <h1>${db.services.heroTitle}</h1>
    <p>${db.services.heroDesc}</p>
  </section>
  <section class="page-content">
    <h2 class="section-title">${db.services.sections[0].title}</h2>
    <div class="services-grid">
      ${db.services.sections[0].items.map(i => `
        <div class="service-card">
          <h3>${i.title}</h3>
          <p>${i.desc}</p>
        </div>
      `).join('')}
    </div>
  </section>
</div>
`;
fs.writeFileSync('PAGE_SERVICES_MINIFIED.html', minify(htmlServices));

// 2. CUSTOMER REFERENCES
let htmlRef = baseCSS + `
<style>
#munisys-page-wrapper .ref-grid { display: grid !important; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important; gap: 2rem !important; }
#munisys-page-wrapper .ref-card { background: #fff !important; border: 1px solid #e2e8f0 !important; padding: 2rem !important; border-radius: 8px !important; text-align: center !important; font-weight: 700 !important; font-size: 1.1rem !important; color: var(--blue-dark) !important; box-shadow: 0 4px 6px rgba(0,0,0,0.02) !important; transition: transform 0.2s !important; }
#munisys-page-wrapper .ref-card:hover { transform: translateY(-5px) !important; box-shadow: 0 10px 15px rgba(0,0,0,0.05) !important; border-color: var(--blue-light) !important; }
</style>
<div id="munisys-page-wrapper">
  <section class="page-hero">
    <h1>${db.customerReferences.heroTitle}</h1>
    <p>${db.customerReferences.heroDesc}</p>
  </section>
  <section class="page-content">
    <h2 class="section-title">${db.customerReferences.title}</h2>
    <p class="section-desc">${db.customerReferences.desc}</p>
    <div class="ref-grid">
      ${db.customerReferences.customers.map(c => `
        <div class="ref-card">${c}</div>
      `).join('')}
    </div>
  </section>
</div>
`;
fs.writeFileSync('PAGE_REFERENCES_MINIFIED.html', minify(htmlRef));

// 3. TECHNOLOGY PARTNERS
let htmlPart = baseCSS + `
<style>
#munisys-page-wrapper .part-grid { display: flex !important; flex-wrap: wrap !important; gap: 1.5rem !important; justify-content: center !important; }
#munisys-page-wrapper .part-card { background: #f4f7f9 !important; padding: 1.5rem 2.5rem !important; border-radius: 50px !important; font-weight: 800 !important; font-size: 1.2rem !important; color: var(--bordeaux) !important; border: 2px solid transparent !important; transition: all 0.2s !important; }
#munisys-page-wrapper .part-card:hover { background: #fff !important; border-color: var(--bordeaux) !important; box-shadow: 0 10px 20px rgba(146, 43, 85, 0.1) !important; }
</style>
<div id="munisys-page-wrapper">
  <section class="page-hero">
    <h1>${db.technologyPartners.heroTitle}</h1>
    <p>${db.technologyPartners.heroDesc}</p>
  </section>
  <section class="page-content">
    <h2 class="section-title">${db.technologyPartners.title}</h2>
    <p class="section-desc">${db.technologyPartners.desc}</p>
    <div class="part-grid">
      ${db.technologyPartners.partners.map(p => `
        <div class="part-card">${p}</div>
      `).join('')}
    </div>
  </section>
</div>
`;
fs.writeFileSync('PAGE_PARTNERS_MINIFIED.html', minify(htmlPart));

// 4. CAREERS
let htmlCareers = baseCSS + `
<style>
#munisys-page-wrapper .job-list { display: flex !important; flex-direction: column !important; gap: 1.5rem !important; max-width: 800px !important; margin: 0 auto !important; }
#munisys-page-wrapper .job-card { display: flex !important; justify-content: space-between !important; align-items: center !important; background: #fff !important; border: 1px solid #e2e8f0 !important; padding: 2rem !important; border-radius: 8px !important; box-shadow: 0 2px 4px rgba(0,0,0,0.02) !important; transition: all 0.2s !important; }
#munisys-page-wrapper .job-card:hover { border-color: var(--bordeaux) !important; box-shadow: 0 10px 15px rgba(0,0,0,0.05) !important; }
#munisys-page-wrapper .job-info h3 { font-size: 1.4rem !important; font-weight: 700 !important; color: var(--blue-dark) !important; margin-bottom: 0.5rem !important; }
#munisys-page-wrapper .job-info p { font-size: 0.95rem !important; color: var(--text-gray) !important; font-weight: 500 !important; }
#munisys-page-wrapper .job-btn { background: var(--bordeaux) !important; color: #fff !important; padding: 0.8rem 1.5rem !important; border-radius: 4px !important; font-weight: 600 !important; text-decoration: none !important; transition: background 0.2s !important; }
#munisys-page-wrapper .job-btn:hover { background: #7a2245 !important; }
@media(max-width: 768px) { #munisys-page-wrapper .job-card { flex-direction: column !important; align-items: flex-start !important; gap: 1.5rem !important; } }
</style>
<div id="munisys-page-wrapper">
  <section class="page-hero">
    <h1>${db.careers.heroTitle}</h1>
    <p>${db.careers.heroDesc}</p>
  </section>
  <section class="page-content">
    <h2 class="section-title">${db.careers.title}</h2>
    <p class="section-desc">${db.careers.desc}</p>
    <div class="job-list">
      ${db.careers.jobs.map(j => `
        <div class="job-card">
          <div class="job-info">
            <h3>${j.title}</h3>
            <p>${j.loc}</p>
          </div>
          <a href="mailto:rh@munisys.ma?subject=Application for ${j.title}" class="job-btn">Apply Now</a>
        </div>
      `).join('')}
    </div>
  </section>
</div>
`;
fs.writeFileSync('PAGE_CAREERS_MINIFIED.html', minify(htmlCareers));

console.log('All pages generated and minified.');
