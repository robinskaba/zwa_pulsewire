<?php

// require_once "../main/validator.php";
// $validator = new Validator();

// if($validator->success()) header("Location: ../html/index.html");

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Password reset</title>
        
        <!-- <script src="../../js/field_error_handling.js" defer></script>
        <script src="../../js/login_form_validation.js" defer></script> -->

        <link rel="stylesheet" href="../../css/form.css">

        <?php include("../../html/metadata.html") ?>
    </head>
    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div class="inner-content">
                <div class="form-heading">
                    <h2 class="form-headline">Reset password for USERNAME</h2>
                </div>

                <div class="form-wrap">
                    <form action="" method="POST">
                        <span id="required-fields-hint">* marked fields are required</span>

                        <label>New password *
                            <input type="password" name="password_1" placeholder="New Password" id="password_1" value="">
                        </label>
                        <label>Password again *
                            <input type="password" name="password_2" placeholder="Password again" id="password_2" value="">
                        </label>
                        
                        <input type="submit" value="Set new password" name="submit">
                    </form>
                </div>
            </div>

            <?php include("../../html/sidemenu.html") ?>
        </main>
    </body>
</html>