# Remote Upload (Theme Files)

Use the bundled Remote Template Agent to safely push edited theme files to the remote WordPress host. It creates on‑server backups and restricts operations to whitelisted theme paths.

## Prerequisites
- Node.js 18+
- Install deps: `npm install`
- Configure `.env` with connection and whitelist settings (examples already included in the repo):
  - `REMOTE_PROTOCOL=WEBDAV` (or `SFTP` / `FTPS`)
  - `WEBDAV_URL`, `WEBDAV_USER`, `WEBDAV_PASS` (or protocol‑specific credentials)
  - `REMOTE_BASES="zidooka/wp-content/themes/picostrap5/,zidooka/wp-content/themes/picostrap5-child/,zidooka/wp-content/themes/picostrap5-child-base/,zidooka/wp-content/themes/zidooka-tw/"`

## Quick Checks
- Connectivity and whitelist:
  - `node scripts/remote-agent/index.js check`
- List a remote directory:
  - `node scripts/remote-agent/index.js ls --dir="zidooka/wp-content/themes/picostrap5-child-base/"`
  - `node scripts/remote-agent/index.js ls --dir="zidooka/wp-content/themes/zidooka-tw/"`

## Push Updated Files
Backups are created remotely as `<file>.bak.<timestamp>` before upload.

- Single post template:
  - `node scripts/remote-agent/index.js push --file="zidooka/wp-content/themes/picostrap5-child-base/single.php" --src="C:\\Users\\user\\Documents\\zidooka_writing\\downloads\\picostrap5-child-base\\single.php"`

- Functions file:
  - `node scripts/remote-agent/index.js push --file="zidooka/wp-content/themes/picostrap5-child-base/functions.php" --src="C:\\Users\\user\\Documents\\zidooka_writing\\downloads\\picostrap5-child-base\\functions.php"`

- Zidooka theme (example):
  - `node scripts/remote-agent/index.js push --file="zidooka/wp-content/themes/zidooka-tw/single.php" --src="C:\\Users\\user\\Documents\\zidooka_writing\\tmp_remote_agent\\zidooka-tw\\single.php"`

Tip: You can use the package script alias:

```
npm run remote:agent -- push --file="zidooka/wp-content/themes/picostrap5-child-base/single.php" --src="C:\\Users\\user\\Documents\\zidooka_writing\\downloads\\picostrap5-child-base\\single.php"
```

## Notes
- Operations are restricted to `REMOTE_BASES` paths; adjust the child theme path if your environment differs.
- To rollback, push the corresponding `.bak.<timestamp>` back to the original filename or restore via your hosts file manager.
- Local backups are saved under `tmp_remote_agent/` on pull/push operations.
