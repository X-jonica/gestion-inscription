<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/models/Candidat.php';

// Récupérer le terme de recherche
$searchTerm = $_GET['search'] ?? '';

// Récupérer les candidats avec filtre de recherche
$candidats = Candidat::search($searchTerm);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            width: calc(100% - 250px);
        }
        
        .page-header {
            color: #495057;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .search-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            padding: 0;
            overflow: hidden;
            width: 100%;
        }
        
        .table {
            margin-bottom: 0;
            width: 100%;
        }
        
        .table thead th {
            border-top: none;
            border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
            padding: 1rem;
            font-weight: 600;
            color: #495057;
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }
        
        .badge-payment {
            padding: 0.5em 0.8em;
            font-size: 0.75em;
            font-weight: 600;
            border-radius: 10px;
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
        
        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.875rem;
        }
        
        /* Style pour le modal */
        .modal-detail-item {
            margin-bottom: 1rem;
        }
        .modal-detail-label {
            font-weight: 600;
            color: #6c757d;
        }
        
        /* Style pour la recherche */
        .search-box {
            position: relative;
        }
        
        .search-box .form-control {
            padding-left: 2.5rem;
        }
        
        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-80%);
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
            <h1 class="page-header">
                <span>Liste des Candidats</span>
            </h1>
            
            <!-- Formulaire de recherche -->
            <div class="search-container">
                <form method="GET" class="row g-3">
                    <div class="col-md-12 search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Rechercher par nom ou prénom..." 
                               value="<?= htmlspecialchars($searchTerm) ?>">
                    </div>
                </form>
            </div>
            
            <!-- Tableau des candidats -->
            <div class="table-container mx-auto" style="">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th class="text-nowrap">Type Bac</th>
                                <th class="text-nowrap">Année Bac</th>
                                <th>Paiement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($candidats)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">Aucun candidat trouvé</td>
                                </tr>
                            <?php else: ?>
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
                                            <i class="fas fa-eye btn-sm"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
                    <div class="mb-3">
                        <label class="form-label modal-detail-label">ID</label>
                        <input type="text" class="form-control" id="modal-id" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label modal-detail-label">Nom</label>
                        <input type="text" class="form-control" id="modal-nom" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label modal-detail-label">Prénom</label>
                        <input type="text" class="form-control" id="modal-prenom" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label modal-detail-label">Email</label>
                        <input type="text" class="form-control" id="modal-email" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label modal-detail-label">Téléphone</label>
                        <input type="text" class="form-control" id="modal-telephone" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label modal-detail-label">Type Bac</label>
                        <input type="text" class="form-control" id="modal-type_bacc" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label modal-detail-label">Année Bac</label>
                        <input type="text" class="form-control" id="modal-annee_bacc" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label modal-detail-label">Paiement reçu</label>
                        <input type="text" class="form-control" id="modal-paiement" disabled>
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
                
                // Remplir les champs du formulaire
                document.getElementById('modal-id').value = button.getAttribute('data-id');
                document.getElementById('modal-nom').value = button.getAttribute('data-nom');
                document.getElementById('modal-prenom').value = button.getAttribute('data-prenom');
                document.getElementById('modal-email').value = button.getAttribute('data-email');
                document.getElementById('modal-telephone').value = button.getAttribute('data-telephone');
                document.getElementById('modal-type_bacc').value = button.getAttribute('data-type_bacc');
                document.getElementById('modal-annee_bacc').value = button.getAttribute('data-annee_bacc');
                document.getElementById('modal-paiement').value = button.getAttribute('data-paiement');
                
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