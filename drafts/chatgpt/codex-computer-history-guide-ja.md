---
title: "CodexのComputer Historyとは？できること・使い方・プライバシーを解説"
categories:
  - AI
tags:
  - OpenAI
  - Codex
  - ChatGPT
  - Computer History
  - macOS
  - AIエージェント
status: publish
slug: codex-computer-history-guide
---

OpenAIは2026年8月13日、ChatGPTデスクトップアプリの新機能「Computer History」を公開しました。Mac上で最近行った作業を時系列の要約とメモリに変換し、ChatGPTやCodexから「あの作業の続き」を尋ねられる機能です。

:::conclusion
Computer Historyは、許可したアプリやWebサイトでの操作を要約し、ChatGPTとCodexが参照できる「作業履歴」にする機能です。現時点ではmacOS版のChatGPTデスクトップアプリで、ChatGPT Pro・Business・Enterpriseユーザーが利用できます。
:::

## Computer Historyで何ができるのか

Computer Historyを有効にすると、許可したアプリやWebサイトでの最近の作業が、日付・時刻ごとのタイムラインとして整理されます。ChatGPTやCodexには、たとえば次のように質問できます。

- 「休憩前に何をしていた？」
- 「さっき見ていた企画書はどこにある？」
- 「今日取り組んだタスクと進捗を一覧にして」
- 「昨日の作業を朝会用に要約して」

履歴は、ファイル、Slackの会話、Googleドキュメントなど、実際の情報源を探す手掛かりとしても使われます。Computer Historyが情報源そのものをすべて保存するというより、「いつ、どのアプリで、何をしていたか」を手掛かりにして、必要ならChatGPTやCodexが元のファイルや会話を読みます。

また、反復している作業が見つかると、その手順を再利用可能なSkillやAutomationにする提案がタイムラインに表示されることがあります。

## Computer Useや通常のメモリとの違い

名前が似ていますが、Computer HistoryとComputer Useは別機能です。

| 機能 | 役割 |
| --- | --- |
| Computer History | 過去のPC操作を要約し、あとから参照できる履歴とメモリを作る |
| Computer Use | ChatGPTやCodexが画面を見て、クリックや入力を行い、アプリを操作する |
| Memories | 好み、作業手順、技術構成などをチャットをまたいで再利用する |

Computer Historyの利用にはMemoriesが必要です。ただし、Computer History自体はComputer Useのように勝手にアプリを操作する機能ではありません。

## Codex CLI（ターミナル版）しか使っていない人は？

Codex CLIだけを使っている場合、Computer Historyそのものをオンにして、ほかのアプリやWebサイトの操作を記録することはできません。Computer Historyの記録開始・対象アプリの設定・タイムラインの閲覧は、macOS版ChatGPTデスクトップアプリの機能だからです。

ただし、Codex CLIにも「ローカルMemories」という別の仕組みがあります。こちらは過去のCodex CLIセッションから、技術構成、作業手順、リポジトリの慣習などをローカルメモリとして残し、次回以降のセッションで再利用する機能です。対話モードでは`/memories`で現在のチャットがメモリを利用・生成するかを選べます。

機能が有効になっていない場合は、`~/.codex/config.toml`に次の設定を追加します。

```toml
[features]
memories = true
```

| 利用方法 | Computer History | Codex CLIのローカルMemories |
| --- | --- | --- |
| CLIだけを使う | 利用不可 | 利用可能 |
| macOSデスクトップ版だけを使う | 対象プランなら利用可能 | 利用可能 |
| 同じMacでデスクトップ版とCLIを使う | デスクトップ版が操作履歴を生成 | CLIも同じローカルメモリ基盤を利用できる |
| Windows／LinuxでCLIを使う | 現時点では利用不可 | 利用可能 |

同じMac上でChatGPTデスクトップアプリとCodex CLIが同じ`CODEX_HOME`（通常は`~/.codex`）を使い、Memoriesが有効なら、公式説明を合わせて読む限り、デスクトップ版のComputer Historyが生成したローカルメモリをCLI側でも文脈として利用できます。ただし、履歴の収集開始やタイムライン管理にはデスクトップアプリが必要です。

:::note
ターミナルだけで完結したい人が使えるのは、まずCodex CLI自身の会話から作るローカルMemoriesです。Computer Historyのように、ブラウザや別アプリをまたいだ作業履歴までは集めません。
:::

## 対応環境と利用条件

2026年8月15日時点の公式情報では、利用条件は次のとおりです。

- ChatGPTデスクトップアプリのmacOS版
- ChatGPT Pro、Business、Enterprise
- Proでは本人が設定をオンにする
- BusinessとEnterpriseでは、管理者が先に利用を許可し、その後各ユーザーが個別にオンにする
- Memoriesを有効にする必要がある
- APIキーやAmazon Bedrockでは利用できない
- EEA、スイス、英国ではまだ利用できない

:::warning
現時点ではWindows版やLinux版では利用できず、ChatGPT Plusも対象として公式ページに記載されていません。設定項目が表示されない場合、まずOSとプランを確認してください。
:::

## Computer Historyを有効にする方法

:::step
1. macOSでChatGPTデスクトップアプリを開きます。
2. `Settings`を開き、`Integrations`の中から`Computer history`を選びます。
3. `Turn on`を選び、プライバシー、権限、ローカル保存についての説明を確認します。
4. 求められた場合はMemoriesも有効にします。
5. 履歴に含めるアプリとWebサイトを選び、macOSの権限要求に対応します。
:::

