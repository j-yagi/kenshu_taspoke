<?php

/**
 * ログイン画面
 * 
 * @since 1.0.0
 */
require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/UserController.php';

$ctrl = new UserController();
extract($ctrl->login());

$title = 'ログイン';
?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <?php include HTML_DIR . '/components/head.php' ?>

</head>

<body class="bg-light">
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="card text-center col-12 col-lg-6 shadow-lg p-3">
            <form name="user.login" method="POST" class="card-body">
                <input type="hidden" name="_token" value="<?= Request::generateCsrfToken('user.login') ?>">
                <h1 class="card-title fs-4 fw-bolder fst-italic text-warning mb-3">
                    <?= Config::get('app.name', 'タスポケ') ?>
                </h1>
                <div class="card-text text-start mb-5">
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
                    <button type="submit" class="btn btn-outline-primary w-100">ログイン</button>
                </div>
                <div class="w-100 text-muted">
                    アカウントをお持ちでない方は
                    <a class="card-link" href='register.php'>こちらから作成</a>
                    してください。
                </div>
            </form>
        </div>
    </div>
</body>

</html>
