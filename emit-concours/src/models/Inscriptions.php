<?php

require_once __DIR__ . '/../config/database.php';

class Inscription {

    private $id;
    private $candidat_id;
    private $concours_id;
    private $date_inscription;
    private $statut;

    public function __construct($id = null, $candidat_id = null, $concours_id = null, $date_inscription = null, $statut = 'en_attente') {
        $this->id = $id;
        $this->candidat_id = $candidat_id;
        $this->concours_id = $concours_id;
        $this->date_inscription = $date_inscription;
        $this->statut = $statut;
    }

    public static function getAll() {
        $db = Database::getConnection();
        $sql = 'SELECT * FROM Inscriptions';
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function getById($id) {
        $db = Database::getConnection();
        $sql = 'SELECT * FROM Inscriptions WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function add() {
        $db = Database::getConnection();
        $sql = 'INSERT INTO Inscriptions (candidat_id, concours_id, date_inscription, statut) 
                VALUES (:candidat_id, :concours_id, :date_inscription, :statut)';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'candidat_id' => $this->candidat_id,
            'concours_id' => $this->concours_id,
            'date_inscription' => $this->date_inscription,
            'statut' => $this->statut
        ]);
    }

    public function update() {
        $db = Database::getConnection();
        $sql = 'UPDATE Inscriptions SET candidat_id = :candidat_id, concours_id = :concours_id, 
                date_inscription = :date_inscription, statut = :statut WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'candidat_id' => $this->candidat_id,
            'concours_id' => $this->concours_id,
            'date_inscription' => $this->date_inscription,
            'statut' => $this->statut
        ]);
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $sql = 'DELETE FROM Inscriptions WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

     public static function getAllWithDetails() {
        $db = Database::getConnection();
        $sql = 'SELECT i.*, 
                c.nom as candidat_nom, c.prenom as candidat_prenom,
                co.mention as concours_mention
                FROM Inscriptions i
                JOIN Candidats c ON i.candidat_id = c.id
                JOIN Concours co ON i.concours_id = co.id';
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
