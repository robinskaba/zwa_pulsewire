<?php

session_start();

?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title>Page not found</title>
        <?php include("../../html/metadata.html") ?>
    </head>
    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div class="inner-content">
                <h3>404 - Page not found</h3>
                <span>An error has occurred. Please return to the <a href="index.php">homepage</a>.</span>
            </div>

            <?php include("../../html/sidemenu.html") ?>          
        </main>
    </body>
</html>