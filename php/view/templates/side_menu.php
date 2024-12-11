<?php

require_once __DIR__."/../../main/categories.php";
require_once __DIR__."/../../main/session.php";

?>

<div class="side-menu">

    <?php if(!$logged_user): ?>
        <a class="account-action" href="login.php">Login</a>
        <a class="account-action" href="register.php">Register</a>
    <?php else: ?>
        <?php 
            if($logged_user->isAdmin() || $logged_user -> isWriter()):
        ?>
            <a class="account-action" href="write.php">Write</a>
            <?php if($logged_user->isAdmin()): ?>
                <a class="account-action" href="admin.php">Admin</a>
            <?php endif; ?>
        <?php endif; ?>
        <a class="account-action" href=<?= "profile.php?username=".$logged_user->username ?>>Profile</a>
        <a class="account-action" href=<?= "../api/log_out.php" ?>>Log out</a>
    <?php endif; ?>    

    <hr>

    <?php foreach($CATEGORIES as $_category): ?>
        <a class="category" href=<?= "search_results.php?category=".$_category ?>><?= $_category ?></a>
    <?php endforeach; ?>
</div>