# 週次統合レポート (2026-06-05)

## データソース

| チャネル | ステータス |
|---------|----------|
| GA4 Overview | OK |
| GA4 Acquisition | OK |
| GA4 Countries | OK |
| GSC Top Queries | OK |
| AdSense Daily | FAIL |
| AdSense RPM by Platform | FAIL |
| Bing Rank & Traffic | OK |
| Bing Crawl Stats | OK |
| PostHog A/B | OK |

## サマリー

### 検索エンジントラフィック
- **Bing**: 3563 sessions
- **Google**: 2514 sessions

### Bing クロール
- **最終日**: 2026-06-04
- **クロール数**: 463
- **エラー数**: 528 (53.3%)

### PostHog A/B
- **Active flag**: zdk_font_size
- **Null rate**: 🔴 41.5%
- **Recommendation**: `fix_null_rate` — Null rate 41.5% exceeds threshold 30.0%. Fix instrumentation before deciding.


Full report: `daily/posthog/2026-06-05.md`

---

## GA4 Overview
```
GA4 property 344037190
Date range: 28daysAgo -> yesterday
sessionDefaultChannelGroup | sessions | totalUsers | screenPageViews | engagedSessions
---------------------------|----------|------------|-----------------|----------------
Organic Search             | 6615     | 5705       | 10262           | 3996           
Direct                     | 1233     | 1125       | 1384            | 186            
Unassigned                 | 201      | 175        | 573             | 97             
Referral                   | 129      | 76         | 158             | 52             
Organic Social             | 37       | 22         | 67              | 21
```

## GA4 Acquisition
```
GA4 property 344037190
Date range: 28daysAgo -> yesterday
sessionSourceMedium     | sessions | totalUsers | engagedSessions | screenPageViews
------------------------|----------|------------|-----------------|----------------
bing / organic          | 3563     | 2974       | 2195            | 5748           
google / organic        | 2514     | 2099       | 1449            | 2911           
(direct) / (none)       | 1233     | 1125       | 186             | 1384           
yahoo / organic         | 278      | 241        | 175             | 313            
openai / organic        | 196      | 189        | 164             | 915            
duckduckgo / organic    | 156      | 135        | 82              | 280            
chatgpt.com / (not set) | 71       | 68         | 31              | 173            
openai / (not set)      | 67       | 61         | 49              | 338            
t.co / referral         | 35       | 20         | 21              | 65             
cn.bing.com / referral  | 32       | 24         | 12              | 27             
ecosia.org / organic    | 30       | 25         | 19              | 40             
(not set)               | 29       | 28         | 0               | 4              
copilot.com / referral  | 29       | 7          | 6               | 35             
chatgpt.com / referral  | 20       | 18         | 11              | 23             
doubao.com / referral   | 19       | 8          | 6               | 29
```

