const fs = require('fs');
let code = fs.readFileSync('src/app/careers/page.js', 'utf8');

code = code.replace(
    /dangerouslySetInnerHTML=\{true\}\n\s*/g,
    ''
);

fs.writeFileSync('src/app/careers/page.js', code);
