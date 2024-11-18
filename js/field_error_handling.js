// displaying issues recorded in the form
function error_handler() {
    this.submit_button = document.querySelector("input[type=submit]");
    this.error_field = document.querySelector("#error-hint");
    this.detected_issues = new Array();

    this.has_issues = function () {
        return this.detected_issues.length > 0;
    };

    this.process_hint_messages = function () {
        if (!this.detected_issues.length) {
            this.error_field.classList.add("hidden");
            this.submit_button.enabled = true;
            this.submit_button.disabled = false;
        } else {
            this.error_field.classList.remove("hidden");
            this.submit_button.enabled = false;
            this.submit_button.disabled = true;
    
            this.error_field.innerHTML = "";
            this.detected_issues.forEach(issue => {
                this.error_field.innerHTML += issue + "<br>";
            });
        }
    };
    this.run_issue = function (condition, issue) {
        let has_issue_recorded = this.detected_issues.includes(issue);
    
        if (condition) {    
            if (!has_issue_recorded) {
                this.detected_issues.push(issue);
            }
        } else {
            if (has_issue_recorded) {
                this.detected_issues = this.detected_issues.filter(element => element != issue);
            }
        }
    };
    this.errorify_element = function (element, possible_issues) {
        if (!Array.isArray(possible_issues)) {
            possible_issues = [possible_issues];
        }
    
        let any_issue_found = false;
        for (let issue of possible_issues) {
            if (this.detected_issues.includes(issue)) {
                any_issue_found = true;
                break;
            }
        }
    
        if (any_issue_found) {
            element.classList.add("error");
        } else {
            element.classList.remove("error");
        }
    };
    this.general_empty_field_check = function (element, message_prefix) {
        let error_message = message_prefix + " can not be empty";
        this.run_issue(element.value.length < 1, error_message);
        this.errorify_element(element, error_message);
        this.process_hint_messages();
    };
    this.on_blur_check_required = function (field_dict) {
        for (let [prefix, field] of Object.entries(field_dict)) {
            field.addEventListener("blur", () => {
                this.general_empty_field_check(field, prefix);
            });
        }
    };
    this.check_required_fields = function (field_dict) {
        for (let [prefix, field] of Object.entries(field_dict)) {
            this.general_empty_field_check(field, prefix);
        }
    };
}
