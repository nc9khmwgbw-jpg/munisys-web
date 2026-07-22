const fs = require('fs');
const path = require('path');

const dbPath = path.join(process.cwd(), 'src/data/db.json');
const dbData = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

if (dbData.fr && dbData.fr.navigation && dbData.fr.navigation.mainLinks) {
    const translations = {
        "Services": "Services",
        "Customer references": "Références clients",
        "Technology partners": "Partenaires technologiques",
        "Insights": "Actualités",
        "Careers": "Carrières",
        "About us": "À propos"
    };
    
    dbData.fr.navigation.mainLinks.forEach(link => {
        if (translations[link.label]) {
            link.label = translations[link.label];
        }
    });
    
    fs.writeFileSync(dbPath, JSON.stringify(dbData, null, 2), 'utf8');
    console.log("Navigation translated.");
}
