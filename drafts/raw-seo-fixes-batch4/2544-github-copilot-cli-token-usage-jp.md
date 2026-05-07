---
id: 2544
slug: "github-copilot-cli-token-usage-jp"
title: "GitHub Copilot CLIで「sorry, you have exceeded your copilot token usage」と出たときの原因と対処法｜今のところ待つしかなさそう"
status: "publish"
date: "2025-12-18T18:00:00"
excerpt: ""
link: "https://www.zidooka.com/archives/2544"
raw_html: true
---
<!-- wp:paragraph -->
<p>GitHub Copilot CLI や VS Code 上で作業していると、突然次のようなエラーに遭遇することがあります。</p>
<!-- /wp:paragraph -->

<!-- wp:code -->
<pre class="wp-block-code"><code class="language-plaintext">sorry, you have exceeded your copilot token usage.
please review our terms of service.</code></pre>
<!-- /wp:code -->

<!-- wp:paragraph -->
<p>有料プランを使っているにもかかわらずこの表示が出ると、「え、もう使えないの？」「課金足りてない？」と戸惑いますよね。この記事では、このエラーの正体と実務的な対処法を整理します。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>同系統の rate_limited 表示は <a href="https://www.zidooka.com/archives/2755">こちら</a>、Premium Requests の上限表示は <a href="https://www.zidooka.com/archives/2114">こちら</a>、Copilot エラー全体の整理は <a href="https://www.zidooka.com/archives/2672">こちら</a> を参照してください。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">結論：これは「契約切れ」ではなく、一時的な利用制限</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>まず結論から言うと、このメッセージはアカウント停止や契約失効ではありません。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>多くの場合、GitHub Copilot 側の一時的なトークン使用量（レート）制限に引っかかっているだけです。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>実際、GitHub Copilot CLI の Issue や Community Discussion でも、有料ユーザーが普通に作業していて突然出るケースが多数報告されています。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">なぜトークン制限に引っかかるのか</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Copilot は「月額で使い放題」というイメージを持たれがちですが、実際には以下のような制限が存在します。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading" id="1-">1. 短時間で大量のトークンを消費した</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Copilot CLI や Agent モードでは、以下のような使い方をすると短時間で大量のトークンを消費します。</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>大きなファイルを丸ごと投げる</li>
<li>長いコンテキストを何度も送る</li>
<li>連続でコマンド補完・生成を走らせる</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>この「瞬間的な使用量」が一定値を超えると、一時ブロックされます。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading" id="2-">2. 月次上限とは別の「リアルタイム制限」がある</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>UI 上で見える使用量（Usage bar）が余っていても、バックエンドの内部カウンタやモデル別の上限、CLI / Agent 用の独立枠によって、見た目と実際の制限がズレることがあります。そのため「まだ余ってるはずなのに弾かれた」という現象が起きます。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading" id="3-copilot-cli">3. Copilot CLIは特に制限に当たりやすい</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>VS Code の通常の補完よりも、Copilot CLI は「1リクエストあたりの情報量が多い」「自動で何度も問い合わせる」という特性があり、制限に到達しやすい傾向があります。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>GitHub Copilot CLI Issue #793 でも、同様の報告が継続的に出ています。</p>
<!-- /wp:paragraph -->

<!-- wp:code -->
<pre class="wp-block-code"><code class="language-json">Model call failed: {&quot;message&quot;:&quot;Sorry, you have exceeded your Copilot token usage. Please review our Terms of Service.&quot;,&quot;code&quot;:&quot;rate_limited&quot;}</code></pre>
<!-- /wp:code -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">すぐできる対処法</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading" id="-">① 少し時間を置く（最重要）</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>この制限はほぼ確実に一時的です。</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>10分〜数十分</li>
<li>長くても数時間</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>待つだけで復活するケースが大半です。焦らず休憩しましょう。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading" id="-cli">② エディタやCLIを再起動する</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>内部セッションがリセットされることで、再びトークンが通るようになったり、エラーが消えたりすることがあります。「ダメ元で再起動」はわりと有効です。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading" id="-">③ 一度に投げるコンテキストを減らす</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>以下を意識すると、再発を防ぎやすくなります。</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>巨大ファイルをそのまま渡さない</li>
<li>差分や必要箇所だけ貼る</li>
<li>Agent に丸投げしすぎない</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>特に CLI 利用時は、プロンプトを小さく刻むのがコツです。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading" id="-">④ モードを切り替える</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Copilot のモードを切り替えることで通ることもあります。</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>Agent → Ask</li>
<li>CLI → エディタ補完</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">まとめ</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>このエラーは契約エラーではない</li>
<li>原因は一時的なトークン・レート制限</li>
<li>Copilot CLI / Agent 利用時に特に起きやすい</li>
<li>待つ・再起動・プロンプト軽量化が有効</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Copilot をガンガン使うほど遭遇しやすいエラーなので、「来たら落ち着いて休憩」くらいの感覚でOKです。</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"zdk_b_note"} -->
<div class="wp-block-group zdk_b_note">
<!-- wp:paragraph -->
<p>Gemini 3 Proで出やすいエラーかもしれません。</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:group {"className":"zdk_b_note"} -->
<div class="wp-block-group zdk_b_note">
<!-- wp:paragraph -->
<p>English Version:
<a href="https://www.zidooka.com/?p=2546">GitHub Copilot CLI Error: &#39;Sorry, you have exceeded your copilot token usage&#39; — Causes and Fixes</a></p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
