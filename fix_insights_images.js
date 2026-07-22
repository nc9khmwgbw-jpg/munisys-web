const fs = require('fs');
let code = fs.readFileSync('src/app/insights/page.js', 'utf8');

// Ensure InlineImageEditor is imported
if (!code.includes('InlineImageEditor')) {
    code = code.replace(
        /import InlineEditor from '@\/components\/InlineEditor';/,
        `import InlineEditor, { InlineImageEditor } from '@/components/InlineEditor';`
    );
}

// Replace the image rendering in insights with InlineImageEditor
code = code.replace(
    /<div className="ins-img-wrap" style=\{\{ background: '#f8f9fa' \}\}>[\s\S]*?<\/div>\s*<\/div>/,
    `<div className="ins-img-wrap" style={{ background: '#f8f9fa', padding: 0 }}>
        <InlineImageEditor 
            dbPath={['insights', 'posts', idx, 'img']} 
            fallback={post.img || ''} 
            style={{ width: '100%', height: '100%' }}
            styleImg={{ width: '100%', height: '100%', objectFit: 'cover' }}
        >
            {(src) => src ? (
                <img src={src} alt={post.title} style={{width: '100%', height: '100%', objectFit: 'cover'}} />
            ) : (
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: '100%', height: '100%', minHeight: '150px' }}>
                    <span style={{ color: '#a0aec0', fontSize: '0.9rem', fontStyle: 'italic' }}>Upload Image</span>
                </div>
            )}
        </InlineImageEditor>
    </div>`
);

fs.writeFileSync('src/app/insights/page.js', code);
console.log("Updated Insights page to use InlineImageEditor");
