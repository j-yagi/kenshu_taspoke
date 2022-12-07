<?php
require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/TaskController.php';

$ctrl = new TaskController();
$data = $ctrl->delete();

$title = 'タスク削除';

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

            <ul class="mb-3">
                <li>タスク情報が削除されます。</li>
                <li>獲得済みポケモン情報は削除されません。</li>
                <li>ノートの紐づけ情報が削除されます。（ノート自体は削除されません）</li>
            </ul>

            <div class="mb-3">
                <strong>タスクを削除してよろしいですか？</strong>
            </div>

            <form name="task.delete" method="POST" class="d-flex">
                <input type="hidden" name="_token" value="<?= Request::generateCsrfToken('task.delete') ?>">
                <input type="hidden" name="id" value="<?= Request::getParam('id') ?>">
                <button type="button" onclick="history.back()" class="btn btn-secondary me-2">キャンセル</button>
                <button type="submit" class="btn btn-danger">はい、削除します。</button>
            </form>
        </div>
    </div>

    <?php include HTML_DIR . '/components/footer.php' ?>
</body>

</html>
