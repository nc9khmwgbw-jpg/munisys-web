import fs from 'fs/promises';
import path from 'path';
import { NextResponse } from 'next/server';

export const dynamic = 'force-dynamic';

export async function POST(request) {
  try {
    const formData = await request.formData();
    const file = formData.get('file');

    if (!file) {
      return NextResponse.json({ error: 'No file uploaded' }, { status: 400 });
    }

    // Advanced Security: Size limit (5MB)
    const MAX_SIZE = 5 * 1024 * 1024;
    if (file.size > MAX_SIZE) {
      return NextResponse.json({ error: 'Fichier trop volumineux (Max 5Mo)' }, { status: 400 });
    }

    // Advanced Security: Strict MIME Type validation
    const allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'image/gif'];
    if (!allowedMimeTypes.includes(file.type)) {
      return NextResponse.json({ error: 'Type de fichier non autorisé. Seules les images sont acceptées.' }, { status: 400 });
    }

    // Read the file as a buffer
    const buffer = Buffer.from(await file.arrayBuffer());

    // Generate a unique WebP filename (always .webp regardless of original format to prevent executing scripts)
    const baseName = file.name.replace(/\.[^/.]+$/, '').replace(/[^a-zA-Z0-9._-]/g, '_');
    const uniqueName = `cms_${Date.now()}_${baseName}.webp`;

    // Ensure uploads directory exists inside public/
    const uploadsDir = path.join(process.cwd(), 'public', 'uploads');
    await fs.mkdir(uploadsDir, { recursive: true });

    const filePath = path.join(uploadsDir, uniqueName);

    // Try to use sharp for WebP optimization
    try {
      const sharp = (await import('sharp')).default;
      const optimizedBuffer = await sharp(buffer)
        .resize(1920, 1920, { fit: 'inside', withoutEnlargement: true }) // Max 1920px, never upscale
        .webp({ quality: 82, effort: 4 }) // Great quality/size balance
        .toBuffer();

      await fs.writeFile(filePath, optimizedBuffer);
      const originalKb = Math.round(buffer.length / 1024);
      const optimizedKb = Math.round(optimizedBuffer.length / 1024);
      console.log(`Image optimized: ${originalKb}KB → ${optimizedKb}KB (WebP)`);
    } catch (sharpErr) {
      // Fallback: save original if sharp fails
      console.warn('Sharp optimization failed, saving original:', sharpErr.message);
      await fs.writeFile(filePath, buffer);
    }

    // Return the public URL
    const publicUrl = `/uploads/${uniqueName}`;
    return NextResponse.json({ success: true, url: publicUrl });
  } catch (error) {
    console.error('Upload error:', error);
    return NextResponse.json({ error: 'Failed to upload file' }, { status: 500 });
  }
}
