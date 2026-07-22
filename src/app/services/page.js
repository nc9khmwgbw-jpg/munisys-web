"use client";
import { useCMS } from '@/components/CMSProvider';
import InlineEditor from '@/components/InlineEditor';
import FadeIn from '@/components/FadeIn';

const defaultServices = [
  {
    title: "Integration Services",
    desc: "At every stage in the lifecycle of your IT Infrastructures, our professional and operational services will help you make the right technology choices, design the soundest architectures and deploy them according to the rules of the art.",
    img: "https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=1000&auto=format&fit=crop",
    features: ["Architecture Design & Validation", "Technology Deployment", "System Migration & Upgrades"]
  },
  {
    title: "IT Support Services",
    desc: "Our reactive and proactive support services ensure maximum availability and continuity of your technological assets. We mitigate risks through strict SLAs and dedicated technical expertise.",
    img: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=1000&auto=format&fit=crop",
    features: ["24/7 Incident Management", "Proactive Health Checks", "Hardware Replacement (RMA)"]
  },
  {
    title: "Managed Services",
    desc: "Offload your daily IT operations to our certified experts, allowing your internal teams to focus on strategic business initiatives. We provide 24/7 monitoring and proactive maintenance.",
    img: "https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1000&auto=format&fit=crop",
    features: ["Infrastructure Monitoring", "Security Operations Center (SOC)", "Cloud & Backup Management"]
  }
];

export default function ServicesPage() {
  const { dbData } = useCMS();
  const data = dbData?.services || {};

  const servicesItems = data.sections?.[0]?.items?.length
    ? data.sections[0].items
    : defaultServices;

  return (
    <div id="munisys-srv-wrapper">
      <FadeIn delay={0.1}>
        <section className="srv-hero">
          <InlineEditor 
            as="span" 
            className="srv-hero-label" 
            dbPath={['services', 'heroLabel']} 
            fallback={data.heroLabel || 'PROFESSIONAL SERVICES'} 
          />
          <h1>
              <InlineEditor as="span" dbPath={['services', 'heroMainTitle']} fallback="Our" /><br />
              <strong><InlineEditor as="span" dbPath={['services', 'heroMainTitleBold']} fallback="Services." /></strong>
          </h1>
          <InlineEditor 
            as="p" 
            dbPath={['services', 'heroDesc']} 
            fallback={data.heroDesc || 'Professional and operational services to help you make the right technology choices, design sound architectures, and deploy them according to best practices.'} 
          />
        </section>
      </FadeIn>

      <FadeIn delay={0.2}>
        <section className="srv-intro">
          <InlineEditor 
              as="h2" 
              dbPath={['services', 'sections', 0, 'title']} 
              fallback={data.sections?.[0]?.title || 'IT Integration & Support'} 
          />
          <InlineEditor 
              as="p" 
              dbPath={['services', 'introDesc']} 
              fallback={data.introDesc || 'At Munisys, we cover the entire lifecycle of your IT infrastructure with a comprehensive catalog of professional services designed to ensure performance, security, and continuous business operations.'} 
          />
        </section>
      </FadeIn>

      <section className="srv-details">
        {servicesItems.map((item, idx) => (
          <FadeIn key={idx} direction={idx % 2 !== 0 ? 'left' : 'right'} delay={0.1}>
            <div className={`srv-row ${idx % 2 !== 0 ? 'reverse' : ''}`} id={`service-${idx}`}>
              <div className="srv-img-col">
                <img src={item.img || defaultServices[idx % defaultServices.length].img} alt={item.title} />
              </div>
              <div className="srv-text-col">
                <span className="srv-num">{String(idx + 1).padStart(2, '0')} //</span>
                <h3>
                  <InlineEditor as="span" dbPath={['services', 'sections', 0, 'items', idx, 'title']} fallback={item.title} />
                </h3>
                <p>
                  <InlineEditor as="span" dbPath={['services', 'sections', 0, 'items', idx, 'desc']} fallback={item.desc} />
                </p>
                <ul className="srv-list">
                  {(item.features || defaultServices[idx % defaultServices.length].features).map((feat, fIdx) => (
                    <li key={fIdx}>
                      <InlineEditor as="span" dbPath={['services', 'sections', 0, 'items', idx, 'features', fIdx]} fallback={feat} />
                    </li>
                  ))}
                </ul>
                <a href="#" className="srv-btn">
                  <InlineEditor as="span" dbPath={['services', 'learnMoreLabel']} fallback="Learn More" /> 
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                  </svg>
                </a>
              </div>
            </div>
          </FadeIn>
        ))}
      </section>
    </div>
  );
}
