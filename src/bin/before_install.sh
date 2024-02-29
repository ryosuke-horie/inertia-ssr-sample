#!/bin/bash
# ソースコードのデプロイ前に実行されるスクリプト。
# 全バージョンのキャッシュの影響を受けないように、デプロイ先のディレクトリを削除する

# シンボリックリンクを削除
unlink /opt/cheerpay/laravel/current/storage
unlink /opt/cheerpay/laravel/current/.env

# currentディレクトリを削除
rm -rf /opt/cheerpay/laravel/current

# currentディレクトリを作成
mkdir -p /opt/cheerpay/laravel/current
