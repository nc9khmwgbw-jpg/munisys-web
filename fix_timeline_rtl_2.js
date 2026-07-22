const fs = require('fs');
const cssPath = './src/app/globals.css';
let css = fs.readFileSync(cssPath, 'utf8');

// Remove the previous block we just added
css = css.replace(/\/\* Fixed RTL Timeline Rules \*\/.*?left: auto !important;\n    }\n}/gs, '');

const finalRTLOverrides = `
/* Final Fixed RTL Timeline Rules */
[dir="rtl"] .abt-tl-item:nth-child(odd) {
    right: 0 !important;
    left: auto !important;
    text-align: left !important;
    padding-left: 60px !important;
    padding-right: 40px !important;
}

[dir="rtl"] .abt-tl-item:nth-child(even) {
    right: 50% !important;
    left: auto !important;
    text-align: right !important;
    padding-right: 60px !important;
    padding-left: 40px !important;
}

[dir="rtl"] .abt-tl-item:nth-child(odd) .abt-tl-dot {
    left: -12px !important;
    right: auto !important;
}

[dir="rtl"] .abt-tl-item:nth-child(even) .abt-tl-dot {
    right: -12px !important;
    left: auto !important;
}

@media (max-width: 992px) {
    [dir="rtl"] .abt-timeline::before {
        right: 30px !important;
        left: auto !important;
    }
    
    [dir="rtl"] .abt-tl-item,
    [dir="rtl"] .abt-tl-item:nth-child(odd),
    [dir="rtl"] .abt-tl-item:nth-child(even) {
        width: 100% !important;
        right: 0 !important;
        left: auto !important;
        text-align: right !important;
        padding-right: 80px !important;
        padding-left: 20px !important;
    }

    [dir="rtl"] .abt-tl-item .abt-tl-dot,
    [dir="rtl"] .abt-tl-item:nth-child(odd) .abt-tl-dot,
    [dir="rtl"] .abt-tl-item:nth-child(even) .abt-tl-dot {
        right: 18px !important;
        left: auto !important;
    }
}
`;

css += finalRTLOverrides;
fs.writeFileSync(cssPath, css);
console.log('Fixed RTL CSS final');
