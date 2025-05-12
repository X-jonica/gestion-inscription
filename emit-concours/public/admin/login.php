<?php
session_start();
if (isset($_SESSION['admin'])) {
    header('Location: dashboard.php');
    exit;
}
$erreur = $_GET['erreur'] ?? '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Connexion Admin</h2>
        <?php if ($erreur): ?>
            <div class="error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        <form method="POST" action="traitement_login.php">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            
            <div class="buttons-container">
                <a href="../index.php" class="back-button">Retour</a>
                <input type="submit" value="Se connecter" class="login-button">
            </div>
        </form>
    </div>
    
    <script src="../assets/js/login.js"></script>
</body>
</html>