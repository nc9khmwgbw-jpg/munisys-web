const fs = require('fs');
let code = fs.readFileSync('src/app/admin/page.js', 'utf8');

code = code.replace(
    /localStorage\.setItem\('munisys_admin_auth', 'true'\)/g,
    `localStorage.setItem('munisys_admin', 'true')`
);

fs.writeFileSync('src/app/admin/page.js', code);
console.log("Fixed localStorage key in admin/page.js");
