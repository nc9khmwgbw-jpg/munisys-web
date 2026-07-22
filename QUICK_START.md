# ⚡ Quick Start - Munisys CMS

**Temps estimé : 5 minutes**

---

## 1️⃣ Installation (1 min)

```bash
cd munisys-cms-full
npm install
```

---

## 2️⃣ Démarrage (30 sec)

```bash
npm run dev
```

Le site sera disponible sur **http://localhost:3000**

---

## 3️⃣ Activer le Mode Admin (30 sec)

Ouvrez votre navigateur et allez sur :

```
http://localhost:3000/admin
```

✅ Vous serez redirigé vers la page d'accueil  
✅ Une barre d'outils apparaît en bas à droite  
✅ Le mode édition est activé

---

## 4️⃣ Éditer une Image (2 min)

### Sur la page d'accueil

1. **Regardez** le carousel en haut
   - Vous verrez des images avec un **cadre rouge pointillé**

2. **Survolez** une image
   - Un overlay bordeaux apparaît
   - Le texte "📷 Cliquez pour changer" s'affiche

3. **Cliquez** sur l'image
   - Une fenêtre de sélection de fichier s'ouvre

4. **Sélectionnez** une nouvelle image depuis votre ordinateur
   - Formats acceptés : JPG, PNG, WebP, GIF
   - L'image sera téléchargée automatiquement

5. **Attendez** le téléchargement
   - Un indicateur "⏳ Téléchargement..." s'affiche

6. **Sauvegardez** vos modifications
   - Un bouton rouge **"💾 Save Changes"** apparaît en bas à droite
   - Cliquez dessus (ou appuyez sur **Ctrl+S**)

✅ **Félicitations !** Vous venez d'éditer votre première image !

---

## 5️⃣ Tester sur la Page de Démonstration (1 min)

```
http://localhost:3000/test-images
```

Cette page contient 8 images de test pour vous entraîner.

---

## 📍 Images Éditables sur la Page d'Accueil

| Image | Emplacement | Comment la trouver |
|-------|-------------|-------------------|
| 🖼️ Slide 2 | Hero Carousel | 2ème slide du carousel |
| 🖼️ Slide 3 | Hero Carousel | 3ème slide du carousel |
| 🖼️ Solutions | Section bleue foncée | Grande image au centre |
| 🖼️ Insights | Section "Insights" | Card "New headquarters" |

---

## ⌨️ Raccourcis Clavier

| Raccourci | Action |
|-----------|--------|
| **Ctrl+S** (ou **Cmd+S** sur Mac) | Sauvegarder rapidement |
| **F5** | Actualiser la page |
| **Double-clic** | Éditer du texte |

---

## 🎨 Bonus : Changer les Couleurs

En mode admin, dans la barre d'outils, vous verrez un panneau **"🎨 Theme Colors"**.

Cliquez sur les sélecteurs de couleur pour modifier :
- Bordeaux
- Blue Dark
- Blue Light
- Background Gray
- Text Gray

Les changements sont visibles immédiatement !

---

## 🔒 Sortir du Mode Admin

Cliquez sur le bouton **"🔒 Exit Admin Mode"** dans la barre d'outils.

---

## ⚠️ Important

- 💾 **Sauvegardez** après chaque modification (Ctrl+S)
- 📱 Testez sur différentes tailles d'écran
- 🚫 N'oubliez pas de sauvegarder avant de fermer le navigateur

---

## 🐛 Problème ?

### Les images n'ont pas de cadre rouge
→ Allez sur `/admin` pour activer le mode édition

### Le bouton "Save Changes" ne fait rien
→ Ouvrez la console (F12) et regardez les erreurs

### L'image ne se télécharge pas
→ Vérifiez que `public/uploads/` existe : `ls public/uploads/`

---

## 📚 Aller Plus Loin

- 📖 **Documentation complète** : [CMS_GUIDE.md](./CMS_GUIDE.md)
- 🗺️ **Carte des images** : [IMAGES_MAP.md](./IMAGES_MAP.md)
- 📋 **Index documentation** : [DOCUMENTATION_INDEX.md](./DOCUMENTATION_INDEX.md)

---

## ✅ Checklist Rapide

- [ ] `npm install` fait
- [ ] `npm run dev` lancé
- [ ] `/admin` visité
- [ ] Une image modifiée
- [ ] Modifications sauvegardées
- [ ] Page de test visitée (`/test-images`)

---

**Félicitations ! Vous maîtrisez maintenant les bases du CMS Munisys ! 🎉**

Pour des fonctionnalités avancées, consultez le **[CMS_GUIDE.md](./CMS_GUIDE.md)**
