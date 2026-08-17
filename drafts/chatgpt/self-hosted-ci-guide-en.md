---
title: "What Is a CI Environment? Can You Run Your Own? A Practical Guide to Self-Hosted CI and GitHub Actions Runners"
categories:
  - general
tags:
  - CI
  - GitHub Actions
  - self-hosted runner
  - DevOps
  - GitHub
status: publish
slug: self-hosted-ci-guide-en
---

CI, or Continuous Integration, can sound like something reserved for large engineering teams. In practice, the core idea is simple: when code changes, a separate environment automatically runs checks such as tests, builds, linting, or type checking.

:::conclusion
A CI environment is the machine or execution environment that automatically checks your code. You can absolutely run that environment yourself.
:::

## What is a CI environment?

Suppose you keep a project on GitHub and want a set of checks to run every time you push code.

```text
Your PC
  ↓ git push
GitHub
  ↓
CI environment
  ├─ npm install
  ├─ npm run build
  ├─ npm test
  └─ lint / type check
```

With GitHub Actions, the CI environment is usually a runner provided by GitHub.

A workflow might look like this:

```yaml
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - run: npm ci
      - run: npm test
```

Instead of testing only on your own machine, the project is checked automatically in a separate, standardized environment. This helps catch the classic "it works on my machine" problem.

## Can you run CI yourself?

Yes.

There are two broad approaches.

The first is to keep GitHub Actions as the workflow controller while running the actual jobs on your own hardware. GitHub calls this a **self-hosted runner**.

The second is to operate more of the stack yourself, using software such as Jenkins or self-managed GitLab so that the Git server and CI infrastructure are both under your control.

For an individual developer who wants to experiment with self-hosted CI, GitHub Actions plus a self-hosted runner is usually the easiest place to start.

## GitHub Actions self-hosted runners

A self-hosted runner lets GitHub manage the workflow while your own machine performs the work.

```text
GitHub repository
      ↓ workflow starts
GitHub Actions
      ↓ assigns job
Your PC / mini PC / server
      ↓
build / test / deploy
```

GitHub documents that self-hosted runners can run on physical machines, virtual machines, containers, on-premises infrastructure, or cloud systems.

[GitHub Docs: Self-hosted runners](https://docs.github.com/en/actions/concepts/runners/self-hosted-runners)

Compared with GitHub-hosted runners, you get much more control over the operating system, CPU, memory, GPU, installed software, storage, and access to services on your local network.

For example, a GPU workstation can be registered as a runner so that a GitHub workflow can trigger AI or other GPU-heavy workloads on your own hardware.

## What changes in the workflow?

After registering a self-hosted runner, the key change is the `runs-on` value.

```yaml
jobs:
  build:
    runs-on: self-hosted
    steps:
      - uses: actions/checkout@v4
      - run: npm ci
      - run: npm run build
```

In simple terms:

```yaml
runs-on: ubuntu-latest
```

means "use a GitHub-hosted machine," while:

```yaml
runs-on: self-hosted
```

means "use one of my registered machines."

You can also assign labels and route specific jobs to runners with particular operating systems, hardware, or purposes.

[GitHub Docs: Using self-hosted runners in a workflow](https://docs.github.com/en/actions/how-tos/manage-runners/self-hosted-runners/use-in-a-workflow)

## Why bother running your own CI machine?

The benefit is not only about reducing usage of GitHub-hosted compute.

You can choose the hardware yourself. If a workload needs large amounts of RAM, a GPU, fast local storage, or unusual peripherals, a self-hosted runner can provide them.

You can also preinstall large or specialized software stacks instead of rebuilding the same environment for every job.

Another useful case is access to resources on a home, office, or lab network.

:::example
A research team could store datasets on a NAS, trigger an analysis workflow from a GitHub push, execute it on a lab runner, and preserve only the resulting artifacts or reports.
:::

## You can self-host the entire Git and CI stack too

If you want full control, GitHub does not have to be part of the architecture at all.

```text
Self-hosted Git server
    ↓ push
Self-hosted CI server
    ↓
test
    ↓
build
    ↓
deploy
```

A self-managed GitLab installation or a Git server combined with Jenkins can keep source control and CI/CD entirely inside your own infrastructure.

The tradeoff is operational responsibility: updates, backups, authentication, security, monitoring, and incident response become your job as well.

## The most important caveat: security

Self-hosted runners are powerful, but they need to be treated as real infrastructure.

GitHub warns that self-hosted runners do not have the same guarantee of a clean, ephemeral machine for every job. If untrusted workflow code is allowed to run, that code may be able to compromise the runner or access credentials and services available to it.

:::warning
Be especially careful with public repositories. GitHub specifically warns that a malicious pull request can potentially execute dangerous code on a self-hosted runner, which is why self-hosted runners are generally recommended for trusted workloads and private repositories.
:::

[GitHub Docs: Secure use reference](https://docs.github.com/en/actions/reference/security/secure-use)

For that reason, a dedicated mini PC, VM, containerized environment, or otherwise isolated server is usually a better runner than your everyday personal workstation.

## An old PC can become a CI server

This is one of the appealing parts of self-hosted CI.

An unused desktop or mini PC can be given a lightweight Linux installation, registered as a self-hosted runner, and turned into a machine that waits for GitHub jobs.

When a push happens, it can automatically perform a pipeline such as:

```text
checkout code
↓
install dependencies
↓
run tests
↓
build
↓
deploy if needed
```

:::conclusion
You do not need to build a large CI platform to start self-hosting. A practical first step is to use GitHub Actions normally, then add an unused PC or mini PC as a self-hosted runner when you need more control. That is already a genuine self-hosted CI environment.
:::
