# 📘 Guide Complet: Migration vers WordPress + Breakdance

## 🎯 Vue d'ensemble

Ce guide vous accompagne pour transformer votre site Munisys Next.js en site WordPress avec Breakdance.

---

## ⚙️ OPTION 1: Installation avec Local by Flywheel (RECOMMANDÉ)

### Étape 1: Installation de Local

1. **Télécharger Local:**
   - URL: https://localwp.com/
   - Téléchargez la version macOS
   - Installez l'application (glissez dans Applications)

2. **Créer votre site WordPress:**
   ```
   - Ouvrez Local
   - Cliquez sur le bouton vert "+" (Create a new site)
   - Nom du site: munisys-site
   - Cliquez "Continue"
   - Environnement: Sélectionnez "Preferred"
   - Cliquez "Continue"
   - Configurez l'admin:
     * WordPress Username: admin
     * WordPress Password: munisys2024
     * WordPress Email: votre_email@example.com
   - Cliquez "Add Site"
   ```

3. **Accéder à votre site:**
   - URL publique: `http://munisys-site.local`
   - Admin WordPress: `http://munisys-site.local/wp-admin`
   - Identifiants: admin / munisys2024

---

## ⚙️ OPTION 2: Installation Manuelle (MAMP/Terminal)

### Prérequis:
- PHP 8.0+
- MySQL 5.7+
- Apache/Nginx

### Étape 1: Télécharger WordPress

```bash
# Dans le terminal
cd ~/Desktop
curl -O https://wordpress.org/latest.zip
unzip latest.zip
mv wordpress munisys-wordpress
cd munisys-wordpress
```

### Étape 2: Installer MAMP (si pas déjà fait)

1. Téléchargez MAMP: https://www.mamp.info/en/downloads/
2. Installez et lancez MAMP
3. Cliquez sur "Start Servers"
4. Allez dans Preferences → Web Server
5. Changez Document Root vers: `/Users/fahamayoub/Desktop/munisys-wordpress`

### Étape 3: Créer la base de données

1. Ouvrez phpMyAdmin: http://localhost:8888/phpMyAdmin/
2. Identifiants par défaut: root / root
3. Créez nouvelle base de données: `munisys_db`

### Étape 4: Configuration WordPress

1. Allez sur: http://localhost:8888
2. Sélectionnez la langue: Français
3. Configuration de la base:
   - Nom de la base: munisys_db
   - Utilisateur: root
   - Mot de passe: root
   - Hôte: localhost
   - Préfixe: wp_
4. Informations du site:
   - Titre: Munisys
   - Identifiant: admin
   - Mot de passe: (généré automatiquement - notez-le!)
   - Email: votre_email@example.com

---

## 🎨 ÉTAPE 2: Installation de Breakdance

### Option A: Version Gratuite (Limitée)

1. **Dans le dashboard WordPress:**
   ```
   - Allez dans Extensions → Ajouter
   - Recherchez "Breakdance"
   - Cliquez "Installer" puis "Activer"
   ```

### Option B: Version Pro (Recommandée - $199/an)

1. **Acheter Breakdance:**
   - URL: https://breakdance.com/pricing/
   - Choisissez un plan (Agency recommandé pour sites illimités)

2. **Télécharger et installer:**
   ```
   - Connectez-vous à votre compte Breakdance
   - Téléchargez le fichier .zip
   - Dans WordPress: Extensions → Ajouter → Téléverser
   - Sélectionnez le fichier breakdance.zip
   - Cliquez "Installer" puis "Activer"
   ```

3. **Activer la licence:**
   ```
   - Allez dans Breakdance → License
   - Entrez votre clé de licence
   - Cliquez "Activate"
   ```

---

## 🎯 ÉTAPE 3: Installation d'un Thème Compatible

### Recommandation: Hello Elementor ou GeneratePress

```
WordPress Dashboard:
- Apparence → Thèmes → Ajouter
- Recherchez "Hello Elementor" OU "GeneratePress"
- Installez et activez
```

**Note:** Ces thèmes sont légers et conçus pour les page builders.

---

## 📄 ÉTAPE 4: Création des Pages avec Breakdance

