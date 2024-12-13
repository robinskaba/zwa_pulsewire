<?php

require_once "../main/session.php";

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
            <div>
                <h1>404 - Page not found</h1>
                <span>An error has occurred. Please return to the <a href="index.php">homepage</a>.</span>
            </div>

            <?php include("templates/side_menu.php") ?>          
        </main>
    </body>
</html>