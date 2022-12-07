<?php

/**
 * 共通初期処理
 */

// ディレクトリ定数定義
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__);
}
if (!defined('HTML_DIR')) {
    define('HTML_DIR', ROOT_DIR . '/public_html');
}

// 外部ライブラリのオートロード（requireが不要になる）設定の読み込み
require_once ROOT_DIR . '/lib/autoload.php';

// 共通クラス、関数の読み込み
require_once ROOT_DIR . '/app/Utill/Config.php';
require_once ROOT_DIR . '/app/Utill/common.php';
require_once ROOT_DIR . '/app/Utill/Log.php';
require_once ROOT_DIR . '/app/Utill/Session.php';
require_once ROOT_DIR . '/app/Utill/Request.php';

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// セッションは必須のため初期処理で開始
Session::start();

// 不要なセッションデータがあれば削除
if (array_search(Request::getCurrentUri(), [
    '/user/register.php',
    '/user/confirm.php',
    '/user/complete.php'
]) === false) {
    // ユーザー登録、確認、完了画面から別画面に遷移した場合（URL直入力等）、
    // 入力内容が残っているため削除
    Session::remove('form_data.user.register');
}
