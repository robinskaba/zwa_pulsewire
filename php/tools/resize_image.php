<?php 

function resize_image_to_type(string $original_path, string $size_type, string $save_path) {
    list($original_width, $original_height, $image_type) = getimagesize($original_path);

    $sizes_of_types = json_decode(file_get_contents("../../config/images.json"), true);
    $new_width = $sizes_of_types[$size_type];

    $aspect_ratio = $original_width / $original_height;
    $new_height = round($new_width / $aspect_ratio);

    $resized_image = imagecreatetruecolor($new_width, $new_height);
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
            imagejpeg($resized_image, $save_path);
            break;
        case IMAGETYPE_PNG:
            imagepng($resized_image, $save_path);
            break;
    }
}

?>