<?php

require_once "../main/session.php";

if(!$logged_user || !$logged_user->isAdmin()) header("Location: page_not_found.php");

$P1_KEY = "password_1";
$P2_KEY = "password_2";

$username = "";
if(isset($_GET["username"])) {
    $username = $_GET["username"];
} else {
    header("Location: page_not_found.php");
}

require_once "../main/validator.php";
$validator = new Validator();

$password_1 = $validator->getFromPOST($P1_KEY);

$validator->checkLength(8, $P1_KEY, "Password");
$validator->checkContainsNumber($P1_KEY, "Password");
$validator->checkMatch($password_1, $P2_KEY, "Passwords");

if($validator->success()) {
    require_once "../main/database.php";
    $db = new Database();
    $db->changePassword($username, $password_1);
    header("Location: admin.php");
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Password reset</title>
        
        <!-- <script src="../../js/error_handler.js" defer></script>
        <script src="../../js/password_reset_validation.js" defer></script> -->

        <link rel="stylesheet" href="../../css/form.css">

        <?php include("../../html/metadata.html") ?>
    </head>
    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div class="inner-content">
                <div class="form-heading">
                    <h2 class="form-headline">Reset password for <?= htmlspecialchars($username, true) ?></h2>
                </div>

                <div class="form-wrap">
                    <form action="" method="POST">
                        <span id="required-fields-hint">* marked fields are required</span>

                        <label>New password *
                            <input type="password" name=<?= $P1_KEY ?> placeholder="New password" id="password_1" value="" class=<?php $validator->errorClass($P1_KEY) ?>>
                        </label>
                        <label>Password again *
                            <input type="password" name=<?= $P2_KEY ?> placeholder="Password again" id="password_2" value="" class=<?php $validator->errorClass($P2_KEY) ?>>
                        </label>
                        <?= $validator->displayErrors() ?>
                        
                        <input type="submit" value="Set new password" name="submit">
                    </form>
                </div>
            </div>

            <?php include("templates/side_menu.php") ?>
        </main>
    </body>
</html>