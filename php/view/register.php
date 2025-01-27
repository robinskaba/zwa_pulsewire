<?php 

/**
 * Tento soubor obsahuje logiku registrace (ověření dat) a formulář pro registraci uživatele.
 * Uživatel je přesměrován na svůj profil po úspěšné registraci.
 */

 /**
  * Vyžaduje session.php pro získání informací o přihlášeném uživateli a zabránění přihlášeným uživatelům vstup na tuto stránku.
  */
require_once "../main/session.php";
if($logged_user) header("Location: index.php");

/**
 * Vyžaduje objekt validátoru z validator.php pro validaci formulářových polí.
 * @var Validator $validator Objekt validátoru
 * @var string $username Uživatelské jméno z formuláře.
 * @var string $first_name Křestní jméno z formuláře.
 * @var string $second_name Příjmení z formuláře.
 * @var string $password1 Heslo z formuláře.
 */
require_once "../main/validator.php";
$validator = new Validator();

$username = $validator->getFromPOST("username");
$first_name = $validator->getFromPOST("first_name");
$second_name = $validator->getFromPOST("second_name");
$password1 = $validator->getFromPOST("password1");

/**
 * Logika ověření registrace.
 */
$validator->checkLength(4, "username", "Username");
$validator->checkIllegalChars("username", "Username");
$validator->checkUserExists("username");
$validator->checkEmpty("first_name", "First name");
$validator->checkEmpty("second_name", "Family name");
$validator->checkLength(8, "password1", "Password");
$validator->checkContainsNumber("password1", "Password");
$validator->checkMatch($password1, "password2", "Passwords");

/**
 * V případě, že validátor schválí daná data, uživatel je zaregistrován a přesměrován na svůj profil.
 */
if ($validator->success()) {
    require_once "../main/database.php";
    $db = new Database();
    $db->addUser($username, $first_name, $second_name, $password1);
    $_SESSION["username"] = $username;

    header("Location: profile.php?username=".$username);
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Register for PulseWire</title>
        <?php include("../../html/metadata.html") ?>

        <script src="../../js/error_handler.js" defer></script>
        <script src="../../js/register_form_validation.js" defer></script>
        
        <link rel="stylesheet" href="../../css/form.css">
    </head>
    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div>
                <div>
                    <h2>Register for</h2>
                    <img src="../../src/logo_128x128.png" alt="PulseWire logo">
                    <h1 class="page-title">PulseWire</h1>
                </div>

                <form action="register.php" method="POST">
                    <span id="required-fields-hint">* marked fields are required</span>

                    <label>Username *
                        <input type="text" name="username" placeholder="username" id="username" value="<?= htmlspecialchars($username) ?>" <?php $validator->errorClass("username") ?>>
                    </label>
                    
                    <div>
                        <label>First Name *
                            <input type="text" name="first_name" placeholder="First Name" id="first_name" value="<?= htmlspecialchars($first_name) ?>" <?php $validator->errorClass("first_name") ?>>
                        </label>
                        
                        <label>Family Name *
                            <input type="text" name="second_name" placeholder="Family Name" id="second_name" value="<?= htmlspecialchars($second_name) ?>" <?php $validator->errorClass("second_name") ?>>
                        </label>
                    </div>
                    <label>Password *
                        <input type="password" name="password1" placeholder="Password" id="password_1" value="" <?php $validator->errorClass("password1") ?>>
                    </label>
                    <label>Password again *
                        <input type="password" name="password2" placeholder="Password again" id="password_2" value="" <?php $validator->errorClass("password2") ?>>
                    </label>
                    <?= $validator->displayErrors() ?>
                    <input type="submit" value="Register" name="submit">
                </form>

                <span>Do you already have an account? 
                    <a href="login.php">Login</a>
                </span>
            </div>
            
            <?php include("templates/side_menu.php") ?>
        </main>
    </body>
</html>
