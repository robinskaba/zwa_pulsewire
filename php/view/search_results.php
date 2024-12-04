<?php

session_start();

$category = NULL;
if(isset($_GET["category"])) $category = $_GET["category"];
if(!$category) header("Location: page_not_found.php");

// redirect if category not in variable declared in categories.php
require_once "../main/categories.php";
$category = ucfirst($category);
if(!in_array($category, $CATEGORIES)) header("Location: page_not_found.php");

require_once "../main/database.php";
$db = new Database();
$category = ucfirst($category);
$articles = $db->getArticlesOfCategory($category);

?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title>Articles: <?= htmlspecialchars($category, ENT_QUOTES) ?></title>

        <link rel="stylesheet" href="../../css/search_results.css">

        <?php include "../../html/metadata.html" ?>
    </head>
    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div class="inner-content">
                <h3><?= htmlspecialchars($category, ENT_QUOTES) ?> - articles</h3>
                <hr>
                <?php foreach($articles as $article): ?>
                <a href=<?= "article.php?id=".$article->id ?>>
                    <img 
                        src=<?= "../api/resize_image.php?img=".urlencode($article->image_path)."&width=150&height=75" ?>
                        alt="article name header image"
                    >
                    <div>
                        <div>
                            <h6><?= htmlspecialchars($article->title, ENT_QUOTES) ?></h6>
                            <span><?= htmlspecialchars($article->publish_date, ENT_QUOTES) ?></span>
                        </div>
                        <p><?= htmlspecialchars($article->summary, ENT_QUOTES) ?></p>
                    </div>
                </a>
                <hr>
                <?php endforeach; ?>
            </div>

            <?php include "../../html/sidemenu.html" ?>
    </body>
</html>