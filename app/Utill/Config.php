<?php

/**
 * 環境変数取得クラス
 * 
 * @since 1.0.0
 */

class Config
{
    /** @var array $configs 環境変数格納用 */
    private static array $configs = [];

    /**
     * 環境変数取得
     * 
     * @example Cofig::get('app.name') config/app.phpのnameキーの値を取得
     * @param string $name      configディレクトリ内のファイル名（拡張子なし）
     * @param string $defalt    環境変数がない場合の返却値
     * @return mixed
     */
    public static function get(string $name, ?string $default = null)
    {
        // ドット区切りで1つ目をファイル名として、以降を環境変数配列の階層として扱う
        $names = explode('.', $name, 2);

        if (self::loadConfigFile($names[0]) === false) {
            return $default;
        }

        $conf = self::$configs[$names[0]];
        if (isset($names[1]) && !empty($names[1])) {
            foreach (explode('.', $names[1]) as $key) {
                if (isset($conf[$key])) {
                    $conf = $conf[$key];
                } else {
                    $conf = $default;
                    break;
                }
            }
        }

        return $conf;
    }

    /**
     * configファイル読み取り
     * 
     * @param string $fname configディレクトリ内のファイル名（拡張子なし）
     * @return bool
     */
    private static function loadConfigFile(string $fname): bool
    {
        if (!isset(self::$configs[$fname])) {
            $path = ROOT_DIR . "/config/{$fname}.php";
            if (file_exists($path)) {
                self::$configs[$fname] = require_once $path;
            } else {
                return false;
            }
        }

        return true;
    }
}
