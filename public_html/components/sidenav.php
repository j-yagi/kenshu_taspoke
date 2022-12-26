<?php
$active = 'border-start border-3 border-primary';
$inactive = 'link-dark';

$current_uri = Request::getCurrentUri();
?>

<nav class="d-flex flex-column flex-shrink-0 p-3 min-vh-100 bg-white" style="width: 200px;" id="side_menu">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <a href="/" class="link-dark text-decoration-none" id="site_name">
            <span class="fs-4 fw-bolder fst-italic text-warning">
                <?= Config::get('app.name', 'タスポケ') ?>
            </span>
        </a>
        <i class="fa-solid fa-angle-left fs-3 text-primary" id="toggle_menu"></i>
    </div>
    <ul class="nav nav-pills flex-column mb-auto" id="side_menu_list">
        <li class="nav-item">
            <a href="/" class="nav-link <?= $current_uri === '/' || $current_uri === '/index.php' ? $active : $inactive ?>" aria-current="page">
                <i class="fa-solid fa-gauge fs-5 text-primary"></i>
                <span class="ms-2">ダッシュボード</span>
            </a>
        </li>
        <li>
            <a href="/project" class="nav-link <?= strpos($current_uri, '/project') === 0 || strpos($current_uri, '/task') === 0 ? $active : $inactive ?>">
                <i class="fa-solid fa-bars-progress fs-5 text-primary"></i>
                <span class="ms-2">プロジェクト</span>
            </a>
        </li>
        <li>
            <a href="#" class="nav-link <?= strpos($current_uri, '/note') === 0 ? $active : $inactive ?>">
                <i class="fa-solid fa-book-open fs-5 text-primary"></i>
                <span class="ms-2">ノート</span>
            </a>
        </li>
        <li>
            <a href="/pokemon" class="nav-link <?= strpos($current_uri, '/pokemon') === 0 ? $active : $inactive ?>">
                <i class="fa-solid fa-book fs-5 text-primary"></i>
                <span class="ms-2">マイポケモン図鑑</span>
            </a>
        </li>
        <li>
            <a href="/account" class="nav-link <?= strpos($current_uri, '/account') === 0 ? $active : $inactive ?>">
                <i class="fa-solid fa-book fs-5 text-primary"></i>
                <span class="ms-2">マイアカウント</span>
            </a>
        </li>
        <li>
            <form name="user.logout" method="POST" action="/user/logout.php">
                <input type="hidden" name="_token" value="<?= Request::generateCsrfToken('user.logout') ?>">
                <a href="javascript:void(0);" class="nav-link link-dark" onclick="document.forms['user.logout'].submit()">
                    <i class="fa-solid fa-right-from-bracket fs-5 text-primary"></i>
                    <span class="ms-2">ログアウト</span>
                </a>
            </form>
        </li>
    </ul>
</nav>
