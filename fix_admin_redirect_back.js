const fs = require('fs');
let code = fs.readFileSync('src/app/admin/page.js', 'utf8');

code = code.replace(
    /if \(r\.ok && d\.success\) \{ localStorage\.setItem\('munisys_admin_auth', 'true'\); router\.push\('\/admin\/dashboard'\); \}/,
    `if (r.ok && d.success) { localStorage.setItem('munisys_admin_auth', 'true'); router.push('/'); }`
);

fs.writeFileSync('src/app/admin/page.js', code);
console.log("Reverted redirect to /");
