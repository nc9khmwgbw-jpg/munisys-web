"use client";
import Link from 'next/link';

import InlineEditor from './InlineEditor';
import { useCMS } from './CMSProvider';

export default function EditableFooter() {
  const { dbData } = useCMS();
  const footer = dbData?.footer || {};

  return (
    <div className="ft-wrapper">
      <footer className="ft">
        <div className="ft-top">
          <InlineEditor 
            as="span" 
            className="ft-brand" 
            dbPath={['footer', 'brandName']} 
            fallback={footer.brandName || 'MUNISYS'} 
          />
          <Link href={footer.linkedinUrl || '#'} className="ft-linkedin">
            <InlineEditor 
              as="span" 
              dbPath={['footer', 'linkedinLabel']} 
              fallback={footer.linkedinLabel || 'Follow us on LinkedIn'} 
            />
          </Link>
        </div>
        
        <div className="ft-main">
          {(dbData?.footer?.columns || []).map((col, colIdx) => (
            <div key={colIdx} className="ft-col">
              <InlineEditor 
                as="h4" 
                dbPath={['footer', 'columns', colIdx, 'title']} 
                fallback={col.title} 
              />
              <ul>
                {(col.links || []).map((link, linkIdx) => (
                  <li key={linkIdx}>
                    <Link href={link.href || '#'}>
                      <InlineEditor 
                        as="span" 
                        dbPath={['footer', 'columns', colIdx, 'links', linkIdx, 'label']} 
                        fallback={link.label} 
                      />
                    </Link>
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>

        <div style={{ padding: '0 5rem 2rem 5rem', borderBottom: '1px solid rgba(255, 255, 255, 0.08)' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '20px' }}>
            <div>
              <h4 style={{ color: '#fff', fontSize: '1.2rem', margin: '0 0 10px 0' }}>
                <InlineEditor as="span" dbPath={['footer', 'newsletterTitle']} fallback="Restez informé" />
              </h4>
              <p style={{ color: 'rgba(255, 255, 255, 0.6)', fontSize: '0.9rem', margin: 0 }}>
                <InlineEditor as="span" dbPath={['footer', 'newsletterText']} fallback="Inscrivez-vous à notre newsletter pour recevoir nos dernières actualités IT." />
              </p>
            </div>
            <form onSubmit={async (e) => {
              e.preventDefault();
              const email = e.target.email.value;
              const btn = e.target.submitBtn;
              btn.disabled = true;
              btn.textContent = "...";
              try {
                const res = await fetch('/api/newsletter', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ email }) });
                const data = await res.json();
                if (data.success) { btn.textContent = "✅"; e.target.reset(); } else { btn.textContent = "Erreur"; }
              } catch (err) { btn.textContent = "Erreur"; }
              setTimeout(() => { btn.disabled = false; btn.textContent = "S'inscrire"; }, 3000);
            }} style={{ display: 'flex', gap: '10px' }}>
              <input type="email" name="email" placeholder="votre@email.com" required style={{ padding: '10px 15px', borderRadius: '4px', border: '1px solid rgba(255,255,255,0.2)', background: 'transparent', color: '#fff', outline: 'none', width: '250px' }} />
              <button name="submitBtn" type="submit" style={{ padding: '10px 20px', borderRadius: '4px', border: '1px solid #fff', background: '#fff', color: '#000', fontWeight: 'bold', cursor: 'pointer' }}>S'inscrire</button>
            </form>
          </div>
        </div>
        
        <div className="ft-bottom">
          <InlineEditor 
            as="span" 
            dbPath={['footer', 'copyright']} 
            fallback={footer.copyright || `© ${new Date().getFullYear()} Munisys. All rights reserved.`} 
          />
          <div style={{ display: 'flex', gap: '20px' }}>
            <Link href="/mentions-legales" style={{ color: 'inherit', textDecoration: 'none' }}>Mentions Légales</Link>
            <Link href="/politique-confidentialite" style={{ color: 'inherit', textDecoration: 'none' }}>Politique de Confidentialité</Link>
          </div>
        </div>
      </footer>
    </div>
  );
}
