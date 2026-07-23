import fs from 'fs/promises';
import path from 'path';
import { NextResponse } from 'next/server';
import PDFParser from 'pdf2json';
import { jwtVerify } from 'jose';

export const dynamic = 'force-dynamic';

const getJwtSecretKey = () => {
  const secret = process.env.JWT_SECRET || 'fallback_secret_key_change_in_production_!';
  return new TextEncoder().encode(secret);
};

function parsePdfBuffer(buffer) {
  return new Promise((resolve, reject) => {
    const pdfParser = new PDFParser(null, 1);
    
    pdfParser.on("pdfParser_dataError", errData => reject(errData.parserError));
    pdfParser.on("pdfParser_dataReady", () => {
      resolve(pdfParser.getRawTextContent());
    });
    
    pdfParser.parseBuffer(buffer);
  });
}

export async function POST(request) {
  try {
    const token = request.cookies.get('munisys_auth_token')?.value;
    if (!token) {
      return NextResponse.json({ error: 'Unauthorized: No token provided' }, { status: 401 });
    }
    try {
      await jwtVerify(token, getJwtSecretKey());
    } catch (error) {
      return NextResponse.json({ error: 'Unauthorized: Invalid token' }, { status: 401 });
    }

    const formData = await request.formData();
    const file = formData.get('file');

    if (!file) {
      return NextResponse.json({ error: 'Aucun fichier uploadé' }, { status: 400 });
    }

    // Size limit (15MB for PDF)
    const MAX_SIZE = 15 * 1024 * 1024;
    if (file.size > MAX_SIZE) {
      return NextResponse.json({ error: 'Fichier trop volumineux (Max 15Mo)' }, { status: 400 });
    }

    if (file.type !== 'application/pdf') {
      return NextResponse.json({ error: 'Seuls les fichiers PDF sont acceptés.' }, { status: 400 });
    }

    // Read the file as a buffer
    const buffer = Buffer.from(await file.arrayBuffer());

    // Parse the PDF text
    let parsedText = '';
    try {
      parsedText = await parsePdfBuffer(buffer);
    } catch (parseErr) {
      console.warn('Erreur lors du parsing PDF avec pdf2json:', parseErr);
    }

    // Clean up text format from pdf2json
    let cleanText = parsedText
      .replace(/\r\n/g, '\n')
      .replace(/-+Page\s*\(\d+\)\s*Break-+/gi, '')
      .replace(/----------------Page \(\d+\)[a-zA-Z0-9\s-]+/gi, '')
      .replace(/-+/g, ' ') // Remove multiple dashes
      .replace(/\s{2,}/g, ' ') // Remove extra spaces
      .trim();
      
    const lines = cleanText.split('\n').map(l => l.trim()).filter(l => l.length > 3);
    
    let extractedTitle = 'Communiqué de Presse';
    let extractedDesc = 'Consultez notre dernier communiqué de presse pour plus d\'informations.';

    if (lines.length > 0) {
      // Find the first line that looks like a real title (not just numbers or short text)
      const validTitleLine = lines.find(l => l.length > 10 && !/^\d+$/.test(l)) || lines[0];
      extractedTitle = validTitleLine.substring(0, 120);
      if (validTitleLine.length > 120) extractedTitle += '...';
    }

    if (lines.length > 1) {
      // Find subsequent lines for description
      const descLines = lines.filter(l => l !== extractedTitle && l.length > 20);
      if (descLines.length > 0) {
        extractedDesc = descLines.slice(0, 3).join(' ').substring(0, 250);
        if (descLines.join(' ').length > 250) extractedDesc += '...';
      }
    }

    // Generate a unique filename
    const baseName = file.name.replace(/\.[^/.]+$/, '').replace(/[^a-zA-Z0-9._-]/g, '_');
    const uniqueName = `pr_${Date.now()}_${baseName}.pdf`;

    // Ensure directory exists
    const uploadsDir = path.join(process.cwd(), 'public', 'uploads', 'pdf');
    await fs.mkdir(uploadsDir, { recursive: true });

    const filePath = path.join(uploadsDir, uniqueName);

    // Save the PDF
    await fs.writeFile(filePath, buffer);

    // Return the public URL and extracted data
    const publicUrl = `/uploads/pdf/${uniqueName}`;
    
    return NextResponse.json({ 
      success: true, 
      url: publicUrl,
      title: extractedTitle,
      desc: extractedDesc,
      category: 'ACTUALITÉS'
    });
  } catch (error) {
    console.error('PDF Upload error:', error);
    return NextResponse.json({ error: 'Échec de l\'upload du fichier', details: error.message, stack: error.stack }, { status: 500 });
  }
}
