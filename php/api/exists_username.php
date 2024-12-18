<?php

/**
 * Soubor zjistí zda existuje uživatel s uživatelským jménem zadaným v GET dotazu.
 * Slouží pro AJAXové ověření, zda je uživatelské jméno dostupné.
 * @author Robin Škába
 */

require_once "../main/database.php";
$db = new Database();

$q_username = NULL;
if(isset($_GET["username"])) $q_username = $_GET["username"];
if(!$q_username || $db->userExists($q_username)) echo "true";
else echo "false";

?>