<?php

require_once "validator.php";
$validator = new Validator();

$title = $validator->getFromPOST("article-title");
$summary = $validator->getFromPOST("article-summary");
$body = $validator->getFromPOST("article-body");
$category = $validator->getFromPOST("article-category");
$image = $validator->getFromPOST("article-image");

$validator->checkEmpty("article-title", "Title");
$validator->checkEmpty("article-summary", "Summary");
$validator->checkEmpty("article-body", "Content");

if ($validator->success()) {
    require_once "database.php";
    $db = new Database();
    $image_path = $db->saveImage($_FILES["article-image"]);
    $id = $db->addArticle($title, $summary, $body, $image_path, $category);
    
    header("Location: ../php/article.php?id=".$id);
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
        <?php include "header.php" ?>

        <main>
            <div class="inner-content">
                <h2>Create an article</h2>
                <form action="write.php" method="POST" enctype="multipart/form-data">
                    <span id="required-fields-hint">* marked fields are required</span>

                    <label for="article-title">Title *</label>
                    <textarea name="article-title" id="article-title" rows="1" class=<?php $validator->errorClass("article-title") ?>><?= htmlspecialchars($title, ENT_QUOTES) ?></textarea>
                    <label for="article-summary">Summary *</label>
                    <textarea name="article-summary" id="article-summary" rows="3" class=<?php $validator->errorClass("article-summary") ?>><?= htmlspecialchars($summary, ENT_QUOTES) ?></textarea>
                    <label for="article-body">Content *</label>
                    <textarea name="article-body" id="article-body" rows="10" class=<?php $validator->errorClass("article-body") ?>><?= htmlspecialchars($body, ENT_QUOTES) ?></textarea>
                    
                    <div>
                        <label for="article-category">Category *</label>
                        <select name="article-category" id="article-category">
                            <?php
                                include "categories.php";
                                foreach($categories as $_category):
                            ?>
                                <option <?php if($_category == $category) echo "selected" ?> value="<?= $_category ?>"><?= $_category ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label id="image-upload">Upload header image *
                            <input type="file" name="article-image" id="article-image" accept="image/png, image/jpeg" class=<?php $validator->errorClass("article-image") ?>>
                        </label>
                    </div>

                    <?php $validator->displayErrors() ?>

                    <input type="submit" name="submit" value="Publish">
                </form>
            </div>

            <?php include("../html/sidemenu.html") ?>
        </main>
    </body>
</html>