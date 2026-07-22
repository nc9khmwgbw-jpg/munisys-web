import fs from 'fs/promises';
import path from 'path';
import { NextResponse } from 'next/server';
import DOMPurify from 'isomorphic-dompurify';
import { createBackup } from '../backups/route';
import defaultDb from '@/data/db.json';

export const dynamic = 'force-dynamic';

const dbPath = path.join(process.cwd(), 'src/data/db.json');

// Recursive function to sanitize all strings in an object/array
const sanitizeData = (data) => {
  if (typeof data === 'string') {
    return DOMPurify.sanitize(data, { 
      ALLOWED_TAGS: ['b', 'i', 'em', 'strong', 'a', 'p', 'br', 'ul', 'li', 'ol', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'span', 'div', 'blockquote', 'img'],
      ALLOWED_ATTR: ['href', 'target', 'class', 'style', 'src', 'alt']
    });
  }
  if (Array.isArray(data)) {
    return data.map(item => sanitizeData(item));
  }
  if (typeof data === 'object' && data !== null) {
    const sanitizedObj = {};
    for (const [key, value] of Object.entries(data)) {
      sanitizedObj[key] = sanitizeData(value);
    }
    return sanitizedObj;
  }
  return data;
};

export async function GET() {
  try {
    const fileContents = await fs.readFile(dbPath, 'utf8');
    const data = JSON.parse(fileContents);
    return NextResponse.json(data);
  } catch (error) {
    console.error('Error reading DB, falling back to bundled JSON:', error);
    return NextResponse.json(defaultDb);
  }
}

export async function POST(request) {
  try {
    const rawData = await request.json();
    
    // Validate we are receiving an object
    if (typeof rawData !== 'object' || rawData === null) {
      return NextResponse.json({ error: 'Invalid data format' }, { status: 400 });
    }

    // Sanitize data to prevent XSS
    const newData = sanitizeData(rawData);

    // Read current data to merge
    let currentData = {};
    try {
      const fileContents = await fs.readFile(dbPath, 'utf8');
      currentData = JSON.parse(fileContents);
      
      // AUTO-BACKUP: Before overwriting, create a backup
      await createBackup();
      
    } catch (e) {
      // Ignore if file doesn't exist yet
    }

    const mergedData = { ...currentData, ...newData };

    // Write back to file
    await fs.writeFile(dbPath, JSON.stringify(mergedData, null, 2), 'utf8');
    
    return NextResponse.json({ success: true, message: 'Database updated successfully' });
  } catch (error) {
    console.error('Error writing DB:', error);
    return NextResponse.json({ error: 'Failed to update database' }, { status: 500 });
  }
}
