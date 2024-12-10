<?php

session_start();

require_once "../main/database.php";
$db = new Database();

$articleId = NULL;
if(isset($_GET["id"])) $articleId = $_GET["id"];

require_once "../main/validator.php";
$validator = new Validator();
$comment_body = $validator->getFromPOST("comment-body");

$validator->checkEmpty("comment-body", "Comment");

if($validator->success() && isset($_SESSION["username"])) {
    $db->addComment($_SESSION["username"], $articleId, $comment_body);
}

if(!$articleId || !$db->articleExists($articleId)) {
    header("Location: page_not_found.php");
} else {
    $article = $db->getArticle($articleId);
    $comments = $db->getCommentsFromIds($article->comments);
}

?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title>PulseWire - <?= htmlspecialchars($article->title, ENT_QUOTES) ?></title>

        <script src="../../js/comments.js" defer></script>

        <link rel="stylesheet" href="../../css/article.css">
        <link rel="stylesheet" href="../../css/comments.css">

        <?php include("../../html/metadata.html") ?>
    </head>

    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div class="inner-content">
                <article>
                    <img
                        src=<?= "../../database/images/large/".$article->image_path ?> 
                        alt="popis titulniho obrazku"
                    >
                    <h2>
                        <?= htmlspecialchars($article->title, ENT_QUOTES); ?>
                    </h2>
                    <div class="so">
                        <span class="date">Published at <?= $article->publish_date ?></span>
                        <a class="category"><?= htmlspecialchars($article->category, ENT_QUOTES) ?></a>
                    </div>
                    <p id="summary"><?= htmlspecialchars($article->summary, ENT_QUOTES) ?></p>
                    <p><?= htmlspecialchars($article->body, ENT_QUOTES) ?></p>    
                    <?php
                        if(isset($_SESSION["username"]) && $db->getUser($_SESSION["username"])->isAdmin()):
                    ?>
                        <form action="../api/delete_article.php" method="POST">
                            <input type="text" name="id" value=<?= $articleId ?> hidden>
                            <input type="submit" value="Delete article">
                        </form>
                    <?php endif; ?>
                </article>

                <hr>
                
                <div>
                    <?php if($logged_user): ?>
                        <h4>Write a comment</h4>
                        <form action=<?= "article.php?id=".$articleId ?> method="POST" id="new-comment-form">
                            <span class="hidden">You can not post an empty comment!</span>
                            <div>
                                <textarea name="comment-body" id="comment-body" placeholder="A comment about the article..." rows="2"></textarea>
                                <input type="submit" name="submit" value="Post comment">
                            </div>
                        </form>
                    <?php endif; ?>

                    <h4>Comments</h4>

                    <?php if(sizeof($comments) > 0): ?>
                        <ul>
                            <?php foreach($comments as $comment): ?>
                            <li>
                                <div class="so cv">
                                    <?php $author = $db->getUser($comment->author); ?>
                                    <h6>
                                        <a href="profile.php?username=<?= htmlspecialchars($author->username, ENT_QUOTES) ?>">
                                            <?= htmlspecialchars($author->first_name." ".$author->second_name, ENT_QUOTES); ?>
                                        </a>
                                    </h6>
                                    <div class="comment-buttons">
                                        <img src="../../src/delete_16x16.png" alt="delete comment button" class="delete-button">
                                        <img src="../../src/edit_16x16.png" alt="edit comment button" class="edit-button">
                                    </div> 
                                </div>
                                <span><?= $comment->publish_date ?></span>
                                <p><?= htmlspecialchars($comment->content, ENT_QUOTES) ?></p>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No comments.</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php include("../../html/sidemenu.html") ?>
        </main>
    </body>
</html>