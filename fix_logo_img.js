const fs = require('fs');
const reactComponent = `export default function Logo() {
  return (
    <div style={{ display: 'flex', alignItems: 'center', height: '40px' }}>
      <img src="https://www.munisys.ma/assets/images/logo.svg" alt="Munisys Logo" style={{ height: '100%', width: 'auto' }} />
    </div>
  );
}`;
fs.writeFileSync('src/components/Logo.js', reactComponent);
console.log("Logo updated to use img tag pointing to live URL!");
