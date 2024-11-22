<?php 
    $username = NULL;
    if (isset($_GET["username"])) $username = $_GET["username"];
    if(!$username) {
        header("Location: page_not_found.php");
    }

    // TODO redirect to page not found when user not in database
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>User's profile</title>
        <link rel="stylesheet" href="../css/profile.css">
        <link rel="stylesheet" href="../css/comments.css">

        <?php include("../html/metadata.html"); ?>
    </head>
    <body>
        <?php include("../html/header.html"); ?>

        <main>
            <div class="inner-content">   
                <h2><?= htmlspecialchars($username) ?>'s profile</h2>
                <h4>Profile information</h4>
                <div class="profile-information">
                    <span><span>First name: </span>xxxxxxxxxxx</span>
                    <span><span>Second name: </span>xxxxxxxxxxx</span>
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