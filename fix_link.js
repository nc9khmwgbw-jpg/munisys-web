const fs = require('fs');
let code = fs.readFileSync('functions-snippet-complete.php', 'utf8');

// Replace both occurrences of the dynamic link with a hardcoded string
code = code.replace(/get_option\('munisys_press_release_url',\s*'\/press-release\/'\)/g, "'/press-release/'");

fs.writeFileSync('functions-snippet-complete.php', code);
console.log('Link fixed');
