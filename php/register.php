<?php 

$username = "";
$first_name = "";
$second_name = "";
$password1 = "";
$password2 = "";

$issue_found = false;

if (isset($_POST["username"])) {
    $username = $_POST["username"];
    if (strlen($username) < 4) {
        $issue_found = true;
        echo "<p>Username is too short</p>";
    }
}

if (!$issue_found) {
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
                                <input type="text" name="first_name" placeholder="First Name" id="first_name">
                            </label>
                            <label>Second Name *
                                <input type="text" name="second_name" placeholder="Second Name" id="second_name">
                            </label>
                        </div>
                        <label>Password *
                            <input type="password" name="password_1" placeholder="Password" id="password_1">
                        </label>
                        <label>Password again *
                            <input type="password" name="password_2" placeholder="Password again" id="password_2">
                        </label>
                        <span id="error-hint" class=
                            <?php 
                                if (!$issue_found) echo "hidden";
                                else echo "";
                            ?>
                        >
                            This is issue field
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
