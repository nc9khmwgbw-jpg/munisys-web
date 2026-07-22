require('dotenv').config({ path: '.env.local' });
const mongoose = require('mongoose');
const fs = require('fs');
const path = require('path');

const MONGODB_URI = process.env.MONGODB_URI;

if (!MONGODB_URI) {
  console.error("❌ ERREUR: MONGODB_URI manquant dans .env.local");
  process.exit(1);
}

const MunisysStateSchema = new mongoose.Schema({
  data: { type: mongoose.Schema.Types.Mixed, required: true },
  version: { type: Number, default: 1 }
}, { timestamps: true });

const MunisysState = mongoose.models.MunisysState || mongoose.model('MunisysState', MunisysStateSchema);

async function migrate() {
  try {
    console.log("Connexion à MongoDB...");
    await mongoose.connect(MONGODB_URI);
    console.log("✅ Connecté à MongoDB.");

    const dbPath = path.join(process.cwd(), 'src/data/db.json');
    const fileContents = fs.readFileSync(dbPath, 'utf8');
    const data = JSON.parse(fileContents);

    console.log("Supression des anciennes données...");
    await MunisysState.deleteMany({});

    console.log("Insertion du fichier db.json dans MongoDB...");
    const state = new MunisysState({ data });
    await state.save();

    console.log("✅ Migration terminée avec succès !");
    process.exit(0);
  } catch (err) {
    console.error("❌ Erreur de migration :", err);
    process.exit(1);
  }
}

migrate();
