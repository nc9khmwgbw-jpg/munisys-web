const fs = require('fs');

const db = JSON.parse(fs.readFileSync('./src/data/db.json', 'utf8'));

// 1. About Us
db.en.aboutUs = {
  heroTitle: "About Us",
  heroDesc: "Learn more about Munisys, our history, our values, and our commitment to digital excellence.",
  subtitle: "Our Heritage",
  heroMainTitle: "Empowering Digital Transformation",
  storyTitle: "Who We Are",
  storyP1: "Since our inception, Munisys has been at the forefront of technological innovation. We believe that technology is the ultimate equalizer, enabling businesses to scale, secure their assets, and streamline operations in ways never before possible.",
  storyP2: "Our team of certified experts works relentlessly to architect and deploy IT solutions that are not just robust, but future-proof. By combining deep industry expertise with cutting-edge tools, we transform complex challenges into seamless digital experiences.",
  timelineTitle: "Our Journey",
  timeline: [
    { year: '1993', text: 'Founded with a vision to provide exceptional IT infrastructure services to local enterprises.' },
    { year: '2005', text: 'Expanded our portfolio to include advanced Cybersecurity and Network Integration.' },
    { year: '2015', text: 'Launched our Managed Services division, providing 24/7 proactive monitoring and SOC.' },
    { year: '2024', text: 'Leading the Cloud-Native transformation era, partnering with global tech giants.' }
  ]
};

// 2. Services
db.en.services = {
  heroLabel: "PROFESSIONAL SERVICES",
  heroMainTitle: "Our",
  heroMainTitleBold: "Services.",
  heroDesc: "Professional and operational services to help you make the right technology choices, design sound architectures, and deploy them according to best practices.",
  introDesc: "At Munisys, we cover the entire lifecycle of your IT infrastructure with a comprehensive catalog of professional services designed to ensure performance, security, and continuous business operations.",
  learnMoreLabel: "Learn More",
  sections: [
    {
      title: "IT Integration & Support",
      items: [
        {
          title: "Integration Services",
          desc: "At every stage in the lifecycle of your IT Infrastructures, our professional and operational services will help you make the right technology choices, design the soundest architectures and deploy them according to the rules of the art.",
          features: ["Architecture Design & Validation", "Technology Deployment", "System Migration & Upgrades"]
        },
        {
          title: "IT Support Services",
          desc: "Our reactive and proactive support services ensure maximum availability and continuity of your technological assets. We mitigate risks through strict SLAs and dedicated technical expertise.",
          features: ["24/7 Incident Management", "Proactive Health Checks", "Hardware Replacement (RMA)"]
        },
        {
          title: "Managed Services",
          desc: "Offload your daily IT operations to our certified experts, allowing your internal teams to focus on strategic business initiatives. We provide 24/7 monitoring and proactive maintenance.",
          features: ["Infrastructure Monitoring", "Security Operations Center (SOC)", "Cloud & Backup Management"]
        }
      ]
    }
  ]
};

// 3. Careers
db.en.careers = {
  applyLabel: "Apply",
  heroTitle: "Shape<br />the <strong>Future.</strong>",
  heroDesc: "Join a team of passionate experts dedicated to orchestrating digital transformation.",
  whyTitle: "Why Munisys?",
  whyDesc: "We believe in empowering our people, fostering continuous learning, and building a culture of excellence and innovation.",
  reasons: [
    { title: "Continuous Growth", desc: "Access to ongoing training, certifications, and clear career progression paths in cutting-edge technologies." },
    { title: "Impactful Work", desc: "Work on large-scale, mission-critical projects that shape the digital landscape of major enterprises." },
    { title: "Collaborative Culture", desc: "Join a diverse, inclusive, and supportive environment where every voice matters and teamwork thrives." }
  ],
  jobsTitle: "Open Positions",
  jobs: [
    { title: "Senior Network Security Engineer", meta: "Full-time • Casablanca • IT Integration" },
    { title: "Cloud Solutions Architect", meta: "Full-time • Casablanca • Cloud Services" },
    { title: "SOC Analyst Level 2", meta: "Full-time • Remote/Hybrid • Managed Services" },
    { title: "IT Project Manager", meta: "Full-time • Casablanca • Management" }
  ]
};

// 4. Contact
db.en.contact = {
  eyebrow: "Contact",
  subline: "Ready to accelerate your digital transformation? Our experts are here, available, and eager to help you shape the future.",
  hqTitle: "Headquarters",
  hqText: "123 Innovation Drive, Tech District, Casablanca",
  emailTitle: "Email Us",
  emailText: "contact@munisys.com",
  callTitle: "Call Us",
  callText: "+212 5 22 00 00 00"
};

fs.writeFileSync('./src/data/db.json', JSON.stringify(db, null, 2));
console.log('Populated en successfully');
