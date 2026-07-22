const fs = require('fs');

const phpFile = fs.readFileSync('functions-snippet-complete.php', 'utf8');
const match = phpFile.match(/\$logo_base64 = '([^']+)';/);
if (match) {
    const base64 = match[1].replace('data:image/svg+xml;base64,', '');
    const svgContent = Buffer.from(base64, 'base64').toString('utf8');
    
    // Create the React component
    const reactComponent = `export default function Logo() {
  return (
    <div style={{ display: 'flex', alignItems: 'center', height: '40px' }} dangerouslySetInnerHTML={{ __html: \`${svgContent.replace(/`/g, '\\`')}\` }} />
  );
}`;
    
    fs.writeFileSync('src/components/Logo.js', reactComponent);
    console.log("Logo updated!");
} else {
    console.log("Could not find logo_base64 in php file.");
}
