---
title: VS Code + GitHub Copilot Agentで「net::ERR_SOCKET_NOT_CONNECTED」が出る原因と対処法
slug: copilot-socket-error-jp
date: 2025-12-23
excerpt: VS CodeでGitHub Copilot Agentを使用中に発生する「net::ERR_SOCKET_NOT_CONNECTED」エラーの原因と解決策を解説します。
categories:
  - copiloterro
---

VS Code で GitHub Copilot（特に Copilot Agent / Chat）を使っていると、以下のようなエラーが突然表示されることがあります。

`net::ERR_SOCKET_NOT_CONNECTED`

![net::ERR_SOCKET_NOT_CONNECTED error in VS Code](../images/copilot-socket-error/copilot-socket-error.png)

一見するとコードや拡張機能の不具合に見えますが、このエラーはほぼ確実に通信レイヤーの問題です。この記事では、実際の利用経験を踏まえて、原因と現実的な対処法を整理します。

:::conclusion
**結論：これはコードエラーではありません**

`net::ERR_SOCKET_NOT_CONNECTED` は、GitHub Copilot が使っている通信ソケット（WebSocket / HTTP2）が既に切断されている状態で、VS Code 側がリクエストを投げたときに出るエラーです。

そのため、以下の要素とは無関係です。

*   書いているコード
*   言語設定
*   Copilotのプロンプト内容
:::

## どういう条件で発生するのか

実際に頻発する条件は、ほぼ次のどれかに当てはまります。

1.  **VS Code を長時間起動しっぱなし**
    Copilot Agent は内部で常時接続を張っています。スリープ復帰後や、数時間〜数十時間放置すると、接続だけが切れてセッションが不整合になります。

2.  **ネットワークの瞬断**
    Wi-Fi の再接続、VPN の ON / OFF、学内・社内ネットワークの制御などで WebSocket が強制切断されると発生します。

3.  **Copilot Agent 利用中の負荷集中**
    Copilot 側（GitHub / OpenAI）の一時的な高負荷時に、セッションが半端に死ぬケースがあります。

:::warning
**よくある誤解**

*   ❌ Copilot のバグコードを踏んだ
*   ❌ VS Code の設定がおかしい
*   ❌ 拡張機能の競合

これらは違います。ほぼ100％「通信が死んでいるだけ」です。
:::

## 即効性のある対処法（おすすめ順）

:::step
**対処① Copilot Language Server を再起動**

まずはこれで十分なケースが多いです。

VS Code のコマンドパレット（`Ctrl+Shift+P` / `Cmd+Shift+P`）で以下を実行します：

`GitHub Copilot: Restart Language Server`
:::

:::step
**対処② VS Code を再起動**

Copilot Agent を使っている場合、最も確実です。セッションが完全に張り直されます。
:::

:::step
**対処③ ネットワークを切り替える**

*   VPN を一度 OFF → ON
*   Wi-Fi を切断 → 再接続

これでソケットが再生成されることがあります。
:::

## 根本的な回避策

完全な解決策はありませんが、次を意識すると発生頻度は下がります。

*   Copilot Agent を常時起動しっぱなしにしない
*   スリープ復帰後は VS Code を再起動する
*   学内・社内ネットワークでは頻発する前提で使う

Copilot Agent は便利ですが、長時間常駐には向いていません。

## まとめ

`net::ERR_SOCKET_NOT_CONNECTED` は、以下の特徴を持つエラーです。

*   Copilot Agent 利用時に非常によく出る
*   コードとは無関係
*   通信セッションの切断が原因

これは「環境由来で、再現性はあるが原因追及しても得しない」類型のトラブルです。エラーが出たら深追いせず、「通信切れたな」と割り切って再起動するのが最短ルートです。

:::note
**関連記事**

*   [GitHub Copilotでエラーが出る原因と対処法まとめ【502 / 504 / Stream terminated / 404】](https://www.zidooka.com/archives/2672)
*   [GitHub Copilot Errors Explained: 502, 504, Stream Terminated & More](https://www.zidooka.com/archives/2675)
:::
