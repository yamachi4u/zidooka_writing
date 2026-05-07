---
title: "【即解決】VS Codeで「&#8221;princexml&#8221; is required to be installed.」が出る原因と対処法"
slug: vs-codemarkdown-preview-enhanced%e3%81%a7pdf%e5%87%ba%e5%8a%9b%e3%81%97%e3%82%88%e3%81%86%e3%81%a8%e3%81%97%e3%81%9f%e5%a0%b4%e5%90%88%e3%81%ae%e3%82%a8%e3%83%a9%e3%83%bc%e2%80%95%e2%80%95princexml
date: 2024-06-08T15:30:00
categories:
  - "copiloterro"
  - "errors"
  - "%e3%81%9d%e3%81%ae%e3%81%bb%e3%81%8b-errors"
tags: []
status: publish
id: 105
---


<p>Markdown Preview Enhanced で PDF を生成しようとすると、<br>“princexml is required to be installed” とだけ表示されて処理が止まってしまうことがあります。</p>



<p>私の環境（Windows 11 / VS Code）でも 2024年6月にまったく同じエラーが発生しましたが、<br>結論として「PrinceXML を手動インストールするだけ」で問題は解消しました。</p>



<p>この記事では、公式ドキュメントでは分かりづらい<br>「どこからダウンロードするのか」「実際に出る画面はどういうものか」<br>を実体験ベースで整理して解説します。</p>



<h3 class="wp-block-heading">関連する AI / VS Code エラー</h3>



<ul class="wp-block-list">
<li><a href="https://www.zidooka.com/archives/240">Cursor の high demand エラー</a></li>



<li><a href="https://www.zidooka.com/archives/2755">Copilot token usage / rate_limited</a></li>



<li><a href="https://www.zidooka.com/archives/411">VS Code Copilot エージェントモードのリクエスト制限</a></li>



<li><a href="https://www.zidooka.com/archives/1965">AIエラーまとめページ</a></li>
</ul>



<h2 class="wp-block-heading">忙しい人用</h2>



<p>↓これインストールしたらOKです（たぶん、多くの場合）。</p>



<p><a href="https://www.princexml.com/download/15">https://www.princexml.com/download/15</a></p>



<figure class="wp-block-image size-large"><img loading="lazy" decoding="async" width="1024" height="608" src="https://www.zidooka.com/wp-content/uploads/2024/06/image-9-1024x608.png" alt="" class="wp-image-3178" srcset="https://www.zidooka.com/wp-content/uploads/2024/06/image-9-1024x608.png 1024w, https://www.zidooka.com/wp-content/uploads/2024/06/image-9-300x178.png 300w, https://www.zidooka.com/wp-content/uploads/2024/06/image-9-768x456.png 768w, https://www.zidooka.com/wp-content/uploads/2024/06/image-9.png 1405w" sizes="auto, (max-width: 1024px) 100vw, 1024px" /></figure>



<p><a href="https://www.princexml.com/download/15/" title="↑ダウンロード">↑ダウンロード</a>する</p>



<figure class="wp-block-image size-full"><img loading="lazy" decoding="async" width="400" height="142" src="https://www.zidooka.com/wp-content/uploads/2024/06/image-10.png" alt="" class="wp-image-3179" srcset="https://www.zidooka.com/wp-content/uploads/2024/06/image-10.png 400w, https://www.zidooka.com/wp-content/uploads/2024/06/image-10-300x107.png 300w" sizes="auto, (max-width: 400px) 100vw, 400px" /></figure>



<figure class="wp-block-image size-full"><img loading="lazy" decoding="async" width="826" height="649" src="https://www.zidooka.com/wp-content/uploads/2024/06/image-11.png" alt="" class="wp-image-3181" srcset="https://www.zidooka.com/wp-content/uploads/2024/06/image-11.png 826w, https://www.zidooka.com/wp-content/uploads/2024/06/image-11-300x236.png 300w, https://www.zidooka.com/wp-content/uploads/2024/06/image-11-768x603.png 768w" sizes="auto, (max-width: 826px) 100vw, 826px" /></figure>



<p>.NET Framework 3.5のインストールをさせられるかもしれないですが、OKを押していけばいいです。</p>



<figure class="wp-block-image size-full"><img loading="lazy" decoding="async" width="831" height="642" src="https://www.zidooka.com/wp-content/uploads/2024/06/image-12.png" alt="" class="wp-image-3182" srcset="https://www.zidooka.com/wp-content/uploads/2024/06/image-12.png 831w, https://www.zidooka.com/wp-content/uploads/2024/06/image-12-300x232.png 300w, https://www.zidooka.com/wp-content/uploads/2024/06/image-12-768x593.png 768w" sizes="auto, (max-width: 831px) 100vw, 831px" /></figure>



<p>機種によっては5分近くかかるかもしれないです。</p>



<hr class="wp-block-separator has-alpha-channel-opacity"/>



<p></p>



<h2 class="wp-block-heading">以下、詳しい解説</h2>



