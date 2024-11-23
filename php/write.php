<?php

require_once "validator.php";
$validator = new Validator();

$title = $validator->getFromPOST("article-title");
$summary = $validator->getFromPOST("article-summary");
$body = $validator->getFromPOST("article-body");
$image = $validator->getFromPOST("article-image");

$validator->checkEmpty("article-title", "Title");
$validator->checkEmpty("article-summary", "Summary");
$validator->checkEmpty("article-body", "Content");

// TODO redirect na stranku s novym clankem
if ($validator->success()) {
    header("Location: ../html/article.html");

    require_once "database.php";
    $db = new Database();
    $db->addArticle($title, $summary, $body);
    // TODO naucit se ukladat ten obrazek
}
    
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title>Write an article</title>
        
        <!-- <script src="../js/field_error_handling.js" defer></script>
        <script src="../js/write_article_handler.js" defer></script> -->

        <link rel="stylesheet" href="../css/form.css">
        <link rel="stylesheet" href="../css/write_article.css">
        
        <?php include("../html/metadata.html") ?>
    </head>
    <body>
        <?php include("../html/header.html") ?>

        <main>
            <div class="inner-content">
                <h2>Create an article</h2>
                <form action="write.php" method="POST" enctype="multipart/form-data">
                    <span id="required-fields-hint">* marked fields are required</span>

                    <!-- TODO pridat vyber kategorie -->

                    <label for="article-title">Title *</label>
                    <textarea name="article-title" id="article-title" rows="1" class=<?php $validator->errorClass("article-title") ?>><?= htmlspecialchars($title) ?></textarea>
                    <label for="article-summary">Summary *</label>
                    <textarea name="article-summary" id="article-summary" rows="3" class=<?php $validator->errorClass("article-summary") ?>><?= htmlspecialchars($summary) ?></textarea>
                    <label for="article-body">Content *</label>
                    <textarea name="article-body" id="article-body" rows="10" class=<?php $validator->errorClass("article-body") ?>><?= htmlspecialchars($body) ?></textarea>
                    
                    <label for="article-image" id="image-upload">Upload header image *
                        <input type="file" name="article-image" id="article-image" accept="image/*" class=<?php $validator->errorClass("article-image") ?>>
                    </label>

                    <?php $validator->displayErrors() ?>

                    <input type="submit" name="submit-button" value="Publish">
                </form>
            </div>

            <?php include("../html/sidemenu.html") ?>
        </main>
    </body>
</html>