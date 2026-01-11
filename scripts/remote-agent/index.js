import 'dotenv/config';
import path from 'node:path';
import fs from 'node:fs/promises';
import { existsSync } from 'node:fs';
import SftpClient from 'ssh2-sftp-client';
import * as BasicFtp from 'basic-ftp';

function parseArgs(argv) {
  const args = { _: [] };
  for (let i = 2; i < argv.length; i++) {
    const a = argv[i];
    if (a.startsWith('--')) {
      const [k, v] = a.slice(2).split('=');
      // boolean flags default to true when no value is provided
      args[k] = v === undefined ? true : v;
    } else {
      args._.push(a);
    }
  }
  return args;
}

function posixJoin(...parts) {
  return parts.join('/').replace(/\\/g, '/').replace(/\/\/+/, '/');
}

function getAllowedBases() {
  const bases = [];
  const multi = process.env.REMOTE_BASES;
  const single = process.env.REMOTE_BASE;
  if (multi) {
    multi.split(',').map((s) => s.trim()).filter(Boolean).forEach((b) => bases.push(b));
  }
  if (single) bases.push(single);
  if (bases.length === 0) {
    throw new Error('Missing env REMOTE_BASES or REMOTE_BASE (comma-separated allowed prefixes)');
  }
  return bases.map((b) => (b.endsWith('/') ? b : b + '/'));
}

function ensureAllowed(allowedBases, target) {
  const normTarget = target.replace(/\\/g, '/');
  const ok = allowedBases.some((base) => normTarget.startsWith(base));
  if (!ok) {
    throw new Error(`Refused: path outside whitelist. allowed=[${allowedBases.join(', ')}] target=${normTarget}`);
  }
}

async function ensureDir(dir) {
  if (!existsSync(dir)) {
    await fs.mkdir(dir, { recursive: true });
  }
}

async function sftpConnect() {
  const {
    SFTP_HOST: host,
    SFTP_PORT: port = '22',
    SFTP_USER: username,
    SFTP_PASS: password,
  } = process.env;
  if (!host || !username || !password) {
    throw new Error('Missing SFTP env: SFTP_HOST, SFTP_USER, SFTP_PASS');
  }
  const client = new SftpClient();
  await client.connect({ host, port: Number(port), username, password });
  return client;
}

async function ftpsConnect() {
  const {
    FTPS_HOST: host,
    FTPS_PORT = '21',
    FTPS_USER: user,
    FTPS_PASS: password,
  } = process.env;
  if (!host || !user || !password) {
    throw new Error('Missing FTPS env: FTPS_HOST, FTPS_USER, FTPS_PASS');
  }
  const client = new BasicFtp.Client();
  await client.access({ host, port: Number(FTPS_PORT), user, password, secure: true });
  return client;
}

function usingFTPS() {
  return String(process.env.REMOTE_PROTOCOL || '').toUpperCase() === 'FTPS';
}

async function sftpReadFile(client, remotePath, localPath) {
  await client.fastGet(remotePath, localPath);
  return fs.readFile(localPath, 'utf8');
}

async function sftpWriteFile(client, remotePath, content) {
  const tmp = path.join(process.cwd(), 'tmp_remote_agent', `.upload-${Date.now()}.tmp`);
  await ensureDir(path.dirname(tmp));
  await fs.writeFile(tmp, content, 'utf8');
  await client.fastPut(tmp, remotePath);
  await fs.unlink(tmp).catch(() => {});
}

async function sftpBackup(client, remotePath, backupPath) {
  // create a remote-side copy by downloading then uploading to backup path
  const tmp = path.join(process.cwd(), 'tmp_remote_agent', `.backup-${Date.now()}.tmp`);
  await ensureDir(path.dirname(tmp));
  await client.fastGet(remotePath, tmp);
  await client.fastPut(tmp, backupPath);
  // keep a local backup alongside
  const localCopy = path.join(process.cwd(), 'tmp_remote_agent', path.basename(backupPath));
  await fs.copyFile(tmp, localCopy).catch(() => {});
  await fs.unlink(tmp).catch(() => {});
}

async function ftpsReadFile(client, remotePath, localPath) {
  await ensureDir(path.dirname(localPath));
  await client.downloadTo(localPath, remotePath);
  return fs.readFile(localPath, 'utf8');
}

async function ftpsWriteFile(client, remotePath, content) {
  const tmp = path.join(process.cwd(), 'tmp_remote_agent', `.upload-${Date.now()}.tmp`);
  await ensureDir(path.dirname(tmp));
  await fs.writeFile(tmp, content, 'utf8');
  await client.uploadFrom(tmp, remotePath);
  await fs.unlink(tmp).catch(() => {});
}

async function ftpsBackup(client, remotePath, backupPath) {
  const tmp = path.join(process.cwd(), 'tmp_remote_agent', `.backup-${Date.now()}.tmp`);
  await ensureDir(path.dirname(tmp));
  await client.downloadTo(tmp, remotePath);
  await client.uploadFrom(tmp, backupPath);
  const localCopy = path.join(process.cwd(), 'tmp_remote_agent', path.basename(backupPath));
  await fs.copyFile(tmp, localCopy).catch(() => {});
  await fs.unlink(tmp).catch(() => {});
}

function buildRegex(find, useRegex, flags = 'g') {
  if (useRegex) return new RegExp(find, flags);
  // escape for literal search
  const esc = find.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  return new RegExp(esc, flags);
}

