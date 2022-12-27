<?php 
// ini_set( 'display_errors', 1 );

/**
 * マイアカウント画面
 * 
 * @since 1.0.0
 */

require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/UserController.php';

$ctrl = new UserController();
$ctrl->edit();

$errors = Session::get('errors');
$old = Session::get('old');

$title = 'アカウント更新';
?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <?php include HTML_DIR . '/components/head.php'?>
</head>

<body class="text-body bg-light">
    <div class="d-flex">

        <?php include HTML_DIR . '/components/sidenav.php' ?>

        <div class="container p-3">
            <h2 class="fs-4 mb-4"><?= $title ?></h2>
            <form name='user.edit' action="" method="post">
                    <div class="mb-3">
                    <label for="name" class="form-label">
                        <span class="text-danger">*</span> アカウント名
                    </label>
                    <?php if (!isset($errors['name'])) : ?>
                        <input type="text" name="name" class="form-control" id="name" value="<?= $old['name'] ?? $user->name ?>">
                    <?php else : ?>
                        <input type="text" name="name" class="form-control is-invalid" id="name" value="<?= $old['name'] ?? $user->name ?>">
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
                        <input type="email" name="email" class="form-control" id="email" value="<?= h($old['email'] ?? $user->email) ?>">
                    <?php else : ?>
                        <input type="email" name="email" class="form-control is-invalid" id="email" value="<?= h($old['email'] ?? $user->email) ?>">
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
                        <input type="password" name="password" class="form-control" id="password" >
                    <?php else : ?>
                        <input type="password" name="password" class="form-control is-invalid" id="password" >
                        <div class="invalid-feedback">
                            <?php foreach ($errors['password'] as $message) : ?>
                                <div><?= $message ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex">
                <a href="<?= '/account' ?>" class="btn btn-secondary">戻る</a>
                    <button type="submit" class="btn btn-primary">更新</button>
                </div>
            </form>
        </div>
    </div>

    <?php include HTML_DIR . '/components/footer.php' ?>
</body>

</html>