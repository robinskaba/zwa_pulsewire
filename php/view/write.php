<?php

require_once "../main/session.php";

if(!$logged_user || (!$logged_user->isWriter() && !$logged_user->isAdmin())) header("Location: page_not_found.php");

require_once "../main/validator.php";
$validator = new Validator();

$title = $validator->getFromPOST("article-title");
$summary = $validator->getFromPOST("article-summary");
$body = $validator->getFromPOST("article-body");
$category = $validator->getFromPOST("article-category");
$image = $validator->recordErrorsForField("article-image");

$validator->checkEmpty("article-title", "Title");
$validator->checkEmpty("article-summary", "Summary");
$validator->checkEmpty("article-body", "Content");

$validator->checkFileSize("article-image", 50, 3000000, "Header image");
$validator->checkFileIsOfType("article-image", ["image/png", "image/jpeg"], "Header image");

if ($validator->success()) {
    require_once "../main/database.php";
    $db = new Database();
    $image_path = $db->saveImage($_FILES["article-image"]);
    $id = $db->addArticle($title, $summary, $body, $image_path, $category);
    
    header("Location: article.php?id=".$id);
}
    
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title>Write an article</title>
        <?php include("../../html/metadata.html") ?>

        <script src="../../js/error_handler.js" defer></script>
        <script src="../../js/write_article_handler.js" defer></script>

        <link rel="stylesheet" href="../../css/form.css">
        <link rel="stylesheet" href="../../css/write_article.css">
    </head>
    <body>
        <?php include "templates/header.php" ?>

        <main>
            <div>
                <h1>Write an article</h1>
                <form action="write.php" method="POST" enctype="multipart/form-data">
                    <span id="required-fields-hint">* marked fields are required</span>

                    <label for="article-title">Title *</label>
                    <textarea name="article-title" id="article-title" rows="1" <?php $validator->errorClass("article-title") ?>><?= htmlspecialchars($title, ENT_QUOTES) ?></textarea>
                    <label for="article-summary">Summary *</label>
                    <textarea name="article-summary" id="article-summary" rows="3" <?php $validator->errorClass("article-summary") ?>><?= htmlspecialchars($summary, ENT_QUOTES) ?></textarea>
                    <label for="article-body">Content *</label>
                    <textarea name="article-body" id="article-body" rows="10" <?php $validator->errorClass("article-body") ?>><?= htmlspecialchars($body, ENT_QUOTES) ?></textarea>
                    
                    <div>
                        <label for="article-category">Category *</label>
                        <select name="article-category" id="article-category">
                            <?php
                                include "../main/categories.php";
                                foreach($CATEGORIES as $_category):
                            ?>
                                <option <?php if($_category == $category) echo "selected" ?> value="<?= $_category ?>"><?= $_category ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label id="image-upload"><span>Upload header image *</span>
                            <input type="file" name="article-image" accept="image/png, image/jpeg" <?php $validator->errorClass("article-image") ?>>
                        </label>
                    </div>

                    <?php $validator->displayErrors() ?>

                    <input type="submit" name="submit" value="Publish">
                </form>
            </div>

            <?php include("templates/side_menu.php") ?>
        </main>
    </body>
</html>