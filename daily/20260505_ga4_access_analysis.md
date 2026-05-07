# 2026-05-05 GA4 access analysis

対象期間: 2026-04-21 から 2026-05-04
比較期間: 2026-04-07 から 2026-04-20

## Summary

- Sessions: 3,520 -> previous 2,854, +666 / +23.34%
- Total users: 3,039 -> previous 2,454, +585 / +23.84%
- Page views: 4,902 -> previous 4,137, +765 / +18.49%
- Engaged sessions: 1,642 -> previous 1,510, +132 / +8.74%
- Engagement rate: 46.65% -> previous 52.91%, -6.26pt

## Channel findings

| Channel | Sessions | Previous | Delta | Notes |
| --- | ---: | ---: | ---: | --- |
| Organic Search | 2,678 | 2,358 | +320 | Main growth driver. Bing and Google both increased. |
| Direct | 749 | 410 | +339 | Session growth is large, but engaged sessions stayed flat. Treat as low-quality or attribution-noisy growth. |
| Referral | 54 | 56 | -2 | ChatGPT referral fell, Copilot referral rose. |
| Organic Social | 20 | 8 | +12 | Small volume. |
| Unassigned | 19 | 22 | -3 | Not a major contributor. |

## Source findings

- `bing / organic`: 1,509 sessions, +126 / +9.11%
- `google / organic`: 965 sessions, +137 / +16.55%
- `(direct) / (none)`: 749 sessions, +339 / +82.68%
- `duckduckgo / organic`: 80 sessions, +14 / +21.21%
- `yahoo / organic`: 75 sessions, +27 / +56.25%
- `copilot.com / referral`: 23 sessions, +20
- `chatgpt.com / referral`: 10 sessions, -11

## Landing page winners

| Landing page | Sessions | Previous | Delta | Engaged delta |
| --- | ---: | ---: | ---: | ---: |
| `/jp/calendar/2026/05` | 176 | 71 | +105 | +75 |
| `/archives/2726` | 96 | 3 | +93 | +61 |
| `/jp/calendar/2026/06` | 86 | 15 | +71 | +57 |
| `(not set)` | 278 | 225 | +53 | 0 |
| `/archives/2590` | 149 | 107 | +42 | +17 |
| `/archives/580` | 35 | 12 | +23 | +15 |
| `/archives/185` | 60 | 38 | +22 | +12 |

## Landing page declines

| Landing page | Sessions | Previous | Delta | Engaged delta |
| --- | ---: | ---: | ---: | ---: |
| `/archives/105` | 86 | 144 | -58 | -45 |
| `/jp/calendar/2026/04` | 56 | 113 | -57 | -38 |
| `/archives/4006` | 38 | 70 | -32 | -17 |
| `/archives/2672` | 6 | 25 | -19 | -10 |
| `/archives/3079` | 7 | 26 | -19 | -13 |
| `/archives/4154` | 3 | 21 | -18 | -9 |
| `/archives/1166` | 12 | 27 | -15 | -14 |
| `/archives/633` | 12 | 27 | -15 | -7 |

## Device findings

| Device | Sessions | Previous | Delta | Engaged delta |
| --- | ---: | ---: | ---: | ---: |
| desktop | 3,080 | 2,506 | +574 | +115 |
| mobile | 404 | 339 | +65 | +15 |
| tablet | 18 | 10 | +8 | +2 |

Desktop dominates growth. This matches the existing pattern where search and `(not set)` issues skew desktop-heavy.

## Events

- `page_view`: 4,902, +765 / +18.49%
- `session_start`: 3,516, +666 / +23.37%
- `first_visit`: 2,945, +580 / +24.52%
- `user_engagement`: 2,230, +222 / +11.06%
- `zdk_debug_search_boot`: 1,907, +79 / +4.32%
- `zdk_debug_search_hidden`: 181, +2 / +1.12%
- `zdk_debug_search_bfcache`: 0, previous 1
- `cta_click`: 2 key events, previous 0
- `form_submit`: 1, previous 9

## Interpretation

Traffic grew clearly, but the quality signal is mixed. Sessions and users are up about 23%, while engaged sessions rose only 8.74% and engagement rate fell from 52.91% to 46.65%.

The strongest genuine growth is from search and calendar pages, especially `/jp/calendar/2026/05` and `/jp/calendar/2026/06`. The biggest noisy component is Direct: +339 sessions but engaged sessions -2, which suggests attribution noise, low-intent landings, or bot-like/direct-like sessions.

`(not set)` remains material: 278 sessions, +53. In LP x source data, the largest `(not set)` contributors are `bing / organic` 134 and `google / organic` 109. This continues the earlier desktop search / landing page attribution issue rather than a single article problem.

## Recommended next actions

1. Refresh or internally link from `/jp/calendar/2026/05` and `/jp/calendar/2026/06` while demand is active.
2. Review `/archives/105`, `/archives/4006`, `/archives/2672`, `/archives/3079`, `/archives/4154`, `/archives/1166`, and `/archives/633` with GSC query loss before editing.
3. Investigate Direct growth separately. Prioritize source/device/browser breakdown for Direct sessions because engagement did not grow.
4. Keep `(not set)` debug monitoring. `zdk_debug_search_hidden` did not worsen materially, and `bfcache` disappeared, so the issue still looks like search/desktop attribution rather than one debug event failure.
5. Treat form and CTA metrics cautiously. `form_submit` fell from 9 to 1 and key events are too sparse for content decisions.

## Generated files

- `daily/ga4-overview-20260421_20260504.csv`
- `daily/ga4-acquisition-20260421_20260504.csv`
- `daily/ga4-landing-pages-20260421_20260504.csv`
- `daily/ga4-landing-pages-20260421_20260504_full.csv`
- `daily/ga4-devices-20260421_20260504.csv`
- `daily/ga4-daily-channel-20260421_20260504.csv`
- `daily/ga4-events-20260421_20260504.csv`
- `daily/ga4-lp-source-20260421_20260504.csv`
