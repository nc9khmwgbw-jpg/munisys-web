const fs = require('fs');
let code = fs.readFileSync('src/components/InlineEditor.js', 'utf8');

if (!code.includes('const { isEditMode, dbData, updateData, lang } = useCMS();')) {
    code = code.replace(
        'const { isEditMode, dbData, updateData } = useCMS();',
        'const { isEditMode, dbData, updateData, lang } = useCMS();'
    );
}

// Add key={lang} to the components
code = code.replace(
    /return <Component className=\{className\} style=\{style\}/,
    'return <Component key={lang} className={className} style={style}'
);

code = code.replace(
    /return \(\n    <Component\n      ref=\{elementRef\}/,
    'return (\n    <Component\n      key={lang}\n      ref={elementRef}'
);

fs.writeFileSync('src/components/InlineEditor.js', code);
