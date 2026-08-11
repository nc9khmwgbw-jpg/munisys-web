export const metadata = {
  title: 'Contact — Munisys',
  description: 'Contactez nos experts en infrastructure IT et transformation digitale. Nous vous répondons dans les 24h.',
  openGraph: {
    title: 'Contact — Munisys',
    description: 'Contactez nos experts en infrastructure IT et transformation digitale.',
    url: 'https://www.munisys.net/contact',
    siteName: 'Munisys',
    images: [{ url: 'https://www.munisys.net/og-image.jpg', width: 1200, height: 630 }],
    type: 'website',
  },
  alternates: {
    canonical: 'https://www.munisys.net/contact',
  },
};

export default function ContactLayout({ children }) {
  return children;
}
