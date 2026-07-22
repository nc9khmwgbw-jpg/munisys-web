# 🎨 Installation du Design Munisys dans WordPress

## 📋 Vue d'Ensemble

Ce guide vous permet d'avoir **EXACTEMENT le même design** que votre site Next.js dans WordPress.

---

## ✅ CE QUE VOUS ALLEZ OBTENIR:

- ✅ **Design identique** à votre site Next.js
- ✅ **Couleurs Munisys** (Bordeaux, Blue Dark, Blue Light)
- ✅ **Typographie Montserrat**
- ✅ **Toutes les sections** (Hero, Solutions, Services, etc.)
- ✅ **Navigation et Footer** identiques
- ✅ **Responsive** (mobile, tablet, desktop)

---

## 🚀 MÉTHODE RAPIDE (15 minutes)

### **Étape 1: Installer le CSS Custom** (5 min)

#### **Option A: Via le Personnaliseur WordPress (Recommandé)**

1. Dans WordPress, allez dans **Apparence → Personnaliser**

2. Descendez et cliquez sur **"CSS additionnel"** (ou "Additional CSS")

3. **Ouvrez le fichier** `munisys-wordpress-styles.css` que je viens de créer

4. **Copiez TOUT le contenu** du fichier

5. **Collez-le** dans la zone "CSS additionnel"

6. Cliquez sur **"Publier"** en haut

✅ **Votre design est maintenant appliqué!**

#### **Option B: Via un Plugin (Si vous préférez)**

1. Installez le plugin **"Simple Custom CSS and JS"**
   - Extensions → Ajouter
   - Recherchez "Simple Custom CSS"
   - Installez et activez

2. Allez dans **Custom CSS & JS** → **Add Custom CSS**

3. Collez le contenu de `munisys-wordpress-styles.css`

4. Publiez

---

### **Étape 2: Créer les Pages avec les Classes CSS** (10 min)

Maintenant que le design est installé, vous devez créer les pages en utilisant les **classes CSS** définies.

#### **Exemple: Page d'Accueil (Homepage)**

1. **Créez une nouvelle page:** Pages → Add New → Titre: "Accueil"

2. **Cliquez sur "Edit with Breakdance"**

3. **Ajoutez les sections** en utilisant les classes CSS:

---

## 📝 STRUCTURE DE LA PAGE D'ACCUEIL

### **Section 1: Hero**

```html
<!-- Dans Breakdance, ajoutez un bloc "HTML" -->
<div class="munisys-hero">
  <div class="munisys-hero-content">
    <h1 class="munisys-hero-h1">
      We orchestrate <strong>digital infrastructure</strong> technologies 
      for a unique <strong>customer experience</strong>
    </h1>
    <a href="/solutions" class="munisys-hero-cta-btn">Our solutions</a>
  </div>
  <div class="munisys-hero-image">
    <img src="/wp-content/uploads/slide1.png" alt="Hero" />
  </div>
</div>
```

---

### **Section 2: Solutions (Fond bleu foncé)**

```html
<div class="munisys-solutions-section">
  <div class="munisys-solutions-grid">
    <div>
      <h2 class="munisys-solutions-title">Solutions</h2>
    </div>
    
    <div class="munisys-solutions-image">
      <img src="/wp-content/uploads/datacenter.png" alt="Solutions" />
    </div>
    
    <div class="munisys-solutions-content">
      <h3>Core Infrastructure Technologies</h3>
      <p>
        Munisys leverages its unique and extensive experience to help IT organizations 
        to setup, manage, secure and seamlessly run consistent compute, storage, networking 
        and backup infrastructure across data centers and public clouds...
      </p>
      <a href="/solutions" class="munisys-button-secondary">Read more</a>
    </div>
  </div>
</div>
```

---

### **Section 3: Offerings**

