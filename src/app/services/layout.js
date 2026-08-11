export const metadata = {
  title: 'Services Managés & Cloud — Munisys',
  description: 'Services managés, intégration cloud, support et maintenance. Munisys accompagne votre entreprise à chaque étape de sa transformation digitale.',
  openGraph: {
    title: 'Services Managés & Cloud — Munisys',
    description: 'Services managés, cloud et support technique par Munisys.',
    url: 'https://www.munisys.net/services',
    siteName: 'Munisys',
    images: [{ url: 'https://www.munisys.net/og-image.jpg', width: 1200, height: 630 }],
    type: 'website',
  },
  alternates: {
    canonical: 'https://www.munisys.net/services',
  },
};

export default function ServicesLayout({ children }) {
  return children;
}
