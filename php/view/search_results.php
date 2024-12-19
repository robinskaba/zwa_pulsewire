<?php

/**
 * Tento soubor obsahuje stránku a logiku, která zobrazuje všechny články v dané kategorii.
 * @author Robin Škába
 */

require_once "../main/session.php";

/**
 * @var string $category Název kategorie získaný z GET dotazu.
 */
$category = NULL;
if(isset($_GET["category"])) $category = $_GET["category"];
if(!$category) header("Location: page_not_found.php");

/**
 * Vyžaduje categories.php pro získání všech kategorií a případný redirect, pokud kategorie neexistuje.
 */
require_once "../main/categories.php";
$category = ucfirst($category);
if(!in_array($category, $CATEGORIES)) header("Location: page_not_found.php");

/**
 * Vyžaduje databází pro práci s články.
 * @var Database $db Objekt databáze
 * @var Article[] $articles Články v dané kategorii
 */
require_once "../main/database.php";
$db = new Database();
$category = ucfirst($category);
$articles = $db->getArticlesOfCategory($category);
$articles = array_reverse($articles);

?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title>Articles: <?= htmlspecialchars($category, ENT_QUOTES) ?></title>
        <?php include "../../html/metadata.html" ?>
        <link rel="stylesheet" href="../../css/search_results.css">
    </head>
    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div>
                <h1><?= htmlspecialchars($category, ENT_QUOTES) ?></h1>
                <hr>
                <ul>
                    <?php foreach($articles as $article): ?>
                        <li>
                            <a href="<?= "article.php?id=".$article->id ?>">
                                <img 
                                    src="<?= "../../database/images/small/".$article->image_path ?>"
                                    alt="<?= "Header image for article ".$article->title ?>"
                                >
                                <div>
                                    <div>
                                        <h2><?= htmlspecialchars($article->title, ENT_QUOTES) ?></h2>
                                        <span><?= htmlspecialchars($article->publish_date, ENT_QUOTES) ?></span>
                                    </div>
                                    <p><?= htmlspecialchars($article->summary, ENT_QUOTES) ?></p>
                                </div>
                            </a>
                            <hr>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
                <?php include "templates/side_menu.php" ?>
            </main>
    </body>
</html>