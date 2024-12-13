<?php

require_once "../main/session.php";

if($logged_user) header("Location: index.php");

require_once "../main/validator.php";
$validator = new Validator();

$username = $validator->getFromPOST("username");
$password = $validator->getFromPOST("password");

$validator->checkEmpty("username", "Username");
$validator->checkEmpty("password", "Password");

if($validator->success()) {
    require_once "../main/database.php";
    $db = new Database();
    $user = $db->getUser($username);
    if(!$user) {
        $validator->addError("username", "Incorrect username");
    } elseif(!password_verify($password, $user->password)) {
        $validator->addError("password", "Incorrect password");
    }
}

if($validator->success()) {
    $_SESSION["username"] = $username;
    header("Location: index.php");
} 

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Log in to PulseWire</title>
        
        <?php include("../../html/metadata.html") ?>

        <script src="../../js/error_handler.js" defer></script>
        <script src="../../js/login_form_validation.js" defer></script>

        <link rel="stylesheet" href="../../css/form.css">
    </head>
    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div>
                <div>
                    <h2>Log in to</h2>
                    <img src="../../src/logo_128x128.png" alt="PulseWire logo">
                    <h1 class="page-title">PulseWire</h1>
                </div>

                <form action="login.php" method="POST">
                    <span id="required-fields-hint">* marked fields are required</span>

                    <label>Username *
                        <input type="text" name="username" placeholder="username" id="username" value="<?= htmlspecialchars($username) ?>" <?php $validator->errorClass("username") ?>>
                    </label>
                    <label>Password *
                        <input type="password" name="password" placeholder="Password" id="password_1" value="" <?php $validator->errorClass("password") ?>>
                    </label>
                    <?= $validator->displayErrors() ?>
                    <input type="submit" value="Log in" name="submit">
                </form>
            
                <span>New to PulseWire? 
                    <a href="register.php">Create an account</a>
                </span>
            </div>

            <?php include("templates/side_menu.php") ?>
        </main>
    </body>
</html>