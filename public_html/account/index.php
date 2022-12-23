<?php

/**
 * マイアカウント画面
 * 
 * @since 1.0.0
 */

//  bootstrapを読み込む
require_once '../../bootstrap.php';
// Usercontrollerを読み込む
require_once ROOT_DIR . '/app/Controllers/UserController.php';

<<<<<<< HEAD
// if(!empty($_POST['name'])){
//         $ctrl = new UserController();
//         $ctrl->update($_POST['name'],$_POST['email'],$_POST['password']);
// }

$ctrl = new UserController();
extract($ctrl->edit());
$title = 'プロジェクト' . (Request::getParam('id') ? '更新' : '登録');
=======
if(!empty($_POST['name'])){
        // UserControllerクラスのインスタンスを生成
        $ctrl = new UserController();
        $ctrl->edit($_POST['name'],$_POST['email'],$_POST['password']);
        $message = 'アカウント情報を更新しました。';
} 
>>>>>>> b4a23ee (update)

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
<<<<<<< HEAD
            
            <form action="" method="post">
                <label for="name">*アカウント名</label>
                    <input type="text" id="name" name="name" require>
                <label for="email">*メールアドレス</label>
                    <input type="email" id="email" name="email" require>
                <label for="password">*パスワード</label>
                    <input type="password" id="password" name="password" require>
                    <br>

                    <div class="mb-3">
                    <label for="name" class="form-label">
                        <span class="text-danger">*</span> アカウント名
                    </label>
                    <?php if (!isset($errors['name'])) : ?>
                        <input type="text" name="name" class="form-control" id="name" value="<?= h($old['name'] ?? $project->name) ?>">
                    <?php else : ?>
                        <input type="text" name="name" class="form-control is-invalid" id="name" value="<?= h($old['name'] ?? $project->name) ?>">
                        <div class="invalid-feedback">
                            <?php foreach ($errors['name'] as $message) : ?>
                                <div><?= $message ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                    <div class="mb-3">
                    <label for="name" class="form-label">
                        <span class="text-danger">*</span> アカウント名
                    </label>
                    <?php if (!isset($errors['name'])) : ?>
                        <input type="text" name="name" class="form-control" id="name" value="<?= h($old['name'] ?? $project->name) ?>">
                    <?php else : ?>
                        <input type="text" name="name" class="form-control is-invalid" id="name" value="<?= h($old['name'] ?? $project->name) ?>">
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
                        <input type="text" email="email" class="form-control" id="email" value="<?= h($old['email'] ?? $project->email) ?>">
                    <?php else : ?>
                        <input type="text" email="email" class="form-control is-invalid" id="email" value="<?= h($old['email'] ?? $project->email) ?>">
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
                        <input type="text" password="password" class="form-control" id="password" value="<?= h($old['password'] ?? $project->password) ?>">
                    <?php else : ?>
                        <input type="text" password="password" class="form-control is-invalid" id="password" value="<?= h($old['password'] ?? $project->password) ?>">
                        <div class="invalid-feedback">
                            <?php foreach ($errors['password'] as $message) : ?>
                                <div><?= $message ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                    <button type="button">戻る</button>
                    <button type="submit">更新</button>
=======

            <?php if(!empty($message)) :?>
                <p><?php echo $message ?></p>
                <!-- 更新失敗のメッセージ -->
            <?php endif; ?>

            <form name="user.edit" action="" method="post">
                <div class="mb-3">
                        <label for="name" class="form-label">
                            <span class="text-danger">*</span> アカウント名
                        </label>
                        <?php if (!isset($errors['name'])) : ?>
                            <input type="text" name="name" class="form-control" id="email" value="<?= h($old['name'] ?? '') ?>">
                        <?php else : ?>
                            <input type="name" name="name" class="form-control is-invalid" id="name" value="<?= h($old['name'] ?? '') ?>">
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
                            <input type="password" name="password" class="form-control" id="password" value="<?= h($old['password'] ?? '') ?>">
                        <?php else : ?>
                            <input type="password" name="password" class="form-control is-invalid" id="password" value="<?= h($old['password'] ?? '') ?>">
                            <div class="invalid-feedback">
                                <?php foreach ($errors['password'] as $message) : ?>
                                    <div><?= $message ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                </div>
                <button  type="button">戻る</button>
                <button type="submit" name="submit">更新</button>
>>>>>>> b4a23ee (update)
            </form>
        </div>
    </div>

    <?php include HTML_DIR . '/components/footer.php' ?>
</body>

</html>