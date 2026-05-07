---
id: 4103
slug: "gemini-ai-studio-spend-cap-jp"
title: "Gemini APIで月額の費用上限を設定できるように。Google AI StudioのSpend Capsを確認"
status: "future"
date: "2026-03-26T18:00:00"
excerpt: "Gemini API / Google AI Studio に月額の費用上限 UI が追加されていた件を、公式 Billing docs とスクリーンショットをもとに整理します。"
link: "https://www.zidooka.com/?p=4103"
raw_html: true
---
<!-- wp:paragraph -->
<p>【結論】Gemini API は、<span class="zdk_i_code">2026-03-25</span> 時点で Google AI Studio から「1か月の費用の上限」を設定できる状態になっていました。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>少し前まで、Gemini API では「Budgets で通知はできても、自動的に止める上限は実質ない」という理解が一般的でした。実際、私も以前はその前提で <a href="https://www.zidooka.com/archives/2786">Gemini APIキーに課金の上限は設定できるのか</a> を整理していました。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>ところが今日 AI Studio を開いたところ、<span class="zdk_i_code">1か月の費用の上限</span> というカードと、<span class="zdk_i_code">費用の上限を設定</span> ダイアログが表示されていました。</p>
<!-- /wp:paragraph -->

<!-- wp:image {"id":4119,"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://www.zidooka.com/wp-content/uploads/2026/03/gemini-ai-studio-spend-cap-card-20260325-9.jpg" alt="Google AI Studio に表示された月額上限カード" class="wp-image-4119"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p>しかも、Google の公式ドキュメントにもすでに <span class="zdk_i_code">Spend caps</span> セクションが追加されています。<span class="zdk_i_code">Billing</span> ページの最終更新日は <span class="zdk_i_code">2026-03-23 UTC</span> でした。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">何が変わったのか</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>今回の変更点は、大きく分けると 2 つあります。</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li><span class="zdk_i_code">Project spend caps</span></li>
<li><span class="zdk_i_code">Billing account tier spend caps</span></li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>【ポイント】以前の「Budget alert は通知だけ」という状態から、少なくとも Gemini API / AI Studio 側で「月額の上限を持つ」方向へ仕様が明確に変わりました。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading" id="1-spend-cap">1. プロジェクト単位の spend cap</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>公式ドキュメントでは、AI Studio の <span class="zdk_i_code">Spend</span> ページからプロジェクト単位の月額上限を設定できると書かれています。これは <span class="zdk_i_code">Experimental</span> 扱いです。</p>
<!-- /wp:paragraph -->

<!-- wp:image {"id":4120,"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://www.zidooka.com/wp-content/uploads/2026/03/gemini-ai-studio-spend-cap-modal-20260325-4.jpg" alt="Google AI Studio の費用上限設定ダイアログ" class="wp-image-4120"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p>私の画面でも、<span class="zdk_i_code">1か月の費用の上限</span> に金額を入力する UI が見えていました。複数プロジェクトを同じ請求アカウントにぶら下げている人にはかなり便利です。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading" id="2-tier-spend-cap">2. 請求アカウント tier 側の spend cap</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>さらに、請求アカウント tier 側にも月額上限が入ります。こちらはユーザーが自由に決めるものではなく、tier ごとの固定値です。</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>Tier 1: <span class="zdk_i_code">$250</span></li>
<li>Tier 2: <span class="zdk_i_code">$2,000</span></li>
<li>Tier 3: <span class="zdk_i_code">$20,000 - $100,000+</span></li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>公式 docs では、これらの tier spend caps は <span class="zdk_i_code">2026-04-01</span> から適用開始予定で、UI はそれより前に見えるようにしていると説明されています。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">どこで設定するのか</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>現時点では、Google AI Studio の <span class="zdk_i_code">Spend</span> ページで設定する流れです。</p>
<!-- /wp:paragraph -->

<!-- wp:list {"ordered":true} -->
<ol>
<li>Google AI Studio を開く</li>
<li>課金済みプロジェクトを選ぶ</li>
<li><span class="zdk_i_code">Spend</span> ページを開く</li>
<li><span class="zdk_i_code">Monthly spend cap</span> から <span class="zdk_i_code">Edit spend cap</span> を押す</li>
<li>金額を入力して保存する</li>
</ol>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>【注意】これは API キー単位の課金設定ではありません。公式 docs でも、API キーは独立した billing 設定を持たず、プロジェクトと billing account の設定を継承すると明記されています。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">まだ注意が必要な点</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>便利になった一方で、完全に「1円も超えない魔法の停止」ではありません。</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>Batch mode は超過が発生する可能性がある</li>
<li>Billing データ処理には最大で約 10 分の遅延がありうる</li>
<li>プロジェクト cap と billing account cap の両方を見る必要がある</li>
<li>cap は「APIキーごと」ではなく「プロジェクトごと」</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>【注意】ドキュメントには、処理遅延のせいで project cap を少し超える可能性がある、と明記されています。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">これ、かなり大きい改善です</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>正直、これはかなり助かります。Gemini API は個人開発や検証用途でも使いやすい一方で、「ちょっと実験したいだけなのに、料金が青天井っぽくて怖い」という心理的ハードルがありました。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>今回の <span class="zdk_i_code">Spend caps</span> は、その不安をかなり下げます。</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>検証用プロジェクトだけ低い cap を置ける</li>
<li>本番用は別枠で管理できる</li>
<li>「通知しかない」状態よりずっと事故りにくい</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>以前の私の記事は「できない」が結論でしたが、少なくとも <span class="zdk_i_code">2026-03-25</span> 時点では、その結論は更新が必要です。</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading" id="">まとめ</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>【結論】Gemini API / Google AI Studio には、月額の費用上限を設定する仕組みが入り始めています。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>今回確認できたことは次のとおりです。</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>AI Studio 上に <span class="zdk_i_code">1か月の費用の上限</span> UI が表示された</li>
<li>公式 Billing docs に <span class="zdk_i_code">Spend caps</span> セクションが追加された</li>
<li>project-level cap は <span class="zdk_i_code">Experimental</span></li>
<li>billing account tier cap は <span class="zdk_i_code">2026-04-01</span> から適用予定</li>
<li>ただし遅延や batch mode による超過の可能性は残る</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>「Gemini API に課金上限がなくて怖い」と感じていた人にとっては、かなり前進です。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>References:</p>
<!-- /wp:paragraph -->

<!-- wp:list {"ordered":true} -->
<ol>
<li>Gemini API Billing
<a href="https://ai.google.dev/gemini-api/docs/billing">https://ai.google.dev/gemini-api/docs/billing</a></li>
<li>Gemini API Rate limits
<a href="https://ai.google.dev/gemini-api/docs/rate-limits">https://ai.google.dev/gemini-api/docs/rate-limits</a></li>
</ol>
<!-- /wp:list -->

