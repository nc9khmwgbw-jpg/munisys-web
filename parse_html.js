const fs = require('fs');
fetch('http://localhost:3000').then(res => res.text()).then(html => {
  const match = html.match(/<div class="bento-grid">([\s\S]*?)<\/section>/);
  if (match) console.log(match[1]);
  else console.log("Not found");
}).catch(console.error);
