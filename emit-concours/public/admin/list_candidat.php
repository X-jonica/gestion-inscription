<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/models/Candidat.php';

// Récupérer tous les candidats
$candidats = Candidat::getAll($pdo);

// Traitement de la suppression
if (isset($_GET['delete'])) {
    Candidat::delete($_GET['delete']);
    header('Location: list_candidats.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Candidats</title>
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
        
        .badge-payment {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
        }
        
        .badge-yes {
            background-color: #28a745;
            color: white;
        }
        
        .badge-no {
            background-color: #dc3545;
            color: white;
        }
        
        .action-btns {
            white-space: nowrap;
        }
        
        /* Style pour le modal */
        .modal-detail-item {
            margin-bottom: 0.5rem;
        }
        .modal-detail-label {
            font-weight: 600;
            color: #6c757d;
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
            <h1 class="page-header">Liste des Candidats</h1>
            
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Type_Bac</th>
                                <th>Année_Bac</th>
                                <th>Paiement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidats as $candidat): ?>
                            <tr>
                                <td><?= $candidat['id'] ?></td>
                                <td><?= htmlspecialchars($candidat['nom']) ?></td>
                                <td><?= htmlspecialchars($candidat['prenom']) ?></td>
                                <td><?= htmlspecialchars($candidat['email']) ?></td>
                                <td><?= htmlspecialchars($candidat['telephone']) ?></td>
                                <td><?= htmlspecialchars($candidat['type_bacc']) ?></td>
                                <td><?= htmlspecialchars($candidat['annee_bacc']) ?></td>
                                <td>
                                    <span class="badge badge-payment <?= $candidat['recu_paiement'] ? 'badge-yes' : 'badge-no' ?>">
                                        <?= $candidat['recu_paiement'] ? 'Oui' : 'Non' ?>
                                    </span>
                                </td>
                                <td class="action-btns">
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                            data-bs-target="#candidatModal" 
                                            data-id="<?= $candidat['id'] ?>"
                                            data-nom="<?= htmlspecialchars($candidat['nom']) ?>"
                                            data-prenom="<?= htmlspecialchars($candidat['prenom']) ?>"
                                            data-email="<?= htmlspecialchars($candidat['email']) ?>"
                                            data-telephone="<?= htmlspecialchars($candidat['telephone']) ?>"
                                            data-type_bacc="<?= htmlspecialchars($candidat['type_bacc']) ?>"
                                            data-annee_bacc="<?= htmlspecialchars($candidat['annee_bacc']) ?>"
                                            data-paiement="<?= $candidat['recu_paiement'] ? 'Oui' : 'Non' ?>">
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
    <div class="modal fade" id="candidatModal" tabindex="-1" aria-labelledby="candidatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="candidatModalLabel">Détails du candidat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">ID:</span> 
                        <span id="modal-id"></span>
                    </div>
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Nom:</span> 
                        <span id="modal-nom"></span>
                    </div>
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Prénom:</span> 
                        <span id="modal-prenom"></span>
                    </div>
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Email:</span> 
                        <span id="modal-email"></span>
                    </div>
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Téléphone:</span> 
                        <span id="modal-telephone"></span>
                    </div>
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Type Bac:</span> 
                        <span id="modal-type_bacc"></span>
                    </div>
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Année Bac:</span> 
                        <span id="modal-annee_bacc"></span>
                    </div>
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Paiement reçu:</span> 
                        <span id="modal-paiement"></span>
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
        // Script pour remplir le modal avec les données du candidat
        document.addEventListener('DOMContentLoaded', function() {
            var candidatModal = document.getElementById('candidatModal');
            candidatModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                
                // Récupérer les données des attributs data-*
                document.getElementById('modal-id').textContent = button.getAttribute('data-id');
                document.getElementById('modal-nom').textContent = button.getAttribute('data-nom');
                document.getElementById('modal-prenom').textContent = button.getAttribute('data-prenom');
                document.getElementById('modal-email').textContent = button.getAttribute('data-email');
                document.getElementById('modal-telephone').textContent = button.getAttribute('data-telephone');
                document.getElementById('modal-type_bacc').textContent = button.getAttribute('data-type_bacc');
                document.getElementById('modal-annee_bacc').textContent = button.getAttribute('data-annee_bacc');
                document.getElementById('modal-paiement').textContent = button.getAttribute('data-paiement');
                
                // Mettre à jour le titre du modal
                document.getElementById('candidatModalLabel').textContent = 
                    'Détails: ' + button.getAttribute('data-prenom') + ' ' + button.getAttribute('data-nom');
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