<?php

/**
 * Tento soubor obsahuje domácí stránku webové aplikace.
 * Zobrazuje seznam nejnovějších článků a nabídku kategorií.
 * @author Robin Škába
 */

/**
 * @var int $ARTICLES_PER_PAGE Počet článků na jedné stránce.
 */
$ARTICLES_PER_PAGE = 6;

/**
 * Vyžaduje databází pro načtení článků.
 * @var Database $db Objekt databáze.
 * @var int $amount_of_pages Počet stránek, které lze vytvořit z článků v databázi po skupinách po $ARTICLES_PER_PAGE.
 */
require_once "../main/database.php";
$db = new Database();
$amount_of_pages = $db->getMaxGroupsOfArticles($ARTICLES_PER_PAGE);

/**
 * Logika pro získání skupiny článků pro aktuální stránku.
 * @var int $current_page Číslo aktuální stránky.
 */
$current_page = 1;
if(isset($_GET["page"])) {
    try {
        $current_page = (int) $_GET["page"];
    } catch (Exception $e) {
        header("Location: page_not_found.php");
    }
}
if($current_page > $amount_of_pages) header("Location: page_not_found.php");
$articles = $db->getGroupOfArticles($current_page, $ARTICLES_PER_PAGE);

?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title>PulseWire</title>
        <?php include "../../html/metadata.html" ?>
        <link rel="stylesheet" href="../../css/home_page.css">
    </head>
    <body>
        <?php include "templates/header.php" ?>
    
        <main>
            <div>
                <h1>Welcome to PulseWire</h1>
                <ul>
                    <?php foreach($articles as $article): ?>
                        <li>
                            <img src="<?= "../../database/images/medium/".$article->image_path ?>" alt="<?= "Header image for article ".$article->title ?>">
                            <div>
                                <span><?= $article->publish_date ?></span>
                                <span><a href="<?= "search_results.php?category=".$article->category ?>"><?= $article->category ?></a></span>
                            </div>
                            <h2>
                                <a 
                                    href="<?= "article.php?id=".$article->id ?>"
                                >
                                    <?= htmlspecialchars($article->title, true) ?>
                                </a>
                            </h2>
                            <p><?= htmlspecialchars($article->summary, true) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div id="page-links">
                    <?php 
                        $minLimit = ($current_page - 2 > 1) ? $current_page - 2 : 1;
                        $maxLimit = ($current_page + 2 < $amount_of_pages) ? $current_page + 2 : $amount_of_pages;

                        if($current_page - 1 > 0): 
                    ?>
                        <a href="<?= "index.php?page=".(string) $current_page - 1 ?>">&lt;</a>
                    <?php endif; ?>

                    <?php if($minLimit > 1): ?>
                        <span>...</span>
                    <?php endif; ?>

                    <?php
                        
                        for($i = $minLimit; $i <= $maxLimit; $i++): 
                    ?>
                        <a href="<?= 'index.php?page='.(string) $i ?>" <?= $i == $current_page ? 'class=selected' : '' ?>><?= $i ?></a>
                    <?php endfor; ?>
                    <?php if($maxLimit < $amount_of_pages): ?>
                        <span>...</span>
                    <?php endif; ?>
                    <?php if($current_page + 1 <= $amount_of_pages): ?>
                        <a href="<?= "index.php?page=".(string) $current_page + 1 ?>">&gt;</a>
                    <?php endif; ?>
                </div>
            </div>

            <?php include "templates/side_menu.php" ?>
        </main>
    </body>
</html>