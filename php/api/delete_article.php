<?php

require_once "../main/database.php";
$db = new Database();

if(isset($_POST["id"])) {
    $db->removeArticle($_POST["id"]);
    header("Location: ../view/index.php");
}

?>