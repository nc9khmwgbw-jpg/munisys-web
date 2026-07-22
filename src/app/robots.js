export default function robots() {
  const baseUrl = 'https://www.munisys.net';

  return {
    rules: {
      userAgent: '*',
      allow: '/',
      disallow: ['/admin/', '/api/'], // On interdit le scan de la zone admin et API
    },
    sitemap: `${baseUrl}/sitemap.xml`,
  };
}
