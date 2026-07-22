const fs = require('fs');
let code = fs.readFileSync('src/components/EditableNavigation.js', 'utf8');
code = code.replace(
  /{.*?\.map\(\(key\) => {\s*const item = nav\.links\?\.\[key\];\s*if \(\!item\) return null;\s*return \(\s*<li key=\{key\} className="nav-item">\s*<a href=\{item\.href \|\| '#'\}>\s*<InlineEditor \s*as="span" \s*dbPath=\{\['navigation', 'links', key, 'label'\]\} \s*fallback=\{item\.label \|\| key\} \s*\/>\s*<\/a>\s*<\/li>\s*\);\s*}\)}/gs,
  `{(nav.mainLinks || []).map((item, idx) => (
              <li key={idx} className="nav-item">
                <a href={item.href || '#'}>
                  <InlineEditor 
                    as="span" 
                    dbPath={['navigation', 'mainLinks', idx, 'label']} 
                    fallback={item.label} 
                  />
                </a>
              </li>
            ))}`
);
fs.writeFileSync('src/components/EditableNavigation.js', code);
