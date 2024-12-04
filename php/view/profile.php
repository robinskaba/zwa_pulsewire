<?php 

session_start();

require_once "../main/database.php";
$db = new Database();

$username = NULL;
if (isset($_GET["username"])) $username = $_GET["username"];
if(!$username || !$db->userExists($username)) {
    header("Location: page_not_found.php");
} else {
    $user = $db->getUser($username);
    $comments = $db->getCommentsFromIds($user->comments);
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- TODO htmlspecialchars for title?? -->
        <title><?= htmlspecialchars($username, ENT_QUOTES) ?>'s profile</title>
        <link rel="stylesheet" href="../../css/profile.css">
        <link rel="stylesheet" href="../../css/comments.css">

        <?php include("../../html/metadata.html"); ?>
    </head>
    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div class="inner-content">   
                <h2><?= htmlspecialchars($username, ENT_QUOTES) ?>'s profile</h2>
                <h4>Profile information</h4>
                <div class="profile-information">
                    <span><span>First name: </span><?= htmlspecialchars($user->first_name, ENT_QUOTES) ?></span>
                    <span><span>Second name: </span><?= htmlspecialchars($user->second_name, ENT_QUOTES) ?></span>
                </div>
                <hr>
                <h4>Posted comments</h4>
                <?php if(sizeof($comments) > 0): ?>
                    <ul>
                        <?php foreach($comments as $comment): ?>
                        <li>
                            <?php
                                $parent_article = $db->getArticle($comment->articleId);
                            ?>
                            <h5><a href=<?= "article.php?id=".$parent_article->id ?>><?= htmlspecialchars($parent_article->title, ENT_QUOTES) ?></a></h5>
                            <span><?= $comment->publish_date ?></span>
                            <p><?= htmlspecialchars($comment->content, ENT_QUOTES) ?></p>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No comments yet</p>
                <?php endif; ?>
            </div>

            <?php include("../../html/sidemenu.html"); ?>          
        </main>
    </body>
</html>