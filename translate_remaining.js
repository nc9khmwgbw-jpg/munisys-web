const fs = require('fs');
const https = require('https');
const dbPath = './src/data/db.json';
const db = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

// Add missing fields
db.en.services.heroMainTitle = "Our";
db.en.services.heroMainTitleBold = "Services.";
db.en.customerReferences.heroMainTitle = "Trusted by";
db.en.customerReferences.heroMainTitleBold = "Leaders.";
db.en.technologyPartners.allSpecialtiesLabel = "All Specialties";
db.en.contact.heroMainTitle = "Let's Build";
db.en.contact.heroMainTitleBold = "Together.";
db.en.contact.formTitle = "Send a Message";
db.en.contact.formSub = "Fill in the form below and we'll get back to you within 24 hours.";
db.en.contact.formSuccess = "✅ Message sent! We'll be in touch shortly.";
db.en.contact.labelName = "Full Name";
db.en.contact.labelEmail = "Email Address";
db.en.contact.labelCompany = "Company";
db.en.contact.labelPhone = "Phone Number";
db.en.contact.labelMessage = "How can we help you?";
db.en.contact.formNote = "🔒 Your data is 100% secure & never shared.";
db.en.contact.btnSubmit = "Send Message";

const toTranslate = [
    ['services', 'heroMainTitle'],
    ['services', 'heroMainTitleBold'],
    ['customerReferences', 'heroMainTitle'],
    ['customerReferences', 'heroMainTitleBold'],
    ['technologyPartners', 'allSpecialtiesLabel'],
    ['contact', 'heroMainTitle'],
    ['contact', 'heroMainTitleBold'],
    ['contact', 'formTitle'],
    ['contact', 'formSub'],
    ['contact', 'formSuccess'],
    ['contact', 'labelName'],
    ['contact', 'labelEmail'],
    ['contact', 'labelCompany'],
    ['contact', 'labelPhone'],
    ['contact', 'labelMessage'],
    ['contact', 'formNote'],
    ['contact', 'btnSubmit']
];

async function translateText(text) {
    if (!text) return text;
    return new Promise((resolve) => {
        const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=ar&dt=t&q=${encodeURIComponent(text)}`;
        https.get(url, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => {
                try {
                    const json = JSON.parse(data);
                    let translated = '';
                    if (json && json[0]) {
                        json[0].forEach(item => {
                            if (item[0]) translated += item[0];
                        });
                        resolve(translated);
                    } else resolve(text);
                } catch (e) { resolve(text); }
            });
        }).on('error', () => resolve(text));
    });
}

const sleep = ms => new Promise(r => setTimeout(r, ms));

async function main() {
    for(const path of toTranslate) {
        const section = path[0];
        const key = path[1];
        
        const enText = db.en[section][key];
        console.log(`Translating: ${enText}`);
        const arText = await translateText(enText);
        
        if(!db.ar[section]) db.ar[section] = {};
        db.ar[section][key] = arText;
        
        await sleep(300);
    }
    
    fs.writeFileSync(dbPath, JSON.stringify(db, null, 2));
    console.log("Translation finished!");
}

main();
