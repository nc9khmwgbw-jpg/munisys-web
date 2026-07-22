"use client";

import React, { createContext, useContext, useState, useEffect } from 'react';

import FloatingFormatToolbar from './FloatingFormatToolbar';

const CMSContext = createContext();

export function CMSProvider({ children }) {
  const [isEditMode, setIsEditMode] = useState(false);
  const [isPreviewMode, setIsPreviewMode] = useState(false);
  const [dbData, setDbData] = useState(null);
  const [saving, setSaving] = useState(false);
  const [hasUnsavedChanges, setHasUnsavedChanges] = useState(false);
  const [lang, setLang] = useState("en");
  const [translating, setTranslating] = useState(false);
  const [viewportMode, setViewportMode] = useState('desktop');

  // Load language from localStorage
  useEffect(() => {
    const savedLang = localStorage.getItem('munisys_lang');
    if (savedLang) {
      setLang(savedLang);
    }
  }, []);

  const changeLang = (newLang) => {
    setLang(newLang);
    localStorage.setItem('munisys_lang', newLang);
    document.cookie = `munisys_lang=${newLang}; path=/; max-age=31536000`;
  };

  useEffect(() => {
    async function checkAuth() {
      try {
        const res = await fetch('/api/admin/check');
        const data = await res.json();
        if (data.authenticated) {
          setIsEditMode(true);
        } else {
          // Si localStorage a la clé mais que le cookie est absent, on nettoie
          localStorage.removeItem('munisys_admin');
        }
      } catch (e) {
        console.error("Auth check failed", e);
      }
    }
    checkAuth();
  }, []);

  // Update HTML direction & font based on language
  useEffect(() => {
    if (lang === 'ar') {
      document.documentElement.dir = 'rtl';
      document.documentElement.lang = 'ar';
      document.documentElement.style.setProperty('--font-primary', "'Cairo', sans-serif");
      document.body.style.fontFamily = "'Cairo', sans-serif";
    } else {
      document.documentElement.dir = 'ltr';
      document.documentElement.lang = lang;
      document.documentElement.style.removeProperty('--font-primary');
      document.body.style.fontFamily = '';
    }
  }, [lang]);

  // Handle viewport mode (Responsive Preview)
  useEffect(() => {
    if (typeof document === 'undefined') return;
    
    document.body.classList.remove('preview-mobile', 'preview-tablet', 'preview-desktop');
    
    const applyPreviewMediaQueries = (mode) => {
      const styleElId = 'munisys-preview-mq-styles';
      let styleEl = document.getElementById(styleElId);
      if (!styleEl) {
        styleEl = document.createElement('style');
        styleEl.id = styleElId;
        document.head.appendChild(styleEl);
      }
      
      if (mode === 'desktop') {
        styleEl.innerHTML = '';
        return;
      }
      
      let newCSS = '';
      const prefix = `.preview-${mode}`;
      
      try {
        for (let i = 0; i < document.styleSheets.length; i++) {
          const sheet = document.styleSheets[i];
          if (!sheet.cssRules) continue;
          
          for (let j = 0; j < sheet.cssRules.length; j++) {
            const rule = sheet.cssRules[j];
            if (rule instanceof CSSMediaRule) {
              const condition = rule.conditionText || rule.media.mediaText;
              const match = condition.match(/max-width:\s*(\d+)px/);
              if (match) {
                const ruleWidth = parseInt(match[1]);
                const isApplicable = (mode === 'mobile' && ruleWidth >= 414) || (mode === 'tablet' && ruleWidth >= 820);
                
                if (isApplicable) {
                  for (let k = 0; k < rule.cssRules.length; k++) {
                    const innerRule = rule.cssRules[k];
                    if (innerRule.type === 1) { // CSSStyleRule
                      const selectors = innerRule.selectorText.split(',');
                      const prefixedSelectors = selectors.map(s => {
                        const trimmed = s.trim();
                        if (trimmed === 'body' || trimmed === 'html') return `${prefix}`;
                        if (trimmed.startsWith(':root')) return trimmed.replace(':root', `${prefix}`);
                        return `${prefix} ${trimmed}`;
                      }).join(', ');
                      newCSS += `${prefixedSelectors} { ${innerRule.style.cssText} }\n`;
                    }
                  }
                }
              }
            }
          }
        }
      } catch (e) {
        console.warn("Could not parse some stylesheets for preview mode", e);
      }
      styleEl.innerHTML = newCSS;
    };

    if (isEditMode) {
      document.body.classList.add(`preview-${viewportMode}`);
      
      let styleEl = document.getElementById('munisys-preview-styles');
      if (!styleEl) {
        styleEl = document.createElement('style');
        styleEl.id = 'munisys-preview-styles';
        document.head.appendChild(styleEl);
      }
      
      if (viewportMode === 'mobile') {
        styleEl.innerHTML = `
          html { background: #0f172a; }
          body.preview-mobile { padding-top: 30px !important; max-width: 414px; min-height: 100vh; margin: 0 auto !important; position: relative; box-shadow: 0 0 50px rgba(0,0,0,0.8); overflow-x: hidden; border-left: 2px solid #333; border-right: 2px solid #333; background: var(--bg-gray, #fff); }
          body.preview-mobile::before { content: "📱 VUE MOBILE (414px)"; position: absolute; top: 0; left: 0; right: 0; text-align: center; background: #0f172a; color: #38bdf8; font-size: 12px; font-weight: bold; padding: 4px; z-index: 999999; border-bottom: 1px solid rgba(255,255,255,0.1); }
          body.preview-mobile #munisys-global-header .nav { position: sticky !important; top: 30px !important; width: 100% !important; left: 0 !important; z-index: 9999 !important; }
          body.preview-mobile #munisys-wrapper .ft-top, body.preview-mobile #munisys-wrapper .ft-bottom { padding: 2rem !important; flex-direction: column !important; gap: 1rem !important; text-align: center !important; justify-content: center !important; }
          body.preview-mobile #munisys-wrapper .ft-main { padding: 1.5rem !important; grid-template-columns: 1fr 1fr !important; gap: 1rem !important; }
          body.preview-mobile #munisys-tp-wrapper .tp-hero { padding: 4rem 5% 2rem !important; }
          body.preview-mobile #munisys-tp-wrapper .tp-hero h1 { font-size: 1.8rem !important; }
          body.preview-mobile #munisys-tp-wrapper .tp-hero p { font-size: 0.9rem !important; }
          body.preview-mobile #munisys-wrapper .text-panel, body.preview-mobile #munisys-wrapper .text-panel.is-right { width: 90% !important; top: auto !important; bottom: 3% !important; left: 0 !important; right: 0 !important; margin: 0 auto !important; inset-inline-start: 0 !important; transform: none !important; padding: 1rem !important; text-align: center !important; display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; }
          body.preview-mobile #munisys-wrapper .text-slide>* { margin-left: auto !important; margin-right: auto !important; text-align: center !important; }
          body.preview-mobile #munisys-wrapper .title { font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0.5rem !important; word-wrap: break-word; overflow-wrap: break-word; hyphens: auto; text-align: center !important; }
          body.preview-mobile #munisys-wrapper .desc { font-size: 0.85rem !important; max-width: 100% !important; line-height: 1.4 !important; margin-bottom: 1rem !important; text-align: center !important; }
          body.preview-mobile #munisys-wrapper .meta { font-size: 0.65rem !important; margin-bottom: 0.5rem !important; justify-content: center !important; }
          body.preview-mobile #munisys-wrapper .meta::before { display: none !important; }
          body.preview-mobile #munisys-global-header .nav-links { position: absolute !important; top: 100% !important; right: 0 !important; width: 100% !important; max-width: none !important; height: auto !important; max-height: calc(100vh - 80px) !important; padding: 1.5rem !important; box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; border-top: 1px solid #eee; overflow-y: auto !important; }
          body.preview-mobile #munisys-global-header .nav-actions .custom-lang-dropdown,
          body.preview-mobile #munisys-global-header .nav-actions button[aria-label="Toggle Dark Mode"] { display: none !important; }
          body.preview-mobile #munisys-global-header .nav-cta { padding: 0.4rem 0.6rem !important; }
          body.preview-mobile #munisys-global-header .nav-cta span { display: none !important; }
          body.preview-mobile .munisys-switcher-page { padding: 60px 15px 40px !important; }
          body.preview-mobile .munisys-switcher-page h1 { font-size: 36px !important; }
          body.preview-mobile .munisys-switcher-page p.hero-subtitle { font-size: 16px !important; }
          body.preview-mobile #munisys-wrapper .ft-brand { font-size: 1rem !important; }
          body.preview-mobile #munisys-wrapper .ft-col h4 { font-size: 0.65rem !important; }
          body.preview-mobile #munisys-wrapper .ft-col a { font-size: 0.8rem !important; }
          body.preview-mobile #munisys-wrapper .fig-item h3 { font-size: 1.8rem !important; }
          body.preview-mobile #munisys-wrapper .fig-item p { font-size: 0.8rem !important; }
          /* --- STRUCTURAL LAYOUT FIXES (MOBILE) --- */
          body.preview-mobile #munisys-wrapper .bento-grid, body.preview-mobile #munisys-wrapper .off-flow, body.preview-mobile #munisys-wrapper .srv-sec, body.preview-mobile #munisys-wrapper .ins-grid, body.preview-mobile #munisys-wrapper .ref-top { grid-template-columns: 1fr !important; }
          body.preview-mobile #munisys-wrapper .figures-sec, body.preview-mobile #munisys-wrapper .fig-grid, body.preview-mobile #munisys-wrapper .ins-sec { flex-direction: column !important; }
          body.preview-mobile #munisys-wrapper .off-label-vert, body.preview-mobile #munisys-wrapper .fig-vertical, body.preview-mobile #munisys-wrapper .ins-title { writing-mode: initial !important; transform: none !important; text-align: left !important; }
          body.preview-mobile #munisys-wrapper .fig-vertical, body.preview-mobile #munisys-wrapper .ins-title { text-align: center !important; }
          body.preview-mobile #munisys-wrapper .srv-item { grid-template-columns: 1fr auto !important; }
          /* ----------------------------------------------- */
        `;
      } else if (viewportMode === 'tablet') {
        styleEl.innerHTML = `
          html { background: #0f172a; }
          body.preview-tablet { padding-top: 30px !important; max-width: 820px; min-height: 100vh; margin: 0 auto !important; position: relative; box-shadow: 0 0 50px rgba(0,0,0,0.8); overflow-x: hidden; border-left: 2px solid #333; border-right: 2px solid #333; background: var(--bg-gray, #fff); }
          body.preview-tablet::before { content: "💻 VUE TABLETTE (820px)"; position: absolute; top: 0; left: 0; right: 0; text-align: center; background: #0f172a; color: #38bdf8; font-size: 12px; font-weight: bold; padding: 4px; z-index: 999999; border-bottom: 1px solid rgba(255,255,255,0.1); }
          body.preview-tablet #munisys-global-header .nav { position: sticky !important; top: 30px !important; width: 100% !important; left: 0 !important; z-index: 9999 !important; }
          body.preview-tablet #munisys-wrapper .ft-top, body.preview-tablet #munisys-wrapper .ft-bottom { padding: 2rem !important; }
          body.preview-tablet #munisys-wrapper .ft-main { padding: 2rem !important; }
          body.preview-tablet #munisys-wrapper .text-panel, body.preview-tablet #munisys-wrapper .text-panel.is-right { width: 90% !important; left: 0 !important; right: 0 !important; margin: 0 auto !important; inset-inline-start: 0 !important; transform: translateY(-50%) !important; padding: 2rem !important; text-align: center !important; display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; }
          body.preview-tablet #munisys-wrapper .text-slide>* { margin-left: auto !important; margin-right: auto !important; text-align: center !important; }
          body.preview-tablet #munisys-wrapper .title { font-size: 2rem !important; line-height: 1.2 !important; margin-bottom: 1rem !important; word-wrap: break-word; overflow-wrap: break-word; hyphens: auto; text-align: center !important; }
          body.preview-tablet #munisys-wrapper .desc { font-size: 1rem !important; max-width: 100% !important; margin-bottom: 1.5rem !important; text-align: center !important; }
          body.preview-tablet #munisys-wrapper .meta { font-size: 0.75rem !important; margin-bottom: 1rem !important; justify-content: center !important; }
          body.preview-tablet #munisys-wrapper .meta::before { display: none !important; }
          /* --- REST OF THE SITE MINIMIZATION (TABLET) --- */
          body.preview-tablet #munisys-wrapper .sol-sec, body.preview-tablet #munisys-wrapper .off-sec, body.preview-tablet #munisys-wrapper .srv-wrapper, body.preview-tablet #munisys-wrapper .figures-sec { padding: 3rem 5% !important; }
          body.preview-tablet #munisys-wrapper .sol-title, body.preview-tablet #munisys-wrapper .off-title, body.preview-tablet #munisys-wrapper .srv-left h2, body.preview-tablet #munisys-wrapper .fig-vertical { font-size: 1.8rem !important; margin-bottom: 2rem !important; text-align: center !important; }
          body.preview-tablet #munisys-wrapper .bento-grid, body.preview-tablet #munisys-wrapper .off-flow, body.preview-tablet #munisys-wrapper .srv-sec, body.preview-tablet #munisys-wrapper .fig-grid { gap: 1.5rem !important; }
          body.preview-tablet #munisys-wrapper .bento-content h3 { font-size: 1.3rem !important; margin-bottom: 0.5rem !important; }
          body.preview-tablet #munisys-wrapper .bento-content p, body.preview-tablet #munisys-wrapper .off-desc { font-size: 0.95rem !important; line-height: 1.5 !important; }
          body.preview-tablet #munisys-wrapper .fig-item h3 { font-size: 2.2rem !important; }
          body.preview-tablet #munisys-wrapper .fig-item p { font-size: 0.9rem !important; }
          /* --- STRUCTURAL LAYOUT FIXES (TABLET) --- */
          body.preview-tablet #munisys-wrapper .bento-grid, body.preview-tablet #munisys-wrapper .ins-grid { grid-template-columns: repeat(2, 1fr) !important; }
          body.preview-tablet #munisys-wrapper .off-flow, body.preview-tablet #munisys-wrapper .srv-sec, body.preview-tablet #munisys-wrapper .ref-top { grid-template-columns: 1fr !important; }
          body.preview-tablet #munisys-wrapper .figures-sec, body.preview-tablet #munisys-wrapper .ins-sec { flex-direction: column !important; }
          body.preview-tablet #munisys-wrapper .off-label-vert, body.preview-tablet #munisys-wrapper .fig-vertical, body.preview-tablet #munisys-wrapper .ins-title { writing-mode: initial !important; transform: none !important; text-align: center !important; }
          /* ----------------------------------------------- */
          body.preview-tablet #munisys-global-header .mobile-toggle { display: flex !important; }
          body.preview-tablet #munisys-global-header .nav-links { position: absolute !important; top: 100% !important; right: 0 !important; width: 100% !important; max-width: none !important; height: auto !important; max-height: calc(100vh - 80px) !important; padding: 1.5rem !important; box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; border-top: 1px solid #eee; overflow-y: auto !important; background: rgba(255, 255, 255, 0.98) !important; flex-direction: column !important; align-items: flex-start !important; gap: 0 !important; transform: translateX(100%) !important; transition: transform 0.3s ease !important; }
          body.preview-tablet #munisys-global-header .nav.nav-open .nav-links { transform: translateX(0) !important; }
          body.preview-tablet #munisys-global-header .nav-actions { margin-left: auto !important; }
          body.preview-tablet #munisys-global-header .nav-item { width: 100% !important; height: auto !important; border-bottom: 1px solid #eee !important; }
          body.preview-tablet #munisys-global-header .nav-item>a, body.preview-tablet #munisys-global-header .nav-item>button { width: 100% !important; padding: 1.25rem 0 !important; justify-content: space-between !important; display: flex !important; }
          body.preview-tablet #munisys-global-header .mega-menu { position: static !important; max-height: 0 !important; opacity: 0 !important; overflow: hidden !important; padding: 0 1rem !important; background: transparent !important; box-shadow: none !important; transform: none !important; transition: all 0.3s ease !important; flex-direction: column !important; gap: 1rem !important; }
          body.preview-tablet #munisys-global-header .nav-item.accordion-open .mega-menu { max-height: 2000px !important; padding: 1rem !important; opacity: 1 !important; pointer-events: all !important; }
          body.preview-tablet #munisys-global-header .mega-col { width: 100% !important; padding: 0 !important; border: none !important; }
        `;
      } else {
        styleEl.innerHTML = ``;
      }
      
      applyPreviewMediaQueries(viewportMode);
    } else {
      const styleEl = document.getElementById('munisys-preview-styles');
      if (styleEl) styleEl.innerHTML = ``;
      const mqStyleEl = document.getElementById('munisys-preview-mq-styles');
      if (mqStyleEl) mqStyleEl.innerHTML = ``;
    }
  }, [viewportMode, isEditMode]);

  // Apply theme CSS variables when data loads (including font)
  useEffect(() => {
    if (!dbData) return;
    const theme = dbData?.theme || dbData?.[lang]?.theme;
    if (!theme) return;
    const root = document.documentElement;
    if (theme.bordeaux) root.style.setProperty('--bordeaux', theme.bordeaux);
    if (theme.blueDark) root.style.setProperty('--blue-dark', theme.blueDark);
    if (theme.blueLight) root.style.setProperty('--blue-light', theme.blueLight);
    if (theme.bgGray) root.style.setProperty('--bg-gray', theme.bgGray);
    if (theme.textGray) root.style.setProperty('--text-gray', theme.textGray);
    if (theme.buttonRadius) root.style.setProperty('--btn-radius', theme.buttonRadius);
    if (theme.fontFamily) {
      const fontStr = theme.fontFamily === 'Inter' ? "'Inter', sans-serif"
                    : theme.fontFamily === 'Playfair Display' ? "'Playfair Display', serif"
                    : "'Montserrat', sans-serif";
      root.style.setProperty('--font-primary', fontStr);
    }
  }, [dbData, lang]);

  const toggleEditMode = (force) => {
    const newVal = typeof force === 'boolean' ? force : !isEditMode;
    setIsEditMode(newVal);
    if (newVal) {
      localStorage.setItem('munisys_admin', 'true');
    } else {
      localStorage.removeItem('munisys_admin');
    }
  };

  const togglePreviewMode = () => {
    setIsPreviewMode(prev => !prev);
  };

  // Fetch initial data from our API route
  useEffect(() => {
    fetch('/api/content?t=' + Date.now(), { cache: 'no-store', headers: { 'Cache-Control': 'no-cache' } })
      .then(res => res.json())
      .then(data => setDbData(data))
      .catch(err => console.error("Error loading CMS data:", err));
  }, []);

  // Update nested data locally (e.g. updateData(['homepage', 'heroTitle'], 'New Title'))
  const updateData = (pathArray, newValue) => {
    if (!dbData) return;
    
    // If the database has a language root, prepend the current language to the path, UNLESS it's a global config like 'theme'
    const isGlobal = pathArray[0] === 'theme';
    const actualPath = (dbData.en !== undefined && !isGlobal) ? [lang, ...pathArray] : pathArray;
    
    setDbData(prev => {
      const newData = { ...prev };
      let current = newData;
      for (let i = 0; i < actualPath.length - 1; i++) {
        current[actualPath[i]] = { ...current[actualPath[i]] };
        current = current[actualPath[i]];
      }
      current[actualPath[actualPath.length - 1]] = newValue;
      
      // INSTANT LIVE PREVIEW: Update CSS variables directly on the DOM
      const root = document.documentElement;
      if (actualPath[0] === 'theme' || (actualPath.length === 3 && actualPath[1] === 'theme')) {
        const key = actualPath[actualPath.length - 1];
        if (key === 'bordeaux') root.style.setProperty('--bordeaux', newValue);
        if (key === 'blueDark') root.style.setProperty('--blue-dark', newValue);
        if (key === 'blueLight') root.style.setProperty('--blue-light', newValue);
        if (key === 'bgGray') root.style.setProperty('--bg-gray', newValue);
        if (key === 'textGray') root.style.setProperty('--text-gray', newValue);
        if (key === 'buttonRadius') root.style.setProperty('--btn-radius', newValue);
        if (key === 'fontFamily') {
          const fontStr = newValue === 'Inter' ? "'Inter', sans-serif" 
                        : newValue === 'Playfair Display' ? "'Playfair Display', serif" 
                        : "'Montserrat', sans-serif";
          root.style.setProperty('--font-primary', fontStr);
          document.body.style.fontFamily = fontStr;
        }
      }
      
      return newData;
    });
    setHasUnsavedChanges(true);
  };

  const saveChanges = async () => {
    if (!dbData) return;
    setSaving(true);
    try {
      const res = await fetch('/api/content', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dbData)
      });
      if (res.ok) {
        setHasUnsavedChanges(false);
        alert('Changes saved successfully!');
      } else {
        alert('Failed to save changes.');
      }
    } catch (err) {
      console.error(err);
      alert('Error saving changes.');
    }
    setSaving(false);
  };

  const addLanguage = async (newLangCode) => {
    if (!dbData || !dbData.en) return;
    if (dbData[newLangCode]) {
      alert("La langue existe déjà !");
      return;
    }
    
    setTranslating(true);
    try {
      const res = await fetch('/api/translate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ targetLang: newLangCode })
      });
      
      const data = await res.json();
      if (data.success && data.db) {
        setDbData(data.db);
        alert(`La langue ${newLangCode.toUpperCase()} a été ajoutée et traduite avec succès !`);
      } else {
        alert("Erreur lors de la traduction : " + data.error);
      }
    } catch (err) {
      console.error(err);
      alert("Erreur réseau lors de la traduction.");
    }
    setTranslating(false);
  };

  const removeLanguage = (langCode) => {
    if (langCode === 'en' || langCode === 'fr') {
      alert("Vous ne pouvez pas supprimer les langues principales (EN, FR).");
      return;
    }
    if (confirm(`Êtes-vous sûr de vouloir supprimer la langue ${langCode.toUpperCase()} ?`)) {
      setDbData(prev => {
        const newData = { ...prev };
        delete newData[langCode];
        return newData;
      });
      if (lang === langCode) {
        changeLang('en');
      }
      setHasUnsavedChanges(true);
    }
  };

  return (
    <CMSContext.Provider value={{ 
      isEditMode, 
      isPreviewMode,
      toggleEditMode, 
      togglePreviewMode,
      dbData: dbData?.[lang] !== undefined ? dbData[lang] : dbData,
      fullDb: dbData,
      updateData, 
      saveChanges, 
      saving, 
      hasUnsavedChanges,
      lang,
      setLang: changeLang,
      addLanguage,
      removeLanguage,
      translating,
      viewportMode,
      setViewportMode
    }}>
      {(dbData?.[lang]?.theme || dbData?.theme) && (
        <style dangerouslySetInnerHTML={{
          __html: `
            :root {
              --bordeaux: ${(dbData?.[lang]?.theme || dbData?.theme).bordeaux || '#922b55'};
              --blue-dark: ${(dbData?.[lang]?.theme || dbData?.theme).blueDark || '#1d4e79'};
              --blue-light: ${(dbData?.[lang]?.theme || dbData?.theme).blueLight || '#83a5c2'};
              --bg-gray: ${(dbData?.[lang]?.theme || dbData?.theme).bgGray || '#fcfcfc'};
              --text-gray: ${(dbData?.[lang]?.theme || dbData?.theme).textGray || '#666666'};
              ${(dbData?.[lang]?.theme || dbData?.theme).fontFamily ? `--font-primary: ${
                (dbData?.[lang]?.theme || dbData?.theme).fontFamily === 'Inter' ? "'Inter', sans-serif"
                : (dbData?.[lang]?.theme || dbData?.theme).fontFamily === 'Playfair Display' ? "'Playfair Display', serif"
                : "'Montserrat', sans-serif"
              };` : ''}
            }
            html, body, #munisys-wrapper, #munisys-wrapper *, #munisys-global-header, #munisys-global-header * {
              font-family: var(--font-primary, 'Montserrat', sans-serif) !important;
            }
          `
        }} />
      )}
      <FloatingFormatToolbar />
      {children}
    </CMSContext.Provider>
  );
}

export function useCMS() {
  return useContext(CMSContext);
}