BusinessまたはEnterpriseを利用している場合は、事前に管理者側の許可が必要です。管理者が許可しただけでは記録は始まらず、各ユーザーが自分でオンにする必要があります。

Computer Historyには画面収録の権限は必要ありません。設定が出てこないときは、プラン、OS、ワークスペース管理者の設定を確認します。

## 何が記録されるのか

Computer Historyは、許可されたアプリとWebサイトから操作イベントを作ります。イベントには、クリック、文字入力、キーボードショートカット、アプリの切り替え、macOSのアクセシビリティ機能を通じて得られる文脈などが含まれます。

一方で、次のものは取得しません。

- スクリーンショット
- 画面録画
- マイク入力
- システム音声
- ブラウザのプライベートモードでの操作

以前提供されていたChronicleはスクリーンショットを使う研究プレビューでした。Computer Historyはその後継ですが、単なる名称変更ではなく、スクリーンショットではなく操作イベントを使う仕組みに作り直されています。

## 記録するアプリやサイトを制限する

`Settings > Computer history > Permissions`では、次の2方式で対象を選べます。

- `Exclude these apps / websites`: 指定したアプリやURLだけ除外する
- `Include only these apps / websites`: 指定したアプリやURLだけ含める

タイムライン項目に表示されるアプリアイコンから、そのアプリを今後の履歴から除外することもできます。権限変更は将来の履歴にだけ反映されるため、すでに作られた履歴は別途削除が必要です。

メニューバーのChatGPTアイコンからは、現在何を記録しているかを確認し、`Pause`と`Resume`で一時停止・再開できます。完全に止めたい場合はComputer Historyをオフにします。

:::warning
OpenAIは、他人とのコミュニケーションを記録対象にする場合、相手から事前の明示的同意を得るよう案内しています。健康、金融、私生活に関する情報を扱うアプリは、最初から対象外にするのが安全です。
:::

## データはどこに保存されるのか

操作イベントはMac内に一時保存され、最大48時間保持されます。ChatGPTとCodexはこのイベントを定期的に要約し、ローカルのMarkdown形式のメモリファイルを作ります。

標準的な保存先は次のとおりです。

```text
~/.codex/memories/extensions/skysight/
```

生成されたメモリは自分で読んだり編集したりできますが、Computer Historyによって暗号化されるわけではありません。同じmacOSユーザーとして動く別のプログラムから読める可能性があります。

操作イベントは要約生成のためOpenAIのサーバーでも処理されます。公式説明では、法律上必要な場合を除き、処理後に一時イベントファイルを保持せず、学習にも使わないとされています。ただし、後のチャットでメモリを使った場合、その内容はチャットの文脈に含まれ、ChatGPTのデータコントロール設定によってはモデル改善に使われる可能性があります。

## 履歴を確認・削除する方法

`Settings > Computer history > History`を開くと、日付と時刻で整理された履歴を確認できます。各項目では、要約、利用されたアプリ、SkillやAutomationの提案を確認し、ローカルのメモリファイルをFinderで表示したり、個別に削除したりできます。

履歴は「直近10分」「直近1時間」「直近1日」「すべて」の単位でも消去できます。消去すると操作イベントと、それをもとに作られたメモリが削除され、元に戻せません。

## 利用前に知っておきたいリスク

Computer Historyは、アプリやWebサイトに表示された内容を文脈として取り込みます。そのため、悪意あるWebページなどに書かれた命令をChatGPTやCodexが指示と誤認する、プロンプトインジェクションの危険が通常より大きくなります。

また、履歴の要約とメモリ作成にはトークンを使用します。公式ページには、この機能に固有の利用量や上限は明記されていません。

:::note
最初は`Include only`方式で対象を絞り、研究・開発に必要なアプリだけを許可する運用が分かりやすそうです。通信、医療、金融、パスワード管理のアプリは除外し、不要なときはメニューバーから一時停止できます。
:::

## 動かないときの確認項目

Computer Historyの設定は表示されるのに開始できない場合は、次の順に確認します。

1. Memoriesがオンになっているか確認する
2. `Settings > Computer history`で`Finish setup`、`Resume`、または`Try again`を選ぶ
3. 改善しなければChatGPTデスクトップアプリを終了して開き直す

設定自体が表示されない場合は、macOS版か、対象プランか、Business／Enterpriseの管理者許可があるかを確認します。

## まとめ

Computer Historyは、単なるブラウザ履歴ではなく、PC上で行った一連の作業を「あとから自然言語で探せる作業記録」にする機能です。中断した仕事への復帰や日報作成、繰り返し作業のSkill化にはかなり便利そうです。

一方で、入力内容やアプリ間の移動も操作イベントに含まれ得ます。対象アプリを絞り、一時停止と履歴削除を使い分けることが前提の機能だと考えたほうがよいでしょう。

## 参考資料

- [Computer History（OpenAI公式）](https://learn.chatgpt.com/docs/customization/computer-history)
- [Memories（OpenAI公式）](https://learn.chatgpt.com/docs/customization/memories)
- [ChatGPT & Codex changelog（OpenAI公式）](https://learn.chatgpt.com/docs/changelog)
