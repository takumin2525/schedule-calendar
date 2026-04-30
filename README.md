# 予定共有カレンダー

## 📌 アプリ概要
グループ内で予定を共有できるカレンダーアプリです。  
日付ごとに予定を登録・確認でき、複数人でスケジュール管理が可能です。

---

## 🧑‍🤝‍🧑 想定ユーザー
- 友人同士の予定共有
- カップルのスケジュール管理
- 小規模チームのタスク管理

---

## 🚀 主な機能

### ■ ユーザー認証
- ログイン機能（Laravel Breeze）

### ■ グループ機能
- グループ作成
- メンバー追加（メールアドレスで招待）
- メンバー削除

### ■ カレンダー機能
- FullCalendarによる月表示
- 日付クリックで予定一覧表示（サイドバー）
- 予定の作成 / 編集 / 削除

### ■ 予定管理
- タイトル（任意）
- 日付（必須）
- 時間指定 or 終日OK
- 自分の予定のみ編集・削除可能

---

## 🛠 使用技術

### ■ バックエンド
- PHP 8.x
- Laravel 12

### ■ フロントエンド
- HTML / CSS / JavaScript
- FullCalendar
- fetch API（非同期通信）

### ■ データベース
- MySQL（phpMyAdmin）

### ■ 開発環境
- MAMP（Mac）

---

## 💡 工夫した点

- **非同期通信（fetch）を使用**
  - ページリロードなしで予定の取得・表示を実現

- **グループ単位のアクセス制御**
  - `auth()->user()->groups()->findOrFail()` により
  - 所属していないグループへのアクセスを防止

- **自分の予定のみ編集可能**
  - user_idで制御

- **UX改善**
  - 日付クリックでサイドバー表示
  - スマホでも使いやすいUI設計

---

## 😅 苦労した点

- FullCalendarとLaravelのデータ連携
- 非同期通信（fetch）の理解
- モーダル・サイドバーの状態管理
- many-to-many（中間テーブル）の理解

---

## 🔧 今後の改善

- メンバー追加をAjax化（モーダルを閉じない）
- イベントのドラッグ移動対応
- 通知機能
- UIデザインの強化

---

## 📸 画面イメージ

### カレンダー
![calendar](./images/calendar.png)

### 予定一覧（サイドバー）
![sidebar](./images/sidebar.png)

### メンバー管理
![member](./images/member-modal.png)

### スマホ表示
![phone](./images/phone.png)

---

## ⚙️ セットアップ方法

```bash
git clone https://github.com/あなたのユーザー名/リポジトリ名.git
cd リポジトリ名

composer install
npm install
npm run dev

cp .env.example .env
php artisan key:generate

# DB設定後
php artisan migrate

php artisan serve