# Guide d'utilisation du CMS Munisys

## 🚀 Démarrage rapide

### Démarrer le serveur de développement

```bash
npm run dev
```

Le site sera accessible sur `http://localhost:3000`

## 📝 Mode d'administration

### Activer le mode admin

1. Accédez à `http://localhost:3000/admin`
2. Vous serez automatiquement redirigé vers la page d'accueil en mode édition
3. Une barre d'outils apparaîtra en bas à droite de l'écran

### Désactiver le mode admin

Cliquez sur le bouton **🔒 Exit Admin Mode** dans la barre d'outils

## ✏️ Édition de contenu

### Éditer du texte

1. **Double-cliquez** sur n'importe quel texte surligné (avec un fond jaune)
2. Le texte devient éditable
3. Modifiez le contenu
4. Cliquez à l'extérieur du champ ou appuyez sur **Entrée** pour valider
5. Les modifications sont enregistrées localement

### 📷 Éditer des images

Les images éditables sont entourées d'un **cadre rouge pointillé** en mode admin.

**Pour changer une image :**

1. **Cliquez** sur l'image que vous souhaitez modifier
2. Une boîte de dialogue s'ouvre pour sélectionner un fichier
3. Choisissez votre nouvelle image
4. L'image est automatiquement téléchargée sur le serveur
5. L'aperçu est mis à jour immédiatement

**Effet visuel :**
- Au survol d'une image éditable, un overlay bordeaux apparaît avec l'icône 📷
- Pendant le téléchargement, un indicateur "⏳ Téléchargement..." s'affiche

### Images éditables sur la page d'accueil

Les images suivantes peuvent être modifiées en mode admin :

1. **Slide 2** (Hero) - Image de code/technologie
2. **Slide 3** (Hero) - Image de datacenter
3. **Section Solutions** - Image de datacenter principale
4. **Section Insights** - Image "New headquarters"

## 💾 Sauvegarder les modifications

1. Après avoir fait vos modifications (texte ou images), un bouton rouge **💾 Save Changes** apparaît
2. Cliquez sur ce bouton pour enregistrer toutes les modifications dans la base de données
3. Un message de confirmation apparaîtra
4. Les modifications seront visibles même après avoir quitté le mode admin

## 🎨 Modifier les couleurs du thème

En mode admin, un panneau **🎨 Theme Colors** est disponible dans la barre d'outils :

- **Bordeaux** - Couleur principale (boutons, accents)
- **Blue Dark** - Couleur bleue foncée (navigation, titres)
- **Blue Light** - Couleur bleue claire (accents secondaires)
- **Background Gray** - Couleur de fond grise
- **Text Gray** - Couleur du texte gris

Cliquez sur les sélecteurs de couleur pour modifier les couleurs en temps réel.

## 📂 Structure des images

### Images statiques
Les images par défaut se trouvent dans le dossier `public/` :
- `slide1.png` - Datacenter
- `slide2.png` - Headquarters
- `slide3.png` - Code/Technology

### Images téléchargées
Les nouvelles images téléchargées via le CMS sont stockées dans :
```
public/uploads/
```

Chaque image téléchargée reçoit un nom unique avec un timestamp pour éviter les conflits.

## 🗄️ Base de données

Les données du CMS sont stockées dans :
```
src/data/db.json
```

Ce fichier contient :
- Le contenu de toutes les pages
- Les configurations des couleurs
- Les chemins des images

## ⚠️ Notes importantes

1. **Sauvegardez régulièrement** : Cliquez sur "Save Changes" après chaque modification importante
2. **Formats d'images supportés** : JPG, PNG, WebP, GIF
3. **Taille recommandée** : Maximum 2-3 MB par image pour de meilleures performances
4. **Résolution recommandée** : 
   - Hero slides : 1920x1080px
   - Section images : 800x1000px
   - Insights : 600x600px

## 🔧 Développement

### Structure des composants CMS

- `CMSProvider.js` - Contexte global pour le mode admin
- `AdminToolbar.js` - Barre d'outils d'administration
- `InlineEditor.js` - Composant pour l'édition de texte
- `InlineImageEditor.js` - Composant pour l'édition d'images

### API Routes

- `GET /api/content` - Récupère toutes les données du CMS
- `POST /api/content` - Sauvegarde les modifications
- `POST /api/upload` - Télécharge une nouvelle image

## 🐛 Résolution de problèmes

### Les images ne se chargent pas
- Vérifiez que le dossier `public/uploads/` existe
- Vérifiez les permissions d'écriture
- Regardez la console du navigateur pour les erreurs

### Les modifications ne sont pas sauvegardées
- Assurez-vous d'avoir cliqué sur "Save Changes"
- Vérifiez que le fichier `src/data/db.json` est accessible en écriture
- Regardez la console du navigateur et du serveur pour les erreurs

### Le mode admin ne s'active pas
- Videz le cache du navigateur
- Vérifiez le localStorage (DevTools → Application → LocalStorage)
- Recherchez la clé `munisys_admin`

## 📞 Support

Pour toute question ou problème, contactez l'équipe de développement.
