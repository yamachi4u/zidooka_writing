Remote Template Agent (MVP)

Minimal, safe SFTP utility to edit WordPress theme templates on a remote host (e.g., Lolipop). It enforces a whitelist base path, creates remote backups, and supports dry-run for text replacements.

Requirements
- Node.js 18+
- Env vars in `.env` (see `.env.example`)
- Dependencies: `ssh2-sftp-client` (SFTP), `basic-ftp` (FTPS)

Install
- Run: `npm install`

Environment
- `REMOTE_PROTOCOL` = `SFTP` | `FTPS` | `WEBDAV` (default `SFTP`)
- If SFTP: `SFTP_HOST`, `SFTP_USER`, `SFTP_PASS` (optional `SFTP_PORT=22`)
- If FTPS: `FTPS_HOST`, `FTPS_USER`, `FTPS_PASS` (optional `FTPS_PORT=21`)
- If WEBDAV: `WEBDAV_URL`, `WEBDAV_USER`, `WEBDAV_PASS`
- `REMOTE_BASES` or `REMOTE_BASE` (comma-separated allowed prefixes)
  - Example for your site: `REMOTE_BASES="zidooka/wp-content/themes/picostrap/,zidooka/wp-content/themes/picostrap-child/"`

Commands
- Replace in a single remote file (with preview and optional dry-run)
  - `node scripts/remote-agent/index.js replace --file=<remote> --from=<text|pattern> [--to=<text>] [--regex] [--dry-run]`
  - Examples:
    - Literal: `node scripts/remote-agent/index.js replace --file=zidooka/wp-content/themes/picostrap/header.php --from="旧タイトル" --to="新タイトル" --dry-run`
    - Regex: `node scripts/remote-agent/index.js replace --file=zidooka/wp-content/themes/picostrap/footer.php --from="<script[\s\S]*?</script>" --to="" --regex`

- Pull a remote file locally
  - `node scripts/remote-agent/index.js pull --file=<remote> --out=<localPath>`

- List remote directory (safe connectivity test)
  - `node scripts/remote-agent/index.js ls --dir=<remoteDir>`

- Check all allowed bases (connectivity check)
  - `node scripts/remote-agent/index.js check`

Safety
- Whitelist: operations are restricted under `REMOTE_BASE` only.
- Backup: before modification, a copy is stored on the remote as `<file>.bak.<timestamp>` and a local copy saved under `tmp_remote_agent/`.
- Dry-run: shows a preview (first 200 chars) without uploading.

Notes
- Uses POSIX-like remote paths (forward slashes) as is typical for shared hosts.
- Keep edits focused to theme files under `wp-content/themes/<theme>/`.
- On Lolipop, WebDAV often succeeds when SFTP is unavailable. Use `REMOTE_PROTOCOL=WEBDAV` with `WEBDAV_URL=https://ciao-yamakazu.webdav-lolipop.jp/`.

## Upload Log
- Pushed fixed `single.php` to remote (picostrap5-child-base).
- Command: `node scripts/remote-agent/index.js push --file=zidooka/wp-content/themes/picostrap5-child-base/single.php --src=downloads/picostrap5-child-base/single.php`
- A remote backup was created automatically with `.bak.<timestamp>`.
