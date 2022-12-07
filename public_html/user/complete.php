<?php

/**
 * アカウント登録完了画面
 * 
 * @since 1.0.0
 */
require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/UserController.php';

$ctrl = new UserController();
$ctrl->complete();

$title = 'アカウント登録完了';
?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <?php include HTML_DIR . '/components/head.php' ?>
</head>

<body class="bg-light">
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="card text-center col-12 col-lg-6 shadow-lg p-3">
            <form name="user.register" method="POST" action="confirm.php" class="card-body">
                <h1 class="card-title fs-5 fw-bolder fst-italic text-warning mb-3">
                    <?= Config::get('app.name', 'タスポケ') ?>
                </h1>
                <h2 class="card-subtitle mb-2 text-muted fs-4 mb-4"><?= $title ?></h2>
                <div class="card-text mb-5">
                    アカウント登録が完了しました。
                    ログインして使用を開始してください。
                </div>
                <div class="w-100 mb-4">
                    <a href="login.php" class="btn btn-success width-100">ログイン</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
