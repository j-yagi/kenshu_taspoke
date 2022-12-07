<?php

/**
 * ダッシュボード画面
 * 
 * @since 1.0.0
 */
require_once '../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/DashboardController.php';

$ctrl = new DashboardController();
$data = $ctrl->index();

$title = 'ダッシュボード';
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
            メインコンテンツ
        </div>
    </div>

    <?php include HTML_DIR . '/components/footer.php' ?>
</body>

</html>
