const fs = require('fs');

const dbPath = './src/data/db.json';

const makeBase64Uri = (svg) => 'data:image/svg+xml;base64,' + Buffer.from(svg).toString('base64');
const makeLogo = (text, color) => makeBase64Uri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 60"><text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" font-family="Arial" font-weight="bold" font-size="24" fill="${color}">${text}</text></svg>`);

const getWikiUrl = (filename) => `https://commons.wikimedia.org/wiki/Special:FilePath/${filename}`;

const partners = {
  cisco: getWikiUrl('Cisco_logo_blue_2016.svg'),
  dell: getWikiUrl('Dell_logo_2016.svg'),
  vmware: makeLogo('vmware', '#607078'),
  fortinet: makeLogo('FORTINET', '#E31837'),
  microsoft: getWikiUrl('Microsoft_logo_(2012).svg'),
  ibm: getWikiUrl('IBM_logo.svg')
};

const customers = {
  bp: getWikiUrl('Banque_populaire_(Maroc).svg'),
  boa: getWikiUrl('Bank_of_Africa_Logo.svg'),
  bam: makeLogo('Bank Al-Maghrib', '#1a4e2b'),
  awb: getWikiUrl('Attijariwafa_Bank_logo.svg'),
  cdg: getWikiUrl('Caisse_de_Dépôt_et_de_Gestion.svg'),
  oncf: getWikiUrl('ONCF_Logo.svg'),
  ocp: getWikiUrl('OCP_Group_logo.svg'),
  marbio: makeLogo('Marbio', '#3f80c0'),
  lafarge: getWikiUrl('LafargeHolcim_logo.svg'),
  inwi: getWikiUrl('Inwi_logo.svg')
};

let data = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

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
console.log('DB updated with Official Wikipedia SVGs!');
