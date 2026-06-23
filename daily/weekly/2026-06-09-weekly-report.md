# 週次統合レポート (2026-06-09)

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
- **期間収益**: ¥1030
- **総インプレッション**: 21212
- **平均RPM**: ¥132
- **RPM推移**: ¥203 → ¥109 📉低下

### 検索エンジントラフィック
- **Bing**: 3537 sessions
- **Google**: 2635 sessions

### Bing クロール
- **最終日**: 2026-06-07
- **クロール数**: 572
- **エラー数**: 745 (56.6%)

### RPM（デバイス別28日平均）
- **Desktop**: ¥133
- **Mobile**: ¥136

### PostHog A/B
- **Active flag**: zdk_toc_sticky
- **Null rate**: 🔴 86.2%
- **Recommendation**: `fix_null_rate` — Null rate 86.2% exceeds threshold 30.0%. Fix instrumentation before deciding.


Full report: `daily/posthog/2026-06-09.md`

---

## GA4 Overview
```
GA4 property 344037190
Date range: 28daysAgo -> yesterday
sessionDefaultChannelGroup | sessions | totalUsers | screenPageViews | engagedSessions
---------------------------|----------|------------|-----------------|----------------
Organic Search             | 6865     | 5758       | 10478           | 4115           
Direct                     | 1153     | 1048       | 1341            | 205            
Unassigned                 | 198      | 167        | 661             | 105            
Referral                   | 137      | 83         | 164             | 55             
Organic Social             | 39       | 21         | 76              | 22
```

## GA4 Acquisition
```
GA4 property 344037190
Date range: 28daysAgo -> yesterday
sessionSourceMedium            | sessions | totalUsers | engagedSessions | screenPageViews
-------------------------------|----------|------------|-----------------|----------------
bing / organic                 | 3537     | 2909       | 2178            | 5684           
google / organic               | 2635     | 2198       | 1509            | 2995           
(direct) / (none)              | 1153     | 1048       | 205             | 1341           
yahoo / organic                | 285      | 247        | 176             | 354            
openai / organic               | 226      | 218        | 192             | 1086           
duckduckgo / organic           | 148      | 129        | 81              | 279            
openai / (not set)             | 77       | 71         | 59              | 437            
chatgpt.com / (not set)        | 59       | 57         | 25              | 149            
t.co / referral                | 37       | 19         | 22              | 74             
copilot.com / referral         | 33       | 8          | 7               | 39             
cn.bing.com / referral         | 26       | 20         | 9               | 21             
ecosia.org / organic           | 26       | 21         | 15              | 33             
(not set)                      | 25       | 24         | 0               | 7              
chatgpt.com / referral         | 23       | 21         | 13              | 27             
tool_onboarding_v1 / (not set) | 20       | 2          | 16              | 52
```

