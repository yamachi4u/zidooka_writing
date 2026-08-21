---
title: "Git LFS・Cloudflare R2・Backblaze B2・Google Drive・Amazon S3を比較する【2026年8月版】"
slug: large-file-storage-comparison-2026-08
status: publish
categories:
  - Web開発
tags:
  - Git LFS
  - Cloudflare R2
  - Backblaze B2
  - Google Drive
  - Amazon S3
  - ストレージ
  - 2026年
---

大量のPDF、画像、音声、動画、学習データなどを保存し始めると、普通のGitリポジトリだけでは扱いにくくなります。GitHubは通常のGitで100MiBを超えるファイルを拒否するため、大容量ファイルをGitと一緒に管理したい場合はGit LFSが候補になります。

ただし、単に「大きなファイルを保存して、必要なときに人間やプログラムが取得したい」のであれば、Git LFSよりCloudflare R2やBackblaze B2のようなオブジェクトストレージの方が自然な場合があります。

この記事では、2026年8月時点のGit LFS、Cloudflare R2、Backblaze B2、Google Drive、Amazon S3を比較します。

## 先に結論

| サービス | 強い用途 | 無料・低価格帯の特徴 | 注意点 |
|---|---|---|---|
| GitHub Git LFS | Gitと大容量ファイルを一体管理 | Free/Proでストレージ10GiB、月間帯域10GiB | 履歴ごと容量を消費する |
| Cloudflare R2 | APIから頻繁に読むファイル庫 | Standardは10GB-month無料、エグレス無料 | APIリクエスト課金がある |
| Backblaze B2 | 数百GB〜TB級の安価な保管 | 10GB無料、$6.95/TB/月から | 無料エグレスは原則保存量の3倍まで |
| Google Drive | 人間が直接整理・閲覧する資料庫 | Googleアカウントに15GB、Google Oneで拡張 | S3型のストレージ基盤ではない |
| Amazon S3 | 本番システム・AWS統合 | 機能と選択肢が非常に多い | 料金体系が複雑になりやすい |

用途が「Gitの履歴として管理したい」のか、「巨大なファイル置き場がほしい」のかで選択はかなり変わります。

## Git LFS：Gitとの一体感は最強

Git LFSは、大容量ファイルそのものを通常のGitオブジェクトとして持つのではなく、Git側にはポインタを保存し、実体をLFSストレージに置く仕組みです。

2026年8月時点でGitHub FreeとGitHub Proには、Git LFSのストレージ10GiBとダウンロード帯域10GiB/月が含まれています。GitHub TeamとEnterprise Cloudでは、それぞれ250GiBです。

1ファイルあたりの上限はFree/Proが2GB、Teamが4GB、Enterprise Cloudが5GBです。

無料枠を超える追加利用について、GitHubの料金計算機ではストレージが$0.07/GiB/月、データ転送が$0.0875/GiBと案内されています。

Git LFSで特に注意したいのはバージョン履歴です。500MBのファイルをpushし、そのファイルを少し変更して再度pushすると、旧版500MBと新版500MBが別オブジェクトとして保存されます。大きなPDFや動画を何度も更新すると、現在のファイルサイズ以上にストレージを消費します。

またGitHub ActionsがLFSファイルを取得した場合も、LFSの帯域使用量として計上されます。

そのため、Git LFSは「大容量ファイルをGitのバージョン管理に参加させたい」ときには便利ですが、「変更しないPDFを何万件も置く資料庫」としては必ずしも最適ではありません。

## Cloudflare R2：頻繁に読むオブジェクトストレージに強い

Cloudflare R2はS3互換APIを持つオブジェクトストレージです。

Standardストレージは10GB-month/月まで無料です。さらにClass A操作は月100万回、Class B操作は月1000万回まで無料で、インターネットへのエグレス料金は原則無料です。

無料枠を超えるStandardストレージは$0.015/GB-monthです。100GBを1か月保存する場合、無料10GBを単純に差し引けば、ストレージ部分は概算で約$1.35/月です。

2026年にはInfrequent Accessクラスもあり、保存単価は$0.01/GB-monthですが、こちらは無料枠の対象外で、データ取得時に$0.01/GBのretrieval料金がかかり、最低保存期間も30日あります。

R2の大きな特徴はエグレス無料です。AIエージェント、CI、Webアプリ、スクリプトなどが同じ資料を何度も取得する用途では、転送量を読みづらいサービスよりコスト設計がしやすくなります。

## Backblaze B2：大量保管の単価が安い

Backblaze B2もS3互換のオブジェクトストレージです。

2026年8月時点では10GBまで無料で、通常のB2 Cloud Storageは$6.95/TB/月からと案内されています。数百GBから数TB以上を長期間保管する場合、保存単価はかなり低い部類です。

