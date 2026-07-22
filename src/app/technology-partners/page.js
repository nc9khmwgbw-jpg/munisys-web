"use client";
import { useState } from 'react';
import { useCMS } from '@/components/CMSProvider';
import InlineEditor from '@/components/InlineEditor';
import InlineImageEditor from '@/components/InlineImageEditor';

export default function TechnologyPartnersPage() {
    const { dbData, isEditMode } = useCMS();
    const data = dbData?.technologyPartners || {};
    const [activeFilter, setActiveFilter] = useState('all');

    const partners = data.partners || [];
    
    // Extract unique categories for the filter
    const categories = ['all', ...new Set(partners.map(p => p.category).filter(Boolean))];

    const filteredPartners = activeFilter === 'all' 
        ? partners 
        : partners.filter(p => p.category === activeFilter);

    return (
        <div id="munisys-tp-wrapper">
            <section className="tp-hero">
                <div className="tp-hero-content">
                    <h1>
                        <InlineEditor as="span" dbPath={['technologyPartners', 'heroTitle']} fallback={data.heroTitle || 'Technology Partners'} />
                    </h1>
                    <InlineEditor as="p" dbPath={['technologyPartners', 'heroDesc']} fallback={data.heroDesc || "We build solid ties with the world's leading technology providers to ensure true resilience, performance, and agility for your digital infrastructure."} />
                </div>
            </section>

            <section className="tp-grid-sec">
                <div className="tp-filters">
                    {categories.map((cat, idx) => (
                        <button 
                            key={idx} 
                            className={`tp-filter-btn ${activeFilter === cat ? 'active' : ''}`}
                            onClick={() => setActiveFilter(cat)}
                        >
                            {cat === 'all' ? <InlineEditor as="span" dbPath={['technologyPartners', 'allSpecialtiesLabel']} fallback="All Specialties" /> : cat}
                        </button>
                    ))}
                </div>

                <div className="tp-grid">
                    {filteredPartners.map((partner) => {
                        // Find the true index in the original DB array to ensure InlineImageEditor updates the correct path
                        const originalIdx = partners.findIndex(p => p.name === partner.name);
                        return (
                            <a key={originalIdx} href={partner.link || '#'} className="tp-card" data-category={partner.category}>
                                {(partner.logo || isEditMode) ? (
                                    <InlineImageEditor 
                                        dbPath={['technologyPartners', 'partners', originalIdx, 'logo']} 
                                        fallback={partner.logo || ''} 
                                        alt={partner.name} 
                                        className="tp-logo-img" 
                                        style={{ maxHeight: '60px', objectFit: 'contain' }} 
                                    />
                                ) : (
                                    <div className="tp-logo-img" style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', height: '60px', fontWeight: 'bold', fontSize: '1.5rem', color: '#1d4e79' }}>
                                        {partner.name}
                                    </div>
                                )}
                                <div className="tp-cat">{partner.category}</div>
                            </a>
                        );
                    })}
                </div>
            </section>
        </div>
    );
}
