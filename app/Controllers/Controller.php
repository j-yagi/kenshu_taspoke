<?php

/**
 * コントローラー共通処理
 * 
 * @since 1.0.0
 */

class Controller
{
    public function redirect(string $url)
    {
        header('Location: ' . $url);
        exit;
    }
}
