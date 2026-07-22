"use client";

import React, { useState, useEffect, useRef } from 'react';
import { useCMS } from './CMSProvider';

export default function FloatingFormatToolbar() {
  const { isEditMode, isPreviewMode } = useCMS();
  const [position, setPosition] = useState(null);
  const [isVisible, setIsVisible] = useState(false);
  const [activeFormats, setActiveFormats] = useState({ bold: false, italic: false, underline: false });
  const toolbarRef = useRef(null);

  useEffect(() => {
    if (!isEditMode || isPreviewMode) {
      setIsVisible(false);
      return;
    }

    const handleSelectionChange = () => {
      const selection = window.getSelection();
      if (!selection || selection.rangeCount === 0 || selection.isCollapsed) {
        setIsVisible(false);
        return;
      }

      // Check if selection is inside a contenteditable element
      let node = selection.anchorNode;
      let isEditable = false;
      while (node) {
        if (node.isContentEditable) {
          isEditable = true;
          break;
        }
        node = node.parentNode;
      }

      if (!isEditable) {
        setIsVisible(false);
        return;
      }

      const range = selection.getRangeAt(0);
      const rect = range.getBoundingClientRect();
      
      // Position above the selection
      setPosition({
        top: rect.top + window.scrollY - 52,
        left: rect.left + window.scrollX + (rect.width / 2)
      });

      // Detect active formats
      setActiveFormats({
        bold: document.queryCommandState('bold'),
        italic: document.queryCommandState('italic'),
        underline: document.queryCommandState('underline'),
      });

      setIsVisible(true);
    };

    document.addEventListener('selectionchange', handleSelectionChange);
    return () => document.removeEventListener('selectionchange', handleSelectionChange);
  }, [isEditMode, isPreviewMode]);

  if (!isVisible || !position) return null;

  const handleFormat = (command, value = null) => {
    document.execCommand(command, false, value);
    setActiveFormats(prev => ({ ...prev, [command]: !prev[command] }));
  };

  const handleLink = () => {
    const url = prompt('URL du lien (ex: https://munisys.ma) :');
    if (url) {
      document.execCommand('createLink', false, url);
    }
  };

  const btnStyle = (active) => ({
    background: active ? 'rgba(255,255,255,0.25)' : 'none',
    border: 'none',
    color: 'white',
    cursor: 'pointer',
    padding: '6px 10px',
    borderRadius: '6px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    transition: 'background 0.15s',
    minWidth: '32px',
    height: '32px',
  });

  return (
    <div
      ref={toolbarRef}
      className="munisys-floating-toolbar"
      style={{
        position: 'absolute',
        top: position.top,
        left: position.left,
        transform: 'translateX(-50%)',
        background: 'linear-gradient(135deg, #1d4e79 0%, #0f172a 100%)',
        color: 'white',
        padding: '6px 8px',
        borderRadius: '10px',
        boxShadow: '0 8px 24px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.1)',
        display: 'flex',
        gap: '2px',
        zIndex: 9999998,
        pointerEvents: 'auto',
        alignItems: 'center',
      }}
      onMouseDown={(e) => {
        // Prevent clicking buttons from removing the selection
        e.preventDefault();
      }}
    >
      {/* Arrow */}
      <div style={{
        position: 'absolute',
        bottom: '-6px',
        left: '50%',
        transform: 'translateX(-50%)',
        width: 0,
        height: 0,
        borderLeft: '6px solid transparent',
        borderRight: '6px solid transparent',
        borderTop: '6px solid #0f172a',
      }} />

      <button
        onClick={() => handleFormat('bold')}
        style={btnStyle(activeFormats.bold)}
        title="Gras (Ctrl+B)"
      >
        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
          <path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/>
          <path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/>
        </svg>
      </button>

      <button
        onClick={() => handleFormat('italic')}
        style={btnStyle(activeFormats.italic)}
        title="Italique (Ctrl+I)"
      >
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round">
          <line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/>
        </svg>
      </button>

      <button
        onClick={() => handleFormat('underline')}
        style={btnStyle(activeFormats.underline)}
        title="Souligné (Ctrl+U)"
      >
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round">
          <path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"/><line x1="4" y1="21" x2="20" y2="21"/>
        </svg>
      </button>

      {/* Separator */}
      <div style={{ width: '1px', height: '20px', background: 'rgba(255,255,255,0.2)', margin: '0 4px' }} />

      <button
        onClick={handleLink}
        style={btnStyle(false)}
        title="Ajouter un lien"
      >
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
          <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
        </svg>
      </button>

      <button
        onClick={() => handleFormat('removeFormat')}
        style={btnStyle(false)}
        title="Effacer le formatage"
      >
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M4 7h16"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12"/>
          <path d="M9 7V4h6v3"/>
        </svg>
      </button>
    </div>
  );
}
