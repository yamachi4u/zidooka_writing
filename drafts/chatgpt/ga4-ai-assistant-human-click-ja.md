---
title: "GA4に突然出てきた「AI Assistant」って何？"
status: publish
slug: ga4-ai-assistant-human-click
tags:
  - GA4
  - Google Analytics
  - AI
  - アクセス解析
---

最近、GA4のチャネルに「AI Assistant」が表示されるようになりました。これは、ChatGPTやGeminiなどと自分のサイトが特別に統合されたという意味なのでしょうか。

結論からいうと、サイト側で新しい設定をしたわけではありません。GoogleがGA4の標準チャネルグループに追加した分類です。

:::conclusion
「AI Assistant」は、AIサービスからサイトへ送られた訪問を、GA4が自動的に分類したチャネルです。ChatGPTなどとのAPI連携や、AIがサイトを読みに来たことを直接示すものではありません。
:::

## 何を見て分類しているのか

AIサービスの画面やページからサイトへのリンクがクリックされると、ブラウザからサイトへリファラ（直前に見ていたページの情報）が送られることがあります。

GA4は、その参照元が認識済みのAIサービスである場合、通常のReferralではなく、次のように分類します。

- チャネル：AI Assistant
- メディア：ai-assistant
- キャンペーン：(ai-assistant)

Googleの公式説明では、ChatGPT、Gemini、DeepSeek、Copilot、Grokなどが例として挙げられています。対象サービスの一覧や判定方法は今後変わる可能性があります。

## 「AIが見に来た」ということ？

ここは少し分けて考える必要があります。

通常のAIクローラーがサイトを巡回しただけなら、GA4の「AI Assistant」に入るとは限りません。GA4は、サイトに埋め込まれた計測タグが実行され、ページビューなどのイベントが送信されて初めてアクセスを記録する仕組みだからです。検索用クローラーがHTMLを取得しただけでは、通常の人間向けブラウザアクセスと同じ計測イベントは発生しません。

一方、AIサービスの回答画面からリンクをクリックしてサイトを開いた場合は、GA4に記録される可能性があります。この場合でも、実際にクリックしたのが人間なのか、ブラウザを操作するAIエージェントなのかまでは、GA4の「AI Assistant」だけでは分かりません。

:::note
GA4が分かるのは「認識されたAIサービスを参照元とする計測可能な訪問」です。「AIがサイトの内容を学習した」「AIがサイトをクロールした」「人間がクリックした」といったことまで直接証明する数字ではありません。
:::

## どのAIから来たのかを見る方法

GA4の「トラフィック獲得」で、チャネルを「AI Assistant」に絞ります。そのうえでディメンションを「セッションの参照元／メディア」に変更すると、どのAIから来たかを確認できます。

- chatgpt.com / ai-assistant
- gemini.google.com / ai-assistant
- claude.ai / ai-assistant

## 数字を見るときの注意

- AIアプリやアプリ内ブラウザでリファラが送られない場合は、Directになることがあります
- URLをコピーして直接開いた場合も、AI経由とは分からなくなります
- GoogleのAI OverviewsやAI Modeは、AI AssistantではなくOrganic Searchに分類されます
- Googleがまだ認識していないサービスはReferralなどに入る可能性があります
- 新しい分類は過去のアクセスに遡って適用されるとは限りません

:::warning
「AI Assistantが少ないから、AIからサイトを見られていない」とは判断できません。GA4で見えるのは、参照元と計測タグがそろった訪問だけです。
:::

## まとめ

GA4の「AI Assistant」は、GoogleがAI経由のクリックを独立した流入チャネルとして見せ始めたものです。

ただし、その数字が表しているのは「AIによるサイト訪問」ではなく、より正確には「AIサービスを参照元として計測されたサイト訪問」です。AIが回答を作るためにクロールした回数や、AIがサイトの内容を知っているかどうかを測る機能ではありません。

サイトへの流入を確認するなら、AI Assistantだけでなく、Referral、Direct、Organic Search、サーバーログ、必要に応じてSearch Consoleも合わせて見るのがよさそうです。

参考：[Google Analyticsのデフォルト チャネル グループ](https://support.google.com/analytics/answer/9756891?hl=ja)／[Google Analyticsの最新情報](https://support.google.com/analytics/answer/9164320?hl=ja)
