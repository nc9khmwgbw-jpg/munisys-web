import fs from 'fs/promises';
import path from 'path';
import { NextResponse } from 'next/server';
import DOMPurify from 'isomorphic-dompurify';

export const dynamic = 'force-dynamic';

const newsletterPath = path.join(process.cwd(), 'src/data/newsletter.json');

export async function POST(request) {
  try {
    const rawData = await request.json();

    if (!rawData.email || !rawData.email.includes('@')) {
      return NextResponse.json({ error: 'Adresse email invalide.' }, { status: 400 });
    }

    const email = DOMPurify.sanitize(rawData.email.toLowerCase().trim());

    const dataDir = path.join(process.cwd(), 'src/data');
    await fs.mkdir(dataDir, { recursive: true });

    let subscribers = [];
    try {
      const fileContents = await fs.readFile(newsletterPath, 'utf8');
      subscribers = JSON.parse(fileContents);
      if (!Array.isArray(subscribers)) {
        subscribers = [];
      }
    } catch (e) {
      // File doesn't exist yet
    }

    // Check if already subscribed
    if (subscribers.some(sub => sub.email === email)) {
      return NextResponse.json({ success: true, message: 'Vous êtes déjà inscrit !' });
    }

    const newSubscriber = {
      id: Date.now().toString(),
      date: new Date().toISOString(),
      email: email,
    };

    subscribers.unshift(newSubscriber);
    await fs.writeFile(newsletterPath, JSON.stringify(subscribers, null, 2), 'utf8');

    return NextResponse.json({ success: true, message: 'Inscription réussie !' });
  } catch (error) {
    console.error('Erreur inscription newsletter:', error);
    return NextResponse.json({ error: 'Erreur serveur.' }, { status: 500 });
  }
}
