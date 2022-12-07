<?php

/**
 * マイポケモン図鑑画面
 * 
 * @since 1.0.0
 */
require_once '../../bootstrap.php';
require_once ROOT_DIR . '/app/Controllers/PokemonController.php';

$ctrl = new PokemonController();
extract($ctrl->index());

$title = 'マイポケモン図鑑';
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
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($current_page === 1) : ?>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">最初</a>
                        </li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">前</a>
                        </li>
                    <?php else : ?>
                        <li class="page-item"><a class="page-link" href="/pokemon/?page=1">最初</a></li>
                        <li class="page-item"><a class="page-link" href="/pokemon/?page=<?= $current_page - 1 ?>">前</a></li>
                    <?php endif ?>
                    <?php for ($p = 1; $p <= $max_page; $p++) : ?>
                        <?php if ($p === $current_page) : ?>
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="#"><?= $p ?></a>
                            </li>
                        <?php else : ?>
                            <li class="page-item"><a class="page-link" href="/pokemon/?page=<?= $p ?>"><?= $p ?></a></li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($current_page === $max_page) : ?>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">最後</a>
                        </li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">次</a>
                        </li>
                    <?php else : ?>
                        <li class="page-item"><a class="page-link" href="/pokemon/?page=<?= $current_page + 1 ?>">次</a></li>
                        <li class="page-item"><a class="page-link" href="/pokemon/?page=<?= $max_page ?>">最後</a></li>
                    <?php endif ?>
                </ul>
            </nav>
            <div class="row w-100">
                <?php foreach ($pokemons as $pokemon) : ?>
                    <a href="show.php?id=<?= $pokemon->id ?>" class="d-block col-md-3 col-10 bg-secondary rounded text-light text-decoration-none align-self-center text-center py-3 m-3">
                        <div><?= h($pokemon->name_ja) ?></div>
                        <img src="<?= $pokemon->img_url ?>" alt="<?= h($pokemon->name_ja) ?>" width="150px">
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php include HTML_DIR . '/components/footer.php' ?>
</body>

</html>
