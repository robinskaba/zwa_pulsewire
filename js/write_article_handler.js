let form = document.querySelector("form");
let fileUpload = document.querySelector("input[type=file]");
let labelUpload = document.querySelector("#image-upload");
let header_image = document.querySelector("#article-image");

var error_handler = new error_handler();

let original_label_text = labelUpload.textContent;

let required_fields = {
  "Title": document.querySelector("#article-title"),
  "Summary": document.querySelector("#article-summary"),
  "Body of article": document.querySelector("#article-body"),
  "Header image": header_image
};
error_handler.on_blur_check_required(required_fields);
header_image.addEventListener("change", () => {
    error_handler.general_empty_field_check(header_image, "Header image");
});

function changeLabelText() {
    if (fileUpload.files.length > 0) {
        labelUpload.textContent = "Uploaded: " + fileUpload.files[0].name;
      } else {
        labelUpload.textContent = original_label_text;
      }
}

fileUpload.addEventListener("change", changeLabelText);

function check_on_submit(ev) {
  // total check on submit

  error_handler.check_required_fields(required_fields);

  if (error_handler.has_issues()) {
      ev.preventDefault();
  }
}
form.addEventListener("submit", check_on_submit);