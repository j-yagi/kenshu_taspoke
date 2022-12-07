<?php

/**
 * ログアウト処理画面
 * 
 * @since 1.0.0
 */
require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/UserController.php';

$ctrl = new UserController();
$ctrl->logout();
