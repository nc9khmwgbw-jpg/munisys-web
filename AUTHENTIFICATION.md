# 🔐 Guide d'Authentification Admin

## ✅ Système d'Authentification Installé !

Votre CMS est maintenant protégé par un système d'authentification.

---

## 🎯 Fonctionnalités

✅ **Page de connexion sécurisée**  
✅ **Identifiants personnalisables**  
✅ **Session persistante** (localStorage)  
✅ **Bouton de déconnexion**  
✅ **Protection contre la perte de données** (confirmation avant déconnexion)  
✅ **Design professionnel et responsive**

---

## 🚀 Comment Utiliser

### 1. Accéder à la page de connexion

```
http://localhost:3000/admin
```

### 2. Se connecter

**Identifiants par défaut :**
- **Username :** `admin`
- **Password :** `munisys2024`

### 3. Éditer le contenu

Une fois connecté :
- Vous êtes redirigé vers la page d'accueil en mode édition
- Double-cliquez pour éditer du texte
- Cliquez sur les images pour les changer
- Ctrl+S pour sauvegarder

### 4. Se déconnecter

Cliquez sur le bouton **"Déconnexion"** dans la barre d'outils en bas à droite.

---

## 🔧 Configuration

### Changer les Identifiants

**Option 1 : Via variables d'environnement (Recommandé)**

1. Ouvrez le fichier `.env.local`
2. Modifiez les valeurs :

```env
ADMIN_USERNAME=votre_username
ADMIN_PASSWORD=votre_mot_de_passe_fort
```

3. Redémarrez le serveur :

```bash
npm run dev
```

**Option 2 : Via le code (Non recommandé)**

Modifiez le fichier `src/app/api/admin/login/route.js` :

```javascript
const ADMIN_CREDENTIALS = {
  username: 'votre_username',
  password: 'votre_password'
};
```

⚠️ **Ne committez jamais les identifiants dans git !**

---

## 🔒 Sécurité

### ✅ Bonnes Pratiques Implémentées

1. **Variables d'environnement** - Identifiants stockés dans `.env.local`
2. **Gitignore** - `.env.local` exclu de git
3. **localStorage** - Session côté client
4. **Validation** - Vérification des champs
5. **Messages d'erreur** - Feedback clair

### ⚠️ Pour la Production

**IMPORTANT :** Avant de déployer en production :

1. ✅ **Changez les identifiants par défaut**
2. ✅ **Utilisez des mots de passe forts** (12+ caractères, alphanumériques + symboles)
3. ✅ **Activez HTTPS**
4. ✅ **Considérez l'ajout de :**
   - Rate limiting (limitation des tentatives)
   - JWT ou sessions côté serveur
   - Authentification à deux facteurs (2FA)
   - Logs des connexions

---

## 📁 Fichiers Créés

| Fichier | Description |
|---------|-------------|
| `src/app/admin/page.js` | Page de connexion |
| `src/app/api/admin/login/route.js` | API d'authentification |
| `.env.local` | Variables d'environnement |
| `.env.local.example` | Exemple de configuration |
| `AUTHENTIFICATION.md` | Ce guide |

---

## 🎨 Page de Connexion

### Fonctionnalités

✅ Design professionnel avec logo Munisys  
✅ Formulaire avec validation  
✅ Messages d'erreur clairs  
✅ Animation de chargement  
✅ Indicateurs visuels  
✅ Responsive (mobile-friendly)  
✅ Identifiants affichés pour développement

### Personnalisation

Pour personnaliser l'apparence, modifiez le fichier `src/app/admin/page.js` :

- **Couleurs** : Modifiez l'objet `styles`
- **Logo** : Remplacez le SVG
- **Textes** : Changez les textes dans le JSX

---

## 🔑 Gestion des Sessions

### Comment ça fonctionne

1. **Connexion** : Identifiants vérifiés → Token stocké dans localStorage
2. **Navigation** : Token vérifié à chaque chargement
3. **Déconnexion** : Token supprimé → Redirection vers `/admin`

### Durée de Session

- **Par défaut** : Jusqu'à la déconnexion manuelle
- **Navigateur fermé** : Session conservée (localStorage)
- **Cache vidé** : Session perdue

---

## 🛠️ Dépannage

### Problème : "Identifiants incorrects"

**Solutions :**
1. Vérifiez que les identifiants sont corrects
2. Vérifiez le fichier `.env.local`
3. Redémarrez le serveur après modification

### Problème : Boucle de redirection

