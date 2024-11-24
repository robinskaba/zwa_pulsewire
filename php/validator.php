<?php 

class Validator {

    private array $errors;
    public bool $all_empty = true;

    public function hasErrors() {
        foreach($this->errors as $_ => $recorded_errors) {
            if (sizeof($recorded_errors) > 0) return TRUE;
        }
        return FALSE;
    }

    public function errorClass($key) {
        if (isset($this->errors[$key])) {
            $recorded_errors = $this->errors[$key];
            if (sizeof($recorded_errors) > 0) {
                echo "error";
                return;
            } 
        }
        echo "";
    }

    public function displayErrors() {
        $message = "The server has denied your request because of the following reasons<hr>";
        foreach($this->errors as $field => $recorded_errors) {
            foreach($recorded_errors as $i => $error_message) {
                if($field == "password2" && $i == sizeof($recorded_errors)-1) $message = $message.$error_message;
                else $message = $message.$error_message."<br>";
            }
        }

        $class = $this->all_empty ? "hidden" : "";

        echo "<span id=error-hint class=".$class.">".$message."</span>";
    }

    public function addPlaceholder(string $key) {
        // for general messages like - wrong login credentials - #CHECKUSED remove if unused
        $this->errors[$key][] = array();
    }

    public function addError(string $field, string $message) {
        $this->errors[$field][] = $message;
    }

    public function getFromPOST($request_key): string {
        // init array for recording errors
        $this->errors[$request_key] = array();

        // check if key in post body
        if (isset($_POST[$request_key])) {
            $value = $_POST[$request_key];
            $this->all_empty = false;
            return $value;
        }
        return "";
    }

    public function success(): bool {
        return !$this->all_empty && !$this->hasErrors();
    }

    // common validations
    public function checkEmpty(string $request_key, string $message_prefix) {
        if (!isset($_POST[$request_key])) return;

        if (strlen($_POST[$request_key]) < 1) {
            $this->addError($request_key, $message_prefix." can not be empty");
        }
    }

    public function checkLength(int $required_length, string $request_key, string $message_prefix) {
        if (!isset($_POST[$request_key])) return;

        if (strlen($_POST[$request_key]) < $required_length) {
            $this->addError($request_key, $message_prefix." must be at least ".(string)$required_length." characters long");
        }
    }

    public function checkIllegalChars(string $request_key, string $message_prefix) {
        if (!isset($_POST[$request_key])) return;

        if (!ctype_alnum($_POST[$request_key])) {
            $this->addError($request_key, $message_prefix." can only contain letters and numbers");
        }
    }

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

    public function checkMatch(string $targetContent, string $request_key, string $message_prefix) {
        if (!isset($_POST[$request_key])) return;

        if ($targetContent != $_POST[$request_key]) {
            $this->addError($request_key, $message_prefix." must match");
        }
    }

    public function checkUserExists(string $request_key) {
        if (!isset($_POST[$request_key])) return;

        require_once "database.php";
        $db = new Database();
        if($db->userExists($_POST[$request_key])) $this->addError($request_key, "Username is already taken");
    }
}

?>