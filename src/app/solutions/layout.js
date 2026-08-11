export const metadata = {
  title: 'Solutions IT — Munisys',
  description: 'Infrastructure, cybersécurité, cloud et services managés. Découvrez toutes les solutions IT de Munisys pour votre transformation digitale.',
  openGraph: {
    title: 'Solutions IT — Munisys',
    description: 'Infrastructure, cybersécurité, cloud et services managés par Munisys.',
    url: 'https://www.munisys.net/solutions',
    siteName: 'Munisys',
    images: [{ url: 'https://www.munisys.net/og-image.jpg', width: 1200, height: 630 }],
    type: 'website',
  },
  alternates: {
    canonical: 'https://www.munisys.net/solutions',
  },
};

export default function SolutionsLayout({ children }) {
  return children;
}
