---
title: "Bluetoothに出てこないと思ったら、K295はUSBレシーバー式だった"
categories:
  - ガジェット
tags:
  - Logicool
  - K295
  - Bluetooth
  - Unifying
  - USBレシーバー
  - Windows
status: publish
slug: logicool-k295-bluetooth-usb-receiver-ja
---

LogicoolのK295を使おうとして、WindowsのBluetooth画面を何度見ても出てきませんでした。

「なぜBluetoothに出てこないんだろう」「ペアリング操作が別にあるのか」と少し悩みましたが、商品情報を見直して原因が判明しました。

:::conclusion
K295はBluetoothキーボードではなく、Unifying USBレシーバーを使う2.4GHzワイヤレスキーボードでした。Bluetooth一覧に出てこないのは正常です。
:::

## 接続先を探す場所が違った

Bluetooth機器だと思い込んでいたので、Windowsの「Bluetoothとデバイス」画面ばかり確認していました。しかし必要なのは、Bluetoothのペアリングではなく、小さなUnifying USBレシーバーをPCへ挿すことでした。

ロジクール公式の[K295製品ページ](https://www.logicool.co.jp/ja-jp/shop/p/k295-silent-wireless-keyboard)にも、接続方式はUnifying USBレシーバーを使う2.4GHzワイヤレス接続と書かれています。

PCのデバイス一覧も確認しましたが、その時点ではLogicoolのUSBレシーバーは検出されていませんでした。そりゃ接続できません。

## レシーバーはどこへ行ったのか

あとはUSBレシーバー探しです。

:::step
- キーボード裏側の電池カバー内
- 商品箱や説明書の袋
- 以前使ったPCのUSBポート
- USBハブや変換アダプター
:::

「Bluetoothに出てこない」と悩む前に、型番の接続方式を確認するべきでした。ワイヤレスだからBluetoothとは限らない、という非常に単純な落とし穴でした。

なお、ロジクールは[K250の発表資料](https://press.logicool.co.jp/ja-jp/k250-mk250/)で、USBレシーバー式のK295に対して、K250はBluetooth接続へ変わったと説明しています。見た目や価格帯が近くても、接続方式は違います。

:::note
K295を今から使うなら、まず付属のUnifying USBレシーバーを探します。レシーバーが見つからない場合は、対応レシーバーと再ペアリング方法を確認する必要があります。
:::
