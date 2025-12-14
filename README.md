# Laravel Docker環境

Docker Composeを使用したLaravel、MySQL、Nginxの開発環境です。

## 構成

- **PHP**: 8.2-fpm
- **Laravel**: 12.x
- **MySQL**: 8.0
- **Nginx**: Alpine
- **Node.js**: package.jsonで管理

## インストール済みパッケージ

### 本番環境
- `linecorp/line-bot-sdk` - LINE Bot SDK
- `phpoffice/phpspreadsheet` - Excel/スプレッドシート操作

### 開発環境
- `fakerphp/faker` - ダミーデータ生成
- `larastan/larastan` - Laravel向けPHPStan静的解析
- `laravel/pail` - ログビューア
- `laravel/pint` - コードフォーマッター
- `laravel/sail` - Docker開発環境
- `mockery/mockery` - モッキングフレームワーク
- `nunomaduro/collision` - エラーハンドリング
- `phpunit/phpunit` - テストフレームワーク

## セットアップ

### 1. Dockerコンテナのビルドと起動

```bash
docker compose up -d
```

### 2. データベースマイグレーション

初回起動時にマイグレーションが必要な場合:

```bash
docker compose exec app php artisan migrate
```

### 3. アプリケーションへのアクセス

ブラウザで以下のURLにアクセス:

```
http://localhost:8080
```

## 開発コマンド

### Composerコマンド

```bash
# パッケージのインストール
docker compose exec app composer install

# パッケージの追加
docker compose exec app composer require パッケージ名
```

### Artisanコマンド

```bash
# Artisanコマンドの実行
docker compose exec app php artisan [コマンド]

# 例: キャッシュクリア
docker compose exec app php artisan cache:clear

# 例: マイグレーション実行
docker compose exec app php artisan migrate

# 例: コントローラー作成
docker compose exec app php artisan make:controller UserController
```

### テスト実行

```bash
# PHPUnitテストの実行
docker compose exec app php artisan test

# または
docker compose exec app vendor/bin/phpunit
```

### コード品質チェック

```bash
# Laravel Pintでコードフォーマット
docker compose exec app vendor/bin/pint

# Larastanで静的解析
docker compose exec app vendor/bin/phpstan analyse
```

### ログ確認

```bash
# Laravel Pailでログをリアルタイム表示
docker compose exec app php artisan pail
```

### データベース接続

**ホストから接続する場合:**
- ホスト: `localhost`
- ポート: `3306`
- データベース: `laravel`
- ユーザー名: `laravel`
- パスワード: `password`

**コンテナ内から接続する場合:**
- ホスト: `db`
- ポート: `3306`
- データベース: `laravel`
- ユーザー名: `laravel`
- パスワード: `password`

## コンテナの操作

### コンテナの起動

```bash
docker compose up -d
```

### コンテナの停止

```bash
docker compose down
```

### コンテナの再起動

```bash
docker compose restart
```

### ログの確認

```bash
# 全サービスのログ
docker compose logs -f

# 特定のサービスのログ
docker compose logs -f app
docker compose logs -f nginx
docker compose logs -f db
```

### コンテナに入る

```bash
# アプリケーションコンテナ
docker compose exec app bash

# MySQLコンテナ
docker compose exec db bash
```

## ディレクトリ構成

```
.
├── docker-compose.yml    # Docker Compose設定
├── Dockerfile           # PHPコンテナのDockerfile
├── nginx/              # Nginx設定
│   └── conf.d/
│       └── default.conf
├── php/                # PHP設定
│   └── local.ini
├── src/                # Laravelアプリケーション
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── resources/
│   ├── routes/
│   └── ...
└── README.md
```

## トラブルシューティング

### パーミッションエラーが発生する場合

```bash
# srcディレクトリの所有者を変更
docker compose exec app chown -R laravel:laravel /var/www
```

### データベース接続エラーが発生する場合

1. `.env`ファイルの設定を確認
2. データベースコンテナが起動しているか確認: `docker compose ps`
3. データベースが作成されているか確認

### コンテナの完全な再ビルド

```bash
docker compose down -v
docker compose build --no-cache
docker compose up -d
```

## 本番環境へのデプロイ

本番環境にデプロイする前に:

1. `.env`ファイルを本番環境用に設定
2. `APP_DEBUG=false`に設定
3. `APP_ENV=production`に設定
4. データベースの認証情報を変更
5. 以下のコマンドを実行:

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## ライセンス

このプロジェクトはMITライセンスの下で公開されています。
