const fs = require('fs');
let code = fs.readFileSync('src/components/CMSProvider.js', 'utf8');

code = code.replace(
    /return \(\n    \{\/\* Determine which data to expose to components \*\/\}\n    <CMSContext.Provider/,
    'return (\n    <CMSContext.Provider'
);

fs.writeFileSync('src/components/CMSProvider.js', code);