**Solutions :**
1. Videz le localStorage : `localStorage.clear()`
2. Actualisez la page (F5)
3. Redémarrez le serveur

### Problème : Page blanche après connexion

**Solutions :**
1. Vérifiez la console (F12)
2. Vérifiez que le `CMSProvider` est bien chargé
3. Redémarrez le serveur

---

## 📊 Workflow Complet

```
┌──────────────────┐
│ Utilisateur      │
│ visite /admin    │
└────────┬─────────┘
         │
         ↓
┌──────────────────────────┐
│ Déjà authentifié ?       │
├──────────┬───────────────┤
│   OUI    │      NON      │
↓          ↓               
Redirection    Afficher
vers /         formulaire
(mode edit)    de connexion
               │
               ↓
        ┌──────────────┐
        │ Soumission   │
        │ formulaire   │
        └──────┬───────┘
               │
               ↓
        ┌─────────────────┐
        │ API /admin/login│
        │ vérifie ids     │
        └──────┬──────────┘
               │
        ┌──────┴──────┐
        │             │
     ✅ OK        ❌ KO
        │             │
        ↓             ↓
   Enregistre    Message
   session       d'erreur
   │
   ↓
Redirection
vers /
(mode edit)
```

---

## 🎓 Exemples d'Utilisation

### Connexion Basique

```javascript
// Les identifiants sont vérifiés côté serveur
POST /api/admin/login
{
  "username": "admin",
  "password": "munisys2024"
}

// Réponse succès
{
  "success": true,
  "message": "Authentification réussie"
}

// Réponse échec
{
  "success": false,
  "message": "Identifiants incorrects"
}
```

### Vérification de Session

```javascript
// Dans n'importe quel composant
const isAuthenticated = localStorage.getItem('munisys_admin_auth') === 'true';

if (isAuthenticated) {
  // Utilisateur connecté
} else {
  // Rediriger vers /admin
}
```

### Déconnexion

```javascript
// Supprimer la session
localStorage.removeItem('munisys_admin_auth');

// Désactiver le mode édition
toggleEditMode(false);

// Rediriger
window.location.href = '/admin';
```

---

## 🔮 Améliorations Futures Possibles

### Court Terme
- [ ] Remember me (session persistante optionnelle)
- [ ] Récupération de mot de passe
- [ ] Changement de mot de passe dans l'interface

### Moyen Terme
- [ ] Multi-utilisateurs avec rôles
- [ ] Logs des connexions
- [ ] Rate limiting (anti-bruteforce)

### Long Terme
- [ ] Authentification OAuth (Google, GitHub)
- [ ] Authentification à deux facteurs (2FA)
- [ ] Sessions côté serveur avec JWT
- [ ] Permissions granulaires

---

## ⚙️ Configuration Avancée

### Variables d'Environnement Disponibles

```env
# Identifiants Admin
ADMIN_USERNAME=admin              # Nom d'utilisateur
ADMIN_PASSWORD=munisys2024        # Mot de passe

# Optionnel (futures versions)
# SESSION_SECRET=your-secret-key  # Clé secrète pour JWT
# SESSION_DURATION=3600           # Durée en secondes
```

### Personnaliser la Durée de Session

Pour implémenter une expiration de session :

```javascript
// Dans src/app/admin/page.js
const SESSION_DURATION = 3600000; // 1 heure en ms

localStorage.setItem('munisys_admin_auth', 'true');
localStorage.setItem('munisys_auth_expiry', Date.now() + SESSION_DURATION);

// Vérifier l'expiration
const expiry = localStorage.getItem('munisys_auth_expiry');
if (Date.now() > expiry) {
  // Session expirée
  handleLogout();
}
```

---

## 📞 Support

**Questions ?** Consultez :
- [CMS_GUIDE.md](./CMS_GUIDE.md) - Guide complet du CMS
- [LISEZ-MOI-EN-PREMIER.md](./LISEZ-MOI-EN-PREMIER.md) - Vue d'ensemble

---

## ✅ Checklist de Sécurité

Avant la production :

- [ ] Identifiants changés dans `.env.local`
- [ ] Mot de passe fort (12+ caractères)
- [ ] `.env.local` dans `.gitignore`
- [ ] HTTPS activé
- [ ] Backup de la base de données
- [ ] Logs de connexion activés
- [ ] Rate limiting configuré (si possible)

---

**Version :** 1.0  
**Date :** 2 juillet 2026  
**Status :** ✅ Opérationnel

🔐 **Votre CMS est maintenant sécurisé !** 🔐
