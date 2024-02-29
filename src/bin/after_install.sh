#!/bin/bash
# ソースコードのデプロイ後に実行されるスクリプト。
# SSR用のビルド成果物はすでにビルド済みのため、
# 本番用の依存関係のインストール、データベースマイグレーション、SSRのバックグラウンド実行などを行う

path="/opt/cheerpay/laravel"
cd ${path}/current || exit 99

# Storageは/opt/cheerpay/laravel/shared以下のものを利用するため、
# 先にcurrentディレクトリ内のstorageを削除
rm -rf ${path}/current/storage
# .envとstorageをシンボリックリンクで再接続
ln -nfs ${path}/shared/.env ${path}/current/.env
ln -nfs ${path}/shared/storage ${path}/current/storage

# ディレクトリ内ファイルの所有者を再帰的にnginxに変更
chown -R nginx:nginx ${path}/current

# Laravelアプリケーション用のコマンドを実行
# 依存関係をインストール
sudo -u nginx composer install --optimize-autoloader --no-dev
sudo -u nginx npm ci --production

# 設定のキャッシュ
sudo -u nginx php artisan config:cache
# ルートのキャッシュ
sudo -u nginx php artisan route:cache
# データベースマイグレーション
sudo -u nginx php artisan migrate --force
# ssrをバックグラウンドで起動
sudo -u nginx php artisan inertia:start-ssr > /dev/null 2> /dev/null < /dev/null &
