export const metadata = {
  title: 'Insights & Actualités — Munisys',
  description: 'Découvrez les dernières actualités, publications et communiqués de presse de Munisys sur l\'infrastructure IT, la cybersécurité et la transformation digitale.',
  openGraph: {
    title: 'Insights & Actualités — Munisys',
    description: 'Actualités et publications Munisys sur l\'IT et la transformation digitale.',
    url: 'https://www.munisys.net/insights',
    siteName: 'Munisys',
    images: [{ url: 'https://www.munisys.net/og-image.jpg', width: 1200, height: 630 }],
    type: 'website',
  },
  alternates: {
    canonical: 'https://www.munisys.net/insights',
  },
};

export default function InsightsLayout({ children }) {
  return children;
}
