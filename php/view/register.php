<?php 

session_start();

require_once "../main/validator.php";
$validator = new Validator();

$username = $validator->getFromPOST("username");
$first_name = $validator->getFromPOST("first_name");
$second_name = $validator->getFromPOST("second_name");
$password1 = $validator->getFromPOST("password1");
$password2 = $validator->getFromPOST("password2");

$validator->checkLength(4, "username", "Username");
$validator->checkIllegalChars("username", "Username");
$validator->checkUserExists("username");
$validator->checkEmpty("first_name", "First name");
$validator->checkEmpty("second_name", "Second name");
$validator->checkLength(8, "password1", "Password");
$validator->checkContainsNumber("password1", "Password");
$validator->checkMatch($password1, "password2", "Passwords");

if ($validator->success()) {
    require_once "../main/database.php";
    $db = new Database();
    $db->addUser($username, $first_name, $second_name, $password1);
    $_SESSION["username"] = $username;

    header("Location: profile.php?username=".$username);
}

?>

<html lang="en">
    <head>
        <title>Register for PulseWire</title>
        
        <script src="../../js/field_error_handling.js" defer></script>
        <script src="../../js/register_form_validation.js" defer></script>

        <link rel="stylesheet" href="../../css/form.css">

        <?php include("../../html/metadata.html") ?>
    </head>
    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div class="inner-content">
                <div class="form-heading">
                    <h2 class="form-headline">Register for</h2>
                    <img src="../../src/logo_128x128.png" alt="page logo">
                    <h1 class="page-title">PulseWire</h1>
                </div>

                <div class="form-wrap">
                    <form action="register.php" method="POST">
                        <span id="required-fields-hint">* marked fields are required</span>

                        <label>Username *
                            <input type="text" name="username" placeholder="username" id="username" value="<?= htmlspecialchars($username) ?>" class=<?php $validator->errorClass("username") ?>>
                        </label>
                        
                        <div>
                            <label>First Name *
                                <input type="text" name="first_name" placeholder="First Name" id="first_name" value="<?= htmlspecialchars($first_name) ?>" class=<?php $validator->errorClass("first_name") ?>>
                            </label>
                            
                            <label>Second Name *
                                <input type="text" name="second_name" placeholder="Second Name" id="second_name" value="<?= htmlspecialchars($second_name) ?>" class=<?php $validator->errorClass("second_name") ?>>
                            </label>
                        </div>
                        <label>Password *
                            <input type="password" name="password1" placeholder="Password" id="password_1" value="<?= htmlspecialchars($password1) ?>" class=<?php $validator->errorClass("password1") ?>>
                        </label>
                        <label>Password again *
                            <input type="password" name="password2" placeholder="Password again" id="password_2" value="<?= htmlspecialchars($password2) ?>" class=<?php $validator->errorClass("password2") ?>>
                        </label>
                        <?= $validator->displayErrors() ?>
                        <input type="submit" value="Register" name="submit">
                    </form>
                </div>
            
            <span>Do you already have an account? 
                    <a href="login.html">Login</a>
            </span>
        </div>

        <?php include("../../html/sidemenu.html") ?>
    </body>
</html>
