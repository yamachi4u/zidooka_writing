---
title: Codex CLIを起動時に自動承認（auto approve）に固定する方法｜How to Force Auto‑Approve at Startup in Codex CLI
slug: codex-cli-auto-approve-startup
status: publish
categories: ["WordPressTips", "便利ツール"]
tags: ["Codex CLI", "approval_policy", "sandbox_mode"]
featured_image: images/2025/cli-demo-1.png
---

【結論】`~/.codex/config.toml` に `approval_policy = "never"` を設定すると、起動時から自動承認になります。

【注意】安全のため、最初は `on-failure` で検証し、必要な場面だけ `never` に切り替える運用を推奨します。

## 手順（日本語）
1) 設定ファイルを作成/編集します。

```
# macOS/Linux: ~/.codex/config.toml
# Windows: %UserProfile%\.codex\config.toml
```

2) 最低限、以下を追記します。

```toml
# 端末起動時の承認ポリシー（自動承認）
approval_policy = "never"

# 追加（任意）：サンドボックスを広げる場合
# danger-full-access: 端末のファイル/ネットワーク制限を解除（注意）
sandbox_mode = "danger-full-access"
```

3) セッション個別で上書きしたい場合は、起動時フラグでも指定できます。

```
codex --config approval_policy=never --config sandbox_mode=danger-full-access
```

4) 対話中に変更する場合は、画面で `/approvals` を実行します（スラッシュコマンド）。

![](images/2025/cli-demo-1.png)

【ポイント】リポジトリ内の `requirements.toml` で `allowed_approval_policies` が制限されていると、`never` を禁止できる運用もあります（チーム運用での安全弁）。

【対処】「Auto」モードが思った挙動にならない場合は、`config.toml` を明示設定にし、セッション途中の `/approvals` で上書きされないかを確認してください。

## Steps (English)
1) Create or edit your Codex user config.

```
# macOS/Linux: ~/.codex/config.toml
# Windows: %UserProfile%\.codex\config.toml
```

2) Add the following keys.

```toml
approval_policy = "never"           # full auto-approve at startup
sandbox_mode = "danger-full-access" # optional; removes FS/network limits (use with care)
```

3) To override per session, pass flags on launch:

```
codex --config approval_policy=never --config sandbox_mode=danger-full-access
```

4) In an interactive session, run `/approvals` to switch modes.

【注意】Some repos may enforce allowed policies through `requirements.toml`. If `never` is disallowed, remove it from the policy list or ask the maintainer.

## よくある質問（FAQ）
- 【質問】「Auto」モードにしても完全自動にならないのは？
  - 【対処】バージョンによっては「Auto」が `on-request` 相当になることがあります。`config.toml` で `approval_policy = "never"` を明示し、必要に応じて `--config` フラグで上書きしてください。
- 【質問】安全に使うコツは？
  - 【ポイント】普段は `on-failure`、信頼できるリポジトリやバッチ作業だけ `never` にする二段運用が安全です。必要なら `requirements.toml` で許可ポリシーを固定します。

## 参考URL / References
1. Codex CLI features
https://developers.openai.com/codex/cli/features/
2. Basic Config (config.toml)
https://developers.openai.com/codex/config-basic
3. Config Reference
https://developers.openai.com/codex/config-reference
4. GitHub Issue: approval_policy behavior
https://github.com/openai/codex/issues/3129

