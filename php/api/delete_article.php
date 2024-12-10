<?php

require_once "../main/database.php";
$db = new Database();

if(isset($_POST["id"])) {
    $article = $db->getArticle($_POST["id"]);
    $db->removeArticle($article);
    header("Location: ../view/index.php");
}

?>