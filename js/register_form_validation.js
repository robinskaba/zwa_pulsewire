let form = document.querySelector("form");

var error_handler = new error_handler();

// username checks
let username_field = document.querySelector("#username");
let username_length_message = "Username must be at least 4 characters long";
let username_illegal_char_message = "Username can not contain illegal characters";
const ILLEGAL_CHARS = ['@', '&', ',', '!', '#', '$', '%', '^', '*', '(', ')', '+', '=', '{', '}', '[', ']', '|', '\\', ':', ';', "'", '"', '<', '>', '?', '/', '~', '`', ','];

function username_length_check() {
    let username = username_field.value;

    error_handler.run_issue(username.length < 4, username_length_message);

    error_handler.errorify_element(username_field, [username_length_message, username_illegal_char_message]);
    error_handler.process_hint_messages();
}
function illegal_char_check() {
    let username = username_field.value;

    let no_illegal_chars = true;
    for (let i = 0; i < username.length; i++) {
        let char = username[i];
        if (ILLEGAL_CHARS.includes(char)) {
            no_illegal_chars = false;
            break;
        }
    }
    error_handler.run_issue(!no_illegal_chars, username_illegal_char_message);

    error_handler.errorify_element(username_field, [username_length_message, username_illegal_char_message]);
    error_handler.process_hint_messages();
}
username_field.addEventListener("input", illegal_char_check);
username_field.addEventListener("blur", username_length_check);

// first and second name checks
let required_fields = {
    "First Name": document.querySelector("#first_name"),
    "Second Name": document.querySelector("#second_name")
}
error_handler.on_blur_check_required(required_fields);

let password_field_1 = document.querySelector("#password_1");
let password_field_2 = document.querySelector("#password_2");

function general_password_check() {
    let length_message = "Password must be at least 8 characters";
    let character_message = "Password must contain a number";

    // check if password is at least 8 characters long
    error_handler.run_issue(password_field_1.value.length < 8, length_message)

    // check if password has a number in it
    let has_number = false;
    for (let i = 0; i < password_field_1.value.length; i++) {
        let char = password_field_1.value[i]
        if (char >= 0 && char <= 9) {
            has_number = true;
            break;
        }
    }
    error_handler.run_issue(!has_number, character_message);
    error_handler.errorify_element(password_field_1, [length_message, character_message]);
    error_handler.process_hint_messages();
}
function password_again_check() {
    let match_message = "Passwords must match";

    error_handler.run_issue(password_field_1.value != password_field_2.value, match_message);
    error_handler.errorify_element(password_field_2, match_message);
    error_handler.process_hint_messages();
}
password_field_1.addEventListener("blur", general_password_check);
password_field_1.addEventListener("blur", password_again_check);
password_field_2.addEventListener("blur", password_again_check);

function check_on_submit(ev) {
    // total check on submit

    username_length_check();
    illegal_char_check();

    error_handler.check_required_fields(required_fields);

    general_password_check();
    password_again_check();

    if (error_handler.has_issues()) {
        ev.preventDefault();
    }
}
form.addEventListener("submit", check_on_submit);