import fs from 'fs/promises';
import path from 'path';

export default async function sitemap() {
  const baseUrl = 'https://www.munisys.net';

  // Define static routes
  const routes = [
    '',
    '/about-us',
    '/solutions',
    '/services',
    '/contact',
  ].map((route) => ({
    url: `${baseUrl}${route}`,
    lastModified: new Date(),
    changeFrequency: route === '' ? 'weekly' : 'monthly',
    priority: route === '' ? 1 : 0.8,
  }));

  try {
    // Attempt to read db.json for dynamic routes (like specific solutions)
    const dbPath = path.join(process.cwd(), 'src/data/db.json');
    const fileContents = await fs.readFile(dbPath, 'utf8');
    const dbData = JSON.parse(fileContents);

    if (dbData && dbData.solutions) {
      const dynamicRoutes = Object.keys(dbData.solutions).map((slug) => ({
        url: `${baseUrl}/solutions/${slug}`,
        lastModified: new Date(),
        changeFrequency: 'monthly',
        priority: 0.6,
      }));
      routes.push(...dynamicRoutes);
    }
  } catch (error) {
    console.error("Erreur lors de la génération du sitemap dynamique:", error);
  }

  return routes;
}
