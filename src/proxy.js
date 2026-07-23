import { NextResponse } from 'next/server';
import { jwtVerify } from 'jose';

const getJwtSecretKey = () => {
  const secret = process.env.JWT_SECRET || 'fallback_secret_key_change_in_production_!';
  return new TextEncoder().encode(secret);
};

export default async function proxy(request) {
  const { pathname } = request.nextUrl;

  // Protect these routes against modifications without auth
  const protectedPaths = ['/api/content', '/api/upload', '/api/translate'];
  const isProtectedPath = protectedPaths.some(p => pathname.startsWith(p));
  const isModifyingMethod = ['POST', 'PUT', 'DELETE'].includes(request.method);

  if (isProtectedPath && isModifyingMethod) {
    const token = request.cookies.get('munisys_auth_token')?.value;

    if (!token) {
      return NextResponse.json({ error: 'Unauthorized: No token provided' }, { status: 401 });
    }

    try {
      await jwtVerify(token, getJwtSecretKey());
      // Authentication successful
    } catch (error) {
      return NextResponse.json({ error: 'Unauthorized: Invalid token' }, { status: 401 });
    }
  }

  return NextResponse.next();
}

export const config = {
  matcher: ['/((?!api/upload-pdf)api/.*)'],
};
