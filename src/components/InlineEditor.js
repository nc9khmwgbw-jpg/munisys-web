"use client";

import React, { useState, useEffect, useRef } from 'react';
import { useCMS } from './CMSProvider';


// CSS inject for hover states
if (typeof document !== 'undefined') {
  const style = document.createElement('style');
  style.innerHTML = `
    .munisys-editable {
      transition: all 0.2s ease;
    }
    .munisys-editable:hover {
      background: rgba(146, 43, 85, 0.1) !important;
      outline: 2px solid var(--bordeaux) !important;
      z-index: 10;
    }
    .munisys-editable-img:hover {
      outline: 4px solid var(--bordeaux) !important;
      opacity: 0.8;
    }
  `;
  document.head.appendChild(style);
}

export default function InlineEditor({ dbPath, as: Component = 'span', className, style, placeholder, fallback }) {
  const { isEditMode, isPreviewMode, dbData, updateData, lang } = useCMS();
  const [content, setContent] = useState(() => {
    if (!dbData) return fallback || '';
    let val = dbData;
    for (const p of dbPath) {
      if (val && val[p] !== undefined) val = val[p];
      else { val = null; break; }
    }
    return val || fallback || '';
  });
  const elementRef = useRef(null);
  const [toolbarVisible, setToolbarVisible] = useState(false);
  const [toolbarPosition, setToolbarPosition] = useState({ top: 0, left: 0 });
  const [isFocused, setIsFocused] = useState(false);

  const checkSelection = () => {
    const selection = window.getSelection();
    if (selection.rangeCount > 0 && !selection.isCollapsed && elementRef.current && elementRef.current.contains(selection.anchorNode)) {
      const range = selection.getRangeAt(0);
      const rect = range.getBoundingClientRect();
      setToolbarPosition({
        top: rect.top - 40,
        left: rect.left + rect.width / 2 - 50,
      });
      setToolbarVisible(true);
    } else {
      setToolbarVisible(false);
    }
  };

  const execFormat = (e, cmd) => {
    e.preventDefault();
    document.execCommand(cmd, false, null);
    if (elementRef.current) {
      const newText = elementRef.current.innerHTML || '';
      updateData(dbPath, newText);
    }
  };

  // Read data from dbData safely based on dbPath array (e.g. ['homepage', 'heroTitle'])
  useEffect(() => {
    if (!dbData) return;
    let val = dbData;
    for (const p of dbPath) {
      if (val && val[p] !== undefined) val = val[p];
      else { val = null; break; }
    }
    setContent(val || fallback || '');
  }, [dbData, dbPath, fallback]);

  if (!isEditMode || isPreviewMode) {
    const safeHtml = (content !== null && content !== undefined) ? String(content) : (fallback ? String(fallback) : '');
    return (
      <Component 
        key={lang}
        className={`${className || ''} munisys-editable`} 
        style={style} 
        dangerouslySetInnerHTML={{ __html: safeHtml }} 
      />
    );
  }

  const handleBlur = () => {
    setIsFocused(false);
    if (!elementRef.current) return;
    const newText = elementRef.current.innerHTML || '';
    if (newText !== content) {
      updateData(dbPath, newText);
    }
  };

  return (
    <>
    <Component
      key={lang}
      ref={elementRef}
      className={`munisys-editable ${className || ''}`}
      style={{ 
        ...style,
        cursor: isEditMode ? 'text' : 'inherit',
        position: 'relative',
        minHeight: '1em',
        minWidth: '1em',
        outline: 'none',
        boxShadow: isEditMode && !isFocused ? '0 0 0 1px rgba(146, 43, 85, 0.2)' : 'none',
        borderRadius: '2px'
      }}
      contentEditable={isEditMode}
      suppressContentEditableWarning={true}
      onBlur={handleBlur}
      onFocus={(e) => {
         setIsFocused(true);
         // Select all text when focused for easy replacing
         const range = document.createRange();
         range.selectNodeContents(e.target);
         const sel = window.getSelection();
         sel.removeAllRanges();
         sel.addRange(range);
      }}
      onKeyDown={(e) => {
        if (e.key === 'Enter' && Component !== 'div' && Component !== 'p') {
          e.preventDefault();
          e.target.blur();
        }
      }}
      onMouseUp={checkSelection}
      onKeyUp={checkSelection}
      onClick={(e) => {
        e.preventDefault();
        e.stopPropagation();
      }}
      title="Double-click or type to edit. Press Enter or click outside to apply."
      dangerouslySetInnerHTML={{ __html: (content !== null && content !== undefined) ? String(content) : (placeholder ? String(placeholder) : 'Type here...') }}
    />
    {toolbarVisible && (
      <div style={{
        position: 'fixed',
        top: toolbarPosition.top,
        left: toolbarPosition.left,
        zIndex: 999999,
        background: '#1d4e79',
        padding: '4px',
        borderRadius: '8px',
        display: 'flex',
        gap: '4px',
        boxShadow: '0 4px 12px rgba(0,0,0,0.15)'
      }}>
        <button onMouseDown={(e) => execFormat(e, 'bold')} style={{ color: 'white', fontWeight: 'bold', padding: '4px 8px' }}>B</button>
        <button onMouseDown={(e) => execFormat(e, 'italic')} style={{ color: 'white', fontStyle: 'italic', padding: '4px 8px' }}>I</button>
        <button onMouseDown={(e) => execFormat(e, 'underline')} style={{ color: 'white', textDecoration: 'underline', padding: '4px 8px' }}>U</button>
        <button onMouseDown={(e) => execFormat(e, 'removeFormat')} style={{ color: 'white', padding: '4px 8px', fontSize: '12px' }}>Tx</button>
      </div>
    )}
    </>
  );
}

