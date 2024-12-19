<?php 

/**
 * Třída sloužící k validaci vstupních dat z formulářů.
 * Obsahuje funkce, které buď přímo vykonávají určitou validaci nebo umožňují lehké provedení validace.
 * @author Robin Škába
 */

class Validator {

    /**
     * @var array $errors Pole pro různé formulářové pole s zaznamenými chybovými hlášeními.
     * @var bool $all_empty Proměnná, která určuje, zda byla nějaká data načtena z $_POST proměnné.
     */
    private array $errors;
    public bool $all_empty = true;

    /**
     * Zjistí jestli byly zaznamenány nějaké chyby.
     * @return bool TRUE, pokud byly zaznamenány nějaké chyby, jinak FALSE.
     */
    public function hasErrors(): bool {
        foreach($this->errors as $_ => $recorded_errors) {
            if (sizeof($recorded_errors) > 0) return TRUE;
        }
        return FALSE;
    }

    /**
     * Nastaví poli formuláře třídu error, pokud byla zaznamenána chyba.
     * Funkce by měla být volaná přímo pomocí PHP v HTML tagu pole.
     * @param string $key Klíč pro pole chyb - name formluřávolého pole
     */
    public function errorClass($key) {
        if (isset($this->errors[$key])) {
            $recorded_errors = $this->errors[$key];
            if (sizeof($recorded_errors) > 0) {
                echo "class=error";
                return;
            } 
        }
        echo "";
    }

    /**
     * Zobrazí chybové hlášení uživateli pomocí vytvoření určitých HTML elementů, pokud byly zaznamenány nějaké chyby.
     */
    public function displayErrors() {
        $message = "";
        foreach($this->errors as $field => $recorded_errors) {
            foreach($recorded_errors as $i => $error_message) {
                if($field == "password2" && $i == sizeof($recorded_errors)-1) $message = $message.$error_message;
                else $message = $message.$error_message."<br>";
            }
        }

        $class = $this->all_empty ? "hidden" : "";

        echo "<span class=".$class.">The server has denied your request because of the following reasons</span><hr class=".$class.">";
        echo "<span id=error-hint class=".$class.">".$message."</span>";
    }

    /**
     * Přidá chybové hlášení do pole chyb pro určité formulářové pole.
     * @param string $field Klíč formulářového pole.
     * @param string $message Chybové hlášení.
     */
    public function addError(string $field, string $message) {
        $this->errors[$field][] = $message;
    }

    /**
     * Získá hodnotu poslaného formuláře z $_POST proměnné.
     * @param string $request_key Klíč formulářového pole.
     * @return string Hodnota daného klíče z formulářového pole (nebo-li $_POST proměnné).
     */
    public function getFromPOST(string $request_key): string {
        $this->errors[$request_key] = array();

        // check if key in post body
        if (isset($_POST[$request_key])) {
            $value = $_POST[$request_key];
            $this->all_empty = false;
            return $value;
        }
        return "";
    }

    /**
     * Zjistí, jestli všechna zaznamenaná pole prošla kontrolami (nebo-li neobsahují žádné zaznamenané chyby).
     * @return bool TRUE, pokud všechna pole prošla kontrolami, jinak FALSE.
     */
    public function success(): bool {
        return !$this->all_empty && !$this->hasErrors();
    }

    /**
     * Zkontroluje, zda bylo pole vyplněno.
     * @param string $request_key Klíč formulářového pole.
     * @param string $message_prefix Prefix chybového hlášení.
     */
    public function checkEmpty(string $request_key, string $message_prefix) {
        if (!isset($_POST[$request_key])) return;

        if (strlen($_POST[$request_key]) < 1) {
            $this->addError($request_key, $message_prefix." can not be empty");
        }
    }

