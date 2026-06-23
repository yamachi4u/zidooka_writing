# 週次統合レポート (2026-06-17)

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
- **Bing**: 3187 sessions
- **Google**: 2642 sessions

### Bing クロール
- **最終日**: 2026-06-15
- **クロール数**: 592
- **エラー数**: 742 (55.6%)

### PostHog A/B
- **Active flag**: zdk_related_posts
- **Null rate**: 🟢 0.0%
- **Recommendation**: `wait_impressions` — Need 200 impressions per variant. Currently control=173 treatment=170. Check again in a few days.


Full report: `daily/posthog/2026-06-17.md`

---

## GA4 Overview
```
GA4 property 344037190
Date range: 28daysAgo -> yesterday
sessionDefaultChannelGroup | sessions | totalUsers | screenPageViews | engagedSessions
---------------------------|----------|------------|-----------------|----------------
Organic Search             | 6496     | 5498       | 10593           | 3905           
Direct                     | 1193     | 1089       | 1383            | 204            
Unassigned                 | 173      | 136        | 651             | 105            
Referral                   | 127      | 75         | 152             | 47             
Organic Social             | 32       | 11         | 87              | 20             
AI Assistant               | 16       | 10         | 60              | 6
```

## GA4 Acquisition
```
GA4 property 344037190
Date range: 28daysAgo -> yesterday
sessionSourceMedium            | sessions | totalUsers | engagedSessions | screenPageViews
-------------------------------|----------|------------|-----------------|----------------
bing / organic                 | 3187     | 2634       | 1993            | 5764           
google / organic               | 2642     | 2210       | 1482            | 2887           
(direct) / (none)              | 1193     | 1089       | 204             | 1383           
yahoo / organic                | 294      | 248        | 170             | 359            
openai / organic               | 253      | 246        | 222             | 1307           
duckduckgo / organic           | 133      | 114        | 76              | 212            
openai / (not set)             | 67       | 63         | 58              | 406            
chatgpt.com / (not set)        | 40       | 38         | 17              | 83             
tool_onboarding_v1 / (not set) | 32       | 2          | 25              | 141            
t.co / referral                | 30       | 9          | 20              | 85             
copilot.com / referral         | 28       | 6          | 5               | 33             
cn.bing.com / referral         | 26       | 21         | 12              | 22             
(not set)                      | 23       | 23         | 0               | 8              
ecosia.org / organic           | 22       | 17         | 15              | 25             
chatgpt.com / referral         | 19       | 16         | 10              | 22
```

