const fs = require('fs');

const dbPath = './src/data/db.json';
let data = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

const updatedCustomers = {
  "Banque Populaire": '/images/logos/bp.svg',
  "Bank Of Africa": '/images/logos/boa.png',
  "Bank Al-Maghrib": '/images/logos/bam.svg',
  "Attijariwafa Bank": '/images/logos/awb.svg',
  "CDG": '/images/logos/cdg.svg',
  "ONCF": '/images/logos/oncf.png',
  "OCP": '/images/logos/ocp.svg',
  "Marbio": '/images/logos/marbio.svg',
  "LafargeHolcim": '/images/logos/lafarge.svg',
  "Inwi": '/images/logos/inwi.svg'
};

['en', 'fr', 'ar'].forEach(lang => {
  if (data[lang] && data[lang].customerReferences) {
    data[lang].customerReferences.references.forEach(r => {
      const englishNameKey = Object.keys(updatedCustomers).find(k => k.toLowerCase() === r.name.toLowerCase() || (lang === 'ar' && updatedCustomers[k]));
      if (englishNameKey) r.logo = updatedCustomers[englishNameKey];
    });
    if (lang === 'ar') {
       const refs = data[lang].customerReferences.references;
       refs[0].logo = updatedCustomers['Banque Populaire'];
       refs[1].logo = updatedCustomers['Bank Of Africa'];
       refs[2].logo = updatedCustomers['Bank Al-Maghrib'];
       refs[3].logo = updatedCustomers['Attijariwafa Bank'];
       refs[4].logo = updatedCustomers['CDG'];
       refs[5].logo = updatedCustomers['ONCF'];
       refs[6].logo = updatedCustomers['OCP'];
       refs[7].logo = updatedCustomers['Marbio'];
       refs[8].logo = updatedCustomers['LafargeHolcim'];
       refs[9].logo = updatedCustomers['Inwi'];
    }
  }
});

['en', 'fr', 'ar'].forEach(lang => {
  if (data[lang] && data[lang].technologyPartners) {
    data[lang].technologyPartners.partners.forEach(p => {
      if (p.name.toLowerCase().includes('vmware') || p.name.includes('في إم وير')) p.logo = '/images/logos/vmware.svg';
      if (p.name.toLowerCase().includes('fortinet') || p.name.includes('فورتينت')) p.logo = '/images/logos/fortinet.svg';
    });
  }
});

fs.writeFileSync(dbPath, JSON.stringify(data, null, 2), 'utf8');
console.log('Database finalized with perfect local paths!');
