<?php 
    require_once "database.php";
    $db = new Database();

    $username = NULL;
    if (isset($_GET["username"])) $username = $_GET["username"];
    if(!$username || !$db->userExists($username)) {
        header("Location: page_not_found.php");
    } else {
        $user = $db->getUser($username);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- TODO htmlspecialchars for title?? -->
        <title><?= htmlspecialchars($username) ?>'s profile</title>
        <link rel="stylesheet" href="../css/profile.css">
        <link rel="stylesheet" href="../css/comments.css">

        <?php include("../html/metadata.html"); ?>
    </head>
    <body>
        <?php include("../html/header.html"); ?>

        <main>
            <div class="inner-content">   
                <h2><?= htmlspecialchars($username, true) ?>'s profile</h2>
                <h4>Profile information</h4>
                <div class="profile-information">
                    <span><span>First name: </span><?= htmlspecialchars($user->first_name, true) ?></span>
                    <span><span>Second name: </span><?= htmlspecialchars($user->second_name, true) ?></span>
                </div>
                <hr>
                <h4>Posted comments</h4>
                <div class="comment-list">
                    <div class="comment">
                        <h5><a href="#idKomentareNaStranceClanku">Title of article</a></h5>
                        <span>Datum publikování</span>
                        <p>
                            Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře 
                            Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře 
                            Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře 
                            Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře Obsah komentáře 
                        </p>
                    </div>
                </div>
            </div>

            <?php include("../html/sidemenu.html"); ?>          
        </main>
    </body>
</html>