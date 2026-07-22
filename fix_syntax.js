const fs = require('fs');
let code = fs.readFileSync('src/components/EditableNavigation.js', 'utf8');

code = code.replace(
    /            <div className="custom-lang-switcher" data-active=\{lang\}>[\s\S]*?            <\/div>\n              <button className="lang-btn active">EN<\/button>\n              <button className="lang-btn">FR<\/button>\n            <\/div>/m,
    `            <div className="custom-lang-switcher" data-active={lang}>
              <div className="switcher-bg"></div>
              <button className={\`lang-btn \${lang === 'en' ? 'active' : ''}\`} onClick={() => setLang('en')}>EN</button>
              <button className={\`lang-btn \${lang === 'fr' ? 'active' : ''}\`} onClick={() => setLang('fr')}>FR</button>
            </div>`
);

fs.writeFileSync('src/components/EditableNavigation.js', code);
