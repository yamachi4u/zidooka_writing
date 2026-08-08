---
title: "Publish WordPress Posts from ChatGPT: A Beginner's Guide to GitHub Actions and the REST API"
slug: chatgpt-github-actions-wordpress-rest-api-en
status: publish
categories:
  - ChatGPT
  - Wordpress
tags:
  - ChatGPT
  - Codex
  - GitHub Actions
  - WordPress REST API
  - Automation
featured_image: ../../images/2026/08/chatgpt-github-actions-wordpress-rest-api-thumbnail.png
---

The origin of this article is also a real example of the system it explains.

I am currently in bed. From my phone, I asked ChatGPT to add scheduled publishing to the GitHub Actions workflow used by ZIDOOKA. The repository was inspected, the code was updated, tests were run, a pull request was opened, and the change was merged. Then I sent one more request:

> “Write and publish a beginner's guide about posting articles from ChatGPT with a REST API and GitHub Actions.”

I was not sitting at my computer. The conversation became the interface, while the reproducible work ran elsewhere.

:::conclusion
Your phone becomes a device for giving direction and reviewing outcomes. GitHub Actions and WordPress handle execution, credentials, history, and publishing.
:::

## The architecture

The workflow has six stages:

1. Describe the article and desired outcome in ChatGPT
2. ChatGPT or Codex creates Japanese and English Markdown drafts
3. Add the drafts and images to a GitHub repository
4. Review and merge the pull request
5. Let GitHub Actions run a Node.js publishing script
6. Send the post and media to the WordPress REST API

ChatGPT does not need to keep clicking through the WordPress admin interface. The useful design choice is to ==separate conversation, content, execution, credentials, and publication==.

OpenAI's official documentation describes connectors as integrations that allow ChatGPT and Codex to read information from services such as GitHub and take authorized actions. The Codex cloud guide also describes connecting a GitHub repository, running work in a cloud environment, and reviewing the result before opening a pull request.

:::note
Available integrations and interfaces depend on your plan, device, and workspace configuration. This guide assumes that your ChatGPT or Codex environment can access the target GitHub repository.
:::

## Why put GitHub Actions in the middle?

It is technically possible for an AI environment to call WordPress directly. GitHub Actions makes the production workflow easier to audit and reproduce.

- Markdown drafts stay in Git history
- A pull request provides a review boundary
- WordPress credentials stay in GitHub Secrets
- Every post runs in the same environment
- Failures are visible in Actions logs
- Bilingual and scheduled publishing can share one implementation

GitHub Actions becomes a ==reproducible workbench== between ChatGPT and WordPress.

## What you need

- A WordPress site
- A WordPress user and application password with permission to publish
- A GitHub repository with Actions enabled
- A script that converts Markdown and calls the WordPress REST API
- A ChatGPT or Codex environment connected to GitHub, or another way to add drafts to the repository

WordPress creates posts through `POST /wp/v2/posts`. The request can include fields such as `title`, `content`, `slug`, `status`, `categories`, `tags`, and `featured_media`.

## A minimal WordPress REST API request

The smallest Node.js example with Axios looks like this:

```js
import axios from 'axios';

const apiUrl = process.env.WP_API_URL;
const user = process.env.WP_USER;
const password = process.env.WP_APP_PASSWORD.replace(/\s/g, '');

const authorization = Buffer
  .from(`${user}:${password}`)
  .toString('base64');

const response = await axios.post(
  `${apiUrl}/wp/v2/posts`,
  {
    title: 'A post created from ChatGPT',
    content: '<p>Hello from GitHub Actions.</p>',
    slug: 'hello-from-github-actions',
    status: 'publish'
  },
  {
    headers: {
      Authorization: `Basic ${authorization}`
    }
  }
);

console.log(response.data.link);
```

A production implementation should also handle Markdown conversion, media uploads, category IDs, update-by-slug behavior, and retries.

:::warning
Never place your WordPress username or application password in the article, repository, or ChatGPT prompt.
:::

## Store credentials in GitHub Secrets

This implementation uses the following repository secrets:

```text
WP_API_URL
WP_MEDIA_API_URL
WP_USER
WP_APP_PASSWORD
WP_TIMEZONE
```

GitHub documents repository secrets under `Settings` → `Secrets and variables` → `Actions`.

Reference them from the workflow without hard-coding the values:

```yaml
env:
  WP_API_URL: ${{ secrets.WP_API_URL }}
  WP_MEDIA_API_URL: ${{ secrets.WP_MEDIA_API_URL }}
  WP_USER: ${{ secrets.WP_USER }}
  WP_APP_PASSWORD: ${{ secrets.WP_APP_PASSWORD }}
  WP_TIMEZONE: ${{ secrets.WP_TIMEZONE }}
```

