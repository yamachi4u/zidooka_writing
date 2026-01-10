---
title: "Why You Still Get 'Permission denied (publickey)' Even With ssh -vvv"
date: 2026-01-04 12:00:00
categories: 
  - Network / Access Errors
tags: 
  - SSH
  - Troubleshooting
  - CLI
  - Debugging
status: publish
slug: ssh-vvv-permission-denied-en
featured_image: ../images/image.png
---

SSH connection failed.
You got `Permission denied (publickey)`, so you ran `ssh -vvv`.
The logs show that the key is being sent.
Yet, you still cannot connect.

This article is written for those who have reached this stage.

**Prerequisites for this article:**
*   You are using public key authentication, not password authentication.
*   You have successfully run `ssh -vvv user@host`.
*   You are still stuck without knowing the cause.

We will not cover "how to create keys" or "what is SSH".

---

## The Problematic Log (This is a "Successful Failure")

First, let's look at the log as it is.

```text
debug1: Offering public key: /home/user/.ssh/id_rsa RSA SHA256:xxxx
debug3: send packet: type 50
debug2: we sent a publickey packet, wait for reply
debug1: Authentications that can continue: publickey
debug1: Offering public key: /home/user/.ssh/id_rsa RSA SHA256:xxxx
debug1: No more authentication methods to try.
Permission denied (publickey).
```

This log actually contains a lot of information.

### "Offering public key" Does Not Mean Authentication Success

I will write the most important thing first.

:::warning
**Even if "Offering public key" appears, authentication has not succeeded.**
:::

This line means:
"The client **attempted** to authenticate using this public key."
It does **not** mean:
"The server accepted that key."

---

## Breaking Down the Log Line by Line

### 1. Offering the Key

```text
debug1: Offering public key: /home/user/.ssh/id_rsa RSA SHA256:xxxx
```

*   The client sent the **public key** corresponding to `id_rsa` to the server.
*   The key file is readable.
*   There is almost no problem on the SSH client side.

### 2. Sending Authentication Request

```text
debug3: send packet: type 50
debug2: we sent a publickey packet, wait for reply
```

*   Sent public key authentication packet.
*   Waiting for server response.

### 3. Server Says "Publickey Authentication is Possible"

```text
debug1: Authentications that can continue: publickey
```

This is important.

*   The server says **public key authentication itself is enabled**.
*   It is not a setting like `PasswordAuthentication no`.

In other words,
**"The method is correct, but that key is rejected."**

### 4. Offering the Same Key Again, Then Ending

```text
debug1: Offering public key: /home/user/.ssh/id_rsa RSA SHA256:xxxx
debug1: No more authentication methods to try.
Permission denied (publickey).
```

*   This is the only usable key.
*   It was rejected.
*   Result: `Permission denied (publickey)`.

---

## What We Can Learn From This (Important)

The **facts confirmed** from this log are:

:::note
*   The key file exists.
*   The client is sending the key correctly.
*   The server accepts public key authentication.
*   **However, that key could not be used for authentication.**
:::

👉 The problem is not "how the key is presented".
👉 The problem is that **the key is not registered as a valid key on the server side**.

---

## Typical Patterns for Offering public key → Rejected

Here are the practical causes.

### 1. authorized_keys Belongs to Another User

The most common cause.

*   The `user` in `ssh user@host`
*   The **owner** of `~/.ssh/authorized_keys`

If these do not match, it will always be rejected.

**Example:**
*   The key was placed by `root`.
*   You are logging in as `deploy`.

### 2. Content of authorized_keys is Different

*   Pasted the wrong key.
*   Forgot to delete an old key.
*   Regenerated the key locally.

Even with the "same key filename", if the content is different, it is a different key.
For how to verify key pairs, see the article below:

[Not Sure If Your SSH Key Pair Is Actually Correct? Check This First](/ssh-key-pair-verification-en)

### 3. Permissions are Too Loose

SSH is very strict about permissions.

**Minimum Requirements:**
```bash
~/.ssh            700
~/.ssh/authorized_keys 600
```

If any of these are too loose, it will be rejected silently.
In this case too, it becomes `Offering public key` → `Permission denied`.

### 4. sshd_config Settings

Be careful in environments where the following are changed:

*   `AuthorizedKeysFile` is different from default.
*   Home directory is different from normal.
*   `chroot` environment.

This is common in VPS or managed hosting scenarios.

---

## What You Should Do Next

If you see this log with `ssh -vvv`, the next place to look is not the log.

**You should check:**

:::step
1.  `~/.ssh/authorized_keys` on the server side.
2.  Owner and permissions of that file.
3.  Are you really logging in as that user?
:::

If you can log in as `root`, **visually checking** `authorized_keys` is the fastest way.

---

## Summary

With `ssh -vvv`:

*   `Offering public key` appears.
*   Still `Permission denied (publickey)`.

This state is a very specific failure:

:::conclusion
"The key is sent correctly, but the server does not treat that key as valid."
:::

If you have come this far, it's not that you don't understand how SSH works.
It's just that your assumption is misaligned with the "reality" of the server side.

The log has spoken enough.
Next is the turn to look inside the server.
