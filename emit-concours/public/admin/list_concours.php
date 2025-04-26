<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/models/Concours.php';

// Récupérer tous les concours
$concours = Concours::getAll($pdo);

// Traitement de la suppression
if (isset($_GET['delete'])) {
    Concours::delete($_GET['delete']);
    header('Location: list_concours.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concours Disponibles</title>
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
        
        .page-header {
            color: #495057;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            padding: 1.5rem;
        }
        
        .table thead th {
            border-top: none;
            border-bottom: 1px solid #dee2e6;
        }
        
        .badge-statut {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 600;
        }
        
        .badge-ouvert {
            background-color: #28a745;
            color: white;
        }
        
        .badge-ferme {
            background-color: #dc3545;
            color: white;
        }
        
        .action-btns {
            white-space: nowrap;
        }
        
        /* Style pour le modal */
        .modal-detail-item {
            margin-bottom: 0.8rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .modal-detail-label {
            font-weight: 600;
            color: #6c757d;
            display: inline-block;
            width: 120px;
        }
        .modal-title {
            color: #4e73df;
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
        
        <!-- Main content -->
        <div class="main-content">
            <h1 class="page-header">Concours Disponibles</h1>
            
            <div class="table-container mx-auto">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Mention</th>
                                <th class="text-nowrap">Date du concours</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($concours as $concour): ?>
                            <tr>
                                <td><?= $concour['id'] ?></td>
                                <td><?= htmlspecialchars($concour['mention']) ?></td>
                                <td><?= htmlspecialchars($concour['date_concours']) ?></td>
                                <td>
                                    <span class="badge badge-statut <?= $concour['statut'] === 'ouvert' ? 'badge-ouvert' : 'badge-ferme' ?>">
                                        <?= htmlspecialchars($concour['statut']) ?>
                                    </span>
                                </td>
                                <td class="action-btns">
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                            data-bs-target="#concoursModal" 
                                            data-id="<?= $concour['id'] ?>"
                                            data-mention="<?= htmlspecialchars($concour['mention']) ?>"
                                            data-date="<?= htmlspecialchars($concour['date_concours']) ?>"
                                            data-statut="<?= htmlspecialchars($concour['statut']) ?>">
                                        Voir
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour afficher les détails -->
    <div class="modal fade" id="concoursModal" tabindex="-1" aria-labelledby="concoursModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="concoursModalLabel">Détails du concours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">ID:</span> 
                        <span id="modal-id"></span>
                    </div>
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Mention:</span> 
                        <span id="modal-mention"></span>
                    </div>
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Date du concours:</span> 
                        <span id="modal-date"></span>
                    </div>
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Statut:</span> 
                        <span id="modal-statut" class="badge"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script pour remplir le modal avec les données du concours
        document.addEventListener('DOMContentLoaded', function() {
            var concoursModal = document.getElementById('concoursModal');
            concoursModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                
                // Récupérer les données des attributs data-*
                document.getElementById('modal-id').textContent = button.getAttribute('data-id');
                document.getElementById('modal-mention').textContent = button.getAttribute('data-mention');
                document.getElementById('modal-date').textContent = button.getAttribute('data-date');
                
                // Gestion du statut avec badge coloré
                var statut = button.getAttribute('data-statut');
                var statutBadge = document.getElementById('modal-statut');
                statutBadge.textContent = statut;
                statutBadge.className = 'badge badge-statut ' + (statut === 'ouvert' ? 'badge-ouvert' : 'badge-ferme');
                
                // Mettre à jour le titre du modal
                document.getElementById('concoursModalLabel').textContent = 
                    'Concours: ' + button.getAttribute('data-mention');
            });
        });

         // deconnexion
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