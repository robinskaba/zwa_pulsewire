<?php

require_once __DIR__."/../../main/categories.php";
require_once __DIR__."/../../main/session.php";

?>

<header>
    <nav id="upper-nav">
        <div>
            <a href="index.html">
                <img src="../../src/logo_64x64.png" alt="pulsewire logo" id="page-logo">
            </a>
            <a class="page-title" href="index.php">PulseWire</a>
        </div>
        <div class="account-actions">
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
            
        </div>
        <img src="../../src/menu-icon.png" alt="open category menu" id="category-menu">
    </nav>
    <nav id="lower-nav">
        <?php foreach($CATEGORIES as $_category): ?>
            <a class="category" href=<?= "search_results.php?category=".$_category ?>><?= $_category ?></a>
        <?php endforeach; ?>
    </nav>
</header>