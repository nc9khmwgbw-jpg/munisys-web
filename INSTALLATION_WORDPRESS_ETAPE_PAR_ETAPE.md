# 🚀 Installation WordPress - Étape par Étape (RECOMMANDÉ)

## 🎯 Objectif: WordPress fonctionnel en 30 minutes

---

## ✅ ÉTAPE 1: Télécharger et Installer Local by Flywheel

### 1.1 Téléchargement

```
1. Ouvrez votre navigateur
2. Allez sur: https://localwp.com/
3. Cliquez sur "DOWNLOAD FOR MAC"
4. Remplissez le formulaire (optionnel) ou cliquez "Skip"
5. Le téléchargement commence (environ 500 MB)
```

### 1.2 Installation

```
1. Ouvrez le fichier téléchargé: local-8.x.x-mac.dmg
2. Glissez l'icône Local dans le dossier Applications
3. Ouvrez Local depuis Applications
4. Si message "Non identifié":
   - Clic droit → Ouvrir
   - Confirmez "Ouvrir"
```

---

## ✅ ÉTAPE 2: Créer votre Site WordPress

### 2.1 Création du Site

```
1. Lancez Local
2. Cliquez sur le gros bouton vert "+" (Create a new site)
3. Configuration:

   📝 What's your site's name?
   → Tapez: munisys-site
   → Cliquez "Continue"

   ⚙️ Choose your environment
   → Sélectionnez "Preferred"
   → Cliquez "Continue"

   👤 Setup WordPress
   → WordPress Username: admin
   → WordPress Password: munisys2024
   → WordPress Email: votre_email@example.com
   → Cliquez "Add Site"

4. Attendez 2-3 minutes (Local installe WordPress)
```

### 2.2 Vérification

```
Une fois terminé, vous verrez:
✅ "munisys-site" dans la barre latérale gauche
✅ Boutons "Admin" et "Open site" en haut à droite
```

---

## ✅ ÉTAPE 3: Accéder à WordPress

### 3.1 Accès Frontend (Site Public)

```
1. Dans Local, cliquez sur "Open site"
   OU
2. Ouvrez votre navigateur: http://munisys-site.local

Vous verrez: Le thème WordPress par défaut (Twenty Twenty-Four)
```

### 3.2 Accès Backend (Dashboard Admin)

```
1. Dans Local, cliquez sur "Admin"
   OU
2. Allez sur: http://munisys-site.local/wp-admin

3. Connectez-vous:
   - Identifiant: admin
   - Mot de passe: munisys2024

4. Vous êtes dans le tableau de bord WordPress! 🎉
```

---

## ✅ ÉTAPE 4: Installation du Thème de Base

### 4.1 Hello Elementor (Thème léger pour Breakdance)

```
1. Dans WordPress Dashboard:
   Apparence → Thèmes → Ajouter

2. Recherchez: "Hello Elementor"

3. Cliquez "Installer" puis "Activer"

✅ Thème installé!
```

---

## ✅ ÉTAPE 5: Installation de Breakdance

### Option A: Version Gratuite (Pour tester)

```
1. Extensions → Ajouter
2. Recherchez: "Breakdance"
3. Installez et activez

⚠️ Limitations de la version gratuite:
- Pas de templates premium
- Fonctionnalités limitées
- Parfait pour tester!
```

### Option B: Version Pro (Si vous avez acheté)

```
1. Allez sur: https://breakdance.com
2. Connectez-vous à votre compte
3. Téléchargez le fichier .zip

4. Dans WordPress:
   Extensions → Ajouter → Téléverser une extension
   
5. Choisissez le fichier breakdance-pro.zip
6. Installez et activez

7. Activez la licence:
   Breakdance → License
   → Entrez votre clé
   → Activate
```

---

## ✅ ÉTAPE 6: Configuration Initiale

### 6.1 Permaliens (Important!)

```
Réglages → Permaliens
→ Sélectionnez "Titre de publication"
→ Enregistrer

(Cela crée des URLs propres: /about-us au lieu de /?p=123)
```

### 6.2 Supprimer le Contenu Exemple

```
1. Pages → Tout
   → Mettre à la corbeille "Sample Page"

2. Articles → Tout
   → Mettre à la corbeille "Bonjour tout le monde!"

3. Commentaires
   → Supprimer le commentaire exemple
```

---

## ✅ ÉTAPE 7: Test de Breakdance

### 7.1 Créer une Page Test

```
1. Pages → Ajouter
2. Titre: "Test Breakdance"
3. Cliquez sur "Edit with Breakdance" (bouton en haut)
4. L'éditeur visuel s'ouvre! 🎨
```

### 7.2 Tester l'Éditeur

```
1. Barre de gauche → Elements
2. Glissez un "Heading" sur la page
3. Tapez: "Mon premier titre"
4. Changez la couleur, taille, etc.
5. Cliquez "Save" en haut à droite
6. Cliquez "Preview" pour voir le résultat
```

✅ Si ça marche → WordPress + Breakdance sont prêts!

---

## 🎉 FÉLICITATIONS!

Vous avez maintenant:
✅ WordPress fonctionnel en local
✅ Thème Hello Elementor installé
✅ Breakdance activé
✅ Prêt pour l'import automatique!

---

## 📋 PROCHAINE ÉTAPE: Script d'Import Automatique

**Une fois que vous avez terminé les étapes ci-dessus, dites-moi:**

✅ "C'est bon, WordPress est installé et Breakdance fonctionne"

**Je lancerai alors le script d'import automatique qui va:**
1. Créer toutes vos pages (Accueil, Solutions, Services, etc.)
2. Importer tout le contenu de votre db.json
3. Uploader toutes les images
4. Configurer la navigation
5. Tout ça en 5 minutes! ⚡

---

## ❓ Problèmes Fréquents

### "Local ne démarre pas"
```bash
# Dans le terminal:
sudo killall -9 com.getflywheel.lightning.local
# Puis relancez Local
```

### "munisys-site.local ne charge pas"
```
1. Dans Local → munisys-site
2. Cliquez sur "Stop site"
3. Attendez 5 secondes
4. Cliquez sur "Start site"
```

### "Breakdance n'apparaît pas"
```
1. Vérifiez que le thème Hello Elementor est activé
2. Désactivez puis réactivez Breakdance:
   Extensions → Extensions installées
   → Breakdance → Désactiver → Activer
```

---

## 📞 Prêt pour la Suite?

**Commencez par l'ÉTAPE 1** et dites-moi quand vous êtes prêt pour le script d'import!

Je suis là pour vous aider à chaque étape. 💪
