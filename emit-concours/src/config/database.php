<?php
class Database {
    private $host = "localhost";
    private $db_name = "gestion_concours";
    private $username = "root";
    private $password = "";

    public $conn;

    // Connexion à la DB
    public static function getConnection() {  // Rendre cette méthode statique
        $db = new Database();  // Créer une instance de la classe Database
        $db->conn = null;
        try {
            $db->conn = new PDO(
                "mysql:host=" . $db->host . ";dbname=" . $db->db_name,
                $db->username,
                $db->password
            );
            $db->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
        return $db->conn;
    }
}


$database = new Database();
$pdo = $database->getConnection();

?>