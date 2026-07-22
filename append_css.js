const fs = require('fs');
const css = `
/* ── PAGE CAREERS STYLES (ARCHITECTURAL PREMIUM) ── */
.m-car-page { background: #fff; color: #333; overflow-x: hidden; }
.m-car-hero { display: flex; min-height: 80vh; background: #fff; }
.m-car-hero-left { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 6rem 5% 6rem 10%; background: #f8f9fa; position: relative; }
.m-car-hero-left::after { content: ''; position: absolute; right: -50px; top: 0; width: 100px; height: 100%; background: #f8f9fa; transform: skewX(-10deg); z-index: 2; }
.m-car-hero-left h1 { font-size: 5rem; font-weight: 200; color: #1d4e79; margin-bottom: 2rem; line-height: 1.1; letter-spacing: -1px; }
.m-car-hero-left p { font-size: 1.4rem; color: #555; max-width: 500px; line-height: 1.8; font-weight: 300; }
.m-car-hero-right { flex: 1; background: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2000&auto=format&fit=crop') center/cover; position: relative; }
.m-car-why { padding: 8rem 5%; max-width: 1400px; margin: 0 auto; }
.m-car-why-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 5rem; }
.m-car-why-header h2 { font-size: 4rem; color: #1d4e79; font-weight: 200; margin: 0; letter-spacing: -1px; }
.m-car-why-header p { font-size: 1.2rem; color: #666; max-width: 400px; margin: 0; text-align: right; }
.m-car-why-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 3rem; }
.m-car-why-card { background: #fcfcfc; padding: 4rem 3rem; border: 1px solid #eaeaea; transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1); position: relative; overflow: hidden; border-radius: 4px; }
.m-car-why-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 2px solid #922b55; opacity: 0; transform: scale(0.95); transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1); pointer-events: none; border-radius: 4px; }
.m-car-why-card:hover { background: #fff; box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08); transform: translateY(-10px); }
.m-car-why-card:hover::before { opacity: 1; transform: scale(1); }
.m-car-why-card h3 { font-size: 2.2rem; color: #1d4e79; margin-bottom: 1.5rem; font-weight: 300; transition: color 0.3s; }
.m-car-why-card p { font-size: 1.15rem; color: #555; line-height: 1.8; }
.m-car-jobs { background: #1d4e79; padding: 8rem 5%; color: #fff; }
.m-jobs-container { max-width: 1200px; margin: 0 auto; }
.m-jobs-header h2 { font-size: 4rem; font-weight: 200; color: #fff; margin-bottom: 4rem; letter-spacing: -1px; }
.m-job-line { display: flex; align-items: center; justify-content: space-between; padding: 3rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.1); transition: all 0.4s ease; position: relative; cursor: pointer; }
.m-job-line::after { content: ''; position: absolute; left: -5%; width: 110%; height: 100%; background: rgba(255, 255, 255, 0.03); opacity: 0; transition: opacity 0.4s ease; z-index: 1; pointer-events: none; }
.m-job-line:hover::after { opacity: 1; }
.m-job-title { flex: 2; z-index: 2; transition: transform 0.4s ease; }
.m-job-title h3 { font-size: 2.5rem; font-weight: 300; margin: 0 0 0.5rem 0; color: #fff; }
.m-job-meta { flex: 1; font-size: 1.1rem; color: rgba(255, 255, 255, 0.6); font-weight: 500; text-transform: uppercase; letter-spacing: 1px; z-index: 2; }
.m-job-action { flex: 1; text-align: right; z-index: 2; overflow: hidden; display: flex; justify-content: flex-end; }
.m-btn-apply { display: inline-flex; align-items: center; justify-content: center; width: 60px; height: 60px; border-radius: 50%; background: #922b55; color: #fff; text-decoration: none; transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1); transform: translateX(50px); opacity: 0; }
.m-btn-apply svg { width: 24px; height: 24px; transition: transform 0.4s; }
.m-job-line:hover .m-job-title { transform: translateX(20px); }
.m-job-line:hover .m-btn-apply { transform: translateX(0); opacity: 1; }
.m-btn-apply:hover { transform: scale(1.1) !important; box-shadow: 0 10px 20px rgba(146, 43, 85, 0.4); }
.m-btn-apply:hover svg { transform: rotate(-45deg); }
@media(max-width: 1024px) { .m-car-hero { flex-direction: column; } .m-car-hero-left { padding: 6rem 5%; } .m-car-hero-left::after { display: none; } .m-car-hero-right { min-height: 400px; } .m-car-why-header { flex-direction: column; align-items: flex-start; gap: 2rem; } .m-car-why-header p { text-align: left; } .m-car-why-grid { grid-template-columns: 1fr 1fr; } }
@media(max-width: 768px) { .m-car-hero-left h1 { font-size: 3.5rem; } .m-car-why-grid { grid-template-columns: 1fr; } .m-job-line { flex-direction: column; align-items: flex-start; gap: 1.5rem; } .m-job-title h3 { font-size: 2rem; } .m-btn-apply { transform: translateX(0); opacity: 1; border-radius: 5px; width: auto; height: auto; padding: 1rem 2rem; } .m-btn-apply span { display: inline; margin-right: 10px; } .m-job-line:hover .m-job-title { transform: none; } }
`;
fs.appendFileSync('src/app/globals.css', css);
