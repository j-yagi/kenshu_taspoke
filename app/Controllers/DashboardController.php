<?php

/**
 * ダッシュボード画面管理コントローラー
 * 
 * ダッシュボードに関する画面の表示に必要な処理を管理するクラス。
 * 
 * @since 1.0.0
 */

require_once ROOT_DIR . '/app/Controllers/Controller.php';
require_once ROOT_DIR . '/app/Utill/Auth.php';

class DashboardController extends Controller
{
    /**
     * コンストラクタ
     * 
     */
    public function __construct()
    {
        // ログインチェック
        Auth::guard();
    }

    /**
     * ダッシュボード画面表示前処理
     *
     * @return array
     */
    public function index(): array
    {
        return [];
    }
}
