import fs from 'fs/promises';
import path from 'path';
import "./globals.css";
import { CMSProvider } from '@/components/CMSProvider';
import ClientLayout from '@/components/ClientLayout';

export const dynamic = 'force-dynamic';

export const metadata = {
  title: "Munisys — Enterprise IT Architecture & Integration",
  description: "We orchestrate digital infrastructure technologies for a unique customer experience.",
};

const CSS = `
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap');

:root {
  --bordeaux: #922b55;
  --blue-dark: #1d4e79;
  --blue-light: #83a5c2;
  --text-dark: #333333;
  --text-gray: #666666;
  --bg-gray: #fcfcfc;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body {
  font-family: 'Montserrat', sans-serif;
  background: #ffffff;
  color: var(--text-dark);
  -webkit-font-smoothing: antialiased;
  overflow-x: hidden;
}
a { text-decoration: none; color: inherit; }
ul { list-style: none; }
button { font-family: inherit; cursor: pointer; border: none; background: none; }
img { display: block; max-width: 100%; }

/* ── NAVBAR ── */
.nav {
  position: sticky; top: 0; z-index: 1000;
  background: #ffffff; height: 90px;
  display: flex; align-items: stretch;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.nav-inner {
  display: flex; align-items: stretch; width: 100%; max-width: 1440px; margin: 0 auto;
}
.nav-logo {
  display: flex; align-items: center; gap: 12px;
  padding: 0 2rem; flex-shrink: 0;
}
.nav-logo-mark {
  font-size: 1.6rem; font-weight: 800; color: var(--blue-dark); letter-spacing: 0px;
}
.nav-links {
  display: flex; align-items: center; flex: 1; padding: 0 1rem;
}
.nav-item {
  position: relative; height: 100%; display: flex; align-items: center;
}
.nav-link {
  font-size: 0.85rem; font-weight: 600; color: #111;
  padding: 0 1rem; height: 100%; display: flex; align-items: center; gap: 4px;
  transition: color 0.2s;
}
.nav-link:hover, .nav-item:hover .nav-link {
  color: var(--blue-dark);
}

/* Mega Menu */
.mega-menu {
  position: absolute; top: 90px; left: -100px;
  background: #fff; width: 1100px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  border-radius: 4px; border-top: 2px solid var(--bg-gray);
  display: none; opacity: 0; transform: translateY(10px);
  transition: opacity 0.3s, transform 0.3s;
  padding: 2.5rem; grid-template-columns: repeat(5, 1fr); gap: 2rem;
  pointer-events: none;
}
.nav-item:hover .mega-menu {
  display: grid; opacity: 1; transform: translateY(0); pointer-events: auto;
}
.mega-col h3 {
  font-size: 1.05rem; font-weight: 700; color: var(--blue-dark);
  margin-bottom: 1.5rem; line-height: 1.4;
}
.mega-col ul { display: flex; flex-direction: column; gap: 1rem; }
.mega-col a {
  font-size: 0.8rem; color: var(--text-gray); font-weight: 500;
  line-height: 1.4; transition: color 0.2s;
}
.mega-col a:hover { color: var(--bordeaux); }

.nav-right {
  display: flex; align-items: stretch; margin-left: auto;
}
.nav-search {
  padding: 0 1.5rem; color: var(--blue-dark); display: flex; align-items: center;
}
.nav-cta {
  display: flex; align-items: center; gap: 0.75rem;
  background: var(--bordeaux); color: #fff;
  font-size: 0.9rem; font-weight: 500;
  padding: 0 2.5rem; height: 100%;
  transition: background 0.2s;
}
.nav-cta:hover { background: #7a2245; }

/* ── INNER PAGE HERO ── */
.inner-hero {
  background: var(--blue-dark); color: #fff; padding: 6rem 5rem;
  position: relative; overflow: hidden; max-width: 1440px; margin: 0 auto;
}
.inner-hero::before {
  content: ''; position: absolute; top: 0; right: 0; width: 30%; height: 100%;
  background: var(--bordeaux); clip-path: polygon(100% 0, 100% 100%, 0 0); z-index: 1;
}
.inner-hero-content {
  position: relative; z-index: 2; max-width: 800px;
}
.inner-hero-title {
  font-size: 3.5rem; font-weight: 300; margin-bottom: 1.5rem;
}
.inner-hero-desc {
  font-size: 1.1rem; line-height: 1.8; color: rgba(255,255,255,0.8);
}
.inner-content-sec {
  padding: 5rem; max-width: 1440px; margin: 0 auto;
  min-height: 50vh; display: flex; flex-direction: column; gap: 2rem;
}

/* ── HERO (HOMEPAGE) ── */
.hero {
  position: relative; height: calc(100vh - 90px); min-height: 600px;
  background: #ffffff; overflow: hidden;
  max-width: 1440px; margin: 0 auto;
}
.hero-slide {
  position: absolute; inset: 0; display: flex;
  opacity: 0; transition: opacity 0.6s ease; pointer-events: none;
}
.hero-slide.active {
  opacity: 1; pointer-events: auto; z-index: 5;
}
.hero-text {
  position: relative; z-index: 10;
  display: flex; flex-direction: column; justify-content: center;
  padding: 0 0 0 5rem; width: 55%;
}
.hero-h1 {
  font-size: clamp(2.5rem, 3.8vw, 3.8rem);
  font-weight: 300; color: var(--text-dark);
  line-height: 1.25; margin-bottom: 3.5rem;
}
.hero-h1 strong { font-weight: 700; color: var(--text-dark); }
.hero-cta-btn {
  display: inline-flex; align-items: center; justify-content: center;
  border: 2px solid var(--bordeaux); color: var(--bordeaux);
  font-size: 1rem; font-weight: 700;
  padding: 1.2rem 2.5rem; width: fit-content;
  transition: all 0.2s;
}
.hero-cta-btn:hover { background: var(--bordeaux); color: #fff; }

.hero-visual {
  position: absolute; right: 0; top: 0; bottom: 0; width: 55%;
  display: flex; align-items: center; justify-content: center;
}
.hero-logo-svg { width: 100%; height: 100%; position: absolute; right: -5%; top: 10%; }
.hero-img-wrap { position: absolute; right: 0; top: 0; bottom: 0; width: 100%; }
.hero-img-clip {
  position: absolute; right: 0; top: 0; bottom: 0; width: 85%;
  clip-path: polygon(25% 0, 100% 0, 100% 100%, 0 100%);
}
.hero-img-clip img { width: 100%; height: 100%; object-fit: cover; }
.hero-tri-blue {
  position: absolute; bottom: 0; left: 5%; width: 30%; height: 50%;
  background: var(--blue-dark); clip-path: polygon(0 100%, 100% 0, 100% 100%); z-index: 2;
}
.hero-tri-bordeaux {
  position: absolute; top: 0; right: 10%; width: 25%; height: 40%;
  background: var(--bordeaux); clip-path: polygon(0 0, 100% 0, 0 100%); z-index: 2;
}

.hero-indicators {
  position: absolute; bottom: 3rem; left: 5rem;
  display: flex; gap: 1rem; z-index: 20; align-items: center;
}
.hero-ind { height: 4px; background: #e0e0e0; cursor: pointer; transition: all 0.3s; }
.hero-ind.active { width: 60px; background: var(--blue-dark); }
.hero-ind.inactive { width: 30px; }


/* ── 1. SOLUTIONS SECTION (Dark Blue) ── */
.sol-sec { 
  background: var(--blue-dark); color: #fff; padding: 5rem; position: relative;
  max-width: 1440px; margin: 0 auto; overflow: hidden;
}
.sol-sec::before {
  content: ''; position: absolute; top: 0; right: 0; width: 15%; height: 25%;
  background: var(--bordeaux); clip-path: polygon(100% 0, 100% 100%, 0 0);
}
.sol-grid { 
  display: grid; grid-template-columns: 350px 350px 1fr; gap: 4rem; align-items: center; position: relative; z-index: 2;
}
.sol-title { font-size: 2.2rem; font-weight: 300; margin-bottom: 3rem; }
.sol-menu { display: flex; flex-direction: column; }
.sol-menu-item { 
  padding: 1.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.2);
  font-size: 1.2rem; cursor: pointer; transition: font-weight 0.2s; color: rgba(255,255,255,0.8);
}
.sol-menu-item.active { font-weight: 700; color: #fff; border-bottom: 1px solid rgba(255,255,255,0.4); }
.sol-img-container { width: 100%; aspect-ratio: 3/4; overflow: hidden; }
.sol-img-container img { width: 100%; height: 100%; object-fit: cover; }
.sol-content h3 { font-size: 1.8rem; margin-bottom: 1.5rem; font-weight: 700; }
.sol-content p { font-size: 0.95rem; line-height: 1.8; color: rgba(255,255,255,0.8); margin-bottom: 2rem; }
.sol-link { font-weight: 800; display: inline-flex; align-items: center; gap: 0.5rem; color: #fff; font-size: 0.9rem; }


/* ── 2. OFFERINGS SECTION ── */
.off-sec { padding: 6rem 5rem; max-width: 1440px; margin: 0 auto; }
.off-header { display: flex; gap: 2rem; align-items: flex-start; margin-bottom: 4rem; }
.off-title { font-size: 3.5rem; font-weight: 300; color: var(--bordeaux); }
.off-desc { font-size: 0.95rem; color: var(--text-gray); max-width: 500px; padding-top: 1rem; line-height: 1.6;}
.off-flow { 
  display: grid; grid-template-columns: auto 1fr auto 1fr; align-items: center; gap: 3rem;
  border: 1px solid var(--bordeaux); padding: 3rem 4rem; position: relative;
}
.off-label-vert { 
  writing-mode: vertical-lr; transform: rotate(180deg); 
  font-size: 3rem; font-weight: 800; 
}
.off-label-through { color: var(--bordeaux); }
.off-label-achieve { color: var(--blue-dark); }
.off-col { display: flex; flex-direction: column; gap: 2rem; }
.off-item { display: flex; align-items: center; gap: 1rem; font-weight: 700; font-size: 1.05rem; color: var(--text-dark); }
.off-check { color: var(--blue-light); display: flex; align-items: center; justify-content: center; }


/* ── 3. SERVICES SECTION (Light Blue) ── */
.srv-sec { background: #eff3f7; padding: 6rem 5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 6rem; max-width: 1440px; margin: 0 auto; }
.srv-left h2 { font-size: 2.8rem; color: var(--blue-dark); font-weight: 300; margin-bottom: 1.5rem; }
.srv-left p { color: var(--text-gray); line-height: 1.9; font-size: 1rem; }
.srv-right { display: flex; flex-direction: column; }
.srv-acc { border-bottom: 1px solid #d4dfe8; padding: 2.5rem 0; }
.srv-acc:first-child { border-top: none; padding-top: 0; }
.srv-acc-head { display: flex; align-items: center; justify-content: space-between; cursor: pointer; }
.srv-acc-title { display: flex; align-items: center; gap: 1.5rem; font-size: 1.5rem; font-weight: 700; color: var(--blue-dark); }
.srv-icon { width: 45px; height: 45px; color: var(--bordeaux); }
.srv-acc-body { padding-top: 1.5rem; padding-left: calc(45px + 1.5rem); display: none; }
.srv-acc.open .srv-acc-body { display: block; }
.srv-acc-body p { color: var(--text-gray); font-size: 0.95rem; line-height: 1.8; margin-bottom: 1.5rem; }
.srv-readmore { font-size: 0.75rem; font-weight: 800; color: var(--blue-dark); text-transform: uppercase; text-decoration: underline; letter-spacing: 1px; }


/* ── 4. INSIGHTS SECTION ── */
.ins-sec { padding: 6rem 5rem; display: flex; gap: 4rem; max-width: 1440px; margin: 0 auto; }
.ins-title { 
  writing-mode: vertical-lr; transform: rotate(180deg); 
  font-size: 4.5rem; font-weight: 800; color: var(--blue-dark); 
}
.ins-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 3rem; flex: 1; }
.ins-card { display: flex; flex-direction: column; gap: 1.5rem; cursor: pointer; }
.ins-img-wrap { 
  aspect-ratio: 1; border: 1px solid #e2e8f0; 
  clip-path: polygon(15% 0, 100% 0, 100% 100%, 0 100%, 0 15%);
  display: flex; align-items: center; justify-content: center; overflow: hidden;
  background: #fff; padding: 2rem;
}
.ins-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
.ins-img-wrap.contain img { object-fit: contain; }
.ins-text { font-weight: 700; color: var(--text-dark); font-size: 1.15rem; line-height: 1.4; }


/* ── 5. CUSTOMER REFERENCES ── */
.ref-sec { padding: 6rem 5rem; display: flex; flex-direction: column; gap: 4rem; max-width: 1440px; margin: 0 auto; }
.ref-top { display: grid; grid-template-columns: 1fr 2fr; gap: 4rem; }
.ref-title { font-size: 2.8rem; font-weight: 300; color: var(--blue-dark); }
.ref-desc { color: var(--text-gray); line-height: 1.9; font-size: 0.95rem; }
.ref-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 4rem; align-items: center; justify-items: center; }
.ref-logo { max-width: 130px; filter: grayscale(100%); opacity: 0.5; transition: all 0.3s; }
.ref-logo:hover { filter: grayscale(0%); opacity: 1; }
.ref-seeall { font-weight: 800; color: var(--bordeaux); text-decoration: underline; cursor: pointer; font-size: 1.1rem; }


/* ── FOOTER ── */
.ft { background: #1a1a1a; color: rgba(255,255,255,0.6); max-width: 1440px; margin: 0 auto; }
.ft-top {
  display: flex; justify-content: space-between; align-items: center;
  padding: 2.5rem 5rem; border-bottom: 1px solid rgba(255,255,255,0.08);
}
.ft-brand { font-size: 1.4rem; font-weight: 900; color: #fff; letter-spacing: 1px; }
.ft-linkedin {
  border: 1px solid rgba(255,255,255,0.3); color: rgba(255,255,255,0.7);
  font-size: 0.82rem; font-weight: 600; padding: 0.5rem 1.2rem; border-radius: 3px;
  transition: color 0.18s, border-color 0.18s;
}
.ft-linkedin:hover { color: #fff; border-color: #fff; }
.ft-main { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; padding: 3rem 5rem; }
.ft-col h4 {
  color: #fff; font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 2px; margin-bottom: 1rem;
}
.ft-col ul { display: flex; flex-direction: column; gap: 0.6rem; }
.ft-col a { font-size: 0.83rem; color: rgba(255,255,255,0.45); transition: color 0.18s; }
.ft-col a:hover { color: #fff; }
.ft-bottom {
  padding: 1.5rem 5rem; display: flex; justify-content: space-between;
  font-size: 0.75rem; color: rgba(255,255,255,0.25); border-top: 1px solid rgba(255,255,255,0.06);
}

.admin-fab {
  position: fixed; bottom: 2rem; right: 2rem;
  background: var(--blue-dark); color: #fff;
  font-size: 0.9rem; font-weight: 600; padding: 0.8rem 1.5rem;
  border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  z-index: 9999; display: flex; align-items: center; gap: 0.5rem;
}
.admin-fab:hover { background: var(--bordeaux); }

@media(max-width:1024px) {
  .nav-links { display: none; }
  .hero-text { width: 100%; padding: 3rem; }
  .hero-visual { display: none; }
  .hero-h1 { font-size: 2.5rem; }
  .sol-grid { grid-template-columns: 1fr; }
  .off-flow { grid-template-columns: 1fr; }
  .off-label-vert { writing-mode: horizontal-tb; transform: none; }
  .srv-sec { grid-template-columns: 1fr; }
  .ins-sec { flex-direction: column; }
  .ins-title { writing-mode: horizontal-tb; transform: none; }
  .ins-grid { grid-template-columns: 1fr; }
  .ref-top { grid-template-columns: 1fr; }
  .ref-grid { grid-template-columns: repeat(2, 1fr); }
  .ft-main { grid-template-columns: 1fr 1fr; }
}
`;

export default async function RootLayout({ children }) {
  let theme = { bordeaux: '#922b55', blueDark: '#1d4e79', blueLight: '#83a5c2', bgGray: '#f8fafc', textGray: '#475569' };
  try {
    const dbPath = path.join(process.cwd(), 'src/data/db.json');
    const dbContent = await fs.readFile(dbPath, 'utf8');
    const db = JSON.parse(dbContent);
    if (db.theme) theme = db.theme;
  } catch (e) {}

  const dynamicCSS = `
    :root {
      --bordeaux: ${theme.bordeaux};
      --blue-dark: ${theme.blueDark};
      --blue-light: ${theme.blueLight};
      --bg-gray: ${theme.bgGray};
      --text-gray: ${theme.textGray};
    }
  `;

  return (
    <html lang="fr">
      <head>
        <style dangerouslySetInnerHTML={{ __html: CSS }} />
        <style dangerouslySetInnerHTML={{ __html: dynamicCSS }} />
      </head>
      <body>
        <CMSProvider>
          <ClientLayout>
            {children}
          </ClientLayout>
        </CMSProvider>
      </body>
    </html>
  );
}
