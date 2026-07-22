# 📚 Index de la Documentation - Munisys CMS

Bienvenue dans la documentation du CMS Munisys ! Utilisez cet index pour trouver rapidement l'information dont vous avez besoin.

---

## 🚀 Démarrage Rapide

**Vous êtes nouveau ?** Commencez par ici :

1. 📖 **[README.md](./README.md)** - Vue d'ensemble et installation
2. 🎯 **[GUIDE_RAPIDE.md](./GUIDE_RAPIDE.md)** - Comment éditer les images (5 min)
3. 🧪 Page de test : `http://localhost:3000/test-images`

**Commandes essentielles :**
```bash
npm install          # Installation
npm run dev          # Démarrer le serveur
node check-cms.js    # Vérifier la configuration
```

---

## 📖 Documentation Complète

### 🏠 Pour les Utilisateurs

| Document | Quand l'utiliser | Temps de lecture |
|----------|------------------|------------------|
| **[README.md](./README.md)** | Premier contact avec le projet | 3 min |
| **[GUIDE_RAPIDE.md](./GUIDE_RAPIDE.md)** | Besoin d'éditer une image rapidement | 5 min |
| **[CMS_GUIDE.md](./CMS_GUIDE.md)** | Guide complet de toutes les fonctionnalités | 15 min |
| **[RÉSUMÉ_DES_MODIFICATIONS.md](./RÉSUMÉ_DES_MODIFICATIONS.md)** | Voir ce qui a été changé récemment | 5 min |

### 🔧 Pour les Développeurs

| Document | Quand l'utiliser | Temps de lecture |
|----------|------------------|------------------|
| **[IMAGES_MAP.md](./IMAGES_MAP.md)** | Trouver où sont les images dans le code | 10 min |
| **[AGENTS.md](./AGENTS.md)** | Règles spécifiques Next.js | 2 min |
| **[CLAUDE.md](./CLAUDE.md)** | Documentation technique (si existe) | Variable |

### 🛠️ Outils

| Fichier | Description | Comment l'utiliser |
|---------|-------------|-------------------|
| **check-cms.js** | Vérifier la configuration | `node check-cms.js` |

---

## 🎯 Navigation par Besoin

### "Je veux éditer le contenu de mon site"

1. Démarrez le serveur : `npm run dev`
2. Allez sur `/admin` pour activer le mode édition
3. Consultez le **[GUIDE_RAPIDE.md](./GUIDE_RAPIDE.md)**

**Pages disponibles :**
- `/` - Page d'accueil (4 images éditables)
- `/test-images` - Page de test (8 images éditables)

---

### "Je veux comprendre comment fonctionne le CMS"

Lisez dans cet ordre :
1. **[README.md](./README.md)** - Architecture générale
2. **[CMS_GUIDE.md](./CMS_GUIDE.md)** - Fonctionnalités détaillées
3. **[IMAGES_MAP.md](./IMAGES_MAP.md)** - Structure des images

---

### "Je veux ajouter de nouvelles images éditables"

Consultez **[IMAGES_MAP.md](./IMAGES_MAP.md)** section :
- "Comment ajouter une nouvelle image éditable"

**Étapes rapides :**
1. Ajoutez l'entrée dans `src/data/db.json`
2. Utilisez `<InlineImageEditor>` dans votre composant
3. Testez en mode admin

---

### "J'ai un problème"

