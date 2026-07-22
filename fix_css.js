const fs = require('fs');
let css = fs.readFileSync('src/app/globals.css', 'utf8');
css = css.replace(/--([a-z-]+):\s+([^;]+)\s+;/g, '--$1: $2;');
fs.writeFileSync('src/app/globals.css', css);
