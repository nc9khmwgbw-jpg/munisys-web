const fs = require('fs');

const db = JSON.parse(fs.readFileSync('./src/data/db.json', 'utf8'));

// 5. Customer References
db.en.customerReferences = {
  heroLabel: "Client References",
  heroDesc: "Our commitment to excellence has earned us lasting and fruitful collaborations with the largest economic actors and state entities in Morocco.",
  references: [
    { name: "Banque Populaire" },
    { name: "Bank Of Africa" },
    { name: "Bank Al-Maghrib" },
    { name: "Attijariwafa Bank" },
    { name: "CDG" },
    { name: "ONCF" },
    { name: "OCP" },
    { name: "Marbio" },
    { name: "LafargeHolcim" },
    { name: "Inwi" }
  ]
};

// 6. Insights
db.en.insights = {
  noImageLabel: "No image",
  heroTitle: "Insights",
  posts: [
    { title: "The Future of Cybersecurity in Morocco", category: "Whitepaper", desc: "An in-depth analysis of emerging threats and the zero-trust architecture necessary for modern enterprises.", img: "https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=800&auto=format&fit=crop" },
    { title: "Cloud Migration Strategies", category: "Blog", desc: "Best practices for seamlessly migrating your core infrastructure to hybrid cloud environments without downtime.", img: "https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=800&auto=format&fit=crop" },
    { title: "Munisys Wins Partner of the Year", category: "News", desc: "Recognized for outstanding delivery and innovation in IT integration services for the third consecutive year.", img: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop" },
    { title: "Optimizing IT Operations with AI", category: "Webinar", desc: "Join our experts as they discuss the integration of AI-driven tools in Managed Services and SOC operations.", img: "https://images.unsplash.com/photo-1573164713988-8665fc963095?q=80&w=800&auto=format&fit=crop" }
  ]
};

// 7. Technology Partners
db.en.technologyPartners = {
  heroTitle: "Technology Partners",
  heroDesc: "We build solid ties with the world's leading technology providers to ensure true resilience, performance, and agility for your digital infrastructure.",
  partners: [
    { name: "Cisco", logo: "https://www.munisys.ma/assets/images/partners/cisco.png" },
    { name: "Dell Technologies", logo: "https://www.munisys.ma/assets/images/partners/dell.png" },
    { name: "VMware", logo: "https://www.munisys.ma/assets/images/partners/vmware.png" },
    { name: "Fortinet", logo: "https://www.munisys.ma/assets/images/partners/fortinet.png" },
    { name: "Microsoft", logo: "https://www.munisys.ma/assets/images/partners/microsoft.png" },
    { name: "IBM", logo: "https://www.munisys.ma/assets/images/partners/ibm.png" }
  ]
};

// 8. Solutions
db.en.solutions = {
  expertiseLabel: "Expertise",
  partnersLabel: "Partners",
  heroLabel: "OUR SOLUTIONS",
  heroTitle: "Empowering your digital journey.",
  heroDesc: "Discover our comprehensive portfolio of IT solutions.",
  categories: [
    {
      title: "Core Infrastructure",
      desc: "Robust foundations for modern digital operations.",
      items: [
        { title: "Compute & Servers", desc: "High-performance processing power.", exp: "Advanced", part: "Dell, HP" },
        { title: "Enterprise Storage", desc: "Scalable and secure data management.", exp: "Expert", part: "NetApp, IBM" },
        { title: "Networking", desc: "High-speed, resilient connectivity.", exp: "Advanced", part: "Cisco, Juniper" }
      ]
    },
    {
      title: "Cybersecurity",
      desc: "End-to-end protection against evolving threats.",
      items: [
        { title: "Network Security", desc: "Next-gen firewalls and IPS.", exp: "Expert", part: "Fortinet, Palo Alto" },
        { title: "Endpoint Protection", desc: "Securing devices and users.", exp: "Advanced", part: "CrowdStrike, McAfee" },
        { title: "Identity & Access", desc: "Zero-trust access management.", exp: "Expert", part: "CyberArk, Okta" }
      ]
    }
  ]
};

fs.writeFileSync('./src/data/db.json', JSON.stringify(db, null, 2));
console.log('Populated en part 2 successfully');
