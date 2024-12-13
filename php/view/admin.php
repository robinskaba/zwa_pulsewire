<?php

require_once "../main/session.php";

// redirectnout, kdyby se nahodou nekdo dostal na tuhle stranku kdo neni admin nebo neni logged in
if(!$logged_user || !$logged_user->isAdmin()) {
    header("Location: page_not_found.php");
    exit;
}

require_once "../main/database.php";
$db = new Database();

if(isset($_POST["ban-user"]) && isset($_POST["username"]) && $logged_user->isAdmin()) {
    $to_ban_username = $_POST["username"];
    $db->removeUser($db->getUser($to_ban_username));
    header("Location: admin.php");
}

$users = $db->getUsers();

?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title>Administrator's page</title>

        <script src="../../js/admin.js" defer></script>

        <link rel="stylesheet" href="../../css/admin_page.css">

        <?php include "../../html/metadata.html" ?>
    </head>

    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div class="inner-content">
                <h1>Admin controls</h1>
                <hr>
                <h2>Registered users</h2>
                <ul>
                    <?php foreach($users as $user): ?>
                    <li>
                        <a href=<?= "profile.php?username=".$user->username ?> id="username-anchor"><?= htmlspecialchars($user->username, ENT_QUOTES) ?></a>
                        <div>
                            <label class="cv">Select role: 
                                <select>
                                    <?php
                                        $roles = ["user", "writer", "admin"];
                                        foreach($roles as $role):
                                    ?>
                                        <option 
                                            value=<?= $role ?> 
                                            <?= $role == $user->role ? "selected" : "" ?>
                                        >
                                            <?= ucfirst($role) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            

                            <a href=<?= "password_reset.php?username=".htmlspecialchars($user->username, ENT_QUOTES) ?>>Reset password</a>
                            <form action="admin.php" method="POST">
                                <input type="hidden" name="username" value="<?= htmlspecialchars($user->username) ?>">
                                <input type="submit" name="ban-user" value="Ban" <?= ($user->username == $logged_user->username) ? "disabled" : "" ?>>
                            </form>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php include "templates/side_menu.php" ?>
    </body>
</html>