import fs from 'fs/promises';
import path from 'path';
import { NextResponse } from 'next/server';
import PDFParser from 'pdf2json';

export const dynamic = 'force-dynamic';

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

    // Clean up text format from pdf2json (remove crazy spaces and line breaks)
    const cleanText = parsedText.replace(/\r\n/g, '\n').replace(/----------------Page \(0\)[a-zA-Z0-9\s-]+/g, '');
    const lines = cleanText.split('\n').map(l => l.trim()).filter(l => l.length > 3);
    
    let extractedTitle = 'Communiqué de Presse';
    let extractedDesc = 'Consultez notre dernier communiqué de presse pour plus d\'informations.';

    if (lines.length > 0) {
      extractedTitle = lines[0];
      if (extractedTitle.length > 120) {
        extractedTitle = extractedTitle.substring(0, 120) + '...';
      }
    }

    if (lines.length > 1) {
      extractedDesc = lines.slice(1, 4).join(' ');
      if (extractedDesc.length > 250) {
        extractedDesc = extractedDesc.substring(0, 250) + '...';
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
      category: 'PRESS RELEASE'
    });
  } catch (error) {
    console.error('PDF Upload error:', error);
    return NextResponse.json({ error: 'Échec de l\'upload du fichier' }, { status: 500 });
  }
}
