<?php
require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/ProjectController.php';

$ctrl = new ProjectController();
extract($ctrl->index());
$title = 'プロジェクト一覧';
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
            <div class="row align-items-center justify-content-between mb-3">
                <div class="col-md-6 order-1 order-md-0">
                    <h2 class="fs-4"><?= $title ?></h2>
                </div>
                <div class="col-md-6 text-end">
                    <a href="/project/edit.php" class="btn btn-outline-success">
                        <i class="fa-solid fa-pen"></i>
                        作成
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <form method="GET">
                    <small class="text-secondary">プロジェクト名またはその一部を入力して検索できます。</small>
                    <div class="mb-2">
                        <?php if (!isset($errors['keyword'])) : ?>
                            <input type="text" name="keyword" class="form-control" placeholder="キーワード" value="<?= Request::getParam('keyword') ?>">
                        <?php else : ?>
                            <input type="text" name="keyword" class="form-control is-invalid" placeholder="キーワード" value="<?= Request::getParam('keyword') ?>">
                            <div class="invalid-feedback">
                                <?php foreach ($errors['keyword'] as $message) : ?>
                                    <div><?= $message ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex">
                        <a href="index.php" class="btn btn-outline-secondary">絞り込み解除</a>
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            検索
                        </button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-secondary text-center">
                        <tr class="text-nowrap">
                            <th>プロジェクト名</th>
                            <th>オーナー</th>
                            <th>参加者</th>
                            <th>最終更新日</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list as $project) : ?>
                            <tr>
                                <td><?= h($project->name) ?></td>
                                <td class="text-center text-nowrap">
                                    <?= $project->isOwner() ? '自分' : h($project->owner_name) ?>
                                </td>
                                <td class="text-center text-nowrap"><?= $project->attendees_count ?>人</td>
                                <td class="text-center text-nowrap"><?= $project->updated_at ?></td>
                                <td class="text-center text-nowrap">
                                    <a href="/task?project_id=<?= $project->id ?>" class="btn btn-sm btn-success">詳細</a>
                                    <?php if ($project->isOwner()) : ?>
                                        <a href="edit.php?id=<?= $project->id ?>" class="btn btn-sm btn-primary">更新</a>
                                        <a href="delete.php?id=<?= $project->id ?>" class="btn btn-sm btn-danger">削除</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <?php include HTML_DIR . '/components/footer.php' ?>
</body>

</html>
