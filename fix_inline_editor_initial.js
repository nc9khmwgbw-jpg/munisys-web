const fs = require('fs');
let code = fs.readFileSync('src/components/InlineEditor.js', 'utf8');

// Replace the useState initialization for content
code = code.replace(
    /const \[content, setContent\] = useState\(''\);/,
    `const [content, setContent] = useState(() => {
    if (!dbData) return fallback || '';
    let val = dbData;
    for (const p of dbPath) {
      if (val && val[p] !== undefined) val = val[p];
      else { val = null; break; }
    }
    return val || fallback || '';
  });`
);

// Replace the useState initialization for list in InlineListEditor
code = code.replace(
    /const \[list, setList\] = useState\(\[\]\);/,
    `const [list, setList] = useState(() => {
    if (!dbData) return fallback || [];
    let val = dbData;
    for (const p of dbPath) {
      if (val && val[p] !== undefined) val = val[p];
      else { val = null; break; }
    }
    return val || fallback || [];
  });`
);

fs.writeFileSync('src/components/InlineEditor.js', code);
