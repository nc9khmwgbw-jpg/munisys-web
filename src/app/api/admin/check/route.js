import { NextResponse } from 'next/server';
import { jwtVerify } from 'jose';

export const dynamic = 'force-dynamic';

const getJwtSecretKey = () => {
  const secret = process.env.JWT_SECRET || 'fallback_secret_key_change_in_production_!';
  return new TextEncoder().encode(secret);
};

export async function GET(request) {
  const token = request.cookies.get('munisys_auth_token')?.value;

  if (!token) {
    return NextResponse.json({ authenticated: false }, { status: 401 });
  }

  try {
    const verified = await jwtVerify(token, getJwtSecretKey());
    return NextResponse.json({ authenticated: true, user: verified.payload.username });
  } catch (err) {
    return NextResponse.json({ authenticated: false }, { status: 401 });
  }
}
