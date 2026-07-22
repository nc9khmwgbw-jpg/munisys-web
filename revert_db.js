const fs = require('fs');
const dbPath = './src/data/db.json';
let data = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

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
console.log('DB reverted to Wikipedia URLs!');
