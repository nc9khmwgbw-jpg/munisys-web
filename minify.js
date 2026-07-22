const fs = require('fs');
let html = fs.readFileSync('PAGE_SOLUTIONS_COMPLETE.html', 'utf8');

// Remove comments
html = html.replace(/<!--[\s\S]*?-->/g, '');

// Extract and minify CSS
html = html.replace(/<style>([\s\S]*?)<\/style>/i, (match, css) => {
  const minifiedCss = css.replace(/\s+/g, ' ').replace(/\/\*[\s\S]*?\*\//g, '').trim();
  return `<style>${minifiedCss}</style>`;
});

// Extract and minify JS (basic)
html = html.replace(/<script>([\s\S]*?)<\/script>/i, (match, js) => {
  const minifiedJs = js.replace(/\s+/g, ' ').replace(/\/\*[\s\S]*?\*\//g, '').trim();
  return `<script>${minifiedJs}</script>`;
});

fs.writeFileSync('PAGE_SOLUTIONS_MINIFIED.html', html);
console.log('Successfully generated PAGE_SOLUTIONS_MINIFIED.html');
