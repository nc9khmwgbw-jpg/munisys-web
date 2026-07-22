const fs = require('fs');
let code = fs.readFileSync('src/app/globals.css', 'utf8');

code = code.replace(
    /font-size: 5\.5rem !important;/g,
    'font-size: clamp(2rem, 5vh, 5.5rem) !important;'
);

fs.writeFileSync('src/app/globals.css', code);
