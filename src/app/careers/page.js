"use client";
import { useCMS } from '@/components/CMSProvider';
import InlineEditor from '@/components/InlineEditor';

import { useEffect } from 'react';

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




    return (
        <div className="m-car-page">
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
                                    <a href="#" className="m-btn-apply">
                                        <InlineEditor as="span" className="d-md-none" dbPath={['careers', 'applyLabel']} fallback="Apply" style={{display: 'none'}} />
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}
