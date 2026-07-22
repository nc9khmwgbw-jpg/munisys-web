const fs = require('fs');
let code = fs.readFileSync('src/components/EditableNavigation.js', 'utf8');

if (!code.includes('import { useState }')) {
    code = code.replace(
        'import { useCMS }',
        "import { useState } from 'react';\nimport { useCMS }"
    );
    fs.writeFileSync('src/components/EditableNavigation.js', code);
}
