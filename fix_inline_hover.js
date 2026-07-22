const fs = require('fs');
let code = fs.readFileSync('src/components/InlineEditor.js', 'utf8');

// Add global styles for the editable hover effect
if (!code.includes('.munisys-editable')) {
    code = code.replace(
        /export default function InlineEditor/,
        `
// CSS inject for hover states
if (typeof document !== 'undefined') {
  const style = document.createElement('style');
  style.innerHTML = \`
    .munisys-editable {
      transition: all 0.2s ease;
    }
    .munisys-editable:hover {
      background: rgba(146, 43, 85, 0.1) !important;
      outline: 2px solid var(--bordeaux) !important;
      z-index: 10;
    }
    .munisys-editable-img:hover {
      outline: 4px solid var(--bordeaux) !important;
      opacity: 0.8;
    }
  \`;
  document.head.appendChild(style);
}

export default function InlineEditor`
    );
}

// Add the className to InlineEditor
code = code.replace(
    /className=\{className\}/,
    `className={\`\${className || ''} munisys-editable\`}`
);

// Add className to InlineImageEditor
code = code.replace(
    /className=\{className\}/g,
    `className={\`\${className || ''} munisys-editable-img\`}`
);

fs.writeFileSync('src/components/InlineEditor.js', code);
console.log("Added hover styles to InlineEditor");
