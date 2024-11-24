<?php

require_once "database.php";
$db = new Database();

$articleId = NULL;
if(isset($_GET["id"])) $articleId = $_GET["id"];
if(!$articleId || !$db->articleExists($articleId)) {
    header("Location: page_not_found.php");
} else {
    $article = $db->getArticle($article);
}

?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title>PulseWire - <?= htmlspecialchars($article->title) ?></title>

        <script src="../js/comments.js" defer></script>

        <link rel="stylesheet" href="../css/article.css">
        <link rel="stylesheet" href="../css/comments.css">

        <?php include("../html/metadata.html") ?>
    </head>

    <body>
        <?php include("../html/header.html") ?>

        <main>
            <div class="inner-content">
                <div class="article">
                    <div>
                        <img
                            src="../src/template_news_image.png" 
                            alt="popis titulniho obrazku"
                        >
                        <div class="article-about">
                            <div class="so cv">
                                <h2>
                                    <?= htmlspecialchars($article->title, true); ?>
                                </h2>
                                <a href="">
                                    <img src="../src/delete_24x24.png" alt="delete comment button">
                                </a>
                            </div>
                            
                            <div class="so">
                                <span class="date">Published at <?= $article->publish_date ?></span>
                                <a class="category"><?= $article->category ?></a>
                            </div>
                            <p><?= htmlspecialchars($article->summary, true) ?><p>
                        </div>
                    </div>
                    <p><?= htmlspecialchars($article->body, true) ?></p>    
                </div>

                <hr>
                
                <div>
                    <h4>Write a comment</h4>
                    <form action="" method="POST" id="new-comment-form">
                        <span class="hidden">You can not post an empty comment!</span>
                        <div>
                            <textarea name="comment-body" id="comment-body" placeholder="A comment about the article..." rows="2"></textarea>
                            <input type="submit" value="Edit comment" class="submit-button db">
                        </div>
                    </form>

                    <h4>Comments</h4>

                    <div class="comment-list">
                        <div class="comment">
                            <div class="so cv">
                                <h6>Username</h6>
                                <div class="comment-buttons">
                                    <img src="../src/delete_16x16.png" alt="delete comment button" class="delete-button">
                                    <img src="../src/edit_16x16.png" alt="edit comment button" class="edit-button">
                                </div> 
                            </div>
                            <span>Datum publikování</span>
                            <p>
                                Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře 
                                Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře 
                                Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře 
                                Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře 
                                Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře 
                            </p>
                        </div>
                        <div class="comment">
                            <div class="so cv">
                                <h6>Username</h6>
                                <div class="comment-buttons">
                                    <img src="../src/delete_16x16.png" alt="delete comment button" class="delete-button">
                                    <img src="../src/edit_16x16.png" alt="edit comment button" class="edit-button">
                                </div> 
                            </div>
                            <span>Datum publikování</span>
                            <p>
                                Komentar o clanku. Velmi užitečný. řčěáýá+šě+éíáščé.
                            </p>
                        </div>
                        <div class="comment">
                            <div class="so cv">
                                <h6>Username</h6>
                                <div class="comment-buttons">
                                    <img src="../src/delete_16x16.png" alt="delete comment button" class="delete-button">
                                    <img src="../src/edit_16x16.png" alt="edit comment button" class="edit-button">
                                </div> 
                            </div>
                            <span>Datum publikování</span>
                            <p>
                                Lorem Ipsum Haha Joe biden
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <?php include("../html/sidemenu.html") ?>
        </main>
    </body>
</html>