export function InlineListEditor({ dbPath, fallback = [] }) {
  const { isEditMode, isPreviewMode, dbData, updateData, lang } = useCMS();
  const [list, setList] = useState(() => {
    if (!dbData) return fallback || [];
    let val = dbData;
    for (const p of dbPath) {
      if (val && val[p] !== undefined) val = val[p];
      else { val = null; break; }
    }
    return val || fallback || [];
  });
  const elementRef = useRef(null);
  const dragIdx = useRef(null);

  useEffect(() => {
    if (!dbData) return;
    let val = dbData;
    for (const p of dbPath) {
      if (val && val[p] !== undefined) val = val[p];
      else { val = null; break; }
    }
    setList(val || fallback || []);
  }, [dbData, dbPath, fallback]);

  const handleBlur = () => {
    if (!elementRef.current) return;
    const newText = elementRef.current.innerText || '';
    const newList = newText.split('\n').map(s => s.trim()).filter(s => s !== '');
    
    // Compare arrays
    if (JSON.stringify(newList) !== JSON.stringify(list)) {
      updateData(dbPath, newList);
    }
  };

  const handleDragStart = (idx) => {
    dragIdx.current = idx;
  };

  const handleDragOver = (e, idx) => {
    e.preventDefault();
    if (dragIdx.current === null || dragIdx.current === idx) return;
    const newList = [...list];
    const [moved] = newList.splice(dragIdx.current, 1);
    newList.splice(idx, 0, moved);
    dragIdx.current = idx;
    setList(newList);
  };

  const handleDrop = () => {
    dragIdx.current = null;
    updateData(dbPath, list);
  };

  if (!isEditMode || isPreviewMode) {
    return (
      <React.Fragment key={lang}>
        {list.map((item, idx) => (
          <div key={idx} style={dbPath.includes('expertises') ? {display: 'flex', gap: '1rem', alignItems: 'flex-start'} : {display: 'flex', alignItems: 'center', gap: '0.5rem'}}>
            {dbPath.includes('expertises') ? (
               <><div style={{color: 'var(--blue-light)', marginTop: '4px'}}>
                 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
               </div>
               <div><h4 style={{fontSize: '1.1rem', fontWeight: 600, color: 'var(--blue-dark)'}}>{item}</h4></div></>
            ) : (
               <><span style={{width: '6px', height: '6px', background: 'var(--blue-light)', borderRadius: '50%', display: 'inline-block'}}></span><li>{item}</li></>
            )}
          </div>
        ))}
      </React.Fragment>
    );
  }

  return (
    <div key={lang} style={{ display: 'flex', flexDirection: 'column', gap: '8px', padding: '16px', background: 'rgba(255,255,255,0.05)', borderRadius: '8px', outline: '2px dashed var(--bordeaux)' }}>
      {list.map((item, idx) => (
        <div
          key={idx}
          draggable
          onDragStart={() => handleDragStart(idx)}
          onDragOver={(e) => handleDragOver(e, idx)}
          onDrop={handleDrop}
          style={{ display: 'flex', alignItems: 'center', gap: '8px', background: 'rgba(0,0,0,0.1)', padding: '8px', borderRadius: '4px', cursor: 'grab' }}
        >
          {/* Drag Handle */}
          <div style={{ color: '#64748b', cursor: 'grab', flexShrink: 0, display: 'flex', alignItems: 'center' }} title="Glisser pour réordonner">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
              <circle cx="9" cy="6" r="1.5"/><circle cx="9" cy="12" r="1.5"/><circle cx="9" cy="18" r="1.5"/>
              <circle cx="15" cy="6" r="1.5"/><circle cx="15" cy="12" r="1.5"/><circle cx="15" cy="18" r="1.5"/>
            </svg>
          </div>
          <div
            style={{ flex: 1, minHeight: '24px', outline: 'none', color: '#000', background: 'white', padding: '4px 8px', borderRadius: '4px', cursor: 'text' }}
            contentEditable={true}
            suppressContentEditableWarning={true}
            onDragStart={(e) => e.stopPropagation()}
            onBlur={(e) => {
              const newList = [...list];
              newList[idx] = e.target.innerText.trim();
              if (JSON.stringify(newList) !== JSON.stringify(list)) {
                updateData(dbPath, newList);
              }
            }}
          >
            {item}
          </div>
          <button 
            onClick={() => {
              const newList = [...list];
              newList.splice(idx, 1);
              updateData(dbPath, newList);
            }}
            style={{ background: 'none', border: 'none', color: '#f43f5e', cursor: 'pointer', padding: '4px', flexShrink: 0 }}
            title="Supprimer l'élément"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
          </button>
        </div>
      ))}
      <button 
        onClick={() => {
          updateData(dbPath, [...list, "Nouvel élément"]);
        }}
        style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '4px', padding: '8px', background: 'rgba(56, 189, 248, 0.1)', color: '#38bdf8', border: '1px dashed #38bdf8', borderRadius: '4px', cursor: 'pointer', marginTop: '8px' }}
      >
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Ajouter un élément
      </button>
    </div>
  );
}

