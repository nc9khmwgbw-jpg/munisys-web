export const metadata = {
  title: 'À Propos — Munisys',
  description: 'Découvrez l\'histoire de Munisys, nos valeurs, notre équipe et notre engagement envers l\'excellence en infrastructure IT et cybersécurité.',
  openGraph: {
    title: 'À Propos — Munisys',
    description: 'Découvrez l\'histoire, les valeurs et l\'équipe de Munisys.',
    url: 'https://www.munisys.net/about-us',
    siteName: 'Munisys',
    images: [{ url: 'https://www.munisys.net/og-image.jpg', width: 1200, height: 630 }],
    type: 'website',
  },
  alternates: {
    canonical: 'https://www.munisys.net/about-us',
  },
};

export default function AboutUsLayout({ children }) {
  return children;
}
