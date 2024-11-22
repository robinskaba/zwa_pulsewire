<?php 

$username = "";
$first_name = "";
$second_name = "";
$password1 = "";
$password2 = "";

$errors = array(
    "username" => array(),
    "first_name" => array(),
    "second_name" => array(),
    "password1" => array(),
    "password2" => array()
);

if (isset($_POST["username"])) {
    $username = $_POST["username"];
    if (strlen($username) < 4) {
        $errors["username"][] = "Username must be at least 4 characters";
    }
    $illegal_chars = array('@', '&', ',', '!', '#', '$', '%', '^', '*', '(', ')', '+', '=', '{', '}', '[', ']', '|', '\\', ':', ';', "'", '"', '<', '>', '?', '/', '~', '`', ',');

    if (!ctype_alnum($username)) {
        $errors["username"][] = "Username can only contain letters and numbers";
    }
}
if (isset($_POST["first_name"])) {
    $first_name = $_POST["first_name"];
    if (strlen($first_name) < 1) {
        $errors["first_name"][] = "First name can not be empty";
    }
}
if (isset($_POST["second_name"])) {
    $second_name = $_POST["second_name"];
    if (strlen($second_name) < 4) {
        $errors["second_name"][] = "Second name can not be empty";
    }
}
if (isset($_POST["password1"])) {
    $password1 = $_POST["password1"];
    if (strlen($password1) < 8) {
        $errors["password1"][] = "Password must be at least 8 characters long";
    }

    $has_number = false;
    foreach(str_split($password1) as $char) {
        if (is_numeric($char)) { 
            $has_number = true; 
            break;
        }
    }
    if (!$has_number) $errors["password1"][] = "Password must contain a number";
}
if (isset($_POST["password2"])) {
    $password2 = $_POST["password2"];
    if ($password1 != $password2) {
        $errors["password2"][] = "Passwords must match";
    }
}

$all_fields_empty =
    strlen($username) == 0 &&
    strlen($first_name) == 0 &&
    strlen($second_name) == 0 &&
    strlen($password1) == 0 &&
    strlen($password2) == 0;

$exists_error = false;
foreach ($errors as $_ => $recorded_errors) {
    if (sizeof($recorded_errors) > 0) { 
        $exists_error = true; 
        break;
    }
} 

if (!$all_fields_empty && !$exists_error) {
    header("Location: ../html/index.html");
}

?>

<html lang="en">
    <head>
        <title>Register for PulseWire</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- <script src="../js/field_error_handling.js" defer></script> -->
        <!-- <script src="../js/register_form_validation.js" defer></script> -->
        <script src="../js/side_menu.js" defer></script>

        <link rel="icon" type="image/x-icon" href="../src/favicon.ico">
        <link rel="stylesheet" href="../css/form.css">

        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/common.css">
    </head>
    <body>

        <?= include("../html/header.html") ?>

        <main>
            <div class="inner-content">
                <div class="form-heading">
                    <h2 class="form-headline">Register for</h2>
                    <img src="../src/logo_128x128.png" alt="page logo">
                    <h1 class="page-title">PulseWire</h1>
                </div>

                <div class="form-wrap">
                    <form action="register.php" method="POST">
                        <span id="required-fields-hint">* marked fields are required</span>

                        <label>Username *
                            <input type="text" name="username" placeholder="username" id="username" value="<?= htmlspecialchars($username) ?>">
                        </label>
                        
                        <div>
                            <label>First Name *
                                <input type="text" name="first_name" placeholder="First Name" id="first_name" value="<?= htmlspecialchars($first_name) ?>">
                            </label>
                            
                            <label>Second Name *
                                <input type="text" name="second_name" placeholder="Second Name" id="second_name" value="<?= htmlspecialchars($second_name) ?>">
                            </label>
                        </div>
                        <label>Password *
                            <input type="password" name="password1" placeholder="Password" id="password_1" value="<?= htmlspecialchars($password1) ?>">
                        </label>
                        <label>Password again *
                            <input type="password" name="password2" placeholder="Password again" id="password_2" value="<?= htmlspecialchars($password2) ?>">
                        </label>
                        <span id="error-hint"
                            <?php 
                                if($all_fields_empty) echo "class=hidden";
                            ?> 
                        >
                            <?php
                                if(!$all_fields_empty) echo "The server has denied your request because of the following reasons<hr>";
                                foreach($errors as $field => $recorded_errors) {
                                    foreach($recorded_errors as $i => $error_message) {
                                        if($field == "password2" && $i == sizeof($recorded_errors)-1) echo $error_message;
                                        else echo $error_message."<br>";
                                    }
                                }
                            ?>
                        </span>
                        <input class="submitButton" type="submit" value="Register" name="submit">
                    </form>
                </div>
            
            <span>Do you already have an account? 
                    <a href="login.html">Login</a>
            </span>
        </div>

        <?= include("../html/sidemenu.html") ?>
    </body>
</html>