## Use a predictable Markdown format

Store metadata in frontmatter:

```markdown
---
title: "Article title"
slug: article-slug-en
status: publish
categories:
  - ChatGPT
tags:
  - GitHub Actions
  - WordPress REST API
featured_image: ../../images/thumbnail.png
---

The article body starts here.
```

A filename convention makes automation simpler:

```text
article-name-jp.md
article-name-en.md
```

In ZIDOOKA's pipeline, specifying either file lets the publishing command find the matching language draft in the same directory and publish both.

## Publish with GitHub Actions

Start with a manually triggered `workflow_dispatch` workflow:

```yaml
name: Publish WordPress Article

on:
  workflow_dispatch:
    inputs:
      draft:
        description: Markdown file path
        required: true
        type: string

jobs:
  publish:
    runs-on: ubuntu-latest
    env:
      WP_API_URL: ${{ secrets.WP_API_URL }}
      WP_USER: ${{ secrets.WP_USER }}
      WP_APP_PASSWORD: ${{ secrets.WP_APP_PASSWORD }}
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: 22
      - run: npm ci --ignore-scripts
      - run: node src/index.js post-pair "${{ inputs.draft }}"
```

GitHub supports running a `workflow_dispatch` workflow from the Actions page, GitHub CLI, or the REST API.

ZIDOOKA also listens for merged pull requests containing article drafts. After a merge, the workflow identifies the changed Japanese lead files and publishes each bilingual pair.

## What working from bed looks like

Once the pipeline exists, the day-to-day instruction can be short.

:::example
“Write a beginner's guide connecting ChatGPT, GitHub Actions, and the WordPress REST API. Open with the true story that I added scheduled publishing from bed. Create Japanese and English versions, generate a thumbnail, and publish them to ZIDOOKA.”
:::

The AI-assisted workflow can then:

1. Read repository-specific writing rules
2. Verify current official information
3. Draft both language versions
4. Generate a thumbnail
5. Validate frontmatter and links
6. Publish the changes through GitHub
7. Let Actions publish them to WordPress

The human defines the purpose, approves consequential actions, and reviews the result.

## Let WordPress handle scheduled publication

Do not keep a GitHub Actions runner waiting until the publication time. Send a future date and `future` status to WordPress, then let WordPress release the post at the scheduled time.

ZIDOOKA supports this frontmatter field:

```yaml
publish_at: "2026-08-15 09:00"
```

The manual Actions workflow also offers three modes:

- `publish_now`: publish immediately
- `schedule_at`: publish at a specified time
- `next_available`: select the next unoccupied day at 09:00

The official WordPress REST API schema defines post statuses including `publish`, `future`, and `draft`.

## Security rules that matter

:::warning
More automation makes permission boundaries and pre-publication review more important. A conversational interface should not mean unlimited authority.
:::

- Use a dedicated WordPress user and a revocable application password
- Never print secrets in workflow logs
- Keep GitHub Actions `permissions` minimal
- Validate file paths and schedule inputs
- Review article and code changes in pull requests
- Use the slug as an idempotency key to avoid duplicate posts
- Stop on API failures instead of silently continuing
- Never move private information into a public article or repository

## The deeper value of this setup

This is more than “AI wrote a blog post.”

Conversation is flexible enough for an imprecise goal. Git, workflows, scripts, and APIs convert that goal into deterministic execution. The real value is ==connecting a human instruction layer to a reproducible machine layer==.

It also changes where work can happen. From a bed, train, or café, a phone can hand off substantial work to cloud infrastructure. OpenAI's Codex cloud guide explicitly describes starting and reviewing work when you are away from your development machine.

:::conclusion
The goal is not to remote-control a desktop from a phone. It is to put a reproducible workflow in the cloud and use the phone to communicate intent.
:::

## References

- [Plugins: how ChatGPT and Codex connect to tools such as GitHub](https://learn.chatgpt.com/docs/plugins)
- [Codex cloud: work in GitHub-connected cloud environments](https://learn.chatgpt.com/docs/cloud)
- [GitHub Actions workflow syntax](https://docs.github.com/en/actions/reference/workflows-and-actions/workflow-syntax)
- [Using secrets in GitHub Actions](https://docs.github.com/en/actions/how-tos/write-workflows/choose-what-workflows-do/use-secrets)
- [Manually running a workflow](https://docs.github.com/en/actions/how-tos/manage-workflow-runs/manually-run-a-workflow)
- [WordPress REST API: Posts](https://developer.wordpress.org/rest-api/reference/posts/)
