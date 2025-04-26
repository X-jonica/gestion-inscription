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

$candidat = Candidat::getAll($pdo);
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
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            width: 250px;
            background: #343a40;
            color: white;
            position: fixed;
            height: 100vh;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-nav a {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            display: block;
            text-decoration: none;
        }
        
        .sidebar-nav a:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        
        .welcome-header {
            color: #495057;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .dashboard-card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            transition: all 0.2s;
            margin-bottom: 1.5rem;
        }
        
        .dashboard-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        
        .card-count {
            font-size: 1.75rem;
            font-weight: 600;
            color: #343a40;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-brand">Admin Dashboard</div>
            <nav class="sidebar-nav">
                <a href="dashboard.php">Accueil</a>
                <a href="list_candidat.php">Candidats</a>
                <a href="list_inscriptions.php">Inscriptions</a>
                <a href="list_concours.php">Concours</a>
                <a href="logout.php" id="logoutLink">Déconnexion</a>
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
                                Candidats 
                            </h5>
                            <div class="card-count display-5 fw-bold text-dark mb-3"><?= count($candidat) ?></div>  
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

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js">
        document.getElementById('logoutLink').addEventListener('click', function(e) {
            e.preventDefault();
            const confirmLogout = confirm("Voulez-vous vraiment vous déconnecter ?");
            if (confirmLogout) {
                window.location.href = "logout.php";
            }
        });

    </script>
</body>
</html>