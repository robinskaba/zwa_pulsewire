<?php

$category = NULL;
if(isset($_GET["category"])) $category = $_GET["category"];
if(!$category) header("Location: page_not_found.php");

// redirect if category not in variable declared in categories.php
require_once "categories.php";
$category = ucfirst($category);
if(!in_array($category, $categories)) header("Location: page_not_found.php");

require_once "database.php";
$db = new Database();
$category = ucfirst($category); // i assume categories will be saved with first letter uppercase
$articles = $db->getArticlesOfCategory($category);

?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title>Articles: <?= $category ?></title>

        <link rel="stylesheet" href="../css/search_results.css">

        <?php include "../html/metadata.html" ?>
    </head>
    <body>
        <?php include "../html/header.html" ?>

        <main>
            <div class="inner-content">
                <h3><?= $category ?> - articles</h3>
                <hr>
                <?php foreach($articles as $article): ?>
                <a href="article.html">
                    <!-- TODO display small header image -->
                    <img src="../src/search_result_small_template.jpg" alt="article name header image">
                    <div>
                        <div>
                            <h6><?= $article->title ?></h6>
                            <span><?= $article->publish_date ?></span>
                        </div>
                        <p><?= $article->summary ?></p>
                    </div>
                </a>
                <hr>
                <?php endforeach; ?>
            </div>

            <?php include "../html/sidemenu.html" ?>
    </body>
</html>