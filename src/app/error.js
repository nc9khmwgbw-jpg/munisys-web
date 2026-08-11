'use client';
import { useEffect } from 'react';
import Link from 'next/link';

export default function GlobalError({ error, reset }) {
  useEffect(() => {
    console.error('[Munisys Error]', error);
  }, [error]);

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

      {/* Ambient orb */}
      <div style={{
        position: 'absolute', top: '20%', right: '15%',
        width: '450px', height: '450px',
        borderRadius: '50%',
        background: 'radial-gradient(circle, rgba(146,43,85,0.12) 0%, transparent 70%)',
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

      {/* 500 Number */}
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
        500
      </div>

      <h1 style={{
        fontSize: 'clamp(1.5rem, 3vw, 2.5rem)',
        fontWeight: 300,
        color: '#f8fafc',
        margin: '0 0 1rem 0',
        textAlign: 'center',
        letterSpacing: '-0.02em',
      }}>
        Une erreur s'est produite
      </h1>

      <p style={{
        fontSize: '1.05rem',
        color: '#94a3b8',
        margin: '0 0 3rem 0',
        textAlign: 'center',
        maxWidth: '480px',
        lineHeight: 1.6,
      }}>
        Quelque chose s'est mal passé. Nos équipes ont été notifiées. Vous pouvez réessayer ou revenir à l'accueil.
      </p>

      <div style={{ display: 'flex', gap: '1rem', flexWrap: 'wrap', justifyContent: 'center' }}>
        <button
          onClick={() => reset()}
          style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '0.75rem',
            padding: '1rem 2.5rem',
            background: 'linear-gradient(135deg, #922b55, #1d4e79)',
            color: '#fff',
            borderRadius: '50px',
            fontWeight: 700,
            fontSize: '0.95rem',
            cursor: 'pointer',
            border: 'none',
            letterSpacing: '0.05em',
            textTransform: 'uppercase',
          }}
        >
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
            <path d="M1 4v6h6M23 20v-6h-6" />
            <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15" />
          </svg>
          Réessayer
        </button>
        <Link href="/" style={{
          display: 'inline-flex',
          alignItems: 'center',
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
        }}>
          Retour à l'accueil
        </Link>
      </div>
    </div>
  );
}
