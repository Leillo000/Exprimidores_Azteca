<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exprimidores Azteca - Registrarse</title>
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/assets/CSS/session.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&family=Annapurna+SIL:wght@400;700&family=Arimo:ital,wght@0,400..700;1,400..700&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Epunda+Sans:ital,wght@0,300..900;1,300..900&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../Images/logo_icon.ico" rel="icon" type="image/x-icon">
</head>

<body>
    <form method="post" action="" id="signInForm">
        <h1>Empieza fácil con</h1>
        <h1>
            Exprimidores Azteca
        </h1>
        <div class="container_session">
            <div class="center_items_session">
                <h2>Registrarse</h2>
                <label for="email">Correo electrónico</label>
                <input type="text" name="email" id="email" autocomplete="on">
                <label class="wrong_login" for="" id="wrongEmail" hidden></label>
                <label for="password">Contraseña</label>
                <input type="text" name="_password" id="password" autocomplete="on">
                <label class="wrong_login" for="" id="wrongPassword" hidden></label>
                <button class="button" type="submit"> <span>Registrarse </span></button>
                <br>
                <a href="index.php">¿Ya tienes una cuenta? Iniciar sesión</a>
            </div>
        </div>
    </form>
</body>
<script>
    const wrongEmail = document.getElementById('wrongEmail');
    const wrongPassword = document.getElementById('wrongPassword');
    const url = "../controllers/PHP/sign_in.php"
    document.getElementById('signInForm').addEventListener("submit", async (e) => {
        e.preventDefault();
        formData = new FormData((e).target);
        const response = await fetch(url, {
            method: "POST",
            body: formData
        });
        const result = await response.json();
        switch (result.RESULT) {
            case "invalid_domain":
                wrongEmail.textContent = "Dominio inválido";
                wrongEmail.hidden = false;
                break;
            case "weak_password":
                wrongPassword.textContent = "Contraseña muy débil";
                wrongPassword.hidden = false;
                break;
            case "email_in_use":
                wrongEmail.textContent = "Este correo ya está en uso";
                wrongEmail.hidden = false;
                break;
            case "success_sign_in":
                alert("Te has registrado correctamente");
                window.location.href = "index.php";
                break;
        }

    })
</script>

</html>