```html
<div class="munisys-offerings-section">
  <div class="munisys-offerings-header">
    <h2 class="munisys-offerings-title">Offerings</h2>
    <p class="munisys-offerings-desc">
      An end-to-end & coherent offer of solutions & services for secure digital infrastructures
    </p>
  </div>
  
  <div class="munisys-offerings-flow">
    <div class="munisys-offerings-label-vert munisys-offerings-label-through">THROUGH</div>
    
    <div class="munisys-offerings-col">
      <div class="munisys-offerings-item">✓ Best of breed technologies</div>
      <div class="munisys-offerings-item">✓ Skillful integrations</div>
      <div class="munisys-offerings-item">✓ Sound architectures</div>
      <div class="munisys-offerings-item">✓ Automation and orchestration</div>
      <div class="munisys-offerings-item">✓ Qualified end-to-end services</div>
    </div>
    
    <div class="munisys-offerings-label-vert munisys-offerings-label-achieve">WE ACHIEVE</div>
    
    <div class="munisys-offerings-col">
      <div class="munisys-offerings-item">✓ Efficient use of converging technologies</div>
      <div class="munisys-offerings-item">✓ Control over complexity</div>
      <div class="munisys-offerings-item">✓ Simplified IT Operations</div>
      <div class="munisys-offerings-item">✓ Agile infrastructure resources</div>
      <div class="munisys-offerings-item">✓ Optimal performance and true resilience</div>
      <div class="munisys-offerings-item">✓ Deep and broad support capabilities</div>
    </div>
  </div>
</div>
```

---

### **Section 4: Services (Fond bleu clair)**

```html
<div class="munisys-services-section">
  <div class="munisys-services-left">
    <h2>Our services</h2>
    <p>A comprehensive catalog of IT Services to cover the entire lifecycle of your IT infrastructure</p>
  </div>
  
  <div class="munisys-services-right">
    <div class="munisys-service-item">
      <h3 class="munisys-service-title">IT integration services</h3>
      <p class="munisys-service-desc">
        At every stage in the lifecycle of your IT Infrastructures, our professional 
        and operational services will help you make the right technology choices...
      </p>
    </div>
    
    <div class="munisys-service-item">
      <h3 class="munisys-service-title">IT support services</h3>
      <p class="munisys-service-desc">
        Our reactive and proactive support services ensure your infrastructure remains healthy...
      </p>
    </div>
    
    <div class="munisys-service-item">
      <h3 class="munisys-service-title">Managed services</h3>
      <p class="munisys-service-desc">
        Offload your daily IT operations to our certified experts...
      </p>
    </div>
  </div>
</div>
```

---

### **Section 5: Customer References**

```html
<div class="munisys-references-section">
  <div class="munisys-references-top">
    <h2 class="munisys-references-title">They trust us</h2>
    <p class="munisys-references-desc">
      As a trusted advisor, we make sure that our contribution to the design, deployment 
      and support of technological solutions...
    </p>
  </div>
  
  <div class="munisys-references-grid">
    <div class="munisys-reference-logo">Attijariwafa bank</div>
    <div class="munisys-reference-logo">Autoroutes du Maroc</div>
    <div class="munisys-reference-logo">Bank Al-Maghrib</div>
    <div class="munisys-reference-logo">BANK OF AFRICA</div>
    <div class="munisys-reference-logo">BANQUE POPULAIRE</div>
    <div class="munisys-reference-logo">CDG</div>
    <div class="munisys-reference-logo">EURAFRIC</div>
    <div class="munisys-reference-logo">Maroc Telecom</div>
    <div class="munisys-reference-logo">inwi</div>
    <div class="munisys-reference-logo">LafargeHolcim</div>
  </div>
</div>
```

---

## 🎨 MÉTHODE ALTERNATIVE: Avec Breakdance Visual Editor

Si vous préférez utiliser l'éditeur visuel de Breakdance au lieu du HTML:

### **Pour chaque section:**

1. **Ajoutez un Container** (Section)

2. **Dans les paramètres du Container:**
   - Onglet "Advanced"
   - Section "CSS Classes"
   - Ajoutez la classe CSS (ex: `munisys-hero`, `munisys-solutions-section`, etc.)

3. **Ajoutez les éléments** à l'intérieur:
   - Headings (Titres)
   - Text Editor (Paragraphes)
   - Buttons (Boutons)
   - Images

4. **Appliquez les classes CSS** à chaque élément:
   - Ex: Pour un titre → Classe: `munisys-hero-h1`
   - Ex: Pour un bouton → Classe: `munisys-hero-cta-btn`

---

## 📐 PAGES INTÉRIEURES (Solutions, About, etc.)

Pour les pages intérieures, utilisez cette structure:

```html
<!-- Hero interne -->
<div class="munisys-inner-hero">
  <div class="munisys-inner-hero-content">
    <h1 class="munisys-inner-hero-title">Compute & Storage</h1>
    <p class="munisys-inner-hero-desc">
      We use proven methodologies to design and build modern compute and storage infrastructures...
    </p>
  </div>
</div>

<!-- Contenu -->
<div class="munisys-content-section">
  <h2>Overview</h2>
  <p>
    We use proven methodologies to design and build modern compute and storage infrastructures, 
    designed to work in edge, data center or hybrid cloud environments...
  </p>
  
  <h3>Expertises</h3>
  <ul>
    <li>Standalone, Rack, and bladed Compute Servers</li>
    <li>High Performance Computing</li>
    <li>Converged IT/OT Systems</li>
    <li>Entry, Midrange and Mission Critical All-flash storage arrays</li>
    <li>File, block, object storage solutions</li>
  </ul>
  
  <h3>Partners</h3>
  <p>DELL, HPE, IBM</p>
</div>
```

