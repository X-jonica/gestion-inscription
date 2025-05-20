<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
$admin = $_SESSION['admin'];
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/models/Candidat.php';
require_once __DIR__ . '/../../src/models/Inscriptions.php';
require_once __DIR__ . '/../../src/models/Concours.php';

// Récupérer toutes les inscriptions validées
$inscriptionsValides = Inscription::search('', 'validé');

// Récupérer les candidats correspondants
$candidatsInscrits = [];
foreach ($inscriptionsValides as $inscription) {
    $candidat = Candidat::getById($inscription['candidat_id']);
    if ($candidat) {
        $candidatsInscrits[] = $candidat;
    }
}

// Récupérer tous les candidats (pour comparaison)
$tousLesCandidats = Candidat::getAll($pdo);
$inscriptions = Inscription::getAll($pdo);
$concours = Concours::getAll($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-brand">
                <span class="sidebar-icon">📊</span> Admin Dashboard
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard.php">
                    <span class="sidebar-icon">🏠</span> Accueil
                </a>
                <a href="list_candidat.php">
                    <span class="sidebar-icon">👥</span> Candidats
                </a>
                <a href="list_inscriptions.php">
                    <span class="sidebar-icon">📝</span> Inscriptions
                </a>
                <a href="list_concours.php">
                    <span class="sidebar-icon">🏆</span> Concours
                </a>
                <a href="logout.php" id="logoutLink">
                    <span class="sidebar-icon">🚪</span> Déconnexion
                </a>
            </nav>
        </div>
        
        <!-- Content area -->
        <div class="main-content">
            <h2 class="welcome-header">Bienvenue, vous etes connecté en tant qu'Administrateur 😎 , <strong><?= htmlspecialchars($admin['nom']) ?></strong></h2>
            
            <div class="row g-4 ">  
                <!-- Card Candidats -->
                <div class="col-md-4">
                    <div class="card dashboard-card h-100 border-0 shadow-sm">  
                        <div class="card-body text-center p-4 d-flex flex-column">  
                            <h5 class="card-title text-primary mb-3">
                                Candidats Inscrits
                            </h5>
                            <div class="card-count display-5 fw-bold text-dark mb-3"><?= count($candidatsInscrits) ?></div>  
                            <small class="text-muted">sur <?= count($tousLesCandidats) ?> candidats au total</small>
                            <a href="list_candidat.php" class="btn btn-primary mt-auto align-self-center">  
                                Voir la liste  
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Inscriptions -->
                <div class="col-md-4">
                    <div class="card dashboard-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4 d-flex flex-column">
                            <h5 class="card-title text-success mb-3">
                                Inscriptions
                            </h5>
                            <div class="card-count display-5 fw-bold text-dark mb-3"><?= count($inscriptions) ?></div>
                            <a href="list_inscriptions.php" class="btn btn-success mt-auto align-self-center">
                                 Voir la liste
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Concours -->
                <div class="col-md-4">
                    <div class="card dashboard-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4 d-flex flex-column">
                            <h5 class="card-title text-info mb-3">
                                Concours
                            </h5>
                            <div class="card-count display-5 fw-bold text-dark mb-3"><?= count($concours) ?></div>
                            <a href="list_concours.php" class="btn btn-info mt-auto align-self-center">
                                Voir la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/dashboard.js"></script>
    
</body>
</html>