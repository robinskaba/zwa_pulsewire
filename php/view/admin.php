<?php

require_once "../main/database.php";
$db = new Database();
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
                <h3>Admin controls</h3>
                <hr>
                <h4>Registered users</h4>
                <ul>
                    <?php foreach($users as $user): ?>
                    <li>
                        <a href=<?= "profile.php?username=".$user->username ?> id="username-anchor"><?= htmlspecialchars($user->username, ENT_QUOTES) ?></a>
                        <div>
                            <label class="cv">Select role: 
                                <select>
                                    <?php
                                        $roles = ["user", "journalist", "administrator"];
                                        foreach($roles as $role):
                                    ?>
                                        <option 
                                            value=<?= $role ?> 
                                            class=<?= $role == $user->role ? "selected" : "" ?>
                                            <?= $role == $user->role ? "selected" : "" ?>
                                        >
                                            <?= ucfirst($role) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            

                            <a href=<?= "password_reset.php?username=".$user->username ?>>Reset password</a>
                            <a href="" class="disabled">Ban</a>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php include "../../html/sidemenu.html" ?>
    </body>
</html>