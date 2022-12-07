<?php

/**
 * アカウント登録画面
 * 
 * @since 1.0.0
 */
require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/UserController.php';

$ctrl = new UserController();
$sets = $ctrl->register();
$errors = $sets['errors'];
$old = $sets['old'];

$title = 'アカウント登録';
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
                <input type="hidden" name="_token" value="<?= Request::generateCsrfToken('user.register') ?>">
                <h1 class="card-title fs-5 fw-bolder fst-italic text-warning mb-3">
                    <?= Config::get('app.name', 'タスポケ') ?>
                </h1>
                <h2 class="card-subtitle mb-2 text-muted fs-4 mb-4"><?= $title ?></h2>
                <div class="card-text text-start mb-5">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <span class="text-danger">*</span> アカウント名
                        </label>
                        <?php if (!isset($errors['name'])) : ?>
                            <input type="text" name="name" class="form-control" id="name" value="<?= h($old['name'] ?? '') ?>">
                        <?php else : ?>
                            <input type="text" name="name" class="form-control is-invalid" id="name" value="<?= h($old['name'] ?? '') ?>">
                            <div class="invalid-feedback">
                                <?php foreach ($errors['name'] as $message) : ?>
                                    <div><?= $message ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <span class="text-danger">*</span> メールアドレス
                        </label>
                        <?php if (!isset($errors['email'])) : ?>
                            <input type="email" name="email" class="form-control" id="email" value="<?= h($old['email'] ?? '') ?>">
                        <?php else : ?>
                            <input type="email" name="email" class="form-control is-invalid" id="email" value="<?= h($old['email'] ?? '') ?>">
                            <div class="invalid-feedback">
                                <?php foreach ($errors['email'] as $message) : ?>
                                    <div><?= $message ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <span class="text-danger">*</span> パスワード
                        </label>
                        <?php if (!isset($errors['password'])) : ?>
                            <input type="password" name="password" class="form-control" id="password">
                        <?php else : ?>
                            <input type="password" name="password" class="form-control is-invalid" id="password">
                            <div class="invalid-feedback">
                                <?php foreach ($errors['password'] as $message) : ?>
                                    <div><?= $message ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="w-100 mb-4">
                    <button type="submit" class="btn btn-outline-success w-100">確認</button>
                </div>
                <div class="w-100 text-muted">
                    すでにアカウントがある場合は
                    <a class="card-link" href='login.php'>こちらからログイン</a>
                    してください。
                </div>
            </form>
        </div>
    </div>
</body>

</html>
