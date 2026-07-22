"use client";

import { useState, useEffect } from 'react';
import Link from 'next/link';
import { useCMS } from './CMSProvider';
import InlineEditor from './InlineEditor';
import Magnetic from './Magnetic';
import navDump from '../../nav_dump.json';
import SearchOverlay from './SearchOverlay';

export default function EditableNavigation() {
  const { dbData, lang, setLang, fullDb } = useCMS();
  const nav = dbData?.navigation || {};

  const [menuOpen, setMenuOpen] = useState(false);
  const [langMenuOpen, setLangMenuOpen] = useState(false);
  const [accordionOpen, setAccordionOpen] = useState(false);
  const [searchOpen, setSearchOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [darkMode, setDarkMode] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 50);
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  useEffect(() => {
    // Init dark mode
    const isDark = localStorage.getItem('munisys_theme') === 'dark';
    setDarkMode(isDark);
    if (isDark) document.documentElement.setAttribute('data-theme', 'dark');
  }, []);

  const toggleDarkMode = () => {
    const newMode = !darkMode;
    setDarkMode(newMode);
    if (newMode) {
      document.documentElement.setAttribute('data-theme', 'dark');
      localStorage.setItem('munisys_theme', 'dark');
    } else {
      document.documentElement.removeAttribute('data-theme');
      localStorage.setItem('munisys_theme', 'light');
    }
  };

  useEffect(() => {
    const header = document.getElementById('munisys-global-header');
    if (header) {
      if (menuOpen) {
        header.classList.add('menu-open');
        document.body.style.overflow = 'hidden';
      } else {
        header.classList.remove('menu-open');
        document.body.style.overflow = '';
      }
    }
  }, [menuOpen]);

  return (
    <>
      <div className="mobile-overlay" onClick={() => setMenuOpen(false)}></div>
      <nav className={`nav ${scrolled ? 'scrolled' : ''} ${menuOpen ? 'nav-open' : ''}`}>
        <div className="nav-container">
          <Link href="/" className="nav-logo" style={{ display: 'flex', alignItems: 'center', height: '40px' }}>
            <img src={dbData?.theme?.logo || "/images/logo.svg"} alt="Logo" style={{ height: '100%', width: 'auto', maxHeight: '40px' }} />
          </Link>
          
          <ul className="nav-links" style={{ gap: '0.4rem', flexWrap: 'nowrap' }}>
            <li className={`nav-item ${accordionOpen ? 'accordion-open' : ''}`}>
              <Magnetic strength={0.3}>
                <button 
                  className="mega-toggle" 
                  aria-expanded={accordionOpen}
                  onClick={(e) => {
                    // Prevent default on click only if it's mobile (which is when it acts as an accordion)
                    if (window.innerWidth <= 1300 || document.body.classList.contains('preview-mobile') || document.body.classList.contains('preview-tablet')) {
                      e.preventDefault();
                      setAccordionOpen(!accordionOpen);
                    }
                  }}
                  style={{ fontSize: '0.65rem', padding: '0.2rem', letterSpacing: '0' }}
                >
                  <InlineEditor 
                    as="span" 
                    dbPath={['navigation', 'links', 'solutions']} 
                    fallback="Solutions" 
                  />
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{width: '16px', height: '16px', marginLeft: '4px', transform: accordionOpen ? 'rotate(180deg)' : 'rotate(0)', transition: 'transform 0.3s ease'}}>
                    <path d="M6 9l6 6 6-6"/>
                  </svg>
                </button>
              </Magnetic>
              <div className="mega-menu">
                {Object.values(dbData?.navigation?.megaMenu || navDump.megaMenu).map((col, colIdx) => (
                  <div key={colIdx} className="mega-col">
                    <Link href={col.href || '#'} className="mega-col-title">
                      <InlineEditor 
                        as="span" 
                        dbPath={['navigation', 'megaMenu', colIdx, 'title']} 
                        fallback={col.title} 
                      />
                    </Link>
                    {Object.values(col.items || {}).map((item, itemIdx) => (
                      <Link key={itemIdx} href={item.href || '#'} className="mega-col-link">
                        <InlineEditor 
                          as="span" 
                          dbPath={['navigation', 'megaMenu', colIdx, 'items', itemIdx, 'label']} 
                          fallback={item.label} 
                        />
                      </Link>
                    ))}
                  </div>
                ))}
              </div>
            </li>

            {(nav.mainLinks || []).map((item, idx) => (
              <li key={idx} className="nav-item">
                <Magnetic strength={0.3}>
                  <Link href={item.href || '#'} style={{ fontSize: '0.65rem', padding: '0.2rem', letterSpacing: '0', display: 'flex', alignItems: 'center' }}>
                    <InlineEditor 
                      as="span" 
                      dbPath={['navigation', 'mainLinks', idx, 'label']} 
                      fallback={item.label} 
                    />
                  </Link>
                </Magnetic>
              </li>
            ))}
          </ul>

          <div className="nav-actions" style={{ gap: '1rem', marginLeft: '0' }}>
            <button 
              className="search-btn" 
              aria-label="Search" 
              id="munisys-header-search-btn"
              onClick={() => setSearchOpen(true)}
            >
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                <circle cx="11" cy="11" r="8" />
                <path d="M21 21l-4.35-4.35" />
              </svg>
            </button>
            <div 
              className="custom-lang-dropdown" 
              style={{ position: 'relative' }}
            >
              <button 
                className="lang-dropdown-toggle"
                onClick={() => setLangMenuOpen(!langMenuOpen)}
                aria-label="Change language"
                style={{ 
                  display: 'flex', alignItems: 'center', gap: '4px', 
                  background: 'rgba(0,0,0,0.04)', border: 'none', padding: '4px 8px', 
                  borderRadius: '20px', cursor: 'pointer', fontWeight: 600, fontSize: '0.75rem', color: '#1d4e79',
                  transition: 'background 0.2s'
                }}
                onMouseOver={(e) => e.currentTarget.style.background = 'rgba(0,0,0,0.08)'}
                onMouseOut={(e) => e.currentTarget.style.background = 'rgba(0,0,0,0.04)'}
              >
                <span style={{ fontSize: '1rem' }}>
                  {({
                    en: '🇬🇧', fr: '🇫🇷', ar: '🇲🇦',
                    es: '🇪🇸', de: '🇩🇪', pt: '🇵🇹',
                    it: '🇮🇹', zh: '🇨🇳', ru: '🇷🇺',
                    nl: '🇳🇱', ja: '🇯🇵', ko: '🇰🇷',
                  })[lang] || '🌐'}
                </span>
                <span style={{ fontSize: '0.75rem', fontWeight: 700, letterSpacing: '0px' }}>
                  {lang.toUpperCase()}
                </span>
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"
                  style={{ transition: 'transform 0.2s', transform: langMenuOpen ? 'rotate(180deg)' : 'rotate(0)' }}>
                  <path d="M6 9l6 6 6-6"/>
                </svg>
              </button>
              
              {langMenuOpen && (
                <>
                  {/* Invisible backdrop to close on click-outside */}
                  <div 
                    style={{ position: 'fixed', inset: 0, zIndex: 99 }}
                    onClick={() => setLangMenuOpen(false)}
                  />
                  <div style={{ position: 'absolute', top: 'calc(100% + 8px)', right: 0, zIndex: 100 }}>
                    <div style={{
                      background: 'white', borderRadius: '14px', 
                      boxShadow: '0 8px 30px rgba(0,0,0,0.14), 0 2px 8px rgba(0,0,0,0.06)',
                      padding: '6px', minWidth: '180px', 
                      border: '1px solid rgba(0,0,0,0.06)',
                      overflow: 'hidden'
                    }}>
                      {[
                        { code: 'en', flag: '🇬🇧', name: 'English',    dir: 'LTR' },
                        { code: 'fr', flag: '🇫🇷', name: 'Français',   dir: 'LTR' },
                        { code: 'ar', flag: '🇲🇦', name: 'العربية',    dir: 'RTL' },
                        { code: 'es', flag: '🇪🇸', name: 'Español',    dir: 'LTR' },
                        { code: 'de', flag: '🇩🇪', name: 'Deutsch',    dir: 'LTR' },
                        { code: 'pt', flag: '🇵🇹', name: 'Português',  dir: 'LTR' },
                        { code: 'it', flag: '🇮🇹', name: 'Italiano',   dir: 'LTR' },
                        { code: 'zh', flag: '🇨🇳', name: '中文',        dir: 'LTR' },
                        { code: 'ru', flag: '🇷🇺', name: 'Русский',    dir: 'LTR' },
                        { code: 'nl', flag: '🇳🇱', name: 'Nederlands', dir: 'LTR' },
                        { code: 'ja', flag: '🇯🇵', name: '日本語',      dir: 'LTR' },
                        { code: 'ko', flag: '🇰🇷', name: '한국어',      dir: 'LTR' },
                      ]
                        // Only show languages that exist in the DB
                        .filter(l => fullDb ? fullDb[l.code] !== undefined : ['en','fr','ar'].includes(l.code))
                        .map(l => (
                        <button 
                          key={l.code}
                          onClick={() => { setLang(l.code); setLangMenuOpen(false); }}
                          style={{ 
                            display: 'flex', alignItems: 'center', gap: '10px',
                            width: '100%', padding: '10px 12px',
                            background: lang === l.code ? 'rgba(146,43,85,0.07)' : 'transparent', 
                            border: 'none', textAlign: 'left', cursor: 'pointer', fontSize: '0.875rem',
                            fontWeight: lang === l.code ? 700 : 500, 
                            color: lang === l.code ? 'var(--bordeaux, #922b55)' : '#475569',
                            borderRadius: '10px',
                            transition: 'all 0.15s ease',
                            direction: 'ltr'
                          }}
                          onMouseOver={(e) => { e.currentTarget.style.background = lang === l.code ? 'rgba(146,43,85,0.07)' : 'rgba(0,0,0,0.04)'; }}
                          onMouseOut={(e) => { e.currentTarget.style.background = lang === l.code ? 'rgba(146,43,85,0.07)' : 'transparent'; }}
                        >
                          <span style={{ fontSize: '1.25rem', flexShrink: 0 }}>{l.flag}</span>
                          <span style={{ flex: 1 }}>{l.name}</span>
                          <span style={{ 
                            fontSize: '0.65rem', fontWeight: 600, letterSpacing: '0.5px',
                            color: '#94a3b8', background: 'rgba(0,0,0,0.05)',
                            padding: '2px 6px', borderRadius: '4px', flexShrink: 0
                          }}>{l.dir}</span>
                          {lang === l.code && (
                            <span style={{ color: 'var(--bordeaux, #922b55)', flexShrink: 0 }}>
                              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                            </span>
                          )}
                        </button>
                      ))}
                    </div>
                  </div>
                </>
              )}
            </div>
            
            <button 
              className="dark-mode-toggle"
              onClick={toggleDarkMode}
              style={{
                background: 'rgba(0,0,0,0.04)', border: 'none', padding: '6px',
                borderRadius: '50%', cursor: 'pointer', color: '#1d4e79',
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                transition: 'background 0.2s'
              }}
              aria-label="Toggle Dark Mode"
            >
              {darkMode ? (
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
              ) : (
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
              )}
            </button>

            <Magnetic strength={0.3}>
              <Link href="/contact" className="nav-cta" style={{ padding: '0.4rem 0.8rem', fontSize: '0.65rem', gap: '0.3rem' }}>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                  <polyline points="22,6 12,13 2,6" />
                </svg>
                <span>
                  <InlineEditor 
                    as="span" 
                    dbPath={['navigation', 'ctaLabel']} 
                    fallback="Contact us" 
                  />
                </span>
              </Link>
            </Magnetic>
            <button className="mobile-toggle" aria-label="Menu" aria-expanded={menuOpen} onClick={() => setMenuOpen(!menuOpen)}>
              {menuOpen ? (
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <line x1="18" y1="6" x2="6" y2="18" />
                  <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
              ) : (
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <line x1="3" y1="12" x2="21" y2="12" />
                  <line x1="3" y1="6" x2="21" y2="6" />
                  <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
              )}
            </button>
          </div>
        </div>
      </nav>
      <SearchOverlay isOpen={searchOpen} onClose={() => setSearchOpen(false)} />
    </>
  );
}
