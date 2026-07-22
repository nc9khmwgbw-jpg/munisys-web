import { NextResponse } from 'next/server';

export const dynamic = 'force-dynamic';

export async function POST() {
  const response = NextResponse.json({ success: true, message: 'Déconnexion réussie' });
  
  response.cookies.delete('munisys_auth_token');
  
  return response;
}
