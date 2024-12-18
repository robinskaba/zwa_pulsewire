<?php

/**
 * Endpoint pro admin.js, který při POST requestu změní roli uživatele.
 * @author Robin Škába
 */

require_once "../main/session.php";

if(isset($_POST["username"]) && isset($_POST["role"]) && $logged_user && $logged_user->isAdmin()) {
    $username = $_POST["username"];
    $role = strtolower($_POST["role"]);

    require_once "../main/database.php";
    $db = new Database();
    $db->changeUserRole($username, $role);
    echo "success";
} else echo "error";

?>