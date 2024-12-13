<?php

require_once __DIR__."/../../main/categories.php";
require_once __DIR__."/../../main/session.php";

?>

<div class="side-menu">

    <?php if(!$logged_user): ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php else: ?>
        <?php 
            if($logged_user->isAdmin() || $logged_user -> isWriter()):
        ?>
            <a href="write.php">Write</a>
            <?php if($logged_user->isAdmin()): ?>
                <a href="admin.php">Admin</a>
            <?php endif; ?>
        <?php endif; ?>
        <a href=<?= "profile.php?username=".$logged_user->username ?>>Profile</a>
        <a href=<?= "../api/log_out.php" ?>>Log out</a>
    <?php endif; ?>    

    <hr>

    <?php foreach($CATEGORIES as $_category): ?>
        <a class="category" href=<?= "search_results.php?category=".$_category ?>><?= $_category ?></a>
    <?php endforeach; ?>
</div>