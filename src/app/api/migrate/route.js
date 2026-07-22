import { NextResponse } from 'next/server';
import dbConnect from '@/lib/mongodb';
import MunisysState from '@/models/MunisysState';
import defaultDb from '@/data/db.json';

export const dynamic = 'force-dynamic';

export async function GET() {
  try {
    await dbConnect();
    
    // Check if migration already happened
    const existing = await MunisysState.findOne();
    if (existing) {
      return NextResponse.json({ message: 'Migration already completed!', data: existing.data });
    }

    // Insert the local db.json
    const state = new MunisysState({ data: defaultDb });
    await state.save();

    return NextResponse.json({ success: true, message: 'Migration completed successfully!' });
  } catch (error) {
    console.error('Migration error:', error);
    return NextResponse.json({ error: error.message, stack: error.stack }, { status: 500 });
  }
}
