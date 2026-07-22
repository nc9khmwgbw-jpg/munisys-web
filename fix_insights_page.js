const fs = require('fs');
let code = fs.readFileSync('src/app/insights/page.js', 'utf8');

code = code.replace(
    /<span className="ins-learn-more">Learn more →<\/span>/,
    `<InlineEditor as="span" className="ins-learn-more" dbPath={['insights', 'learnMore']} fallback="Learn more &rarr;" />`
);

fs.writeFileSync('src/app/insights/page.js', code);
