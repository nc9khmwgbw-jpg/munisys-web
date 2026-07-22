"use client";

export default function HeroBackground() {
  return (
    <>
      <div className="hero-mesh-bg"></div>
      <style dangerouslySetInnerHTML={{__html: `
        .hero-mesh-bg {
          position: absolute;
          inset: 0;
          z-index: 0;
          overflow: hidden;
          background-color: var(--bordeaux, #922b55);
          opacity: 0.8;
          pointer-events: none;
        }
        
        .hero-mesh-bg::before,
        .hero-mesh-bg::after {
          content: "";
          position: absolute;
          width: 200%;
          height: 200%;
          top: -50%;
          left: -50%;
          z-index: 0;
          background-image: 
            radial-gradient(circle at 50% 50%, var(--blue-dark, #1d4e79) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, var(--blue-light, #83a5c2) 0%, transparent 40%);
          background-blend-mode: screen;
          animation: meshFlow 20s ease-in-out infinite alternate;
          opacity: 0.6;
        }

        .hero-mesh-bg::after {
          background-image: 
            radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
          animation: meshFlow2 25s ease-in-out infinite alternate-reverse;
        }

        @keyframes meshFlow {
          0% { transform: translate(0, 0) scale(1) rotate(0deg); }
          50% { transform: translate(10%, 10%) scale(1.1) rotate(5deg); }
          100% { transform: translate(-5%, 15%) scale(0.9) rotate(-5deg); }
        }
        
        @keyframes meshFlow2 {
          0% { transform: translate(0, 0) scale(1) rotate(0deg); }
          50% { transform: translate(-10%, -10%) scale(1.2) rotate(-5deg); }
          100% { transform: translate(15%, -5%) scale(0.95) rotate(5deg); }
        }

        [data-theme="dark"] .hero-mesh-bg {
          background-color: #0f172a;
          opacity: 1;
        }
        [data-theme="dark"] .hero-mesh-bg::before {
          background-image: 
            radial-gradient(circle at 50% 50%, var(--bordeaux) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, var(--blue-dark) 0%, transparent 40%);
          opacity: 0.3;
        }
      `}} />
    </>
  );
}
