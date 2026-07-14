<?php 
class logIn{
    // Se crea la intancia por primera vez y se 
    // le asigna null por defecto
    private static $instance = null;

    // __construct es una palabra reservada de php y se ejecuta automáticamente esta función
    // Si el estado de la sessión es NONE, entonces se crea una nueva sesión.
    private function __construct(){
        if (session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }

    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new logIn();
        }
        return self::$instance; // Usamos instance porque solo podemos acceder a el solo a traves   
                                // de la clase
    }

    // Guardar los datos del usuario
    public function setUser($data){
        $_SESSION["data"] = $data;
    }

    // Obtener los datos del usuario
    public function getUser(){
        // Devuelve null si es que $_SESSION['data'] no existe
        // $_SESSION['data'] es un arreglo asociativo
        return $_SESSION["data"] ?? null;
    }

    public function logOut(){
        session_unset();
        session_destroy();
        self::$instance = null;
    }
}

function verificarLogIn(){
    $session = logIn::getInstance();
    if ($session->getUser() == null){
        header("Location:index.php?message=Necesitas_Iniciar_Sesion");
        exit();
    }
}

?>