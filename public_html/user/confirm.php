<?php

/**
 * アカウント確認画面
 * 
 * @since 1.0.0
 */
require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/UserController.php';

$ctrl = new UserController();
$data = $ctrl->confirm();

$title = 'アカウント登録確認';
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
                <div class="card-text text-start mb-5">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <span class="text-danger">*</span> アカウント名
                        </label>
                        <input type="text" class="form-control" id="name" value="<?= h($data['name']) ?>" disabled readonly>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <span class="text-danger">*</span> メールアドレス
                        </label>
                        <input type="text" class="form-control" id="email" value="<?= h($data['email']) ?>" disabled readonly>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <span class="text-danger">*</span> パスワード
                        </label>
                        <input type="password" class="form-control" id="email" value="********" disabled readonly>
                    </div>
                </div>
                <div class="w-100 mb-4">
                    <a href="register.php" class="btn btn-outline-secondary col-5">キャンセル</a>
                    <a href="complete.php" class="btn btn-outline-primary col-5">登録</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
