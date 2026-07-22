const fs = require('fs');
const path = require('path');

const dbPath = './src/data/db.json';
const partnersDir = './public/partners';

// Create partners directory
if (!fs.existsSync(partnersDir)) {
  fs.mkdirSync(partnersDir, { recursive: true });
}

// Write SVGs
const svgs = {
  'cisco.svg': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30"><text x="0" y="22" font-family="Arial" font-weight="bold" font-size="22" fill="#049fd9">CISCO</text></svg>',
  'dell.svg': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30"><circle cx="15" cy="15" r="14" stroke="#007db8" stroke-width="2" fill="none"/><text x="4" y="19" font-family="Arial" font-weight="bold" font-size="9" fill="#007db8">DELL</text></svg>',
  'vmware.svg': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30"><text x="0" y="22" font-family="Arial" font-weight="bold" font-size="22" fill="#607078">vmware</text></svg>',
  'fortinet.svg': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30"><text x="0" y="22" font-family="Arial" font-weight="bold" font-size="22" fill="#E31837">FORTINET</text></svg>',
  'microsoft.svg': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30"><rect x="0" y="5" width="10" height="10" fill="#F25022"/><rect x="12" y="5" width="10" height="10" fill="#7FBA00"/><rect x="0" y="17" width="10" height="10" fill="#00A4EF"/><rect x="12" y="17" width="10" height="10" fill="#FFB900"/><text x="28" y="24" font-family="Segoe UI,Arial" font-weight="bold" font-size="18" fill="#737373">Microsoft</text></svg>',
  'ibm.svg': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30"><text x="0" y="25" font-family="Arial" font-weight="bold" font-size="28" fill="#0530ad">IBM</text></svg>'
};

for (const [filename, content] of Object.entries(svgs)) {
  fs.writeFileSync(path.join(partnersDir, filename), content);
}

let data = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

// Correct order and mapping for all languages
const correctPartners = {
  en: [
    { name: "Cisco", logo: "https://upload.wikimedia.org/wikipedia/commons/0/08/Cisco_logo_blue_2016.svg", category: "Networking" },
    { name: "Dell Technologies", logo: "https://upload.wikimedia.org/wikipedia/commons/1/18/Dell_logo_2016.svg", category: "Infrastructure" },
    { name: "VMware", logo: "https://upload.wikimedia.org/wikipedia/commons/9/9a/Vmware.svg", category: "Virtualization" },
    { name: "Fortinet", logo: "https://upload.wikimedia.org/wikipedia/commons/4/41/Fortinet_logo.svg", category: "Security" },
    { name: "Microsoft", logo: "https://upload.wikimedia.org/wikipedia/commons/9/96/Microsoft_logo_%282012%29.svg", category: "Cloud & Software" },
    { name: "IBM", logo: "https://upload.wikimedia.org/wikipedia/commons/5/51/IBM_logo.svg", category: "Enterprise Solutions" }
  ],
  fr: [
    { name: "Cisco", logo: "https://upload.wikimedia.org/wikipedia/commons/0/08/Cisco_logo_blue_2016.svg", category: "Réseaux" },
    { name: "Dell Technologies", logo: "https://upload.wikimedia.org/wikipedia/commons/1/18/Dell_logo_2016.svg", category: "Infrastructure" },
    { name: "VMware", logo: "https://upload.wikimedia.org/wikipedia/commons/9/9a/Vmware.svg", category: "Virtualisation" },
    { name: "Fortinet", logo: "https://upload.wikimedia.org/wikipedia/commons/4/41/Fortinet_logo.svg", category: "Sécurité" },
    { name: "Microsoft", logo: "https://upload.wikimedia.org/wikipedia/commons/9/96/Microsoft_logo_%282012%29.svg", category: "Cloud & Logiciel" },
    { name: "IBM", logo: "https://upload.wikimedia.org/wikipedia/commons/5/51/IBM_logo.svg", category: "Solutions d'Entreprise" }
  ],
  ar: [
    { name: "سيسكو", logo: "https://upload.wikimedia.org/wikipedia/commons/0/08/Cisco_logo_blue_2016.svg", category: "الشبكات" },
    { name: "شركة ديل للتكنولوجيا", logo: "https://upload.wikimedia.org/wikipedia/commons/1/18/Dell_logo_2016.svg", category: "البنية التحتية" },
    { name: "في إم وير", logo: "https://upload.wikimedia.org/wikipedia/commons/9/9a/Vmware.svg", category: "المحاكاة الافتراضية" },
    { name: "فورتينت", logo: "https://upload.wikimedia.org/wikipedia/commons/4/41/Fortinet_logo.svg", category: "أمن المعلومات" },
    { name: "مايكروسوفت", logo: "https://upload.wikimedia.org/wikipedia/commons/9/96/Microsoft_logo_%282012%29.svg", category: "الحوسبة السحابية والبرمجيات" },
    { name: "آي بي إم", logo: "https://upload.wikimedia.org/wikipedia/commons/5/51/IBM_logo.svg", category: "حلول الشركات" }
  ]
};

['en', 'fr', 'ar'].forEach(lang => {
  if (data[lang] && data[lang].technologyPartners) {
    data[lang].technologyPartners.partners = correctPartners[lang];
  }
});

fs.writeFileSync(dbPath, JSON.stringify(data, null, 2), 'utf8');
console.log('Database and SVGs updated successfully!');
