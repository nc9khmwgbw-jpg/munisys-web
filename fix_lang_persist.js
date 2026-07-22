const fs = require('fs');
let code = fs.readFileSync('src/components/CMSProvider.js', 'utf8');

// Replace standard useState for lang with one that checks localStorage (but we can't do that safely on server render, so we'll do it in useEffect)
code = code.replace(
    /const \[lang, setLang\] = useState\("en"\);/,
    `const [lang, setLang] = useState("en");

  // Load language from localStorage
  useEffect(() => {
    const savedLang = localStorage.getItem('munisys_lang');
    if (savedLang) {
      setLang(savedLang);
    }
  }, []);

  const changeLang = (newLang) => {
    setLang(newLang);
    localStorage.setItem('munisys_lang', newLang);
  };`
);

// Replace setLang in context with changeLang
code = code.replace(
    /setLang\n    \}\}>/,
    `setLang: changeLang\n    }}>`
);

fs.writeFileSync('src/components/CMSProvider.js', code);
