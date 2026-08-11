"use client";
import { useCMS } from '@/components/CMSProvider';
import InlineEditor from '@/components/InlineEditor';
import JsonLd from '@/components/JsonLd';
import { useState, useEffect } from 'react';

export default function CareersPage() {
    const { dbData, isEditMode } = useCMS();

    useEffect(() => {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('m-reveal-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // We use a short timeout to let React render the elements first
        const timeout = setTimeout(() => {
            document.querySelectorAll('.m-reveal, .m-reveal-left').forEach(el => observer.observe(el));
        }, 100);
        
        return () => {
            clearTimeout(timeout);
            observer.disconnect();
        };
    }, [dbData]);

    const data = dbData?.careers || { 
        heroTitle: '', heroDesc: '', whyTitle: '', whyDesc: '', reasons: [], jobsTitle: '', jobs: [] 
    };

    const [selectedJob, setSelectedJob] = useState(null);
    const [isApplying, setIsApplying] = useState(false);

    const handleApplySubmit = async (e) => {
        e.preventDefault();
        if (isApplying) return;
        setIsApplying(true);
        const formData = {
            position: selectedJob,
            name: e.target.name.value,
            email: e.target.email.value,
            phone: e.target.phone.value,
            linkedin: e.target.linkedin.value,
            motivation: e.target.motivation.value,
        };
        try {
            const res = await fetch('/api/apply', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
            const result = await res.json();
            if (res.ok) {
                if (typeof window.__toast === 'function') window.__toast('✅ Candidature envoyée avec succès !', 'success');
                setSelectedJob(null);
            } else {
                if (typeof window.__toast === 'function') window.__toast(result.error || "Erreur lors de l'envoi.", 'error');
            }
        } catch(err) {
            if (typeof window.__toast === 'function') window.__toast('Erreur réseau.', 'error');
        } finally {
            setIsApplying(false);
        }
    };

    // Generate JSON-LD for open positions
    const jobSchemas = (data.jobs || []).map(job => ({
        "@context": "https://schema.org",
        "@type": "JobPosting",
        "title": job.title || "Open Position",
        "description": job.title || "Open Position at Munisys",
        "datePosted": new Date().toISOString().split('T')[0],
        "employmentType": "FULL_TIME",
        "hiringOrganization": {
            "@type": "Organization",
            "name": "Munisys",
            "sameAs": "https://www.munisys.net"
        },
        "jobLocation": {
            "@type": "Place",
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "Casablanca",
                "addressCountry": "MA"
            }
        }
    }));

    return (
        <div className="m-car-page">
            {jobSchemas.map((schema, idx) => <JsonLd key={idx} data={schema} />)}
            {/* Split Hero */}
            <div className="m-car-hero">
                <div className="m-car-hero-left m-reveal-left">
                    <InlineEditor 
                        as="h1" 
                        dbPath={['careers', 'heroTitle']} 
                        fallback={data.heroTitle || 'Shape<br>the future'} 
                        />
                    <InlineEditor 
                        as="p" 
                        dbPath={['careers', 'heroDesc']} 
                        fallback={data.heroDesc || ''} 
                    />
                </div>
                <div className="m-car-hero-right"></div>
            </div>

            {/* Hover Cards */}
            <div className="m-car-why">
                <div className="m-car-why-header m-reveal">
                    <InlineEditor 
                        as="h2" 
                        dbPath={['careers', 'whyTitle']} 
                        fallback={data.whyTitle || 'Why Munisys?'} 
                    />
                    <InlineEditor 
                        as="p" 
                        dbPath={['careers', 'whyDesc']} 
                        fallback={data.whyDesc || ''} 
                    />
                </div>

                <div className="m-car-why-grid">
                    {(data.reasons || []).map((reason, idx) => (
                        <div key={idx} className={`m-car-why-card m-reveal m-delay-${idx + 1}`}>
                            <InlineEditor 
                                as="h3" 
                                dbPath={['careers', 'reasons', idx, 'title']} 
                                fallback={reason.title} 
                            />
                            <InlineEditor 
                                as="p" 
                                dbPath={['careers', 'reasons', idx, 'desc']} 
                                fallback={reason.desc} 
                            />
                        </div>
                    ))}
                </div>
            </div>

            {/* Interactive Job Lines */}
            <div className="m-car-jobs">
                <div className="m-jobs-container">
                    <div className="m-jobs-header m-reveal">
                        <InlineEditor 
                            as="h2" 
                            dbPath={['careers', 'jobsTitle']} 
                            fallback={data.jobsTitle || 'Open Positions'} 
                        />
                    </div>

                    <div className="m-job-list">
                        {(data.jobs || []).length === 0 ? (
                            <div style={{ textAlign: 'center', padding: '3rem', color: '#94a3b8', fontSize: '1.1rem' }}>
                                {isEditMode 
                                    ? '➕ Aucun poste pour l\'instant — ajoutez-en depuis le panneau d\'administration.'
                                    : 'Aucun poste ouvert en ce moment. Revenez bientôt !'}
                            </div>
                        ) : (data.jobs || []).map((job, idx) => (
                            <div key={idx} className={`m-job-line m-reveal m-delay-${Math.min(idx + 1, 5)}`}>
                                <div className="m-job-title">
                                    <InlineEditor 
                                        as="h3" 
                                        dbPath={['careers', 'jobs', idx, 'title']} 
                                        fallback={job.title} 
                                    />
                                </div>
                                <div className="m-job-meta">
                                    <InlineEditor 
                                        as="span" 
                                        dbPath={['careers', 'jobs', idx, 'meta']} 
                                        fallback={job.meta} 
                                    />
                                </div>
                                <div className="m-job-action">
                                    <button 
                                        className="m-btn-apply" 
                                        onClick={() => setSelectedJob(job.title)}
                                        style={{ background: 'none', border: 'none', cursor: 'pointer' }}
                                    >
                                        <InlineEditor as="span" className="d-md-none" dbPath={['careers', 'applyLabel']} fallback="Apply" style={{display: 'none'}} />
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* Application Modal */}
            {selectedJob && (
                <div style={{
                    position: 'fixed', top: 0, left: 0, right: 0, bottom: 0,
                    backgroundColor: 'rgba(15, 23, 42, 0.85)',
                    backdropFilter: 'blur(8px)',
                    zIndex: 99999,
                    display: 'flex', alignItems: 'center', justifyContent: 'center',
                    padding: '1rem'
                }}>
                    <div style={{
                        background: 'linear-gradient(135deg, #1e293b, #0f172a)',
                        padding: '2.5rem',
                        borderRadius: '24px',
                        width: '100%',
                        maxWidth: '550px',
                        position: 'relative',
                        border: '1px solid rgba(255,255,255,0.1)',
                        boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.5)',
                        maxHeight: '90vh',
                        overflowY: 'auto'
                    }}>
                        <button 
                            onClick={() => setSelectedJob(null)}
                            style={{ position: 'absolute', top: '1.5rem', right: '1.5rem', background: 'none', border: 'none', color: '#94a3b8', cursor: 'pointer' }}
                        >
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                        <h3 style={{ color: '#f8fafc', fontSize: '1.5rem', marginBottom: '0.5rem', fontWeight: 600 }}>Postuler chez Munisys</h3>
                        <p style={{ color: '#922b55', marginBottom: '2rem', fontWeight: 500 }}>Poste : {selectedJob}</p>
                        
                        <form onSubmit={handleApplySubmit} style={{ display: 'flex', flexDirection: 'column', gap: '1.25rem' }}>
                            <div>
                                <label style={{ display: 'block', color: '#cbd5e1', marginBottom: '0.5rem', fontSize: '0.9rem' }}>Nom complet *</label>
                                <input name="name" type="text" required style={{ width: '100%', padding: '0.75rem 1rem', borderRadius: '8px', background: 'rgba(255,255,255,0.05)', border: '1px solid rgba(255,255,255,0.1)', color: '#fff', fontSize: '1rem' }} />
                            </div>
                            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
                                <div>
                                    <label style={{ display: 'block', color: '#cbd5e1', marginBottom: '0.5rem', fontSize: '0.9rem' }}>Email *</label>
                                    <input name="email" type="email" required style={{ width: '100%', padding: '0.75rem 1rem', borderRadius: '8px', background: 'rgba(255,255,255,0.05)', border: '1px solid rgba(255,255,255,0.1)', color: '#fff', fontSize: '1rem' }} />
                                </div>
                                <div>
                                    <label style={{ display: 'block', color: '#cbd5e1', marginBottom: '0.5rem', fontSize: '0.9rem' }}>Téléphone</label>
                                    <input name="phone" type="tel" style={{ width: '100%', padding: '0.75rem 1rem', borderRadius: '8px', background: 'rgba(255,255,255,0.05)', border: '1px solid rgba(255,255,255,0.1)', color: '#fff', fontSize: '1rem' }} />
                                </div>
                            </div>
                            <div>
                                <label style={{ display: 'block', color: '#cbd5e1', marginBottom: '0.5rem', fontSize: '0.9rem' }}>Lien LinkedIn / Portfolio</label>
                                <input name="linkedin" type="url" style={{ width: '100%', padding: '0.75rem 1rem', borderRadius: '8px', background: 'rgba(255,255,255,0.05)', border: '1px solid rgba(255,255,255,0.1)', color: '#fff', fontSize: '1rem' }} />
                            </div>
                            <div>
                                <label style={{ display: 'block', color: '#cbd5e1', marginBottom: '0.5rem', fontSize: '0.9rem' }}>Lettre de motivation courte *</label>
                                <textarea name="motivation" required rows="4" style={{ width: '100%', padding: '0.75rem 1rem', borderRadius: '8px', background: 'rgba(255,255,255,0.05)', border: '1px solid rgba(255,255,255,0.1)', color: '#fff', fontSize: '1rem', resize: 'vertical' }}></textarea>
                            </div>
                            <button type="submit" disabled={isApplying} style={{ 
                                marginTop: '1rem', padding: '1rem', borderRadius: '50px', background: 'linear-gradient(135deg, #1d4e79, #922b55)', 
                                color: '#fff', fontWeight: 600, border: 'none', cursor: 'pointer', opacity: isApplying ? 0.7 : 1, transition: 'opacity 0.2s'
                            }}>
                                {isApplying ? 'Envoi en cours...' : 'Envoyer ma candidature'}
                            </button>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
}
