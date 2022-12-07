<?php

/**
 * ポケモン図鑑画面コントローラー
 * 
 * @since 1.0.0
 */
require_once ROOT_DIR . '/app/Controllers/Controller.php';
require_once ROOT_DIR . '/app/Models/UserPokemon.php';
require_once ROOT_DIR . '/app/Utill/Auth.php';

class PokemonController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // ポケモン図鑑に関する画面はすべてログイン必須（未ログインの場合リダイレクト）
        Auth::guard();
    }

    /**
     * マイポケモン図鑑画面表示前処理
     *
     * @return array
     */
    public function index(): array
    {
        // 1ページ表示件数
        $limit = 20;

        // 現在ページ番号
        $current_page = (int)Request::getParam('page', 1);

        // 最大ページ数
        $count = UserPokemon::getCount(Auth::getUserId());
        $max_page = $count > 0 ? (int)ceil($count / $limit) : 1;

        // ポケモン一覧取得
        $pokemons = UserPokemon::getJoinPokemon('user_id = ?', [Auth::getUserId()], 'pokemon_id', $limit, $limit * ($current_page - 1));

        return compact('pokemons', 'current_page', 'max_page');
    }
}
