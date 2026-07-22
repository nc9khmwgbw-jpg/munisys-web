"use client";

import React, { useState, useRef, useEffect } from 'react';
import { useMunisysContent } from '@/hooks/useMunisysContent';

const defaultKeys = {
  hero_tagline:   'Your IT partner for tomorrow',
  hero_line1:     'We design',
  hero_line2:     'architectures & integrations',
  hero_line3:     'with your future IT in mind',
  hero_cta:       'Discover our solutions',
  services_title: 'Our solutions at a glance',
  services_desc:  'We help organisations design, integrate, and optimise their IT ecosystems.',
  about_title:    'Building the IT backbone of tomorrow',
  about_desc:     'For over 20 years, Munisys has been supporting large enterprises across Morocco and Africa.',
};

export default function AdminContentEditor({ pageName = 'home' }) {
  const { content, isLoading, refreshContent } = useMunisysContent(pageName);

  // Initialize with default values immediately — no infinite loop
  const [formData, setFormData] = useState({ ...defaultKeys });
  const [isSaving, setIsSaving] = useState(false);
  const [saveStatus, setSaveStatus] = useState(null);

  // Only merge content once when it arrives from the DB (not on every render)
  const mergedRef = useRef(false);
  useEffect(() => {
    if (!mergedRef.current && content && Object.keys(content).length > 0) {
      setFormData(prev => ({ ...prev, ...content }));
      mergedRef.current = true;
    }
  }, [content]);

  const handleSave = async (key) => {
    setIsSaving(true);
    setSaveStatus({ msg: 'Sauvegarde en cours…', type: 'info' });
    try {
      const res = await fetch('/api/admin/munisys/content', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ key, value: formData[key] }),
      });
      const r = await res.json();
      if (!r.success) throw new Error(r.message);
      setSaveStatus({ msg: '✓ Modification enregistrée !', type: 'success' });
      mergedRef.current = false; // allow re-merge after next fetch
      refreshContent();
    } catch {
      setSaveStatus({ msg: '✗ Erreur lors de la sauvegarde.', type: 'error' });
    } finally {
      setIsSaving(false);
      setTimeout(() => setSaveStatus(null), 3000);
    }
  };

  const isChanged = (key) => formData[key] !== (content[key] || defaultKeys[key]);

  if (isLoading) return <p style={{ padding: '2rem', color: '#718096' }}>Chargement…</p>;

  return (
    <div className="mn-editor-box">
      <div className="mn-editor-title">
        Éditeur de contenu
        <span> — page {pageName}</span>
        <p className="mn-editor-desc">Modifiez les textes et cliquez "Enregistrer" pour mettre à jour le site.</p>
      </div>

      {saveStatus && (
        <div className={`mn-status ${saveStatus.type}`}>{saveStatus.msg}</div>
      )}

      {Object.keys(formData).map(key => (
        <div key={key} className={`mn-field-wrap${isChanged(key) ? ' dirty' : ''}`}>
          <label className="mn-field-label">
            {key.replace(/_/g, ' ')}
            <code className="mn-field-key">{key}</code>
          </label>
          <textarea
            className="mn-textarea"
            value={formData[key]}
            rows={key.includes('desc') || key.includes('line2') ? 3 : 2}
            onChange={e => setFormData(p => ({ ...p, [key]: e.target.value }))}
          />
          <div style={{ display: 'flex', justifyContent: 'flex-end', marginTop: '.75rem' }}>
            <button
              className="mn-save-btn"
              onClick={() => handleSave(key)}
              disabled={isSaving || !isChanged(key)}
            >
              {isSaving ? 'Enregistrement…' : 'Enregistrer'}
            </button>
          </div>
        </div>
      ))}
    </div>
  );
}
