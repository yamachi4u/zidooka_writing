---
title: "Why Does researchmap Keep Going Down? Inferring Its Stack and Bottlenecks from Public Information"
slug: researchmap-outage-stack-en
status: publish
categories:
  - ウェブサイト系
tags:
  - researchmap
  - NetCommons3
  - Apache
  - MySQL
  - Cloudflare
  - reverse proxy
  - outage
  - infrastructure
---

researchmap has been noticeably unstable. Pages sometimes fail to load, users encounter access-congestion messages, and individual functions occasionally stop working.

Public documents provide enough evidence to build a fairly concrete picture of why.

:::conclusion
As of August 2026, the most plausible explanation is that researchmap is a heavily customized, relatively legacy LAMP-style application built on NetCommons3, and that large volumes of dynamic requests are reaching the application and database layers.

JST itself has acknowledged that traffic growth since late 2025 has caused intermittent failures. There is also little public evidence that researchmap is fronted by a large external CDN such as Cloudflare in the usual reverse-proxy configuration.
:::

## JST has already acknowledged traffic-driven failures

On June 2, 2026, JST stated that access to researchmap had increased sharply since around the end of 2025. According to the notice, this higher load caused intermittent failures including pages becoming unavailable and some functions stopping.

JST also identified large-scale crawling by external search engines as one cause of the increase. Since January 2026, it has restricted which pages search engines may crawl, especially detailed achievement pages and blogs.

So the first-order explanation is not speculative: more traffic is producing more system load.

The interesting question is why anonymous crawling can degrade the service so broadly. On a modern high-traffic public site, much of that traffic can normally be absorbed by CDN caching, WAF rules, rate limiting, and bot management before it reaches the application.

## researchmap v2 is based on NetCommons3

This is documented rather than inferred.

NetCommons materials describe the relationship between the migration from researchmap v1 to v2 and the NetCommons2-to-NetCommons3 migration tooling. More importantly, a 2025 JST procurement document explicitly states that the current researchmap system uses both standard NetCommons3 functions and customized extensions.

That procurement document contains a particularly revealing point: documentation explaining how NetCommons3 is used to implement researchmap's functions did not exist, making vulnerability and defect investigation time-consuming and creating a significant operational concern.

JST therefore procured reverse-engineering work on the current source code and database structure, including CRUD diagrams and documentation of NetCommons3 usage.

:::note
This is more significant than merely saying that the site uses an old CMS. It means a large customized production system had accumulated enough implementation complexity that maintainers needed to reconstruct design knowledge from the running code and database.
:::

## The backend is very likely LAMP-style

A July 2026 JST recruitment notice for researchmap system staff lists experience with Linux, Apache, and MySQL as a required qualification. It also specifically mentions reading Apache access logs, using SQL for data extraction, understanding DNS, and writing shell scripts.

NetCommons3 itself is PHP-based.

The core stack can therefore be approximated as:

```text
Browser / Bot
     ↓
Internet
     ↓
[Load balancer / reverse proxy?]
     ↓
Apache
     ↓
PHP / NetCommons3 + researchmap custom code
     ↓
MySQL
     ↓
Researcher and publication data
```

The exact internal load-balancing product, server count, database replication topology, and caching layer are not publicly documented.

## Is Cloudflare in front of researchmap?

There is little evidence of the common setup where Cloudflare acts as the public reverse proxy and CDN.

Public DNS data shows `researchmap.jp` resolving to `160.74.72.2`. That address belongs to AS2513, the Japan Science and Technology Agency's own network. The authoritative nameservers are `ns1.do-reg.jp` and `ns2.do-reg.jp`, rather than Cloudflare nameservers.

The observed TLS certificate is also a DigiCert certificate issued to JST.

This does not prove that no reverse proxy exists. The public IP could be a virtual IP backed by internal load balancers and multiple Apache servers. What it does suggest is that researchmap is not obviously placing a global external edge network such as Cloudflare in front of JST's infrastructure.

Cloudflare Radar having a page for the domain is not evidence that Cloudflare hosts or proxies the site; Radar catalogs domains generally.

## Why would crawlers cause outages?

From here, the discussion is architectural inference.

researchmap exposes an enormous URL space: researcher profiles, individual papers, MISC records, books, presentations, blogs, communities, and many other plugin-driven pages. A single researcher may have many detail pages.

If a crawler walks these URLs deeply and each request causes Apache/PHP/NetCommons3 to execute application logic and query MySQL, the load is very different from serving cached static HTML.

A plausible failure chain is:

```text
Search / AI crawlers traverse many URLs
        ↓
Concurrent Apache connections increase
        ↓
PHP work increases
        ↓
MySQL queries increase
        ↓
Slow queries / locks / connections accumulate
        ↓
PHP workers remain occupied
        ↓
Web workers become saturated
        ↓
Normal users queue behind them
        ↓
Congestion messages / timeouts / partial failures
```

