const fs = require('fs');
const db = JSON.parse(fs.readFileSync('./src/data/db.json'));
db.en.homepage.sections.partnersTitle = "Technology partners";
db.en.homepage.sections.partnersDesc = "The IT infrastructures we design and deploy for our clients are at the heart of their business and determine their capacity to innovate and stand out. We build solid ties with our technology partners and acquire in-house expertise to ensure true resilience and agility.";
db.en.homepage.sections.partnersLinkText = "Read more";
fs.writeFileSync('./src/data/db.json', JSON.stringify(db, null, 2));
