# Munisys CMS - Site Web Entreprise avec CMS Intégré

Site web corporate pour Munisys avec un système de gestion de contenu (CMS) intégré permettant l'édition en ligne du contenu et des images.

## 🚀 Démarrage rapide

### Installation

```bash
npm install
```

### Lancer le serveur de développement

```bash
npm run dev
```

Ouvrez [http://localhost:3000](http://localhost:3000) dans votre navigateur.

## 📝 Utilisation du CMS

### Activer le mode administration

1. Accédez à `http://localhost:3000/admin`
2. Le mode édition s'active automatiquement
3. Vous êtes redirigé vers la page d'accueil

### Éditer du contenu

**Texte :**
- Double-cliquez sur n'importe quel texte surligné
- Modifiez le contenu
- Cliquez à l'extérieur ou appuyez sur Entrée

**Images :**
- Cliquez sur une image avec un cadre rouge pointillé
- Sélectionnez une nouvelle image
- L'image est téléchargée automatiquement

### Sauvegarder

Cliquez sur le bouton **💾 Save Changes** qui apparaît après vos modifications.

## 📖 Documentation complète

Pour plus d'informations, consultez le [Guide CMS complet](./CMS_GUIDE.md).

## 🛠️ Technologies utilisées

- **Next.js 16** - Framework React
- **React 19** - Bibliothèque UI
- **Mongoose** - ODM MongoDB (optionnel)
- **Tailwind CSS** - Framework CSS

## 📂 Structure du projet

```
munisys-cms-full/
├── src/
│   ├── app/              # Pages Next.js
│   ├── components/       # Composants React (CMS inclus)
│   ├── data/            # Base de données JSON
│   ├── hooks/           # Custom React hooks
│   ├── lib/             # Utilitaires
│   └── models/          # Modèles de données
├── public/              # Fichiers statiques
│   └── uploads/         # Images téléchargées via le CMS
└── CMS_GUIDE.md         # Guide d'utilisation du CMS
```

## 🎨 Fonctionnalités CMS

- ✏️ Édition de texte en ligne
- 📷 Gestion des images avec upload
- 🎨 Personnalisation des couleurs du thème
- 💾 Sauvegarde automatique des modifications
- 🔒 Mode admin sécurisé

## 🔧 Scripts disponibles

```bash
npm run dev      # Démarre le serveur de développement
npm run build    # Compile l'application pour la production
npm run start    # Démarre le serveur de production
npm run lint     # Vérifie le code avec ESLint
```

## 📞 Support

Pour toute question concernant le CMS, consultez le [CMS_GUIDE.md](./CMS_GUIDE.md) ou contactez l'équipe de développement.
