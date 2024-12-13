let side_menu = document.querySelector("div#side-menu");
let menu_icon = document.querySelector("#category-menu");

let menu_open = false;

function open_menu() {
    if (menu_open) {
        side_menu.style.width = "0";
    } else {
        side_menu.style.width = "10em";
    }
    menu_open = !menu_open;
}

menu_icon.addEventListener("click", open_menu);