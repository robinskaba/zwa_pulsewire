<?php

/**
 * Tento soubor slouží pouze k načtení používaných kategorií článků z .json souboru.
 * Využívají ho soubory jako header.php a write.php.
*/

$CATEGORIES = json_decode(file_get_contents("../../config/categories.json"), true);

?>