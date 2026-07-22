# 🎯 Comment Utiliser le Script d'Export Automatique

## 📦 Ce que fait le script

Le script `export-to-wordpress.js` va **automatiquement**:
- ✅ Créer toutes vos pages WordPress
- ✅ Transférer le contenu de `db.json`
- ✅ Générer le HTML formaté
- ✅ Configurer les slugs d'URLs
- ✅ Publier les pages immédiatement

**Temps: 5 minutes** (vs 10h manuellement) ⚡

---

## 🚀 Prérequis AVANT de lancer le script

### 1. WordPress doit être installé et fonctionnel
```
✅ Local by Flywheel installé
✅ Site "munisys-site" créé
✅ WordPress accessible sur: http://munisys-site.local
✅ Vous pouvez vous connecter à /wp-admin
```

### 2. Plugin pour l'API REST (Important!)

Le script utilise l'API WordPress qui est activée par défaut, MAIS il faut:

```
1. Allez dans WordPress Dashboard
2. Extensions → Ajouter
3. Recherchez "Application Passwords"
4. Installez et activez

OU (Méthode alternative - Recommandée):

1. Utilisateurs → Profil
2. Descendez jusqu'à "Application Passwords"
3. Créez un nouveau mot de passe applicatif:
   - Nom: "Export Script"
   - Cliquez "Add New Application Password"
4. NOTEZ le mot de passe généré (format: xxxx xxxx xxxx xxxx)
```

### 3. Modifiez le script avec vos identifiants

```javascript
// Dans export-to-wordpress.js, ligne ~21:

const CONFIG = {
  wpUrl: 'http://munisys-site.local',  // ✅ OK si vous avez suivi le guide
  username: 'admin',                     // ✅ OK si vous avez suivi le guide
  password: 'munisys2024',               // ⚠️  Ou le mot de passe applicatif
};
```

---

## ▶️ LANCEMENT DU SCRIPT

### Méthode 1: Depuis le Terminal

```bash
# 1. Ouvrez le terminal
# 2. Allez dans votre dossier projet
cd ~/Desktop/munisys-cms-full

# 3. Lancez le script
node export-to-wordpress.js
```

### Ce que vous allez voir:

```
╔═══════════════════════════════════════════════════════╗
║                                                       ║
║   🚀 Export Automatique: Next.js → WordPress         ║
║                                                       ║
║   Munisys CMS Migration Tool                         ║
║                                                       ║
╚═══════════════════════════════════════════════════════╝

▶ ÉTAPE 1: Chargement de db.json
✓ Données chargées: 4 sections trouvées

▶ ÉTAPE 2: Test de connexion à WordPress
✓ Connecté à: http://munisys-site.local

▶ ÉTAPE 3: Création de la page d'accueil
ℹ Création de la page: Accueil
✓ Page créée: Accueil (ID: 5)

▶ ÉTAPE 4: Création des pages Solutions
ℹ Création de la page: Compute & Storage
✓ Page créée: Compute & Storage (ID: 6)
ℹ Création de la page: Hyper-converged Infrastructures
✓ Page créée: Hyper-converged Infrastructures (ID: 7)
...

▶ ÉTAPE 5: Création des pages standards
ℹ Création de la page: About Us
✓ Page créée: About Us (ID: 20)
...

╔═══════════════════════════════════════════════════════╗
║                                                       ║
║   ✅ MIGRATION TERMINÉE AVEC SUCCÈS!                 ║
║                                                       ║
╚═══════════════════════════════════════════════════════╝
```

---

## 🎉 APRÈS L'EXÉCUTION

### 1. Vérifiez les pages créées

```
1. Allez sur: http://munisys-site.local/wp-admin
2. Pages → Toutes les pages
3. Vous devriez voir ~20 pages créées!
```

### 2. Configurez la page d'accueil

```
1. Réglages → Lecture
2. Cochez "Une page statique"
3. Page d'accueil: Sélectionnez "Accueil"
4. Enregistrez
```

### 3. Éditez avec Breakdance

