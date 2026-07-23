"use client";

import { useCMS } from '@/components/CMSProvider';
import InlineEditor from '@/components/InlineEditor';
import ReadingProgressBar from '@/components/ReadingProgressBar';
import Link from 'next/link';
import { useParams } from 'next/navigation';
import { useEffect, useState } from 'react';

export default function InsightArticlePage() {
    const params = useParams();
    const slug = params?.slug;
    const { dbData } = useCMS();
    const [localData, setLocalData] = useState(null);
    const [loading, setLoading] = useState(false);

    // Try to find in CMSProvider context first; if not found, fetch directly from API
    const cmsPost = dbData?.insights?.posts?.find(p => p.slug === slug);
    const cmsPosts = dbData?.insights?.posts || [];
    const cmsIdx = cmsPosts.findIndex(p => p.slug === slug);

    useEffect(() => {
        if (!cmsPost) {
            // Article not in context - fetch fresh from API
            setLoading(true);
            fetch('/api/content')
                .then(r => r.json())
                .then(data => {
                    const insightsPosts = data?.insights?.posts || [];
                    const found = insightsPosts.find(p => p.slug === slug);
                    if (found) {
                        setLocalData({ post: found, idx: insightsPosts.findIndex(p => p.slug === slug), posts: insightsPosts });
                    }
                    setLoading(false);
                })
                .catch(() => setLoading(false));
        }
    }, [slug, cmsPost]);

    const post = cmsPost || localData?.post;
    const idx = cmsPost ? cmsIdx : (localData?.idx ?? -1);

    if (loading) {
        return (
            <div style={{ minHeight: '60vh', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <div style={{ width: 40, height: 40, border: '3px solid #e2e8f0', borderTop: '3px solid #922b55', borderRadius: '50%', animation: 'spin 1s linear infinite' }} />
                <style>{`@keyframes spin { to { transform: rotate(360deg); } }`}</style>
            </div>
        );
    }

    if (!post) {
        return (
            <div style={{ minHeight: '60vh', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center' }}>
                <h2>Article introuvable</h2>
                <Link href="/insights" style={{ color: '#1d4e79', textDecoration: 'underline', marginTop: '1rem' }}>
                    Retour aux actualités
                </Link>
            </div>
        );
    }


    return (
        <article style={{ background: '#ffffff', minHeight: '100vh', paddingTop: '6rem' }}>
            <ReadingProgressBar />
            
            <div style={{ maxWidth: '800px', margin: '0 auto', padding: '4rem 5%' }}>
                <Link href="/insights" style={{ display: 'inline-flex', alignItems: 'center', color: '#64748b', fontSize: '0.9rem', marginBottom: '3rem', textDecoration: 'none', transition: 'color 0.2s' }}>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ marginRight: '0.5rem' }}>
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Retour
                </Link>

                <header style={{ marginBottom: '3rem' }}>
                    <InlineEditor 
                        as="div" 
                        style={{ fontSize: '0.9rem', color: '#922b55', fontWeight: 800, textTransform: 'uppercase', letterSpacing: '1px', marginBottom: '1rem' }} 
                        dbPath={['insights', 'posts', idx, 'category']} 
                        fallback={post.category || 'Article'} 
                    />
                    <InlineEditor 
                        as="h1" 
                        style={{ fontSize: 'clamp(2rem, 4vw, 3.5rem)', fontWeight: 900, color: '#1d4e79', lineHeight: 1.1, marginBottom: '1.5rem' }}
                        dbPath={['insights', 'posts', idx, 'title']} 
                        fallback={post.title || "Titre de l'article"} 
                    />
                    <InlineEditor 
                        as="p" 
                        style={{ fontSize: '1.2rem', color: '#64748b', lineHeight: 1.6, borderLeft: '4px solid #922b55', paddingLeft: '1.5rem' }}
                        dbPath={['insights', 'posts', idx, 'desc']} 
                        fallback={post.desc || 'Résumé...'} 
                    />
                </header>

                {post.img && (
                    <div style={{ width: '100%', height: '400px', borderRadius: '16px', overflow: 'hidden', marginBottom: '4rem' }}>
                        <img src={post.img} alt={post.title} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                    </div>
                )}

                <div style={{ fontSize: '1.1rem', color: '#334155', lineHeight: 1.8, marginBottom: '4rem' }}>
                    <InlineEditor 
                        as="div" 
                        style={{ whiteSpace: 'pre-wrap', fontFamily: 'var(--font-primary, sans-serif)' }}
                        dbPath={['insights', 'posts', idx, 'content']} 
                        fallback={post.content || "Contenu de l'article..."} 
                    />
                </div>

                {post.pdfUrl && (
                    <div style={{ padding: '2rem', background: '#f8f9fa', borderRadius: '12px', display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: '1rem' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#e11d48" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <div>
                                <h4 style={{ margin: 0, color: '#1d4e79', fontSize: '1rem', fontWeight: 700 }}>Version originale</h4>
                                <p style={{ margin: 0, color: '#64748b', fontSize: '0.85rem' }}>Téléchargez le document PDF complet</p>
                            </div>
                        </div>
                        <a 
                            href={post.pdfUrl} 
                            target="_blank" 
                            rel="noopener noreferrer"
                            style={{ padding: '0.8rem 1.5rem', background: '#922b55', color: '#fff', borderRadius: '8px', textDecoration: 'none', fontWeight: 600, fontSize: '0.9rem', transition: 'background 0.2s' }}
                            onMouseOver={(e) => e.target.style.background = '#7a2245'}
                            onMouseOut={(e) => e.target.style.background = '#922b55'}
                        >
                            Ouvrir le PDF
                        </a>
                    </div>
                )}
            </div>
        </article>
    );
}
