<?php

/**
 * タスク登録、更新画面
 * 
 * @since 1.0.0
 */
require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/TaskController.php';

$ctrl = new TaskController();
extract($ctrl->edit());
$title = 'タスク' . (Request::getParam('id') ? '更新' : '登録');
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

            <form name="task.edit" method="POST">
                <input type="hidden" name="_token" value="<?= Request::generateCsrfToken('task.edit') ?>">
                <input type="hidden" name="id" value="<?= $task->id ?>">
                <input type="hidden" name="code" value="<?= $task->code ?>">
                <input type="hidden" name="project_id" value="<?= $task->project_id ?>">
                <input type="hidden" name="pokemon_id" value="<?= $task->pokemon_id ?>">

                <div class="mb-3 row justify-content-center">
                    <div class="border border-secondary rounded col-md-3 col-12 align-self-center text-center">
                        <img src="<?= $task->battle_status_code === TASK::BATTLE_STATUS['DEFEAT'] ? $pokemon->back_img_url : $pokemon->front_img_url ?>" alt="pokemon" class="w-50">
                        <p><?= $pokemon->name_ja . TASK::BATTLE_STATUS_MSG[$task->battle_status_code] ?></p>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">
                        <span class="text-danger">*</span> 件名
                    </label>
                    <input type="text" name="title" class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>" id="title" value="<?= h($old['title'] ?? $task->title) ?>">

                    <?php if (isset($errors['title'])) : ?>
                        <div class="invalid-feedback">
                            <?php foreach ($errors['title'] as $message) : ?>
                                <div><?= $message ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">
                        説明
                    </label>
                    <textarea name="description" id="description" rows="3" class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>"><?= h($old['description'] ?? $task->description) ?></textarea>
                    <?php if (isset($errors['description'])) : ?>
                        <div class="invalid-feedback">
                            <?php foreach ($errors['description'] as $message) : ?>
                                <div><?= $message ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="status_code" class="form-label">
                        <span class="text-danger">*</span> 状態
                    </label>
                    <select name="status_code" id="status_code" class="form-select <?= isset($errors['status_code']) ? 'is-invalid' : '' ?>">
                        <?php foreach (Task::STATUS_DISP as $code => $name) : ?>
                            <option value="<?= $code ?>" <?= $code === $task->status_code ? 'selected' : '' ?>>
                                <?= $name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['status_code'])) : ?>
                        <div class="invalid-feedback">
                            <?php foreach ($errors['status_code'] as $message) : ?>
                                <div><?= $message ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="assign_user_id" class="form-label">
                        担当者
                    </label>
                    <select name="assign_user_id" id="assign_user_id" class="form-select <?= isset($errors['assign_user_id']) ? 'is-invalid' : '' ?>">
                        <?php foreach ($attendees as $attendee) : ?>
                            <option value="<?= $attendee->user_id ?>" <?= $attendee->user_id === $task->assign_user_id ? 'selected' : '' ?>>
                                <?= h($attendee->user_name) ?>
                            </option>
                        <?php endforeach; ?>
                        <option value=""></option>
                    </select>
                    <?php if (isset($errors['assign_user_id'])) : ?>
                        <div class="invalid-feedback">
                            <?php foreach ($errors['assign_user_id'] as $message) : ?>
                                <div><?= $message ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">
                        開始日
                    </label>
                    <input type="date" name="start_date" class="form-control <?= isset($errors['start_date']) ? 'is-invalid' : '' ?>" id="start_date" value="<?= h($old['start_date'] ?? $task->start_date) ?>">

                    <?php if (isset($errors['start_date'])) : ?>
                        <div class="invalid-feedback">
                            <?php foreach ($errors['start_date'] as $message) : ?>
                                <div><?= $message ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="expired_date" class="form-label">
                        期限日
                    </label>
                    <input type="date" name="expired_date" class="form-control <?= isset($errors['expired_date']) ? 'is-invalid' : '' ?>" id="expired_date" value="<?= h($old['expired_date'] ?? $task->expired_date) ?>">

                    <?php if (isset($errors['expired_date'])) : ?>
                        <div class="invalid-feedback">
                            <?php foreach ($errors['expired_date'] as $message) : ?>
                                <div><?= $message ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="complete_date" class="form-label">
                        完了日
                    </label>
                    <input type="date" name="complete_date" class="form-control <?= isset($errors['complete_date']) ? 'is-invalid' : '' ?>" id="complete_date" value="<?= h($old['complete_date'] ?? $task->complete_date) ?>">

                    <?php if (isset($errors['complete_date'])) : ?>
                        <div class="invalid-feedback">
                            <?php foreach ($errors['complete_date'] as $message) : ?>
                                <div><?= $message ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="expectation_time" class="form-label">
                        予定時間
                    </label>
                    <div class="input-group mb-3">
                        <input type="number" step="0.25" min="0" name="expectation_time" class="form-control <?= isset($errors['expectation_time']) ? 'is-invalid' : '' ?>" id="expectation_time" value="<?= h($old['expectation_time'] ?? $task->expectation_time) ?>">
                        <span class="input-group-text">時間</span>
                    </div>
                    <?php if (isset($errors['expectation_time'])) : ?>
                        <div class="invalid-feedback">
                            <?php foreach ($errors['expectation_time'] as $message) : ?>
                                <div><?= $message ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="actual_time" class="form-label">
                        実績時間
                    </label>
                    <div class="input-group mb-3">
                        <input type="number" step="0.25" min="0" name="actual_time" class="form-control <?= isset($errors['actual_time']) ? 'is-invalid' : '' ?>" id="actual_time" value="<?= h($old['actual_time'] ?? $task->actual_time) ?>">
                        <span class="input-group-text">時間</span>
                    </div>
                    <?php if (isset($errors['actual_time'])) : ?>
                        <div class="invalid-feedback">
                            <?php foreach ($errors['actual_time'] as $message) : ?>
                                <div><?= $message ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex">
                    <a href="<?= Request::getParam('ref') ?: '/task/?project_id=' . $task->project_id ?>" class="btn btn-secondary">戻る</a>
                    <button type="submit" class="btn btn-primary" onclick="return checkExpired()">登録</button>
                </div>

            </form>
        </div>
    </div>

    <?php include HTML_DIR . '/components/footer.php' ?>

    <script>
        /**
         * ポケモン未獲得かつ状態が完了の場合、期限日と完了日をチェック
         *
         * @return bool
         */
        function checkExpired() {
            <?php if ($task->id && $task->battle_status_code === Task::BATTLE_STATUS['BATTLE']) : ?>
                if (document['task.edit'].status_code.value == <?= Task::STATUS['COMPLETED'] ?>) {
                    if (!document['task.edit'].expired_date.value || !document['task.edit'].complete_date.value) {
                        return confirm('期限日または完了日がないため、ポケモンを獲得できません。よろしいですか？');
                    }
                }
            <?php endif; ?>
            return true;
        }
    </script>
</body>

</html>
