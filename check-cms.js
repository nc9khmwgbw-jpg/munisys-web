#!/usr/bin/env node

/**
 * Script de vérification du CMS Munisys
 * Vérifie que tous les composants nécessaires sont en place
 */

const fs = require('fs');
const path = require('path');

const checks = [
  {
    name: 'Dossier uploads',
    path: 'public/uploads',
    type: 'dir',
    fix: 'Créez le dossier: mkdir -p public/uploads'
  },
  {
    name: 'Base de données JSON',
    path: 'src/data/db.json',
    type: 'file',
    fix: 'Le fichier db.json doit exister dans src/data/'
  },
  {
    name: 'CMSProvider',
    path: 'src/components/CMSProvider.js',
    type: 'file',
    fix: 'Le composant CMSProvider est manquant'
  },
  {
    name: 'InlineEditor',
    path: 'src/components/InlineEditor.js',
    type: 'file',
    fix: 'Le composant InlineEditor est manquant'
  },
  {
    name: 'InlineImageEditor',
    path: 'src/components/InlineImageEditor.js',
    type: 'file',
    fix: 'Le composant InlineImageEditor est manquant'
  },
  {
    name: 'AdminToolbar',
    path: 'src/components/AdminToolbar.js',
    type: 'file',
    fix: 'Le composant AdminToolbar est manquant'
  },
  {
    name: 'API Content',
    path: 'src/app/api/content/route.js',
    type: 'file',
    fix: "L'API route content est manquante"
  },
  {
    name: 'API Upload',
    path: 'src/app/api/upload/route.js',
    type: 'file',
    fix: "L'API route upload est manquante"
  }
];

console.log('🔍 Vérification de la configuration du CMS Munisys...\n');

let allGood = true;

checks.forEach((check) => {
  const fullPath = path.join(process.cwd(), check.path);
  let exists = false;

  try {
    const stats = fs.statSync(fullPath);
    if (check.type === 'dir') {
      exists = stats.isDirectory();
    } else {
      exists = stats.isFile();
    }
  } catch (e) {
    exists = false;
  }

  if (exists) {
    console.log(`✅ ${check.name}`);
  } else {
    console.log(`❌ ${check.name}`);
    console.log(`   → ${check.fix}\n`);
    allGood = false;
  }
});

console.log('\n' + '='.repeat(60));

if (allGood) {
  console.log('✅ Tous les composants CMS sont en place !');
  console.log('\n📝 Pour utiliser le CMS:');
  console.log('   1. Démarrez le serveur: npm run dev');
  console.log('   2. Accédez à: http://localhost:3000/admin');
  console.log('   3. Consultez le guide: CMS_GUIDE.md');
} else {
  console.log('⚠️  Certains composants sont manquants.');
  console.log('   Corrigez les problèmes ci-dessus avant de continuer.');
}

console.log('='.repeat(60) + '\n');
