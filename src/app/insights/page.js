"use client";
import { useState, useRef } from 'react';
import { useCMS } from '@/components/CMSProvider';
import InlineEditor from '@/components/InlineEditor';
import ReadingProgressBar from '@/components/ReadingProgressBar';
import Link from 'next/link';

function PdfUploader({ onUploadComplete }) {
    const fileInputRef = useRef(null);
    const [uploading, setUploading] = useState(false);

    const handleFileChange = async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        setUploading(true);
        const formData = new FormData();
        formData.append('file', file);

        try {
            const res = await fetch('/api/upload-pdf', {
                method: 'POST',
                body: formData,
            });
            const data = await res.json();
            if (data.success) {
                onUploadComplete(data);
            } else {
                alert("Erreur: " + data.error);
            }
        } catch (err) {
            console.error(err);
            alert("Erreur lors de l'upload du PDF.");
        } finally {
            setUploading(false);
            if (fileInputRef.current) fileInputRef.current.value = '';
        }
    };

    return (
        <div style={{ marginBottom: '3rem', padding: '2rem', background: 'rgba(146, 43, 85, 0.05)', borderRadius: '16px', border: '2px dashed rgba(146, 43, 85, 0.3)', textAlign: 'center', transition: 'all 0.3s ease' }}>
            <div style={{ width: '64px', height: '64px', background: 'rgba(146, 43, 85, 0.1)', borderRadius: '50%', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 1rem auto' }}>
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#922b55" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="12" y1="18" x2="12" y2="12"></line>
                    <line x1="9" y1="15" x2="15" y2="15"></line>
                </svg>
            </div>
            <h3 style={{ color: '#922b55', marginTop: 0, fontSize: '1.4rem', fontWeight: 800 }}>Ajouter un communiqué (PDF)</h3>
            <p style={{ color: '#64748b', fontSize: '1rem', marginBottom: '1.5rem', maxWidth: '400px', margin: '0 auto 1.5rem auto' }}>
                Le système lira le contenu du document et créera automatiquement une nouvelle publication.
            </p>
            <input 
                type="file" 
                accept="application/pdf" 
                ref={fileInputRef} 
                onChange={handleFileChange} 
                style={{ display: 'none' }} 
            />
            <button 
                onClick={() => fileInputRef.current?.click()} 
                disabled={uploading}
                style={{
                    background: '#922b55', 
                    color: '#fff', 
                    border: 'none', 
                    padding: '12px 28px', 
                    borderRadius: '8px', 
                    cursor: uploading ? 'not-allowed' : 'pointer', 
                    fontWeight: 700,
                    fontSize: '1rem',
                    boxShadow: '0 4px 14px rgba(146, 43, 85, 0.25)',
                    transition: 'transform 0.2s, box-shadow 0.2s',
                    opacity: uploading ? 0.7 : 1
                }}
            >
                {uploading ? 'Analyse du document...' : 'Sélectionner un PDF'}
            </button>
        </div>
    );
}

export default function InsightsPage() {
    const { dbData, isEditMode, isPreviewMode, updateData, fullDb } = useCMS();
    const data = dbData?.insights || { heroTitle: '', posts: [] };

    const handlePdfUploaded = async (parsedData) => {
        const newPost = {
            title: parsedData.title,
            desc: parsedData.desc,
            category: parsedData.category,
            pdfUrl: parsedData.url,
            slug: parsedData.slug,
            content: parsedData.content,
            img: ''
        };
        const updatedPosts = [newPost, ...(data.posts || [])];
        
        // 1. Update local state
        updateData(['insights', 'posts'], updatedPosts);
        
        // 2. Immediately persist to DB/JSON so the article page can read it
        try {
            const currentData = fullDb || dbData || {};
            const insightsSection = currentData.insights || {};
            const updatedDb = {
                ...currentData,
                insights: {
                    ...insightsSection,
                    posts: updatedPosts
                }
            };
            await fetch('/api/content', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(updatedDb)
            });
        } catch (err) {
            console.error('Auto-save after PDF upload failed:', err);
        }
    };

    return (
        <div id="munisys-insights-wrapper">
            <ReadingProgressBar />
            
            <section className="ins-sec" id="insights">
                <div className="ins-title-col">
                    <InlineEditor 
                        as="div" 
                        className="ins-title" 
                        dbPath={['insights', 'heroTitle']} 
                        fallback={data.heroTitle || 'Insights'} 
                    />
                </div>

                <div className="ins-grid">
                    {isEditMode && !isPreviewMode && (
                        <div style={{ gridColumn: '1 / -1', marginBottom: '1rem' }}>
                            <PdfUploader onUploadComplete={handlePdfUploaded} />
                        </div>
                    )}
                    {(data.posts || []).map((post, idx) => {
                        const isExternal = !post.slug && (post.pdfUrl || post.link);
                        const href = post.slug ? `/insights/${post.slug}` : (post.link || post.pdfUrl || "#");
                        
                        // Card content — NO InlineEditor here, as contentEditable blocks link clicks.
                        // Edit title/desc/content on the article page itself.
                        const cardContent = (
                            <>
                                <div className="ins-img-wrap" style={{ background: '#f8f9fa' }}>
                                    {post.img ? (
                                        <img src={post.img} alt={post.title} className="ins-img" />
                                    ) : (
                                        <div style={{ height: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#94a3b8' }}>
                                            Aucune image
                                        </div>
                                    )}
                                </div>
                                <div className="ins-content">
                                    <div className="ins-cat">{post.category || 'Article'}</div>
                                    <p className="ins-text" style={{ textTransform: 'uppercase', fontSize: '1.1rem', letterSpacing: '1px' }}>
                                        {post.title || 'Nouvelle publication'}
                                    </p>
                                    <p className="ins-desc">{post.desc || ''}</p>
                                    <span className="ins-learn-more">EN SAVOIR PLUS →</span>
                                </div>
                            </>
                        );
                        
                        if (isExternal) {
                            return (
                                <a key={idx} href={href} target="_blank" rel="noopener noreferrer" className="ins-card">
                                    {cardContent}
                                </a>
                            );
                        }
                        
                        return (
                            <Link key={idx} href={href} className="ins-card">
                                {cardContent}
                            </Link>
                        );
                    })}
                </div>
            </section>
        </div>
    );
}
