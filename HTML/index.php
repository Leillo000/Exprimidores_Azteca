<?php

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exprimidores Azteca - Iniciar sesión</title>
    <link rel="stylesheet" href="http://192.168.1.128/Codigo_Exprimidores_Azteca/assets/CSS/session.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&family=Annapurna+SIL:wght@400;700&family=Arimo:ital,wght@0,400..700;1,400..700&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Epunda+Sans:ital,wght@0,300..900;1,300..900&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../Images/logo_icon.ico" rel="icon" type="image/x-icon">
</head>

<body>
        <form action="submit">
            <h1>¡Bienvenido/a a </h1>
            <h1>
                Exprimidores Azteca!
            </h1>
            <div class="container_session">
                <div class="center_items_session">
                    <h2>Iniciar sesión</h2>
                    <label for="email">Correo electrónico</label>
                    <input type="text" name="email" id="email" autocomplete="on">
                    <label for="password">Contraseña</label>
                    <input type="text" name="password" id="password" autocomplete="on">
                    <button class="button" type="submit"> <span>Iniciar sesión </span></button>
                    <br>
                    <a href="registrarse.php">¿Aún no te has registrado? Regístrate ahora</a>
                </div>
            </div>
        </form>
</body>

</html>