async function cmdReplace(args) {
  const allowedBases = getAllowedBases();

  const remoteFile = args.file || args.f;
  const find = args.from;
  const to = args.to ?? '';
  const dryRun = Boolean(args['dry-run'] || args.dryrun || args.d);
  const useRegex = Boolean(args.regex || args.r);
  if (!remoteFile || !find) {
    throw new Error('Usage: replace --file=<remote> --from=<text|pattern> [--to=<text>] [--regex] [--dry-run]');
  }

  ensureAllowed(allowedBases, remoteFile);
  const isFTPS = usingFTPS();
  const client = isFTPS ? await ftpsConnect() : await sftpConnect();
  try {
    const tmpDir = path.join(process.cwd(), 'tmp_remote_agent');
    await ensureDir(tmpDir);

    const localTmp = path.join(tmpDir, path.basename(remoteFile));
    const original = isFTPS
      ? await ftpsReadFile(client, remoteFile, localTmp)
      : await sftpReadFile(client, remoteFile, localTmp);

    const re = buildRegex(find, useRegex);
    const replaced = original.replace(re, to);

    if (replaced === original) {
      console.log('No changes (pattern not found).');
      return;
    }

    console.log(`[Preview] First 200 chars after replace:\n${replaced.slice(0, 200)}`);

    if (dryRun) {
      console.log('Dry-run: no backup or upload performed.');
      return;
    }

    const backupPath = `${remoteFile}.bak.${Date.now()}`;
    if (isFTPS) {
      await ftpsBackup(client, remoteFile, backupPath);
      await ftpsWriteFile(client, remoteFile, replaced);
    } else {
      await sftpBackup(client, remoteFile, backupPath);
      await sftpWriteFile(client, remoteFile, replaced);
    }
    console.log(`Updated ${remoteFile}\nBackup: ${backupPath}`);
  } finally {
    if (isFTPS) {
      client.close();
    } else {
      await client.end().catch(() => {});
    }
  }
}

async function cmdPull(args) {
  const allowedBases = getAllowedBases();
  const remoteFile = args.file || args.f;
  const out = args.out || args.o;
  if (!remoteFile || !out) throw new Error('Usage: pull --file=<remote> --out=<localPath>');
  ensureAllowed(allowedBases, remoteFile);
  const isFTPS = usingFTPS();
  const client = isFTPS ? await ftpsConnect() : await sftpConnect();
  try {
    await ensureDir(path.dirname(out));
    if (isFTPS) {
      await client.downloadTo(out, remoteFile);
    } else {
      await client.fastGet(remoteFile, out);
    }
    console.log(`Pulled -> ${out}`);
  } finally {
    if (isFTPS) {
      client.close();
    } else {
      await client.end().catch(() => {});
    }
  }
}

async function cmdLs(args) {
  const allowedBases = getAllowedBases();
  const dir = args.dir || args.d;
  if (!dir) throw new Error('Usage: ls --dir=<remoteDir>');
  ensureAllowed(allowedBases, dir.endsWith('/') ? dir : dir + '/');

  const isFTPS = usingFTPS();
  const client = isFTPS ? await ftpsConnect() : await sftpConnect();
  try {
    let entries;
    entries = await client.list(dir);
    const names = entries.map((e) => e.name || e.filename).filter(Boolean);
    console.log(names.join('\n'));
  } finally {
    if (isFTPS) client.close(); else await client.end().catch(() => {});
  }
}

async function cmdCheck() {
  const bases = getAllowedBases();
  const isFTPS = usingFTPS();
  const client = isFTPS ? await ftpsConnect() : await sftpConnect();
  try {
    for (const base of bases) {
      try {
        const list = await client.list(base.replace(/\/$/, ''));
        console.log(`OK ${base} (${list.length} entries)`);
      } catch (e) {
        console.log(`NG ${base}: ${e.message || e}`);
      }
    }
  } finally {
    if (isFTPS) client.close(); else await client.end().catch(() => {});
  }
}

async function main() {
  const args = parseArgs(process.argv);
  const [cmd] = args._;
  if (!cmd || ['-h', '--help'].includes(cmd) || args.help) {
    console.log(`Remote Template Agent (MVP)

Usage:
  node scripts/remote-agent/index.js replace --file=<remote> --from=<text|pattern> [--to=<text>] [--regex] [--dry-run]
  node scripts/remote-agent/index.js pull --file=<remote> --out=<localPath>
  node scripts/remote-agent/index.js ls --dir=<remoteDir>
  node scripts/remote-agent/index.js check

Env (required):
  Choose protocol via REMOTE_PROTOCOL=SFTP|FTPS (default SFTP)
  For SFTP: SFTP_HOST, SFTP_USER, SFTP_PASS, [SFTP_PORT=22]
  For FTPS: FTPS_HOST, FTPS_USER, FTPS_PASS, [FTPS_PORT=21]
  REMOTE_BASES or REMOTE_BASE (comma-separated allowed prefixes)
    e.g. REMOTE_BASES="zidooka/wp-content/themes/picostrap/,zidooka/wp-content/themes/picostrap-child/"
`);
    return;
  }

  if (cmd === 'replace') return cmdReplace(args);
  if (cmd === 'pull') return cmdPull(args);
  if (cmd === 'ls') return cmdLs(args);
  if (cmd === 'check') return cmdCheck(args);

  throw new Error(`Unknown command: ${cmd}`);
}

main().catch((err) => {
  console.error('Error:', err.message || err);
  process.exit(1);
});
