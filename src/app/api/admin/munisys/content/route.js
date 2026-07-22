import { NextResponse } from 'next/server';
import dbConnect from '@/lib/mongodb';
import MunisysContent from '@/models/MunisysContent';

export async function PUT(request) {
  try {
    // Dans un vrai projet, ajouter la vérification du token ici
    await dbConnect();
    
    const body = await request.json();
    const { key, value } = body;

    if (!key || value === undefined) {
      return NextResponse.json({ success: false, message: 'Clé et valeur requises.' }, { status: 400 });
    }

    const updatedContent = await MunisysContent.findOneAndUpdate(
      { key: key },
      { $set: { value: value, page: 'home', section: 'general' } }, 
      { new: true, upsert: true, runValidators: true }
    );

    return NextResponse.json({ success: true, message: 'Contenu mis à jour.', data: updatedContent });

  } catch (error) {
    console.error('Erreur updateContent:', error);
    return NextResponse.json({ success: false, message: 'Erreur serveur.' }, { status: 500 });
  }
}
