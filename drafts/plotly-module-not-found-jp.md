---
title: "Pythonで「No module named 'plotly'」が出る原因と対処法 ― pip install plotly 済みでも直らない理由"
date: 2025-12-22 10:00:00
categories: 
  - Python Errors
tags: 
  - Python
  - plotly
  - ModuleNotFoundError
  - Troubleshooting
status: publish
slug: plotly-module-not-found-jp
featured_image: ../images/2025/image copy 36.png
---

Pythonでデータ可視化ライブラリの **plotly** を使おうとしたとき、以下のエラーが出て処理が止まってしまうことがあります。

```
ModuleNotFoundError: No module named 'plotly'
```

「えっ、さっき `pip install plotly` したのに！？」
「`Requirement already satisfied` って言われるのに動かない！」

そんな状況に陥っているあなたへ。
このエラーは、単にインストールを忘れている場合もありますが、**「インストールした場所」と「実行している場所」がズレている** ケースが非常に多いです。

この記事では、ZIDOOKA！流にこのエラーの **本当の原因** と **環境別の確実な直し方** を解説します。

## 結論：なぜエラーが出るのか

結論から言います。このエラーが出る原因の9割はこれです。

**「pipでインストールしたPython」と「コードを実行しているPython」が別モノだから。**

PCの中にPythonが複数入っていて、
- AのPythonに `pip install` した
- BのPythonで `import plotly` した

という「すれ違い」が起きているのです。
特に **VS Code**、**Anaconda**、**Jupyter Notebook** を使っている人はこの罠にハマりやすいです。

---

## 1. 基本：まずはインストール確認

まだ一度もインストールしていない場合は、シンプルにインストールすれば直ります。

### ターミナル（コマンドプロンプト）で実行
```bash
pip install plotly
```

MacやLinuxでPython3系を明示する場合：
```bash
pip3 install plotly
```

インストール後に以下を実行してエラーが出なければOKです。
```python
import plotly
print("plotly is installed")
```

これで直らない場合、ここからが本番です。

---

## 2. 「pip install 済み」なのにエラーが出る場合

「`Requirement already satisfied`（もう入ってるよ）」と言われるのに、実行すると `ModuleNotFoundError` になる。
これは **環境の不一致** です。

### 確認方法：Pythonとpipの向き先を調べる

ターミナルで以下のコマンドを打ち比べてみてください。

**Mac / Linux:**
```bash
which python
which pip
```

**Windows:**
```powershell
where python
where pip
```

もし、この2つのパス（場所）が全然違うフォルダを指していたら、それが原因です。
「pipはAnacondaのPythonに入れているのに、実行は標準のPythonを使っている」といった状態です。

### 対処法
実行したいPythonのフルパスを指定してインストールするのが確実です。

```bash
# 例：pythonコマンドが指しているPythonにインストールする
python -m pip install plotly
```
`python -m pip` と打つことで、「今 `python` コマンドで動くそのPython」に対してpipを実行できます。

---

## 3. VS Code でエラーが出る場合

VS Codeでは、画面右下（またはコマンドパレット）で **「使用するPythonインタープリタ」** を選べます。

1. `Ctrl + Shift + P` (Macは `Cmd + Shift + P`) を押す。
2. `Python: Select Interpreter` を入力して選択。
3. 一覧から、**plotlyをインストールしたはずのPython環境** を選ぶ。

よくあるのが、「グローバル環境には入れたけど、VS Codeが勝手に作った仮想環境（venv）を見ている」というパターンです。

---

## 4. Jupyter Notebook でエラーが出る場合

Jupyter Notebook (または JupyterLab) は、ターミナルとは独立したカーネルで動いていることが多いです。
「ターミナルで `pip install` したのに Notebook で動かない」のはこれが原因です。

### 対処法：Notebookセル内でインストールする

一番確実なのは、**Notebookのセルの中に** 以下を書いて実行することです。

```python
import sys
!{sys.executable} -m pip install plotly
```

単に `!pip install plotly` と書くと、Notebookが使っているカーネルとは別のpipが動いてしまうことがあります。
`{sys.executable}` を使うことで、**「今このNotebookを動かしているPython」** に対してインストールを強制できます。

---

## 5. 仮想環境 (venv) の有効化忘れ

プロジェクトごとに `venv` を作っている場合、有効化（Activate）を忘れるとグローバル環境を見に行ってしまいます。

**Windows:**
```powershell
.\venv\Scripts\activate
pip install plotly
```

**Mac / Linux:**
```bash
source venv/bin/activate
pip install plotly
```

ターミナルの先頭に `(venv)` と表示されている状態でインストールしてください。

---

## まとめ

`ModuleNotFoundError: No module named 'plotly'` が出たら、焦らず以下を確認してください。

1. **まずは `pip install plotly`**
2. **直らなければ `python -m pip install plotly`**
3. **VS Codeならインタープリタを確認**
4. **Jupyterなら `!{sys.executable} -m pip ...`**

「入れたはずなのに無い」ときは、**「どこに入れたか」** を疑うのが解決への近道です。
