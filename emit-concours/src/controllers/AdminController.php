<?php
session_start();
require_once __DIR__ . '/../models/Admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $motDePasse = $_POST['mot_de_passe'] ?? '';

    $admin = Admin::trouverParEmail($email);

    if (!$admin) {
        header('Location: ../../public/admin/login.php?erreur=Email non trouvé');
        exit;
    }

    if (!password_verify($motDePasse, $admin['mot_de_passe'])) {
        header('Location: ../../public/admin/login.php?erreur=Mot de passe incorrect');
        exit;
    }

    $_SESSION['admin'] = [
        'id' => $admin['id'],
        'nom' => $admin['nom'],
        'email' => $admin['email']
    ];

    header('Location: ../../public/admin/dashboard.php');
    exit;
}
