<?php

require_once "../main/database.php";
$db = new Database();
// $articles = $db->getArticles()

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
                    <li>
                        <div class="image-frame">
                            <img src="../../src/rectangular-image.jpg" alt="article header image">
                        </div>
                        <h5><a href="article.html">News headline headline headline</a></h5>
                        <div class="article-metadata">
                            <span class="date">24.5.2004</span>
                            <span class="category"><a href="">Category</a></span>
                        </div>
                        <p>Article description</p>
                    </li>
                </ul>
                <div class="page-links">
                    <a href="">&lt;</a>
                    <a href="" class="selected">1</a>
                    <a href="">2</a>
                    <a href="">3</a>
                    <a href="">&gt;</a>
                </div>
            </div>

            <?php include "../../html/sidemenu.html" ?>
        </main>
    </body>
</html>