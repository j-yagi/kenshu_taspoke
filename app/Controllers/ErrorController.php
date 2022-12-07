<?php

/**
 * エラー画面コントローラ
 * 
 * エラーに関する画面の表示に必要な処理を管理するクラス。
 * 
 * @since 1.0.0
 */

class ErrorController
{
    /**
     * エラー画面表示前処理
     *
     * @return array
     */
    public function error(): array
    {
        // セッションに保存されているエラー情報を取得
        $error = Session::pull('error', []);

        // セッションからエラー情報を取得できない場合、デフォルト値を設定
        $error['message'] = $error['message'] ?? 'エラーが発生しました！サーバー管理者にご連絡ください。';
        if (!isset($error['back_url'])) {
            $error['back_url'] = $_SERVER['HTTP_REFERER'] ?? Config::get('app.url');
        }
        $error['title'] = $error['title'] ?? 'Error!';

        if (isset($error['code'])) {
            http_response_code($error['code']);
        }

        return $error;
    }
}