    /**
     * Zkontroluje, zda pole obsahuje alespoň určitý počet znaků.
     * @param int $required_length Požadovaná délka pole.
     * @param string $request_key Klíč formulářového pole.
     * @param string $message_prefix Prefix chybového hlášení.
     */
    public function checkLength(int $required_length, string $request_key, string $message_prefix) {
        if (!isset($_POST[$request_key])) return;

        if (strlen($_POST[$request_key]) < $required_length) {
            $this->addError($request_key, $message_prefix." must be at least ".(string)$required_length." characters long");
        }
    }

    /**
     * Zkontroluje, zda pole obsahuje pouze anglická písmena a čísla.
     * @param string $request_key Klíč formulářového pole.
     * @param string $message_prefix Prefix chybového hlášení.
     */
    public function checkIllegalChars(string $request_key, string $message_prefix) {
        if (!isset($_POST[$request_key])) return;

        if (!ctype_alnum($_POST[$request_key])) {
            $this->addError($request_key, $message_prefix." can only contain English letters and numbers");
        }
    }

    /**
     * Zkontroluje, zda pole obsahuje alespoň jedno číslo.
     * @param string $request_key Klíč formulářového pole.
     * @param string $message_prefix Prefix chybového hlášení.
     */
    public function checkContainsNumber(string $request_key, string $message_prefix) {
        if (!isset($_POST[$request_key])) return;

        $has_number = false;
        foreach(str_split($_POST[$request_key]) as $char) {
            if (is_numeric($char)) { 
                $has_number = true; 
                break;
            }
        }
        
        if (!$has_number) $this->addError($request_key, $message_prefix." must contain a number");
    }

    /**
     * Zkontroluje zda obsah formulářového pole se rovná zadanému obsahu.
     * @param string $targetContent Obsah, se kterým se má porovnávat.
     * @param string $request_key Klíč formulářového pole.
     * @param string $message_prefix Prefix chybového hlášení.
     */
    public function checkMatch(string $targetContent, string $request_key, string $message_prefix) {
        if (!isset($_POST[$request_key])) return;

        if ($targetContent != $_POST[$request_key]) {
            $this->addError($request_key, $message_prefix." must match");
        }
    }

    /**
     * Zvaliduje, jestli uživatelské jméno zadané ve formuláři existuji v databázi.
     * @param string $request_key Klíč formulářového pole.
     */
    public function checkUserExists(string $request_key) {
        if (!isset($_POST[$request_key])) return;

        require_once "database.php";
        $db = new Database();
        if($db->userExists($_POST[$request_key])) $this->addError($request_key, "Username is already taken");
    }

    /**
     * Zvaliduje velikost nahraného souboru.
     * @param string $request_key Jméno nahraného souboru.
     * @param int $min_size_in_bytes Minimální velikost souboru v bytech.
     * @param int $max_size_in_bytes Maximální velikost souboru v bytech.
     * @param string $message_prefix Prefix chybového hlášení.
     */
    public function checkFileSize(string $request_key, $min_size_in_bytes, $max_size_in_bytes, string $message_prefix) {
        if (!isset($_FILES[$request_key])) return;
    
        $file_size = $_FILES[$request_key]["size"];
        if ($file_size < $min_size_in_bytes || $file_size > $max_size_in_bytes) {
            $this->addError($request_key, $message_prefix." must be between ".(string)$min_size_in_bytes." and ".(string)$max_size_in_bytes." bytes. (not ".(string) $file_size.")");
        }
    }

    /**
     * Zvaliduje, zda je nahraný soubor určitého typu.
     * @param string $request_key Jméno nahraného souboru.
     * @param array $allowed_types Pole povolených typů souborů.
     * @param string $message_prefix Prefix chybového hlášení.
     */
    public function checkFileIsOfType(string $request_key, array $allowed_types, string $message_prefix) {
        if(!isset($_FILES[$request_key])) return;

        if($_FILES[$request_key]["size"] == 0) return;
        $file_data = $_FILES[$request_key];
        if(!in_array($file_data["type"], $allowed_types)) $this->addError($request_key, $message_prefix." must be of type png, jpg / jpeg. (not ".$file_data["type"].")");
    }
}

?>