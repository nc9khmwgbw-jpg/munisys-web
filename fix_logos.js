const fs = require('fs');
const dbPath = './src/data/db.json';
let data = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

const logoMap = {
  'cisco': 'https://upload.wikimedia.org/wikipedia/commons/0/08/Cisco_logo_blue_2016.svg',
  'dell': 'https://upload.wikimedia.org/wikipedia/commons/1/18/Dell_logo_2016.svg',
  'vmware': 'https://upload.wikimedia.org/wikipedia/commons/9/9a/Vmware.svg',
  'fortinet': 'https://upload.wikimedia.org/wikipedia/commons/4/41/Fortinet_logo.svg',
  'microsoft': 'https://upload.wikimedia.org/wikipedia/commons/9/96/Microsoft_logo_%282012%29.svg',
  'ibm': 'https://upload.wikimedia.org/wikipedia/commons/5/51/IBM_logo.svg'
};

const languages = ['en', 'fr', 'ar'];

for (const lang of languages) {
  if (data[lang] && data[lang].technologyPartners && data[lang].technologyPartners.partners) {
    data[lang].technologyPartners.partners.forEach(partner => {
      if (partner.logo) {
        for (const [key, newUrl] of Object.entries(logoMap)) {
          if (partner.logo.includes(key + '.png')) {
            partner.logo = newUrl;
          }
        }
      }
    });
  }
}

fs.writeFileSync(dbPath, JSON.stringify(data, null, 2), 'utf8');
console.log('Database updated successfully with working Wikimedia logos!');
