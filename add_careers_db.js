const fs = require('fs');
const path = require('path');

const dbPath = path.join(process.cwd(), 'src/data/db.json');
const dbData = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

const careersDataEN = {
    heroTitle: "Shape<br>the future",
    heroDesc: "Join Munisys and work with cutting-edge infrastructure technologies to accelerate digital transformation for major enterprises.",
    whyTitle: "Why Munisys?",
    whyDesc: "We provide an environment where excellence meets innovation. Grow your career with industry leaders.",
    reasons: [
        { title: "Innovation", desc: "Work with cutting-edge technologies and shape the digital transformation of major enterprises in Morocco and beyond." },
        { title: "Excellence", desc: "Join a team of highly certified experts and evolve in a culture of continuous learning and high performance." },
        { title: "Impact", desc: "Your work will directly secure and optimize the core infrastructure of the largest institutions in the region." }
    ],
    jobsTitle: "Open Positions",
    jobs: [
        { title: "Senior Cloud Architect", meta: "Rabat • Full-Time" },
        { title: "Network Security Engineer", meta: "Casablanca • Full-Time" }
    ]
};

const careersDataFR = {
    heroTitle: "Façonnez<br>l'avenir",
    heroDesc: "Rejoignez Munisys et travaillez avec des technologies d'infrastructure de pointe pour accélérer la transformation numérique des grandes entreprises.",
    whyTitle: "Pourquoi Munisys ?",
    whyDesc: "Nous offrons un environnement où l'excellence rencontre l'innovation. Développez votre carrière avec les leaders du secteur.",
    reasons: [
        { title: "Innovation", desc: "Travaillez avec des technologies de pointe et façonnez la transformation numérique des grandes entreprises au Maroc et au-delà." },
        { title: "Excellence", desc: "Rejoignez une équipe d'experts hautement certifiés et évoluez dans une culture d'apprentissage continu et de haute performance." },
        { title: "Impact", desc: "Votre travail sécurisera et optimisera directement l'infrastructure de base des plus grandes institutions de la région." }
    ],
    jobsTitle: "Postes Ouverts",
    jobs: [
        { title: "Architecte Cloud Senior", meta: "Rabat • Temps Plein" },
        { title: "Ingénieur Sécurité Réseau", meta: "Casablanca • Temps Plein" }
    ]
};

dbData.en.careers = careersDataEN;
dbData.fr.careers = careersDataFR;

fs.writeFileSync(dbPath, JSON.stringify(dbData, null, 2), 'utf8');
console.log("Careers data added.");
