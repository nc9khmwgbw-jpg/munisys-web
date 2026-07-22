const fs = require('fs');

const dbPath = './src/data/db.json';

const makeBase64Uri = (svg) => 'data:image/svg+xml;base64,' + Buffer.from(svg).toString('base64');

// Simple elegant text SVGs for the 10 customers
const makeLogo = (text, color) => makeBase64Uri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 60"><text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" font-family="Arial" font-weight="bold" font-size="24" fill="${color}">${text}</text></svg>`);

const svgs = {
  bp: makeLogo('Banque Populaire', '#c46927'),
  boa: makeLogo('Bank Of Africa', '#003b70'),
  bam: makeLogo('Bank Al-Maghrib', '#1a4e2b'),
  awb: makeLogo('Attijariwafa', '#d88b22'),
  cdg: makeLogo('CDG', '#003366'),
  oncf: makeLogo('ONCF', '#f37021'),
  ocp: makeLogo('OCP', '#007849'),
  marbio: makeLogo('Marbio', '#3f80c0'),
  lafarge: makeLogo('LafargeHolcim', '#000000'),
  inwi: makeLogo('inwi', '#e3000f')
};

let data = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

const customersBase = [
  { id: 'bp', en: "Banque Populaire", fr: "Banque Populaire", ar: "البنك الشعبي", logo: svgs.bp },
  { id: 'boa', en: "Bank Of Africa", fr: "Bank Of Africa", ar: "بنك أفريقيا", logo: svgs.boa },
  { id: 'bam', en: "Bank Al-Maghrib", fr: "Bank Al-Maghrib", ar: "بنك المغرب", logo: svgs.bam },
  { id: 'awb', en: "Attijariwafa Bank", fr: "Attijariwafa Bank", ar: "التجاري وفا بنك", logo: svgs.awb },
  { id: 'cdg', en: "CDG", fr: "CDG", ar: "صندوق الإيداع والتدبير", logo: svgs.cdg },
  { id: 'oncf', en: "ONCF", fr: "ONCF", ar: "المكتب الوطني للسكك الحديدية", logo: svgs.oncf },
  { id: 'ocp', en: "OCP", fr: "OCP", ar: "المكتب الشريف للفوسفاط", logo: svgs.ocp },
  { id: 'marbio', en: "Marbio", fr: "Marbio", ar: "ماربيو", logo: svgs.marbio },
  { id: 'lafarge', en: "LafargeHolcim", fr: "LafargeHolcim", ar: "لافارج هولسيم", logo: svgs.lafarge },
  { id: 'inwi', en: "Inwi", fr: "Inwi", ar: "إنوي", logo: svgs.inwi }
];

['en', 'fr', 'ar'].forEach(lang => {
  if (!data[lang].customerReferences) {
    data[lang].customerReferences = {};
  }
  
  // Standardize the references array
  data[lang].customerReferences.references = customersBase.map(c => ({
    name: c[lang],
    logo: c.logo
  }));
});

// Also fix the french headers that are in English
if (data.fr.customerReferences) {
  data.fr.customerReferences.heroLabel = "Références Clients";
  data.fr.customerReferences.heroTitle = "Références Clients";
  data.fr.customerReferences.heroDesc = "Un conseiller de confiance au service d'un grand nombre d'acteurs économiques et d'entités publiques de premier plan au Maroc.";
}

fs.writeFileSync(dbPath, JSON.stringify(data, null, 2), 'utf8');
console.log('Customer References standardizes with Base64 logos!');
