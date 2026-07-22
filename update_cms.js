const fs = require('fs');

let code = fs.readFileSync('src/components/CMSProvider.js', 'utf8');

// Add lang state
code = code.replace(
    'const [hasUnsavedChanges, setHasUnsavedChanges] = useState(false);',
    'const [hasUnsavedChanges, setHasUnsavedChanges] = useState(false);\n  const [lang, setLang] = useState("en");'
);

// Fix updateData to prepend lang if dbData[lang] exists
code = code.replace(
    /const updateData = \(pathArray, newValue\) => \{[\s\S]*?setDbData\(prev => \{/,
    `const updateData = (pathArray, newValue) => {
    if (!dbData) return;
    
    // If the database has a language root, prepend the current language to the path
    const actualPath = (dbData.en !== undefined) ? [lang, ...pathArray] : pathArray;
    
    setDbData(prev => {`
);

// Fix the path variables in updateData
code = code.replace(
    /for \(let i = 0; i < pathArray.length - 1; i\+\+\) \{[\s\S]*?current\[pathArray\[pathArray.length - 1\]\] = newValue;/,
    `for (let i = 0; i < actualPath.length - 1; i++) {
        current[actualPath[i]] = { ...current[actualPath[i]] };
        current = current[actualPath[i]];
      }
      current[actualPath[actualPath.length - 1]] = newValue;`
);

// Export lang, setLang and the subset of dbData in the context
code = code.replace(
    /<CMSContext\.Provider value=\{\{ isEditMode, toggleEditMode, dbData, updateData, saveChanges, saving, hasUnsavedChanges \}\}>/,
    `{/* Determine which data to expose to components */}
    <CMSContext.Provider value={{ 
      isEditMode, 
      toggleEditMode, 
      dbData: dbData?.[lang] !== undefined ? dbData[lang] : dbData, 
      updateData, 
      saveChanges, 
      saving, 
      hasUnsavedChanges,
      lang,
      setLang
    }}>`
);

// Fix the theme injection which also needs dbData[lang]
code = code.replace(
    /\{dbData\?\.theme && \(/,
    `{(dbData?.[lang]?.theme || dbData?.theme) && (`
);
code = code.replace(
    /--bordeaux: \$\{dbData\.theme\.bordeaux\}/g,
    `--bordeaux: \${(dbData?.[lang]?.theme || dbData?.theme).bordeaux}`
);
code = code.replace(
    /--blue-dark: \$\{dbData\.theme\.blueDark\}/g,
    `--blue-dark: \${(dbData?.[lang]?.theme || dbData?.theme).blueDark}`
);
code = code.replace(
    /--blue-light: \$\{dbData\.theme\.blueLight\}/g,
    `--blue-light: \${(dbData?.[lang]?.theme || dbData?.theme).blueLight}`
);
code = code.replace(
    /--bg-gray: \$\{dbData\.theme\.bgGray\}/g,
    `--bg-gray: \${(dbData?.[lang]?.theme || dbData?.theme).bgGray}`
);
code = code.replace(
    /--text-gray: \$\{dbData\.theme\.textGray\}/g,
    `--text-gray: \${(dbData?.[lang]?.theme || dbData?.theme).textGray}`
);

fs.writeFileSync('src/components/CMSProvider.js', code);
