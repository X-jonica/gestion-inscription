<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/models/Inscriptions.php';
require_once __DIR__ . '/../../src/models/Candidat.php';
require_once __DIR__ . '/../../src/models/Concours.php';

// Récupérer toutes les inscriptions avec les infos des candidats et concours
$inscriptions = Inscription::getAllWithDetails($pdo);

// Traitement de la mise à jour du statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $inscriptionId = $_POST['inscription_id'];
    $newStatus = $_POST['statut'];
    
    // Mettre à jour le statut dans la base de données
    $db = Database::getConnection();
    $sql = 'UPDATE Inscriptions SET statut = :statut WHERE id = :id';
    $stmt = $db->prepare($sql);
    $stmt->execute(['statut' => $newStatus, 'id' => $inscriptionId]);
    
    header('Location: list_inscriptions.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Inscriptions</title>
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
        
        .badge-attente {
            background-color: #ffc107;
            color: #212529;
        }
        
        .badge-valide {
            background-color: #28a745;
            color: white;
        }
        
        .badge-rejete {
            background-color: #dc3545;
            color: white;
        }
        
        .action-btns {
            white-space: nowrap;
        }
        
        /* Style pour le modal */
        .modal-detail-item {
            margin-bottom: 1rem;
        }
        .modal-detail-label {
            font-weight: 600;
            color: #6c757d;
        }
        .form-control:disabled {
            background-color: #f8f9fa;
            border-color: #e9ecef;
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
            <h1 class="page-header">Liste des Inscriptions</h1>
            
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Candidat</th>
                                <th>Concours</th>
                                <th>Date Inscription</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscriptions as $inscription): ?>
                            <tr>
                                <td><?= $inscription['id'] ?></td>
                                <td>
                                    <?= htmlspecialchars($inscription['candidat_nom']) ?> 
                                    <?= htmlspecialchars($inscription['candidat_prenom']) ?>
                                </td>
                                <td><?= htmlspecialchars($inscription['concours_mention']) ?></td>
                                <td><?= htmlspecialchars($inscription['date_inscription']) ?></td>
                                <td>
                                    <?php 
                                    $badgeClass = '';
                                    switch($inscription['statut']) {
                                        case 'validé': $badgeClass = 'badge-valide'; break;
                                        case 'rejeté': $badgeClass = 'badge-rejete'; break;
                                        default : $badgeClass = 'badge-attente';
                                    }
                                    ?>
                                    <span class="badge badge-statut <?= $badgeClass ?>">
                                        <?= htmlspecialchars($inscription['statut']) ?>
                                    </span>
                                </td>
                                <td class="action-btns">
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                            data-bs-target="#inscriptionModal" 
                                            data-id="<?= $inscription['id'] ?>"
                                            data-candidat="<?= htmlspecialchars($inscription['candidat_nom'].' '.$inscription['candidat_prenom']) ?>"
                                            data-concours="<?= htmlspecialchars($inscription['concours_mention']) ?>"
                                            data-date="<?= htmlspecialchars($inscription['date_inscription']) ?>"
                                            data-statut="<?= htmlspecialchars($inscription['statut']) ?>">
                                        Modifier
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

    <!-- Modal pour modifier le statut -->
    <div class="modal fade" id="inscriptionModal" tabindex="-1" aria-labelledby="inscriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="list_inscriptions.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="inscriptionModalLabel">Modifier le statut</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="inscription_id" id="modal-inscription-id">
                        <input type="hidden" name="update_status" value="1">
                        
                        <div class="mb-3">
                            <label class="form-label modal-detail-label">Candidat</label>
                            <input type="text" class="form-control" id="modal-candidat" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label modal-detail-label">Concours</label>
                            <input type="text" class="form-control" id="modal-concours" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label modal-detail-label">Date Inscription</label>
                            <input type="text" class="form-control" id="modal-date" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label for="modal-statut" class="form-label modal-detail-label">Statut</label>
                            <select class="form-select" id="modal-statut" name="statut">
                                <option value="en attente">En attente</option>
                                <option value="validé">Validé</option>
                                <option value="rejeté">Rejeté</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script pour remplir le modal avec les données de l'inscription
        document.addEventListener('DOMContentLoaded', function() {
            var inscriptionModal = document.getElementById('inscriptionModal');
            inscriptionModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                
                // Remplir les champs du formulaire
                document.getElementById('modal-inscription-id').value = button.getAttribute('data-id');
                document.getElementById('modal-candidat').value = button.getAttribute('data-candidat');
                document.getElementById('modal-concours').value = button.getAttribute('data-concours');
                document.getElementById('modal-date').value = button.getAttribute('data-date');
                
                // Sélectionner le statut actuel
                var currentStatus = button.getAttribute('data-statut');
                var statusSelect = document.getElementById('modal-statut');
                for (var i = 0; i < statusSelect.options.length; i++) {
                    if (statusSelect.options[i].value === currentStatus) {
                        statusSelect.selectedIndex = i;
                        break;
                    }
                }
                
                // Mettre à jour le titre du modal
                document.getElementById('inscriptionModalLabel').textContent = 
                    'Modifier inscription #' + button.getAttribute('data-id');
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