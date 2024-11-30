<?php

require_once "categories.php";

?>

<header>
    <nav id="upper-nav">
        <div>
            <a href="index.html">
                <img src="../src/logo_64x64.png" alt="pulsewire logo" id="page-logo">
            </a>
            <a class="page-title" href="index.php">PulseWire</a>
        </div>
        <div class="account-actions">
            <a class="account-action" href="admin.php">Admin</a>
            <a class="account-action" href="write.php">Write</a>
            <a class="account-action" href="profile.php">Profile</a>
            <a class="account-action" href="login.php">Login</a>
            <a class="account-action" href="login.php">Log out</a>
            <a class="account-action" href="register.php">Register</a>
        </div>
        <img src="../src/menu-icon.png" alt="open category menu" id="category-menu">
    </nav>
    <nav id="lower-nav">
        <?php foreach($categories as $_category): ?>
            <a class="category" href=<?= "search_results.php?category=".$_category ?>><?= $_category ?></a>
        <?php endforeach; ?>
    </nav>
</header>