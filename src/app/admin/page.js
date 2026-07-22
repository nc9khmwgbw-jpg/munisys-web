"use client";

import { useState } from 'react';

export default function AdminLoginPage() {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  return (
    <div style={{ 
      minHeight: '80vh', 
      display: 'flex', 
      alignItems: 'center', 
      justifyContent: 'center', 
      background: '#f8fafc',
      padding: '2rem'
    }}>
      <div style={{ 
        width: '100%', 
        maxWidth: '450px', 
        background: '#fff', 
        padding: '3rem', 
        borderRadius: '16px', 
        boxShadow: '0 10px 40px rgba(0,0,0,0.08)', 
        border: '1px solid #e2e8f0' 
      }}>
        <div style={{ textAlign: 'center', marginBottom: '2rem' }}>
          <h2 style={{ fontSize: '1.8rem', fontWeight: 800, color: '#1d4e79', margin: 0 }}>Espace Administration</h2>
          <p style={{ fontSize: '0.95rem', color: '#64748b', marginTop: '0.5rem' }}>
            Connectez-vous pour activer l'éditeur visuel
          </p>
        </div>

        <form onSubmit={async (e) => {
          e.preventDefault();
          setError(''); 
          setLoading(true);
          try {
            const r = await fetch('/api/admin/login', { 
              method: 'POST', 
              headers: { 'Content-Type': 'application/json' }, 
              body: JSON.stringify({ username, password }) 
            });
            const d = await r.json();
            if (r.ok && d.success) { 
              window.location.href = '/'; 
            } else { 
              setError(d.message || 'Identifiants incorrects'); 
            }
          } catch { 
            setError('Erreur de connexion'); 
          } finally { 
            setLoading(false); 
          }
        }} style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
          
          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem' }}>
            <label style={{ fontSize: '0.9rem', fontWeight: 600, color: '#334155' }}>Nom d'utilisateur</label>
            <input 
              type="text" 
              value={username} 
              onChange={e => setUsername(e.target.value)} 
              placeholder="admin" 
              required 
              autoFocus 
              disabled={loading} 
              style={{ 
                padding: '1rem', 
                fontSize: '1rem', 
                border: '2px solid #e2e8f0', 
                borderRadius: '8px', 
                outline: 'none',
                transition: 'border-color 0.2s'
              }} 
            />
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem' }}>
            <label style={{ fontSize: '0.9rem', fontWeight: 600, color: '#334155' }}>Mot de passe</label>
            <input 
              type="password" 
              value={password} 
              onChange={e => setPassword(e.target.value)} 
              placeholder="••••••••" 
              required 
              disabled={loading} 
              style={{ 
                padding: '1rem', 
                fontSize: '1rem', 
                border: '2px solid #e2e8f0', 
                borderRadius: '8px', 
                outline: 'none',
                transition: 'border-color 0.2s'
              }} 
            />
          </div>

          {error && (
            <div style={{ 
              display: 'flex', alignItems: 'center', gap: '0.5rem', 
              background: '#fee2e2', border: '1px solid #fca5a5', 
              padding: '1rem', borderRadius: '8px', color: '#991b1b', fontSize: '0.9rem' 
            }}>
              <span>{error}</span>
            </div>
          )}

          <button 
            type="submit" 
            disabled={loading} 
            style={{ 
              padding: '1rem', 
              background: loading ? '#94a3b8' : '#922b55', 
              color: 'white', 
              border: 'none', 
              borderRadius: '8px', 
              fontSize: '1.05rem', 
              fontWeight: 700, 
              cursor: loading ? 'not-allowed' : 'pointer',
              marginTop: '0.5rem',
              transition: 'background 0.2s'
            }}>
            {loading ? '⏳ Connexion...' : '🔐 Activer l\'éditeur'}
          </button>
        </form>
      </div>
    </div>
  );
}