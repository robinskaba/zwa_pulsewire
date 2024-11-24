var error_handler = new error_handler();

let form = document.querySelector("form");

// ENFORCING REQUIRED FIELDS
let required_fields = {
    "Username": form.querySelector("input[name=username]"),
    "Password": form.querySelector("input[name=password]")
};
error_handler.on_blur_check_required(required_fields);

function check_on_submit(ev) {
    // total check on submit
  
    error_handler.check_required_fields(required_fields);
  
    if (error_handler.has_issues()) {
        ev.preventDefault();
    }
}
form.addEventListener("submit", check_on_submit);