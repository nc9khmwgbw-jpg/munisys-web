"use client";
import { useCMS } from '@/components/CMSProvider';
import InlineEditor, { InlineImageEditor } from '@/components/InlineEditor';
import ReadingProgressBar from '@/components/ReadingProgressBar';

export default function InsightsPage() {
    const { dbData } = useCMS();
    const data = dbData?.insights || { heroTitle: '', posts: [] };

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
                    {(data.posts || []).map((post, idx) => (
                        <a key={idx} href="#" className="ins-card">
                            <div className="ins-img-wrap" style={{ background: '#f8f9fa' }}>
                                {post.img ? (
                                    <img src={post.img} alt={post.title} style={{width: '100%', height: '100%', objectFit: 'cover'}} />
                                ) : (
                                    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: '100%', height: '100%' }}>
                                        <InlineEditor as="span" style={{ color: '#a0aec0', fontSize: '0.9rem', fontStyle: 'italic' }} dbPath={['insights', 'noImageLabel']} fallback="Aucune image" />
                                    </div>
                                )}
                            </div>
                            <div>
                                <InlineEditor 
                                    as="div" 
                                    style={{ fontSize: '0.8rem', color: '#922b55', fontWeight: 800, textTransform: 'uppercase', letterSpacing: '1px', marginBottom: '0.5rem' }} 
                                    dbPath={['insights', 'posts', idx, 'category']} 
                                    fallback={post.category} 
                                />
                                <InlineEditor 
                                    as="p" 
                                    className="ins-text" 
                                    style={{ textTransform: 'uppercase', fontSize: '1.1rem', letterSpacing: '1px' }}
                                    dbPath={['insights', 'posts', idx, 'title']} 
                                    fallback={post.title} 
                                />
                                <InlineEditor 
                                    as="p" 
                                    className="ins-desc" 
                                    dbPath={['insights', 'posts', idx, 'desc']} 
                                    fallback={post.desc} 
                                />
                                <InlineEditor as="span" className="ins-learn-more" dbPath={['insights', 'learnMore']} fallback="Learn more &rarr;" />
                            </div>
                        </a>
                    ))}
                </div>
            </section>
        </div>
    );
}
