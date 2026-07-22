const fs = require('fs');
const path = require('path');

const dbPath = path.join(process.cwd(), 'src/data/db.json');
const dbData = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

if (dbData.fr && dbData.fr.insights && dbData.fr.insights.posts) {
    dbData.fr.insights.posts[0].category = "ACTUALITÉS";
    dbData.fr.insights.posts[0].title = "UNE NOUVELLE IDENTITÉ VISUELLE POUR MUNISYS";
    dbData.fr.insights.posts[1].category = "ENTREPRISE";
    dbData.fr.insights.posts[1].title = "OUVERTURE DU NOUVEAU SIÈGE SOCIAL";
    dbData.fr.insights.posts[2].category = "COMMUNIQUÉ";
    dbData.fr.insights.posts[2].title = "PARTENARIAT D'INVESTISSEMENT HELIOS - FIPAR";
    dbData.fr.insights.posts[3].category = "ÉVÉNEMENT";
    dbData.fr.insights.posts[3].title = "MUNISYS LANCE LA NOUVELLE SOLUTION CLOUD";
    
    fs.writeFileSync(dbPath, JSON.stringify(dbData, null, 2), 'utf8');
    console.log("Insights translated.");
}
