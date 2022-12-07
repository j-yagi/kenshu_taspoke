<?php

/**
 * ポケモン対戦履歴画面
 * 
 * @since 1.0.0
 */
require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/ProjectController.php';

$ctrl = new ProjectController();
extract($ctrl->battle_log());

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
                            <a class="nav-link active" href="<?= Request::getCurrentUri() ?>" aria-current="true">ポケモン対戦履歴</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/project/show.php?id=<?= $project->id ?>">プロジェクト情報</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body" style="min-height: 70vh;">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-secondary text-center">
                                <tr class="text-nowrap">
                                    <th>キー</th>
                                    <th>対戦ポケモン</th>
                                    <th>対戦仲間</th>
                                    <th>メッセージ</th>
                                    <th>日時</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logs as $log) : ?>
                                    <tr>
                                        <td>
                                            <a href="/task/edit.php?project_id=<?= $project->id ?>&id=<?= $log->task_id ?>&ref=<?= Request::getCurrentUri() ?>">
                                                <?= $log->task_code ? 'TASK-' . $log->task_code : '' ?>
                                            </a>
                                        </td>
                                        <td>
                                            <img src="<?= $log->action_code === Task::BATTLE_STATUS['DEFEAT'] ? $log->pokemon_back_img_url : $log->pokemon_front_img_url ?>" alt="pokemon">
                                        </td>
                                        <td><?= h($log->user_name) ?></td>
                                        <td><?= h($log->message) ?></td>
                                        <td><?= $log->updated_at ?></td>
                                    </tr>
                                <?php endforeach; ?>
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
