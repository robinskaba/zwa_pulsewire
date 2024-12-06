function recolor(select, selectedRole) {
    let options = select.querySelectorAll("option");
    options.forEach(option => {
        if(option.value == selectedRole) option.setAttribute("selected", "selected")
        else option.removeAttribute("selected");
    });
}

function updateRoleOnServer(ev) {
    let select = ev.target;
    let role = select.value;
    let username = select.closest("li").querySelector("a").textContent;

    let formData = new FormData();
    formData.append("username", username);
    formData.append("role", role);

    let request = new XMLHttpRequest();
    request.open("POST", "../api/change_role.php", true);
    request.addEventListener("load", () => {
        recolor(select, role);
    });
    request.send(formData);
}

let roleSelects = document.querySelectorAll("select");
roleSelects.forEach(roleSelect => {
    roleSelect.onchange = updateRoleOnServer;
})