1. **Problème d'affichage ?** → [GUIDE_RAPIDE.md - Dépannage](./GUIDE_RAPIDE.md#-dépannage)
2. **Erreur technique ?** → [CMS_GUIDE.md - Résolution de problèmes](./CMS_GUIDE.md#-résolution-de-problèmes)
3. **Configuration ?** → Lancez `node check-cms.js`

---

## 📂 Structure du Projet

```
munisys-cms-full/
│
├── 📚 DOCUMENTATION
│   ├── README.md                          ← Commencez ici !
│   ├── GUIDE_RAPIDE.md                    ← Édition d'images
│   ├── CMS_GUIDE.md                       ← Guide complet
│   ├── IMAGES_MAP.md                      ← Carte des images
│   ├── RÉSUMÉ_DES_MODIFICATIONS.md        ← Changelog
│   └── DOCUMENTATION_INDEX.md (ce fichier) ← Vous êtes ici
│
├── 🛠️ OUTILS
│   └── check-cms.js                       ← Script de vérification
│
├── 📁 SOURCE CODE
│   ├── src/
│   │   ├── app/                          ← Pages Next.js
│   │   │   ├── page.js                   ← Page d'accueil (4 images)
│   │   │   ├── test-images/page.js       ← Page de test (8 images)
│   │   │   ├── admin/page.js             ← Active le mode admin
│   │   │   └── api/                      ← Routes API
│   │   │       ├── content/route.js      ← GET/POST data
│   │   │       └── upload/route.js       ← Upload images
│   │   │
│   │   ├── components/                   ← Composants React
│   │   │   ├── CMSProvider.js            ← Contexte global
│   │   │   ├── AdminToolbar.js           ← Barre d'outils admin
│   │   │   ├── InlineEditor.js           ← Édition de texte
│   │   │   └── InlineImageEditor.js      ← Édition d'images ⭐
│   │   │
│   │   └── data/
│   │       └── db.json                   ← Base de données
│   │
│   └── public/
│       ├── slide1.png, slide2.png, ...   ← Images par défaut
│       └── uploads/                       ← Images téléchargées ⭐
│
└── 📦 CONFIGURATION
    ├── package.json                       ← Dépendances
    ├── next.config.mjs                    ← Config Next.js
    └── ...
```

---

## 🎓 Parcours d'Apprentissage

### Niveau 1 : Débutant (30 min)
1. ✅ Lire le **README.md**
2. ✅ Lancer `npm run dev`
3. ✅ Aller sur `/admin` puis tester sur `/test-images`
4. ✅ Éditer une image
5. ✅ Sauvegarder avec Ctrl+S

### Niveau 2 : Utilisateur (1h)
1. ✅ Lire le **GUIDE_RAPIDE.md**
2. ✅ Éditer les 4 images de la page d'accueil
3. ✅ Modifier les couleurs du thème
4. ✅ Éditer du texte
5. ✅ Lire le **CMS_GUIDE.md**

### Niveau 3 : Avancé (2h)
1. ✅ Lire **IMAGES_MAP.md**
2. ✅ Comprendre la structure du code
3. ✅ Ajouter une nouvelle image éditable
4. ✅ Tester sur la page de test
5. ✅ Explorer les routes API

---

## 🔍 Recherche Rapide

### Commandes
- Démarrer : `npm run dev`
- Vérifier : `node check-cms.js`
- Build : `npm run build`

### URLs Importantes
- Page d'accueil : `http://localhost:3000/`
- Mode admin : `http://localhost:3000/admin`
- Page de test : `http://localhost:3000/test-images`

### Composants Clés
- `InlineImageEditor` : Édition d'images
- `InlineEditor` : Édition de texte
- `CMSProvider` : Contexte global
- `AdminToolbar` : Barre d'outils

### Fichiers Importants
- `src/data/db.json` : Base de données
- `src/components/InlineImageEditor.js` : Composant images
- `src/app/api/upload/route.js` : API upload
- `public/uploads/` : Dossier des uploads

---

## 📱 Raccourcis

| Action | Document | Section |
|--------|----------|---------|
| Installer le projet | README.md | Installation |
| Éditer une image | GUIDE_RAPIDE.md | Étape 3 |
| Changer les couleurs | CMS_GUIDE.md | Modifier les couleurs |
| Résoudre un problème | CMS_GUIDE.md | Résolution de problèmes |
| Ajouter une image | IMAGES_MAP.md | Comment ajouter |
| Voir les changements | RÉSUMÉ_DES_MODIFICATIONS.md | - |

---

## ❓ FAQ Rapide

**Q : Comment activer le mode admin ?**  
R : Allez sur `/admin`

**Q : Où sont les images téléchargées ?**  
R : Dans `public/uploads/`

**Q : Comment sauvegarder ?**  
R : Cliquez sur "💾 Save Changes" ou appuyez sur Ctrl+S

**Q : Combien d'images sont éditables ?**  
R : 4 sur la page d'accueil, 8 sur la page de test

**Q : Le système est-il en production ?**  
R : Non, c'est un environnement de développement. Pour la production, lancez `npm run build` puis `npm start`

---

## 🎯 Prochaines Étapes Recommandées

1. ⬜ Lire le README.md
2. ⬜ Démarrer le serveur (`npm run dev`)
3. ⬜ Activer le mode admin (`/admin`)
4. ⬜ Tester l'édition d'images (`/test-images`)
5. ⬜ Lire le GUIDE_RAPIDE.md
6. ⬜ Éditer une image sur la page d'accueil
7. ⬜ Lire le CMS_GUIDE.md complet
8. ⬜ Explorer IMAGES_MAP.md

---

## 📞 Support

- **Documentation complète** : Consultez les fichiers MD dans ce dossier
- **Script de diagnostic** : `node check-cms.js`
- **Console de débogage** : F12 dans le navigateur

---

**Dernière mise à jour :** 2 juillet 2026  
**Version :** 1.0  
**Nombre de documents :** 6  
**Nombre de pages éditables :** 2 (accueil + test)  
**Nombre d'images éditables :** 12 (4 + 8)

🎉 **Bonne utilisation du CMS Munisys !**
