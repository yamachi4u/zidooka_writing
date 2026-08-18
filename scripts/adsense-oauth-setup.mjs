import 'dotenv/config';
import { createServer } from 'http';
import { randomBytes, createHash } from 'crypto';

const GOOGLE_TOKEN_URL = 'https://oauth2.googleapis.com/token';
const GOOGLE_AUTH_URL = 'https://accounts.google.com/o/oauth2/v2/auth';
const SCOPES = ['https://www.googleapis.com/auth/adsense.readonly'];
const REDIRECT_PORT = 8080;
const REDIRECT_URI = `http://localhost:${REDIRECT_PORT}/callback`;

function usage() {
  console.log(`Usage:
  node scripts/adsense-oauth-setup.mjs

This script will:
1. Open a browser for Google OAuth consent
2. Start a local server on port ${REDIRECT_PORT} to receive the callback
3. Exchange the code for an access + refresh token
4. Print the refresh token to add to your .env

Prerequisites:
- OAuth 2.0 Client ID (Desktop app) created in Google Cloud Console
- Set GOOGLE_ADSENSE_CLIENT_ID and GOOGLE_ADSENSE_CLIENT_SECRET in .env
  or pass via --client-id and --client-secret`);
}

function base64UrlEncode(input) {
  return Buffer.from(input)
    .toString('base64url');
}

function generateCodeVerifier() {
  return base64UrlEncode(randomBytes(32));
}

function generateCodeChallenge(verifier) {
  const hash = createHash('sha256').update(verifier).digest();
  return base64UrlEncode(hash);
}

async function main() {
  const clientId = process.env.GOOGLE_ADSENSE_CLIENT_ID;
  const clientSecret = process.env.GOOGLE_ADSENSE_CLIENT_SECRET;

  if (!clientId || !clientSecret) {
    usage();
    console.error('\nError: Set GOOGLE_ADSENSE_CLIENT_ID and GOOGLE_ADSENSE_CLIENT_SECRET in .env');
    process.exitCode = 1;
    return;
  }

  const codeVerifier = generateCodeVerifier();
  const codeChallenge = generateCodeChallenge(codeVerifier);
  const state = randomBytes(16).toString('hex');

  const authUrl = new URL(GOOGLE_AUTH_URL);
  authUrl.searchParams.set('client_id', clientId);
  authUrl.searchParams.set('redirect_uri', REDIRECT_URI);
  authUrl.searchParams.set('response_type', 'code');
  authUrl.searchParams.set('scope', SCOPES.join(' '));
  authUrl.searchParams.set('state', state);
  authUrl.searchParams.set('code_challenge', codeChallenge);
  authUrl.searchParams.set('code_challenge_method', 'S256');
  authUrl.searchParams.set('access_type', 'offline');
  authUrl.searchParams.set('prompt', 'consent');

  console.log('Opening browser for Google OAuth consent...');
  const { execSync } = await import('child_process');
  execSync(`Start-Process "${authUrl}"`, { shell: 'powershell.exe' });

  const code = await new Promise((resolve, reject) => {
    const server = createServer((req, res) => {
      const url = new URL(req.url, `http://localhost:${REDIRECT_PORT}`);
      if (url.pathname !== '/callback') {
        res.writeHead(404);
        res.end();
        return;
      }

      const receivedState = url.searchParams.get('state');
      if (receivedState !== state) {
        res.writeHead(400);
        res.end('State mismatch');
        reject(new Error('State mismatch'));
        return;
      }

      const error = url.searchParams.get('error');
      if (error) {
        res.writeHead(400);
        res.end(`Error: ${error}`);
        reject(new Error(`OAuth error: ${error}`));
        return;
      }

      const authCode = url.searchParams.get('code');
      if (!authCode) {
        res.writeHead(400);
        res.end('No code received');
        reject(new Error('No authorization code received'));
        return;
      }

      res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
      res.end('<h1>認証完了</h1><p>このタブは閉じてOKです。</p>');
      resolve(authCode);
    });

    server.listen(REDIRECT_PORT, () => {
      console.log(`Listening on http://localhost:${REDIRECT_PORT}/callback ...`);
    });
  });

  const tokenResponse = await fetch(GOOGLE_TOKEN_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({
      code,
      client_id: clientId,
      client_secret: clientSecret,
      redirect_uri: REDIRECT_URI,
      grant_type: 'authorization_code',
      code_verifier: codeVerifier,
    }),
  });

  if (!tokenResponse.ok) {
    const detail = await tokenResponse.text();
    throw new Error(`Token exchange failed (${tokenResponse.status}): ${detail}`);
  }

  const tokens = await tokenResponse.json();

  console.log('\n========================================');
  console.log('Success! Add these to your .env file:');
  console.log('========================================');
  console.log(`GOOGLE_ADSENSE_REFRESH_TOKEN=${tokens.refresh_token}`);
  console.log('========================================');
  console.log('(Access token expires in', tokens.expires_in, 'seconds; refresh will happen automatically)');
}

main().catch((error) => {
  console.error(error.message);
  process.exitCode = 1;
});
