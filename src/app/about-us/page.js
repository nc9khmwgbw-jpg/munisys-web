"use client";
import { useCMS } from '@/components/CMSProvider';
import InlineEditor from '@/components/InlineEditor';
import FadeIn from '@/components/FadeIn';

export default function AboutUsPage() {
    const { dbData } = useCMS();
    const data = dbData?.aboutUs || {};

    return (
        <div className="abt-wrapper">
            <FadeIn delay={0.1}>
                <div className="abt-hero">
                    <div className="abt-hero-content">
                        <span className="abt-subtitle">
                            <InlineEditor as="span" dbPath={['aboutUs', 'subtitle']} fallback={data.subtitle || 'Our Heritage'} />
                        </span>
                        <h1 className="abt-title">
                            <InlineEditor as="span" dbPath={['aboutUs', 'heroMainTitle']} fallback={data.heroMainTitle || 'Empowering Digital Transformation'} />
                        </h1>
                    </div>
                </div>
            </FadeIn>

            <FadeIn delay={0.2}>
                <div className="abt-story">
                    <div className="abt-story-left">
                        <h2>
                            <InlineEditor as="span" dbPath={['aboutUs', 'storyTitle']} fallback={data.storyTitle || 'Who We Are'} />
                        </h2>
                    </div>
                    <div className="abt-story-right">
                        <p>
                            <InlineEditor as="span" dbPath={['aboutUs', 'storyP1']} fallback={data.storyP1 || 'Since our inception, Munisys has been at the forefront of technological innovation. We believe that technology is the ultimate equalizer, enabling businesses to scale, secure their assets, and streamline operations in ways never before possible.'} />
                        </p>
                        <p>
                            <InlineEditor as="span" dbPath={['aboutUs', 'storyP2']} fallback={data.storyP2 || 'Our team of certified experts works relentlessly to architect and deploy IT solutions that are not just robust, but future-proof. By combining deep industry expertise with cutting-edge tools, we transform complex challenges into seamless digital experiences.'} />
                        </p>
                    </div>
                </div>
            </FadeIn>

            <div className="abt-timeline-sec">
                <style dangerouslySetInnerHTML={{__html: `
                    [dir="rtl"] .abt-tl-item-rtl:nth-child(odd) {
                        right: 0 !important;
                        left: auto !important;
                        text-align: left !important;
                        padding-left: 60px !important;
                        padding-right: 40px !important;
                    }
                    [dir="rtl"] .abt-tl-item-rtl:nth-child(even) {
                        right: 50% !important;
                        left: auto !important;
                        text-align: right !important;
                        padding-right: 60px !important;
                        padding-left: 40px !important;
                    }
                    [dir="rtl"] .abt-tl-item-rtl:nth-child(odd) .abt-tl-dot {
                        left: -12px !important;
                        right: auto !important;
                    }
                    [dir="rtl"] .abt-tl-item-rtl:nth-child(even) .abt-tl-dot {
                        right: -12px !important;
                        left: auto !important;
                    }
                    @media (max-width: 992px) {
                        [dir="rtl"] .abt-tl-item-rtl,
                        [dir="rtl"] .abt-tl-item-rtl:nth-child(odd),
                        [dir="rtl"] .abt-tl-item-rtl:nth-child(even) {
                            width: 100% !important;
                            right: 0 !important;
                            left: auto !important;
                            text-align: right !important;
                            padding-right: 80px !important;
                            padding-left: 20px !important;
                        }
                        [dir="rtl"] .abt-tl-item-rtl .abt-tl-dot,
                        [dir="rtl"] .abt-tl-item-rtl:nth-child(odd) .abt-tl-dot,
                        [dir="rtl"] .abt-tl-item-rtl:nth-child(even) .abt-tl-dot {
                            right: 18px !important;
                            left: auto !important;
                        }
                    }
                `}} />
                <FadeIn delay={0.1}>
                    <h2 className="abt-timeline-title">
                        <InlineEditor as="span" dbPath={['aboutUs', 'timelineTitle']} fallback={data.timelineTitle || 'Our Journey'} />
                    </h2>
                </FadeIn>
                <div className="abt-timeline">
                    {[
                        { year: '1993', text: 'Founded with a vision to provide exceptional IT infrastructure services to local enterprises.' },
                        { year: '2005', text: 'Expanded our portfolio to include advanced Cybersecurity and Network Integration.' },
                        { year: '2015', text: 'Launched our Managed Services division, providing 24/7 proactive monitoring and SOC.' },
                        { year: '2024', text: 'Leading the Cloud-Native transformation era, partnering with global tech giants.' }
                    ].map((item, idx) => (
                        <FadeIn key={idx} direction={idx % 2 === 0 ? 'right' : 'left'} delay={0.1}>
                            <div className="abt-tl-item abt-tl-item-rtl">
                                <div className="abt-tl-dot"></div>
                                <div className="abt-tl-year">
                                    <InlineEditor as="span" dbPath={['aboutUs', 'timeline', idx, 'year']} fallback={item.year} />
                                </div>
                                <div className="abt-tl-text">
                                    <InlineEditor as="span" dbPath={['aboutUs', 'timeline', idx, 'text']} fallback={item.text} />
                                </div>
                            </div>
                        </FadeIn>
                    ))}
                </div>
            </div>
        </div>
    );
}