<p>Visual Studio CodeのMarkdown Preview Enhanced拡張機能を使って、PDFを出力しようとしたところ、「&#8221;princexml&#8221; is required to be installed.」が出てしまいました。調べてもトラブルシュートが以下の記事しかでてこなかったので、私のトラブルシュートも記しておきます。</p>



<p></p>



<p>Cf. 「Markdown Preview Enhancedでprinceを利用してPDF化するときに日本語が文字化けする」<a href="https://qiita.com/rrr/items/9b938f7b58d609f32e62">https://qiita.com/rrr/items/9b938f7b58d609f32e62</a></p>



<figure class="wp-block-image size-full"><img loading="lazy" decoding="async" width="462" height="183" src="https://www.zidooka.com/wp-content/uploads/2024/06/4fa4ef95cf43ec72d1c1ce384b6d826e-1.png" alt="" class="wp-image-108" srcset="https://www.zidooka.com/wp-content/uploads/2024/06/4fa4ef95cf43ec72d1c1ce384b6d826e-1.png 462w, https://www.zidooka.com/wp-content/uploads/2024/06/4fa4ef95cf43ec72d1c1ce384b6d826e-1-300x119.png 300w" sizes="auto, (max-width: 462px) 100vw, 462px" /></figure>



<h3 class="wp-block-heading">環境</h3>



<ul class="wp-block-list">
<li>Windows 11</li>



<li>Visual Studio Code</li>
</ul>



<h2 class="wp-block-heading"><strong>原因</strong></h2>



<p>Markdown Preview Enhanced で PDF を生成する際、<br>内部的に PrinceXML を使用する設定になっている場合、<br>PC に PrinceXML 本体がインストールされていないとこのエラーが出ます。</p>



<p>VS Code や拡張機能側の不具合ではなく、<br>単純に PrinceXML が未インストールなことが原因です。</p>



<h2 class="wp-block-heading">トラブルシュート</h2>



<p>　簡単に言えば、こちらに行って、ダウンロードして、インストールすれば解決します。先ほどの記事にあるようにパッケージマネージャで取得する必要はありません。</p>



<p><a href="https://www.princexml.com/download/15">https://www.princexml.com/download/15</a></p>



<figure class="wp-block-image size-full"><img loading="lazy" decoding="async" width="737" height="556" src="https://www.zidooka.com/wp-content/uploads/2024/06/image-4.png" alt="" class="wp-image-107" srcset="https://www.zidooka.com/wp-content/uploads/2024/06/image-4.png 737w, https://www.zidooka.com/wp-content/uploads/2024/06/image-4-300x226.png 300w" sizes="auto, (max-width: 737px) 100vw, 737px" /></figure>



<p>　私の環境だと.Netframeworkのインストールするように言われました。</p>



<hr class="wp-block-separator has-alpha-channel-opacity"/>



<p>なので、結論として、PrinceXML を公式サイトからダウンロードしてインストールするだけで解決します。<br>パッケージマネージャでの導入は不要です。</p>



<h2 class="wp-block-heading">解決手順</h2>



<ol class="wp-block-list">
<li>PrinceXML 公式ページを開く<br><a href="https://www.princexml.com/download/15">https://www.princexml.com/download/15</a></li>



<li>Windows 用インストーラをダウンロード</li>



<li>インストールを実行<br>※ 私の環境では、途中で .NET Framework の追加インストールが必要でした。</li>



<li>VS Code を再起動し、再度 PDF 出力を実行</li>
</ol>



<p>以上でエラーは解消され、PDF が正常に生成されるようになりました。</p>



<p>おすすめ商品：<br><a href="https://amzn.to/3XP0ahc" title="">by Amazon ナチュラルミネラルウォーター ラベルレス 500ml 36本</a></p>



<hr class="wp-block-separator has-alpha-channel-opacity"/>



<ol class="wp-block-list">
<li>Markdown Preview Enhancedでprinceを利用してPDF化するときに日本語が文字化けする<br><a href="https://qiita.com/rrr/items/9b938f7b58d609f32e62">https://qiita.com/rrr/items/9b938f7b58d609f32e62</a></li>



<li>PrinceXML 公式ダウンロードページ<br><a href="https://www.princexml.com/download/15">https://www.princexml.com/download/15</a></li>
</ol>



<h2 class="wp-block-heading">QandA</h2>



<p><strong>Q. パッケージマネージャ（npm / choco / winget）は必要？</strong><br>→ 不要。公式インストーラでOK。</p>



<p><strong>Q. VS Code 側の設定変更は必要？</strong><br>→ 基本不要。再起動のみ。</p>



<p></p>



<h2 class="wp-block-heading">環境</h2>



<p>Windows 11 Pro 23H2<br>Visual Studio Code<br>Markdown Preview Enhanced</p>



<h2 class="wp-block-heading">備考</h2>



<p>なお、このエラーは<br>&#8220;princexml is required to be installed&#8221;<br>&#8220;princexml vscode&#8221;<br>などの文言で検索されることが多いようですが、<br>原因と対処法はすべて同じです。</p>
