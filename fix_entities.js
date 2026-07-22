const fs = require('fs');
let code = fs.readFileSync('src/app/contact/page.js', 'utf8');

code = code.replace(/Let's Build/g, "Let&apos;s Build");
code = code.replace(/we'll get back/g, "we&apos;ll get back");
code = code.replace(/We'll be/g, "We&apos;ll be");

fs.writeFileSync('src/app/contact/page.js', code);
