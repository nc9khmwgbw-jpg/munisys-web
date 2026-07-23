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
    '/insights',
    '/contact',
  ].map((route) => ({
    url: `${baseUrl}${route}`,
    lastModified: new Date(),
    changeFrequency: route === '' ? 'weekly' : 'monthly',
    priority: route === '' ? 1 : 0.8,
  }));

  try {
    // Attempt to read db.json for dynamic routes
    const dbPath = path.join(process.cwd(), 'src/data/db.json');
    const fileContents = await fs.readFile(dbPath, 'utf8');
    const dbData = JSON.parse(fileContents);

    // Add /solutions/[slug] routes
    const dbLang = dbData.en || dbData;
    if (dbLang && dbLang.solutions) {
      const solutionRoutes = Object.keys(dbLang.solutions).map((slug) => ({
        url: `${baseUrl}/solutions/${slug}`,
        lastModified: new Date(),
        changeFrequency: 'monthly',
        priority: 0.6,
      }));
      routes.push(...solutionRoutes);
    }

    // Add /insights/[slug] article routes
    const insightsPosts = dbLang?.insights?.posts || dbData?.insights?.posts || [];
    const articleRoutes = insightsPosts
      .filter(post => post.slug)
      .map(post => ({
        url: `${baseUrl}/insights/${post.slug}`,
        lastModified: new Date(),
        changeFrequency: 'monthly',
        priority: 0.7,
      }));
    routes.push(...articleRoutes);

  } catch (error) {
    console.error("Erreur lors de la génération du sitemap dynamique:", error);
  }

  return routes;
}
