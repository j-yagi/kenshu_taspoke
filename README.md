# タスク管理 Web アプリケーション

## 環境

- PHP: 7.4 以上

- MySQL

## 構築

1. Git クローンする。

1. `config/app.default.php`をコピーし、`config/app.php`にリネームする。

1. `config/app.php`をローカル環境に合わせて変更する。

1. `config/db.default.php`をコピーし、`config/db.php`にリネームする。

1. `config/db.php`をローカル環境に合わせて変更する。

1. コンソール（PowerShell やコマンドプロンプト等）を使用し、PHP コマンドで`php ./bin/setup.php`を実行する。

1. コンソールを使用し、`composer install`コマンドを実行する。(ローカル環境に Composer がインストールされていない場合は[インストール](https://weblabo.oscasierra.net/php-composer-windows-install/)する)

1. `public_html`をドキュメントルートに設定する。

1. Web ブラウザからアクセスし、動作確認を行う。