## GSC Top Queries
```
GSC site https://www.zidooka.com/
Date range: 2026-05-08 -> 2026-06-05
query                                                                                                                                                          | page                                  | clicks | impressions | ctr    | position
---------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------|--------|-------------|--------|---------
copilot has been working on this problem for a while. it can continue to iterate, or you can send a new message to refine your prompt. configure max requests. | https://www.zidooka.com/archives/411  | 70     | 156         | 44.87% | 1.60    
実行に失敗しました。rhino ランタイムは非推奨となり、サポートが終了しました。                                                                                                                      | https://www.zidooka.com/archives/2838 | 51     | 191         | 26.70% | 2.19    
"princexml" is required to be installed.                                                                                                                       | https://www.zidooka.com/archives/105  | 43     | 73          | 58.90% | 2.21    
files.oaiusercontent.com にアップロードできませんでした。ネットワーク設定でこのサイトへのアクセスを許可するか、ネットワーク管理者に問い合わせてください。                                                                      | https://www.zidooka.com/archives/185  | 38     | 162         | 23.46% | 2.57    
princexml                                                                                                                                                      | https://www.zidooka.com/archives/105  | 35     | 124         | 28.23% | 3.77    
files.oaiusercontent.com にアップロードできませんでした。ネットワーク設定でこのサイトへのアクセスを許可するか、ネットワーク管理者に問い合わせてください。                                                                      | https://www.zidooka.com/archives/586  | 32     | 162         | 19.75% | 4.23    
edgesuite                                                                                                                                                      | https://www.zidooka.com/archives/2590 | 23     | 99          | 23.23% | 1.87    
princexml is required to be installed                                                                                                                          | https://www.zidooka.com/archives/105  | 23     | 36          | 63.89% | 3.28    
copilot has been working on this problem for a while. it can continue to iterate, or you can send a new message to refine your prompt.                         | https://www.zidooka.com/archives/411  | 20     | 52          | 38.46% | 1.88    
ポストを読み込めません 垢消し                                                                                                                                                | https://www.zidooka.com/archives/3017 | 20     | 82          | 24.39% | 2.22    
チャットgpt オフラインになっているようです                                                                                                                                        | https://www.zidooka.com/archives/443  | 18     | 90          | 20.00% | 2.00    
rhino ランタイムは非推奨となり、サポートが終了しました。                                                                                                                                | https://www.zidooka.com/archives/2838 | 17     | 55          | 30.91% | 2.02    
個の画像を分析しています                                                                                                                                                   | https://www.zidooka.com/archives/1083 | 16     | 51          | 31.37% | 1.22    
x something went wrong                                                                                                                                         | https://www.zidooka.com/archives/3290 | 15     | 125         | 12.00% | 3.43    
dns probe finished no internet                                                                                                                                 | https://www.zidooka.com/archives/2770 | 14     | 62          | 22.58% | 4.92    
we're experiencing high demand for the selected model right now. please upgrade to pro, switch to auto, another model, or try again in a few moments.          | https://www.zidooka.com/archives/240  | 14     | 87          | 16.09% | 3.48    
errors.edgesuite.net                                                                                                                                           | https://www.zidooka.com/archives/2590 | 13     | 35          | 37.14% | 1.23    
chatgpt オフラインになっているようです                                                                                                                                        | https://www.zidooka.com/archives/443  | 11     | 50          | 22.00% | 1.92    
edgesuite.net とは                                                                                                                                               | https://www.zidooka.com/archives/2590 | 10     | 50          | 20.00% | 2.02    
https://errors.edgesuite.net/                                                                                                                                  | https://www.zidooka.com/archives/2590 | 10     | 43          | 23.26% | 2.16    
princexml vscode                                                                                                                                               | https://www.zidooka.com/archives/105  | 10     | 15          | 66.67% | 2.33    
something went wrong while generating the response. if this issue persists please contact us through our help center at help.openai.com.                       | https://www.zidooka.com/archives/121  | 10     | 864         | 1.16%  | 7.15    
ポストを読み込めません アカウント削除                                                                                                                                            | https://www.zidooka.com/archives/3017 | 10     | 42          | 23.81% | 2.38    
ポストを読み込めません 特定の人                                                                                                                                               | https://www.zidooka.com/archives/4287 | 10     | 1095        | 0.91%  | 6.17    
https://errors.edgesuite.net                                                                                                                                   | https://www.zidooka.com/archives/2590 | 9      | 28          | 32.14% | 2.21    
copilot has been working on this problem for a while. it can continue to iterate, or you can send a new message to refine your prompt                          | https://www.zidooka.com/archives/411  | 8      | 21          | 38.10% | 1.29    
errors.edgesuite.net とは                                                                                                                                        | https://www.zidooka.com/archives/2590 | 8      | 40          | 20.00% | 1.82    
something went wrong. try reloading.                                                                                                                           | https://www.zidooka.com/archives/3290 | 8      | 159         | 5.03%  | 3.76    
ダイソー ワイヤレスイヤホン 700円                                                                                                                                            | https://www.zidooka.com/archives/1060 | 8      | 49          | 16.33% | 8.22    
edgesuite.net                                                                                                                                                  | https://www.zidooka.com/archives/2590 | 7      | 27          | 25.93% | 1.30
```

## AdSense Daily
```

```

## AdSense RPM by Platform
```

```

## Bing Rank & Traffic
```
Bing Webmaster — Rank & Traffic Stats
Site: https://www.zidooka.com/
Date       | Impressions | Clicks
-----------|-------------|-------
2026-05-14 | 3946        | 160   
2026-05-15 | 2465        | 78    
2026-05-16 | 1694        | 74    
2026-05-17 | 3964        | 138   
2026-05-18 | 4001        | 174   
2026-05-19 | 5416        | 175   
2026-05-20 | 3493        | 158   
2026-05-21 | 2786        | 136   
2026-05-22 | 1606        | 81    
2026-05-23 | 1066        | 58    
2026-05-24 | 2517        | 126   
2026-05-25 | 3001        | 142   
2026-05-26 | 3313        | 146   
2026-05-27 | 3050        | 123   
2026-05-28 | 3568        | 137   
2026-05-29 | 2751        | 107   
2026-05-30 | 1628        | 51    
2026-05-31 | 4626        | 168   
2026-06-01 | 4904        | 163   
2026-06-02 | 4470        | 123
```

## Bing Crawl Stats
```
Bing Webmaster — Crawl Stats
Site: https://www.zidooka.com/
Date       | CrawledPages | CrawlErrors
-----------|--------------|------------
2026-05-16 | 116          | 746        
2026-05-17 | 83           | 1057       
2026-05-18 | 734          | 366        
2026-05-19 | 853          | 419        
2026-05-20 | 877          | 331        
2026-05-21 | 727          | 274        
2026-05-22 | 731          | 379        
2026-05-23 | 430          | 550        
2026-05-24 | 718          | 407        
2026-05-25 | 475          | 485        
2026-05-26 | 403          | 518        
2026-05-27 | 417          | 624        
2026-05-28 | 529          | 719        
2026-05-29 | 344          | 600        
2026-05-30 | 438          | 522        
2026-05-31 | 547          | 322        
2026-06-01 | 297          | 638        
2026-06-02 | 112          | 508        
2026-06-03 | 462          | 551        
2026-06-04 | 463          | 528
```
