const fs = require('fs');
const path = require('path');

function findFiles(dir, ext, fileList = []) {
  const files = fs.readdirSync(dir);
  for (const file of files) {
    const filePath = path.join(dir, file);
    if (fs.statSync(filePath).isDirectory()) {
      findFiles(filePath, ext, fileList);
    } else if (filePath.endsWith(ext)) {
      fileList.push(filePath);
    }
  }
  return fileList;
}

const jsFiles = findFiles('./src/app', '.js');

const dbPaths = {};

jsFiles.forEach(file => {
  const content = fs.readFileSync(file, 'utf8');
  
  // Regex to match InlineEditor with dbPath and fallback
  // Example: <InlineEditor as="span" dbPath={['aboutUs', 'timelineTitle']} fallback={data.timelineTitle || 'Our Journey'} />
  // Or: <InlineEditor dbPath={['some', 'key']} fallback="Some string" />
  
  const regex = /<InlineEditor[^>]*dbPath=\{\s*\[([^\]]+)\]\s*\}[^>]*fallback=\{?([^}>]+)\}?[^>]*>/g;
  let match;
  while ((match = regex.exec(content)) !== null) {
    const pathStr = match[1];
    let fallbackStr = match[2].trim();
    
    // Parse dbPath array
    const pathParts = pathStr.split(',').map(s => s.trim().replace(/['"]/g, '').replace(/`/g, ''));
    
    // Parse fallback (clean up simple strings, or evaluate default objects if possible)
    if (fallbackStr.startsWith("'") || fallbackStr.startsWith('"') || fallbackStr.startsWith('`')) {
      fallbackStr = fallbackStr.slice(1, -1);
    } else if (fallbackStr.includes('||')) {
      const parts = fallbackStr.split('||');
      fallbackStr = parts[parts.length - 1].trim();
      if (fallbackStr.startsWith("'") || fallbackStr.startsWith('"') || fallbackStr.startsWith('`')) {
        fallbackStr = fallbackStr.slice(1, -1);
      }
    }
    
    dbPaths[pathParts.join('.')] = fallbackStr;
  }
});

console.log(JSON.stringify(dbPaths, null, 2));
