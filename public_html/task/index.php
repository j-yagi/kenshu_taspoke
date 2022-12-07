<?php

/**
 * タスク一覧画面
 * 
 * @since 1.0.0
 */
require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/TaskController.php';

$ctrl = new TaskController();
extract($ctrl->index());

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
                            <a class="nav-link active" href="<?= Request::getCurrentUri() ?>" aria-current="true">タスク</a>
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
                            <a class="nav-link" href="/project/show.php?id=<?= $project->id ?>">プロジェクト情報</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body" style="min-height: 70vh;">
                    <div class="mb-5">
                        <a href="/task/edit.php?project_id=<?= $project->id ?>" class="btn btn-outline-success me-2">
                            <i class="fa-solid fa-pen"></i>
                            作成
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-secondary text-center">
                                <tr class="text-nowrap">
                                    <th>キー</th>
                                    <th>件名</th>
                                    <th>担当者</th>
                                    <th>状態</th>
                                    <th>期限</th>
                                    <th>更新日</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tasks as $task) : ?>
                                    <tr>
                                        <td class="text-center text-nowrap">
                                            <a href="edit.php?project_id=<?= $task->project_id ?>&id=<?= $task->id ?>">
                                                TASK-<?= $task->code ?>
                                            </a>
                                        </td>
                                        <td><?= h($task->title) ?></td>
                                        <td class="text-center text-nowrap">
                                            <?= h($task->assign_user_name) ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <?= Task::STATUS_DISP[$task->status_code] ?>
                                        </td>
                                        <td class="text-center text-nowrap"><?= $task->complete_date ? $task->complete_date_dt->format('Y/m/d') : '' ?></td>
                                        <td class="text-center text-nowrap"><?= $task->updated_at_dt->format('Y/m/d') ?></td>
                                        <td class="text-center text-nowrap">
                                            <a href="edit.php?project_id=<?= $task->project_id ?>&id=<?= $task->id ?>" class="btn btn-sm btn-primary">更新</a>
                                            <a href="delete.php?project_id=<?= $task->project_id ?>&id=<?= $task->id ?>" class="btn btn-sm btn-danger">削除</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php include HTML_DIR . '/components/footer.php' ?>
</body>

</html>