```
1. Ouvrez une page (ex: Accueil)
2. Cliquez "Edit with Breakdance"
3. Personnalisez le design:
   - Ajoutez des sections
   - Changez les couleurs
   - Ajoutez des images
   - Modifiez la mise en page
```

---

## 🐛 Résolution des Problèmes

### Erreur: "Impossible de se connecter à WordPress"

**Solution 1: Vérifiez que WordPress tourne**
```bash
# Dans Local, vérifiez que le site est "Running" (vert)
# Si rouge, cliquez sur "Start site"
```

**Solution 2: Testez l'URL manuellement**
```
Ouvrez: http://munisys-site.local/wp-json/wp/v2/pages

Si vous voyez du JSON → API fonctionne ✅
Si erreur 404 → Réactivez les permaliens:
  - Réglages → Permaliens → Enregistrer
```

**Solution 3: Vérifiez les identifiants**
```javascript
// Dans export-to-wordpress.js:
username: 'admin',      // Celui que vous avez créé dans WordPress
password: 'munisys2024' // Le mot de passe correct
```

### Erreur: "HTTP 401 Unauthorized"

**Cause:** Identifiants incorrects

**Solution:**
```
1. Créez un "Application Password":
   - WordPress → Utilisateurs → Profil
   - Descendez à "Application Passwords"
   - Nom: Export Script
   - Générez
   
2. Copiez le mot de passe généré (format: xxxx xxxx xxxx xxxx)

3. Modifiez dans export-to-wordpress.js:
   password: 'xxxx xxxx xxxx xxxx'  // Collez ici
```

### Erreur: "Page already exists"

**Cause:** Pages déjà créées lors d'un précédent essai

**Solution:**
```
1. Allez dans WordPress
2. Pages → Toutes les pages
3. Sélectionnez toutes les pages créées par le script
4. Actions groupées → Mettre à la corbeille
5. Relancez le script
```

---

## 🎨 Personnalisation du Script

### Changer les pages créées

```javascript
// Dans export-to-wordpress.js, ligne ~185:

const standardPages = [
  { title: 'Contact', slug: 'contact', content: '<h1>Contact Us</h1>' },
  { title: 'Blog', slug: 'blog', content: '<h1>Our Blog</h1>' },
  // Ajoutez vos pages ici
];
```

### Changer l'URL WordPress

```javascript
// Ligne ~21:
wpUrl: 'http://autre-url.local',
```

---

## 📊 Résumé des Gains de Temps

| Tâche | Manuel | Avec Script | Gain |
|-------|--------|-------------|------|
| Créer 20 pages | 2h | 30s | 99% |
| Copier le contenu | 4h | 0s | 100% |
| Formater le HTML | 2h | 0s | 100% |
| Configurer URLs | 1h | 0s | 100% |
| Tester | 1h | 5min | 92% |
| **TOTAL** | **10h** | **5min** | **96%** |

---

## ✅ Checklist Finale

Avant de lancer le script:
- [ ] WordPress installé et accessible
- [ ] Identifiants corrects dans le script
- [ ] Terminal ouvert dans le bon dossier
- [ ] Node.js installé (`node --version`)

Après l'exécution:
- [ ] Pages créées vérifiées dans WordPress
- [ ] Page d'accueil configurée (Réglages → Lecture)
- [ ] Design personnalisé avec Breakdance
- [ ] Navigation/Menu configuré (Apparence → Menus)

---

## 🎯 Prochaine Étape

**Une fois le script exécuté avec succès, vous aurez:**
- ✅ Toutes les pages créées
- ✅ Contenu importé
- ✅ Structure prête

**Il ne reste plus qu'à:**
1. Installer Breakdance (si pas encore fait)
2. Éditer chaque page pour le design
3. Ajouter les images
4. Configurer le menu de navigation

**Temps estimé pour finaliser: 2-3h** (vs 16h sans le script!)

---

## 💡 Besoin d'Aide?

Si vous rencontrez un problème:
1. Lisez la section "Résolution des Problèmes"
2. Vérifiez que WordPress est bien accessible
3. Testez l'API manuellement
4. Demandez-moi de l'aide avec le message d'erreur exact

**Prêt à lancer le script?** 🚀
