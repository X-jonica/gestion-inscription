<?php

require_once __DIR__ . '/../config/Database.php'; 

class Concours {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function addInscription($candidatId, $concoursId) {
        $db = new Database();
        $conn = $db->getConnection();
    
        $sql = "INSERT INTO Inscriptions (candidat_id, concours_id) VALUES (:candidat_id, :concours_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':candidat_id', $candidatId);
        $stmt->bindParam(':concours_id', $concoursId);
        $stmt->execute();
    }

    public function getConcoursOuverts() {
        $query = "SELECT * FROM concours ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM concours");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
