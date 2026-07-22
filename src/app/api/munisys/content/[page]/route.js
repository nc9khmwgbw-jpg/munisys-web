import { NextResponse } from 'next/server';
import dbConnect from '@/lib/mongodb';
import MunisysContent from '@/models/MunisysContent';

export async function GET(request, { params }) {
  try {
    await dbConnect();
    
    const { page } = await params;

    const contents = await MunisysContent.find({ page: page.toLowerCase() }).lean();

    if (!contents || contents.length === 0) {
      return NextResponse.json({ success: false, message: `Aucun contenu trouvé pour la page : ${page}` }, { status: 404 });
    }

    const formattedContent = contents.reduce((acc, item) => {
      acc[item.key] = item.value;
      return acc;
    }, {});

    return NextResponse.json({ success: true, data: formattedContent });

  } catch (error) {
    console.error('Erreur getPageContent:', error);
    return NextResponse.json({ success: false, message: 'Erreur serveur.' }, { status: 500 });
  }
}
