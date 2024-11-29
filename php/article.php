<?php

require_once "database.php";
$db = new Database();

$articleId = NULL;
if(isset($_GET["id"])) $articleId = $_GET["id"];
if(!$articleId || !$db->articleExists($articleId)) {
    header("Location: page_not_found.php");
} else {
    $article = $db->getArticle($articleId);
    $comments = $db->getCommentsFromIds($article->comments);
}

require_once "validator.php";
$validator = new Validator();
$comment_body = $validator->getFromPOST("comment-body");

$validator->checkEmpty("comment-body", "Comment");

if($validator->success()) {
    $db->addComment("TODO-USERNAME", $articleId, $comment_body);
}

?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title>PulseWire - <?= htmlspecialchars($article->title, ENT_QUOTES) ?></title>

        <script src="../js/comments.js" defer></script>

        <link rel="stylesheet" href="../css/article.css">
        <link rel="stylesheet" href="../css/comments.css">

        <?php include("../html/metadata.html") ?>
    </head>

    <body>
        <?php include "header.php" ?>

        <main>
            <div class="inner-content">
                <div class="article">
                    <div>
                        <img
                            src=<?= "resize_image.php?img=".urlencode($article->image_path)."&width=400&height=200" ?> 
                            alt="popis titulniho obrazku"
                        >
                        <div class="article-about">
                            <div class="so cv">
                                <h2>
                                    <?= htmlspecialchars($article->title, ENT_QUOTES); ?>
                                </h2>
                                <a href="">
                                    <img src="../src/delete_24x24.png" alt="delete comment button">
                                </a>
                            </div>
                            
                            <div class="so">
                                <span class="date">Published at <?= $article->publish_date ?></span>
                                <a class="category"><?= htmlspecialchars($article->category, ENT_QUOTES) ?></a>
                            </div>
                            <p><?= htmlspecialchars($article->summary, ENT_QUOTES) ?><p>
                        </div>
                    </div>
                    <p><?= htmlspecialchars($article->body, ENT_QUOTES) ?></p>    
                </div>

                <hr>
                
                <div>
                    <h4>Write a comment</h4>
                    <form action="article.php" method="POST" id="new-comment-form">
                        <span class="hidden">You can not post an empty comment!</span>
                        <div>
                            <textarea name="comment-body" id="comment-body" placeholder="A comment about the article..." rows="2"></textarea>
                            <input type="submit" name="submit" value="Edit comment" class="submit-button db">
                        </div>
                    </form>

                    <h4>Comments</h4>

                    <div class="comment-list">
                        <?php foreach($comments as $comment): ?>
                        <div class="comment">
                            <div class="so cv">
                                <?php $author = $db->getUser($comment->author); ?>
                                <h6>
                                    <a href="profile.php?username=<?= htmlspecialchars($author->username, ENT_QUOTES) ?>">
                                        <?= htmlspecialchars($author->first_name." ".$author->second_name, ENT_QUOTES); ?>
                                    </a>
                                </h6>
                                <div class="comment-buttons">
                                    <img src="../src/delete_16x16.png" alt="delete comment button" class="delete-button">
                                    <img src="../src/edit_16x16.png" alt="edit comment button" class="edit-button">
                                </div> 
                            </div>
                            <span><?= $comment->publish_date ?></span>
                            <p><?= htmlspecialchars($comment->content, ENT_QUOTES) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php include("../html/sidemenu.html") ?>
        </main>
    </body>
</html>