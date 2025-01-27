<?php 

/**
 * Tento soubor obsahuje stránku, která zobrazuje profil uživatele. 
 * Zobrazuje informace o uživateli a jeho komentáře s odkazy na příslušné články.
 * @author Robin Škába
 */

/**
 * Vyžaduje sessions.php pro získání informace o klientovi.
 */
require_once "../main/session.php";

/**
 * Vyžaduje objekt databáze pro načtení dat o žádaném uživateli.
 * @var Database $db Objekt databáze
 * @var string $username Uživatelské jméno uživatele, jehož profil se má zobrazit.
 */
require_once "../main/database.php";
$db = new Database();

/**
 * Logika načtení informací o uživateli.
 * @var User $user Uživatel, jehož profil se má zobrazit.
 * @var Comment[] $comments Komentáře uživatele.
 */
$username = NULL;
if (isset($_GET["username"])) $username = $_GET["username"];
if(!$username || !$db->userExists($username)) {
    header("Location: page_not_found.php");
} else {
    $user = $db->getUser($username);
    $comments = $db->getCommentsFromIds($user->comments);
    $comments = array_reverse($comments);
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= htmlspecialchars($username, ENT_QUOTES) ?>'s profile</title>
        <?php include("../../html/metadata.html"); ?>
        <link rel="stylesheet" href="../../css/profile.css">
        <link rel="stylesheet" href="../../css/comments.css">
    </head>
    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div>   
                <h1><?= htmlspecialchars($username, ENT_QUOTES) ?>'s profile</h1>
                <h2>Profile information</h2>
                <div>
                    <span><span>First name: </span><?= htmlspecialchars($user->first_name, ENT_QUOTES) ?></span>
                    <span><span>Family name: </span><?= htmlspecialchars($user->second_name, ENT_QUOTES) ?></span>
                    <span><span>Role: </span><?= htmlspecialchars($user->role, ENT_QUOTES) ?></span>
                </div>
                <hr>
                <h2>Posted comments</h2>
                <?php if(sizeof($comments) > 0): ?>
                    <ul>
                        <?php foreach($comments as $comment): ?>
                        <li>
                            <?php
                                $parent_article = $db->getArticle($comment->articleId);
                            ?>
                            <div>
                                <h3><a href="<?= "article.php?id=".$parent_article->id ?>"><?= htmlspecialchars($parent_article->title, ENT_QUOTES) ?></a></h3>
                                <span><?= $comment->publish_date ?></span>
                            </div>
                            <p><?= htmlspecialchars($comment->content, ENT_QUOTES) ?></p>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No comments yet</p>
                <?php endif; ?>
            </div>

            <?php include("templates/side_menu.php"); ?>          
        </main>
    </body>
</html>