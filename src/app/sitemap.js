import fs from 'fs/promises';
import path from 'path';

export default async function sitemap() {
  const baseUrl = 'https://www.munisys.net';

  // All static routes with proper priority
  const staticRoutes = [
    { path: '',                      changeFreq: 'weekly',  priority: 1.0 },
    { path: '/solutions',            changeFreq: 'monthly', priority: 0.9 },
    { path: '/services',             changeFreq: 'monthly', priority: 0.9 },
    { path: '/insights',             changeFreq: 'weekly',  priority: 0.85 },
    { path: '/about-us',             changeFreq: 'monthly', priority: 0.8 },
    { path: '/contact',              changeFreq: 'monthly', priority: 0.8 },
    { path: '/careers',              changeFreq: 'weekly',  priority: 0.75 },
    { path: '/customer-references',  changeFreq: 'monthly', priority: 0.7 },
    { path: '/technology-partners',  changeFreq: 'monthly', priority: 0.7 },
    { path: '/mentions-legales',     changeFreq: 'yearly',  priority: 0.2 },
    { path: '/politique-confidentialite', changeFreq: 'yearly', priority: 0.2 },
  ].map(({ path: routePath, changeFreq, priority }) => ({
    url: `${baseUrl}${routePath}`,
    lastModified: new Date(),
    changeFrequency: changeFreq,
    priority,
  }));

  const dynamicRoutes = [];

  try {
    const dbPath = path.join(process.cwd(), 'src/data/db.json');
    const fileContents = await fs.readFile(dbPath, 'utf8');
    const dbData = JSON.parse(fileContents);
    const dbLang = dbData.en || dbData;

    // /solutions/[slug]
    if (dbLang?.solutions) {
      Object.keys(dbLang.solutions).forEach((slug) => {
        dynamicRoutes.push({
          url: `${baseUrl}/solutions/${slug}`,
          lastModified: new Date(),
          changeFrequency: 'monthly',
          priority: 0.75,
        });
      });
    }

    // /insights/[slug]
    const insightsPosts = dbLang?.insights?.posts || dbData?.insights?.posts || [];
    insightsPosts
      .filter(post => post.slug)
      .forEach(post => {
        dynamicRoutes.push({
          url: `${baseUrl}/insights/${post.slug}`,
          lastModified: new Date(post.date || new Date()),
          changeFrequency: 'monthly',
          priority: 0.7,
        });
      });

  } catch (error) {
    console.error('Erreur lors de la génération du sitemap dynamique:', error);
  }

  return [...staticRoutes, ...dynamicRoutes];
}
