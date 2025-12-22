---
title: "Why Your Featured Image Never Appears in WordPress (And How to Fix It)"
date: 2025-02-10 20:00:00
categories: 
  - WordPress
tags: 
  - WordPress
  - Troubleshooting
  - Featured Image
  - Child Theme
status: publish
slug: wordpress-featured-image-troubleshoot-en
featured_image: ../images/thumbnails.png
---

In WordPress, you may encounter a frustrating situation where your featured image never appears, no matter what you try. You check functions.php, review single.php, adjust Gutenberg settings, and yet nothing changes. 

This is a common problem that spans multiple layers: theme setup, post type configuration, template rendering, and CSS. Most cases are not caused by a single mistake, but by overlooking one critical step that cascades into confusion.

This article walks through a practical checklist based on real troubleshooting experience, explaining exactly what to verify when your featured image refuses to show up.

## [First Check] Does the Featured Image Panel Appear in the Post Editor?

The first thing to verify is whether the Featured Image panel exists in the post editor sidebar. If it's completely missing, the issue is **not** related to the individual post or template rendering.

**A missing editor UI usually indicates a theme or post type configuration problem.**

A common culprit is a child theme whose parent theme is not properly loaded. Even when the parent theme folder exists on the server, WordPress can silently fail to recognize it. The site will still render, leaving you with confusing missing features like the absent Featured Image UI.

## [Next Check] Is post-thumbnails Support Enabled?

Next, verify that featured images are enabled in your theme's functions.php:

```php
add_theme_support('post-thumbnails');
```

However, even if this line exists, it may not work correctly if the parent theme is not loaded as expected. This is why the previous check (verifying the UI exists) is so important.

If you are using custom post types with `register_post_type()`, make sure the supports array includes `thumbnail`:

```php
register_post_type('custom_post', array(
    'supports' => array('title', 'editor', 'thumbnail'), // ← Don't forget 'thumbnail'
    // ... other parameters
));
```

Without this setting, featured images will not be available for that post type, even if everything else looks correct.

## [Template Check] Is the_post_thumbnail() Actually Called?

Once the editor shows the Featured Image panel, inspect the actual template files. Check whether `the_post_thumbnail()` is being called in single.php, archive.php, or other relevant templates:

```php
<?php
if (has_post_thumbnail()) {
    the_post_thumbnail('medium');
}
?>
```

Verify that:
- The function exists in your template
- It's not wrapped in a conditional that always evaluates to false
- The output isn't being removed by filter hooks

**The most effective approach is to troubleshoot in order. Skipping steps leads to hours of debugging the wrong section of code.** Most featured image issues stem from configuration problems, not rendering issues.

## [CSS Check] Is the Image Hidden by Styling?

If `the_post_thumbnail()` is being called but the image doesn't appear on the front end, CSS may be hiding it unintentionally.

Use your browser's developer tools (F12) to inspect the element:
- Check if `display: none` or `visibility: hidden` is applied
- Look for `opacity: 0`
- Check for `z-index` issues or positioning that places it off-screen
- Verify that image dimensions are not set to `0px`

In heavily customized themes, style conflicts from multiple stylesheets can easily hide elements you thought were visible.

## [The Final Piece] Is Your Child Theme's style.css Configured Correctly?

Finally, do not overlook the `Template` field in your child theme's style.css. This value is critical:

```css
/*
Theme Name: My Child Theme
Template: parent-theme-folder-name
*/
```

**The Template value must match the parent theme's directory folder name exactly, not its display name.**

A mismatch here will silently break many theme features, including the featured image UI. WordPress may render the site successfully, but the parent theme will not load, making all functions.php additions and child theme hooks ineffective.

### My Real-World Example

In my troubleshooting, the parent theme directory was `picostrap5`, but I had mistakenly configured the Template field incorrectly. The result:
- The Featured Image panel disappeared from the editor
- functions.php changes had zero effect
- single.php modifications were ignored
- The child theme appeared to be working while silently broken

It took investigating the child theme structure itself to discover that the parent theme was never being recognized.

For more details on this issue, see:

- [このテーマは壊れています。親テーマが見つかりません【WordPress／子テーマの落とし穴】](https://www.zidooka.com/archives/2279)
- [WordPress Error: "The Parent Theme Is Missing" — Even Though It Exists](https://www.zidooka.com/archives/2283)

## [Debugging Checklist] The Correct Order to Troubleshoot

Featured image issues in WordPress span a wide range of causes. The most effective approach is to troubleshoot from the top down, in this exact order:

1. **Does the Featured Image panel appear in the post editor?**
2. **Is `add_theme_support('post-thumbnails')` present in functions.php?**
3. **For custom post types, is `thumbnail` included in the supports array?**
4. **Is `the_post_thumbnail()` called in the template file (single.php, etc.)?**
5. **Is the image hidden by CSS styling?**
6. **Does the child theme's style.css have the correct Template field?**

By following this order, you avoid hours of unnecessary debugging in the wrong sections of code. I hope this guide helps you resolve your featured image issue without the confusion I experienced.
