const fs = require('fs');
const dbPath = './src/data/db.json';
let db;
try {
  db = JSON.parse(fs.readFileSync(dbPath, 'utf8'));
} catch (e) {
  console.error("Failed to read db.json", e);
  process.exit(1);
}

if (!db.en.homepage.offeringsListThrough) {
  db.en.homepage.offeringsThroughTitle = "Through";
  db.en.homepage.offeringsAchieveTitle = "We achieve";
  db.en.homepage.offeringsListThrough = [
    "Best of breed technologies",
    "Skillful integrations",
    "Sound architectures",
    "Automation and orchestration",
    "Qualified end-to-end services"
  ];
  db.en.homepage.offeringsListAchieve = [
    "Efficient use of converging technologies",
    "Control over complexity",
    "Simplified IT Operations",
    "Agile infrastructure resources",
    "Optimal performance and true resilience",
    "Deep and broad support capabilities"
  ];
  fs.writeFileSync(dbPath, JSON.stringify(db, null, 2));
  console.log("Seeded db.en with offerings!");
} else {
  console.log("Already seeded!");
}
