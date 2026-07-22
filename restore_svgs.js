const fs = require('fs');

const dbPath = './src/data/db.json';
let data = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

const updatedCustomers = {
  "Attijariwafa Bank": '/images/logos/awb.svg',
  "Marbio": '/images/logos/marbio.svg',
  "Inwi": '/images/logos/inwi.svg'
};

['en', 'fr', 'ar'].forEach(lang => {
  if (data[lang] && data[lang].customerReferences) {
    data[lang].customerReferences.references.forEach(r => {
      // For Arabic, match by the English name key using index or direct replacement
      const englishNameKey = Object.keys(updatedCustomers).find(k => 
        k.toLowerCase() === r.name.toLowerCase() || 
        (r.name === 'التجاري وفا بنك' && k === 'Attijariwafa Bank') ||
        (r.name === 'ماربيو' && k === 'Marbio') ||
        (r.name === 'إنوي' && k === 'Inwi')
      );
      if (englishNameKey) {
        r.logo = updatedCustomers[englishNameKey];
      }
    });
  }
});

fs.writeFileSync(dbPath, JSON.stringify(data, null, 2), 'utf8');
console.log('Restored beautiful text SVGs for AWB, Marbio, Inwi!');
