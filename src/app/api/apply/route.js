import fs from 'fs/promises';
import path from 'path';
import { NextResponse } from 'next/server';
import DOMPurify from 'isomorphic-dompurify';

export const dynamic = 'force-dynamic';

const applicationsPath = path.join(process.cwd(), 'src/data/applications.json');

// In-memory rate limiter — same pattern as leads API
const rateLimitMap = new Map();
const RATE_LIMIT = 3;
const RATE_WINDOW_MS = 15 * 60 * 1000; // 3 per 15 minutes

function checkRateLimit(ip) {
  const now = Date.now();
  const entry = rateLimitMap.get(ip);
  if (!entry || now > entry.resetAt) {
    rateLimitMap.set(ip, { count: 1, resetAt: now + RATE_WINDOW_MS });
    return true;
  }
  if (entry.count >= RATE_LIMIT) return false;
  entry.count += 1;
  return true;
}

const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

export async function POST(request) {
  try {
    const forwarded = request.headers.get('x-forwarded-for');
    const ip = forwarded ? forwarded.split(',')[0].trim() : 'unknown';

    if (!checkRateLimit(ip)) {
      return NextResponse.json(
        { error: 'Trop de candidatures. Veuillez patienter avant de réessayer.' },
        { status: 429 }
      );
    }

    const rawData = await request.json();

    // Validation
    if (!rawData.name || !rawData.email || !rawData.position || !rawData.motivation) {
      return NextResponse.json({ error: 'Tous les champs obligatoires doivent être remplis.' }, { status: 400 });
    }
    if (typeof rawData.email !== 'string' || !EMAIL_REGEX.test(rawData.email)) {
      return NextResponse.json({ error: 'Adresse email invalide.' }, { status: 400 });
    }
    if (rawData.name.length > 100) {
      return NextResponse.json({ error: 'Le nom est trop long.' }, { status: 400 });
    }
    if (rawData.motivation.length < 20) {
      return NextResponse.json({ error: 'La lettre de motivation est trop courte (minimum 20 caractères).' }, { status: 400 });
    }
    if (rawData.motivation.length > 5000) {
      return NextResponse.json({ error: 'La lettre de motivation est trop longue (maximum 5000 caractères).' }, { status: 400 });
    }

    const newApplication = {
      id: Date.now().toString(),
      date: new Date().toISOString(),
      status: 'new', // new | reviewed | interview | rejected
      position: DOMPurify.sanitize(String(rawData.position).trim()),
      name: DOMPurify.sanitize(String(rawData.name).trim()),
      email: DOMPurify.sanitize(String(rawData.email).trim().toLowerCase()),
      phone: DOMPurify.sanitize(String(rawData.phone || '').trim()),
      linkedin: DOMPurify.sanitize(String(rawData.linkedin || '').trim()),
      motivation: DOMPurify.sanitize(String(rawData.motivation).trim()),
    };

    // Save to applications.json
    await fs.mkdir(path.join(process.cwd(), 'src/data'), { recursive: true });
    let current = [];
    try {
      const file = await fs.readFile(applicationsPath, 'utf8');
      current = JSON.parse(file);
      if (!Array.isArray(current)) current = [];
    } catch (e) { /* first run */ }

    current.unshift(newApplication);
    await fs.writeFile(applicationsPath, JSON.stringify(current, null, 2), 'utf8');

    return NextResponse.json({ success: true });
  } catch (err) {
    console.error('[apply API error]', err);
    return NextResponse.json({ error: 'Erreur serveur.' }, { status: 500 });
  }
}
