<?php

/**
 * セッション管理クラス
 * 
 * @since 1.0.0
 */

class Session
{
    /**
     * セッションが開始されているか
     *
     * @return bool
     */
    public static function isActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * セッション開始
     *
     * @return bool
     */
    public static function start(): bool
    {
        return self::isActive() ? false : session_start();
    }

    /**
     * セッションに格納
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public static function set(string $name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * セッションから指定したキーの値を取得
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $name, $default = null)
    {
        return $_SESSION[$name] ?? $default;
    }

    /**
     * セッションから指定したキーの値を削除
     *
     * @param string $name
     * @return void
     */
    public static function remove(string $name): void
    {
        unset($_SESSION[$name]);
    }

    /**
     * セッションから指定したキーの値を取得後削除
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function pull(string $name, $default = null)
    {
        $value = self::get($name, $default);
        self::remove($name);

        return $value;
    }

    /**
     * セッション情報をすべて削除
     *
     * @return void
     */
    public static function clear(): void
    {
        $_SESSION = array();
    }

    /**
     * セッションIDを新しく生成して置き換え
     *
     * @param bool $delete_old_session 古いセッションを削除するかどうか
     * @return bool
     */
    public static function regenerate(bool $delete_old_session = false): bool
    {
        return session_regenerate_id($delete_old_session);
    }

    /**
     * 現在のセッションIDを取得
     *
     * @return string|false
     */
    public static function getId()
    {
        return session_id();
    }
}
