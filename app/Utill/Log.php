<?php

/**
 * ログ管理クラス
 * 
 * @since 1.0.0
 */

class Log
{
    /** @var string LOG_DIR ログ格納ディレクトリ */
    protected const LOG_DIR = ROOT_DIR . '/storage/logs';

    /** @var array LEVELS ログレベル出力名 */
    protected const LEVELS = [
        'info' => 'INFO',
        'debug' => 'DEBUG',
        'warning' => 'WARNING',
        'error' => 'ERROR',
    ];

    /** @var string|null $file  ログファイルフルパス */
    protected static ?string $file = null;

    /**
     * infoログ出力
     *
     * @param string $msg
     * @param string|null $method
     * @return bool
     */
    public static function info(string $msg, ?string $method = null): bool
    {
        return self::put(self::LEVELS[__FUNCTION__], $msg, $method);
    }

    /**
     * debugログ出力
     *
     * @param string $msg
     * @param string $method
     * @return bool
     */
    public static function debug(string $msg, ?string $method = null): bool
    {
        return self::put(self::LEVELS[__FUNCTION__], $msg, $method);
    }

    /**
     * warningログ出力
     *
     * @param string $msg
     * @param string|null $method
     * @return bool
     */
    public static function warning(string $msg, ?string $method = null): bool
    {
        return self::put(self::LEVELS[__FUNCTION__], $msg, $method);
    }

    /**
     * errorログ出力
     *
     * @param string $msg
     * @param string|null $method
     * @return bool
     */
    public static function error(string $msg, ?string $method = null): bool
    {
        return self::put(self::LEVELS[__FUNCTION__], $msg, $method);
    }

    /**
     * ログ出力
     *
     * @param string $level
     * @param string $msg
     * @param string|null $method
     * @return bool
     */
    protected static function put(string $level, string $msg, ?string $method = null): bool
    {
        if (self::createLogFile() === false) {
            return false;
        }

        $datetime = date("Y/m/d H:i:s");
        $log = "[{$datetime}] - $level - $method : $msg" . PHP_EOL;
        if (file_put_contents(self::$file, $log, FILE_APPEND | LOCK_EX) === false) {
            return false;
        }

        return true;
    }

    /**
     * ログファイル（self::LOG_DIR/yyyy/mm/dd.log）作成
     * 
     * @return bool
     */
    protected static function createLogFile(): bool
    {
        if (self::$file === null) {
            try {
                $dir = self::LOG_DIR . '/' . date('Y/m');
                $file = $dir . '/' . date('d') . '.log';

                if (!is_dir($dir)) {
                    mkdir($dir, 0775, true);
                }

                self::$file = $file;
            } catch (Exception $e) {
                // self::LOG_DIRのパーミッションエラーで例外が起こりうる
                if (isDevelopment()) {
                    echo $e->getMessage();
                }

                return false;
            }
        }

        return true;
    }
}
