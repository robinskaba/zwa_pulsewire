<?php

/**
 * Soubor obsahuje hlavičku stránky, která se zobrazuje na každé stránce.
 * V závislosti na informace o klientovi získaného z session.php se mu zobrazí různé možnosti.
 * @author Robin Škába
 */

require_once __DIR__."/../../main/categories.php";

?>

<header>
    <nav id="upper-nav">
        <div>
            <a href="../view/index.php">
                <img src="../../src/logo_64x64.png" alt="PulseWire logo" id="page-logo">
            </a>
            <a class="page-title" href="index.php">PulseWire</a>
        </div>
        <div class="account-actions">
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
                <a href="<?= "profile.php?username=".htmlspecialchars($logged_user->username) ?>">Profile</a>
                <a href=<?= "../api/log_out.php" ?>>Log out</a>
            <?php endif; ?>
            
        </div>
        <img src="../../src/menu-icon.png" alt="Button for opening side menu" id="category-menu">
    </nav>
    <nav id="lower-nav">
        <?php foreach($CATEGORIES as $_category): ?>
            <a class="category" href="<?= "search_results.php?category=".$_category ?>"><?= $_category ?></a>
        <?php endforeach; ?>
    </nav>
</header>