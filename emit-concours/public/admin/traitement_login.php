<?php
// traitement_login.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/models/Admin.php';

// Initialise la connexion
$db = new Database();
$pdo = $db->getConnection(); 

// Vérifie que le formulaire a bien été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    // Requête à la base pour chercher l'admin avec l'email et la connexion PDO
    $admin = Admin::trouverParEmail($pdo, $email);  // Passer ici $pdo et $email

    // Vérifie si l'admin existe et si le mot de passe est correct (sans hachage)
    if ($admin && $mot_de_passe == $admin['mot_de_passe']) {
        // Authentification réussie
        $_SESSION['admin'] = $admin;
        header('Location: dashboard.php');
        exit;
    } else {
        // Échec de connexion
        $erreur = 'Email ou mot de passe incorrect.';
        header('Location: login.php?erreur=' . urlencode($erreur));
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}
?>
