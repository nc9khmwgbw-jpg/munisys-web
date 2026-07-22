const fs = require('fs');

const dbPath = './src/data/db.json';
let data = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

const updatedCustomers = {
  "Bank Al-Maghrib": '/images/logos/bam.png',
  "Attijariwafa Bank": '/images/logos/awb.png',
  "Marbio": '/images/logos/marbio.png',
  "Inwi": '/images/logos/inwi.png'
};

['en', 'fr', 'ar'].forEach(lang => {
  if (data[lang] && data[lang].customerReferences) {
    data[lang].customerReferences.references.forEach(r => {
      const englishNameKey = Object.keys(updatedCustomers).find(k => k.toLowerCase() === r.name.toLowerCase() || (lang === 'ar' && updatedCustomers[k]));
      if (englishNameKey) r.logo = updatedCustomers[englishNameKey];
    });
  }
});

fs.writeFileSync(dbPath, JSON.stringify(data, null, 2), 'utf8');
console.log('Database updated with final PNG favicons!');
