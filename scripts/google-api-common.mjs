import 'dotenv/config';
import { createSign } from 'crypto';
import { readFile } from 'fs/promises';

const GOOGLE_TOKEN_URL = 'https://oauth2.googleapis.com/token';

export function parseArgs(argv) {
  const args = {};
  for (let i = 0; i < argv.length; i += 1) {
    const part = argv[i];
    if (!part.startsWith('--')) {
      continue;
    }

    const keyValue = part.slice(2).split('=');
    const key = keyValue[0];

    if (keyValue.length > 1) {
      args[key] = keyValue.slice(1).join('=');
      continue;
    }

    const next = argv[i + 1];
    if (!next || next.startsWith('--')) {
      args[key] = true;
      continue;
    }

    args[key] = next;
    i += 1;
  }
  return args;
}

export function splitCsv(value, fallback = []) {
  if (!value) {
    return fallback;
  }
  return String(value)
    .split(',')
    .map((item) => item.trim())
    .filter(Boolean);
}

export async function loadServiceAccount(explicitPath) {
  const keyPath = explicitPath || process.env.GOOGLE_SERVICE_ACCOUNT_KEY_PATH;
  if (!keyPath) {
    throw new Error('Missing GOOGLE_SERVICE_ACCOUNT_KEY_PATH or --key-file');
  }

  const raw = await readFile(keyPath, 'utf8');
  const parsed = JSON.parse(raw);

  if (!parsed.client_email || !parsed.private_key) {
    throw new Error(`Invalid service account JSON: ${keyPath}`);
  }

  return {
    keyPath,
    clientEmail: parsed.client_email,
    privateKey: parsed.private_key,
    projectId: parsed.project_id,
  };
}

function base64UrlEncode(input) {
  return Buffer.from(input)
    .toString('base64')
    .replace(/\+/g, '-')
    .replace(/\//g, '_')
    .replace(/=+$/g, '');
}

function signJwt(header, payload, privateKey) {
  const encodedHeader = base64UrlEncode(JSON.stringify(header));
  const encodedPayload = base64UrlEncode(JSON.stringify(payload));
  const signer = createSign('RSA-SHA256');
  signer.update(`${encodedHeader}.${encodedPayload}`);
  signer.end();
  const signature = signer
    .sign(privateKey, 'base64')
    .replace(/\+/g, '-')
    .replace(/\//g, '_')
    .replace(/=+$/g, '');

  return `${encodedHeader}.${encodedPayload}.${signature}`;
}

export async function getAccessToken({ scopes, keyFile }) {
  const account = await loadServiceAccount(keyFile);
  const now = Math.floor(Date.now() / 1000);
  const assertion = signJwt(
    { alg: 'RS256', typ: 'JWT' },
    {
      iss: account.clientEmail,
      scope: scopes.join(' '),
      aud: GOOGLE_TOKEN_URL,
      exp: now + 3600,
      iat: now,
    },
    account.privateKey,
  );

  const response = await fetch(GOOGLE_TOKEN_URL, {
    method: 'POST',
    headers: { 'content-type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({
      grant_type: 'urn:ietf:params:oauth:grant-type:jwt-bearer',
      assertion,
    }),
  });

  if (!response.ok) {
    const detail = await response.text();
    throw new Error(`Token request failed (${response.status}): ${detail}`);
  }

  const payload = await response.json();
  return {
    accessToken: payload.access_token,
    expiresIn: payload.expires_in,
    account,
  };
}

export async function fetchJson(url, { method = 'GET', token, body } = {}) {
  const response = await fetch(url, {
    method,
    headers: {
      authorization: `Bearer ${token}`,
      ...(body ? { 'content-type': 'application/json' } : {}),
    },
    body: body ? JSON.stringify(body) : undefined,
  });

  if (!response.ok) {
    const detail = await response.text();
    throw new Error(`Request failed (${response.status}) ${url}: ${detail}`);
  }

  return response.json();
}

function pad(text, width) {
  return String(text ?? '').padEnd(width, ' ');
}

export function printTable(rows) {
  if (!rows.length) {
    console.log('(no rows)');
    return;
  }

  const headers = Object.keys(rows[0]);
  const widths = headers.map((header) =>
    Math.max(header.length, ...rows.map((row) => String(row[header] ?? '').length)),
  );

  console.log(headers.map((header, index) => pad(header, widths[index])).join(' | '));
  console.log(widths.map((width) => '-'.repeat(width)).join('-|-'));
  for (const row of rows) {
    console.log(headers.map((header, index) => pad(row[header], widths[index])).join(' | '));
  }
}
