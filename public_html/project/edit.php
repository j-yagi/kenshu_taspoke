<?php

/**
 * プロジェクト登録、更新画面
 * 
 * @since 1.0.0
 */
require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/ProjectController.php';

$ctrl = new ProjectController();
extract($ctrl->edit());
$title = 'プロジェクト' . (Request::getParam('id') ? '更新' : '登録');
?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <?php include HTML_DIR . '/components/head.php' ?>
</head>

<body class="text-body bg-light">
    <div class="d-flex">

        <?php include HTML_DIR . '/components/sidenav.php' ?>

        <div class="container p-3">
            <h2 class="fs-4 mb-4"><?= $title ?></h2>

            <form name="project.edit" method="POST">
                <input type="hidden" name="_token" value="<?= Request::generateCsrfToken('project.edit') ?>">
                <input type="hidden" name="id" value="<?= $project->id ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">
                        <span class="text-danger">*</span> プロジェクト名
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

                <div class="mb-4">
                    <label class="form-label">
                        <span class="text-danger">*</span> 参加者
                    </label>
                    <div class="input-group">
                        <span class="input-group-text w-25">オーナー</span>
                        <input type="text" class="form-control w-75" value="自分" disabled readonly>
                    </div>

                    <?php /* TODO: チーム機能追加 ?>
                    <div class="input-group">
                        <select name="invitation_type[]" class="form-select w-25">
                            <option value="1" selected>メールアドレス</option>
                            <option value="2">チーム</option>
                        </select>
                        <input type="email" name="email[]" class="form-control w-75">
                    </div>
                    <div class="input-group">
                        <select name="invitation_type[]" class="form-select w-25">
                            <option value="1">メールアドレス</option>
                            <option value="2" selected>チーム</option>
                        </select>
                        <select name="team[]" class="form-select w-75">

                        </select>
                    </div>
                    */ ?>
                </div>

                <div class="flex">
                    <a href="<?= Request::getParam('ref') ?: '/project' ?>" class="btn btn-secondary">戻る</a>
                    <button type="submit" class="btn btn-primary">登録</button>
                </div>

            </form>
        </div>
    </div>

    <?php include HTML_DIR . '/components/footer.php' ?>
</body>

</html>
