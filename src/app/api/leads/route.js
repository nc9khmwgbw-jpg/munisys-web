import fs from 'fs/promises';
import path from 'path';
import { NextResponse } from 'next/server';
import DOMPurify from 'isomorphic-dompurify';
import { Resend } from 'resend';

export const dynamic = 'force-dynamic';

const leadsPath = path.join(process.cwd(), 'src/data/leads.json');

// Initialize Resend if API key is provided
const resend = process.env.RESEND_API_KEY && process.env.RESEND_API_KEY !== 're_votre_cle_api_ici' 
  ? new Resend(process.env.RESEND_API_KEY) 
  : null;

// ── In-memory rate limiter (resets on server restart, good enough for most cases) ──
const rateLimitMap = new Map(); // ip -> { count, resetAt }
const RATE_LIMIT = 5;           // max requests
const RATE_WINDOW_MS = 10 * 60 * 1000; // per 10 minutes

function checkRateLimit(ip) {
  const now = Date.now();
  const entry = rateLimitMap.get(ip);

  if (!entry || now > entry.resetAt) {
    rateLimitMap.set(ip, { count: 1, resetAt: now + RATE_WINDOW_MS });
    return true; // allowed
  }

  if (entry.count >= RATE_LIMIT) {
    return false; // blocked
  }

  entry.count += 1;
  return true; // allowed
}

// ── Email validation regex ──
const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

export async function POST(request) {
  try {
    // 1. Rate limiting
    const forwarded = request.headers.get('x-forwarded-for');
    const ip = forwarded ? forwarded.split(',')[0].trim() : 'unknown';
    
    if (!checkRateLimit(ip)) {
      return NextResponse.json(
        { error: 'Trop de tentatives. Veuillez patienter quelques minutes avant de réessayer.' },
        { status: 429 }
      );
    }

    const rawData = await request.json();

    // 2. Presence validation
    if (!rawData.name || !rawData.email || !rawData.message) {
      return NextResponse.json(
        { error: 'Veuillez remplir tous les champs obligatoires.' },
        { status: 400 }
      );
    }

    // 3. Type validation
    if (typeof rawData.name !== 'string' || typeof rawData.email !== 'string' || typeof rawData.message !== 'string') {
      return NextResponse.json({ error: 'Format de données invalide.' }, { status: 400 });
    }

    // 4. Email format validation
    if (!EMAIL_REGEX.test(rawData.email)) {
      return NextResponse.json({ error: 'Adresse email invalide.' }, { status: 400 });
    }

    // 5. Length validation (prevents abuse with giant payloads)
    if (rawData.name.length > 100) {
      return NextResponse.json({ error: 'Le nom ne peut pas dépasser 100 caractères.' }, { status: 400 });
    }
    if (rawData.message.length < 10) {
      return NextResponse.json({ error: 'Le message est trop court (minimum 10 caractères).' }, { status: 400 });
    }
    if (rawData.message.length > 5000) {
      return NextResponse.json({ error: 'Le message ne peut pas dépasser 5000 caractères.' }, { status: 400 });
    }
    if (rawData.company && rawData.company.length > 200) {
      return NextResponse.json({ error: 'Le nom d\'entreprise est trop long.' }, { status: 400 });
    }

    // 6. Sanitize to prevent malicious HTML/JS in leads
    const newLead = {
      id: Date.now().toString(),
      date: new Date().toISOString(),
      ip: ip, // Store IP for audit trail
      name: DOMPurify.sanitize(String(rawData.name).trim()),
      email: DOMPurify.sanitize(String(rawData.email).trim().toLowerCase()),
      company: DOMPurify.sanitize(String(rawData.company || '').trim()),
      phone: DOMPurify.sanitize(String(rawData.phone || '').trim()),
      message: DOMPurify.sanitize(String(rawData.message).trim()),
    };

    // 7. Ensure directory exists
    const dataDir = path.join(process.cwd(), 'src/data');
    await fs.mkdir(dataDir, { recursive: true });

    // 8. Read existing leads
    let currentLeads = [];
    try {
      const fileContents = await fs.readFile(leadsPath, 'utf8');
      currentLeads = JSON.parse(fileContents);
      if (!Array.isArray(currentLeads)) currentLeads = [];
    } catch (e) {
      // File doesn't exist yet — that's fine
    }

    // Add new lead at the beginning
    currentLeads.unshift(newLead);
    await fs.writeFile(leadsPath, JSON.stringify(currentLeads, null, 2), 'utf8');

    // 9. Email Sending Logic via Resend
    if (resend) {
      const salesEmail = process.env.SALES_EMAIL || 'contact@munisys.net';
      
      const emailHtml = `
        <div style="font-family: Arial, sans-serif; max-width: 600px; padding: 20px; border: 1px solid #eaeaea; border-radius: 10px;">
          <h2 style="color: #1d4e79; border-bottom: 2px solid #922b55; padding-bottom: 10px;">Nouveau contact depuis le site web</h2>
          <p><strong>Nom :</strong> ${newLead.name}</p>
          <p><strong>Email :</strong> <a href="mailto:${newLead.email}">${newLead.email}</a></p>
          <p><strong>Téléphone :</strong> ${newLead.phone || 'Non renseigné'}</p>
          <p><strong>Société :</strong> ${newLead.company || 'Non renseignée'}</p>
          <div style="margin-top: 20px; background-color: #f8fafc; padding: 15px; border-left: 4px solid #922b55;">
            <strong>Message :</strong><br/>
            <p style="white-space: pre-wrap;">${newLead.message}</p>
          </div>
          <p style="font-size: 12px; color: #64748b; margin-top: 30px;">Ce message a été envoyé automatiquement depuis le formulaire de contact de Munisys.</p>
        </div>
      `;

      try {
        await resend.emails.send({
          from: 'Munisys Site Web <onboarding@resend.dev>',
          to: salesEmail,
          subject: `Nouveau message de ${newLead.name}`,
          html: emailHtml,
          reply_to: newLead.email,
        });
      } catch (emailError) {
        console.error("Erreur lors de l'envoi de l'email via Resend:", emailError);
        // Don't fail the request if email fails — it's already saved locally
      }
    }

    return NextResponse.json({ success: true, message: 'Message envoyé avec succès' });

  } catch (error) {
    console.error('Erreur lors de l\'enregistrement du lead:', error);
    return NextResponse.json(
      { error: 'Erreur serveur. Veuillez réessayer plus tard.' },
      { status: 500 }
    );
  }
}
