<?php

require_once "../main/database.php";
$db = new Database();

if(isset($_GET["id"])) {
    $db->removeArticle($_GET["id"]);
    header("Location: ../view/index.php");
    echo "1";
} else echo "0";

?>