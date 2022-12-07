<?php

/**
 * アプリケーション共通関数
 * 
 * @since 1.0.0
 */

if (!function_exists('isProduction')) {
    /**
     * 本番環境かどうか
     *
     * @return bool
     */
    function isProduction(): bool
    {
        return Config::get('app.env', 'development') === 'production';
    }
}

if (!function_exists('isDevelopment')) {
    /**
     * 開発環境かどうか
     *
     * @return bool
     */
    function isDevelopment(): bool
    {
        return Config::get('app.env', 'development') === 'development';
    }
}

if (!function_exists('redirect_error')) {
    /**
     * エラーページにリダイレクトする
     *
     * @param string|null $msg
     * @param string|null $back_url
     * @param string|null $title
     * @return void
     */
    function redirect_error(?string $msg = null, ?string $back_url = null, ?string $title = null, ?int $code = null)
    {
        $msg = $msg ?? 'エラーが発生しました！サーバー管理者にお問い合わせください。';
        if (is_null($back_url)) {
            $back_url = $_SERVER['HTTP_REFERER'] ?? Config::get('app.url');
        }
        $title = $title ?? 'Error!';

        $_SESSION['error'] = [
            'message' => $msg,
            'back_url' => $back_url,
            'title' => $title,
            'code' => $code,
        ];

        header('Location: ' . Config::get('app.url') . '/error.php');
    }
}

if (!function_exists('h')) {
    /**
     * サニタイズ
     * 
     * 文字列または数値以外は空文字を返す
     *
     * @param mixed $value
     * @return string
     */
    function h($value): string
    {
        if (is_string($value) || is_numeric($value)) {
            return htmlentities($value);
        } else {
            return '';
        }
    }
}

if (!function_exists('underscore')) {
    /**
     * 大文字区切りをアンダースコア区切りに変換
     *
     * 
     * @example camelize('camelString'): camel_string
     * @param string $str
     * @return string
     */
    function underscore(string $str): string
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_\0', $str)), '_');
    }
}

if (!function_exists('camelize')) {
    /**
     * アンダースコア区切りを大文字区切りに変換
     *
     * @example camelize('snake_string'): snakeString
     * @param string $str
     * @return string
     */
    function camelize(string $str): string
    {
        return lcfirst(strtr(ucwords(strtr($str, ['_' => ' '])), [' ' => '']));
    }
}
