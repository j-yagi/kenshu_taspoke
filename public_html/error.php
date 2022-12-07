<?php
require_once '../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/ErrorController.php';

$ctrl = new ErrorController();
$error = $ctrl->error();

$title = $error['title'];
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <?php include HTML_DIR . '/components/head.php' ?>
</head>

<body class="text-body bg-light">
    <div class="min-vh-100 d-flex justify-content-center align-items-center">
        <div class="card text-center col-12 col-lg-6">
            <div class="card-body">
                <h2 class="card-title text-danger">
                    <?= $error['title'] ?>
                </h2>
                <p class="card-text text-left p-4">
                    <?= $error['message'] ?>
                </p>
                <div class="w-100">
                    <a class="col-5 btn btn-sm btn-dark" href="<?= $error['back_url'] ?>">戻る</a>
                    <a class="col-5 btn btn-sm btn-success" href="<?= Config::get('app.url') ?>">ホーム画面</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
