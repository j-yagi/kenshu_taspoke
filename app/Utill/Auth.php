<?php

/**
 * 認証管理
 * 
 * @since 1.0.0
 */

class Auth
{
    /**
     * ログイン前にログイン後表示可能な画面にアクセスした場合のリダイレクト先
     * 
     * @var string
     */
    public const BEFORE_LOGIN_REDIRECT_URI = '/user/login.php';

    /**
     * ログイン後に未ログインで表示可能な画面にアクセスした場合のリダイレクト先
     * 
     * @var string
     */
    public const AFTER_LOGIN_REDIRECT_URI = '/';

    /**
     * ログイン済みかどうか
     *
     * @return bool
     */
    public static function isLoggedIn(): bool
    {
        return (bool)Session::get('user_id', false);
    }

    /**
     * ログインユーザーIDをセッションに保存
     *
     * @param int $id
     * @return void
     */
    public static function setUserId(?int $id = null): void
    {
        if ($id) {
            Session::set('user_id', $id);
            Session::regenerate();
        } else {
            Session::clear();
        }
    }

    /**
     * ログイン済ユーザーIDを取得
     *
     * @return int|null
     */
    public static function getUserId()
    {
        return Session::get('user_id');
    }

    /**
     * リダイレクトして終了
     *
     * @param string|null $uri
     * @return never
     */
    public static function redirectTo(?string $uri = null): never
    {
        $uri = $uri ?? static::BEFORE_LOGIN_REDIRECT_URI;
        header('Location: ' . $uri);
        exit;
    }

    /**
     * ログインしていない場合リダイレクトして終了
     *
     * @return void
     */
    public static function guard()
    {
        if (static::isLoggedIn() === false) {
            static::redirectTo();
        }
    }

    /**
     * ログイン済の場合リダイレクトして終了
     *
     * @return void
     */
    public static function guest()
    {
        if (static::isLoggedIn()) {
            static::redirectTo(self::AFTER_LOGIN_REDIRECT_URI);
        }
    }
}
