let form = document.querySelector("form");
let fileUpload = document.querySelector("input[type=file]");
let uploadDesc = document.querySelector("#image-upload > span");

var error_handler = new error_handler();

let original_label_text = uploadDesc.textContent;

let required_fields = {
  "Title": document.querySelector("#article-title"),
  "Summary": document.querySelector("#article-summary"),
  "Body of article": document.querySelector("#article-body")
};
error_handler.on_blur_check_required(required_fields);
fileUpload.addEventListener("change", () => {
    error_handler.general_empty_field_check(fileUpload, "Header image");
});

function changeLabelText() {
    if (fileUpload.files.length > 0) {
      uploadDesc.textContent = "Uploaded: " + fileUpload.files[0].name;
    } else {
      uploadDesc.textContent = original_label_text;
    }
}

fileUpload.addEventListener("change", changeLabelText);

function check_on_submit(ev) {
  error_handler.check_required_fields(required_fields);
  error_handler.general_empty_field_check(fileUpload, "Header image");

  if (error_handler.has_issues()) {
      ev.preventDefault();
  }
}
form.addEventListener("submit", check_on_submit);