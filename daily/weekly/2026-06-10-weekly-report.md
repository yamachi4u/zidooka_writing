# 週次統合レポート (2026-06-10)

## データソース

| チャネル | ステータス |
|---------|----------|
| GA4 Overview | OK |
| GA4 Acquisition | OK |
| GA4 Countries | OK |
| GSC Top Queries | OK |
| AdSense Daily | OK |
| AdSense RPM by Platform | OK |
| Bing Rank & Traffic | OK |
| Bing Crawl Stats | OK |
| PostHog A/B | OK |

## サマリー

### AdSense
- **期間収益**: ¥998
- **総インプレッション**: 21023
- **平均RPM**: ¥127
- **RPM推移**: ¥129 → ¥74 📉低下

### 検索エンジントラフィック
- **Bing**: 3547 sessions
- **Google**: 2634 sessions

### Bing クロール
- **最終日**: 2026-06-08
- **クロール数**: 217
- **エラー数**: 654 (75.1%)

### RPM（デバイス別28日平均）
- **Desktop**: ¥127
- **Mobile**: ¥134

### PostHog A/B
- **Active flag**: zdk_line_height
- **Null rate**: 🔴 82.6%
- **Recommendation**: `fix_null_rate` — Null rate 82.6% exceeds threshold 30.0%. Fix instrumentation before deciding.


Full report: `daily/posthog/2026-06-10.md`

---

## GA4 Overview
```
GA4 property 344037190
Date range: 28daysAgo -> yesterday
sessionDefaultChannelGroup | sessions | totalUsers | screenPageViews | engagedSessions
---------------------------|----------|------------|-----------------|----------------
Organic Search             | 6858     | 5767       | 10573           | 4150           
Direct                     | 1154     | 1047       | 1333            | 205            
Unassigned                 | 200      | 168        | 618             | 104            
Referral                   | 140      | 84         | 166             | 55             
Organic Social             | 37       | 19         | 74              | 21
```

## GA4 Acquisition
```
GA4 property 344037190
Date range: 28daysAgo -> yesterday
sessionSourceMedium            | sessions | totalUsers | engagedSessions | screenPageViews
-------------------------------|----------|------------|-----------------|----------------
bing / organic                 | 3547     | 2905       | 2183            | 5780           
google / organic               | 2634     | 2200       | 1504            | 2919           
(direct) / (none)              | 1154     | 1047       | 205             | 1333           
yahoo / organic                | 291      | 248        | 178             | 361            
openai / organic               | 235      | 227        | 200             | 1167           
duckduckgo / organic           | 146      | 132        | 85              | 271            
openai / (not set)             | 74       | 70         | 56              | 372            
chatgpt.com / (not set)        | 61       | 59         | 25              | 151            
t.co / referral                | 35       | 17         | 21              | 72             
copilot.com / referral         | 33       | 8          | 7               | 39             
cn.bing.com / referral         | 26       | 20         | 10              | 21             
(not set)                      | 25       | 24         | 0               | 7              
chatgpt.com / referral         | 25       | 22         | 13              | 29             
ecosia.org / organic           | 24       | 19         | 14              | 28             
tool_onboarding_v1 / (not set) | 23       | 2          | 18              | 72
```

