---
title: "【Git】pushエラー「Updates were rejected because the remote contains work that you do not have locally」の解決法"
date: 2025-12-26 10:00:00
categories: 
  - エラーについて
tags: 
  - github
  - VS Code
  - トラブルシューティング
  - git
status: publish
slug: git-push-rejected-remote-ahead-error-jp
featured_image: ../images/git-push-rejected-remote-ahead.png
---

GitHubにプッシュしようとしたとき、以下のようなエラーが出て拒否されることがあります。

```
Updates were rejected because the remote contains work that you do not have locally.
```

これは**「GitHub側（リモート）に、あなたのローカルにはない新しいコミットが存在する」**という状態です。
そのため、Gitは「そのままpushするとGitHub側の変更が消えてしまうので、先にpullして統合してください」と警告しています。

:::conclusion
**結論：いま起きていること**
GitHubのリポジトリがローカルより進んでしまっています。
解決するには、**まず `git pull` をして差分を取り込む**必要があります。
:::

## 1. 基本的な解決策（9割はこれで解決）

まずはローカルの状態を最新にするために `pull` を行います。

### 手順

VS Codeのターミナル、またはGit Bashなどで以下のコマンドを実行します。

```bash
git pull
```

### 結果のパターン

1.  **何も衝突（Conflict）がなければ**
    そのまま完了です。続いて `git push` すれば成功します。
2.  **衝突（Conflict）が出た場合**
    同じファイルの同じ行を、リモートとローカルの両方で編集していた場合などに発生します。この場合は手動での修正が必要です（後述）。

---

## 2. 衝突（Conflict）が出た場合の対処

`git pull` した際にコンフリクトが発生すると、VS Codeなどのエディタには以下のような表示が出現します。

```
<<<<<<< HEAD
あなたの変更（ローカル）
=======
GitHub側の変更（リモート）
>>>>>>> origin/main
```

### 修正手順

1.  **どっちを残すか決める**
    VS Codeの場合、コードの上に「Accept Current Change（ローカルを残す）」「Accept Incoming Change（リモートを残す）」「Accept Both Changes（両方残す）」というボタンが表示されるので、適切なものをクリックします。
2.  **ファイルを保存**
    修正が終わったらファイルを保存します。
3.  **コミットしてプッシュ**
    修正内容を確定させてからプッシュします。

```bash
git add .
git commit -m "Fix conflict"
git push
```

---

## 3. よくある原因

なぜこのエラーが起きるのでしょうか？主なパターンは以下の通りです。

- **パターンA：GitHub上で直接編集した**（一番多い）
    - GitHubのWeb画面でREADMEなどを修正した。
    - GitHub ActionsなどのBotが自動でコミットした。
- **パターンB：他のPCからプッシュした**
    - 別のPCで作業してプッシュ済みだったのを忘れていた。

:::note
チーム開発では頻繁に発生しますが、個人開発でも「ブラウザでちょっと修正」した後にローカルで作業するとよく遭遇します。
:::

---

## 4. 【最終手段】強制プッシュ（Force Push）

もし、「GitHub側の変更なんてどうでもいい！ローカルの内容で上書きしたい！」という場合（例えば、GitHub上で間違って変なコミットをしてしまった場合など）は、強制的にプッシュすることも可能です。

:::warning
**注意：**
このコマンドを使うと、**GitHub側の履歴（ローカルにない分）は完全に消えます。**
チーム開発では**絶対に使用禁止**の現場が多いです。
:::

自分一人の個人ブログや、完全に自分専用のリポジトリであれば、状況次第ではアリです。

```bash
git push --force
```

---

## まとめ

エラーが出たら、まずは落ち着いて以下の手順を試してください。

:::step
1. まずは `git pull` を試す。
2. 何も言われなければ、そのまま `git push`。
3. コンフリクトしたら、修正して `git add` -> `git commit` -> `git push`。
4. （個人開発で）どうしても面倒なら `git push --force` も検討。
:::