### 4.1 Créer la page d'accueil

```
1. Pages → Ajouter
2. Titre: "Accueil"
3. Cliquez sur "Edit with Breakdance"
4. Vous entrez dans l'éditeur visuel
```

### 4.2 Structure de la page d'accueil Munisys

**Section 1: Hero avec Slider**
```
- Ajoutez un "Slider" depuis la barre de gauche
- 3 slides avec:
  * Slide 1: "We orchestrate digital infrastructure..."
  * Slide 2: "We design architectures & integrations..."
  * Slide 3: "We take care of your digital infrastructure..."
- Ajoutez les images: slide1.png, slide2.png, slide3.png
- Bouton CTA: "Our solutions"
```

**Section 2: Services**
```
- Ajoutez un "Section" (Container)
- Titre: "Our services"
- Sous-titre: "A comprehensive catalog..."
- Ajoutez 3 colonnes avec "Icon Box":
  * IT integration services
  * IT support services
  * Managed services
```

**Section 3: Why Choose Munisys**
```
- Section avec fond gris
- Titre: "Why choose Munisys"
- Listes avec icônes (Through / We achieve)
```

**Section 4: Solutions**
```
- Grid de solutions
- Carte avec image + description
```

**Section 5: Clients**
```
- Titre: "They trust us"
- Logo grid des clients
- Bouton "See all"
```

### 4.3 Créer les autres pages

Répétez le processus pour:
- À propos (About Us)
- Solutions
- Services
- Références clients
- Partenaires
- Carrières
- Insights

---

## 🧭 ÉTAPE 5: Configuration de la Navigation

### 5.1 Créer le menu

```
1. Apparence → Menus
2. Créer un nouveau menu: "Menu Principal"
3. Ajoutez les pages:
   - Solutions (avec sous-menu)
   - Services
   - À propos
   - Références
   - Partenaires
   - Carrières
   - Insights
4. Cochez "Menu principal" dans Réglages du menu
5. Cliquez "Enregistrer"
```

### 5.2 Personnaliser avec Breakdance

```
1. Breakdance → Theme Builder → Header
2. Créez un nouveau header
3. Ajoutez:
   - Logo Munisys
   - Menu de navigation
   - Bouton Contact
   - Icône recherche
```

---

## 🦶 ÉTAPE 6: Création du Footer

```
1. Breakdance → Theme Builder → Footer
2. Créez un nouveau footer
3. Structure (4 colonnes):
   - Colonne 1: À propos + LinkedIn
   - Colonne 2: Solutions
   - Colonne 3: Services
   - Colonne 4: Company
4. Barre du bas: Copyright + liens légaux
```

---

## 🎨 ÉTAPE 7: Personnalisation du Design

### 7.1 Couleurs du thème Munisys

```
Breakdance → Global Settings → Colors:
- Bordeaux: #922b55
- Blue Dark: #1d4e79
- Blue Light: #83a5c2
- Background Gray: #f8fafc
- Text Gray: #475569
```

### 7.2 Typographie

```
Breakdance → Global Settings → Fonts:
- Police principale: Montserrat
- Poids: 300, 400, 600, 700, 800
```

---

## 📦 ÉTAPE 8: Migration du Contenu

### Méthode manuelle (copier-coller):

1. **Textes:**
   - Ouvrez votre db.json actuel
   - Copiez chaque section dans les pages Breakdance correspondantes

2. **Images:**
   - Allez dans Médias → Ajouter
   - Uploadez toutes les images de `/public/`
   - Remplacez dans les pages Breakdance

### Méthode automatisée (avec script):

Je peux créer un script qui:
- Lit votre db.json
- Génère des pages WordPress via l'API REST
- Importe automatiquement les images

---

## 🔌 ÉTAPE 9: Plugins Essentiels

### Installation recommandée:

```
1. Yoast SEO (gratuit)
   - Extensions → Ajouter → "Yoast SEO"
   - Pour l'optimisation SEO

2. Contact Form 7 (gratuit)
   - Pour les formulaires de contact

3. WP Rocket (payant - $59/an) OU W3 Total Cache (gratuit)
   - Pour la performance/cache

4. Wordfence Security (gratuit)
   - Pour la sécurité

5. UpdraftPlus (gratuit)
   - Pour les sauvegardes automatiques
```

