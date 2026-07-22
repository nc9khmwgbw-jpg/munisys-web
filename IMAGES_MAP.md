# 🗺️ Carte des Images Éditables

Ce document liste toutes les images éditables via le CMS sur le site Munisys.

## 📍 Page d'accueil (`/`)

### Hero Section (Carousel)

#### Slide 1
- **Type** : SVG (Logo géométrique)
- **Éditable** : ❌ Non (SVG codé en dur)
- **Localisation dans le code** : `src/app/page.js` - Composant `HeroBigLogo`

#### Slide 2
- **Éditable** : ✅ Oui
- **dbPath** : `['homepage', 'images', 'slide3']`
- **Image par défaut** : `/slide3.png` (Code/Technologie)
- **Composant** : `<InlineImageEditor dbPath={['homepage', 'images', 'slide3']} />`
- **Localisation** : `src/app/page.js` - ligne ~48

#### Slide 3
- **Éditable** : ✅ Oui
- **dbPath** : `['homepage', 'images', 'slide1']`
- **Image par défaut** : `/slide1.png` (Datacenter)
- **Composant** : `<InlineImageEditor dbPath={['homepage', 'images', 'slide1']} />`
- **Localisation** : `src/app/page.js` - ligne ~57

---

### Section Solutions (Fond bleu foncé)

#### Image principale
- **Éditable** : ✅ Oui
- **dbPath** : `['homepage', 'images', 'solutionsDC']`
- **Image par défaut** : `/slide1.png` (Datacenter)
- **Composant** : `<InlineImageEditor dbPath={['homepage', 'images', 'solutionsDC']} />`
- **Localisation** : `src/app/page.js` - ligne ~98

---

### Section Insights

#### Card 1
- **Type** : Logo SVG + Texte
- **Éditable** : ❌ Non (SVG codé en dur)
- **Contenu** : Logo Munisys miniature

#### Card 2
- **Éditable** : ✅ Oui
- **dbPath** : `['homepage', 'images', 'insightsHQ']`
- **Image par défaut** : `/slide2.png` (Headquarters)
- **Composant** : `<InlineImageEditor dbPath={['homepage', 'images', 'insightsHQ']} />`
- **Localisation** : `src/app/page.js` - ligne ~183

#### Card 3
- **Type** : Texte stylisé (Helios + FIPAR)
- **Éditable** : ❌ Non (HTML/CSS)

---

## 📊 Résumé

| Zone | Nombre d'images | Éditables |
|------|-----------------|-----------|
| Hero Carousel | 3 | 2 |
| Section Solutions | 1 | 1 |
| Section Insights | 3 | 1 |
| **TOTAL** | **7** | **4** |

## 🔧 Structure dans db.json

```json
{
  "homepage": {
    "images": {
      "slide1": "/slide1.png",      // Slide 3 du Hero
      "slide3": "/slide3.png",      // Slide 2 du Hero
      "solutionsDC": "/slide1.png", // Section Solutions
      "insightsHQ": "/slide2.png"   // Section Insights Card 2
    }
  }
}
```

## 📝 Comment ajouter une nouvelle image éditable

### 1. Mettre à jour `db.json`

```json
{
  "homepage": {
    "images": {
      "monNouveauChamp": "/mon-image-par-defaut.png"
    }
  }
}
```

### 2. Utiliser le composant dans le code

```jsx
import InlineImageEditor from '@/components/InlineImageEditor';

// Dans votre composant
<InlineImageEditor 
  dbPath={['homepage', 'images', 'monNouveauChamp']} 
  fallback="/mon-image-par-defaut.png" 
  alt="Description de l'image" 
/>
```

### 3. Tester en mode admin

1. Allez sur `/admin`
2. Localisez votre nouvelle image
3. Cliquez dessus pour la modifier
4. Sauvegardez avec "Save Changes"

## 🎨 Styles CSS pour les images

Les images éditables héritent des styles de leur conteneur. Voici les styles appliqués :

### Hero Slides
```css
.hero-img-clip {
  clip-path: polygon(25% 0, 100% 0, 100% 100%, 0 100%);
}
.hero-img-clip img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
```

### Section Solutions
```css
.sol-img-container {
  width: 100%;
  aspect-ratio: 3/4;
  overflow: hidden;
}
.sol-img-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
```

### Section Insights
```css
.ins-img-wrap {
  aspect-ratio: 1;
  clip-path: polygon(15% 0, 100% 0, 100% 100%, 0 100%, 0 15%);
}
.ins-img-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
```

## 🔍 Débug

### Trouver le dbPath d'une image

1. Ouvrez les DevTools (F12)
2. Inspectez l'image en question
3. Cherchez l'attribut `data-dbpath` (si ajouté) ou regardez dans `db.json`

### Vérifier qu'une image est uploadée

```bash
ls -la public/uploads/
```

Les images uploadées ont un préfixe `cms_` suivi d'un timestamp.

### Voir les images dans la base de données

```bash
cat src/data/db.json | grep -A 10 "images"
```

---

**Mis à jour** : Juillet 2026  
**Version** : 1.0
