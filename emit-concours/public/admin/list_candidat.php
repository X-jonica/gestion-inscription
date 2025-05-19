<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Inclure les deux classes nécessaires
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/models/Candidat.php';
require_once __DIR__ . '/../../src/models/Inscriptions.php';

// Récupérer le terme de recherche
$searchTerm = $_GET['search'] ?? '';

// 1. Récupérer toutes les inscriptions validées
$inscriptionsValides = Inscription::search('', 'validé');

// 2. Récupérer les candidats correspondants
$candidatsValides = [];
foreach ($inscriptionsValides as $inscription) {
    $candidat = Candidat::getById($inscription['candidat_id']);
    if ($candidat) {
        // Ajouter le statut de l'inscription au candidat si besoin
        $candidat['statut_inscription'] = $inscription['statut'];
        $candidatsValides[] = $candidat;
    }
}

// 3. Filtrer selon le terme de recherche
if (!empty($searchTerm)) {
    $candidatsValides = array_filter($candidatsValides, function($candidat) use ($searchTerm) {
        return stripos($candidat['nom'], $searchTerm) !== false || 
               stripos($candidat['prenom'], $searchTerm) !== false || 
               stripos($candidat['email'], $searchTerm) !== false;
    });
}

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
    <link rel="stylesheet" href="../assets/css/list_candidat.css">
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
            
            <!-- Message d'information -->
            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle me-2"></i>
                Seuls les candidats dont l'inscription a été validée et qui participeront au concours sont affichés ici.
            </div>
            
            <!-- Formulaire de recherche -->
            <div class="search-container">
                <form method="GET" class="row g-3">
                    <div class="col-md-12 search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" class="form-control" 
                            placeholder="Rechercher par nom, prénom ou email..." 
                            value="<?= htmlspecialchars($searchTerm) ?>">
                    </div>
                </form>
            </div>
            
            <!-- Tableau des candidats -->
            <div class="table-container" style="">
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
                            <?php if (empty($candidatsValides)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">Aucun candidat validé trouvé</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($candidatsValides as $candidat): ?>
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
                                            <i class="fas fa-eye btn-sm">voir</i>
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

    <!-- lient vers notre modal js de recuperation dans list_candidat.js  -->
    <script src="../assets/js/list_candidat.js"></script>
</body>
</html>