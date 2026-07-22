"use client";
import { useCMS } from '@/components/CMSProvider';
import InlineEditor from '@/components/InlineEditor';
import InlineImageEditor from '@/components/InlineImageEditor';

export default function CustomerReferencesPage() {
    const { dbData, isEditMode } = useCMS();
    const data = dbData?.customerReferences || {};
    const references = data.references || [];

    return (
        <div id="munisys-cr-wrapper">
            <section className="cr-hero">
                <InlineEditor as="span" className="cr-hero-label" dbPath={['customerReferences', 'heroLabel']} fallback={data.heroLabel || 'Client References'} />
                <h1>
                    <InlineEditor as="span" dbPath={['customerReferences', 'heroMainTitle']} fallback="Trusted by" /><br />
                    <strong><InlineEditor as="span" dbPath={['customerReferences', 'heroMainTitleBold']} fallback="Leaders." /></strong>
                </h1>
                <InlineEditor as="p" dbPath={['customerReferences', 'heroDesc']} fallback={data.heroDesc || 'Our commitment to excellence has earned us lasting and fruitful collaborations with the largest economic actors and state entities in Morocco.'} />
            </section>

            <section className="cr-grid-sec">
                <div className="cr-bento">
                    {references.map((ref, idx) => (
                        <div key={idx} className="cr-card">
                            {(ref.logo || isEditMode) ? (
                                <InlineImageEditor 
                                    dbPath={['customerReferences', 'references', idx, 'logo']} 
                                    fallback={ref.logo || ''} 
                                    alt={ref.name} 
                                    className="cr-logo-img" 
                                    style={{ height: '60px', objectFit: 'contain' }} 
                                />
                            ) : (
                                <div className="cr-logo-img" style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', height: '60px', color: '#1d4e79', fontWeight: 'bold' }}>
                                    {ref.name}
                                </div>
                            )}
                            <h3 className="cr-name">
                                <InlineEditor as="span" dbPath={['customerReferences', 'references', idx, 'name']} fallback={ref.name} />
                            </h3>
                            <span className="cr-sector">
                                <InlineEditor as="span" dbPath={['customerReferences', 'references', idx, 'sector']} fallback={ref.sector} />
                            </span>
                        </div>
                    ))}
                </div>
            </section>
        </div>
    );
}
