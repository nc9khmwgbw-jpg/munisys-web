const fs = require('fs');
const https = require('https');

const dbPath = './src/data/db.json';
let data = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

// We will use Google Favicon API as it is 100% reliable and returns official brand icons.
// We will set size=128 which is sharp and clean.
const getLogoUrl = (domain) => `https://t3.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=http://${domain}&size=128`;

const partners = {
  cisco: getLogoUrl('cisco.com'),
  dell: getLogoUrl('dell.com'),
  vmware: getLogoUrl('vmware.com'),
  fortinet: getLogoUrl('fortinet.com'),
  microsoft: getLogoUrl('microsoft.com'),
  ibm: getLogoUrl('ibm.com')
};

const customers = {
  bp: getLogoUrl('groupebcp.com'),
  boa: getLogoUrl('bankofafrica.ma'),
  bam: getLogoUrl('bkam.ma'),
  awb: getLogoUrl('attijariwafabank.com'),
  cdg: getLogoUrl('cdg.ma'),
  oncf: getLogoUrl('oncf.ma'),
  ocp: getLogoUrl('ocp-group.com'),
  marbio: getLogoUrl('marbio.ma'),
  lafarge: getLogoUrl('lafargeholcim.com'),
  inwi: getLogoUrl('inwi.ma')
};

// Update Technology Partners
const correctPartners = {
  en: [
    { name: "Cisco", logo: partners.cisco, category: "Networking" },
    { name: "Dell Technologies", logo: partners.dell, category: "Infrastructure" },
    { name: "VMware", logo: partners.vmware, category: "Virtualization" },
    { name: "Fortinet", logo: partners.fortinet, category: "Security" },
    { name: "Microsoft", logo: partners.microsoft, category: "Cloud & Software" },
    { name: "IBM", logo: partners.ibm, category: "Enterprise Solutions" }
  ],
  fr: [
    { name: "Cisco", logo: partners.cisco, category: "Réseaux" },
    { name: "Dell Technologies", logo: partners.dell, category: "Infrastructure" },
    { name: "VMware", logo: partners.vmware, category: "Virtualisation" },
    { name: "Fortinet", logo: partners.fortinet, category: "Sécurité" },
    { name: "Microsoft", logo: partners.microsoft, category: "Cloud & Logiciel" },
    { name: "IBM", logo: partners.ibm, category: "Solutions d'Entreprise" }
  ],
  ar: [
    { name: "سيسكو", logo: partners.cisco, category: "الشبكات" },
    { name: "شركة ديل للتكنولوجيا", logo: partners.dell, category: "البنية التحتية" },
    { name: "في إم وير", logo: partners.vmware, category: "المحاكاة الافتراضية" },
    { name: "فورتينت", logo: partners.fortinet, category: "أمن المعلومات" },
    { name: "مايكروسوفت", logo: partners.microsoft, category: "الحوسبة السحابية والبرمجيات" },
    { name: "آي بي إم", logo: partners.ibm, category: "حلول الشركات" }
  ]
};

// Update Customers
const correctCustomers = {
  en: [
    { name: "Banque Populaire", logo: customers.bp },
    { name: "Bank Of Africa", logo: customers.boa },
    { name: "Bank Al-Maghrib", logo: customers.bam },
    { name: "Attijariwafa Bank", logo: customers.awb },
    { name: "CDG", logo: customers.cdg },
    { name: "ONCF", logo: customers.oncf },
    { name: "OCP", logo: customers.ocp },
    { name: "Marbio", logo: customers.marbio },
    { name: "LafargeHolcim", logo: customers.lafarge },
    { name: "Inwi", logo: customers.inwi }
  ],
  fr: [
    { name: "Banque Populaire", logo: customers.bp },
    { name: "Bank Of Africa", logo: customers.boa },
    { name: "Bank Al-Maghrib", logo: customers.bam },
    { name: "Attijariwafa Bank", logo: customers.awb },
    { name: "CDG", logo: customers.cdg },
    { name: "ONCF", logo: customers.oncf },
    { name: "OCP", logo: customers.ocp },
    { name: "Marbio", logo: customers.marbio },
    { name: "LafargeHolcim", logo: customers.lafarge },
    { name: "Inwi", logo: customers.inwi }
  ],
  ar: [
    { name: "البنك الشعبي", logo: customers.bp },
    { name: "بنك أفريقيا", logo: customers.boa },
    { name: "بنك المغرب", logo: customers.bam },
    { name: "التجاري وفا بنك", logo: customers.awb },
    { name: "صندوق الإيداع والتدبير", logo: customers.cdg },
    { name: "المكتب الوطني للسكك الحديدية", logo: customers.oncf },
    { name: "المكتب الشريف للفوسفاط", logo: customers.ocp },
    { name: "ماربيو", logo: customers.marbio },
    { name: "لافارج هولسيم", logo: customers.lafarge },
    { name: "إنوي", logo: customers.inwi }
  ]
};

['en', 'fr', 'ar'].forEach(lang => {
  if (data[lang] && data[lang].technologyPartners) {
    data[lang].technologyPartners.partners = correctPartners[lang];
  }
  if (data[lang] && data[lang].customerReferences) {
    data[lang].customerReferences.references = correctCustomers[lang];
  }
});

fs.writeFileSync(dbPath, JSON.stringify(data, null, 2), 'utf8');
console.log('DB updated with reliable Google Favicon logos!');
