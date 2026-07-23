"use client";
import { useState } from 'react';
import { useCMS } from '@/components/CMSProvider';
import InlineEditor from '@/components/InlineEditor';
import FadeIn from '@/components/FadeIn';

const defaultSolutions = [
  {
    title: "Core Infrastructure Technologies",
    desc: "Setting up, managing, and securing consistent compute, storage, networking and backup infrastructure.",
    items: [
      { title: "Compute & Storage", desc: "We use proven methodologies to design and build modern compute and storage infrastructures.", exp: "Compute Servers, High Performance Computing, Storage", part: "DELL, HPE, IBM" },
      { title: "Hyper-converged Infrastructures", desc: "Modern Hyper-converged Infrastructures (HCI) simplify datacenter operations.", exp: "HCI appliances, HCI Ready-Nodes", part: "DELL, HPE, Nutanix, VMware" },
      { title: "Data Resilience", desc: "Ensuring business continuity through availability, integrity, and accessibility of data.", exp: "Data Protection, Cloud Data Security", part: "DELL, HPE, Veeam, Veritas" },
      { title: "Networking", desc: "Setting up secure and highly scalable wired, wireless, and SD-WAN networking platforms.", exp: "Core LAN, Edge Networks, Wireless LAN, SD-WAN", part: "HPE Aruba, Cisco" },
      { title: "Cabling & Data Center", desc: "Dependable, manageable and secure cabling, rack, power and monitoring systems.", exp: "Intelligent racks, Power security", part: "Corning, Eaton, Schneider Electric" }
    ]
  },
  {
    title: "Cybersecurity Solutions",
    desc: "Comprehensive protection for your enterprise against evolving digital threats.",
    items: [
      { title: "Endpoint Security", desc: "Comprehensive monitoring and protection features for all user devices.", exp: "Antivirus, EDR, Threat protection", part: "HP, TrendMicro, Kaspersky" },
      { title: "Network Security", desc: "Secure network infrastructure with scalable, automated, and manageable rules.", exp: "Next Gen Firewalling, NAC, DDoS Protection", part: "F5, Fortinet, PaloAlto" },
      { title: "Cloud & App Security", desc: "Rapidly migrate applications safely outside the organization's network perimeter.", exp: "Cloud-native apps, SASE, DNS Security", part: "Broadcom, OpenText, F5" },
      { title: "Identity & Access", desc: "Deploy an Identity-centric strategy to protect every user-resource connection.", exp: "MFA, CASB, Data privilege Management", part: "Fortinet, Varonis" },
      { title: "Visibility & Compliance", desc: "Gaining deep visibility into valued assets and verifying compliance.", exp: "SIEM, SOAR, Vulnerability Management", part: "Tenable, Wallix" }
    ]
  },
  {
    title: "Infrastructure Software",
    desc: "Optimize your operations with enterprise-grade software solutions.",
    items: [
      { title: "Service & Asset Management", desc: "ITIL-aligned and cloud-native solutions for the management of business services.", exp: "Enterprise Service Management, CMDB", part: "OpenText" },
      { title: "Observability & Automation", desc: "Applying advanced automation to run, monitor and protect enterprise assets.", exp: "Datacenter Monitoring, RPA", part: "OpenText, Redhat" },
      { title: "Data Management & Analytics", desc: "Turning Data into meaningful and actionable insights.", exp: "Hybrid Data platforms, AI Data Analytics", part: "OpenText, Cohesity" },
      { title: "Application Modernization", desc: "Modernization of incumbent applications to take advantage of cloud technology.", exp: "App Modernization, Kubernetes, API Management", part: "Redhat, GitLab" }
    ]
  },
  {
    title: "Managed & Cloud Services",
    desc: "Seamless cloud integration and managed services.",
    items: [
      { title: "Hosting & Cloud Infra", desc: "Automated provisioning and API-driven infrastructure management.", exp: "Data Center Colocation, IaaS", part: "MDC, OVH, OCI" },
      { title: "Application & Platform", desc: "Managed PaaS solutions for developers.", exp: "Managed DB, PaaS", part: "Redhat, VMware" }
    ]
  },
  {
    title: "Endpoint Solutions",
    desc: "Empowering users with reliable endpoint devices and digital workplace platforms.",
    items: [
      { title: "Digital Workplace", desc: "Technologies for efficient and secure communication.", exp: "VDI, Collaboration Tools", part: "Microsoft, Citrix" },
      { title: "Printing Solutions", desc: "Managed print services and enterprise printers.", exp: "Managed Print Services", part: "HP, Canon" }
    ]
  }
];

