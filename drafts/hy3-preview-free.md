---
title: "Tencent製AIモデル「Hy3 Preview Free」をOpenCode Zenで試してみた"
categories: 
  - AI
tags: 
  - Hy3
  - Tencent
  - AIモデル
  - OpenCode
status: publish
slug: hy3-preview-free
---

OpenCode Zenで提供されている「Hy3 Preview Free」を実際に使ってみました。このモデルはTencent（騰訊）が開発した混合専門家（Mixture-of-Experts）アーキテクチャを採用しています。

## Hy3 Preview Freeとは

:::note
Hy3 Preview Freeは、Tencentが開発した高性能AIモデルです。295Bのパラメータを持ちながら、アクティブなパラメータは21Bのみという効率的な設計です。
:::

## 主な特徴

- **パラメータ**: 295B（アクティブ21B、192専門家、top-8ルーティング）
- **コンテキストウィンドウ**: 256,000トークン
- **出力**: 最大64,000トークン
- **機能**: ツール呼び出し、推論機能付き
- **最適化**: エージェントワークフローや本番環境向け

## 使用方法

OpenCode Zenを通じて無料で利用できます。現在はプレビュー期間中のため、フィードバック収集を目的に無料提供されています。

```powershell
# OpenCode Zenでのモデル指定例
model: opencode/hy3-preview-free
```

## まとめ

:::conclusion
Tencent製の高性能モデルが無料で試せるのは非常に貴重な機会です。特にエージェントワークフローや本番環境向けに最適化されている点が魅力です。
:::
