const fs = require('fs');
let code = fs.readFileSync('src/components/InlineEditor.js', 'utf8');

if (!code.includes('const { isEditMode, dbData, updateData, lang } = useCMS();', code.indexOf('export function InlineListEditor'))) {
    code = code.replace(
        'export function InlineListEditor({ dbPath, fallback = [] }) {\n  const { isEditMode, dbData, updateData } = useCMS();',
        'export function InlineListEditor({ dbPath, fallback = [] }) {\n  const { isEditMode, dbData, updateData, lang } = useCMS();'
    );
}

// Add key={lang} to the components in InlineListEditor
code = code.replace(
    /return \(\n      <>\n        \{list\.map/,
    'return (\n      <React.Fragment key={lang}>\n        {list.map'
);
code = code.replace(
    /return \(\n    <div\n      ref=\{elementRef\}/,
    'return (\n    <div\n      key={lang}\n      ref={elementRef}'
);

fs.writeFileSync('src/components/InlineEditor.js', code);
