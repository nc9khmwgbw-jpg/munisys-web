import fs from 'fs/promises';
import path from 'path';
import "./globals.css";
import { CMSProvider } from '@/components/CMSProvider';
import AdminToolbar from '@/components/AdminToolbar';
import EditableNavigation from '@/components/EditableNavigation';
import EditableFooter from '@/components/EditableFooter';
import CustomCursor from '@/components/CustomCursor';
import CookieBanner from '@/components/CookieBanner';

export const dynamic = 'force-dynamic';

import { cookies } from 'next/headers';

export async function generateMetadata() {
  try {
    const cookieStore = await cookies();
    const lang = cookieStore.get('munisys_lang')?.value || 'en';
    const dbPath = path.join(process.cwd(), 'src/data/db.json');
    const dbContent = await fs.readFile(dbPath, 'utf8');
    const data = JSON.parse(dbContent);
    const seo = data[lang]?.seo || data.en?.seo || {};
    
    return {
      title: seo.title || "Munisys — Enterprise IT Architecture & Integration",
      description: seo.description || "We orchestrate digital infrastructure technologies for a unique customer experience.",
      openGraph: {
        title: seo.title || "Munisys — Enterprise IT",
        description: seo.description || "We orchestrate digital infrastructure technologies for a unique customer experience.",
        url: 'https://www.munisys.net',
        siteName: 'Munisys',
        images: [
          {
            url: seo.ogImage || 'https://www.munisys.net/og-image.jpg',
            width: 1200,
            height: 630,
            alt: 'Munisys Preview',
          },
        ],
        locale: 'fr_FR',
        type: 'website',
      },
      icons: {
        icon: data.theme?.favicon || '/favicon.ico'
      }
    };
  } catch (error) {
    return {
      title: "Munisys — Enterprise IT Architecture & Integration",
      description: "We orchestrate digital infrastructure technologies for a unique customer experience.",
    };
  }
}

const Logo = () => (
  <svg width="45" height="45" viewBox="0 0 100 100" fill="none">
    <polygon points="10,20 40,50 10,80" fill="#83a5c2" />
    <polygon points="10,20 40,50 40,20" fill="#1d4e79" />
    <polygon points="40,50 70,20 70,80" fill="#922b55" />
    <polygon points="70,20 100,20 70,50" fill="#1d4e79" />
  </svg>
);

const CSS = `
:root {
  --bordeaux: #922b55;
  --blue-dark: #1d4e79;
  --blue-light: #83a5c2;
  --text-dark: #333333;
  --text-gray: #666666;
  --bg-gray: #fcfcfc;
}
`;

export default async function RootLayout({ children }) {
  let theme = { bordeaux: '#922b55', blueDark: '#1d4e79', blueLight: '#83a5c2', bgGray: '#f8fafc', textGray: '#475569' };
  try {
    const dbPath = path.join(process.cwd(), 'src/data/db.json');
    const dbContent = await fs.readFile(dbPath, 'utf8');
    const db = JSON.parse(dbContent);
    const cookieStore = await cookies();
    const lang = cookieStore.get('munisys_lang')?.value || 'en';
    if (db.theme) theme = { ...theme, ...db.theme };
    if (db[lang]?.theme) theme = { ...theme, ...db[lang].theme };
  } catch (e) {}

  const dynamicCSS = `
    :root {
      --bordeaux: ${theme.bordeaux};
      --blue-dark: ${theme.blueDark};
      --blue-light: ${theme.blueLight};
      --bg-gray: ${theme.bgGray};
      --text-gray: ${theme.textGray};
      --btn-radius: ${theme.buttonRadius || '30px'};
      --font-primary: ${
        theme.fontFamily === 'Inter' ? "'Inter', sans-serif" 
        : theme.fontFamily === 'Playfair Display' ? "'Playfair Display', serif" 
        : "'Montserrat', sans-serif"
      };
    }
  `;

  return (
    <html lang="fr">
      <head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@200;300;400;500;600;700;800;900&family=Outfit:wght@200;300;400;600;800;900&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet" />
        <style dangerouslySetInnerHTML={{ __html: dynamicCSS }} />
      </head>
      <body>
        <CMSProvider>
        <CustomCursor />
        <header id="munisys-global-header">
          <EditableNavigation />
        </header>

        {children}

        <div id="munisys-wrapper">
          <EditableFooter />
        </div>
        
        <AdminToolbar />
        <CookieBanner />
        </CMSProvider>
      </body>
    </html>
  );
}