エグレスは平均月間保存量の3倍まで無料で、それを超える分は原則$0.01/GBです。またCloudflareなど一部のCDN・コンピュートパートナー経由では無料エグレスの仕組みもあります。

したがって、頻繁に無制限に読み出すならR2が分かりやすく、巨大なデータを比較的安く保管しつつ読み出し量もある程度予測できるならB2が有力です。

## Google Drive：人間が使うファイル庫として強い

Google DriveはR2やB2とは性格が違います。

GoogleアカウントにはGmail、Google Drive、Googleフォトで共有する15GBの保存容量があり、Google Oneでは100GB、200GBなどのストレージプランに拡張できます。

最大の利点は、人間向けUIが最初から完成していることです。フォルダを開く、PDFをプレビューする、スマートフォンから検索する、共有リンクを送る、といった作業はオブジェクトストレージより圧倒的に簡単です。

一方で、R2、B2、S3のような「S3互換の巨大なファイル基盤」として設計されているわけではありません。プログラムから大量のオブジェクトを規則的に読み書きする基盤としては、認証やAPIの扱いを含めてオブジェクトストレージの方が組みやすいケースが多いです。

## Amazon S3：標準的だが、料金設計は複雑

Amazon S3はオブジェクトストレージの事実上の標準の一つです。IAM、ライフサイクル管理、バージョニング、Glacier系のアーカイブ、AWSの各種サービスとの統合など、必要な機能をかなり細かく構成できます。

一方、料金はストレージだけでは決まりません。ストレージクラス、リクエスト、データ取得、データ転送、リージョンなどによって変わります。

AWSが公開している東京リージョンの構成例では、S3 Standardが$0.025/GBとして計算されています。単純なファイル庫だけが必要ならR2やB2より割高になりやすい一方、AWS上の本番システムと密接に連携するなら、S3を選ぶ合理性は非常に高いです。

## 容量と用途で選ぶなら

かなり単純化すると、次のように考えられます。

| 状況 | 第一候補 |
|---|---|
| 数GBで、Gitの履歴に含めたい | Git LFS |
| 10〜数百GBで、APIやAIエージェントから頻繁に読む | Cloudflare R2 |
| 数百GB〜数TB以上を安く保管 | Backblaze B2 |
| スマホやPCから人間が直接探して読む | Google Drive |
| AWS上の本番システムに組み込む | Amazon S3 |

もちろん境界は固定ではありません。特にR2とB2はどちらもS3互換なので、設計次第でかなり広い用途をカバーできます。

## GitHubにはメタデータ、実ファイルはR2/B2という構成

大量のPDFや資料を扱う場合、個人的に分かりやすいのは、GitHubとオブジェクトストレージを分離する構成です。

```text
GitHub
├─ README.md
├─ bibliography.csv
├─ metadata/
│  ├─ 001.yaml
│  └─ 002.yaml
└─ scripts/
   └─ fetch_documents.py

R2 / B2
├─ papers/
├─ books/
├─ scans/
└─ images/
```

GitHub側にはタイトル、著者、出典、ファイルキー、ハッシュ値、メモなどを置き、PDFの実体はR2やB2に保存します。

```yaml
id: example-1972
title: Example Paper
object_key: papers/example-1972.pdf
sha256: ...
```

こうすると、Gitの差分管理や検索性は維持しつつ、巨大なバイナリでリポジトリを膨らませずに済みます。必要なファイルだけスクリプトやエージェントが取得する構成にもできます。

:::conclusion
Git LFSは「大容量ファイルをGitとして扱う」ための仕組みです。R2やB2は「大容量ファイルをオブジェクトとして保存する」ための仕組みです。この違いを最初に分けて考えると選びやすくなります。

2026年8月時点では、小規模なGit連携ならGit LFS、APIから頻繁に読むならR2、大容量を安く持つならB2、人間中心ならGoogle Drive、AWS本番環境ならS3、という整理が実用的です。
:::

References:
1. GitHub Docs - Git Large File Storage billing
https://docs.github.com/en/billing/concepts/product-billing/git-lfs
2. GitHub Docs - About Git Large File Storage
https://docs.github.com/en/repositories/working-with-files/managing-large-files/about-git-large-file-storage
3. GitHub Pricing Calculator
https://github.com/pricing/calculator
4. Cloudflare R2 Pricing
https://developers.cloudflare.com/r2/pricing/
5. Backblaze B2 Pricing
https://www.backblaze.com/cloud-storage/pricing
6. Google One
https://one.google.com/intl/ja_jp/about/
7. Amazon S3 Pricing
https://aws.amazon.com/jp/s3/pricing/
