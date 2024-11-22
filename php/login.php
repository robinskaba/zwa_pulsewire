<?php

require_once "form_validator.php";
$validator = new Validator(["username", "password"]);

$username = "";
$password = "";

if (isset($_POST["username"])) {
    $username = $_POST["username"];

    if(strlen($username) < 4) {
        $validator->addError("username", "Username can not be shorter than 4 characters");
    }
}

if (isset($_POST["password"])) {
    $password = $_POST["password"];

    if(strlen($password) < 1) {
        $validator->addError("password", "Password can not be empty");
    }
}

$all_fields_empty = strlen($username) == 0 && strlen($password) == 0;
if(!$all_fields_empty && !$validator->hasErrors()) {
    header("Location: ../html/index.html");
}

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
                        <span id="error-hint"
                            <?php 
                                if($all_fields_empty) echo "class=hidden";
                            ?> 
                        >
                            <?php
                                if(!$all_fields_empty) echo "The server has denied your request because of the following reasons<hr>";
                                $validator->formatMessages();
                            ?>
                        </span>
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