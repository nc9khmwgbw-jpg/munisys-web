"use client";
import Link from 'next/link';

import InlineEditor from './InlineEditor';
import { useCMS } from './CMSProvider';

const defaultColumns = [
  {
    title: 'Solutions',
    links: [
      { label: 'Core Infrastructure', href: '/solutions' },
      { label: 'Cybersecurity', href: '/solutions' },
      { label: 'Infrastructure Software', href: '/solutions' },
      { label: 'Managed & Cloud Services', href: '/solutions' },
      { label: 'Endpoint Solutions', href: '/solutions' },
    ]
  },
  {
    title: 'Services',
    links: [
      { label: 'IT Integration', href: '/services' },
      { label: 'IT Support', href: '/services' },
      { label: 'Managed Services', href: '/services' },
    ]
  },
  {
    title: 'Company',
    links: [
      { label: 'About Us', href: '/about-us' },
      { label: 'Careers', href: '/careers' },
      { label: 'Insights', href: '/insights' },
      { label: 'Customer References', href: '/customer-references' },
    ]
  },
  {
    title: 'Contact',
    links: [
      { label: 'Get in Touch', href: '/contact' },
      { label: 'Technology Partners', href: '/technology-partners' },
    ]
  },
];

export default function EditableFooter() {
  const { dbData } = useCMS();
  const footer = dbData?.footer || {};
  const columns = (dbData?.footer?.columns?.length ? dbData.footer.columns : null) || defaultColumns;

  return (
    <div className="ft-wrapper">
      <footer className="ft">
        {/* Top bar: brand + linkedin */}
        <div className="ft-top">
          <InlineEditor
            as="span"
            className="ft-brand"
            dbPath={['footer', 'brandName']}
            fallback={footer.brandName || 'MUNISYS'}
          />
          <a
            href={footer.linkedinUrl || 'https://www.linkedin.com/company/munisys'}
            target="_blank"
            rel="noopener noreferrer"
            className="ft-linkedin"
          >
            <InlineEditor
              as="span"
              dbPath={['footer', 'linkedinLabel']}
              fallback={footer.linkedinLabel || 'Follow us on LinkedIn'}
            />
          </a>
        </div>

        {/* Navigation columns */}
        <div className="ft-main">
          {columns.map((col, colIdx) => (
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

        {/* Newsletter — texte à gauche, formulaire à droite, bien alignés */}
        <div className="ft-newsletter">
          <div className="ft-newsletter-text">
            <h4>
              <InlineEditor as="span" dbPath={['footer', 'newsletterTitle']} fallback="Restez informé" />
            </h4>
            <p>
              <InlineEditor as="span" dbPath={['footer', 'newsletterText']} fallback="Inscrivez-vous à notre newsletter pour recevoir nos dernières actualités IT." />
            </p>
          </div>
          <form
            className="ft-newsletter-form"
            onSubmit={async (e) => {
              e.preventDefault();
              const email = e.target.email.value;
              const btn = e.target.submitBtn;
              btn.disabled = true;
              btn.textContent = '...';
              try {
                const res = await fetch('/api/newsletter', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({ email }),
                });
                const data = await res.json();
                if (data.success) { btn.textContent = '✅'; e.target.reset(); }
                else { btn.textContent = 'Erreur'; }
              } catch {
                btn.textContent = 'Erreur';
              }
              setTimeout(() => { btn.disabled = false; btn.textContent = "S'inscrire"; }, 3000);
            }}
          >
            <input
              type="email"
              name="email"
              placeholder="votre@email.com"
              required
              className="ft-newsletter-input"
            />
            <button name="submitBtn" type="submit" className="ft-newsletter-btn">
              S&apos;inscrire
            </button>
          </form>
        </div>

        {/* Bottom bar */}
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