---

## 🧭 NAVIGATION ET FOOTER

### **Navigation:**

Créez un menu dans **Apparence → Menus**, puis:

1. Créez un template **Header** dans Breakdance

2. Utilisez ce code HTML:

```html
<nav class="munisys-nav">
  <div class="munisys-nav-inner">
    <a href="/" class="munisys-nav-logo">
      <span class="munisys-nav-logo-mark">MUNISYS</span>
    </a>
    
    <div class="munisys-nav-links">
      <?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
    </div>
    
    <div class="munisys-nav-right">
      <a href="/contact" class="munisys-nav-cta">Contact</a>
    </div>
  </div>
</nav>
```

### **Footer:**

Créez un template **Footer** dans Breakdance:

```html
<footer class="munisys-footer">
  <div class="munisys-footer-top">
    <span class="munisys-footer-brand">MUNISYS</span>
    <a href="#" class="munisys-footer-linkedin">Follow us on LinkedIn</a>
  </div>
  
  <div class="munisys-footer-main">
    <div class="munisys-footer-col">
      <h4>Solutions</h4>
      <ul>
        <li><a href="#">Compute & Storage</a></li>
        <li><a href="#">Networking</a></li>
        <li><a href="#">Security</a></li>
      </ul>
    </div>
    
    <div class="munisys-footer-col">
      <h4>Services</h4>
      <ul>
        <li><a href="#">Integration</a></li>
        <li><a href="#">Support</a></li>
        <li><a href="#">Managed Services</a></li>
      </ul>
    </div>
    
    <div class="munisys-footer-col">
      <h4>Company</h4>
      <ul>
        <li><a href="#">About</a></li>
        <li><a href="#">Careers</a></li>
        <li><a href="#">Partners</a></li>
      </ul>
    </div>
    
    <div class="munisys-footer-col">
      <h4>Resources</h4>
      <ul>
        <li><a href="#">Insights</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </div>
  </div>
  
  <div class="munisys-footer-bottom">
    <span>© 2026 Munisys. All rights reserved.</span>
    <span>Privacy Policy · Legal Notice</span>
  </div>
</footer>
```

---

## ⏱️ TEMPS ESTIMÉ

| Tâche | Temps |
|-------|-------|
| Installation CSS | 5 min |
| Page d'accueil | 30 min |
| Navigation + Footer | 20 min |
| Pages intérieures (×5) | 2h |
| **TOTAL** | **~3h** |

**vs 15h** si vous recréez tout visuellement dans Breakdance sans les CSS!

---

## 🎯 AVANTAGES DE CETTE MÉTHODE

✅ **Design identique** à votre Next.js  
✅ **Gain de temps énorme** (3h vs 15h)  
✅ **Code propre** et maintenable  
✅ **Responsive** automatique  
✅ **Facilement modifiable** (juste changer le CSS)  

---

## 💡 CONSEILS

1. **Commencez par la page d'accueil** pour tester le CSS
2. **Uploadez vos images** dans WordPress avant de créer les pages
3. **Testez sur mobile** après chaque section
4. **Gardez le fichier CSS** pour modifications futures

---

## ❓ QUESTIONS FRÉQUENTES

### Q: Je peux modifier les couleurs?
**R:** Oui! Dans le CSS, changez les valeurs dans `:root`:
```css
:root {
  --bordeaux: #VOTRE_COULEUR;
  --blue-dark: #VOTRE_COULEUR;
}
```

### Q: Ça marche avec d'autres page builders?
**R:** Oui, le CSS fonctionne avec n'importe quel page builder (Elementor, Divi, etc.)

### Q: Je peux éditer le contenu facilement après?
**R:** Oui! Une fois les pages créées, vous éditez avec Breakdance normalement.

---

## 🚀 PROCHAINE ÉTAPE

**Commencez maintenant:**

1. Copiez le CSS dans WordPress (Apparence → Personnaliser → CSS additionnel)
2. Créez la page "Accueil"
3. Utilisez le HTML de la section Hero ci-dessus
4. Publiez et vérifiez!

**Le design sera identique à votre Next.js!** 🎉
