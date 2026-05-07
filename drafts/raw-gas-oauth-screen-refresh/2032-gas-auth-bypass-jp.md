---
id: 2032
slug: "gas-auth-bypass-jp"
title: "【GAS】「このアプリはGoogleによって確認されていません」と出た時の承認手順（認証回避）"
status: "publish"
date: "2025-12-13T19:00:00"
excerpt: ""
link: "https://www.zidooka.com/archives/2032"
raw_html: true
---
<!-- wp:paragraph -->
<p>Google Apps Script (GAS) を初めて実行する際、または新しい権限（Gmailやスプレッドシートへのアクセスなど）を追加した際、「承認が必要です」 というポップアップが表示されます。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>さらに進むと、「このアプリはGoogleによって確認されていません」 という警告画面が出て、「安全ではない」と言われるため、驚いて手が止まってしまう人が多いです。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>これは、「あなたが作ったスクリプトを、Googleが（公式アプリとして）審査していない」 というだけの意味であり、自分で書いたコードであれば安全上の問題はありません。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>この記事では、この警告画面を突破してスクリプトを実行するための手順を画像付きで解説します。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>なお、最近は <code>Advanced</code> ではなく <code>Continue</code> と <code>Back to safety</code> が表示される新しい tester warning 画面になるケースもあります。その場合の意味と見分け方は <a href="https://www.zidooka.com/archives/4161">こちらの新しい解説</a> を見てください。</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="1">手順1：権限の確認</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>スクリプトを実行すると、最初に以下のダイアログが出ます。
「権限を確認」をクリックします。</p>
<!-- /wp:paragraph -->

<!-- wp:image {"id":2036,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="http://www.zidooka.com/wp-content/uploads/2025/12/gas-auth-03-advanced-1.jpg" alt="Click Advanced" class="wp-image-2036"/></figure>
<!-- /wp:image -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="2">手順2：アカウントの選択</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>実行するGoogleアカウントを選択します。</p>
<!-- /wp:paragraph -->

<!-- wp:image {"id":2035,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="http://www.zidooka.com/wp-content/uploads/2025/12/gas-auth-02-choose-account-1.jpg" alt="Choose an account" class="wp-image-2035"/></figure>
<!-- /wp:image -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="3">手順3：警告画面の突破（ここが重要）</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>ここで、赤いビックリマークと共に「このアプリはGoogleによって確認されていません」という画面が表示されます。
「安全なページに戻る」という青いボタンを押したくなりますが、押してはいけません（押すと処理が中断されます）。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>左下にある 「詳細 (Advanced)」 というリンクをクリックしてください。</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="4">手順4：安全ではないページへ移動</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>詳細が開くと、下の方にリンクが表示されます。
「[プロジェクト名]（安全ではないページ）に移動」 をクリックします。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>※「安全ではない」と書かれていますが、これは「Googleの審査を受けていない」という意味です。自作スクリプトならそのまま進んでOKです。</p>
<!-- /wp:paragraph -->

<!-- wp:image {"id":2038,"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="http://www.zidooka.com/wp-content/uploads/2025/12/gas-auth-05-allow-1-1024x691.jpg" alt="Click Allow" class="wp-image-2038"/></figure>
<!-- /wp:image -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="5">手順5：アクセスの許可</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>最後に、スクリプトが何にアクセスしようとしているか（スプレッドシートの編集権限など）が表示されます。
内容を確認し、一番下の 「許可 (Allow)」 をクリックします。</p>
<!-- /wp:paragraph -->

<!-- wp:image {"id":2037,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="http://www.zidooka.com/wp-content/uploads/2025/12/gas-auth-04-unsafe-1.jpg" alt="Go to unsafe page" class="wp-image-2037"/></figure>
<!-- /wp:image -->

<!-- wp:heading -->
<h2 class="wp-block-heading">完了</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>これでスクリプトが実行されます。
この認証プロセスは、一度許可すれば、次回からは表示されません（スクリプトに新しい権限を追加した場合は、再度表示されます）。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>怖がらずに「詳細」→「移動」→「許可」と進めましょう。</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2 class="wp-block-heading">よくある質問（Q&amp;A）</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Q1. この警告は無視して使い続けても大丈夫ですか？</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>結論から言うと、<strong>自分専用・社内利用であれば問題ありません</strong>。<br>この警告は「危険なアプリ」という意味ではなく、<strong>Googleによる第三者向けの審査が未完了</strong>であることを示しているだけです。<br>自分が書いたGASや、信頼できる開発者のスクリプトであれば、リスクは極めて低いです。</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Q2. 毎回この警告が出るのはなぜですか？</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>未確認アプリとして作成されたGASは、初回実行時に必ずこの画面が出ます。</strong><br>これは設定ミスではなく、GASの仕様です。<br>一度許可すれば、同じアカウント・同じスクリプトでは基本的に再表示されません。</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Q3. 「詳細」→「○○（安全ではないページ）に移動」を押しても大丈夫？</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>はい。<strong>この操作自体で何かが実行されることはありません。</strong><br>単に「自己責任で進みますか？」という確認ステップです。<br>コードの内容を自分で把握しているなら、安心して進んで問題ありません。</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Q4. クライアントに配布するGASでも、この方法でいいですか？</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>この方法は<strong>自分用・検証用・社内利用向け</strong>です。<br>不特定多数やクライアントに配布する場合は、</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>OAuth 同意画面の設定</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>アプリの公開・確認申請<br>を行う方が適切です。</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>ただし、<strong>小規模案件や検証段階では未確認のまま使われているケースも非常に多い</strong>のが実情です。</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Q5. この警告を完全に出さない方法はありますか？</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>ありますが、<strong>現実的にはおすすめしないケースが多い</strong>です。</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Google によるアプリ確認（時間・手間がかかる）</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>OAuth スコープを最小限に絞る</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>個人開発や業務自動化目的なら、<br><strong>警告を理解した上で使う方が圧倒的にコスパが良い</strong>です。</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Q6. 「このアプリはブロックされています」と出た場合は？</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>それは別問題です。<br>管理者制限（Google Workspace）や、<br>組織ポリシーによって<strong>実行自体が禁止</strong>されている可能性があります。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>この場合は：</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>個人アカウントで実行</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>管理者に許可を依頼<br>といった対応が必要になります。</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p>この警告は「危険だから止めろ」ではなく、「あなたは理解していますか？」という確認です。<br>GASを業務で使う以上、この画面と正しく付き合えるかどうかが一つの分かれ目になります。</p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->
