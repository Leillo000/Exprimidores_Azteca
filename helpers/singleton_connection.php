<?php
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=exprimidores_azteca", "root", "");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getDatabase()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function doSearch($tabla, $palabraABuscar, $fechaDesde, $fechaHasta,  $columnas = [])
    {
        $query = "SELECT * FROM {$tabla} WHERE ( ";
        for ($i = 0; $i <= (count($columnas) - 1); $i++) {
            $query .= "{$columnas[$i]} LIKE '%{$palabraABuscar}%' OR ";
        }
        $query = substr_replace($query, "", -4);
        $query .= ")";

        if (!empty($fechaDesde)) {
            $query .= " AND (fecha BETWEEN '{$fechaDesde}' AND '{$fechaHasta}')";
        }

        $queryCount = str_replace("*", "COUNT(*) as total", $query);

        return [
            "query" => $query,
            "query_count" => $queryCount
        ];
    }

    public function doQuery($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>