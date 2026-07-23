"use client";
import { useState, useRef } from 'react';
import { useCMS } from '@/components/CMSProvider';
import InlineEditor from '@/components/InlineEditor';
import ReadingProgressBar from '@/components/ReadingProgressBar';

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
    const { dbData, isEditMode, isPreviewMode, updateData } = useCMS();
    const data = dbData?.insights || { heroTitle: '', posts: [] };

    const handlePdfUploaded = (parsedData) => {
        const newPost = {
            title: parsedData.title,
            desc: parsedData.desc,
            category: parsedData.category,
            pdfUrl: parsedData.url,
            img: ''
        };
        const updatedPosts = [newPost, ...(data.posts || [])];
        updateData(['insights', 'posts'], updatedPosts);
    };

    return (
        <div id="munisys-insights-wrapper">
            <ReadingProgressBar />
            
            {isEditMode && !isPreviewMode && (
                <div style={{ maxWidth: '900px', margin: '3rem auto 0 auto', padding: '0 5%' }}>
                    <PdfUploader onUploadComplete={handlePdfUploaded} />
                </div>
            )}

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
                    {(data.posts || []).map((post, idx) => (
                        <a 
                            key={idx} 
                            href={post.pdfUrl || post.link || "#"} 
                            target={(post.pdfUrl || post.link) ? "_blank" : undefined} 
                            rel={(post.pdfUrl || post.link) ? "noopener noreferrer" : undefined} 
                            className="ins-card"
                        >
                            <div className="ins-img-wrap" style={{ background: post.pdfUrl ? '#fff1f2' : '#f8f9fa' }}>
                                {post.img ? (
                                    <img src={post.img} alt={post.title} style={{width: '100%', height: '100%', objectFit: 'cover'}} />
                                ) : (
                                    <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', width: '100%', height: '100%' }}>
                                        {post.pdfUrl ? (
                                            <>
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#e11d48" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" style={{marginBottom: '12px'}}>
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                                    <polyline points="10 9 9 9 8 9"></polyline>
                                                </svg>
                                                <span style={{ color: '#e11d48', fontSize: '0.95rem', fontWeight: 600 }}>Document PDF</span>
                                            </>
                                        ) : (
                                            <InlineEditor as="span" style={{ color: '#a0aec0', fontSize: '0.9rem', fontStyle: 'italic' }} dbPath={['insights', 'posts', idx, 'noImageLabel']} fallback="Aucune image" />
                                        )}
                                    </div>
                                )}
                            </div>
                            <div>
                                <InlineEditor 
                                    as="div" 
                                    style={{ fontSize: '0.8rem', color: '#922b55', fontWeight: 800, textTransform: 'uppercase', letterSpacing: '1px', marginBottom: '0.5rem' }} 
                                    dbPath={['insights', 'posts', idx, 'category']} 
                                    fallback={post.category || 'Article'} 
                                />
                                <InlineEditor 
                                    as="p" 
                                    className="ins-text" 
                                    style={{ textTransform: 'uppercase', fontSize: '1.1rem', letterSpacing: '1px' }}
                                    dbPath={['insights', 'posts', idx, 'title']} 
                                    fallback={post.title || 'Nouvelle publication'} 
                                />
                                <InlineEditor 
                                    as="p" 
                                    className="ins-desc" 
                                    dbPath={['insights', 'posts', idx, 'desc']} 
                                    fallback={post.desc || 'Description...'} 
                                />
                                <span className="ins-learn-more" style={{ color: post.pdfUrl ? '#e11d48' : undefined }}>
                                    {post.pdfUrl ? "Ouvrir le document" : (
                                        <InlineEditor as="span" dbPath={['insights', 'learnMore']} fallback="Learn more &rarr;" />
                                    )}
                                </span>
                            </div>
                        </a>
                    ))}
                </div>
            </section>
        </div>
    );
}
