function updateRoleOnServer(ev) {
    let select = ev.target;
    let username = select.closest("li").querySelector("a").textContent;
    console.log(username);
}

let roleSelects = document.querySelectorAll("select");
roleSelects.forEach(roleSelect => {
    roleSelect.onchange = updateRoleOnServer;
})