
const checkboxes = document.querySelectorAll("td input[type='checkbox']");
checkboxes.forEach(chk => {
    if (chk.value == 1) {
        chk.checked = true
    }
})

const filas = document.querySelectorAll("table tr");
filas.forEach(fila => {
    const usuario = fila.querySelectorAll("td")
    usuario.forEach(rol => {
        if (rol.textContent == "2") {
            rol.textContent = "Trabajador"
        }
    })
})

async function cambiarAcceso(id, access) {
    formData = new FormData;
    formData.append("user_id", id);
    formData.append("access", access);
    const url = "../controllers/PHP/usuarios.php";
    response = await fetch(url, {
        method: "POST",
        body: formData
    }).then(result => result.json())
    .then(resJson => console.log(resJson))
}

console.log("helo")