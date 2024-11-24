<?php

require_once "database.php";
$db = new Database();

$q_username = NULL;
if(isset($_GET["username"])) $q_username = $_GET["username"];
if(!$q_username || $db->userExists($q_username)) echo "true";
else echo "false";

?>