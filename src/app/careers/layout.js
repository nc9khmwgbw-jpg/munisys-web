export const metadata = {
  title: 'Carrières — Rejoignez Munisys',
  description: 'Rejoignez l\'équipe Munisys et façonnez l\'avenir de l\'infrastructure digitale. Découvrez nos offres d\'emploi en IT, cybersécurité et cloud.',
  openGraph: {
    title: 'Carrières — Rejoignez Munisys',
    description: 'Façonnez l\'avenir de l\'infrastructure digitale avec Munisys.',
    url: 'https://www.munisys.net/careers',
    siteName: 'Munisys',
    images: [{ url: 'https://www.munisys.net/og-image.jpg', width: 1200, height: 630 }],
    type: 'website',
  },
  alternates: {
    canonical: 'https://www.munisys.net/careers',
  },
};

export default function CareersLayout({ children }) {
  return children;
}
