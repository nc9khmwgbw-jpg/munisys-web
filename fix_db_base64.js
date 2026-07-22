const fs = require('fs');

const dbPath = './src/data/db.json';

const makeBase64Uri = (svg) => 'data:image/svg+xml;base64,' + Buffer.from(svg).toString('base64');

const svgs = {
  'cisco': makeBase64Uri('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30" width="100%" height="100%"><text x="0" y="22" font-family="Arial" font-weight="bold" font-size="22" fill="#049fd9">CISCO</text></svg>'),
  'dell': makeBase64Uri('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30" width="100%" height="100%"><circle cx="15" cy="15" r="14" stroke="#007db8" stroke-width="2" fill="none"/><text x="4" y="19" font-family="Arial" font-weight="bold" font-size="9" fill="#007db8">DELL</text></svg>'),
  'vmware': makeBase64Uri('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30" width="100%" height="100%"><text x="0" y="22" font-family="Arial" font-weight="bold" font-size="22" fill="#607078">vmware</text></svg>'),
  'fortinet': makeBase64Uri('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30" width="100%" height="100%"><text x="0" y="22" font-family="Arial" font-weight="bold" font-size="22" fill="#E31837">FORTINET</text></svg>'),
  'microsoft': makeBase64Uri('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30" width="100%" height="100%"><rect x="0" y="5" width="10" height="10" fill="#F25022"/><rect x="12" y="5" width="10" height="10" fill="#7FBA00"/><rect x="0" y="17" width="10" height="10" fill="#00A4EF"/><rect x="12" y="17" width="10" height="10" fill="#FFB900"/><text x="28" y="24" font-family="Segoe UI,Arial" font-weight="bold" font-size="18" fill="#737373">Microsoft</text></svg>'),
  'ibm': makeBase64Uri('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30" width="100%" height="100%"><text x="0" y="25" font-family="Arial" font-weight="bold" font-size="28" fill="#0530ad">IBM</text></svg>')
};

let data = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

const correctPartners = {
  en: [
    { name: "Cisco", logo: svgs.cisco, category: "Networking" },
    { name: "Dell Technologies", logo: svgs.dell, category: "Infrastructure" },
    { name: "VMware", logo: svgs.vmware, category: "Virtualization" },
    { name: "Fortinet", logo: svgs.fortinet, category: "Security" },
    { name: "Microsoft", logo: svgs.microsoft, category: "Cloud & Software" },
    { name: "IBM", logo: svgs.ibm, category: "Enterprise Solutions" }
  ],
  fr: [
    { name: "Cisco", logo: svgs.cisco, category: "Réseaux" },
    { name: "Dell Technologies", logo: svgs.dell, category: "Infrastructure" },
    { name: "VMware", logo: svgs.vmware, category: "Virtualisation" },
    { name: "Fortinet", logo: svgs.fortinet, category: "Sécurité" },
    { name: "Microsoft", logo: svgs.microsoft, category: "Cloud & Logiciel" },
    { name: "IBM", logo: svgs.ibm, category: "Solutions d'Entreprise" }
  ],
  ar: [
    { name: "سيسكو", logo: svgs.cisco, category: "الشبكات" },
    { name: "شركة ديل للتكنولوجيا", logo: svgs.dell, category: "البنية التحتية" },
    { name: "في إم وير", logo: svgs.vmware, category: "المحاكاة الافتراضية" },
    { name: "فورتينت", logo: svgs.fortinet, category: "أمن المعلومات" },
    { name: "مايكروسوفت", logo: svgs.microsoft, category: "الحوسبة السحابية والبرمجيات" },
    { name: "آي بي إم", logo: svgs.ibm, category: "حلول الشركات" }
  ]
};

['en', 'fr', 'ar'].forEach(lang => {
  if (data[lang] && data[lang].technologyPartners) {
    data[lang].technologyPartners.partners = correctPartners[lang];
  }
});

fs.writeFileSync(dbPath, JSON.stringify(data, null, 2), 'utf8');
console.log('DB updated with Base64 URIs!');
