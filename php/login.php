<?php

// TODO musim to vubec validovat?? vzdyt to je login
require_once "validator.php";
$validator = new Validator();

$username = $validator->getFromPOST("username");
$password = $validator->getFromPOST("password");

$validator->checkLength(4, "username", "Username");
$validator->checkIllegalChars("username", "Username");
$validator->checkLength(8, "password", "Password");
$validator->checkContainsNumber("password", "Password");

if($validator->success()) header("Location: ../html/index.html");

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Log in to PulseWire</title>
        
        <script src="../js/field_error_handling.js" defer></script>
        <script src="../js/login_form_validation.js" defer></script>
        

        <link rel="stylesheet" href="../css/form.css">

        <?php include("../html/metadata.html") ?>
    </head>
    <body>
        <?php include("../html/header.html") ?>

        <main>
            <div class="inner-content">
                <div class="form-heading">
                    <h2 class="form-headline">Log in to</h2>
                    <img src="../src/logo_128x128.png" alt="page logo">
                    <h1 class="page-title">PulseWire</h1>
                </div>

                <div class="form-wrap">
                    <form action="" method="POST">
                        <span id="required-fields-hint">* marked fields are required</span>

                        <label>Username *
                            <input type="text" name="username" placeholder="username" id="username" value="<?= htmlspecialchars($username) ?>">
                        </label>
                        <label>Password *
                            <input type="password" name="password" placeholder="Password" id="password_1" value="<?= htmlspecialchars($password) ?>">
                        </label>
                        <?= $validator->displayErrors() ?>
                        <input class="submitButton" type="submit" value="Log in" name="submit">
                    </form>
                </div>
                
                <span>New to PulseWire? 
                    <a href="register.html">Create an account</a>
                </span>
            </div>

            <?php include("../html/sidemenu.html") ?>
        </main>
    </body>
</html>