<?php

require_once __DIR__."/../../main/categories.php";

$logged = isset($_SESSION["username"]);

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
            <?php if(!$logged): ?>
                <a class="account-action" href="login.php">Login</a>
                <a class="account-action" href="register.php">Register</a>
            <?php else: ?>
                <?php 
                    require_once __DIR__."/../../main/database.php";
                    $db = new Database();
                    $user = $db->getUser($_SESSION["username"]);
                    if($user->isAdmin() || $user -> isWriter()):
                ?>
                    <a class="account-action" href="write.php">Write</a>
                    <?php if($user->isAdmin()): ?>
                        <a class="account-action" href="admin.php">Admin</a>
                    <?php endif; ?>
                <?php endif; ?>
                <a class="account-action" href=<?= "profile.php?username=".$_SESSION["username"] ?>>Profile</a>
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