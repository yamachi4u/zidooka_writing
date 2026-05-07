---
id: 1219
slug: "gemini-3-pro-%e3%81%a7%e3%80%8cserver-error-stream-terminated%e3%80%8d%e3%81%8c%e5%87%ba%e3%81%9f%e3%81%a8%e3%81%8d%e3%81%ae%e5%8e%9f%e5%9b%a0%e3%81%a8%e3%80%81claude-sonnet-4-5-%e3%81%ab%e5%88%87"
title: "Gemini 3 Pro で「Server error. Stream terminated」が出たときの原因と、Claude Sonnet 4.5 に切り替えて解決した話【2025年実環境】"
status: "publish"
date: "2025-12-17T12:25:42"
excerpt: ""
link: "https://www.zidooka.com/archives/1219"
raw_html: true
---
<!-- wp:paragraph -->
<p>GitHub Copilot を使っていると、モデルを切り替えた瞬間にエラーが出て作業が止まることがあります。とくに、2025年12月時点の <strong>Gemini 3 Pro（Preview）</strong> は、環境によって「Server error. Stream terminated」が発生しやすい印象です。今回は、<strong>Windows / Chrome / GitHub Copilot Chat</strong> という実際の作業環境で遭遇したケースをもとに、原因と対処法を分かりやすくまとめました。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Copilot のエラー全体を見渡したい場合は <a href="https://www.zidooka.com/archives/2672">Copilotエラーまとめ</a>、502 系なら <a href="https://www.zidooka.com/archives/2665">Server error: 502</a>、英語版の 502 記事は <a href="https://www.zidooka.com/archives/2668">こちら</a> です。</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2 class="wp-block-heading">◆ 発生したエラー内容</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>実際に表示されたのが次のメッセージです。</p>
<!-- /wp:paragraph -->

<!-- wp:code -->
<pre class="wp-block-code"><code>Sorry, your request failed. Please try again.
Reason: Server error. Stream terminated
Copilot Request id: XXXX
GH Request id: XXXX


</code></pre>
<!-- /wp:code -->

<!-- wp:image {"id":1220,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="https://www.zidooka.com/wp-content/uploads/2025/12/67acddd1-5432-4e61-a2f4-8add90da1ed1.png" alt="" class="wp-image-1220"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p>Gemini 3 Pro に切り替えた直後のリクエストで、ほぼ毎回発生しました。コード修正中などに突然起きるため、作業が途切れがちになるタイプのエラーです。</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2 class="wp-block-heading">◆ なぜ発生するのか</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>同じ動作をしても、モデルによって安定性が大きく異なります。今回のケースを分析すると、主な理由は次のとおりでした。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">1. モデル切り替え時にストリームが不安定になる</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Gemini 3 Pro は Preview 段階で、切り替え直後の初回リクエストでストリーム確立に失敗しやすい傾向があります。<br>Copilot Chat の内部ではセッションを再構築しますが、ここが不安定だと途中で通信が切断され、上記のエラーになります。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">2. 大きめのコンテキストを渡すと落ちやすい</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>コード量の多いファイルや長文依頼を投げると、さらにストリームが途切れやすくなります。高速に連投していく作業スタイルの場合、とくに起きやすい現象です。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">3. Copilot 側のサーバー負荷による影響</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>同じ時間帯に似た報告が増えることがあり、サーバー混雑時にはエラー率が上昇することがあります。環境依存よりも <strong>サーバー側状態</strong> による影響のほうが大きい印象です。</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2 class="wp-block-heading">◆ 実際の解決方法：Claude Sonnet 4.5 に切り替えたら即復旧</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>今回もっとも効果的だったのが、<strong>モデルを Claude Sonnet 4.5 に変更すること</strong>でした。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>モデル切り替え後、同じ内容を再実行してもエラーは一度も発生せず、スムーズにコード修正が進みました。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>安定性の体感としては次のとおりです。</p>
<!-- /wp:paragraph -->

<!-- wp:table -->
<figure class="wp-block-table"><table class="has-fixed-layout"><thead><tr><th>モデル</th><th>安定性</th><th>コメント</th></tr></thead><tbody><tr><td>GPT-4o 系</td><td>◎</td><td>もっとも安定している</td></tr><tr><td>Claude Sonnet 4.5</td><td>○</td><td>高速かつ扱いやすい</td></tr><tr><td>Gemini 3 Pro (Preview)</td><td>△</td><td>初回リクエストで落ちやすい</td></tr></tbody></table></figure>
<!-- /wp:table -->

<!-- wp:paragraph -->
<p>Preview モデルが不安定なのはよくあることで、今回のように <strong>同クラスの別モデルに切り替える</strong> のが最も手軽で再現性の高い対処法でした。</p>
<!-- /wp:paragraph -->

<!-- wp:image {"id":1221,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="https://www.zidooka.com/wp-content/uploads/2025/12/image-117.png" alt="" class="wp-image-1221"/></figure>
<!-- /wp:image -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2 class="wp-block-heading">◆ すぐできる対処法まとめ</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>同じエラーが出るときは、以下の手順が効果的です。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">1. モデルを変える（Claude Sonnet 4.5 or GPT-4o 系へ）</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>もっとも確実で、今回も即解決しました。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">2. モデル変更後は一度だけ軽いメッセージを送る</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>「hi」程度で OK。セッションを安定化させる効果があります。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">3. 長文依頼は、最初に短い依頼に分ける</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Preview モデルは長文初回で落ちやすいため、負荷を軽減できます。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">4. ブラウザ版の場合、タブ再読み込みで復帰する</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>VS Code Web 使用時は特に有効です。</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2 class="wp-block-heading">◆ まとめ</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Gemini 3 Pro に切り替えたときに発生する「Server error. Stream terminated」は、環境の問題というより <strong>AIモデル側のセッション不安定・サーバー負荷</strong> が原因でした。Preview モデルではよくある症状で、今回のように <strong>Claude Sonnet 4.5 に変更するだけ</strong> で問題なく作業を継続できます。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>同じエラーに悩んでいる人は、まずモデル変更を試してみるとスムーズです。</p>
<!-- /wp:paragraph -->
