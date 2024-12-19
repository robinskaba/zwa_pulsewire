<?php

/**
 * Tento soubor obsahuje stránku, která umožňuje administrátorovi nastavit nové heslo pro uživatele.
 * @author Robin Škába
 */

/**
 * Vyžaduje session.php pro získání informací o přihlášeném uživateli.
 */
require_once "../main/session.php";

// redirectnout, kdyby se nahodou nekdo dostal na tuhle stranku kdo neni admin
if(!$logged_user || !$logged_user->isAdmin()) header("Location: page_not_found.php");

/**
 * @var string $P1_KEY Klíč formulářového pole pro první heslo.
 * @var string $P2_KEY Klíč formulářového pole pro znovuzadané heslo.
 */
$P1_KEY = "password_1";
$P2_KEY = "password_2";


/**
 * @var string $username Uživatelské jméno uživatele, pro kterého se má heslo resetovat.
 */
$username = "";
if(isset($_GET["username"])) {
    $username = $_GET["username"];
} else {
    header("Location: page_not_found.php");
}

/**
 * Vyžaduje objekt validátoru z validator.php pro validaci formulářových polí.
 * @var Validator $validator Objekt validátoru
 * @var string $password_1 První heslo z formuláře.
 */
require_once "../main/validator.php";
$validator = new Validator();

$password_1 = $validator->getFromPOST($P1_KEY);

/**
 * Validace formulářových polí validátorem.
 */
$validator->checkLength(8, $P1_KEY, "Password");
$validator->checkContainsNumber($P1_KEY, "Password");
$validator->checkMatch($password_1, $P2_KEY, "Passwords");

/**
 * V případě, že validátor schválí daná data, změní se heslo uživatele v databázi.
 */
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
        <?php include("../../html/metadata.html") ?>

        <script src="../../js/error_handler.js" defer></script>
        <script src="../../js/password_reset_validation.js" defer></script>
        
        <link rel="stylesheet" href="../../css/form.css">
    </head>
    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div>
                <div>
                    <h1>Reset password for <?= htmlspecialchars($username, true) ?></h1>
                </div>

                <form action="<?= "password_reset.php?username=".$username ?>" method="POST">
                    <span id="required-fields-hint">* marked fields are required</span>

                    <label>New password *
                        <input type="password" name=<?= $P1_KEY ?> placeholder="New password" id="password_1" value="" <?php $validator->errorClass($P1_KEY) ?>>
                    </label>
                    <label>Password again *
                        <input type="password" name=<?= $P2_KEY ?> placeholder="Password again" id="password_2" value="" <?php $validator->errorClass($P2_KEY) ?>>
                    </label>
                    <?= $validator->displayErrors() ?>
                    
                    <input type="submit" value="Set new password" name="submit">
                </form>
            </div>

            <?php include("templates/side_menu.php") ?>
        </main>
    </body>
</html>