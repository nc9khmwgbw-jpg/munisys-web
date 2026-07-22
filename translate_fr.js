const fs = require('fs');
const path = require('path');

const dbPath = path.join(process.cwd(), 'src/data/db.json');
const dbData = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

if (dbData.fr) {
    if (dbData.fr.homepage) {
        if (dbData.fr.homepage.slides) {
            dbData.fr.homepage.slides[0].h1 = "Nous orchestrons l'<strong>infrastructure numérique</strong> pour une <strong>expérience unique</strong>";
            dbData.fr.homepage.slides[1].h1 = "Nous concevons des <strong>architectures et intégrations</strong> pour votre <strong>informatique future</strong>";
            dbData.fr.homepage.slides[2].h1 = "Nous gérons <strong>votre infrastructure numérique</strong> pour que vous puissiez innover";
        }
        dbData.fr.homepage.heroCTALabel = "Nos solutions";
        if (dbData.fr.homepage.services) {
            dbData.fr.homepage.services[0].title = "Services d'intégration IT";
            dbData.fr.homepage.services[0].body = "À chaque étape du cycle de vie de vos infrastructures, nous vous accompagnons.";
            dbData.fr.homepage.services[1].title = "Services gérés et Cloud";
            dbData.fr.homepage.services[1].body = "Déléguez la gestion de votre IT pour vous concentrer sur l'essentiel.";
            dbData.fr.homepage.services[2].title = "Réseaux et Sécurité";
            dbData.fr.homepage.services[2].body = "Protégez vos données et connectez vos équipes en toute sécurité.";
        }
        dbData.fr.homepage.servicesTitle = "Services Informatiques";
        dbData.fr.homepage.servicesSubtitle = "NOTRE EXPERTISE";
        dbData.fr.homepage.aboutTitle = "À propos de Munisys";
        dbData.fr.homepage.aboutBody = "Munisys est un leader dans l'intégration de solutions informatiques.";
        dbData.fr.homepage.stats = [
            { "val": "30+", "label": "Années d'expérience" },
            { "val": "500+", "label": "Clients satisfaits" },
            { "val": "50+", "label": "Partenaires technologiques" }
        ];
    }
    
    fs.writeFileSync(dbPath, JSON.stringify(dbData, null, 2), 'utf8');
    console.log("Translation applied.");
}