## GSC Top Queries
```
GSC site https://www.zidooka.com/
Date range: 2026-05-20 -> 2026-06-17
query                                                                                                                                                          | page                                  | clicks | impressions | ctr    | position
---------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------|--------|-------------|--------|---------
実行に失敗しました。rhino ランタイムは非推奨となり、サポートが終了しました。                                                                                                                      | https://www.zidooka.com/archives/2838 | 128    | 476         | 26.89% | 2.16    
copilot has been working on this problem for a while. it can continue to iterate, or you can send a new message to refine your prompt. configure max requests. | https://www.zidooka.com/archives/411  | 71     | 178         | 39.89% | 1.94    
files.oaiusercontent.com にアップロードできませんでした。ネットワーク設定でこのサイトへのアクセスを許可するか、ネットワーク管理者に問い合わせてください。                                                                      | https://www.zidooka.com/archives/185  | 50     | 160         | 31.25% | 2.17    
files.oaiusercontent.com にアップロードできませんでした。ネットワーク設定でこのサイトへのアクセスを許可するか、ネットワーク管理者に問い合わせてください。                                                                      | https://www.zidooka.com/archives/586  | 39     | 160         | 24.38% | 3.93    
rhino ランタイムは非推奨となり、サポートが終了しました。                                                                                                                                | https://www.zidooka.com/archives/2838 | 38     | 151         | 25.17% | 1.99    
"princexml" is required to be installed.                                                                                                                       | https://www.zidooka.com/archives/105  | 37     | 60          | 61.67% | 1.72    
チャットgpt オフラインになっているようです                                                                                                                                        | https://www.zidooka.com/archives/443  | 30     | 142         | 21.13% | 2.27    
copilot has been working on this problem for a while. it can continue to iterate, or you can send a new message to refine your prompt.                         | https://www.zidooka.com/archives/411  | 28     | 56          | 50.00% | 1.45    
edgesuite                                                                                                                                                      | https://www.zidooka.com/archives/2590 | 27     | 111         | 24.32% | 1.99    
princexml                                                                                                                                                      | https://www.zidooka.com/archives/105  | 27     | 126         | 21.43% | 4.02    
x something went wrong                                                                                                                                         | https://www.zidooka.com/archives/3290 | 26     | 358         | 7.26%  | 4.03    
princexml is required to be installed                                                                                                                          | https://www.zidooka.com/archives/105  | 19     | 31          | 61.29% | 3.29    
dns probe finished no internet                                                                                                                                 | https://www.zidooka.com/archives/2770 | 18     | 62          | 29.03% | 4.48    
we're experiencing high demand for the selected model right now. please upgrade to pro, switch to auto, another model, or try again in a few moments.          | https://www.zidooka.com/archives/240  | 18     | 89          | 20.22% | 3.42    
個の画像を分析しています                                                                                                                                                   | https://www.zidooka.com/archives/1083 | 17     | 61          | 27.87% | 1.23    
https://errors.edgesuite.net/                                                                                                                                  | https://www.zidooka.com/archives/2590 | 16     | 49          | 32.65% | 1.65    
ポストを読み込めません                                                                                                                                                    | https://www.zidooka.com/archives/3017 | 16     | 4051        | 0.39%  | 9.80    
ポストを読み込めません 垢消し                                                                                                                                                | https://www.zidooka.com/archives/3017 | 15     | 81          | 18.52% | 2.31    
chatgpt オフラインになっているようです                                                                                                                                        | https://www.zidooka.com/archives/443  | 13     | 70          | 18.57% | 1.99    
errors.edgesuite.net                                                                                                                                           | https://www.zidooka.com/archives/2590 | 13     | 37          | 35.14% | 1.14    
ダイソー ワイヤレスイヤホン 700円                                                                                                                                            | https://www.zidooka.com/archives/1060 | 12     | 51          | 23.53% | 6.24    
ポストを読み込めません 特定の人                                                                                                                                               | https://www.zidooka.com/archives/3017 | 10     | 1104        | 0.91%  | 7.50    
codex auto approve                                                                                                                                             | https://www.zidooka.com/archives/3229 | 9      | 121         | 7.44%  | 6.32    
copilot has been working on this problem for a while. it can continue to iterate, or you can send a new message to refine your prompt                          | https://www.zidooka.com/archives/411  | 9      | 16          | 56.25% | 1.00    
princexml vscode                                                                                                                                               | https://www.zidooka.com/archives/105  | 9      | 15          | 60.00% | 2.93    
rhino ランタイムは非推奨となり、サポートが終了しました。このスクリプトを実行するには、v8 ランタイムに移行する必要があります。                                                                                            | https://www.zidooka.com/archives/2838 | 9      | 41          | 21.95% | 3.00    
x ポストを読み込めません アカウント削除                                                                                                                                          | https://www.zidooka.com/archives/3017 | 9      | 33          | 27.27% | 2.85    
ポストを読み込めません アカウント削除                                                                                                                                            | https://www.zidooka.com/archives/3017 | 9      | 55          | 16.36% | 3.13    
ポストを読み込めません 凍結                                                                                                                                                 | https://www.zidooka.com/archives/3017 | 9      | 769         | 1.17%  | 7.25    
errors.edgesuite.net とは                                                                                                                                        | https://www.zidooka.com/archives/2590 | 8      | 33          | 24.24% | 2.03
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
2026-06-03 | 4637        | 113   
2026-06-04 | 5212        | 142   
2026-06-05 | 3780        | 71    
2026-06-06 | 3522        | 68    
2026-06-07 | 4771        | 110   
2026-06-08 | 5321        | 142   
2026-06-09 | 4683        | 111   
2026-06-10 | 4522        | 127   
2026-06-11 | 4447        | 97    
2026-06-12 | 3355        | 73    
2026-06-13 | 1556        | 30    
2026-06-14 | 2525        | 100
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
2026-06-05 | 527          | 683        
2026-06-06 | 609          | 847        
2026-06-07 | 572          | 745        
2026-06-08 | 217          | 654        
2026-06-09 | 392          | 669        
2026-06-10 | 532          | 681        
2026-06-11 | 631          | 687        
2026-06-12 | 658          | 536        
2026-06-13 | 754          | 722        
2026-06-14 | 585          | 551        
2026-06-15 | 592          | 742
```
