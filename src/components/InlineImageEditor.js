"use client";

import React, { useRef, useState } from 'react';
import { useCMS } from './CMSProvider';

export default function InlineImageEditor({ dbPath, fallback, alt = '', style = {}, className = '' }) {
  const { isEditMode, isPreviewMode, dbData, updateData } = useCMS();
  const fileInputRef = useRef(null);
  const [uploading, setUploading] = useState(false);

  // Read image URL from dbData based on dbPath
  let src = fallback;
  if (dbData) {
    let val = dbData;
    for (const p of dbPath) {
      if (val && val[p] !== undefined) val = val[p];
      else { val = null; break; }
    }
    if (val) src = val;
  }

  // Clear broken URLs AFTER extracting from dbData
  if (!src || src.includes('munisys.ma') || src === 'https://www.munisys.ma/assets/images/partners/undefined') {
    src = ''; 
  }

  const handleUpload = async (e) => {
    const file = e.target.files?.[0];
    if (!file) return;

    setUploading(true);
    try {
      const formData = new FormData();
      formData.append('file', file);

      const res = await fetch('/api/upload', {
        method: 'POST',
        body: formData,
      });

      if (res.ok) {
        const data = await res.json();
        updateData(dbPath, data.url);
      } else {
        alert('Failed to upload image.');
      }
    } catch (err) {
      console.error('Upload error:', err);
      alert('Error uploading image.');
    }
    setUploading(false);
  };

  // Normal mode: just render the image
  if (!isEditMode || isPreviewMode) {
    if (!src) return null;
    return (
      <img
        src={src}
        alt={alt}
        className={className}
        style={{ ...style, display: 'block', maxWidth: '100%', minWidth: '120px' }}
      />
    );
  }

  // Edit mode: show upload UI
  return (
    <div
      className={src ? className : 'munisys-img-placeholder'}
      style={{
        ...style,
        position: 'relative',
        cursor: 'pointer',
        border: !src ? '2px dashed #922b55' : 'none',
        boxShadow: src ? '0 0 0 1px rgba(146, 43, 85, 0.2)' : 'none',
        borderRadius: src ? '4px' : '0',
        minHeight: !src ? '90px' : undefined,
        minWidth: '120px',
        maxHeight: !src ? 'none' : style.maxHeight,
        height: !src ? 'auto' : style.height,
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: !src ? 'rgba(146, 43, 85, 0.05)' : undefined,
        overflow: 'hidden'
      }}
      onClick={() => fileInputRef.current?.click()}
      title="Cliquez pour changer l'image"
    >
      {src ? (
        <img
          src={src}
          alt={alt}
          style={{ maxWidth: '100%', maxHeight: '100%', objectFit: 'contain' }}
        />
      ) : (
        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '8px', padding: '10px' }}>
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#922b55" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" style={{opacity: 0.5}}>
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <polyline points="21 15 16 10 5 21"/>
          </svg>
          {alt && (
            <span style={{ fontSize: '0.8rem', color: '#922b55', opacity: 0.8, textAlign: 'center', fontWeight: '500' }}>
              {alt}
            </span>
          )}
        </div>
      )}
      {/* Upload indicator */}
      {uploading && (
        <div style={{
          position: 'absolute',
          top: 0,
          left: 0,
          right: 0,
          bottom: 0,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          background: 'rgba(0,0,0,0.7)',
          zIndex: 10,
        }}>
          <span style={{ color: '#fff', fontSize: '1.2rem', fontWeight: 700 }}>
            ⏳ Téléchargement...
          </span>
        </div>
      )}

      {/* Hover effect */}
      <div
        className="img-edit-hover"
        style={{
          position: 'absolute',
          top: 0,
          left: 0,
          right: 0,
          bottom: 0,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          background: 'rgba(146, 43, 85, 0.75)',
          opacity: 0,
          transition: 'opacity 0.3s ease',
          pointerEvents: 'none',
        }}
        onMouseEnter={(e) => e.currentTarget.style.opacity = '1'}
        onMouseLeave={(e) => e.currentTarget.style.opacity = '0'}
      >
        <div style={{ 
          textAlign: 'center',
          color: '#fff',
          textShadow: '0 2px 4px rgba(0,0,0,0.5)'
        }}>
          <div style={{ fontSize: '3rem', marginBottom: '0.5rem' }}>📷</div>
          <div style={{ fontSize: '1.1rem', fontWeight: 700 }}>
            Cliquez pour changer
          </div>
        </div>
      </div>

      <input
        ref={fileInputRef}
        type="file"
        accept="image/*"
        onChange={handleUpload}
        style={{ display: 'none' }}
      />
    </div>
  );
}
