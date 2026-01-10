---
title: "Xserver VPS Permission denied (publickey) | Why You Can't Connect After Registering SSH Keys"
date: 2026-01-04 11:00:00
categories: 
  - Network / Access Errors
tags: 
  - Xserver
  - VPS
  - SSH
  - Troubleshooting
  - Permission denied
status: publish
slug: xserver-vps-ssh-permission-denied-en
featured_image: ../images/xserver-ssh.png
---

You registered your SSH public key in Xserver VPS, but you still can't log in.

```text
Permission denied (publickey)
```

The IP, username, and port seem correct, yet the connection fails.

This is a very common initial configuration mistake with Xserver VPS.
The conclusion is simple: even if the SSH key is registered in the panel, it is not enabled on the OS level.

---

## Conclusion: "Key Registration" Does Not Mean "SSH Ready"

In Xserver VPS, it is easy to assume:

*   I registered the SSH public key in the control panel.
*   Therefore, I can connect via SSH.

This is **incorrect**.

:::warning
Registering an SSH key in the control panel alone does not establish SSH access for general users.
:::

### Why "Permission denied (publickey)" Occurs

When this error appears, SSH itself is usually not broken.
The problem is a discrepancy:

*   **Control Panel**: SSH key is registered.
*   **Server Internal**: `authorized_keys` is not correctly configured.

In other words, **"The key exists, but it is not being used."**

---

## Common Misunderstandings with Xserver VPS

### 1. Thinking Registration is Enough

The SSH key registration in the Xserver VPS control panel does not automatically complete the user configuration inside the OS.
This is especially true in the following cases:

*   Immediately after VPS re-installation.
*   When creating a user later.

### 2. Confusing Public and Private Keys

```text
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAA...
```

This is a **Public Key**.

To connect via SSH, you need:

*   **Client side**: Private Key
*   **Server side**: Corresponding Public Key

Even if the public key is registered, if the private key does not match, you will get `Permission denied (publickey)`.

### 3. Ignoring Permissions

In Xserver VPS (and Linux in general), if any of the following are incorrect, SSH will fail immediately:

*   `.ssh` directory: `700`
*   `authorized_keys`: `600`
*   Owner: The target user

If even one permission bit is off, SSH will mercilessly reject the connection.

---

## Correct Solution

### Configure the OS Side with Root Privileges

You must configure this inside the server, not just in the control panel.

```bash
mkdir -p /home/USER/.ssh
chmod 700 /home/USER/.ssh

echo "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAA..." >> /home/USER/.ssh/authorized_keys

chmod 600 /home/USER/.ssh/authorized_keys
chown -R USER:USER /home/USER/.ssh
```

Only then are the conditions met:

1.  SSH
2.  Public Key Authentication
3.  User Connection

### What to Check If It Still Fails

*   Does the private key actually correspond to the public key?
*   Are there any line breaks or missing characters in the key file?
*   Is public key authentication disabled in `sshd_config`?

Mismatched key pairs are a very common cause.

---

## Official Documentation Note

The Xserver VPS manual officially states that OS-side configuration is required after registering SSH keys.

> **Setting up Key Authentication for an Active Server**
> ...
> 4. Registering the Public Key
> Register the copied public key into the server's SSH public key management file.
>
> Source: [SSH Key - Xserver VPS Manual](https://vps.xserver.ne.jp/support/manual/man_server_ssh.php)

The correct understanding is not "I registered it but it doesn't work," but **"It won't work just by registering it."**

---

## Summary | The Truth About Xserver VPS Permission denied (publickey)

If you see `Permission denied (publickey)` on Xserver VPS, the cause is likely one of the following:

:::conclusion
*   You stopped at the control panel SSH key registration.
*   `authorized_keys` on the OS side is not set.
*   Permission errors.
*   Key pair mismatch.
:::

In SSH troubleshooting, **isolation** is more important than configuration.
First, verify "Is the key actually being used by the OS?"

---

**Image Source:**
[Xserver VPS Manual - SSH Key](https://vps.xserver.ne.jp/support/manual/man_server_ssh.php)
