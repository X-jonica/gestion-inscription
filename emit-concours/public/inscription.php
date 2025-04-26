<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - EMIT Concours</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --success-color: #2ecc71;
            --error-color: #e74c3c;
            --light-gray: #f5f5f5;
            --dark-gray: #333;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-gray);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 600px;
        }

        .form-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            margin-bottom: 20px;
        }

        h1 {
            color: var(--dark-gray);
            text-align: center;
            margin-bottom: 25px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-gray);
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: border 0.3s;
        }

        input:focus, select:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .radio-group {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: var(--secondary-color);
        }

        .message {
            padding: 12px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            background-color: rgba(46, 204, 113, 0.2);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .error {
            background-color: rgba(231, 76, 60, 0.2);
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <h1>Inscription au Concours EMIT</h1>

            <?php
            //  Activation des erreurs
             ini_set('display_errors', 1);
             ini_set('display_startup_errors', 1);
             error_reporting(E_ALL);
             
             // DEBUG: Affiche le chemin recherché
             $dbPath = realpath(__DIR__ . '/../../src/config/database.php');
             if (!$dbPath) {
                 die("Erreur: Impossible de trouver database.php. Chemin essayé: " . __DIR__ . '/../../src/config/database.php');
             }
             
             // Chemins ABSOLUS corrigés
             require_once $dbPath;
             require_once __DIR__ . '/../../src/models/Inscription.php';
             require_once __DIR__ . '/../../src/models/Candidat.php';
             require_once __DIR__ . '/../../src/models/Concours.php';

            $emailError = '';
            $successMessage = '';

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $db = Database::getConnection();
                
                // Validation des données
                $nom = htmlspecialchars($_POST['nom']);
                $prenom = htmlspecialchars($_POST['prenom']);
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $telephone = htmlspecialchars($_POST['telephone']);
                $type_bacc = htmlspecialchars($_POST['type_bacc']);
                $annee_bacc = intval($_POST['annee_bacc']);
                $recu_paiement = isset($_POST['recu_paiement']) ? 1 : 0;
                $concours_id = intval($_POST['concours_id']);

                // Vérification de l'email existant
                $candidat = new Candidat();
                if ($candidat->emailExiste($email)) {
                    $emailError = "Cet email est déjà utilisé pour une inscription.";
                } else {
                    try {
                        // Création du candidat
                        $candidatId = $candidat->create([
                            'nom' => $nom,
                            'prenom' => $prenom,
                            'email' => $email,
                            'telephone' => $telephone,
                            'type_bacc' => $type_bacc,
                            'annee_bacc' => $annee_bacc,
                            'recu_paiement' => $recu_paiement
                        ]);

                        // Création de l'inscription
                        $inscription = new Inscription(
                            null,
                            $candidatId,
                            $concours_id,
                            date('Y-m-d H:i:s'),
                            'en_attente'
                        );
                        $inscription->add();

                        $successMessage = "Votre inscription a été enregistrée avec succès!";
                    } catch (Exception $e) {
                        $emailError = "Une erreur est survenue: " . $e->getMessage();
                    }
                }
            }

            // Récupération des concours ouverts
            $concoursModel = new Concours();
            $concours = $concoursModel->getAll();
            ?>

            <?php if ($successMessage): ?>
                <div class="message success"><?= $successMessage ?></div>
            <?php endif; ?>

            <?php if ($emailError): ?>
                <div class="message error"><?= $emailError ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required>
                </div>

                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" required>
                </div>

                <div class="form-group">
                    <label for="type_bacc">Type de Baccalauréat</label>
                    <select id="type_bacc" name="type_bacc" required>
                        <option value="">-- Sélectionnez --</option>
                        <option value="Scientifique">Scientifique</option>
                        <option value="Littéraire">Littéraire</option>
                        <option value="Technique">Technique</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="annee_bacc">Année d'obtention</label>
                    <input type="number" id="annee_bacc" name="annee_bacc" min="2000" max="<?= date('Y') ?>" required>
                </div>

                <div class="form-group">
                    <label>Concours choisi</label>
                    <select name="concours_id" required>
                        <option value="">-- Sélectionnez un concours --</option>
                        <?php foreach ($concours as $c): ?>
                            <option value="<?= $c['id'] ?>">
                                <?= htmlspecialchars($c['mention']) ?> (<?= date('d/m/Y', strtotime($c['date_concours'])) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="recu_paiement" value="1">
                        J'ai payé les frais de concours
                    </label>
                </div>

                <button type="submit">Soumettre l'inscription</button>
            </form>

            <a href="index.php" class="back-link">← Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>