If anonymous GET responses are difficult to cache because of sessions, permissions, plugins, widgets, or page-specific dynamic generation, crawler traffic can translate almost directly into application and database load.

A strong CDN layer could cache many public profile and achievement pages at the edge and apply per-bot rate limits before those requests reached PHP.

## JST's workaround is itself revealing

Since January 2026, JST has reduced which pages external search engines may crawl.

That is primarily a traffic-reduction strategy rather than an application-performance fix. It also has a major side effect: detailed achievements and blog entries become harder to find through external search services.

The fact that JST accepted that trade-off suggests the existing platform cannot safely absorb the crawling load with its current architecture and controls.

## A full renewal is planned for 2029

In October 2025, JST announced that researchmap would be renewed around 2029 because support for the underlying platform software is ending.

The explicitly stated goals include:

- improved maintainability
- improved security
- improved performance

The next system is also expected to remove or reduce many NetCommons-style functions: achievement search, follower features, widgets, blogs, bulletin boards, generic databases, and numerous portal plugins.

That simplification makes architectural sense. The essential role of researchmap is maintaining and publishing researcher information and achievements, plus institutional/API integration. Removing CMS-like peripheral features reduces application state, permissions, plugin dependencies, database complexity, and cache invalidation problems.

## Would adding Cloudflare solve everything?

No.

Cloudflare, Fastly, CloudFront, or another edge service could materially improve anonymous-page caching, WAF protection, DDoS resistance, and bot throttling.

But a CDN alone does not fix:

- expensive PHP execution
- N+1 queries
- missing or poor MySQL indexes
- session-dependent responses that cannot be cached
- heavy authenticated pages
- database-intensive batch jobs
- complex plugin dependencies
- poor internal documentation

A more modern target architecture might look like:

```text
CDN / WAF / Bot Management
          ↓
L7 Load Balancer
          ↓
Stateless Web/App servers
          ↓
Redis or equivalent cache/session layer
          ↓
Tuned RDBMS / read replicas
```

But application simplification and database work would still be necessary.

## Confidence summary

| Item | Assessment | Confidence |
| --- | --- | --- |
| Base CMS | NetCommons3 | High: documented by JST/NetCommons sources |
| OS | Linux | High: listed in JST recruitment |
| Web server | Apache | High: listed in JST recruitment |
| Database | MySQL | High: listed in JST recruitment |
| Application | PHP + NetCommons3 + extensive customizations | High to medium |
| Cloudflare proxy/CDN | No strong evidence of fronting the site | Medium |
| Internal load balancer | Entirely possible | Unknown |
| Immediate outage trigger | High traffic causing application load | High: stated by JST |
| Deeper cause | Legacy complexity, dynamic processing, caching limits, and maintainability problems | Medium: architectural inference from public evidence |

:::conclusion
The recurring researchmap outages are best understood as a structural problem rather than simply "too many users."

The evidence points to a large NetCommons3-based system with extensive customizations, a Linux/Apache/MySQL stack, increasing crawler load, insufficiently documented internals, and an underlying platform already scheduled for replacement.

JST's own 2029 roadmap prioritizes maintainability, security, and performance. That is consistent with the conclusion that the current architecture has reached the point where traffic workarounds alone are not enough.
:::

## Sources

- JST, "Access concentration causing researchmap web failures and restrictions in external search services"  
  https://researchmap.jp/blogs/blog_entries/view/515443/20c0ce4aa9e19560c836f78b46d5a65e?frame_id=1028063
- JST procurement information, documentation/reverse engineering of the researchmap system  
  https://www.kkj.go.jp/d/?A=c2VhcmNoMi9qc3QvMjAyNS8yMDI1MDkyNi00MV8yMDI1XzEwMS5odG1sCg%3D%3D&L=ja
- JST researchmap system staff recruitment notice  
  https://www.jst.go.jp/saiyou/pdf/bosyu260714_researchmap.pdf
- NetCommons, NC2-to-NC3 migration discussion  
  https://www.netcommons.org/bbses/bbs_articles/view/779/52f7a628fa0b7514b578c3cbff97afd0?frame_id=200
- JST, planned researchmap system renewal for 2029  
  https://researchmap.jp/blogs/blog_entries/view/515443/faf933c60441476e773956fd046618c4?frame_id=1028063
- Cloudflare Radar: researchmap.jp  
  https://radar.cloudflare.com/domains/domain/researchmap.jp
- IPinfo: 160.74.72.0/24 / AS2513 JST  
  https://ipinfo.io/ips/160.74.72.0/24
