# 🚀 Guide Rapide - Édition d'Images

## Comment changer les images de la page d'accueil

### Étape 1 : Activer le mode admin

1. Ouvrez votre navigateur
2. Allez sur : `http://localhost:3000/admin`
3. Vous serez redirigé automatiquement vers la page d'accueil

### Étape 2 : Identifier les images éditables

En mode admin, vous verrez :
- ✏️ Une barre d'outils en bas à droite
- 🎨 Un panneau de couleurs
- 📝 Les textes avec un fond jaune au survol
- **📷 Les images avec un cadre rouge pointillé**

### Étape 3 : Changer une image

1. **Survolez une image** avec un cadre rouge pointillé
   - Un overlay bordeaux apparaît
   - L'icône 📷 et "Cliquez pour changer" s'affichent

2. **Cliquez sur l'image**
   - Une boîte de dialogue de sélection de fichier s'ouvre

3. **Sélectionnez votre nouvelle image**
   - Formats acceptés : JPG, PNG, WebP, GIF
   - Taille recommandée : max 2-3 MB

4. **Attendez le téléchargement**
   - Un indicateur "⏳ Téléchargement..." apparaît
   - L'image est uploadée sur le serveur
   - L'aperçu se met à jour automatiquement

5. **Sauvegardez**
   - Un bouton rouge **💾 Save Changes** apparaît en bas à droite
   - Cliquez dessus pour enregistrer définitivement

### Images disponibles sur la page d'accueil

#### 1. Hero Slider - Slide 2
- **Position** : Deuxième slide du carousel en haut
- **Image actuelle** : Code/Technologie (`slide3.png`)
- **Utilisation** : Illustration technologique

#### 2. Hero Slider - Slide 3
- **Position** : Troisième slide du carousel
- **Image actuelle** : Datacenter (`slide1.png`)
- **Utilisation** : Infrastructure IT

#### 3. Section Solutions
- **Position** : Section bleue foncée "Solutions"
- **Image actuelle** : Datacenter (`slide1.png`)
- **Utilisation** : Illustration des solutions d'infrastructure

#### 4. Section Insights - Card 2
- **Position** : Deuxième carte dans la section "Insights"
- **Image actuelle** : Headquarters (`slide2.png`)
- **Utilisation** : "New headquarters"

## ⚠️ Points importants

### ✅ À faire
- Vérifier la qualité de l'image avant de l'uploader
- Utiliser des images optimisées pour le web
- Sauvegarder après chaque modification
- Tester l'affichage sur différentes tailles d'écran

### ❌ À éviter
- Images trop lourdes (> 3 MB)
- Résolutions trop faibles (< 800px de largeur)
- Oublier de cliquer sur "Save Changes"
- Fermer le navigateur sans sauvegarder

## 🎯 Résolutions recommandées

| Zone | Résolution recommandée | Ratio |
|------|----------------------|-------|
| Hero Slider | 1920 x 1080 px | 16:9 |
| Section Solutions | 800 x 1000 px | 4:5 |
| Insights Cards | 600 x 600 px | 1:1 |

## 🔧 Dépannage

### L'image ne s'affiche pas après le téléchargement
1. Vérifiez la console du navigateur (F12)
2. Assurez-vous que le dossier `public/uploads/` existe
3. Vérifiez que l'image a bien été uploadée dans `public/uploads/`

### Le bouton "Save Changes" ne fait rien
1. Ouvrez la console du navigateur (F12)
2. Regardez les erreurs réseau
3. Vérifiez que l'API `/api/content` répond

### Le cadre rouge ne s'affiche pas
1. Vérifiez que vous êtes en mode admin
2. Actualisez la page (F5)
3. Videz le cache du navigateur (Ctrl+Shift+Del)

## 📱 Raccourcis clavier

- **F5** : Actualiser la page
- **F12** : Ouvrir les outils de développement
- **Ctrl+S** : Sauvegarder (déclenche Save Changes)

## 🎓 Pour aller plus loin

Consultez le guide complet : [CMS_GUIDE.md](./CMS_GUIDE.md)

---

**Besoin d'aide ?** Contactez l'équipe de développement.
