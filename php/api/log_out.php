<?php

/**
 * Soubor provede všechny nutné akce k odhlášení uživatele a odstranění jeho session.
 */

session_start();
session_unset();
session_destroy();

header("Location: ../view/login.php");

?>