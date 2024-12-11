<?php

session_start();
$logged_user = NULL;

if(isset($_SESSION["username"])) {
    require_once "database.php";
    $db = new Database();
    $logged_user = $db->getUser($_SESSION["username"]);
}

?>