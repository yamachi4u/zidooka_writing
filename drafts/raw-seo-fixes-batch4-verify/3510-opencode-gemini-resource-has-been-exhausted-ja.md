---
id: 3510
slug: "opencode-gemini-resource-has-been-exhausted-ja"
title: "OpenCode-Geminiで『Resource has been exhausted』が出る原因と対処法（APIキー/課金/レート制限）"
status: "publish"
date: "2026-01-21T21:20:00"
excerpt: "【結論】OpenCode-Geminiの「Resource has been exhausted」は、ほとんどの場合「使用量（クォータ）・課金上限・レート制限（同時実行含む）」のいずれかで“使い切り/上限到達”している状態です。 この記事で扱うこと ----------------…"
link: "https://www.zidooka.com/archives/3510"
raw_html: true
---
<!-- wp:paragraph -->
<p>【結論】OpenCode-Geminiの「Resource has been exhausted」は、ほとんどの場合「使用量（クォータ）・課金上限・レート制限（同時実行含む）」のいずれかで“使い切り/上限到達”している状態です。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">この記事で扱うこと</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>OpenCode-Gemini（Gemini API キー利用）で、次のようなエラーが繰り返し出て止まるケースの原因と対処をまとめます。</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote">
<p>Resource has been exhausted (e.g. check quota). [retrying in 46s]</p>
</blockquote>
<!-- /wp:quote -->

<!-- wp:image {"id":3512,"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://www.zidooka.com/wp-content/uploads/2026/01/image-copy-14-3.jpg" alt="エラー画面（例）" class="wp-image-3512"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">エラーの意味（ざっくり）</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>「リソースを使い切りました」という直訳どおりで、APIが“これ以上処理できない（または処理してはいけない）”状態です。よくある内訳は次のとおりです。</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li><ol>
<li>クォータ（使用量）を使い切った</li>
</ol>
</li>
<li><ol start="2">
<li>課金（Billing）の上限/支払い/残高の問題で止められている</li>
</ol>
</li>
<li><ol start="3">
<li>レート制限（リクエスト数、トークン、同時実行数）に当たっている</li>
</ol>
</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>【ポイント】「少し待てば直る」場合もありますが、何度も出続けるなら“待つ”より“上限の種類を特定して対処”が近道です。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Gemini API の課金キャップ可否は <a href="https://www.zidooka.com/archives/2786">日本語版</a> と <a href="https://www.zidooka.com/archives/2794">英語版</a>、Gemini 本体の Error (5) は <a href="https://www.zidooka.com/archives/621">日本語版</a> と <a href="https://www.zidooka.com/archives/623">英語版</a> があります。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">よくある原因</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="1-">原因1: 課金・残高・上限の問題</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>一定額（例: 1日/1ヶ月の上限）を設定していたり、想定より早く消費したりすると発生します。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>【対処】Gemini API を管理しているコンソールで「使用量」「請求（Billing）」「上限（Limits）」を確認し、上限を引き上げるか、別のキー/プロジェクトに切り替えます。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="2-">原因2: レート制限（同時実行を含む）</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>OpenCode側が複数リクエストを並列で投げている、あるいは短い間隔で再試行していると、レート制限に当たり続けて“実質的に永久ループ”に見えることがあります。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>【対処】</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>同時実行数を下げる（並列を1〜2にする）</li>
<li>リトライは指数バックオフ + ジッターにする（例: 2s→4s→8s…、上限回数も設定）</li>
<li>失敗時にモデルを軽いものへフォールバックする（短期的に通りやすくなることがある）</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="3-api">原因3: APIキーの紐付け/権限/プロジェクト違い</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>同じキーを長く使っていると、プロジェクトを切り替えた、権限が変わった、上限設定が変わったなどで突然止まることもあります。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>【対処】</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>OpenCodeが参照しているAPIキーが“意図したもの”か再確認</li>
<li>可能なら新しいキーを作って差し替えて挙動を見る</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">すぐできるチェックリスト</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>【ポイント】まずは「クォータ/課金」→「レート制限」→「キー差し替え」の順で潰すと早いです。</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>コンソールで使用量・請求ステータス・上限（Limits）を確認</li>
<li>OpenCodeの同時実行数を下げる</li>
<li>リトライ設定（待ち時間、最大回数）を確認</li>
<li>別のAPIキーに差し替えて再現するか確認</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">再発防止（運用のコツ）</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>予算上限（Budget/Limit）に到達する前にアラートを設定</li>
<li>高コストモデルは“必要な場面だけ”使う（普段は軽いモデルに寄せる）</li>
<li>失敗ログ（日時・モデル・推定トークン・並列数）を残して原因切り分けを速くする</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">まとめ</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>【結論】「Resource has been exhausted」は“API側の上限”が原因なので、まずは「クォータ/課金/レート制限」のどれに当たっているかを確認し、上限設定・並列・リトライ方式を調整するのが最短です。</p>
<!-- /wp:paragraph -->
