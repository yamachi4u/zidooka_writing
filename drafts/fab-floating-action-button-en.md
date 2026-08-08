---
title: "FAB (Floating Action Button): Benefits, Drawbacks, and Implementation Patterns"
categories:
  - WEB制作
tags:
  - UX
  - デザイン
  - モバイル
status: publish
slug: fab-floating-action-button-en
---

:::conclusion
A FAB is a button that keeps the one main action of a page always within reach. It's convenient, but overusing it makes an interface noisy. Run a short checklist before adding one.
:::

## What is a FAB?

FAB stands for Floating Action Button. It's that round button hovering near the bottom-right corner of a screen. Tap it and the page's primary action kicks in. The component comes from Google's Material Design, and these days you see it in iOS apps and on the web too.

Here's the thing: a FAB is not decoration. It keeps the one thing you want the user to do always in thumb's reach. That only works when the page has a clear single purpose. If the role is fuzzy, the button becomes noise instead.

I notice the difference myself when I look at mobile apps. Whether a screen has a FAB decides how quickly I understand what I'm supposed to do next.

## Benefits

Four strengths stand out.

- The path to the main action is always visible.
- It sits in thumb reach on mobile.
- It stays available no matter the scroll position.
- It rarely interrupts the reading flow.

These pay off when a page narrows down to one primary action. The button waits at the same spot, ready when the reader finishes the content and thinks "what now?".

## Drawbacks

It isn't free, though.

- It occupies screen space permanently.
- Accidental taps happen.
- Accessibility needs attention (screen readers, contrast, size).
- Pages without a single main action get confusing.
- Overuse makes the UI feel noisy.

Accidental taps worry me the most. A spot that's easy to reach is also easy to hit by mistake. If the FAB triggers something destructive, like deleting or sending, add a confirmation step.

:::note
Set an accessible label with `aria-label`. An icon-only button says nothing to a screen reader.
:::

## Implementation patterns

On the web, the base is CSS `position: fixed` plus `z-index`. Add a little JavaScript and you get several useful variants.

### Basic FAB

```css
.fab {
  position: fixed;
  right: 16px;
  bottom: 16px;
  z-index: 100;
  width: 56px;
  height: 56px;
  border-radius: 50%;
}
```

If `z-index` doesn't work, inspect the parent elements. A stacking context on a parent usually wins over a high `z-index` on the button.

### Speed dial

Tap the FAB and child buttons fan out. Two or three children stay simple. Beyond five, the interaction gets complicated.

### Scroll-aware FAB

Show it on scroll down, hide it on scroll up. On long articles a permanent FAB gets in the way. Conditional visibility is a kind gesture to the reader.

### Badge FAB

Stack a count, like unread messages, on top of the FAB. It constantly tells the user something is waiting. Just remember that an ever-changing number can tire people out.

### Label FAB

An icon alone is sometimes unclear. Add a label. A common pattern: always show the label on wide screens, and reveal it on long-press on small screens.

### Extended FAB

The extended FAB puts an icon and text side by side. Use it when the main action's meaning should be clear at first glance.

## Real-world examples

- Gmail: the compose button in the corner.
- Google Maps: the directions button.
- Instagram: the create post button.

Every example narrows down to one clear primary action. Two FABs on one screen is rare. That "one FAB per screen" rule matters more than any styling detail.

## Checklist before adding a FAB

Ask four questions first.

| Question | What to check |
| --- | --- |
| One main action? | Can you name the single action this page exists for? |
| Thumb reach? | Can it be reached one-handed? |
| Mobile-first? | Is mobile the main usage? If not, a header button may be enough. |
| Obstructive? | Does a permanent button cover content people read? |

If any answer is "No", consider another pattern. A normal button or a CTA near the top works fine for plenty of pages.

:::step
When in doubt: pick one main action. Decide where it lives on mobile. Then test on a real device whether a permanent button still feels annoying.
:::

## Summary

FABs are handy, but overuse backfires. Think of a FAB as a tool built for "one hero action per page", and you won't go wrong.

## References

1. Material Design — Floating action button: <https://m3.material.io/components/floating-action-button/overview>
2. MDN — position: <https://developer.mozilla.org/en-US/docs/Web/CSS/position>
3. WCAG 2.2 — Target Size (Minimum): <https://www.w3.org/TR/WCAG22/#target-size-minimum>
