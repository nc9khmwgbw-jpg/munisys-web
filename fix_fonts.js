const fs = require('fs');
const file = 'src/app/globals.css';
let css = fs.readFileSync(file, 'utf8');

// Add new font imports if not present
const imports = `
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap');
`;
if (!css.includes('family=Inter')) {
  css = imports + css;
}

// Replace hardcoded Montserrat with var(--font-primary)
// We'll catch variations like font-family: 'Montserrat', sans-serif !important;
css = css.replace(/font-family:\s*['"]?Montserrat['"]?,\s*sans-serif\s*(!important)?;/g, 'font-family: var(--font-primary, \'Montserrat\', sans-serif) $1;');

fs.writeFileSync(file, css);
console.log('Fonts fixed!');
