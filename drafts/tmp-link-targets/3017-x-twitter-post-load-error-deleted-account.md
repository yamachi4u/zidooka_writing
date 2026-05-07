---
id: 3017
slug: "x-twitter-post-load-error-deleted-account"
title: "X(Twitter)で「ポストを読み込めません」と出る原因は相手のアカウント削除か鍵化です"
status: "publish"
date: "2025-12-26T18:38:33"
excerpt: ""
link: "https://www.zidooka.com/archives/3017"
raw_html: true
---
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">はじめに</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>X（旧Twitter）のアプリで特定のポスト（ツイート）を開こうとしたときに、以下のエラーが表示されて見られないことがあります。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>X 全体の障害でタイムライン自体がおかしい場合は <a href="https://www.zidooka.com/archives/3290">「Something went wrong. Try reloading.」</a>、Android アプリで取得失敗が出る場合は <a href="https://www.zidooka.com/archives/633">「現在、ポストを取得できません」</a> の記事を見たほうが近いです。</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote">
<p>ポストを読み込めません
やりなおす</p>
</blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph -->
<p>または</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote">
<p>非公開アカウントのためこのポストは表示できません</p>
</blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph -->
<p>「やりなおす」ボタンを何度押しても反応がなく、通信環境を変えても直らない。
これは、あなた側のアプリや通信の問題ではありません。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">結論：相手のアカウントが消えているか、鍵垢になっています</h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"zdk_b_conclusion"} -->
<div class="wp-block-group zdk_b_conclusion">
<!-- wp:paragraph -->
<p>この表示が出る原因は、以下の3つのいずれかです。</p>
<!-- /wp:paragraph -->

<!-- wp:list {"ordered":true} -->
<ol>
<li>相手がアカウントを削除した</li>
<li>相手がアカウントを非公開（鍵垢）にした</li>
<li>相手のアカウントが凍結された</li>
</ol>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>つまり、「ポストのURL自体は過去に存在していたが、現在は表示する権限がない（または実体がない）」 という状態です。</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading" id="">エラーメッセージの罠</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Xの仕様として、これらの状況をすべて「読み込めません」や「非公開アカウントのため〜」というメッセージでまとめて表示することがあります。
特に「非公開アカウントのため」と言われると、「相手が鍵垢にしたのかな？」と思いがちですが、実際にはアカウント削除や凍結の場合でもこのメッセージが出ることがあります。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">なぜ「やりなおす」で直らないのか</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>このエラーが出ているとき、サーバー側からは 403 Forbidden（権限なし） や 404 Not Found（見つからない） という明確な拒否レスポンスが返ってきています。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>アプリ側でいくら「やりなおす（再試行）」を行っても、サーバー側に実体がない、あるいは見せる気がないため、絶対に表示されることはありません。
一時的な通信エラーではないため、時間を置いても復活することは（相手がアカウントを復活させない限り）ありません。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">似ているエラーとの違い</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>よく似たエラーに、「現在、ポストを取得できません」 というものがあります。
こちらは一時的な読み込み失敗やキャッシュの問題であることが多く、再読み込みで直る可能性があります。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>詳しい直し方は以下の記事で解説しています。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="https://www.zidooka.com/archives/633">【Android】X(Twitter)で「現在、ポストを取得できません」と表示される原因と対処法｜読み込みエラーの直し方</a></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading" id="">見分け方</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">まとめ</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>「ポストを読み込めません」と出て、再読み込みしても直らない場合は、相手側の事情（削除・鍵化・凍結） で確定です。
あなたのスマホやアプリの不具合ではないので、設定をいじったり再インストールしたりする必要はありません。</p>
<!-- /wp:paragraph -->
