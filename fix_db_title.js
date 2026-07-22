const fs = require('fs');
const path = require('path');

const dbPath = path.join(process.cwd(), 'src/data/db.json');
const dbData = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

if (dbData.fr && dbData.fr.insights) {
    dbData.fr.insights.heroTitle = "ACTUALITÉS";
    fs.writeFileSync(dbPath, JSON.stringify(dbData, null, 2), 'utf8');
    console.log("Title shortened.");
}
