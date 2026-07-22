const fs = require('fs');
const dbPath = './src/data/db.json';
const db = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

const enPartners = db.en.technologyPartners.partners;
const arPartnersStr = db.ar.technologyPartners.partners;

const arPartnersObj = [];

for (let i = 0; i < enPartners.length; i++) {
    arPartnersObj.push({
        name: typeof arPartnersStr[i] === 'string' ? arPartnersStr[i] : (arPartnersStr[i]?.name || enPartners[i].name),
        logo: enPartners[i].logo,
        link: enPartners[i].link,
        category: enPartners[i].category
    });
}

db.ar.technologyPartners.partners = arPartnersObj;

fs.writeFileSync(dbPath, JSON.stringify(db, null, 2));
console.log('Fixed technologyPartners in db.ar');
