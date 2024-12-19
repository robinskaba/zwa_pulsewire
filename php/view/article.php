<?php

/**
 * Tento soubor obsahuje logiku a strukturu stránky zobrazující článek a komentáře k němu.
 * Umožňuje také i práci s komentáři - jejich přidání, editaci a smazání.
 * @author Robin Škába
 */

/**
 * Vyžaduje session.php pro získání informací o přihlášeném uživateli.
 */
require_once "../main/session.php";

/**
 * Vyžaduje databází pro práci s články a komentáři.
 * @var Database $db Objekt databáze
 */
require_once "../main/database.php";
$db = new Database();

/**
 * @var string $articleId ID článku, který se má zobrazit - nastaven v GET dotaz.
 */
$articleId = NULL;
if(isset($_GET["id"])) $articleId = $_GET["id"];

/**
 * Vyžaduje objekt validátoru z validator.php pro validaci formulářových polí.
 * @var Validator $validator Objekt validátoru
 */
require_once "../main/validator.php";
$validator = new Validator();
$comment_body = $validator->getFromPOST("comment-body");

/**
 * Logika vytvoření komentáře.
 */
if(isset($_POST["post-comment"]) && $logged_user) {
    $validator->checkEmpty("comment-body", "Comment");
    if($validator->success()) {
        $db->addComment($logged_user->username, $articleId, $comment_body);
        header("Location: article.php?id=".$articleId);
    }
}

/**
 * Logika smazání komentáře.
 */
if($logged_user && isset($_POST["delete-comment"])) {
    $comment_id = $_POST["comment-id"];
    $comment = $db->getCommentById($comment_id);
    $is_author = $comment->author == $logged_user->username;
    if($is_author || $logged_user->isAdmin()) {
        $db->removeComment($comment);
        header("Location: article.php?id=".$articleId);
    }
}

/**
 * Logika editace komentáře.
 */
if($logged_user && isset($_POST["edit-comment"])) {
    // redirectnout kdyby nebyly zadane potrebne informace
    if(!isset($_POST["comment-id"]) || !isset($_POST["comment-body"])) header("Location: page_not_found.php");
    else {
        $comment = $db->getCommentById($_POST["comment-id"]);
        // editnout jen pokud autor komentare s danym ID se rovna prihlasenemu uzivateli
        if($comment->author == $logged_user->username) {
            $db->editComment($_POST["comment-id"], $_POST["comment-body"]);
            header("Location: article.php?id=".$articleId);
        } else {
            header("Location: page_not_found.php");
        }
    }
}

/**
 * Načtení informací o žádaném článku. V případě, že neexistuje, je uživatel přesměrován na stránku 404.
 */
if(!$articleId || !$db->articleExists($articleId)) {
    header("Location: page_not_found.php");
} else {
    $article = $db->getArticle($articleId);
    $comments = $db->getCommentsFromIds($article->comments);
    $comments = array_reverse($comments);
}

?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title>PulseWire - <?= htmlspecialchars($article->title, ENT_QUOTES) ?></title>

        <?php include("../../html/metadata.html") ?>

        <script src="../../js/comments.js" defer></script>

        <link rel="stylesheet" href="../../css/article.css">
        <link rel="stylesheet" href="../../css/comments.css">
    </head>

    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div>
                <article>
                    <img
                        src="<?= "../../database/images/large/".$article->image_path ?>" 
                        alt="Header image for the article"
                    >
                    <h1>
                        <?= htmlspecialchars($article->title, ENT_QUOTES); ?>
                    </h1>
                    <div>
                        <span>Published on <?= $article->publish_date ?></span>
                        <a href="<?= "search_results.php?category=".$article->category ?>"><?= $article->category ?></a>
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
                    <h2>Comments</h2>
                    
                    <?php if($logged_user): ?>
                        <h3>Write a comment</h3>
                        <form action="<?= "article.php?id=".$articleId ?>" method="POST" id="new-comment-form">
                            <span class="hidden">You can not post an empty comment!</span>
                            <div>
                                <textarea name="comment-body" placeholder="A comment about the article..." rows="2"></textarea>
                                <input type="submit" name="post-comment" value="Post comment">
                            </div>
                        </form>
                    <?php endif; ?>

                    <?php if(sizeof($comments) > 0): ?>
                        <ul>
                            <?php foreach($comments as $comment): ?>
                            <li>
                                <div>
                                    <?php $author = $db->getUser($comment->author); ?>
                                    <div>
                                        <h3>
                                            <a href="profile.php?username=<?= htmlspecialchars($author->username, ENT_QUOTES) ?>">
                                                <?= htmlspecialchars($author->first_name." ".$author->second_name, ENT_QUOTES); ?>
                                            </a>
                                        </h3>
                                        <span><?= $comment->publish_date ?></span>
                                    </div>
                                    
                                    <?php if($logged_user && ($logged_user->username == $comment->author || $logged_user->isAdmin())): ?>
                                        <form action="<?= "article.php?id=".$articleId ?>" method="POST" class="comment-actions">
                                            <input type="text" name="comment-id" hidden value="<?= $comment->id ?>">
                                            <?php if($logged_user->username == $comment->author): ?>
                                                <a class="edit-button" id=<?= $comment->id ?>>Edit</a>
                                            <?php endif; ?>
                                            <input type="submit" value="Delete" name="delete-comment">
                                        </form>
                                    <?php endif; ?>
                                </div>
                                
                                <p><?= htmlspecialchars($comment->content, ENT_QUOTES) ?></p>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No comments.</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php include("templates/side_menu.php") ?>
        </main>
    </body>
</html>