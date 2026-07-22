let dbData = { en: { theme: { bordeaux: "#922b55" } } };
const lang = 'en';

const updateData = (pathArray, newValue) => {
  if (!dbData) return;
  const isGlobal = pathArray[0] === 'theme';
  const actualPath = (dbData.en !== undefined && !isGlobal) ? [lang, ...pathArray] : pathArray;
  
  let newData = { ...dbData };
  let current = newData;
  for (let i = 0; i < actualPath.length - 1; i++) {
    current[actualPath[i]] = { ...current[actualPath[i]] };
    current = current[actualPath[i]];
  }
  current[actualPath[actualPath.length - 1]] = newValue;
  
  dbData = newData;
};

updateData(['theme', 'fontFamily'], 'Inter');
console.log(dbData.theme.fontFamily);
