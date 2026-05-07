---
id: 2039
slug: "gas-auth-bypass-en"
title: "[GAS] How to Fix \"Google hasn't verified this app\" (Authorization Guide)"
status: "publish"
date: "2025-12-13T19:00:00"
excerpt: ""
link: "https://www.zidooka.com/archives/2039"
raw_html: true
---
<!-- wp:paragraph -->
<p>When you run a Google Apps Script (GAS) for the first time, or when you add new permissions (such as access to Gmail or Sheets), you will see an "Authorization required" popup.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>As you proceed, you will encounter a scary warning screen saying "Google hasn't verified this app", often labeling it as "unsafe". Many users stop here, fearing they've done something wrong.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>This simply means "Google hasn't reviewed your script as an official app". If it's a script you wrote yourself, it is safe to proceed.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>This article explains the steps to bypass this warning screen and execute your script, with screenshots.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Recently, some users see a newer tester warning with <code>Continue</code> and <code>Back to safety</code> instead of the old <code>Advanced</code> flow. If that is what you are seeing, read <a href="https://www.zidooka.com/archives/4163">this updated explanation</a>.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="step-1-review-permissions">Step 1: Review Permissions</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>When you run the script, you will first see this dialog.
Click "Review permissions".</p>
<!-- /wp:paragraph -->

<!-- wp:image {"id":2036,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="http://www.zidooka.com/wp-content/uploads/2025/12/gas-auth-03-advanced-1.jpg" alt="Click Advanced" class="wp-image-2036"/></figure>
<!-- /wp:image -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="step-2-choose-an-account">Step 2: Choose an Account</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Select the Google account you want to use to run the script.</p>
<!-- /wp:paragraph -->

<!-- wp:image {"id":2034,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="http://www.zidooka.com/wp-content/uploads/2025/12/gas-auth-01-required-1.jpg" alt="Authorization required" class="wp-image-2034"/></figure>
<!-- /wp:image -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="step-3-bypass-the-warning-screen-important">Step 3: Bypass the Warning Screen (Important)</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Here, you will see a screen with a red alert icon saying "Google hasn't verified this app".
You might be tempted to click the blue "Back to safety" button, but do not click it (it will cancel the execution).</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Instead, click the "Advanced" link in the bottom left corner.</p>
<!-- /wp:paragraph -->

<!-- wp:image {"id":2035,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="http://www.zidooka.com/wp-content/uploads/2025/12/gas-auth-02-choose-account-1.jpg" alt="Choose an account" class="wp-image-2035"/></figure>
<!-- /wp:image -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="step-4-go-to-the-unsafe-page">Step 4: Go to the "Unsafe" Page</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Once the advanced details open, a link will appear at the bottom.
Click "Go to [Project Name] (unsafe)".</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><em>Note: It says "unsafe", but this just means it hasn't been verified by Google. If it's your own script, it is safe to proceed.</em></p>
<!-- /wp:paragraph -->

<!-- wp:image {"id":2031,"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="http://www.zidooka.com/wp-content/uploads/2025/12/gas-auth-05-allow-1024x691.jpg" alt="許可をクリック" class="wp-image-2031"/></figure>
<!-- /wp:image -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="step-5-allow-access">Step 5: Allow Access</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Finally, you will see what the script is requesting access to (e.g., Edit access to Spreadsheets).
Review the permissions and click "Allow" at the bottom.</p>
<!-- /wp:paragraph -->

<!-- wp:image {"id":2037,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="http://www.zidooka.com/wp-content/uploads/2025/12/gas-auth-04-unsafe-1.jpg" alt="Go to unsafe page" class="wp-image-2037"/></figure>
<!-- /wp:image -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="done">Done</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Your script will now execute.
You only need to do this authorization process once (unless you add new permissions to the script later).</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Don't be afraid of the warning—just follow the path: Advanced -&gt; Go to (unsafe) -&gt; Allow.</p>
<!-- /wp:paragraph -->
