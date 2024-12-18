<?php

/**
 * Soubor slouží k zahájení session a načtení objektu přihlášeného uživatele.
 * Používané skoro ve všech souborech. Podle toho, jestli je uživatel přihlášený, se zobrazují různé části stránky.
 * @author Robin Škába
 * @var User|null $logged_user Objekt přihlášeného uživatele, pokud není přihlášený, je NULL.
 */

session_start();
$logged_user = NULL;

if(isset($_SESSION["username"])) {
    require_once "database.php";
    $db = new Database();
    $logged_user = $db->getUser($_SESSION["username"]);
}

?>