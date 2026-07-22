import { NextResponse } from 'next/server';
import DOMPurify from 'isomorphic-dompurify';
import dbConnect from '@/lib/mongodb';
import MunisysState from '@/models/MunisysState';
import { createBackup } from '../backups/route';
import defaultDb from '@/data/db.json';

export const dynamic = 'force-dynamic';

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
    await dbConnect();
    // Chercher le seul et unique document d'état
    const stateDoc = await MunisysState.findOne().lean();
    
    if (!stateDoc || !stateDoc.data) {
      // Si la DB est vide, on renvoie le JSON par défaut
      return NextResponse.json(defaultDb);
    }
    
    return NextResponse.json(stateDoc.data);
  } catch (error) {
    console.error('Error reading MongoDB, falling back to bundled JSON:', error);
    return NextResponse.json(defaultDb);
  }
}

export async function POST(request) {
  try {
    const rawData = await request.json();
    
    if (typeof rawData !== 'object' || rawData === null) {
      return NextResponse.json({ error: 'Invalid data format' }, { status: 400 });
    }

    const newData = sanitizeData(rawData);

    await dbConnect();
    
    let stateDoc = await MunisysState.findOne();
    
    let mergedData;
    if (stateDoc) {
      // Create backup before modifying
      await createBackup(); // Optional: might need refactoring if it relies on files
      mergedData = { ...stateDoc.data, ...newData };
      stateDoc.data = mergedData;
      // Tell mongoose that a Mixed type has changed
      stateDoc.markModified('data');
      await stateDoc.save();
    } else {
      // First time saving
      mergedData = { ...defaultDb, ...newData };
      stateDoc = new MunisysState({ data: mergedData });
      await stateDoc.save();
    }
    
    return NextResponse.json({ success: true, message: 'Database updated successfully' });
  } catch (error) {
    console.error('Error writing to MongoDB:', error);
    return NextResponse.json({ error: 'Failed to update database' }, { status: 500 });
  }
}
