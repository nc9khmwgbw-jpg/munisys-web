const fs = require('fs');
const path = require('path');
const dbPath = path.join(__dirname, 'src/data/db.json');

const db = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

// 1. About Us
if (!db.fr.aboutUs) db.fr.aboutUs = {};
db.fr.aboutUs.subtitle = "NOTRE HÉRITAGE";
db.fr.aboutUs.title = "Stimuler la Transformation Numérique";
db.fr.aboutUs.storyTitle = "Qui Nous Sommes";
db.fr.aboutUs.storyP1 = "Depuis notre création, Munisys est à la pointe de l'innovation technologique. Nous pensons que la technologie est le grand égalisateur, permettant aux entreprises de se développer, de sécuriser leurs actifs et de rationaliser leurs opérations comme jamais auparavant.";
db.fr.aboutUs.storyP2 = "Notre équipe d'experts certifiés travaille sans relâche pour concevoir et déployer des solutions informatiques qui ne sont pas seulement robustes, mais aussi prêtes pour l'avenir. En combinant une expertise sectorielle approfondie avec des outils de pointe, nous transformons des défis complexes en expériences numériques fluides.";
db.fr.aboutUs.timelineTitle = "Notre Parcours";
db.fr.aboutUs.timeline = [
    { year: '1993', text: 'Fondation avec la vision de fournir des services d\'infrastructure informatique exceptionnels aux entreprises locales.' },
    { year: '2005', text: 'Élargissement de notre portefeuille pour inclure la cybersécurité avancée et l\'intégration réseau.' },
    { year: '2015', text: 'Lancement de notre division de services gérés, offrant une surveillance proactive 24/7 et un SOC.' },
    { year: '2024', text: 'Leader de l\'ère de la transformation Cloud-Native, en partenariat avec les géants mondiaux de la technologie.' }
];

// 2. Services
if (!db.fr.services) db.fr.services = {};
db.fr.services.heroLabel = "SERVICES PROFESSIONNELS";
db.fr.services.heroDesc = "Des services professionnels et opérationnels pour vous aider à faire les bons choix technologiques, concevoir des architectures solides et les déployer selon les meilleures pratiques.";
db.fr.services.sections = [
  {
    "title": "Intégration Informatique & Support",
    "items": [
      {
        "title": "Services d'intégration",
        "desc": "À chaque étape du cycle de vie de vos infrastructures informatiques, nos services professionnels et opérationnels vous aideront à faire les bons choix technologiques, à concevoir les architectures les plus solides et à les déployer selon les règles de l'art.",
        "features": ["Conception et validation d'architecture", "Déploiement technologique", "Migration de systèmes et mises à niveau"]
      },
      {
        "title": "Services de support informatique",
        "desc": "Nos services de support réactifs et proactifs garantissent une disponibilité et une continuité maximales de vos actifs technologiques. Nous atténuons les risques grâce à des SLA stricts et une expertise technique dédiée.",
        "features": ["Gestion des incidents 24/7", "Bilans de santé proactifs", "Remplacement matériel (RMA)"]
      },
      {
        "title": "Services Gérés",
        "desc": "Déléguez vos opérations informatiques quotidiennes à nos experts certifiés, permettant à vos équipes internes de se concentrer sur les initiatives stratégiques. Nous assurons une surveillance 24/7 et une maintenance proactive.",
        "features": ["Surveillance des infrastructures", "Centre des opérations de sécurité (SOC)", "Gestion du cloud et des sauvegardes"]
      }
    ]
  }
];

// 3. Homepage new keys
if (!db.fr.homepage) db.fr.homepage = {};
db.fr.homepage.slides = db.fr.homepage.slides || [];
if(db.fr.homepage.slides[0]) { db.fr.homepage.slides[0].meta = "INFRASTRUCTURE CENTRALE"; db.fr.homepage.slides[0].desc = "Configurez, gérez et sécurisez vos calculs, votre stockage et vos réseaux..."; db.fr.homepage.slides[0].btnText = "NOS SOLUTIONS"; }
if(db.fr.homepage.slides[1]) { db.fr.homepage.slides[1].meta = "CYBERSÉCURITÉ"; db.fr.homepage.slides[1].desc = "Protégez votre réseau, vos terminaux et vos données contre les menaces numériques évolutives..."; db.fr.homepage.slides[1].btnText = "DÉCOUVRIR LA SÉCURITÉ"; }
if(db.fr.homepage.slides[2]) { db.fr.homepage.slides[2].meta = "SERVICES GÉRÉS"; db.fr.homepage.slides[2].desc = "Intégration cloud transparente et services gérés pour accélérer votre parcours de transformation numérique..."; db.fr.homepage.slides[2].btnText = "SERVICES CLOUD"; }

db.fr.homepage.solutions = [
  { title: 'Technologies<br/>d\'infrastructure', desc: 'Infrastructures de calcul, stockage, réseaux et sauvegarde sur centres de données et clouds publics.', btn: 'Lire la suite', span: 'span-2x3' },
  { title: 'Solutions de<br/>Cybersécurité', desc: 'Architectures de sécurité de bout en bout pour protéger vos actifs numériques contre les menaces.', btn: 'Découvrir', span: 'span-2x1' },
  { title: 'Logiciels<br/>d\'infrastructure', desc: 'Approches définies par logiciel et intégrations robustes pour les opérations informatiques modernes.', btn: '', span: 'glass' },
  { title: 'Services Cloud<br/>& Gérés', desc: 'Opérations transparentes sur des environnements cloud publics, privés et hybrides.', btn: '', span: 'glass' },
  { title: 'Solutions<br/>de terminaux', desc: 'Dotant votre personnel d\'appareils informatiques sécurisés et efficaces.', btn: 'Voir les offres', span: 'span-2x1' }
];

fs.writeFileSync(dbPath, JSON.stringify(db, null, 2));
console.log('Successfully seeded French translations!');