export default function SolutionsPage() {
    const { dbData } = useCMS();
    const data = dbData?.solutions || {};
    const categories = data.categories?.length ? data.categories : defaultSolutions;
    
    const [activeTab, setActiveTab] = useState(0);

    return (
        <div id="munisys-solutions-wrapper">
            <FadeIn delay={0.1}>
                <section className="sol-hero" style={{ background: '#ffffff' }}>
                    <InlineEditor as="span" className="sol-hero-label" style={{ color: '#922b55' }} dbPath={['solutions', 'heroLabel']} fallback={data.heroLabel || 'OUR SOLUTIONS'} />
                    <h1 style={{ color: '#333333' }}>
                        <InlineEditor as="span" dbPath={['solutions', 'heroTitle']} fallback={data.heroTitle || 'Empowering your digital journey.'} />
                    </h1>
                    <InlineEditor as="p" style={{ color: '#4b5563' }} dbPath={['solutions', 'heroDesc']} fallback={data.heroDesc || 'Discover our comprehensive portfolio of IT solutions.'} />
                </section>
            </FadeIn>

            <section className="sol-container">
                <div className="sol-sidebar">
                    <FadeIn delay={0.2} direction="left">
                        {categories.map((cat, idx) => (
                            <div 
                                key={idx} 
                                className={`sol-cat-btn ${activeTab === idx ? 'active' : ''}`}
                                onMouseDown={(e) => {
                                    // Use onMouseDown so the tab switches even if InlineEditor captures the click
                                    e.preventDefault();
                                    setActiveTab(idx);
                                }}
                            >
                                <span style={{ pointerEvents: 'none' }}>{cat.title}</span>
                            </div>
                        ))}
                    </FadeIn>
                </div>

                <div className="sol-content-area">
                    {categories.map((cat, idx) => (
                        <div key={idx} className={`sol-cat-content ${activeTab === idx ? 'active' : ''}`} style={{ display: activeTab === idx ? 'block' : 'none' }}>
                            <FadeIn delay={0.1}>
                                <div className="cat-header">
                                    <h2><InlineEditor as="span" dbPath={['solutions', 'categories', idx, 'title']} fallback={cat.title} /></h2>
                                    <p><InlineEditor as="span" dbPath={['solutions', 'categories', idx, 'desc']} fallback={cat.desc} /></p>
                                </div>
                            </FadeIn>
                            <div className="sub-sol-grid">
                                {(cat.items || []).map((item, iIdx) => (
                                    <FadeIn key={iIdx} delay={0.1 + (iIdx * 0.1)}>
                                        <div className="sub-sol-card">
                                            <h3><InlineEditor as="span" dbPath={['solutions', 'categories', idx, 'items', iIdx, 'title']} fallback={item.title} /></h3>
                                            <p><InlineEditor as="span" dbPath={['solutions', 'categories', idx, 'items', iIdx, 'desc']} fallback={item.desc} /></p>
                                            <div className="sub-sol-meta">
                                                <div className="meta-item">
                                                <InlineEditor as="span" className="meta-label" dbPath={['solutions', 'expertiseLabel']} fallback="Expertise" />
                                                <span className="meta-val"><InlineEditor as="span" dbPath={['solutions', 'categories', idx, 'items', iIdx, 'exp']} fallback={item.exp} /></span>
                                            </div>
                                            <div className="meta-item">
                                                <InlineEditor as="span" className="meta-label" dbPath={['solutions', 'partnersLabel']} fallback="Partners" />
                                                <span className="meta-val"><InlineEditor as="span" dbPath={['solutions', 'categories', idx, 'items', iIdx, 'part']} fallback={item.part} /></span>
                                            </div>
                                        </div>
                                    </div>
                                    </FadeIn>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>
            </section>
        </div>
    );
}
