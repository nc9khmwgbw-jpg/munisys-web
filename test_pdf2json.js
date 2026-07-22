const fs = require('fs');
const PDFParser = require('pdf2json');

async function test() {
  const buffer = fs.readFileSync('package.json'); // Just testing instantiation
  const pdfParser = new PDFParser(this, 1);
  console.log('PDFParser instantiated successfully in text mode');
}

test();
