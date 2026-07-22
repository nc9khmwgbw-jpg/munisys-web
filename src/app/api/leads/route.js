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

export async function POST(request) {
  try {
    const rawData = await request.json();

    // Basic Validation
    if (!rawData.name || !rawData.email || !rawData.message) {
      return NextResponse.json({ error: 'Veuillez remplir tous les champs obligatoires.' }, { status: 400 });
    }

    // Sanitize to prevent malicious HTML/JS in leads
    const newLead = {
      id: Date.now().toString(),
      date: new Date().toISOString(),
      name: DOMPurify.sanitize(rawData.name),
      email: DOMPurify.sanitize(rawData.email),
      company: DOMPurify.sanitize(rawData.company || ''),
      phone: DOMPurify.sanitize(rawData.phone || ''),
      message: DOMPurify.sanitize(rawData.message)
    };

    // Ensure directory exists (it should, but just in case)
    const dataDir = path.join(process.cwd(), 'src/data');
    await fs.mkdir(dataDir, { recursive: true });

    // Read existing leads
    let currentLeads = [];
    try {
      const fileContents = await fs.readFile(leadsPath, 'utf8');
      currentLeads = JSON.parse(fileContents);
      if (!Array.isArray(currentLeads)) {
        currentLeads = [];
      }
    } catch (e) {
      // File doesn't exist yet
    }

    // Add new lead at the beginning
    currentLeads.unshift(newLead);

    // Save to leads.json
    await fs.writeFile(leadsPath, JSON.stringify(currentLeads, null, 2), 'utf8');

    // Email Sending Logic via Resend
    if (resend) {
      const salesEmail = process.env.SALES_EMAIL || 'contact@munisys.net';
      
      const emailHtml = `
        <div style="font-family: Arial, sans-serif; max-width: 600px; padding: 20px; border: 1px solid #eaeaea; border-radius: 10px;">
          <h2 style="color: #1d4e79; border-bottom: 2px solid #38bdf8; padding-bottom: 10px;">Nouveau contact depuis le site web</h2>
          <p><strong>Nom :</strong> ${newLead.name}</p>
          <p><strong>Email :</strong> <a href="mailto:${newLead.email}">${newLead.email}</a></p>
          <p><strong>Téléphone :</strong> ${newLead.phone || 'Non renseigné'}</p>
          <p><strong>Société :</strong> ${newLead.company || 'Non renseignée'}</p>
          <div style="margin-top: 20px; background-color: #f8fafc; padding: 15px; border-left: 4px solid #38bdf8;">
            <strong>Message :</strong><br/>
            <p style="white-space: pre-wrap;">${newLead.message}</p>
          </div>
          <p style="font-size: 12px; color: #64748b; margin-top: 30px;">Ce message a été envoyé automatiquement depuis le formulaire de contact de Munisys.</p>
        </div>
      `;

      try {
        await resend.emails.send({
          from: 'Munisys Site Web <onboarding@resend.dev>', // Update this to a verified domain in production if you have one
          to: salesEmail,
          subject: `Nouveau message de ${newLead.name}`,
          html: emailHtml,
          reply_to: newLead.email,
        });
        console.log("Email envoyé avec succès via Resend.");
      } catch (emailError) {
        console.error("Erreur lors de l'envoi de l'email via Resend:", emailError);
        // We don't fail the request if the email fails, since it's saved locally
      }
    } else {
      console.log("RESEND_API_KEY non configurée. Le lead a été sauvegardé localement uniquement.");
    }

    return NextResponse.json({ success: true, message: 'Message envoyé avec succès' });
  } catch (error) {
    console.error('Erreur lors de l\'enregistrement du lead:', error);
    return NextResponse.json({ error: 'Erreur serveur. Veuillez réessayer plus tard.' }, { status: 500 });
  }
}
