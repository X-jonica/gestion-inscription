<?php

// On inclut la configuration de la base de données
require_once __DIR__ . '/../config/database.php';

class Candidat {

    // Propriétés représentant les colonnes de la table 'Candidats'
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $telephone;
    private $type_bacc;
    private $annee_bacc;
    private $recu_paiement;
    private $password_hash;

    // Constructeur pour initialiser les valeurs
    public function __construct($id = null, $nom = null, $prenom = null, $email = null, $telephone = null, 
                                $type_bacc = null, $annee_bacc = null, $recu_paiement = false, $password_hash = null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->type_bacc = $type_bacc;
        $this->annee_bacc = $annee_bacc;
        $this->recu_paiement = $recu_paiement;
        $this->password_hash = $password_hash;
    }

    // Méthode pour récupérer tous les candidats
    public static function getAll() {
        $db = Database::getConnection();
        $sql = 'SELECT * FROM Candidats';
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // Méthode pour récupérer un candidat par son ID
    public static function getById($id) {
        $db = Database::getConnection();
        $sql = 'SELECT * FROM Candidats WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    // Méthode pour ajouter un nouveau candidat
    public function add() {
        $db = Database::getConnection();
        $sql = 'INSERT INTO Candidats (nom, prenom, email, telephone, type_bacc, annee_bacc, recu_paiement, password_hash) 
                VALUES (:nom, :prenom, :email, :telephone, :type_bacc, :annee_bacc, :recu_paiement, :password_hash)';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'type_bacc' => $this->type_bacc,
            'annee_bacc' => $this->annee_bacc,
            'recu_paiement' => $this->recu_paiement,
            'password_hash' => $this->password_hash
        ]);
    }

    // Méthode pour mettre à jour un candidat
    public function update() {
        $db = Database::getConnection();
        $sql = 'UPDATE Candidats SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone, 
                type_bacc = :type_bacc, annee_bacc = :annee_bacc, recu_paiement = :recu_paiement, 
                password_hash = :password_hash WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'type_bacc' => $this->type_bacc,
            'annee_bacc' => $this->annee_bacc,
            'recu_paiement' => $this->recu_paiement,
            'password_hash' => $this->password_hash
        ]);
    }

    // Méthode pour supprimer un candidat
    public static function delete($id) {
        $db = Database::getConnection();
        $sql = 'DELETE FROM Candidats WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
?>
