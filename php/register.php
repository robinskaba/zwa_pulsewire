<?php 

$username = "";
$first_name = "";
$second_name = "";
$password1 = "";
$password2 = "";

$issue_found = false;

if (isset($_GET["username"])) {
    $username = $_GET["username"];
    if (strlen($username) < 4) {
        $issue_found = true;
        echo "<p>Username is too short</p>";
    }
}

if (!$issue_found) {
    header("Location: ../index.html");
}

?>

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
    <span id="error-hint" class="hidden"></span>
    <input class="submitButton" type="submit" value="Register" name="submit">
</form>