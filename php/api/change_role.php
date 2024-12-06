<?php

if(isset($_POST["username"]) && isset($_POST["role"])) {
    $username = $_POST["username"];
    $role = strtolower($_POST["role"]);

    require_once "../main/database.php";
    $db = new Database();
    $db->changeUserRole($username, $role);
    echo "success";
} else echo "error";

?>