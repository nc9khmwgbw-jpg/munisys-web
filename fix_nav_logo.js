const fs = require('fs');
let code = fs.readFileSync('src/components/EditableNavigation.js', 'utf8');

// Remove the hardcoded Logo component
code = code.replace(
    /const Logo = \(\) => \([\s\S]*?\);\n\n/,
    ''
);

// Replace the logo rendering block
code = code.replace(
    /<a href="\/" className="nav-logo" style=\{\{ display: 'flex', alignItems: 'center', gap: '10px' \}\}>\s*<Logo \/>\s*<InlineEditor[\s\S]*?className="nav-logo-mark"[\s\S]*?\/>\s*<\/a>/,
    `<a href="/" className="nav-logo" style={{ display: 'flex', alignItems: 'center', height: '40px' }}>
            <img src="https://www.munisys.ma/assets/images/logo.svg" alt="Munisys" style={{ height: '100%', width: 'auto' }} />
          </a>`
);

fs.writeFileSync('src/components/EditableNavigation.js', code);
console.log("Updated EditableNavigation.js with real logo.");
