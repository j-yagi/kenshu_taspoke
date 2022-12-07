<?php
require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/ProjectController.php';

$ctrl = new ProjectController();
extract($ctrl->show());

$title = h($project->name);
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
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link" href="/task/?project_id=<?= $project->id ?>">タスク</a>
                        </li>
                        <li class="nav-item">
                            <?php /* TODO: ガントチャート機能追加 */ ?>
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">ガントチャート</a>
                        </li>
                        <li class="nav-item">
                            <?php /* TODO: ノート機能追加 */ ?>
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">ノート</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/project/battle_log.php?project_id=<?= $project->id ?>">ポケモン対戦履歴</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="<?= Request::getCurrentUri() ?>" aria-current="true">プロジェクト情報</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body" style="min-height: 70vh;">
                    <div class="d-flex mb-5">
                        <a href="/project/edit.php?id=<?= $project->id ?>&ref=<?= Request::getCurrentUri() ?>" class="btn btn-outline-primary me-2">更新</a>
                        <a href="/project/delete.php?id=<?= $project->id ?>" class="btn btn-outline-danger">削除</a>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">プロジェクト名</label>
                        <input type="text" class="form-control" value="<?= h($project->name) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">参加者</label>
                        <div class="input-group">
                            <span class="input-group-text w-25">オーナー</span>
                            <input type="text" class="form-control w-75" value="自分" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include HTML_DIR . '/components/footer.php' ?>
</body>

</html>
