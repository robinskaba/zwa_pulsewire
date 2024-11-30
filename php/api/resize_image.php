<?php 

function resize_image(string $original_path, int $new_width, int $new_height) {
    $resized_image = imagecreatetruecolor($new_width, $new_height);
    list($original_width, $original_height, $image_type) = getimagesize($original_path);
    switch($image_type) {
        case IMAGETYPE_JPEG:
            $original_image = imagecreatefromjpeg($original_path);
            break;
        case IMAGETYPE_PNG:
            $original_image = imagecreatefrompng($original_path);
            break;
        default: throw new Exception("Server shouldn't have image of this type.");
    }
    imagecopyresized($resized_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

    switch ($image_type) {
        case IMAGETYPE_JPEG:
            header("Content-Type: image/jpeg");
            imagejpeg($resized_image);
            break;
        case IMAGETYPE_PNG:
            header("Content-Type: image/png");
            imagepng($resized_image);
            break;
    }
}

if (isset($_GET['img'], $_GET['width'], $_GET['height'])) {
    resize_image(urldecode($_GET['img']), (int) $_GET["width"], (int) $_GET["height"]);
} else {
    header("Location: ../view/page_not_found.php");
}

?>