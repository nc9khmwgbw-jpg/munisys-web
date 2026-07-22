"use client";

import React, { useState, useEffect } from 'react';

export default function CookieBanner() {
  const [showBanner, setShowBanner] = useState(false);

  useEffect(() => {
    // Vérifie si l'utilisateur a déjà fait un choix
    const cookieConsent = localStorage.getItem('munisys_cookie_consent');
    if (!cookieConsent) {
      setShowBanner(true);
    }
  }, []);

  const acceptCookies = () => {
    localStorage.setItem('munisys_cookie_consent', 'accepted');
    setShowBanner(false);
    // Ici vous pourrez initialiser Google Analytics plus tard
  };

  const declineCookies = () => {
    localStorage.setItem('munisys_cookie_consent', 'declined');
    setShowBanner(false);
  };

  if (!showBanner) return null;

  return (
    <div style={{
      position: 'fixed',
      bottom: '24px',
      left: '50%',
      transform: 'translateX(-50%)',
      width: 'calc(100% - 48px)',
      maxWidth: '900px',
      background: 'rgba(15, 23, 42, 0.75)',
      backdropFilter: 'blur(20px)',
      WebkitBackdropFilter: 'blur(20px)',
      border: '1px solid rgba(255, 255, 255, 0.08)',
      borderRadius: '12px',
      padding: '16px 24px',
      display: 'flex',
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'space-between',
      gap: '24px',
      zIndex: 999999,
      boxShadow: '0 20px 40px rgba(0,0,0,0.4), 0 1px 3px rgba(255,255,255,0.05) inset',
      color: '#fff',
      fontFamily: 'var(--font-primary, sans-serif)',
      animation: 'slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1)'
    }}>
      <style>{`
        @keyframes slideUp {
          from { opacity: 0; transform: translate(-50%, 40px); }
          to { opacity: 1; transform: translate(-50%, 0); }
        }
        @media (max-width: 768px) {
          .cookie-banner-container {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 16px !important;
          }
          .cookie-banner-buttons {
            width: 100%;
            justify-content: flex-end;
          }
        }
      `}</style>
      
      <div style={{ display: 'flex', alignItems: 'center', gap: '16px', flex: 1 }}>
        <div style={{ 
          width: '40px', 
          height: '40px', 
          borderRadius: '50%', 
          background: 'rgba(56, 189, 248, 0.1)', 
          display: 'flex', 
          alignItems: 'center', 
          justifyContent: 'center',
          flexShrink: 0
        }}>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
          </svg>
        </div>
        <div>
          <h4 style={{ margin: '0 0 4px 0', fontSize: '0.95rem', fontWeight: 600, color: '#f8fafc' }}>Respect de votre vie privée</h4>
          <p style={{ margin: 0, fontSize: '0.8rem', color: '#cbd5e1', lineHeight: '1.5' }}>
            Nous utilisons des cookies pour optimiser les performances de notre site et analyser notre trafic. 
            <a href="/politique-confidentialite" style={{ color: '#38bdf8', textDecoration: 'none', marginLeft: '6px', fontWeight: 500 }}>En savoir plus →</a>
          </p>
        </div>
      </div>
      
      <div className="cookie-banner-buttons" style={{ display: 'flex', gap: '12px', flexShrink: 0 }}>
        <button 
          onClick={declineCookies}
          style={{
            background: 'transparent',
            border: '1px solid rgba(255, 255, 255, 0.15)',
            color: '#cbd5e1',
            padding: '8px 20px',
            borderRadius: '6px',
            cursor: 'pointer',
            fontSize: '0.85rem',
            fontWeight: 500,
            transition: 'all 0.2s',
            whiteSpace: 'nowrap'
          }}
          onMouseOver={e => { e.target.style.background = 'rgba(255,255,255,0.05)'; e.target.style.color = '#fff'; }}
          onMouseOut={e => { e.target.style.background = 'transparent'; e.target.style.color = '#cbd5e1'; }}
        >
          Refuser
        </button>
        <button 
          onClick={acceptCookies}
          style={{
            background: '#38bdf8',
            border: 'none',
            color: '#0f172a',
            padding: '8px 20px',
            borderRadius: '6px',
            cursor: 'pointer',
            fontSize: '0.85rem',
            fontWeight: 600,
            transition: 'all 0.2s',
            whiteSpace: 'nowrap',
            boxShadow: '0 2px 10px rgba(56, 189, 248, 0.3)'
          }}
          onMouseOver={e => { e.target.style.background = '#0ea5e9'; e.target.style.transform = 'translateY(-1px)'; }}
          onMouseOut={e => { e.target.style.background = '#38bdf8'; e.target.style.transform = 'none'; }}
        >
          Accepter
        </button>
      </div>
    </div>
  );
}
