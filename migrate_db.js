const fs = require('fs');
const path = require('path');

const dbPath = path.join(process.cwd(), 'src/data/db.json');
const currentData = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

if (!currentData.en && !currentData.fr) {
    const newData = {
        en: currentData,
        fr: JSON.parse(JSON.stringify(currentData)) // deep copy
    };
    
    // Simple translation for FR to make it obvious it works
    if (newData.fr.navigation && newData.fr.navigation.links) {
        newData.fr.navigation.links.solutions = "Solutions (FR)";
        newData.fr.navigation.links.contact = "Contactez-nous";
    }
    if (newData.fr.homepage) {
        newData.fr.homepage.heroLabel = "VOTRE PARTENAIRE DE CONFIANCE";
    }

    fs.writeFileSync(dbPath, JSON.stringify(newData, null, 2), 'utf8');
    console.log("Migration successful.");
} else {
    console.log("Already migrated.");
}
