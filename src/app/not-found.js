import Link from 'next/link';
import './globals.css';

export const metadata = {
  title: '404 — Page introuvable | Munisys',
  description: 'La page que vous recherchez est introuvable.',
};

export default function NotFound() {
  return (
    <div style={{
      minHeight: '100vh',
      background: 'linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%)',
      display: 'flex',
      flexDirection: 'column',
      alignItems: 'center',
      justifyContent: 'center',
      fontFamily: "'Outfit', 'Montserrat', sans-serif",
      padding: '2rem',
      position: 'relative',
      overflow: 'hidden',
    }}>

      {/* Ambient orbs */}
      <div style={{
        position: 'absolute', top: '20%', left: '10%',
        width: '400px', height: '400px',
        borderRadius: '50%',
        background: 'radial-gradient(circle, rgba(146,43,85,0.15) 0%, transparent 70%)',
        filter: 'blur(60px)',
        pointerEvents: 'none',
      }} />
      <div style={{
        position: 'absolute', bottom: '20%', right: '10%',
        width: '500px', height: '500px',
        borderRadius: '50%',
        background: 'radial-gradient(circle, rgba(29,78,121,0.2) 0%, transparent 70%)',
        filter: 'blur(80px)',
        pointerEvents: 'none',
      }} />

      {/* Logo */}
      <Link href="/" style={{ marginBottom: '4rem', display: 'block' }}>
        <svg width="45" height="45" viewBox="0 0 100 100" fill="none">
          <polygon points="10,20 40,50 10,80" fill="#83a5c2" />
          <polygon points="10,20 40,50 40,20" fill="#1d4e79" />
          <polygon points="40,50 70,20 70,80" fill="#922b55" />
          <polygon points="70,20 100,20 70,50" fill="#1d4e79" />
        </svg>
      </Link>

      {/* 404 Number */}
      <div style={{
        fontSize: 'clamp(8rem, 20vw, 14rem)',
        fontWeight: 900,
        lineHeight: 1,
        background: 'linear-gradient(135deg, #922b55, #1d4e79)',
        WebkitBackgroundClip: 'text',
        WebkitTextFillColor: 'transparent',
        backgroundClip: 'text',
        letterSpacing: '-5px',
        marginBottom: '1.5rem',
        userSelect: 'none',
      }}>
        404
      </div>

      {/* Title */}
      <h1 style={{
        fontSize: 'clamp(1.5rem, 3vw, 2.5rem)',
        fontWeight: 300,
        color: '#f8fafc',
        margin: '0 0 1rem 0',
        textAlign: 'center',
        letterSpacing: '-0.02em',
      }}>
        Page introuvable
      </h1>

      {/* Subtitle */}
      <p style={{
        fontSize: '1.05rem',
        color: '#94a3b8',
        margin: '0 0 3rem 0',
        textAlign: 'center',
        maxWidth: '480px',
        lineHeight: 1.6,
      }}>
        La page que vous recherchez a peut-être été déplacée, supprimée ou n'a jamais existé.
      </p>

      {/* Buttons */}
      <div style={{ display: 'flex', gap: '1rem', flexWrap: 'wrap', justifyContent: 'center' }}>
        <Link href="/" style={{
          display: 'inline-flex',
          alignItems: 'center',
          gap: '0.75rem',
          padding: '1rem 2.5rem',
          background: 'linear-gradient(135deg, #922b55, #1d4e79)',
          color: '#fff',
          borderRadius: '50px',
          fontWeight: 700,
          fontSize: '0.95rem',
          textDecoration: 'none',
          letterSpacing: '0.05em',
          textTransform: 'uppercase',
          transition: 'opacity 0.2s ease',
        }}>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
            <path d="M19 12H5M12 19l-7-7 7-7" />
          </svg>
          Retour à l'accueil
        </Link>
        <Link href="/contact" style={{
          display: 'inline-flex',
          alignItems: 'center',
          gap: '0.75rem',
          padding: '1rem 2.5rem',
          background: 'transparent',
          color: '#94a3b8',
          borderRadius: '50px',
          fontWeight: 600,
          fontSize: '0.95rem',
          textDecoration: 'none',
          letterSpacing: '0.05em',
          textTransform: 'uppercase',
          border: '1px solid rgba(148,163,184,0.25)',
          transition: 'all 0.2s ease',
        }}>
          Nous contacter
        </Link>
      </div>

      {/* Quick nav */}
      <div style={{
        marginTop: '4rem',
        display: 'flex',
        gap: '2rem',
        flexWrap: 'wrap',
        justifyContent: 'center',
      }}>
        {[
          { label: 'Solutions', href: '/solutions' },
          { label: 'Services', href: '/services' },
          { label: 'Insights', href: '/insights' },
          { label: 'About Us', href: '/about-us' },
        ].map(({ label, href }) => (
          <Link key={href} href={href} style={{
            fontSize: '0.9rem',
            color: 'rgba(148,163,184,0.7)',
            textDecoration: 'none',
            borderBottom: '1px solid rgba(148,163,184,0.2)',
            paddingBottom: '2px',
            transition: 'color 0.2s',
          }}>
            {label}
          </Link>
        ))}
      </div>
    </div>
  );
}