## GSC Top Queries
```
GSC site https://www.zidooka.com/
Date range: 2026-05-13 -> 2026-06-10
query                                                                                                                                                          | page                                  | clicks | impressions | ctr    | position
---------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------|--------|-------------|--------|---------
実行に失敗しました。rhino ランタイムは非推奨となり、サポートが終了しました。                                                                                                                      | https://www.zidooka.com/archives/2838 | 116    | 412         | 28.16% | 2.11    
copilot has been working on this problem for a while. it can continue to iterate, or you can send a new message to refine your prompt. configure max requests. | https://www.zidooka.com/archives/411  | 68     | 153         | 44.44% | 1.58    
"princexml" is required to be installed.                                                                                                                       | https://www.zidooka.com/archives/105  | 44     | 77          | 57.14% | 2.03    
files.oaiusercontent.com にアップロードできませんでした。ネットワーク設定でこのサイトへのアクセスを許可するか、ネットワーク管理者に問い合わせてください。                                                                      | https://www.zidooka.com/archives/185  | 41     | 162         | 25.31% | 2.55    
princexml                                                                                                                                                      | https://www.zidooka.com/archives/105  | 37     | 125         | 29.60% | 3.76    
files.oaiusercontent.com にアップロードできませんでした。ネットワーク設定でこのサイトへのアクセスを許可するか、ネットワーク管理者に問い合わせてください。                                                                      | https://www.zidooka.com/archives/586  | 36     | 162         | 22.22% | 4.06    
rhino ランタイムは非推奨となり、サポートが終了しました。                                                                                                                                | https://www.zidooka.com/archives/2838 | 33     | 132         | 25.00% | 1.97    
x something went wrong                                                                                                                                         | https://www.zidooka.com/archives/3290 | 28     | 349         | 8.02%  | 3.86    
チャットgpt オフラインになっているようです                                                                                                                                        | https://www.zidooka.com/archives/443  | 25     | 133         | 18.80% | 2.21    
copilot has been working on this problem for a while. it can continue to iterate, or you can send a new message to refine your prompt.                         | https://www.zidooka.com/archives/411  | 23     | 50          | 46.00% | 1.78    
edgesuite                                                                                                                                                      | https://www.zidooka.com/archives/2590 | 21     | 94          | 22.34% | 2.18    
princexml is required to be installed                                                                                                                          | https://www.zidooka.com/archives/105  | 21     | 33          | 63.64% | 3.21    
ポストを読み込めません 垢消し                                                                                                                                                | https://www.zidooka.com/archives/3017 | 21     | 77          | 27.27% | 2.12    
we're experiencing high demand for the selected model right now. please upgrade to pro, switch to auto, another model, or try again in a few moments.          | https://www.zidooka.com/archives/240  | 18     | 94          | 19.15% | 3.41    
dns probe finished no internet                                                                                                                                 | https://www.zidooka.com/archives/2770 | 17     | 60          | 28.33% | 4.95    
chatgpt オフラインになっているようです                                                                                                                                        | https://www.zidooka.com/archives/443  | 14     | 68          | 20.59% | 1.96    
個の画像を分析しています                                                                                                                                                   | https://www.zidooka.com/archives/1083 | 14     | 52          | 26.92% | 1.25    
https://errors.edgesuite.net/                                                                                                                                  | https://www.zidooka.com/archives/2590 | 13     | 51          | 25.49% | 2.02    
copilot has been working on this problem for a while. it can continue to iterate, or you can send a new message to refine your prompt                          | https://www.zidooka.com/archives/411  | 10     | 21          | 47.62% | 1.10    
princexml vscode                                                                                                                                               | https://www.zidooka.com/archives/105  | 10     | 13          | 76.92% | 2.46    
ポストを読み込めません                                                                                                                                                    | https://www.zidooka.com/archives/3017 | 10     | 2922        | 0.34%  | 9.84    
ポストを読み込めません アカウント削除                                                                                                                                            | https://www.zidooka.com/archives/3017 | 10     | 37          | 27.03% | 2.32    
ポストを読み込めません 特定の人                                                                                                                                               | https://www.zidooka.com/archives/4287 | 10     | 1095        | 0.91%  | 6.17    
errors.edgesuite.net                                                                                                                                           | https://www.zidooka.com/archives/2590 | 9      | 31          | 29.03% | 1.23    
https://errors.edgesuite.net                                                                                                                                   | https://www.zidooka.com/archives/2590 | 9      | 27          | 33.33% | 2.07    
ダイソー ワイヤレスイヤホン 700円                                                                                                                                            | https://www.zidooka.com/archives/1060 | 9      | 51          | 17.65% | 7.25    
ポストを読み込めません 凍結                                                                                                                                                 | https://www.zidooka.com/archives/3017 | 8      | 624         | 1.28%  | 7.65    
codex auto approve                                                                                                                                             | https://www.zidooka.com/archives/3229 | 7      | 112         | 6.25%  | 7.69    
errors.edgesuite.net とは                                                                                                                                        | https://www.zidooka.com/archives/2590 | 7      | 33          | 21.21% | 2.06    
https //errors.edgesuite.net とは                                                                                                                                | https://www.zidooka.com/archives/2590 | 7      | 13          | 53.85% | 1.00
```

## AdSense Daily
```
AdSense account: pub-5002038850592836
Date range: 28daysAgo -> yesterday
DATE       | ESTIMATED_EARNINGS | IMPRESSIONS | CLICKS | PAGE_VIEWS_RPM | COST_PER_CLICK
-----------|--------------------|-------------|--------|----------------|---------------
2026-05-13 | 38                 | 806         | 6      | 129            | 6             
2026-05-14 | 46                 | 815         | 0      | 144            | 0             
2026-05-15 | 41                 | 626         | 3      | 151            | 14            
2026-05-16 | 20                 | 459         | 2      | 102            | 10            
2026-05-17 | 19                 | 334         | 0      | 117            | 0             
2026-05-18 | 43                 | 868         | 2      | 137            | 21            
2026-05-19 | 48                 | 761         | 6      | 144            | 8             
2026-05-20 | 22                 | 439         | 3      | 71             | 7             
2026-05-21 | 15                 | 386         | 1      | 56             | 15            
2026-05-22 | 29                 | 533         | 2      | 95             | 14            
2026-05-23 | 16                 | 282         | 1      | 91             | 16            
2026-05-24 | 16                 | 206         | 2      | 101            | 8             
2026-05-25 | 31                 | 532         | 2      | 98             | 15            
2026-05-26 | 30                 | 935         | 3      | 112            | 10            
2026-05-27 | 50                 | 1307        | 8      | 137            | 6             
2026-05-28 | 43                 | 943         | 7      | 150            | 6             
2026-05-29 | 59                 | 1211        | 7      | 167            | 8             
2026-05-30 | 23                 | 494         | 2      | 158            | 12            
2026-05-31 | 33                 | 531         | 1      | 213            | 33            
2026-06-01 | 72                 | 1532        | 2      | 179            | 36            
2026-06-02 | 51                 | 1234        | 3      | 145            | 17            
2026-06-03 | 50                 | 1174        | 8      | 141            | 6             
2026-06-04 | 54                 | 1238        | 2      | 160            | 27            
2026-06-05 | 43                 | 1150        | 4      | 119            | 11            
2026-06-06 | 33                 | 670         | 2      | 174            | 17            
2026-06-07 | 18                 | 299         | 1      | 94             | 18            
2026-06-08 | 34                 | 696         | 1      | 109            | 34            
2026-06-09 | 21                 | 562         | 2      | 74             | 11
```

## AdSense RPM by Platform
```
AdSense account: pub-5002038850592836
Date range: 28daysAgo -> yesterday
PLATFORM_TYPE_CODE | ESTIMATED_EARNINGS | IMPRESSIONS | PAGE_VIEWS_RPM | AD_REQUESTS_COVERAGE
-------------------|--------------------|-------------|----------------|---------------------
Desktop            | 781                | 15947       | 127            | 0.7106              
HighEndMobile      | 213                | 4955        | 134            | 0.9461              
Tablet             | 4                  | 121         | 90             | 0.9149
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
```
