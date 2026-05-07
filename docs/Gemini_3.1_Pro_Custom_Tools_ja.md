# Gemini 3.1 Pro Preview Custom Tools とは

## 基本情報
- **リリース日**: 2026年2月19日
- **開発元**: Google DeepMind
- **モデルID**: `gemini-3.1-pro-preview-customtools`

## 標準版との違い
Gemini 3.1 Pro Custom Toolsは、標準の`gemini-3.1-pro-preview`と同一の性能と価格を持ちますが、唯一の違いは「カスタムツールの優先使用」にあります。

### 標準版の問題点
標準版では、開発者が登録したカスタムツールをスキップして直接bashコマンドを実行することがありました。例えば、`view_file`ツールを登録していても、`cat filename.py`を実行してしまう可能性がありました。

### Custom Tools版の解決策
このバリアントは、登録されたカスタムツールを優先的に使用するように設計されています。これにより、開発者が意図したツールフローを確実に実行できます。

## 使いどころ
- **AIエージェント開発**: `view_file`、`search_code`、`edit_file`などのカスタムツールを使用する場合
- **DevOpsエージェント**: bashコマンドとカスタムツールの混合環境
- **MCP（Model Context Protocol）ワークフロー**: ツール優先が重要なシナリオ

## 性能と価格
- **価格**: 1M入力トークンあたり$2.00、1M出力トークンあたり$12.00（標準版と同一）
- **文脈ウィンドウ**: 1,048,576トークン
- **ARC-AGI-2スコア**: 77.1%（標準版と同一）

## 切り替え方法
標準版からCustom Tools版に切り替えるには、モデルIDを`gemini-3.1-pro-preview-customtools`に変更するだけです。これにより、カスタムツールの優先使用が有効になります。

## 選び方のガイド
| シナリオ | 推奨モデル | 理由 |
|----------|------------|------|
| 純粋な会話/Q&A | 標準版 | 最も安定した出力品質 |
| コード生成（ツールなし） | 標準版 | 直接コードを出力 |
| コーディングアシスタント（`view_file`など） | Custom Tools版 | ツール呼び出しを正確に実行 |
| DevOpsエージェント（bash + カスタムツール） | Custom Tools版 | ツールをバイパスしない |
| MCPワークフロー | Custom Tools版 | ツール優先を保証 |

## 公式のアドバイス
Googleの公式声明: "If you are using gemini-3.1-pro-preview and the model ignores your custom tools in favor of bash commands, try the gemini-3.1-pro-preview-customtools model instead."

## まとめ
Gemini 3.1 Pro Custom Toolsは、AIエージェント開発においてカスタムツールの信頼性を高めるための専用バリアントです。性能は標準版と同一ですが、ツール優先の動作により、より予測可能で安全な開発環境を提供します。