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

// Récupérer les paramètres de recherche
$searchTerm = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';

// Récupérer les inscriptions avec filtres
$inscriptions = Inscription::search($searchTerm, $statusFilter);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
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
        
        .badge-statut {
            padding: 0.5em 0.8em;
            font-size: 0.75em;
            font-weight: 600;
            border-radius: 10px;
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
        .form-control:disabled {
            background-color: #f8f9fa;
            border-color: #e9ecef;
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
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .status-filter {
            min-width: 180px;
        }
    </style>
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
        
        <!-- Main content -->
        <div class="main-content">
            <h1 class="page-header">
                <span>Liste des Inscriptions</span>
            </h1>
            
            <!-- Formulaire de recherche et filtre -->
            <div class="search-container">
                <form method="GET" class="row g-3">
                    <div class="col-md-8 search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Rechercher par nom, prénom ou concours..." 
                               value="<?= htmlspecialchars($searchTerm) ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select status-filter">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente" <?= $statusFilter === 'en attente' ? 'selected' : '' ?>>En attente</option>
                            <option value="validé" <?= $statusFilter === 'validé' ? 'selected' : '' ?>>Validé</option>
                            <option value="rejeté" <?= $statusFilter === 'rejeté' ? 'selected' : '' ?>>Rejeté</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Tableau des inscriptions -->
            <div class="table-container">
                <div class="table-responsive ">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Candidat</th>
                                <th>Concours</th>
                                <th class="text-nowrap">Date Inscription</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($inscriptions)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">Aucune inscription trouvée</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($inscriptions as $inscription): ?>
                                <tr>
                                    <td><?= $inscription['id'] ?></td>
                                    <td>
                                        <?= htmlspecialchars($inscription['candidat_nom']) ?> 
                                        <?= htmlspecialchars($inscription['candidat_prenom']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($inscription['concours_mention']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($inscription['date_inscription'])) ?></td>
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
                                            <i class="fas fa-edit"></i> Modifier
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

    <!-- Impoerarion des autres fichiers js  -->
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/list_inscriptions.js" ></script>
</body>
</html>