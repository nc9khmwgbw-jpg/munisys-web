const fs = require('fs');
let code = fs.readFileSync('src/components/EditableNavigation.js', 'utf8');

if (!code.includes('const [lang, setLang] = useState(')) {
    code = code.replace(
        'export default function EditableNavigation() {',
        "export default function EditableNavigation() {\n  const [lang, setLang] = useState('en');"
    );
}

// Replace the switcher HTML
code = code.replace(
    /<div className="custom-lang-switcher" data-active="en">[\s\S]*?<\/div>/,
    `<div className="custom-lang-switcher" data-active={lang}>
              <div className="switcher-bg"></div>
              <button className={\`lang-btn \${lang === 'en' ? 'active' : ''}\`} onClick={() => setLang('en')}>EN</button>
              <button className={\`lang-btn \${lang === 'fr' ? 'active' : ''}\`} onClick={() => setLang('fr')}>FR</button>
            </div>`
);

fs.writeFileSync('src/components/EditableNavigation.js', code);