export function InlineImageEditor({ dbPath, fallback, className, style, alt, styleImg, children }) {
  const { isEditMode, isPreviewMode, dbData, updateData, lang } = useCMS();
  const [uploading, setUploading] = useState(false);
  
  const [content, setContent] = useState(() => {
    if (!dbData) return fallback || '';
    let val = dbData;
    for (const p of dbPath) {
      if (val && val[p] !== undefined) val = val[p];
      else { val = null; break; }
    }
    return val || fallback || '';
  });

  useEffect(() => {
    if (!dbData) return;
    let val = dbData;
    for (const p of dbPath) {
      if (val && val[p] !== undefined) val = val[p];
      else { val = null; break; }
    }
    setContent(val || fallback || '');
  }, [dbData, dbPath, fallback]);

  const handleFileChange = async (e) => {
    const file = e.target.files[0];
    if (!file) return;

    setUploading(true);
    const formData = new FormData();
    formData.append('file', file);

    try {
      const res = await fetch('/api/upload', {
        method: 'POST',
        body: formData,
      });
      const data = await res.json();
      if (data.success) {
        updateData(dbPath, data.url);
      } else {
        alert("Erreur d'upload");
      }
    } catch (err) {
      alert("Erreur d'upload");
    } finally {
      setUploading(false);
    }
  };

  if (!isEditMode || isPreviewMode) {
    if (children) {
      return children(content);
    }
    return <img src={content} alt={alt || ''} className={`${className || ''} munisys-editable-img`} style={styleImg || style} />;
  }

  return (
    <div 
      className={`${className || ''} munisys-editable-img`} 
      style={{
        position: 'relative',
        boxShadow: '0 0 0 1px rgba(146, 43, 85, 0.2)',
        borderRadius: '4px',
        display: 'inline-block',
        ...style
      }}
      onClick={(e) => {
        e.preventDefault();
        e.stopPropagation();
      }}
    >
      {children ? (
        children(content)
      ) : (
        <img src={content} alt={alt || ''} style={{ ...(styleImg || style), opacity: uploading ? 0.5 : 1 }} />
      )}
      <label 
        title="Cliquez pour changer l'image"
        style={{ 
          position: 'absolute', 
          top: '16px', 
          right: '16px', 
          width: '40px', 
          height: '40px', 
          borderRadius: '50%', 
          background: 'var(--bordeaux)', 
          color: 'white', 
          display: 'flex', 
          alignItems: 'center', 
          justifyContent: 'center', 
          cursor: 'pointer', 
          boxShadow: '0 4px 12px rgba(0,0,0,0.5)',
          zIndex: 20
        }}
        onClick={(e) => e.stopPropagation()}
      >
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path><path d="m15 5 4 4"></path></svg>
        <input type="file" style={{ display: 'none' }} accept="image/*" onChange={handleFileChange} disabled={uploading} />
      </label>
      {uploading && (
        <div style={{ position: 'absolute', inset: 0, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(255,255,255,0.7)', zIndex: 10 }}>
          <span style={{ fontWeight: 'bold', color: 'var(--bordeaux)' }}>...</span>
        </div>
      )}
    </div>
  );
}

export function ArrayAddButton({ dbPath, defaultItem, label = "+ Ajouter un élément" }) {
  const { isEditMode, addArrayItem } = useCMS();
  if (!isEditMode) return null;
  return (
    <button 
      onClick={() => addArrayItem(dbPath, defaultItem)}
      style={{
        marginTop: '1rem',
        padding: '0.5rem 1rem',
        background: 'rgba(56, 189, 248, 0.1)',
        color: '#0284c7',
        border: '2px dashed #38bdf8',
        borderRadius: '8px',
        cursor: 'pointer',
        fontWeight: 'bold',
        display: 'inline-flex',
        alignItems: 'center',
        gap: '0.5rem',
        width: '100%',
        justifyContent: 'center',
        transition: 'all 0.2s'
      }}
      onMouseOver={e => e.currentTarget.style.background = 'rgba(56, 189, 248, 0.2)'}
      onMouseOut={e => e.currentTarget.style.background = 'rgba(56, 189, 248, 0.1)'}
    >
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
      {label}
    </button>
  );
}

export function ArrayRemoveButton({ dbPath, index }) {
  const { isEditMode, removeArrayItem } = useCMS();
  if (!isEditMode) return null;
  return (
    <button 
      onClick={(e) => {
        e.preventDefault();
        e.stopPropagation();
        if (confirm('Voulez-vous vraiment supprimer cet élément ?')) {
          removeArrayItem(dbPath, index);
        }
      }}
      style={{
        position: 'absolute',
        top: '-10px',
        right: '-10px',
        width: '28px',
        height: '28px',
        background: '#ef4444',
        color: 'white',
        border: '2px solid white',
        borderRadius: '50%',
        cursor: 'pointer',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        zIndex: 100,
        boxShadow: '0 2px 5px rgba(0,0,0,0.2)'
      }}
      title="Supprimer cet élément"
    >
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button>
  );
}
