const fs = require('fs');
let code = fs.readFileSync('src/app/page.js', 'utf8');

// Fix bottom padding of the section
code = code.replace(
  '<section className="ref-sec">',
  '<section className="ref-sec" style={{ paddingBottom: "8rem" }}>'
);

// Fix margin between marquees
code = code.replace(
  `marginBottom: '2rem'`,
  `marginBottom: '4rem'`
);

// Fix spacing between marquee and button
code = code.replace(
  `marginTop: '1.5rem'`,
  `marginTop: '4rem'`
);

// Fix line-height of ref-logo to prevent overlapping strokes
code = code.replace(
  /<div className="ref-logo">\{name\}<\/div>/g,
  '<div className="ref-logo" style={{ lineHeight: "1.5", padding: "1rem 0" }}>{name}</div>'
);

fs.writeFileSync('src/app/page.js', code);
