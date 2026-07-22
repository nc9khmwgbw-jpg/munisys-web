"use client";
import { useCMS } from '@/components/CMSProvider';
import InlineEditor from '@/components/InlineEditor';

const defaultServices = [
  {
    title: "IT Integration Services",
    desc: "At every stage in the lifecycle of your IT Infrastructures, our professional and operational services will help you make the right technology choices, design the soundest architectures and deploy them according to best practices, all under the supervision and control of our project managers."
  },
  {
    title: "IT Support Services",
    desc: "Our reactive and proactive support services ensure your infrastructure remains healthy, minimizing downtime and optimizing performance. We provide 24/7 monitoring, incident response, and preventive maintenance."
  },
  {
    title: "Managed Services",
    desc: "Offload your daily IT operations to our certified experts, allowing your internal teams to focus on strategic business initiatives. We provide 24/7 monitoring, proactive maintenance, and dedicated support."
  },
  {
    title: "Professional Services",
    desc: "Our certified consultants bring deep expertise in architecture design, technology migration, and digital transformation projects. From assessment to delivery, we accompany you throughout your IT journey."
  }
];

export default function ServicesPage() {
  const { dbData } = useCMS();
  const data = dbData?.services || {};

  const servicesItems = data.sections?.[0]?.items?.length
    ? data.sections[0].items
    : defaultServices;

  return (
    <div id="munisys-wrapper">
      {/* Page Hero - WordPress style */}
      <section className="page-hero">
        <InlineEditor as="h1" dbPath={['services', 'heroTitle']} fallback={data.heroTitle || 'Services'} />
        <InlineEditor as="p" dbPath={['services', 'heroDesc']} fallback={data.heroDesc || 'Professional and operational services to help you make the right technology choices, design sound architectures, and deploy them according to best practices.'} />
      </section>

      {/* Page Content */}
      <section className="page-content">
        <InlineEditor as="h2" className="section-title" dbPath={['services', 'sections', 0, 'title']} fallback={data.sections?.[0]?.title || 'IT Integration & Support'} />

        <div className="services-grid">
          {servicesItems.map((item, idx) => (
            <div key={idx} className="service-card">
              <h3>
                <InlineEditor as="span" dbPath={['services', 'sections', 0, 'items', idx, 'title']} fallback={item.title} />
              </h3>
              <p>
                <InlineEditor as="span" dbPath={['services', 'sections', 0, 'items', idx, 'desc']} fallback={item.desc} />
              </p>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
}