## GSC Top Queries
```
GSC site https://www.zidooka.com/
Date range: 2026-05-12 -> 2026-06-09
query                                                                                                                                                          | page                                  | clicks | impressions | ctr    | position
---------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------|--------|-------------|--------|---------
実行に失敗しました。rhino ランタイムは非推奨となり、サポートが終了しました。                                                                                                                      | https://www.zidooka.com/archives/2838 | 105    | 386         | 27.20% | 2.11    
copilot has been working on this problem for a while. it can continue to iterate, or you can send a new message to refine your prompt. configure max requests. | https://www.zidooka.com/archives/411  | 69     | 157         | 43.95% | 1.60    
"princexml" is required to be installed.                                                                                                                       | https://www.zidooka.com/archives/105  | 45     | 79          | 56.96% | 2.00    
files.oaiusercontent.com にアップロードできませんでした。ネットワーク設定でこのサイトへのアクセスを許可するか、ネットワーク管理者に問い合わせてください。                                                                      | https://www.zidooka.com/archives/185  | 40     | 163         | 24.54% | 2.61    
files.oaiusercontent.com にアップロードできませんでした。ネットワーク設定でこのサイトへのアクセスを許可するか、ネットワーク管理者に問い合わせてください。                                                                      | https://www.zidooka.com/archives/586  | 38     | 163         | 23.31% | 4.05    
princexml                                                                                                                                                      | https://www.zidooka.com/archives/105  | 36     | 128         | 28.13% | 3.74    
rhino ランタイムは非推奨となり、サポートが終了しました。                                                                                                                                | https://www.zidooka.com/archives/2838 | 30     | 122         | 24.59% | 1.97    
x something went wrong                                                                                                                                         | https://www.zidooka.com/archives/3290 | 28     | 349         | 8.02%  | 3.85    
copilot has been working on this problem for a while. it can continue to iterate, or you can send a new message to refine your prompt.                         | https://www.zidooka.com/archives/411  | 27     | 56          | 48.21% | 1.77    
edgesuite                                                                                                                                                      | https://www.zidooka.com/archives/2590 | 25     | 105         | 23.81% | 2.13    
チャットgpt オフラインになっているようです                                                                                                                                        | https://www.zidooka.com/archives/443  | 24     | 128         | 18.75% | 2.19    
princexml is required to be installed                                                                                                                          | https://www.zidooka.com/archives/105  | 22     | 35          | 62.86% | 3.34    
ポストを読み込めません 垢消し                                                                                                                                                | https://www.zidooka.com/archives/3017 | 22     | 82          | 26.83% | 2.13    
dns probe finished no internet                                                                                                                                 | https://www.zidooka.com/archives/2770 | 18     | 60          | 30.00% | 5.02    
we're experiencing high demand for the selected model right now. please upgrade to pro, switch to auto, another model, or try again in a few moments.          | https://www.zidooka.com/archives/240  | 18     | 94          | 19.15% | 3.41    
chatgpt オフラインになっているようです                                                                                                                                        | https://www.zidooka.com/archives/443  | 14     | 65          | 21.54% | 1.95    
個の画像を分析しています                                                                                                                                                   | https://www.zidooka.com/archives/1083 | 14     | 53          | 26.42% | 1.26    
https://errors.edgesuite.net/                                                                                                                                  | https://www.zidooka.com/archives/2590 | 13     | 51          | 25.49% | 2.04    
princexml vscode                                                                                                                                               | https://www.zidooka.com/archives/105  | 13     | 16          | 81.25% | 2.19    
copilot has been working on this problem for a while. it can continue to iterate, or you can send a new message to refine your prompt                          | https://www.zidooka.com/archives/411  | 11     | 23          | 47.83% | 1.22    
https://errors.edgesuite.net                                                                                                                                   | https://www.zidooka.com/archives/2590 | 10     | 28          | 35.71% | 2.11    
ポストを読み込めません                                                                                                                                                    | https://www.zidooka.com/archives/3017 | 10     | 2772        | 0.36%  | 9.92    
ポストを読み込めません アカウント削除                                                                                                                                            | https://www.zidooka.com/archives/3017 | 10     | 38          | 26.32% | 2.34    
ポストを読み込めません 特定の人                                                                                                                                               | https://www.zidooka.com/archives/4287 | 10     | 1095        | 0.91%  | 6.17    
errors.edgesuite.net                                                                                                                                           | https://www.zidooka.com/archives/2590 | 9      | 34          | 26.47% | 1.21    
ダイソー ワイヤレスイヤホン 700円                                                                                                                                            | https://www.zidooka.com/archives/1060 | 9      | 51          | 17.65% | 7.57    
edgesuite.net とは                                                                                                                                               | https://www.zidooka.com/archives/2590 | 8      | 46          | 17.39% | 2.07    
errors.edgesuite.net とは                                                                                                                                        | https://www.zidooka.com/archives/2590 | 8      | 37          | 21.62% | 2.00    
https //errors.edgesuite.net とは                                                                                                                                | https://www.zidooka.com/archives/2590 | 8      | 14          | 57.14% | 1.00    
status_breakpoint                                                                                                                                              | https://www.zidooka.com/archives/4219 | 8      | 267         | 3.00%  | 6.92
```

## AdSense Daily
```
AdSense account: pub-5002038850592836
Date range: 28daysAgo -> yesterday
DATE       | ESTIMATED_EARNINGS | IMPRESSIONS | CLICKS | PAGE_VIEWS_RPM | COST_PER_CLICK
-----------|--------------------|-------------|--------|----------------|---------------
2026-05-12 | 53                 | 751         | 6      | 203            | 9             
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
```

## AdSense RPM by Platform
```
AdSense account: pub-5002038850592836
Date range: 28daysAgo -> yesterday
PLATFORM_TYPE_CODE | ESTIMATED_EARNINGS | IMPRESSIONS | PAGE_VIEWS_RPM | AD_REQUESTS_COVERAGE
-------------------|--------------------|-------------|----------------|---------------------
Desktop            | 812                | 16121       | 133            | 0.713               
HighEndMobile      | 213                | 4970        | 136            | 0.9451              
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
```
