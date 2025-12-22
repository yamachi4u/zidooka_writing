---
title: "Fatal error: Call to undefined function が出たときの正しい原因と対処法"
date: "2025-12-17 10:00:00"
categories: 
  - WordPress
tags: 
  - WordPress
  - PHP
  - トラブルシューティング
status: publish
---

functions.php の1行で WordPress 管理画面が落ちるケース

WordPressを運用していると、ある日突然、管理画面にアクセスできなくなることがあります。
画面には大量のエラーメッセージが表示され、「PHPのバージョンが悪いのか」「WordPressのアップデートが原因なのか」「プラグインが壊れたのか」と疑いたくなる状況です。

しかし、実際に管理画面を落としている原因は、意外なほど単純なケースがあります。
それが functions.php に書かれた、たった1行の関数呼び出しです。

この記事では、Fatal error: Call to undefined function が表示されて管理画面が落ちる原因と、正しい切り分け方、そして再発防止の考え方を整理します。

## 管理画面が落ちる直接の原因

今回のエラーメッセージは次のようなものでした。

```
Fatal error: Uncaught Error: Call to undefined function foo_bar_function()
```

このエラーの意味は非常にシンプルです。

* 存在しない関数を呼び出している
* PHPはその時点で処理を続行できない
* WordPressの初期化途中で処理が止まる

重要なのは、このエラーが functions.php の読み込み時点で発生しているという点です。

functions.php は、フロント画面だけでなく、管理画面を含めたすべてのリクエストで読み込まれます。そのため、ここで Fatal error が発生すると、管理画面も含めて WordPress 全体が落ちます。

## なぜ Notice や Deprecated が大量に出ていても無関係なのか

この種のトラブルでは、次のようなメッセージが同時に表示されていることが多くあります。

* Deprecated: Creation of dynamic property is deprecated
* wp_register_style が誤って呼び出されました
* _load_textdomain_just_in_time が誤って呼び出されました

これらはすべて Notice や Deprecated レベルの警告です。

Notice や Deprecated は、
「書き方として望ましくない」
「将来のバージョンでは問題になる可能性がある」
という警告であり、即座に WordPress を停止させるものではありません。

一方で、Fatal error は違います。

* Fatal error は即終了
* 後続の処理は一切実行されない
* 他のエラーがどれだけ出ていても、最優先で見るべき対象

管理画面が完全に落ちている場合、まず注目すべきなのは Fatal error が出ていないかどうかです。

## functions.php で起きがちな典型パターン

今回のケースは、WordPressの自作テーマやカスタマイズをしていると非常によく起きるパターンです。

* 過去に実験的なコードを書いた
* 別ファイルに切り出した、あるいは削除した
* 関数定義は消えたが、呼び出しだけが残った

たとえば、次のようなコードです。

```php
foo_bar_function();
```

この関数がどこにも定義されていなければ、WordPressは即座に Fatal error を起こします。

functions.php に書かれたコードは、管理画面かどうかに関係なく実行されるため、「フロント用の処理のつもりだった」という言い訳は通用しません。

## 正しい対処法

このエラーの対処は難しくありません。状況に応じて、次のいずれかを選びます。

### 使っていない処理なら削除する

最も安全で、もっとも正しい方法です。

```php
// foo_bar_function();
```

あるいは行自体を削除します。

### 将来使う可能性があるならガードする

```php
if (function_exists('foo_bar_function')) {
    foo_bar_function();
}
```

これにより、関数が存在しない環境でも Fatal error を防げます。

### 応急対応としてダミー関数を定義する（非推奨）

```php
function foo_bar_function() {
    return [];
}
```

これは一時的な回避策としては有効ですが、後で忘れやすいため、恒久対応としてはおすすめしません。

## 再発防止のための設計の考え方

今回の問題は、WordPressのバグでもPHPの仕様変更でもありません。
functions.php にロジックを詰め込みすぎたことが原因です。

functions.php の役割は、本来次のようなものに限定すべきです。

* add_action / add_filter
* 定数定義
* require_once によるファイル読み込み

表示用のデータ生成や、外部サービス（Simple History、RSS、APIなど）を扱う処理は、functions.php に直接書くべきではありません。

表示に関係する処理は、以下のいずれかに置くのが安全です。

* front-page.php や single.php
* inc ディレクトリ以下の専用ファイル

functions.php は「配線」、実体は別ファイル、という構造を意識することで、管理画面を巻き込む事故を防げます。

## まとめ

WordPressのエラー対応で重要なのは、エラーの数ではなく種類を見ることです。

* 管理画面が落ちているなら Fatal error を最優先で確認する
* functions.php の1行が全体を止めることがある
* Notice や Deprecated は原因ではないことが多い

自作テーマやカスタマイズが増えてきたタイミングほど、こうした事故は起きやすくなります。
だからこそ、functions.php の役割を明確に分け、コードの置き場所を意識することが重要です。
