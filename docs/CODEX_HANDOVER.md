# Codex handover

This is one of the main working repositories for yamachi4u.

## Environment files

This checkout does not include real environment files or secrets.

Do not commit `.env`, API keys, WordPress credentials, analytics credentials, FTP credentials, OpenAI keys, tokens, or service-account files to this repository.

When running this repository on the main PC, restore the required environment values through a local-only secure channel, for example:

- a password manager or encrypted local note
- a local `.env` created from `.env.example`
- OS-level user environment variables
- deployment-provider secret settings when running remotely

If an agent or script needs env values and they are missing, stop before execution and ask the user to provide them through a non-Git path.

## Local reminder

This repository drives ZIDOOKA writing, analytics, image, and WordPress publishing workflows. Treat publishing scripts as production-affecting and verify target site/account settings before posting.
