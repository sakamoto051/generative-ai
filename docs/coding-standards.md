# コーディング規約

## 目次

1. [概要](#概要)
2. [アーキテクチャ](#アーキテクチャ)
3. [ディレクトリ構成](#ディレクトリ構成)
4. [命名規則](#命名規則)
5. [各層の責務](#各層の責務)
6. [PHPコーディング規約](#phpコーディング規約)
7. [データベース規約](#データベース規約)
8. [JavaScriptコーディング規約](#javascriptコーディング規約)
9. [Bladeテンプレート規約](#bladeテンプレート規約)

---

## 概要

本プロジェクトでは、Laravelの標準的なMVCフレームワークをベースに、ビジネスロジックと外部連携を分離するためのサービス層を追加した構成を採用します。

### 基本方針

- **関心の分離**: 各層は明確に責務を分離し、相互依存を最小化する
- **可読性**: コードは読みやすく、意図が明確であること
- **保守性**: 変更に強く、拡張しやすいコードを書く
- **テスタビリティ**: テストしやすい設計を心がける
- **PSR-12準拠**: PHP Standard Recommendations 12に準拠する

---

## アーキテクチャ

### レイヤー構成

```
┌─────────────────────────────────────────┐
│          User (Browser/API Client)      │
└─────────────────┬───────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│         1. Request Layer                │
│    app/Http/Requests/                   │
│    継承: FormRequest.php                │
└─────────────────┬───────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│         2. Controller Layer             │
│    app/Http/Controllers/                │
│    継承: Controller.php                 │
└─────────┬───────────────┬───────────────┘
          ↓               ↓
┌───────────────────┐  ┌──────────────────┐
│ 3. Service Layer  │  │  4. Model Layer  │
│  app/Services/    │  │  app/Models/     │
│  継承: なし        │  │  継承: Model.php │
└─────────┬─────────┘  └────────┬─────────┘
          │                     ↑
          └─────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│         5. Response Layer               │
└─────────────────┬───────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│         View Layer                      │
│    resources/views/                     │
│    継承: base-layout.blade.php          │
└─────────────────────────────────────────┘
```

---

## ディレクトリ構成

```
app/
├── Http/
│   ├── Controllers/          # コントローラー
│   │   ├── Controller.php    # ベースコントローラー
│   │   ├── Api/              # API用コントローラー
│   │   └── Web/              # Web用コントローラー
│   ├── Requests/             # フォームリクエスト
│   │   └── FormRequest.php   # ベースリクエスト
│   └── Middleware/           # ミドルウェア
├── Models/                   # モデル
│   └── Model.php             # ベースモデル
├── Services/                 # サービス層
│   ├── External/             # 外部連携サービス
│   │   ├── ErpService.php
│   │   ├── SalesService.php
│   │   └── AccountingService.php
│   ├── File/                 # ファイル処理サービス
│   │   ├── ExcelImportService.php
│   │   ├── ExcelExportService.php
│   │   └── PdfExportService.php
│   └── Notification/         # 通知サービス
│       ├── MailService.php
│       └── SmsService.php
├── Repositories/             # リポジトリ（オプション）
├── Exceptions/               # カスタム例外
└── Helpers/                  # ヘルパー関数

resources/
└── views/
    ├── layouts/
    │   ├── base-layout.blade.php  # ベースレイアウト
    │   ├── app.blade.php
    │   └── guest.blade.php
    ├── components/           # 再利用可能コンポーネント
    └── [module]/             # モジュール別ビュー
        ├── index.blade.php
        ├── create.blade.php
        ├── edit.blade.php
        └── show.blade.php

database/
├── migrations/               # マイグレーション
├── seeders/                  # シーダー
└── factories/                # ファクトリ

tests/
├── Feature/                  # 機能テスト
│   ├── Api/
│   └── Web/
└── Unit/                     # 単体テスト
    ├── Models/
    └── Services/
```

---

## 命名規則

### ファイル名

| 種類 | 命名規則 | 例 |
|------|---------|-----|
| コントローラー | `{Resource}Controller.php` | `ProductionPlanController.php` |
| リクエスト | `{Action}{Resource}Request.php` | `StoreProductionPlanRequest.php` |
| モデル | `{Entity}.php`（単数形） | `ProductionPlan.php` |
| サービス | `{Purpose}Service.php` | `ExcelImportService.php` |
| ミドルウェア | `{Purpose}Middleware.php` | `CheckProductionPlanOwnership.php` |
| ビュー | `{action}.blade.php` | `index.blade.php`, `create.blade.php` |
| マイグレーション | `{timestamp}_create_{table}_table.php` | `2024_01_01_000000_create_production_plans_table.php` |

### クラス名

- **PascalCase**を使用

### メソッド名

- **camelCase**を使用
- 動詞で始める

### 変数名

- **camelCase**を使用
- bool型の変数は`is`、`has`、`can`などで始める

### 定数名

- **UPPER_SNAKE_CASE**を使用

### データベーステーブル名

- **snake_case**（小文字）
- **複数形**を使用
- 中間テーブルは両テーブル名をアルファベット順で結合

### データベースカラム名

- **snake_case**（小文字）
- 外部キーは`{table}_id`

---

## 各層の責務

### 1. Request層

#### 責務
- ユーザー入力のバリデーション
- データの整形・正規化
- 認可チェック（authorize メソッド）

#### 配置場所
- `app/Http/Requests/`

#### 継承
- `app/Http/Requests/FormRequest.php`を継承

---

### 2. Controller層

#### 責務
- リクエストの受け取り
- 適切なサービス・モデルの呼び出し
- レスポンスの返却
- トランザクション制御

#### 配置場所
- `app/Http/Controllers/`
- Web画面用: `app/Http/Controllers/Web/`
- API用: `app/Http/Controllers/Api/`

#### 継承
- `app/Http/Controllers/Controller.php`を継承

---

### 3. Service層

#### 責務
- 外部システム連携（ERP、販売管理、会計など）
- ファイル処理（Excel、PDF、CSV）
- メール・SMS送信
- 外部API呼び出し
- **ビジネスロジックは記載しない**

#### 配置場所
- `app/Services/`
- 外部連携: `app/Services/External/`
- ファイル処理: `app/Services/File/`
- 通知: `app/Services/Notification/`

#### 継承
- なし

---

### 4. Model層

#### 責務
- データベース操作
- ビジネスロジック
- データの整合性保証
- リレーション定義
- スコープ定義
- アクセサ・ミューテータ

#### 配置場所
- `app/Models/`

#### 継承
- `Illuminate\Database\Eloquent\Model`または`app/Models/Model.php`を継承

---

### 5. View層

#### 責務
- データの表示
- フォームの表示
- ユーザーインタラクション

#### 配置場所
- `resources/views/`

#### 継承
- `resources/views/layouts/base-layout.blade.php`を継承

---

## PHPコーディング規約

### PSR-12準拠

- [PSR-12: Extended Coding Style](https://www.php-fig.org/psr/psr-12/)に準拠

### インデント・スペース

- インデント: **4スペース**（タブ禁止）
- 演算子の前後: スペース1つ
- カンマの後: スペース1つ

### 波括弧

- クラス、メソッドの開始波括弧は次の行
- 制御構造（if, for, while等）の開始波括弧は同じ行

### 型宣言

- 引数と戻り値には必ず型を宣言

### 配列

- 短い配列構文`[]`を使用（`array()`は使用しない）

### 文字列

- シングルクォート`'`を基本とし、変数展開が必要な場合のみダブルクォート`"`

---

## データベース規約

### 命名規則

- テーブル名: `snake_case`複数形
- カラム名: `snake_case`
- 外部キー: `{table}_id`
- 中間テーブル: アルファベット順で結合

### マイグレーション

- テーブル作成時は必ずコメントを記載
- 外部キー制約を適切に設定
- インデックスは検索頻度の高いカラムに設定

---

## JavaScriptコーディング規約

### 基本方針

- ES6+を使用
- Airbnb JavaScript Style Guideに準拠

### 変数宣言

- `const`を基本とし、再代入が必要な場合のみ`let`を使用
- `var`は使用しない

### 関数

- アロー関数を推奨

---

## Bladeテンプレート規約

### XSS対策

- `{{ }}`を使用（自動エスケープ）
- `{!! !!}`は信頼できるHTMLのみ

### ディレクティブ

- 条件分岐: `@if`, `@elseif`, `@else`, `@endif`
- ループ: `@foreach`, `@forelse`, `@empty`
- 認可: `@can`, `@cannot`

---

**作成日**: 2025年12月14日
**バージョン**: 1.0
