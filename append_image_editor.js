const fs = require('fs');

const codeToAppend = `
export function InlineImageEditor({ dbPath, fallback, className, style, alt, styleImg, children }) {
  const { isEditMode, dbData, updateData, lang } = useCMS();
  const [uploading, setUploading] = useState(false);
  
  const [content, setContent] = useState(() => {
    if (!dbData) return fallback || '';
    let val = dbData;
    for (const p of dbPath) {
      if (val && val[p] !== undefined) val = val[p];
      else { val = null; break; }
    }
    return val || fallback || '';
  });

  useEffect(() => {
    if (!dbData) return;
    let val = dbData;
    for (const p of dbPath) {
      if (val && val[p] !== undefined) val = val[p];
      else { val = null; break; }
    }
    setContent(val || fallback || '');
  }, [dbData, dbPath, fallback]);

  const handleFileChange = async (e) => {
    const file = e.target.files[0];
    if (!file) return;

    setUploading(true);
    const formData = new FormData();
    formData.append('file', file);

    try {
      const res = await fetch('/api/upload', {
        method: 'POST',
        body: formData,
      });
      const data = await res.json();
      if (data.success) {
        updateData(dbPath, data.url);
      } else {
        alert("Erreur d'upload");
      }
    } catch (err) {
      alert("Erreur d'upload");
    } finally {
      setUploading(false);
    }
  };

  if (!isEditMode) {
    if (children) {
      return children(content);
    }
    return <img src={content} alt={alt || ''} className={className} style={styleImg || style} />;
  }

  return (
    <div 
      className={className} 
      style={{
        ...style,
        position: 'relative',
        outline: '2px dashed var(--bordeaux)',
        outlineOffset: '2px',
        cursor: 'pointer',
        display: 'inline-block'
      }}
      title="Cliquez pour changer l'image"
    >
      <label style={{ width: '100%', height: '100%', display: 'block', cursor: 'pointer', margin: 0 }}>
        {children ? (
          children(content)
        ) : (
          <img src={content} alt={alt || ''} style={{ ...(styleImg || style), opacity: uploading ? 0.5 : 1 }} />
        )}
        <input type="file" style={{ display: 'none' }} accept="image/*" onChange={handleFileChange} disabled={uploading} />
        {uploading && (
          <div style={{ position: 'absolute', inset: 0, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(255,255,255,0.7)', zIndex: 10 }}>
            <span style={{ fontWeight: 'bold', color: 'var(--bordeaux)' }}>...</span>
          </div>
        )}
      </label>
    </div>
  );
}
`;

fs.appendFileSync('src/components/InlineEditor.js', codeToAppend);
console.log("Appended InlineImageEditor!");
