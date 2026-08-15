<?php
class logIn
{
    // Se crea la intancia por primera vez y se 
    // le asigna null por defecto
    private static $instance = null;

    // __construct es una palabra reservada de php y se ejecuta automáticamente esta función
    // Si el estado de la sessión es NONE, entonces se crea una nueva sesión.
    private function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,              // dura hasta que se cierre el navegador
                'path' => '/',
                'domain' => 'localhost',      // cámbialo a tu dominio real en producción
                'secure' => true,             // requiere HTTPS
                'httponly' => true,           // evita acceso desde JavaScript
                'samesite' => 'Strict'        // evita envío en peticiones cross-site
            ]);
            session_start();
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new logIn();
        }
        return self::$instance; // Usamos instance porque solo podemos acceder a el solo a traves   
        // de la clase
    }

    // Guardar los datos del usuario
    public function setUser($email)
    {
        $_SESSION["email"] = $email;
    }

    // Obtener los datos del usuario
    public function getUser()
    {
        // Devuelve null si es que $_SESSION['data'] no existe
        // $_SESSION['data'] es un arreglo asociativo
        return $_SESSION["email"] ?? null;
    }

    // public function getRol($email)
    // {
    //     $db = Database::getDatabase();
    //     $db->doQuery("SELECT rol FROM usuarios WHERE email = ? AND access = 1", [$email])
    // }

    public function logOut()
    {
        session_unset();
        session_destroy();
        self::$instance = null;
    }
}

function verificarLogIn()
{
    $session = logIn::getInstance();
    if ($session->getUser() == null) {
        header("Location:index.php?message=necesitas_iniciar_sesion");
        exit();
    }
}

?>