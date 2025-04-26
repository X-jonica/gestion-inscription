<?php
require_once __DIR__ . '/../models/Candidat.php';
require_once __DIR__ . '/../models/Concours.php';
require_once __DIR__ . '/../config/database.php'; 

class InscriptionController {
    public function inscrire($data) {
        // Vérification email
        if (Candidat::emailExiste($data['email'])) {
            throw new Exception("Email déjà utilisé");
        }

        // Vérification reçu
        if (!$data['recu_paiement']) {
            throw new Exception("Reçu de paiement obligatoire");
        }

        // Initialisation de la connexion
        $database = new Database(); // Utilisation directe de votre classe Database
        $db = $database->getConnection();
        
        // Transaction
        $db->beginTransaction();

        try {
            // Création candidat
            Candidat::create($data);
            $candidat_id = $db->lastInsertId();

            // Création inscription
            Concours::addInscription($candidat_id, $data['concours_id']);

            $db->commit();
            return "Inscription réussie !";
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}