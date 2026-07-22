import { NextResponse } from 'next/server';
import { SignJWT } from 'jose';

export const dynamic = 'force-dynamic';

const ADMIN_CREDENTIALS = {
  username: process.env.ADMIN_USERNAME || 'admin',
  password: process.env.ADMIN_PASSWORD || 'munisys2024'
};

const getJwtSecretKey = () => {
  const secret = process.env.JWT_SECRET || 'fallback_secret_key_change_in_production_!';
  return new TextEncoder().encode(secret);
};

// Simple in-memory rate limiter (per instance)
const rateLimitMap = new Map();
const MAX_ATTEMPTS = 5;
const BLOCK_DURATION_MS = 15 * 60 * 1000; // 15 minutes

const checkRateLimit = (ip) => {
  const now = Date.now();
  const record = rateLimitMap.get(ip);
  if (!record) return { allowed: true };
  if (record.blockedUntil && now < record.blockedUntil) return { allowed: false, blockedUntil: record.blockedUntil };
  if (record.blockedUntil && now >= record.blockedUntil) {
    rateLimitMap.delete(ip);
    return { allowed: true };
  }
  return { allowed: true };
};

const recordFailedAttempt = (ip) => {
  const now = Date.now();
  const record = rateLimitMap.get(ip) || { attempts: 0 };
  record.attempts += 1;
  if (record.attempts >= MAX_ATTEMPTS) {
    record.blockedUntil = now + BLOCK_DURATION_MS;
  }
  rateLimitMap.set(ip, record);
};

const clearFailedAttempts = (ip) => {
  rateLimitMap.delete(ip);
};

export async function POST(request) {
  try {
    const ip = request.ip || request.headers.get('x-forwarded-for') || 'unknown';
    const rateStatus = checkRateLimit(ip);

    if (!rateStatus.allowed) {
      const waitMinutes = Math.ceil((rateStatus.blockedUntil - Date.now()) / 60000);
      return NextResponse.json(
        { success: false, message: `Trop de tentatives. Veuillez patienter ${waitMinutes} minute(s).` },
        { status: 429 }
      );
    }

    const { username, password } = await request.json();

    if (!username || !password) {
      return NextResponse.json(
        { success: false, message: 'Nom d\'utilisateur et mot de passe requis' },
        { status: 400 }
      );
    }

    const expectedUsername = process.env.ADMIN_USERNAME || 'admin';
    const expectedPassword = process.env.ADMIN_PASSWORD || 'munisys2024';

    console.log(`[LOGIN ATTEMPT] Received: ${username} | ${password}`);
    console.log(`[LOGIN ATTEMPT] Expected: ${expectedUsername} | ${expectedPassword}`);

    if (username === expectedUsername && password === expectedPassword) {
      clearFailedAttempts(ip);
      // Create JWT
      const token = await new SignJWT({ admin: true, username })
        .setProtectedHeader({ alg: 'HS256' })
        .setIssuedAt()
        .setExpirationTime('24h')
        .sign(getJwtSecretKey());

      const response = NextResponse.json({
        success: true,
        message: 'Authentification réussie'
      });

      // Set cookie
      response.cookies.set({
        name: 'munisys_auth_token',
        value: token,
        httpOnly: true,
        secure: process.env.NODE_ENV === 'production',
        sameSite: 'strict',
        maxAge: 60 * 60 * 24, // 24 hours
        path: '/',
      });

      return response;
    }

    recordFailedAttempt(ip);
    return NextResponse.json(
      { success: false, message: 'Identifiants incorrects' },
      { status: 401 }
    );
  } catch (error) {
    console.error('Erreur d\'authentification:', error);
    return NextResponse.json(
      { success: false, message: 'Erreur serveur' },
      { status: 500 }
    );
  }
}
