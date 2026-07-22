const fs = require('fs');
let html = fs.readFileSync('PAGE_SOLUTIONS_COMPLETE.html', 'utf8');

// Remove comments
html = html.replace(/<!--[\s\S]*?-->/g, '');

// Minify CSS
html = html.replace(/<style>([\s\S]*?)<\/style>/i, (match, css) => {
  return '<style>' + css.replace(/\s+/g, ' ').replace(/\/\*[\s\S]*?\*\//g, '').trim() + '</style>';
});

// Minify JS
html = html.replace(/<script>([\s\S]*?)<\/script>/i, (match, js) => {
  return '<script>' + js.replace(/\s+/g, ' ').replace(/\/\*[\s\S]*?\*\//g, '').trim() + '</script>';
});

// Minify HTML (remove all newlines and multiple spaces between tags)
html = html.replace(/>\s+</g, '><').trim();

fs.writeFileSync('PAGE_SOLUTIONS_MINIFIED.html', html);
console.log('Successfully generated PAGE_SOLUTIONS_MINIFIED.html');
