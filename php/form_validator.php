<?php class Validator {

    private array $errors;

    public function __construct(array $field_keys) {
        $this->errors = array();
        foreach($field_keys as $key) {
            $this->errors[$key] = array();
        }
    }

    public function hasErrors() {
        foreach($this->errors as $_ => $recorded_errors) {
            if (sizeof($recorded_errors) > 0) return TRUE;
        }
        return FALSE;
    }

    public function formatMessages() {
        foreach($this->errors as $field => $recorded_errors) {
            foreach($recorded_errors as $i => $error_message) {
                if($field == "password2" && $i == sizeof($recorded_errors)-1) echo $error_message;
                else echo $error_message."<br>";
            }
        }
    }

    public function addError(string $field, string $message) {
        $this->errors[$field][] = $message;
    }
}

?>