---
title: "Keychron R3 Won't Turn On: Diagnosing a USB Device Descriptor Request Failed Error"
categories:
  - general
tags:
  - Keychron
  - R3
  - Windows
  - USB
  - Troubleshooting
status: publish
slug: keychron-r3-usb-descriptor-failure-en
---

I tried to set up a Keychron R3, but the keyboard would not power on at all.

At first, several explanations seemed possible: an empty battery, the wrong connection mode, a bad USB cable, or even a problem caused by the USB PD charger I had tried. The most useful clue came later, when Windows showed the device as **“Unknown USB Device (Device Descriptor Request Failed).”**

After basic checks failed, I treated the unit as a likely defective-on-arrival device and contacted the retailer. This is the troubleshooting sequence I used.

:::conclusion
If a Keychron R3 still does not power on with the original USB cable connected directly to a PC, and Windows reports a device descriptor request failure, the problem may be on the keyboard side rather than in Bluetooth settings or the cable alone. For a new unit, contacting the retailer before attempting disassembly or firmware recovery is the safer option.
:::

## Initial symptom: no lights at all

The R3 showed no indicator lights when a USB-C cable was connected.

I switched the connection mode to Cable and connected it directly to the PC, but there was still no visible response. Connecting it to a USB power supply did not change the behavior either.

I then worked through the following checks.

:::step
1. Set the connection mode to Cable.
2. Reseat the USB-C connector firmly.
3. Connect the keyboard directly to another USB port.
4. Try another USB power source.
5. Connect it directly to a PC and check how Windows detects the USB device.
:::

The same symptom occurred with a lower-power USB source and with the PC itself, which made a problem specific to one charger less likely.

## The original cable exposed the Windows error

A major clue appeared when I used the USB cable supplied with the Keychron.

Instead of appearing as a normal keyboard, Windows listed the device as:

> Unknown USB Device (Device Descriptor Request Failed)

That means Windows is at least detecting that a USB device has been attached, but it cannot obtain the information required to enumerate it normally.

:::note
A charge-only USB cable can supply power without carrying data, so checking with a known data-capable cable is an important early step. In this case, the error remained even with the original Keychron cable, making a simple cable issue less likely.
:::

## I also considered the USB PD charger

Because I had initially connected the keyboard to a USB PD-capable charger, I briefly wondered whether the charger had been involved.

However, the keyboard behaved exactly the same way when connected to a lower-power USB source and directly to the PC. In this troubleshooting sequence, the failure therefore did not look specific to one PD charger.

## The Windows error shifted suspicion toward the keyboard

At that point, the situation was:

- Cable mode selected
- Original USB cable in use
- Direct connection to the PC
- No improvement on another USB port
- No power-up on another USB source
- No indicator lights
- Windows reports a device descriptor request failure

With that combination of symptoms, changing normal Windows keyboard settings or Bluetooth pairing options is unlikely to address the core problem.

Possible fault areas include the USB-C connection, the PCB, the controller, or firmware initialization. The important practical point is that the keyboard is not completing normal USB communication with the host PC.

## Because it was new, I stopped there and contacted the retailer

It may be possible to investigate further with firmware recovery procedures or by opening the keyboard, but that is not necessarily worthwhile on a newly purchased unit that already appears defective.

Instead, I stopped troubleshooting and contacted the retailer as an initial-defect case.

The description I provided was essentially this:

:::example
The Keychron R3 does not power on when connected directly to a PC with the supplied USB cable. The issue remains on other USB ports and power sources, and Windows reports “Unknown USB Device (Device Descriptor Request Failed).”
:::

Providing those checks up front makes it clear that the usual first-line troubleshooting—changing ports, power sources, and cables—has already been performed.

## Summary

The most useful diagnostic step was not simply observing that the keyboard did not light up, but checking how Windows saw the device over USB.

The fact that Windows reached **“Device Descriptor Request Failed”** provided a much stronger clue than the lack of lights alone. It pointed away from a simple Bluetooth or mode-selection problem and toward a failure in USB communication on the device side.

For a new R3 showing the same behavior, I would verify the original cable, another USB port, and another power source. If the error persists, contacting the retailer for an initial-defect exchange or return is more sensible than immediately opening the keyboard or attempting invasive recovery steps.
