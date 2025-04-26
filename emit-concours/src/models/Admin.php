<?php
require_once __DIR__ . '/../config/database.php';

class Admin {
    public static function trouverParEmail($pdo, $email) {
        $sql = "SELECT * FROM admin WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
