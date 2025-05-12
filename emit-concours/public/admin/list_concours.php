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
    <link rel="stylesheet" href="../assets/css/list_concours.css">
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

    <!-- importer les fichiers js -->
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/list_concours.js"></script>
</body>
</html>