"use client";
import { useEffect, useState } from 'react';

let toastQueue = [];
let toastListeners = [];

// Simple global toast API — call window.__toast('msg', 'type') from anywhere
export function showToast(message, type = 'success') {
  const id = Date.now();
  const toast = { id, message, type };
  toastQueue = [...toastQueue, toast];
  toastListeners.forEach(fn => fn([...toastQueue]));
  // Auto-remove after 4s
  setTimeout(() => {
    toastQueue = toastQueue.filter(t => t.id !== id);
    toastListeners.forEach(fn => fn([...toastQueue]));
  }, 4000);
}

export default function ToastContainer() {
  const [toasts, setToasts] = useState([]);

  useEffect(() => {
    toastListeners.push(setToasts);
    // Expose globally for easy use from forms
    if (typeof window !== 'undefined') {
      window.__toast = showToast;
    }
    return () => {
      toastListeners = toastListeners.filter(fn => fn !== setToasts);
    };
  }, []);

  if (toasts.length === 0) return null;

  return (
    <div style={{
      position: 'fixed',
      bottom: '2rem',
      right: '2rem',
      zIndex: 10000,
      display: 'flex',
      flexDirection: 'column',
      gap: '0.75rem',
      pointerEvents: 'none',
    }}>
      {toasts.map(toast => (
        <div
          key={toast.id}
          role="alert"
          aria-live="polite"
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '0.75rem',
            padding: '1rem 1.5rem',
            borderRadius: '14px',
            background: toast.type === 'error'
              ? 'linear-gradient(135deg, #7f1d1d, #991b1b)'
              : 'linear-gradient(135deg, #1d4e79, #922b55)',
            color: '#fff',
            fontFamily: "'Outfit', 'Montserrat', sans-serif",
            fontWeight: 600,
            fontSize: '0.95rem',
            boxShadow: '0 8px 32px rgba(0,0,0,0.25)',
            pointerEvents: 'auto',
            backdropFilter: 'blur(12px)',
            animation: 'toast-in 0.4s cubic-bezier(0.19, 1, 0.22, 1)',
            maxWidth: '360px',
            lineHeight: 1.4,
          }}
        >
          {/* Icon */}
          {toast.type === 'error' ? (
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ flexShrink: 0 }}>
              <circle cx="12" cy="12" r="10" /><line x1="15" y1="9" x2="9" y2="15" /><line x1="9" y1="9" x2="15" y2="15" />
            </svg>
          ) : (
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ flexShrink: 0 }}>
              <circle cx="12" cy="12" r="10" /><path d="M8 12l3 3 5-6" />
            </svg>
          )}
          <span>{toast.message}</span>
        </div>
      ))}
      <style>{`
        @keyframes toast-in {
          from { opacity: 0; transform: translateY(20px) scale(0.95); }
          to   { opacity: 1; transform: translateY(0) scale(1); }
        }
      `}</style>
    </div>
  );
}
