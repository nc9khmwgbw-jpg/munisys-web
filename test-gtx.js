async function test() {
  const targetLang = 'es';
  const textToTranslate = "Hello world ||| How are you? ||| I am fine.";
  const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=${targetLang}&dt=t&q=${encodeURIComponent(textToTranslate)}`;
  const fetchRes = await fetch(url);
  const data = await fetchRes.json();
  const translatedText = data[0].map(item => item[0]).join('');
  console.log("Translated Text:", translatedText);
  const translatedParts = translatedText.split(/\|\|\||\| \| \|/g).map(s => s.trim());
  console.log("Parts:", translatedParts);
}
test();
