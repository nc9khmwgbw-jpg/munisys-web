const fs = require('fs');
let code = fs.readFileSync('src/components/InlineEditor.js', 'utf8');

code = code.replace(
    /        \}\)\}\n      <\/>/,
    '        }))}\n      </React.Fragment>'
);

fs.writeFileSync('src/components/InlineEditor.js', code);
