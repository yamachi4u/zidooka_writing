---
title: "tlmgrとは？TeX Liveのパッケージ管理をざっくり理解する"
categories:
  - PC
tags:
  - LaTeX
  - TeX Live
  - tlmgr
  - TinyTeX
  - CI
status: publish
slug: tlmgr-tex-live-package-manager
---

LaTeX環境を触っていると、`tlmgr`というコマンドが突然出てくることがあります。名前だけでは役割が分かりにくいですが、正体はかなり単純です。

:::conclusion
`tlmgr`はTeX Liveに付属するパッケージ・設定管理ツールです。感覚としては、Pythonの`pip`、Node.jsの`npm`、Linuxの`apt`に近く、LaTeXパッケージの追加・更新・検索などを担当します。
:::

## tlmgrは何の略？

`tlmgr`はTeX Live Managerのコマンド名です。TeX Users Groupの公式ドキュメントでは、既存のTeX Live環境について、パッケージと設定を管理するツールとして説明されています。

TeX Live自体は、LaTeX本体だけではなく、大量のパッケージ、フォント、エンジン、補助ツールをまとめたディストリビューションです。`tlmgr`は、その中身を後から管理する役割を持ちます。

たとえば、次のようなことができます。

- パッケージをインストールする
- パッケージを更新する
- パッケージを削除する
- インストール済みパッケージを調べる
- ファイル名から必要なパッケージを探す
- TeX Liveのリポジトリ設定を変更する

OS全体のパッケージを管理する`apt`やHomebrewとは別物です。`tlmgr`が管理するのは、あくまでTeX Liveの内部です。

## よく使うコマンド

### パッケージを入れる

```bash
tlmgr install geometry
```

複数まとめて入れることもできます。

```bash
tlmgr install geometry fancyhdr xcolor
```

LaTeXのコンパイル時に「ある`.sty`が見つからない」と言われた場合、必要なパッケージを`tlmgr install`で追加するのが典型的な対処です。

:::example
ある文書生成環境で`geometry.sty`が必要なら、TeX Live上では`geometry`パッケージを追加します。

```bash
tlmgr install geometry
```
:::

### 更新を確認する

```bash
tlmgr update --list
```

実際に全体を更新する場合は次のようにします。

```bash
tlmgr update --self
tlmgr update --all
```

`--self`は`tlmgr`自身、`--all`は更新可能なTeX Liveパッケージ全体が対象です。

### パッケージやファイルを探す

```bash
tlmgr search geometry
```

特定のファイルがどのパッケージに含まれているかをネットワーク側のTeX Liveデータベースから探すなら、次の形が使えます。

```bash
tlmgr search --global --file t2aenc.def
```

「エラーに出てきたファイル名は分かるが、どのパッケージを入れればいいのか分からない」というときに便利です。

## TinyTeXでもtlmgrを使う

TinyTeXはTeX Liveをベースにした軽量なLaTeXディストリビューションです。TinyTeXの公式ドキュメントでも、コマンドライン利用者が覚える中心的なコマンドとして`tlmgr`が紹介されています。

TinyTeXは必要なパッケージだけを入れて環境を小さく保つ思想なので、足りないパッケージを後から`tlmgr`で追加する構成と相性がよいです。

一方で、TinyTeXには複数の配布形態があります。最小構成ではパッケージがかなり少なく、より大きなprebuilt bundleでは一般的なパッケージが最初から多く含まれています。

:::note
同じTinyTeXでも、どのbundleを使っているかによって「最初から入っているパッケージ」は変わります。`tlmgr install`が常に必要とは限りません。
:::

## CIでは毎回tlmgr installするべき？

GitHub ActionsなどのCIでLaTeX文書を生成するとき、毎回次のような処理を書くことがあります。

```bash
tlmgr install geometry fancyhdr xcolor
```

これは確実ですが、必要なパッケージがすでに配布bundleに含まれているなら、そのインストールは重複作業です。ネットワークアクセスも発生するため、ビルド時間が長くなる原因になります。

たとえば、ある資料をXeLaTeXでPDF化するCIを考えます。最初は不足を避けるために必要そうなパッケージを`tlmgr install`していたとしても、使用中のTinyTeX bundleにすべて含まれていることが確認できれば、そのステップを削除できます。

:::step
CIを軽くしたい場合は、まず追加の`tlmgr install`を外した状態で実際に文書をコンパイルします。成功するなら、そのインストール処理は不要です。失敗した場合だけ、ログに出た不足パッケージを追加する方が構成を小さく保てます。
:::

## 「tlmgrが見つからない」とき

`tlmgr: command not found`やPowerShellでコマンドが認識されない場合、主に次の可能性があります。

- TeX Live / TinyTeX自体が入っていない
- TeX Liveは入っているが`tlmgr`のあるディレクトリが`PATH`に入っていない
- CIでTeX Liveのセットアップ前に`tlmgr`を呼んでいる
- シェルが変わり、セットアップ処理が追加した`PATH`を引き継げていない

特にCIでは「インストールには成功したのに、その次のシェルから`tlmgr`が見えない」ということがあります。TeX LiveのセットアップActionやスクリプトがどのように`PATH`を追加しているかを確認するのが先です。

:::warning
`tlmgr`が見つからないからといって、すぐにTeX Liveをもう一度インストールする必要はありません。まず`PATH`と実行しているシェルを確認した方が安全です。
:::

## aptやHomebrewとの違い

`tlmgr`とOSのパッケージマネージャは管理範囲が違います。

| ツール | 主な管理対象 |
| --- | --- |
| `apt` | Ubuntu / Debianのシステムパッケージ |
| Homebrew | macOSなどのアプリ・CLIツール |
| `pip` | Pythonパッケージ |
| `npm` | Node.jsパッケージ |
| `tlmgr` | TeX Liveのパッケージと設定 |

Linuxディストリビューションのパッケージ管理経由でTeX Liveを入れている場合など、システム側の管理方法と`tlmgr`をどう組み合わせるかは環境によって異なります。TeX Live公式のインストールを直接使っている場合と、OSディストリビューションが提供するTeX Liveでは運用方針が違うことがあるため、無理に混ぜない方が安全です。

## まとめ

:::conclusion
`tlmgr`は「LaTeXで使う追加部品を管理するTeX Live専用のパッケージマネージャ」と考えると分かりやすいです。普段は意識しなくても、`.sty`不足の解消、TinyTeXの軽量運用、CIのLaTeX環境調整では重要な役割を持ちます。
:::

最低限、次の3つを覚えておけば十分です。

```bash
tlmgr install <package>
tlmgr update --list
tlmgr search <keyword>
```

CIでは「念のため毎回インストールする」より、使っているTeX Live / TinyTeX bundleに何が含まれているかを確認し、不足分だけ追加する方が高速で壊れにくい構成になります。

## 参考資料

- [TeX Live 2026 Guide - tlmgr: Managing your installation](https://www.tug.org/texlive/doc/texlive-en/texlive-en.html)
- [tlmgr - the native TeX Live Manager](https://tug.org/texlive/doc/tlmgr.html)
- [TinyTeX公式ドキュメント](https://yihui.org/tinytex/)
