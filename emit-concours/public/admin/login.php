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
    <style>
        body {
            background-color: #f3f3f3;
            font-family: Arial, sans-serif;
        }
        .login-container {
            width: 400px;
            margin: 100px auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .buttons-container {
            display: flex;
            gap: 10px;
            margin-top: 1rem;
        }
        .login-button {
            background: #007BFF;
            color: white;
            padding: 0.8rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            flex: 1;
            text-align: center;
        }
        .back-button {
            background: #6c757d;
            color: white;
            padding: 0.8rem;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
            flex: 1;
            transition: background 0.3s;
        }
        .back-button:hover {
            background: #5a6268;
        }
        .error {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
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
</body>
</html>