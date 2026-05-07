---
title: "技術的裏側：縦書きエディタの作り方"
date: "2026-03-07 09:00:00"
categories:
  - WEB制作
tags:
  - Next.js
  - IndexedDB
  - 縦書き
  - 技術解説
status: draft
slug: tategaki-editor-technical
featured_image: ../images/2026/03/tategaki-technical-thumbnail.png
---

# 技術的裏側：縦書きエディタの作り方

今回は「ログイン不要縦書きエディタ」の技術的な裏側をご紹介します。Next.jsとIndexedDBを組み合わせた、モダンなWebアプリケーションの実装例として参考にしていただければ幸いです。

## 🔗 ツールはこちら

**[ログイン不要縦書きエディタ](https://tools.zidooka.com/jp/tategaki-editor)**

---

## アーキテクチャ概要

### 使用技術スタック

- **Framework:** Next.js 14 (App Router)
- **Language:** TypeScript
- **Styling:** Tailwind CSS
- **Storage:** IndexedDB
- **Deployment:** Static Export

### なぜサーバーレスか

このエディタは**完全にクライアントサイド**で動作します。サーバーを介さないため：

- インフラコストがかからない
- ユーザーデータがサーバーに上がらない（プライバシー保護）
- オフラインでも使える（Service Worker等と組み合わせれば）

---

## 縦書きの実装

### CSS writing-mode

縦書きの核心は、CSSの `writing-mode` プロパティです：

```css
.tategaki-textarea {
  writing-mode: vertical-rl;
  text-orientation: upright;
  letter-spacing: 0.02em;
  line-height: 1.85;
}
```

- `vertical-rl`: 縦書きで、行を右から左へ進める
- `text-orientation: upright`: 英数字も直立させる
- `letter-spacing`: 字間を調整
- `line-height`: 行間を調整（縦書きでは実際には「列間」）

### 縦中横の実装

2桁の数字を横倒しにする「縦中横」は、CSSの `text-combine-upright` を使用：

```css
/* チェック時 */
.text-combine {
  text-combine-upright: digits 2;
}

/* 非チェック時 */
.text-no-combine {
  text-combine-upright: none;
}
```

これにより、「2026」が一つの文字として縦に収まります。

---

## IndexedDBによるデータ永続化

### なぜlocalStorageではなくIndexedDBか

| 特性 | localStorage | IndexedDB |
|------|-------------|-----------|
| 容量制限 | ~5-10MB | ディスクの50%程度まで |
| データ構造 | キー・バリューのみ | オブジェクトストア、インデックス |
| 非同期 | 同期（ブロッキング） | 非同期 |
| 検索 | キーでのみ取得 | インデックスで高速検索 |

長文を保存するエディタには、**IndexedDBが適しています**。

### データスキーマ

```typescript
interface TategakiDoc {
  id: string;           // ドキュメントID
  title: string;        // タイトル
  content: string;      // 本文
  createdAt: number;    // 作成日時
  updatedAt: number;    // 更新日時
}

interface Snapshot {
  id: string;           // スナップショットID
  docId: string;        // 紐づくドキュメントID
  title: string;        // タイトル（当時）
  content: string;      // 本文（当時）
  savedAt: number;      // 保存日時
}
```

### 自動保存の実装

Reactの `useEffect` と `setTimeout` を組み合わせ：

```typescript
useEffect(() => {
  if (!loaded || !docId) return;
  
  const timer = setTimeout(() => {
    saveCurrentDoc('auto');
  }, AUTO_SAVE_DELAY_MS); // 850ms
  
  return () => clearTimeout(timer);
}, [title, content, loaded, docId]);
```

タイピングを止めて850ms経つと自動保存が発動します。

---

## スナップショット機能の実装

### 保存タイミング

```typescript
const shouldCreateSnapshot = 
  mode === 'manual' || // 手動保存時
  (now - lastSnapshotAtRef.current >= AUTO_SNAPSHOT_INTERVAL_MS && // 30秒経過
   content.trim().length > 0 && // 内容がある
   content !== lastSnapshotContentRef.current); // 内容が変化している
```

### スナップショットの上限管理

最大40個まで保存し、古いものから自動削除：

```typescript
const MAX_SNAPSHOTS = 40;

// 保存時に古いものを削除
const snapshots = await listSnapshots(db, docId);
if (snapshots.length >= MAX_SNAPSHOTS) {
  const toDelete = snapshots.slice(MAX_SNAPSHOTS - 1);
  for (const snap of toDelete) {
    await removeSnapshot(db, snap.id);
  }
}
```

---

## テーマ切り替えの実装

### ローカルストレージとの連携

```typescript
const persistUiSettings = (nextTheme: boolean, nextFontSize: number, nextFontFamilyId: string) => {
  localStorage.setItem(THEME_KEY, nextTheme ? 'dark' : 'light');
  localStorage.setItem(FONT_SIZE_KEY, String(nextFontSize));
  localStorage.setItem(FONT_FAMILY_KEY, nextFontFamilyId);
};
```

UI設定はlocalStorageに保存し、ページ読み込み時に復元します。

### Tailwindでのダークモード

Tailwindの `darkMode: 'class'` を使用せず、**インラインスタイル**で制御：

```typescript
const shellStyle = {
  background: darkMode ? '#0a1222' : '#f8fafc',
  color: darkMode ? '#e6edf7' : '#0f172a',
};
```

これにより、より細かい色のコントロールが可能になります。

---

## パフォーマンス最適化

### useMemoによる最適化

```typescript
const widthStats = useMemo(() => {
  let half = 0;
  let full = 0;
  for (const ch of content) {
    if (isHalfWidth(ch)) half += 1;
    else full += 1;
  }
  return { half, full };
}, [content]);
```

文字数カウントなどの計算は `useMemo` でメモ化し、再レンダリングを最小限に。

### データベース接続の管理

```typescript
useEffect(() => {
  return () => {
    if (dbRef.current) {
      dbRef.current.close();
      dbRef.current = null;
    }
  };
}, []);
```

コンポーネントアンマウント時にDB接続を明示的に閉じ、メモリリークを防止。

---

## 今後の技術的展望

### 検討中の機能

1. **Service Workerによるオフライン対応**  
   PWA化し、完全なオフライン動作を実現

2. **Web Share API**  
   ネイティブアプリのようにテキストを共有

3. **File System Access API**  
   ローカルファイルの直接読み書き

4. **WebAssembly (WASM)**  
   高度なテキスト処理（形態素解析等）

---

## まとめ

「ログイン不要縦書きエディタ」は、**モダンなWeb技術の組み合わせ**で実現されています：

- CSS `writing-mode` で本格的な縦書き
- IndexedDB で大容量・高速なローカル保存
- React Hooks で直感的な状態管理

技術的にも興味深い実装例として、ぜひソースコードも参考にしてください。

**[ログイン不要縦書きエディタ](https://tools.zidooka.com/jp/tategaki-editor)**

---

*2026年3月7日 ZIDOOKA Tools Team*
