---
title: "[WCAG] Is alt text not enough? Create a \"Description Refuge\" with aria-describedby"
date: 2026-02-01 09:00:00
categories: 
  - アクセシビリティ
slug: aria-describedby-intro-en
tags: 
  - WCAG
  - ARIA
  - HTML
  - Accessibility
status: publish
---

When you start taking WCAG and accessibility seriously, you inevitably hit a wall.

"Alt attributes (alternative text) are important. Images should have meaning."

We understand this much. But as you implement it, there comes a moment when your hands stop.

"The meaning of the image is correct. But there's too much information I want to explain..."
"Do I really write *all* of this in the alt text? Doesn't that feel wrong?"

If you've ever felt this way, that sensation is **actually very healthy**.
That discomfort is a warning that you are "stuffing too much into alt".

In this article, we'll explain how to handle "complex descriptions" that alt attributes alone can't solve, using **aria-describedby** as the solution.

## The True Nature of That Discomfort is Not "Amount of Information"

Many people misunderstand this point.

"The alt text is getting too long. I need to shorten it."

But the core issue isn't the "length".
The real problem is that **"you are trying to stuff information with different roles into the single box called alt".**

### Resetting the Role of Alt

Let's rethink the role of alt once and for all.
Basically, alt is responsible for just this:

**Briefly conveying "What is this?"**

For example:

```html
<img src="chart.png" alt="Sales trend chart">
```

This is sufficient.
"The meaning of the numbers", "reasons for increase/decrease", and "precautions" are not originally the job of alt.

### Common Mistakes

As a result of wondering where to write the "description", many people end up doing this:

```html
<!-- ❌ NG Example: Stuffing everything into alt -->
<img
  src="chart.png"
  alt="Sales trend chart showing an upward trend from 2023 to 2024, especially growing significantly in Q3"
/>
```

This is a trap you fall into precisely because you are serious about it, but structurally, it's broken.
This is because you are putting "Name", "Description", and "Analysis" all into `alt`.

## Enter "ARIA"

This is where **ARIA (Accessible Rich Internet Applications)** finally comes in.

ARIA is a mechanism to supplement "meaning, role, and relationships" that cannot be fully expressed with HTML alone.
Roughly speaking, it's a set of attributes to teach machines information that is "visible to the eye, but not understandable by HTML alone".

### What is aria-describedby?

Among them, `aria-describedby` has a very clear role.

**An attribute to link "The description for this element is written here".**

The points are:
*   It is not an attribute for writing a new description.
*   **It is an attribute for associating an existing description.**

### Correctly Decomposing the Example Where Alt Hit Its Limit

Let's rewrite the previous example by dividing the roles.

```html
<!-- ✅ OK Example: Dividing roles -->
<img
  src="plan.png"
  alt="Pricing Plan Diagram"
  aria-describedby="plan-desc"
/>

<!-- Write the description as body text -->
<p id="plan-desc">
  The Basic Plan is $9.80/month with ads.
  The Premium Plan is $19.80/month with no ads and allows family sharing.
</p>
```

Here is what is happening:

1.  **alt**: Tells the name "This is a Pricing Plan Diagram".
2.  **Body Text**: Detailed description is written normally in `<p>` tags, etc.
3.  **aria-describedby**: Links "This `<p>` is the description of that image".

The amount of information has not decreased. **We just separated the locations.**
Screen readers can read the name and description in stages, like "Image, Pricing Plan Diagram. Description: The Basic Plan is...".

## Decision Criteria: "Should I use aria-describedby?"

For ZIDOOKA!, the criteria is simple.

**If writing it in alt starts to feel like an "explanation", consider aria-describedby.**

Specifically, consider writing the following information in a separate place (body text or notes) and linking it, rather than forcing it into alt:

*   Conditions / Prerequisites
*   Precautions
*   Input rules (for forms)
*   Supplementary explanations
*   Details of error messages

## WCAG Does Not Say "Use ARIA"

It is often misunderstood, but WCAG (Web Content Accessibility Guidelines) does not directly say "Use ARIA" or "Make alt short".

What it says is the principle: **"Provide information structurally according to its role."**
`aria-describedby` is just one of the tools to implement that principle in HTML.

## Summary: If You Feel Discomfort with Alt, It's a Chance for "Structuring"

If you thought "It's weird to write all this" while writing alt text, it's not a lack of knowledge.
**It's a sign that you've started to be conscious of structure.**

The answer to that discomfort leads to `aria-describedby`, `aria-labelledby`, and the philosophy of WCAG.
Stop "stuffing everything into alt" and create a "refuge for descriptions".

---

## Reference Links (Official / Primary Sources)

Here is a summary of sites where people who got stuck on alt can go for "answer checking".

### WCAG (The Standard Itself)
*   [WCAG 2.2 Recommendation](https://www.w3.org/TR/WCAG22/)
    *   The official standard of "what needs to be met".
*   [1.1.1 Non-text Content](https://www.w3.org/TR/WCAG22/#non-text-content)
    *   The origin of what is required for alt. You can read that it does not say "Write everything in alt".

### ARIA (Specifications and Guides)
*   [WAI-ARIA 1.2 Specification - aria-describedby](https://www.w3.org/TR/wai-aria-1.2/#aria-describedby)
    *   The role of "associating a description" is clearly stated.
*   [ARIA Authoring Practices Guide (APG) - Names and Descriptions](https://www.w3.org/WAI/ARIA/apg/practices/names-and-descriptions/)
    *   **Super Important**. A god-tier page that systematically explains the difference between "Name" and "Description".

### Implementation Guides
*   [WAI Tutorials - Images Concepts](https://www.w3.org/WAI/tutorials/images/)
    *   Carefully breaks down cases that cannot be solved with alt alone.
*   [MDN - aria-describedby](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Attributes/aria-describedby)
    *   Implementation examples are easy to understand.
