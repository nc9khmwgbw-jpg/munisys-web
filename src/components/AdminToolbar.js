"use client";

import React, { useState, useEffect, useRef } from 'react';
import { usePathname } from 'next/navigation';
import { useCMS } from './CMSProvider';

export default function AdminToolbar() {
  const { isEditMode, isPreviewMode, toggleEditMode, togglePreviewMode, saveChanges, saving, hasUnsavedChanges, dbData, updateData, fullDb, addLanguage, removeLanguage, translating, viewportMode, setViewportMode } = useCMS();
  const [isOpen, setIsOpen] = useState(false);
  const [activeTab, setActiveTab] = useState('Design');
  const [newLangInput, setNewLangInput] = useState('');
  const pathname = usePathname();
  const [draggedItemIndex, setDraggedItemIndex] = useState(null);
  const [dragOverItemIndex, setDragOverItemIndex] = useState(null);
  const sidebarRef = useRef(null);

  useEffect(() => {
    const handleClickOutside = (event) => {
      // Don't close if they click the toggle button, floating toolbar, or inline editor elements
      if (
        sidebarRef.current && 
        !sidebarRef.current.contains(event.target) &&
        !event.target.closest('.munisys-floating-toolbar') &&
        !event.target.closest('.munisys-editable') &&
        !event.target.closest('.munisys-editable-img') &&
        event.target.id !== 'munisys-admin-toggle'
      ) {
        setIsOpen(false);
      }
    };

    if (isOpen) {
      document.addEventListener('mousedown', handleClickOutside);
    }
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, [isOpen]);

  const pageKey = pathname === '/' ? 'home' : pathname.replace(/^\//, '').replace(/\//g, '_');
  const pageSeo = dbData?.seo?.[pageKey] || {};

  const formatPathName = (path) => {
    if (path === '/' || !path) return 'Home';
    return path.replace(/^\//, '').split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
  };
  const globalSeo = dbData?.seo?.home || {};
  const defaultTitle = globalSeo.title ? `${globalSeo.title.split('—')[0].trim()} — ${formatPathName(pathname)}` : `Munisys — ${formatPathName(pathname)}`;
  
  const getSmartDescFallback = () => {
    if (!dbData) return "";
    if (pathname === '/') return dbData.homepage?.slides?.[0]?.desc || "";
    
    // Convert /about-us to aboutUs
    let sectionKey = pathname.replace(/^\//, '').replace(/-([a-z])/g, (g) => g[1].toUpperCase());
    
    if (dbData[sectionKey] && dbData[sectionKey].heroDesc) {
      return dbData[sectionKey].heroDesc;
    }
    if (pathname.startsWith('/solutions/')) {
      const slug = pathname.replace('/solutions/', '');
      if (dbData.solutions?.[slug]?.heroDesc) return dbData.solutions[slug].heroDesc;
    }
    return globalSeo.description || "We orchestrate digital infrastructure technologies for a unique customer experience.";
  };

  const defaultDesc = getSmartDescFallback();
  const defaultImg = globalSeo.ogImage || "";

  // Raccourci clavier Ctrl+S pour sauvegarder
  React.useEffect(() => {
    if (!isEditMode) return;
    
    const handleKeyDown = (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        if (hasUnsavedChanges && !saving) {
          saveChanges();
        }
      }
    };

    document.addEventListener('keydown', handleKeyDown);
    return () => document.removeEventListener('keydown', handleKeyDown);
  }, [isEditMode, hasUnsavedChanges, saving, saveChanges]);

  const handleLogout = async () => {
    if (hasUnsavedChanges) {
      if (!confirm('Vous avez des modifications non sauvegardées. Voulez-vous vraiment vous déconnecter ?')) {
        return;
      }
    }
    
    try {
      await fetch('/api/admin/logout', { method: 'POST' });
    } catch (e) {
      console.error('Erreur lors de la déconnexion', e);
    }

    localStorage.removeItem('munisys_admin');
    toggleEditMode(false);
    window.location.href = '/admin';
  };

  if (!isEditMode) return null;

  const renderColorInput = (label, key) => {
    const val = fullDb?.theme?.[key] || '#000000';
    return (
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '12px' }}>
        <label style={{ fontSize: '0.85rem', color: '#cbd5e1' }}>{label}</label>
        <div style={{
          position: 'relative',
          width: '32px',
          height: '32px',
          borderRadius: '8px',
          overflow: 'hidden',
          border: '2px solid rgba(255,255,255,0.1)',
          boxShadow: '0 2px 5px rgba(0,0,0,0.3)'
        }}>
          <input 
            type="color" 
            value={val} 
            onChange={(e) => updateData(['theme', key], e.target.value)}
            style={{ 
              position: 'absolute',
              top: '-10px', left: '-10px',
              width: '52px', height: '52px',
              padding: 0, border: 'none', cursor: 'pointer',
              background: 'none'
            }}
          />
        </div>
      </div>
    );
  };

  return (
    <>
      {/* Floating Toggle Button (When Sidebar is closed) */}
      <button 
        id="munisys-admin-toggle"
        onClick={() => setIsOpen(!isOpen)}
        style={{
          position: 'fixed',
          top: '50%',
          transform: 'translateY(-50%)',
          right: isOpen ? '440px' : '20px',
          width: '50px',
          height: '50px',
          borderRadius: '25px',
          background: 'linear-gradient(135deg, #922b55, #1d4e79)',
          color: 'white',
          boxShadow: '0 4px 15px rgba(0,0,0,0.3)',
          cursor: 'pointer',
          zIndex: 9999999,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          transition: 'right 0.4s cubic-bezier(0.16, 1, 0.3, 1), transform 0.2s',
        }}
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
        </svg>
      </button>

      {/* Sidebar Panel */}
      <div 
        id="munisys-admin-sidebar"
        ref={sidebarRef}
        style={{
          position: 'fixed',
          top: '20px',
          right: '20px',
          height: 'calc(100vh - 40px)',
          width: '400px',
          background: '#0f172a',
          borderRadius: '16px',
          border: '1px solid rgba(255,255,255,0.1)',
          boxShadow: '0 10px 40px rgba(0,0,0,0.5)',
          transform: isOpen ? 'translateX(0)' : 'translateX(calc(100% + 40px))',
          transition: 'transform 0.4s cubic-bezier(0.16, 1, 0.3, 1)',
          zIndex: 9999999,
          color: 'white',
          display: 'flex',
          flexDirection: 'column',
          fontFamily: "'Inter', 'Montserrat', sans-serif"
        }}>
        
        {/* Header */}
        <div style={{
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          padding: '20px 24px',
          borderBottom: '1px solid rgba(255,255,255,0.08)',
          borderRadius: '16px 16px 0 0'
        }}>
          <h2 style={{ fontSize: '1.1rem', fontWeight: 700, margin: 0, display: 'flex', alignItems: 'center', gap: '8px' }}>
            <span style={{ 
              display: 'flex', alignItems: 'center', justifyContent: 'center', 
              width: '28px', height: '28px', background: '#922b55', borderRadius: '6px' 
            }}>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5"><path d="M12 20h9"></path><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
            </span>
            Visual Builder
          </h2>
          <button 
            onClick={() => setIsOpen(false)}
            style={{
              background: 'none', border: 'none', color: '#94a3b8', cursor: 'pointer',
              display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '4px',
              transition: 'color 0.2s'
            }}
            onMouseOver={(e) => e.currentTarget.style.color = 'white'}
            onMouseOut={(e) => e.currentTarget.style.color = '#94a3b8'}
          >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          </button>
        </div>

        {/* Tab Navigation */}
        <div style={{ display: 'flex', borderBottom: '1px solid rgba(255,255,255,0.08)' }}>
          {['Design', 'SEO', 'Menu', 'Langues'].map(tab => (
            <button
              key={tab}
              onClick={() => setActiveTab(tab)}
              style={{
                flex: 1,
                padding: '12px 0',
                background: 'transparent',
                border: 'none',
                color: activeTab === tab ? 'white' : '#94a3b8',
                borderBottom: activeTab === tab ? '2px solid #38bdf8' : '2px solid transparent',
                cursor: 'pointer',
                fontWeight: 600,
                fontSize: '0.9rem',
                transition: 'all 0.2s'
              }}
            >
              {tab}
            </button>
          ))}
        </div>

        {/* Scrollable Content */}
        <div style={{ flex: 1, overflowY: 'auto', padding: '0 24px 24px', borderRadius: '0 0 16px 16px' }}>
          
          {/* Save Status / Button */}
          <div style={{ marginBottom: '32px' }}>
            <button 
              onClick={saveChanges}
              disabled={saving || !hasUnsavedChanges}
              style={{
                width: '100%',
                padding: '12px 20px',
                background: saving ? '#475569' : (hasUnsavedChanges ? '#22c55e' : 'rgba(255,255,255,0.05)'),
                color: hasUnsavedChanges || saving ? 'white' : '#64748b',
                border: 'none',
                borderRadius: '8px',
                cursor: (saving || !hasUnsavedChanges) ? 'default' : 'pointer',
                fontWeight: 600,
                fontSize: '0.95rem',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                gap: '8px',
                transition: 'all 0.3s',
                boxShadow: hasUnsavedChanges ? '0 4px 15px rgba(34, 197, 94, 0.3)' : 'none',
                animation: hasUnsavedChanges && !saving ? 'pulse 2s infinite' : 'none'
              }}
            >
              {saving ? (
                <span>⏳ Sauvegarde en cours...</span>
              ) : hasUnsavedChanges ? (
                <>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                  Publier les modifications
                </>
              ) : (
                <>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                  À jour
                </>
              )}
            </button>
            {hasUnsavedChanges && <div style={{ fontSize: '0.75rem', color: '#94a3b8', textAlign: 'center', marginTop: '8px' }}>Raccourci: Ctrl+S</div>}
          </div>

          {activeTab === 'Design' && (
            <div style={{ display: 'flex', flexDirection: 'column', gap: '24px' }}>
              {/* Theme Colors Section */}
              <div>
                <h3 style={{ fontSize: '0.8rem', textTransform: 'uppercase', letterSpacing: '1px', color: '#94a3b8', margin: '0 0 16px 0', borderBottom: '1px solid rgba(255,255,255,0.08)', paddingBottom: '8px' }}>
                  🎨 Thème Global
                </h3>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                  
                  {/* Colors */}
                  {[
                    { key: 'bordeaux', label: 'Couleur Primaire (Accents)', default: '#922b55' },
                    { key: 'blueDark', label: 'Couleur Secondaire (Fonds)', default: '#1d4e79' },
                    { key: 'blueLight', label: 'Couleur Tertiaire (Détails)', default: '#83a5c2' },
                    { key: 'bgGray', label: 'Couleur de Fond Globale', default: '#f8fafc' },
                    { key: 'textGray', label: 'Couleur du Texte', default: '#475569' }
                  ].map((colorObj, idx) => (
                    <div key={idx} style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                      <input 
                        type="color" 
                        value={(fullDb?.theme || {})[colorObj.key] || colorObj.default}
                        onChange={(e) => updateData(['theme', colorObj.key], e.target.value)}
                        style={{ width: '36px', height: '36px', padding: '0', border: 'none', borderRadius: '4px', cursor: 'pointer', background: 'transparent' }}
                      />
                      <div>
                        <h4 style={{ margin: '0 0 2px 0', fontSize: '0.85rem', color: '#e2e8f0' }}>{colorObj.label}</h4>
                      </div>
                    </div>
                  ))}

                  {/* Typography */}
                  <div style={{ marginTop: '8px' }}>
                    <h4 style={{ margin: '0 0 8px 0', fontSize: '0.85rem', color: '#e2e8f0' }}>Typographie (Police)</h4>
                    <select 
                      value={(fullDb?.theme || {}).fontFamily || 'Montserrat'}
                      onChange={(e) => updateData(['theme', 'fontFamily'], e.target.value)}
                      style={{ width: '100%', padding: '8px 12px', background: 'rgba(0,0,0,0.2)', border: '1px solid rgba(255,255,255,0.1)', color: 'white', borderRadius: '6px', outline: 'none' }}
                    >
                      <option value="Montserrat">Montserrat (Moderne, Actuel)</option>
                      <option value="Inter">Inter (Très lisible, Tech)</option>
                      <option value="Playfair Display">Playfair Display (Classique, Premium)</option>
                    </select>
                  </div>

                  {/* Button Style */}
                  <div style={{ marginTop: '8px' }}>
                    <h4 style={{ margin: '0 0 8px 0', fontSize: '0.85rem', color: '#e2e8f0' }}>Style des Boutons</h4>
                    <div style={{ display: 'flex', gap: '8px' }}>
                      {[
                        { label: 'Carré', radius: '0px' },
                        { label: 'Arrondi', radius: '8px' },
                        { label: 'Pilule', radius: '30px' }
                      ].map(style => (
                        <button
                          key={style.label}
                          onClick={() => updateData(['theme', 'buttonRadius'], style.radius)}
                          style={{
                            flex: 1, padding: '8px', background: (fullDb?.theme?.buttonRadius || '30px') === style.radius ? 'var(--bordeaux)' : 'rgba(255,255,255,0.1)',
                            border: 'none', borderRadius: '6px', color: 'white', cursor: 'pointer', fontSize: '0.8rem', transition: '0.2s'
                          }}
                        >
                          {style.label}
                        </button>
                      ))}
                    </div>
                  </div>

                </div>
              </div>

              {/* Branding Section */}
              <div>
                <h3 style={{ fontSize: '0.8rem', textTransform: 'uppercase', letterSpacing: '1px', color: '#94a3b8', margin: '0 0 16px 0', borderBottom: '1px solid rgba(255,255,255,0.08)', paddingBottom: '8px' }}>
                  ✨ Branding Global
                </h3>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                  <div>
                    <h4 style={{ margin: '0 0 8px 0', fontSize: '0.85rem', color: '#e2e8f0' }}>Logo Principal (Menu)</h4>
                    <input 
                      type="text" 
                      value={(fullDb?.theme || {}).logo || 'https://www.munisys.ma/assets/images/logo.svg'}
                      onChange={(e) => updateData(['theme', 'logo'], e.target.value)}
                      placeholder="https://..."
                      style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid rgba(255,255,255,0.1)', background: 'rgba(0,0,0,0.3)', color: 'white', fontSize: '0.85rem' }}
                    />
                  </div>
                  <div>
                    <h4 style={{ margin: '0 0 8px 0', fontSize: '0.85rem', color: '#e2e8f0' }}>Favicon (Onglet Navigateur)</h4>
                    <input 
                      type="text" 
                      value={(fullDb?.theme || {}).favicon || '/favicon.ico'}
                      onChange={(e) => updateData(['theme', 'favicon'], e.target.value)}
                      placeholder="/favicon.ico ou URL"
                      style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid rgba(255,255,255,0.1)', background: 'rgba(0,0,0,0.3)', color: 'white', fontSize: '0.85rem' }}
                    />
                  </div>
                </div>
              </div>

              {/* Instructions Section */}
              <div>
                <h3 style={{ fontSize: '0.8rem', textTransform: 'uppercase', letterSpacing: '1px', color: '#94a3b8', margin: '0 0 16px 0', borderBottom: '1px solid rgba(255,255,255,0.08)', paddingBottom: '8px' }}>
                  📖 Comment éditer ?
                </h3>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                  <div style={{ display: 'flex', gap: '12px' }}>
                    <div style={{ flexShrink: 0, width: '28px', height: '28px', borderRadius: '50%', background: 'rgba(56, 189, 248, 0.1)', color: '#38bdf8', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><polyline points="4 7 4 4 20 4 20 7"></polyline><line x1="9" y1="20" x2="15" y2="20"></line><line x1="12" y1="4" x2="12" y2="20"></line></svg>
                    </div>
                    <div>
                      <h4 style={{ margin: '0 0 4px 0', fontSize: '0.85rem', color: '#e2e8f0' }}>Textes & Titres</h4>
                      <p style={{ margin: 0, fontSize: '0.75rem', color: '#94a3b8', lineHeight: 1.4 }}>
                        Double-cliquez sur n'importe quel texte encadré en pointillés. Modifiez, puis cliquez à l'extérieur.
                      </p>
                    </div>
                  </div>
                  <div style={{ display: 'flex', gap: '12px' }}>
                    <div style={{ flexShrink: 0, width: '28px', height: '28px', borderRadius: '50%', background: 'rgba(244, 63, 94, 0.1)', color: '#f43f5e', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    </div>
                    <div>
                      <h4 style={{ margin: '0 0 4px 0', fontSize: '0.85rem', color: '#e2e8f0' }}>Images & Fonds</h4>
                      <p style={{ margin: 0, fontSize: '0.75rem', color: '#94a3b8', lineHeight: 1.4 }}>
                        Cliquez sur une image. Un sélecteur de fichier s'ouvrira pour l'importer instantanément.
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}

          {activeTab === 'SEO' && (
            <div>
              <h3 style={{ fontSize: '0.8rem', textTransform: 'uppercase', letterSpacing: '1px', color: '#94a3b8', margin: '0 0 16px 0', borderBottom: '1px solid rgba(255,255,255,0.08)', paddingBottom: '8px' }}>
                🔍 Référencement de la page actuelle
              </h3>
              <p style={{ fontSize: '0.75rem', color: '#cbd5e1', marginBottom: '16px' }}>Page modifiée : <strong style={{color: 'white'}}>{pathname}</strong></p>
              <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                <div>
                  <label style={{ display: 'block', fontSize: '0.8rem', color: '#cbd5e1', marginBottom: '8px' }}>Titre de la page (onglet)</label>
                  <input 
                    type="text" 
                    value={pageSeo.title !== undefined ? pageSeo.title : defaultTitle} 
                    onChange={(e) => updateData(['seo', pageKey, 'title'], e.target.value)}
                    placeholder="Munisys — IT Architecture"
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid rgba(255,255,255,0.1)', background: 'rgba(0,0,0,0.3)', color: 'white', fontSize: '0.85rem' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '0.8rem', color: '#cbd5e1', marginBottom: '8px' }}>Description (Google)</label>
                  <textarea 
                    value={pageSeo.description !== undefined ? pageSeo.description : defaultDesc} 
                    onChange={(e) => updateData(['seo', pageKey, 'description'], e.target.value)}
                    placeholder="We orchestrate digital infrastructure..."
                    rows={4}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid rgba(255,255,255,0.1)', background: 'rgba(0,0,0,0.3)', color: 'white', fontSize: '0.85rem', resize: 'vertical' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '0.8rem', color: '#cbd5e1', marginBottom: '8px' }}>Image de partage (Lien de l'image)</label>
                  <input 
                    type="text" 
                    value={pageSeo.ogImage !== undefined ? pageSeo.ogImage : defaultImg} 
                    onChange={(e) => updateData(['seo', pageKey, 'ogImage'], e.target.value)}
                    placeholder="https://..."
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid rgba(255,255,255,0.1)', background: 'rgba(0,0,0,0.3)', color: 'white', fontSize: '0.85rem' }}
                  />
                </div>
              </div>
            </div>
          )}

          {activeTab === 'Menu' && (
            <div>
              <h3 style={{ fontSize: '0.8rem', textTransform: 'uppercase', letterSpacing: '1px', color: '#94a3b8', margin: '0 0 16px 0', borderBottom: '1px solid rgba(255,255,255,0.08)', paddingBottom: '8px', display: 'flex', justifyContent: 'space-between' }}>
                <span>🧭 Liens Principaux</span>
                <button 
                  onClick={() => {
                    const currentLinks = dbData?.navigation?.mainLinks || [];
                    updateData(['navigation', 'mainLinks'], [...currentLinks, { label: 'Nouveau lien', href: '#' }]);
                  }}
                  style={{ background: 'none', border: 'none', color: '#38bdf8', cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '4px' }}
                >
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                  Ajouter
                </button>
              </h3>
              <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
                {(dbData?.navigation?.mainLinks || []).map((link, idx) => (
                  <div 
                    key={idx} 
                    draggable
                    onDragStart={(e) => {
                      setDraggedItemIndex(idx);
                      // Set dataTransfer for Firefox compatibility
                      e.dataTransfer.setData('text/plain', idx);
                    }}
                    onDragOver={(e) => {
                      e.preventDefault();
                      setDragOverItemIndex(idx);
                    }}
                    onDrop={(e) => {
                      e.preventDefault();
                      if (draggedItemIndex === null || draggedItemIndex === idx) return;
                      const currentLinks = [...(dbData?.navigation?.mainLinks || [])];
                      const draggedItem = currentLinks[draggedItemIndex];
                      currentLinks.splice(draggedItemIndex, 1);
                      currentLinks.splice(idx, 0, draggedItem);
                      updateData(['navigation', 'mainLinks'], currentLinks);
                      setDraggedItemIndex(null);
                      setDragOverItemIndex(null);
                    }}
                    onDragEnd={() => {
                      setDraggedItemIndex(null);
                      setDragOverItemIndex(null);
                    }}
                    style={{ 
                      background: 'rgba(255,255,255,0.03)', 
                      border: dragOverItemIndex === idx ? '2px dashed #38bdf8' : '1px solid rgba(255,255,255,0.05)', 
                      borderRadius: '8px', 
                      padding: '12px',
                      opacity: draggedItemIndex === idx ? 0.5 : 1,
                      cursor: 'grab',
                      transition: 'border 0.2s, opacity 0.2s'
                    }}
                  >
                    <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '8px', alignItems: 'center' }}>
                      <span style={{ fontSize: '0.75rem', color: '#94a3b8', display: 'flex', alignItems: 'center', gap: '8px' }}>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ cursor: 'grab' }}><line x1="4" y1="8" x2="20" y2="8"></line><line x1="4" y1="16" x2="20" y2="16"></line></svg>
                        Lien {idx + 1}
                      </span>
                      <button 
                        onClick={() => {
                          const currentLinks = [...(dbData?.navigation?.mainLinks || [])];
                          currentLinks.splice(idx, 1);
                          updateData(['navigation', 'mainLinks'], currentLinks);
                        }}
                        style={{ background: 'none', border: 'none', color: '#f43f5e', cursor: 'pointer', display: 'flex' }}
                      >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                      </button>
                    </div>
                    <div style={{ display: 'flex', flexDirection: 'column', gap: '8px' }}>
                      <input 
                        type="text" 
                        value={link.label}
                        onChange={(e) => {
                          const currentLinks = [...(dbData?.navigation?.mainLinks || [])];
                          currentLinks[idx] = { ...currentLinks[idx], label: e.target.value };
                          updateData(['navigation', 'mainLinks'], currentLinks);
                        }}
                        placeholder="Nom (ex: Services)"
                        style={{ width: '100%', padding: '8px', borderRadius: '4px', border: '1px solid rgba(255,255,255,0.1)', background: 'rgba(0,0,0,0.3)', color: 'white', fontSize: '0.8rem' }}
                      />
                      <input 
                        type="text" 
                        value={link.href}
                        onChange={(e) => {
                          const currentLinks = [...(dbData?.navigation?.mainLinks || [])];
                          currentLinks[idx] = { ...currentLinks[idx], href: e.target.value };
                          updateData(['navigation', 'mainLinks'], currentLinks);
                        }}
                        placeholder="Lien (ex: /services)"
                        style={{ width: '100%', padding: '8px', borderRadius: '4px', border: '1px solid rgba(255,255,255,0.1)', background: 'rgba(0,0,0,0.3)', color: 'white', fontSize: '0.8rem' }}
                      />
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}

          {activeTab === 'Langues' && (
            <div>
              <h3 style={{ fontSize: '0.8rem', textTransform: 'uppercase', letterSpacing: '1px', color: '#94a3b8', margin: '0 0 16px 0', borderBottom: '1px solid rgba(255,255,255,0.08)', paddingBottom: '8px' }}>
                🌐 Langues Actives
              </h3>
              <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap', marginBottom: '24px' }}>
                {Object.keys(fullDb || {}).filter(k => k !== 'theme').map(l => (
                  <div key={l} style={{ display: 'flex', alignItems: 'center', gap: '6px', padding: '6px 12px', background: 'rgba(255,255,255,0.1)', borderRadius: '20px', fontSize: '0.85rem', color: 'white', border: '1px solid rgba(255,255,255,0.2)' }}>
                    <span style={{ textTransform: 'uppercase' }}>{l}</span>
                    {l !== 'en' && l !== 'fr' && (
                      <button 
                        onClick={() => removeLanguage(l)}
                        style={{ background: 'none', border: 'none', color: '#f43f5e', cursor: 'pointer', padding: '0', display: 'flex', alignItems: 'center', justifyContent: 'center' }}
                        title="Supprimer la langue"
                      >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                      </button>
                    )}
                  </div>
                ))}
              </div>

              <h3 style={{ fontSize: '0.8rem', textTransform: 'uppercase', letterSpacing: '1px', color: '#94a3b8', margin: '0 0 16px 0', borderBottom: '1px solid rgba(255,255,255,0.08)', paddingBottom: '8px' }}>
                ➕ Ajouter une langue
              </h3>
              <p style={{ fontSize: '0.75rem', color: '#cbd5e1', marginBottom: '12px', lineHeight: 1.4 }}>
                Entrez le code de la langue (ex: <strong>ar</strong> pour arabe, <strong>es</strong> pour espagnol). Cela clônera tout le site anglais pour vous permettre de le traduire.
              </p>
              <div style={{ display: 'flex', gap: '8px' }}>
                <input 
                  type="text" 
                  value={newLangInput}
                  onChange={(e) => setNewLangInput(e.target.value.toLowerCase().replace(/[^a-z]/g, '').substring(0, 2))}
                  placeholder="ex: ar"
                  style={{ flex: 1, padding: '10px', borderRadius: '6px', border: '1px solid rgba(255,255,255,0.1)', background: 'rgba(0,0,0,0.3)', color: 'white', fontSize: '0.85rem', textTransform: 'uppercase' }}
                />
                <button 
                  onClick={() => {
                    if (newLangInput.length === 2) {
                      addLanguage(newLangInput);
                      setNewLangInput('');
                    } else {
                      alert('Le code de langue doit faire 2 lettres (ex: ar, es, de)');
                    }
                  }}
                  disabled={translating}
                  style={{ padding: '10px 16px', borderRadius: '6px', border: 'none', background: translating ? '#475569' : '#38bdf8', color: translating ? '#94a3b8' : '#0f172a', fontWeight: 600, cursor: translating ? 'default' : 'pointer' }}
                >
                  {translating ? 'Traduction... ⏳' : 'Ajouter'}
                </button>
              </div>
            </div>
          )}

        </div>

        {/* Footer Area (Logout & Preview) */}
        <div style={{
          padding: '20px 24px',
          borderTop: '1px solid rgba(255,255,255,0.08)',
          background: 'rgba(0,0,0,0.2)',
          display: 'flex',
          flexDirection: 'column',
          gap: '12px'
        }}>
          {/* Responsive Preview Toggle */}
          <div style={{ display: 'flex', gap: '8px', marginBottom: '8px' }}>
            <button 
              onClick={() => setViewportMode('mobile')}
              style={{
                flex: 1, padding: '8px', background: viewportMode === 'mobile' ? 'rgba(56,189,248,0.2)' : 'rgba(255,255,255,0.05)',
                color: viewportMode === 'mobile' ? '#38bdf8' : '#94a3b8', border: '1px solid', borderColor: viewportMode === 'mobile' ? '#38bdf8' : 'rgba(255,255,255,0.1)',
                borderRadius: '6px', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.2s'
              }}
              title="Aperçu Mobile"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
            </button>
            <button 
              onClick={() => setViewportMode('tablet')}
              style={{
                flex: 1, padding: '8px', background: viewportMode === 'tablet' ? 'rgba(56,189,248,0.2)' : 'rgba(255,255,255,0.05)',
                color: viewportMode === 'tablet' ? '#38bdf8' : '#94a3b8', border: '1px solid', borderColor: viewportMode === 'tablet' ? '#38bdf8' : 'rgba(255,255,255,0.1)',
                borderRadius: '6px', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.2s'
              }}
              title="Aperçu Tablette"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
            </button>
            <button 
              onClick={() => setViewportMode('desktop')}
              style={{
                flex: 1, padding: '8px', background: viewportMode === 'desktop' ? 'rgba(56,189,248,0.2)' : 'rgba(255,255,255,0.05)',
                color: viewportMode === 'desktop' ? '#38bdf8' : '#94a3b8', border: '1px solid', borderColor: viewportMode === 'desktop' ? '#38bdf8' : 'rgba(255,255,255,0.1)',
                borderRadius: '6px', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.2s'
              }}
              title="Aperçu PC"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
            </button>
          </div>

          <button 
            onClick={togglePreviewMode}
            style={{
              width: '100%',
              padding: '10px 16px',
              background: isPreviewMode ? 'var(--blue-dark)' : 'rgba(255,255,255,0.05)',
              color: 'white',
              border: '1px solid rgba(255,255,255,0.1)',
              borderRadius: '8px',
              cursor: 'pointer',
              fontWeight: 600,
              fontSize: '0.85rem',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '8px',
              transition: 'all 0.2s'
            }}
            onMouseOver={(e) => { e.currentTarget.style.background = isPreviewMode ? 'var(--blue-dark)' : 'rgba(255,255,255,0.1)'; }}
            onMouseOut={(e) => { e.currentTarget.style.background = isPreviewMode ? 'var(--blue-dark)' : 'rgba(255,255,255,0.05)'; }}
          >
            {isPreviewMode ? (
              <>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
                Quitter l'Aperçu
              </>
            ) : (
              <>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                  <line x1="1" y1="1" x2="23" y2="23"></line>
                </svg>
                Aperçu Visiteur
              </>
            )}
          </button>

          <button 
            onClick={handleLogout}
            style={{
              width: '100%',
              padding: '10px 16px',
              background: 'transparent',
              color: '#ef4444',
              border: '1px solid rgba(239, 68, 68, 0.3)',
              borderRadius: '8px',
              cursor: 'pointer',
              fontWeight: 600,
              fontSize: '0.85rem',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '8px',
              transition: 'background 0.2s'
            }}
            onMouseOver={(e) => { e.currentTarget.style.background = 'rgba(239, 68, 68, 0.1)'; }}
            onMouseOut={(e) => { e.currentTarget.style.background = 'transparent'; }}
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
              <polyline points="16 17 21 12 16 7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Se déconnecter
          </button>
        </div>

      </div>
    </>
  );
}
