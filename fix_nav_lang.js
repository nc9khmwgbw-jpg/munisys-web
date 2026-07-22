const fs = require('fs');
let code = fs.readFileSync('src/components/EditableNavigation.js', 'utf8');

code = code.replace(
    /const \[lang, setLang\] = useState\('en'\);\n  const \{ dbData \} = useCMS\(\);/,
    `const { dbData, lang, setLang } = useCMS();`
);

fs.writeFileSync('src/components/EditableNavigation.js', code);
