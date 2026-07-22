const fs = require('fs');
const path = require('path');

const partnersDir = './public/partners';

const svgs = {
  'cisco.svg': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30" width="100%" height="100%"><text x="0" y="22" font-family="Arial" font-weight="bold" font-size="22" fill="#049fd9">CISCO</text></svg>',
  'dell.svg': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30" width="100%" height="100%"><circle cx="15" cy="15" r="14" stroke="#007db8" stroke-width="2" fill="none"/><text x="4" y="19" font-family="Arial" font-weight="bold" font-size="9" fill="#007db8">DELL</text></svg>',
  'vmware.svg': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30" width="100%" height="100%"><text x="0" y="22" font-family="Arial" font-weight="bold" font-size="22" fill="#607078">vmware</text></svg>',
  'fortinet.svg': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30" width="100%" height="100%"><text x="0" y="22" font-family="Arial" font-weight="bold" font-size="22" fill="#E31837">FORTINET</text></svg>',
  'microsoft.svg': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30" width="100%" height="100%"><rect x="0" y="5" width="10" height="10" fill="#F25022"/><rect x="12" y="5" width="10" height="10" fill="#7FBA00"/><rect x="0" y="17" width="10" height="10" fill="#00A4EF"/><rect x="12" y="17" width="10" height="10" fill="#FFB900"/><text x="28" y="24" font-family="Segoe UI,Arial" font-weight="bold" font-size="18" fill="#737373">Microsoft</text></svg>',
  'ibm.svg': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 30" width="100%" height="100%"><text x="0" y="25" font-family="Arial" font-weight="bold" font-size="28" fill="#0530ad">IBM</text></svg>'
};

for (const [filename, content] of Object.entries(svgs)) {
  fs.writeFileSync(path.join(partnersDir, filename), content);
}

console.log('SVGs updated with width/height attributes!');
