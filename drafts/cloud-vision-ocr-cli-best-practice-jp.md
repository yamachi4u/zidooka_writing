---
title: "Cloud Vision OCRをCLIで回すのが一番ラクだった理由"
date: 2025-12-17 11:00:00
categories: 
  - OCR
tags: 
  - Cloud Vision API
  - OCR
  - CLI
  - GCP
  - Python
  - VS Code
status: publish
slug: cloud-vision-ocr-cli-best-practice-jp
featured_image: ../images/twitterposttool/cloud-vision-api-ocr.png
---

Google Cloud Vision API を使った OCR は、
一見すると「GUIで設定してポチっとやれば終わり」な作業に見えます。

実際にやってみる前は、私もそう思っていました。

ところが、いざ本気で OCR を回そうとすると、
GCP の GUI だけで完結させるのはかなりしんどいという結論に至りました。

この記事では、Cloud Vision OCR を実際に使ってみた体験をもとに、
なぜ最終的に CLI（CUI）で回す構成が一番ラクだったのかを整理します。

## GCPのGUIは「設定できる」が「流れが見えない」

GCP の管理画面は、機能単体で見るとよくできています。

*   API の有効化
*   IAM の設定
*   サービスアカウント作成
*   Cloud Storage のバケット作成

それぞれは確かに GUI で操作できます。

ただし、OCR のように

*   Vision API
*   IAM
*   Storage
*   ローカル環境（Python）

が同時に絡むケースでは、
設定がどこで効いているのかが分かりにくいのが正直なところです。

「設定は合っているはずなのに動かない」
この状態が長く続きました。

## IAMエラーはGUIだと原因が見えにくい

Cloud Vision OCR で一番ハマりやすいのが IAM 周りです。

*   `roles/vision.user` を付けたはずなのに通らない
*   バケットは存在するのに `bucket.exists()` でエラー
*   API は有効なのに権限エラーが出る

GUI 上では「設定済み」に見えても、
実際にその権限がどの処理に使われているのかが追いづらい。

ここでログを見ながら試行錯誤する必要が出てきます。

## Cloud Shell + CLI に切り替えて一気に楽になった

途中から発想を切り替えました。

GUIで頑張るより、
GCPの中で shell を開いて、
CLI 前提で整理したほうが早いのでは？

結果的に、これが正解でした。

Cloud Shell を起点にして、

1.  サービスアカウントキー（`key.json`）を明示的に置く
2.  `GOOGLE_APPLICATION_CREDENTIALS` を自分で指定
3.  Python スクリプトを直接実行

この構成にした途端、
「どの認証で」「どのAPIを」「どの権限で」叩いているかが一気に見えるようになりました。

## 認証情報を“自分で指定できる”安心感

CLI で回す最大のメリットはここです。

```python
os.environ["GOOGLE_APPLICATION_CREDENTIALS"] = "key.json"
```

この1行があるだけで、

*   どのサービスアカウントを使っているか
*   GUIの状態に引きずられていないか
*   権限トラブルがコード側か設定側か

が明確になります。

GUIだと「今どの認証で動いているのか」が曖昧になりがちですが、
CLIでは **すべて自分で指定している** という感覚があります。

これは精神的にもかなりラクでした。

## OCRは「何度も回す作業」なのでCLI向き

OCR は一度動けば終わり、という処理ではありません。

1.  PDFを差し替える
2.  設定を少し変える
3.  結果のJSONを確認する
4.  再実行する

このループを何度も回します。

GUI操作を毎回やるより、

```bash
python ocr_pdf.py
```

で即回せるほうが圧倒的に早い。

特に精度検証や失敗原因の切り分けでは、
CLIで即再実行できること自体が大きなメリットでした。

## 結論：OCRは「最初からCLI前提」が一番ラク

Cloud Vision OCR 自体は強力です。
ただし、GCPのGUIだけで完結させようとすると、
構成が見えなくなり、トラブル時に詰みやすくなります。

*   認証を明示する
*   実行経路を単純にする
*   ログをその場で確認できる

この条件を満たすなら、
Cloud Shell + CLI + ローカルスクリプトという構成が、
結果的に一番ラクでした。

「GUIで頑張っているけど、なぜか動かない」
そんな状態になっているなら、一度 CLI に寄せてみるのはおすすめです。

少なくとも私は、そこでようやく OCR を前に進めることができました。

---

### Cloud Vision OCR シリーズ記事一覧

本記事は、Cloud Vision API を使った OCR 環境構築・運用に関するシリーズ記事の一部です。

*   **[【まとめ】Cloud Vision OCR × CLI × GCP 導入・トラブルシュート全記録](/archives/ocr-series-summary-jp)**
*   **導入編:** [Cloud Vision API の OCR が IAM で通らない理由と、最終的に動かすまでの全記録](/archives/cloud-vision-api-ocr-iam-trouble-jp)
*   **運用編:** [Cloud Vision OCRをCLIで回すのが一番ラクだった理由](/archives/cloud-vision-ocr-cli-best-practice-jp)
*   **ワークフロー編:** [頻繁にOCRするなら GCP × VSC × CLI が正解ルートになる理由](/archives/frequent-ocr-gcp-vsc-cli-workflow-jp)
*   **ツール編:** [Cloud Vision OCRをCLIで回すために作った実務向けツール解説](/archives/cloud-vision-ocr-cli-tool-jp)
*   **コード編:** [【コード無料】Cloud Vision OCRをCLIで安全に回す最小構成スクリプト](/archives/cloud-vision-ocr-minimal-script-jp)
*   **番外編:** [GCPのGUIで詰んだら、Cloud Shell＋CUIに逃げたほうが早かった話（失敗談）](/archives/gcp-gui-vs-cloud-shell-failure-story-jp)
