<?php

require_once "../main/session.php";

$ARTICLES_PER_PAGE = 6;

require_once "../main/database.php";
$db = new Database();
$amount_of_pages = $db->getMaxGroupsOfArticles($ARTICLES_PER_PAGE);

$current_page = 1;
if(isset($_GET["page"])) {
    try {
        $current_page = (int) $_GET["page"];
    } catch (Exception $e) {
        header("Location: page_not_found.php");
    }
}

$articles = $db->getGroupOfArticles($current_page, $ARTICLES_PER_PAGE);

?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title>PulseWire</title>
        <link rel="stylesheet" href="../../css/home_page.css">
        <?php include "../../html/metadata.html" ?>
    </head>
    <body>
        <?php include "templates/header.php" ?>
    
        <main>
            <div class="inner-content">
                <ul>
                    <?php foreach($articles as $article): ?>
                        <li>
                            <img src=<?= "../../database/images/medium/".$article->image_path ?> alt="article header image">
                            <div class="article-metadata">
                                <span class="date"><?= $article->publish_date ?></span>
                                <span class="category"><a href=<?= "search_results.php?category=".$article->category ?>><?= $article->category ?></a></span>
                            </div>
                            <h5>
                                <a 
                                    href=<?= "article.php?id=".$article->id ?>
                                >
                                    <?= htmlspecialchars($article->title, true) ?>
                                </a>
                            </h5>
                            <p><?= htmlspecialchars($article->summary, true) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="page-links">
                    <?php if($current_page - 1 > 0): ?>
                        <a href=<?= "index.php?page=".(string) $current_page - 1 ?>>&lt;</a>
                    <?php endif; ?>

                    <?php if($amount_of_pages > 7): ?>
                        <span>...</span>
                    <?php endif; ?>

                    <?php
                        $minLimit = ($current_page - 2 > 1) ? $current_page - 2 : 1;
                        $maxLimit = ($current_page + 2 < $amount_of_pages) ? $current_page + 2 : $amount_of_pages;
                        for($i = $minLimit; $i <= $maxLimit; $i++): 
                    ?>
                        <a href=<?= "index.php?page=".(string) $i ?> <?= $i == $current_page ? "class=selected" : "" ?>><?= $i ?></a>
                    <?php endfor; ?>
                    <?php if($amount_of_pages > 7): ?>
                        <span>...</span>
                    <?php endif; ?>
                    <?php if($current_page + 1 <= $amount_of_pages): ?>
                        <a href=<?= "index.php?page=".(string) $current_page + 1 ?>>&gt;</a>
                    <?php endif; ?>
                </div>
            </div>

            <?php include "templates/side_menu.php" ?>
        </main>
    </body>
</html>