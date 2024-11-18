let edit_buttons = document.querySelectorAll(".edit-button");
let forms = document.querySelectorAll(".comment form");

let form_template = document.querySelector("#new-comment-form");
form_template.addEventListener("submit", handle_empty_form);

// replaces paragraph in comment with a form to edit the comment
function update_to_form(event) {
    let comment_div = event.target.closest(".comment");
    let comment_paragraph = comment_div.querySelector("p");
    let action_buttons = event.target.closest("div");

    action_buttons.classList.add("hidden");
    comment_paragraph.classList.add("hidden");

    let edit_form = form_template.cloneNode(true);
    edit_form.querySelector("textarea").value = comment_paragraph.textContent;
    comment_div.appendChild(edit_form);
    edit_form.addEventListener("submit", submit_comment_edit);
}

// checks and handles if form submits an empty textarea (empty comment)
function handle_empty_form(event) {
    let textarea = event.target.querySelector("textarea");
    let error_field = event.target.querySelector("span");
    let empty = textarea.value.length < 1;

    if (empty) {
        error_field.classList.remove("hidden");
        event.preventDefault();
    }

    return !empty;
}

// changes comment back to normal if form passes
function submit_comment_edit(event) {
    let comment_div = event.target.closest(".comment");
    let comment_paragraph = comment_div.querySelector("p");
    let action_buttons = event.target.closest("div");

    let success = handle_empty_form(event);
    if (success) {
        action_buttons.classList.remove("hidden");
        comment_paragraph.classList.remove("hidden");
        form.remove();
    }
}

// adding event to trigger edit when edit button clicked
edit_buttons.forEach(edit_button => {
    edit_button.addEventListener("click", update_to_form);
})

