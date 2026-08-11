"use client";

import { useCMS } from '@/components/CMSProvider';
import InlineEditor from '@/components/InlineEditor';
import ReadingProgressBar from '@/components/ReadingProgressBar';
import JsonLd from '@/components/JsonLd';
import Link from 'next/link';
import { useParams } from 'next/navigation';
import { useEffect, useState } from 'react';

export default function InsightArticlePage() {
    const params = useParams();
    const slug = params?.slug;
    const { dbData, isEditMode } = useCMS();
    const [localPost, setLocalPost] = useState(null);
    const [loading, setLoading] = useState(false);

    // Try to find in CMSProvider context first (available when navigating via Link)
    const cmsPosts = dbData?.insights?.posts || [];
    const cmsIdx = cmsPosts.findIndex(p => p.slug === slug);
    const cmsPost = cmsIdx >= 0 ? cmsPosts[cmsIdx] : null;

    // If not in context (e.g. hard refresh), fetch from API
    useEffect(() => {
        if (!cmsPost && slug) {
            setLoading(true);
            fetch('/api/content')
                .then(r => r.json())
                .then(data => {
                    const posts = data?.insights?.posts || [];
                    const found = posts.find(p => p.slug === slug);
                    setLocalPost(found || null);
                    setLoading(false);
                })
                .catch(() => setLoading(false));
        }
    }, [slug, cmsPost]);

    // Which post and source to use
    const post = cmsPost || localPost;
    // idx is only meaningful for InlineEditor when post is from CMS context
    const editIdx = cmsIdx >= 0 ? cmsIdx : null;
    const canEdit = isEditMode && editIdx !== null;

    if (loading) {
        return <div style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>Chargement...</div>;
    }

    if (!post) {
        return (
            <div style={{ minHeight: '60vh', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', gap: '1rem' }}>
                <h1 style={{ fontSize: '2rem', color: '#1d4e79' }}>Article introuvable</h1>
                <Link href="/insights" style={{ color: '#922b55', textDecoration: 'underline' }}>Retour aux actualités</Link>
            </div>
        );
    }

    const articleSchema = {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": post.title || "Article",
        "description": post.desc || "",
        "image": post.img || "https://www.munisys.net/og-image.jpg",
        "datePublished": post.date || new Date().toISOString(),
        "author": {
            "@type": "Organization",
            "name": "Munisys"
        },
        "publisher": {
            "@type": "Organization",
            "name": "Munisys",
            "logo": {
                "@type": "ImageObject",
                "url": "https://www.munisys.net/logo.png"
            }
        }
    };

    return (
        <div style={{ background: '#f8fafc', minHeight: '100vh', padding: '120px 20px 60px 20px' }}>
            <ReadingProgressBar />
            <JsonLd data={articleSchema} />
            <article style={{ maxWidth: '800px', margin: '0 auto', background: '#fff', borderRadius: '24px', padding: '40px', boxShadow: '0 10px 40px rgba(0,0,0,0.03)' }}>
                
                <Link href="/insights" style={{ display: 'inline-flex', alignItems: 'center', color: '#64748b', textDecoration: 'none', marginBottom: '2rem', fontSize: '0.9rem', fontWeight: 600, transition: 'color 0.2s' }} onMouseOver={(e) => e.target.style.color = '#1d4e79'} onMouseOut={(e) => e.target.style.color = '#64748b'}>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ marginRight: '0.5rem' }}>
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Retour
                </Link>

                <header style={{ marginBottom: '3rem' }}>
                    {canEdit ? (
                        <InlineEditor as="div" style={{ fontSize: '0.9rem', color: '#922b55', fontWeight: 800, textTransform: 'uppercase', letterSpacing: '1px', marginBottom: '1rem' }} dbPath={['insights', 'posts', editIdx, 'category']} fallback={post.category || 'Article'} />
                    ) : (
                        <div style={{ fontSize: '0.9rem', color: '#922b55', fontWeight: 800, textTransform: 'uppercase', letterSpacing: '1px', marginBottom: '1rem' }}>{post.category || 'Article'}</div>
                    )}
                    {canEdit ? (
                        <InlineEditor as="h1" style={{ fontSize: 'clamp(2rem, 4vw, 3.5rem)', fontWeight: 900, color: '#1d4e79', lineHeight: 1.1, marginBottom: '1.5rem' }} dbPath={['insights', 'posts', editIdx, 'title']} fallback={post.title || "Titre de l'article"} />
                    ) : (
                        <h1 style={{ fontSize: 'clamp(2rem, 4vw, 3.5rem)', fontWeight: 900, color: '#1d4e79', lineHeight: 1.1, marginBottom: '1.5rem' }}>{post.title || "Titre de l'article"}</h1>
                    )}
                    {canEdit ? (
                        <InlineEditor as="p" style={{ fontSize: '1.2rem', color: '#64748b', lineHeight: 1.6, borderLeft: '4px solid #922b55', paddingLeft: '1.5rem' }} dbPath={['insights', 'posts', editIdx, 'desc']} fallback={post.desc || 'Résumé...'} />
                    ) : (
                        <p style={{ fontSize: '1.2rem', color: '#64748b', lineHeight: 1.6, borderLeft: '4px solid #922b55', paddingLeft: '1.5rem' }}>{post.desc || ''}</p>
                    )}
                </header>

                {post.img && (
                    <div style={{ width: '100%', height: '400px', borderRadius: '16px', overflow: 'hidden', marginBottom: '4rem' }}>
                        <img src={post.img} alt={post.title} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                    </div>
                )}

                <div style={{ fontSize: '1.1rem', color: '#334155', lineHeight: 1.8, marginBottom: '4rem' }}>
                    {canEdit ? (
                        <InlineEditor as="div" style={{ whiteSpace: 'pre-wrap', fontFamily: 'var(--font-primary, sans-serif)' }} dbPath={['insights', 'posts', editIdx, 'content']} fallback={post.content || "Contenu de l'article..."} />
                    ) : (
                        <div style={{ whiteSpace: 'pre-wrap', fontFamily: 'var(--font-primary, sans-serif)' }}>{post.content || ''}</div>
                    )}
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
                            style={{ padding: '0.8rem 1.5rem', background: '#922b55', color: '#fff', borderRadius: '8px', textDecoration: 'none', fontWeight: 600, fontSize: '0.9rem' }}
                        >
                            Ouvrir le PDF
                        </a>
                    </div>
                )}
            </article>
        </div>
    );
}