---

## 🚀 ÉTAPE 10: Mise en Production

### Avec Local by Flywheel:

```
1. Installer WP Migrate DB Pro
2. Exporter la base de données
3. Choisir un hébergeur:
   - Hostinger (pas cher, performant)
   - SiteGround (recommandé WordPress)
   - Kinsta (premium, très rapide)
```

### Hébergement recommandé pour Munisys:

**Option 1: Hostinger ($2.99/mois)**
- https://www.hostinger.com
- Plan Business WordPress
- SSL gratuit, bon support

**Option 2: SiteGround ($3.99/mois)**
- https://www.siteground.com
- Optimisé WordPress
- Excellent support

**Option 3: o2switch (€5/mois - Français)**
- https://www.o2switch.fr
- Hébergement illimité
- Support français

---

## 💰 Récapitulatif des Coûts

### Setup Initial:
- **WordPress:** Gratuit ✅
- **Breakdance Pro:** $199/an (ou gratuit version limitée)
- **Thème:** Gratuit (Hello Elementor) ✅
- **Hébergement:** $36-48/an (~$3-4/mois)

### Total Année 1: ~$235-247 (avec Breakdance Pro)
### Total Année 1: ~$36-48 (version gratuite Breakdance)

### Coûts récurrents annuels:
- Hébergement: $36-48/an
- Breakdance (si Pro): $199/an
- Nom de domaine: ~$12/an

---

## 🆚 Comparaison Temps de Développement

### Votre solution actuelle (Next.js):
- ✅ Déjà fait et fonctionnel
- ⏱️ Temps investi: ~5-8h
- 🎯 Maintenance: Minimale

### Migration WordPress + Breakdance:
- ⏱️ Setup WordPress: 1h
- ⏱️ Installation Breakdance: 30min
- ⏱️ Recréation des pages: 8-12h
- ⏱️ Configuration thème: 2-3h
- **Total: 12-16h de travail**

---

## ❓ Questions Fréquentes

### Q: Puis-je garder les deux solutions?
**R:** Oui! Gardez votre Next.js actuel, testez WordPress en parallèle.

### Q: Breakdance gratuit suffit-il?
**R:** Pour un site vitrine simple, oui. Mais limitations sur templates et fonctionnalités.

### Q: Puis-je importer automatiquement depuis Next.js?
**R:** Oui, je peux créer un script qui convertit votre db.json en contenu WordPress.

### Q: L'hébergement Next.js vs WordPress?
**R:**
- Next.js: Vercel gratuit, performances excellentes
- WordPress: Hébergement payant, performances moyennes

---

## 🎯 Ma Recommandation Finale

### Si vous devez vraiment passer à WordPress:

1. **Testez d'abord en local** (Local by Flywheel)
2. **Recréez quelques pages** pour voir le temps nécessaire
3. **Comparez** l'expérience d'édition vs votre CMS actuel
4. **Décidez** ensuite si ça vaut le coup

### Scénario idéal:

**Montrez votre solution Next.js actuelle à votre encadrant AVANT de migrer.**

Si elle répond aux besoins → Vous économisez 16h de travail + coûts récurrents!

---

## 🛠️ Aide Supplémentaire

Voulez-vous que je:
1. ✅ Crée un script pour exporter votre contenu vers WordPress?
2. ✅ Vous aide à installer Local et configurer WordPress?
3. ✅ Crée un template Breakdance basé sur votre design actuel?
4. ✅ Compare en détail les fonctionnalités des deux solutions?

---

## 📞 Prochaines Étapes

Dites-moi ce que vous voulez faire:
- 🔵 "Je commence avec Local by Flywheel" → Je vous guide pas à pas
- 🟢 "Crée-moi le script d'export" → J'automatise la migration
- 🟡 "Je reste sur Next.js finalement" → On améliore votre CMS actuel
- 🔴 "J'hésite encore" → On discute plus en détail

**Quelle est votre décision?** 🤔
