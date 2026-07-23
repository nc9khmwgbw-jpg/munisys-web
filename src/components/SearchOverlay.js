"use client";

import React, { useState, useEffect, useRef } from 'react';
import { useCMS } from './CMSProvider';

export default function SearchOverlay({ isOpen, onClose }) {
  const { dbData } = useCMS();
  const [query, setQuery] = useState('');
  const [results, setResults] = useState([]);
  const inputRef = useRef(null);

  useEffect(() => {
    if (isOpen) {
      document.body.style.overflow = 'hidden';
      setTimeout(() => inputRef.current?.focus(), 100);
    } else {
      document.body.style.overflow = '';
      setQuery('');
      setResults([]);
    }
    return () => { document.body.style.overflow = ''; };
  }, [isOpen]);

  useEffect(() => {
    if (!query.trim() || !dbData) {
      setResults([]);
      return;
    }

    const q = query.trim().toLowerCase();
    const newResults = [];

    // Search Solutions
    if (dbData.solutions) {
      Object.entries(dbData.solutions).forEach(([slug, sol]) => {
        if ((sol.heroTitle && sol.heroTitle.toLowerCase().includes(q)) || 
            (sol.heroDesc && sol.heroDesc.toLowerCase().includes(q))) {
          newResults.push({
            type: 'Solution',
            title: sol.heroTitle || slug,
            desc: sol.heroDesc || '',
            link: `/solutions/${slug}`
          });
        }
      });
    }

    // Search Insights (posts)
    if (dbData.insights?.posts) {
      dbData.insights.posts.forEach(post => {
        if ((post.title && post.title.toLowerCase().includes(q)) || 
            (post.desc && post.desc.toLowerCase().includes(q)) ||
            (post.content && post.content.toLowerCase().includes(q))) {
          newResults.push({
            type: 'Actualité',
            title: post.title || 'Article',
            desc: post.desc || '',
            link: post.slug ? `/insights/${post.slug}` : `/insights`
          });
        }
      });
    }

    // Search Partners
    if (dbData.technologyPartners?.partners) {
      dbData.technologyPartners.partners.forEach(p => {
        if (p.name && p.name.toLowerCase().includes(q)) {
          newResults.push({
            type: 'Technology Partner',
            title: p.name,
            desc: p.category || '',
            link: `/technology-partners`
          });
        }
      });
    }

    // Search Customer References
    if (dbData.customerReferences?.references) {
      dbData.customerReferences.references.forEach(r => {
        if (r.name && r.name.toLowerCase().includes(q)) {
          newResults.push({
            type: 'Customer Reference',
            title: r.name,
            desc: r.sector || '',
            link: `/customer-references`
          });
        }
      });
    }

    // Search Static Pages
    const staticPages = [
      { key: 'aboutUs', url: '/about-us', name: 'About Us' },
      { key: 'careers', url: '/careers', name: 'Careers' },
      { key: 'services', url: '/services', name: 'Services' },
      { key: 'contact', url: '/contact', name: 'Contact' }
    ];

    staticPages.forEach(page => {
      const pageData = dbData[page.key];
      if (pageData) {
        // Search in title/description of the page
        const titleStr = pageData.heroTitle || pageData.heroMainTitle || page.name;
        const descStr = pageData.heroDesc || pageData.subline || '';
        
        if (titleStr.toLowerCase().includes(q) || descStr.toLowerCase().includes(q) || page.name.toLowerCase().includes(q)) {
          newResults.push({
            type: 'Page',
            title: titleStr,
            desc: descStr,
            link: page.url
          });
        }
      }
    });

    setResults(newResults);
  }, [query, dbData]);

  if (!isOpen) return null;

  return (
    <div style={{
      position: 'fixed', top: 0, left: 0, right: 0, bottom: 0,
      background: 'rgba(15, 23, 42, 0.4)', zIndex: 1000000,
      display: 'flex', justifyContent: 'center', alignItems: 'flex-start', padding: '12vh 20px',
      overflowY: 'auto', backdropFilter: 'blur(8px)'
    }} onClick={onClose}>
      
      <div 
        onClick={(e) => e.stopPropagation()}
        style={{ 
          maxWidth: '650px', width: '100%', 
          background: 'white', borderRadius: '16px',
          boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
          overflow: 'hidden', display: 'flex', flexDirection: 'column',
          maxHeight: '80vh', border: '1px solid rgba(0,0,0,0.1)'
        }}
      >
        <div style={{ position: 'relative', borderBottom: '1px solid rgba(0,0,0,0.08)', padding: '20px 24px', display: 'flex', alignItems: 'center' }}>
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" strokeWidth="2" style={{ marginRight: '16px' }}>
            <circle cx="11" cy="11" r="8" />
            <path d="M21 21l-4.35-4.35" />
          </svg>
          <input 
            ref={inputRef}
            type="text" 
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder="Search for solutions, insights, partners..."
            style={{
              flex: 1, fontSize: '1.2rem', border: 'none', background: 'transparent',
              color: '#0f172a', outline: 'none', fontFamily: "'Montserrat', sans-serif"
            }}
          />
          <button onClick={onClose} style={{ background: 'rgba(0,0,0,0.05)', border: 'none', borderRadius: '6px', padding: '4px 8px', fontSize: '0.75rem', color: '#64748b', cursor: 'pointer', fontWeight: 600 }}>ESC</button>
        </div>

        <div style={{ padding: '20px', overflowY: 'auto', background: '#f8fafc', flex: 1 }}>
          {query.trim() && results.length === 0 && (
            <p style={{ color: '#475569', fontSize: '1rem', textAlign: 'center', padding: '20px 0' }}>No results found for "{query}".</p>
          )}

          {!query.trim() && (
            <div style={{ padding: '20px 0', textAlign: 'center', color: '#94a3b8', fontSize: '0.9rem' }}>
              Type to start searching across the CMS...
            </div>
          )}

          <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
            {results.map((res, i) => (
              <a key={i} href={res.link} onClick={onClose} style={{
                display: 'block', textDecoration: 'none', padding: '16px',
                borderRadius: '12px', background: 'white',
                border: '1px solid rgba(0,0,0,0.05)', transition: 'background 0.2s, box-shadow 0.2s'
              }}
              onMouseOver={(e) => { e.currentTarget.style.background = '#f1f5f9'; e.currentTarget.style.boxShadow = '0 4px 12px rgba(0,0,0,0.05)'; }}
              onMouseOut={(e) => { e.currentTarget.style.background = 'white'; e.currentTarget.style.boxShadow = 'none'; }}
              >
                <span style={{ 
                  display: 'inline-block', fontSize: '0.7rem', textTransform: 'uppercase', 
                  fontWeight: 700, color: 'var(--bordeaux, #922b55)', marginBottom: '4px', letterSpacing: '0.5px'
                }}>
                  {res.type}
                </span>
                <h3 style={{ margin: '0 0 4px 0', color: '#0f172a', fontSize: '1.1rem' }}>{res.title}</h3>
                {res.desc && (
                  <p style={{ margin: 0, color: '#64748b', fontSize: '0.85rem', lineHeight: 1.4,
                    display: '-webkit-box', WebkitLineClamp: 1, WebkitBoxOrient: 'vertical', overflow: 'hidden'
                  }}>
                    {res.desc}
                  </p>
                )}
              </a>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
