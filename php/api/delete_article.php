<?php

/**
 * Soubor provede odstranění článku z databáze.
 * @author Robin Škába
 */

require_once "../main/session.php";
require_once "../main/database.php";
$db = new Database();

if(isset($_POST["id"]) && $logged_user && $logged_user->isAdmin()) {
    $article = $db->getArticle($_POST["id"]);
    $db->removeArticle($article);
    header("Location: ../view/index.php");
}

?>