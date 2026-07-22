const fs = require('fs');
let code = fs.readFileSync('src/app/globals.css', 'utf8');

code = code.replace(
    /width: 40px !important;/g,
    'width: 40px !important; min-width: 40px !important;'
);
code = code.replace(
    /background: #ffffff !important;\n            border-radius: 50px !important;\n            box-shadow: 0 2px 8px rgba\(0, 0, 0, 0\.15\) !important;/g,
    'background: #ffffff !important;\n            border-radius: 50px !important;\n            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) !important;\n            border: 1px solid #dee2e6 !important;'
);

fs.writeFileSync('src/app/globals.css', code);
