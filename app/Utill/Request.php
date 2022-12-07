<?php

/**
 * リクエストに関する共通処理
 * 
 * @since 1.0.0
 */

require_once ROOT_DIR . '/app/Utill/Session.php';

class Request
{
    /**
     * CSRFトークン保存最大数
     * 
     * @var int
     */
    protected const CSRF_TOKEN_LIMIT = 10;

    /**
     * GETリクエストかどうか
     *
     * @return bool
     */
    public static function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * POSTリクエストかどうか
     *
     * @return bool
     */
    public static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * GETデータから指定したキーの値を取得
     *
     * @param string|null $name
     * @param mixed $default
     * @return mixed
     */
    public static function getParam(?string $name = null, $default = null)
    {
        if ($name) {
            return $_GET[$name] ?? $default;
        } else {
            return $_GET;
        }
    }

    /**
     * POSTデータから指定したキーの値を取得
     *
     * @param string|null $name
     * @param mixed $default
     * @return mixed
     */
    public static function getPost(?string $name = null, $default = null)
    {
        if ($name) {
            return $_POST[$name] ?? $default;
        } else {
            return $_POST;
        }
    }

    /**
     * CSRFトークンを生成しセッションに保存
     *
     * 同一画面を複数開いた場合も対応するよう、フォーム名ごとに
     * self::CSRF_TOKEN_LIMIT個までトークンを保持できるものとする。
     * 
     * @param string $form_name
     * @return string
     */
    public static function generateCsrfToken(string $form_name): string
    {
        $key = 'csrf_tokens/' . $form_name;
        $tokens = Session::get($key, []);
        if (count($tokens) >= self::CSRF_TOKEN_LIMIT) {
            // トークン最大保持数を超えた場合、古いものを削除
            array_shift($tokens);
        }

        $token = hash('sha256', $form_name . Session::getId() . microtime());
        $tokens[] = $token;
        Session::set($key, $tokens);

        return $token;
    }

    /**
     * 指定したトークンがセッションに保持しているトークンと合致するか
     *
     * @param string $form_name
     * @param string $token
     * @return bool
     */
    public static function checkCsrfToken(string $form_name, string $token): bool
    {
        $key = 'csrf_tokens/' . $form_name;
        $tokens = Session::pull($key, []);

        $result = false;
        if (($pos = array_search($token, $tokens, true)) !== false) {
            // 合致するものがある場合、セッションから該当トークンを削除
            unset($tokens[$pos]);
            $result = true;
        }

        if ($tokens) {
            Session::set($key, $tokens);
        }

        return $result;
    }

    /**
     * リクエストURIを取得
     *
     * @return string
     */
    public static function getCurrentUri(): string
    {
        return $_SERVER['REQUEST_URI'] ?? '';
    }

    /**
     * 直前のリクエストURIを取得
     * 
     * @return string
     */
    public static function getReferer(string $default = '/'): string
    {
        return $_SERVER['HTTP_REFERER'] ?: $default;
    }
}
