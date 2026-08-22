---
title: "My K295 Never Appeared in Bluetooth—Because It Uses a USB Receiver"
categories:
  - ガジェット
tags:
  - Logitech
  - K295
  - Bluetooth
  - Unifying
  - USB receiver
  - Windows
status: publish
slug: logitech-k295-bluetooth-usb-receiver-en
---

I was trying to use a Logitech K295 keyboard, but it never appeared in the Windows Bluetooth device list.

I wondered whether there was a special pairing procedure. Then I checked the product information again and found the very simple explanation.

:::conclusion
The K295 is not a Bluetooth keyboard. It uses a 2.4 GHz wireless connection through a Unifying USB receiver, so it is not supposed to appear in the Bluetooth device list.
:::

## I was looking in the wrong place

Because the keyboard was wireless, I had assumed it used Bluetooth. I kept checking the Windows “Bluetooth & devices” screen, but the actual setup requires plugging the tiny Unifying USB receiver into the computer.

The official [Logicool K295 product page](https://www.logicool.co.jp/ja-jp/shop/p/k295-silent-wireless-keyboard) describes it as a 2.4 GHz wireless keyboard that uses a Unifying USB receiver.

I also checked the devices currently detected by Windows. No Logitech USB receiver was connected at the time. Naturally, the keyboard could not connect.

## Now I just need to find the receiver

The next step is a physical search.

:::step
- Check inside the keyboard’s battery compartment.
- Check the product box and documentation pouch.
- Inspect the USB ports on computers previously used with it.
- Check USB hubs and adapters.
:::

The lesson was straightforward: wireless does not always mean Bluetooth. Checking the connection method for the exact model would have saved the whole detour.

Logicool has explicitly contrasted the USB-receiver-based K295 with the Bluetooth-based K250 in its [K250 announcement](https://press.logicool.co.jp/ja-jp/k250-mk250/), so even similarly priced keyboards from the same brand may connect differently.

:::note
To use the K295, first locate its Unifying USB receiver. If it is missing, check receiver compatibility and the required re-pairing procedure before buying a replacement.
:::
