const fs = require('fs');
let code = fs.readFileSync('src/app/careers/page.js', 'utf8');

const hookStr = `
    useEffect(() => {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('m-reveal-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.m-reveal, .m-reveal-left').forEach(el => observer.observe(el));
        
        return () => observer.disconnect();
    }, []);
`;

code = code.replace(
    /export default function CareersPage\(\) \{/,
    `import { useEffect } from 'react';\n\nexport default function CareersPage() {`
);

code = code.replace(
    /const \{ dbData \} = useCMS\(\);/,
    `const { dbData } = useCMS();\n${hookStr}`
);

fs.writeFileSync('src/app/careers/page.js', code);
