<?php
/***************************
* CONFIGURATION DE LA BASE DE DONNÉES : Communiquer avec la base de donnée directement ;
***************************/
class Database {
    private $host = "localhost";
    private $db_name = "gestion_concours";
    private $username = "root";
    private $password = "";

    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            error_log("Erreur de connexion DB: " . $e->getMessage());
            throw new Exception("Erreur de connexion à la base de données");
        }
        return $this->conn;
    }
}

/***************************
* MODÈLES : implementation des requetes SQL necessaire a notre inscription 
***************************/
class Candidat {
    public static function emailExiste($email) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT id FROM Candidats WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }

    public static function create($data) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("INSERT INTO Candidats 
            (nom, prenom, email, telephone, type_bacc, annee_bacc, recu_paiement) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['telephone'],
            $data['type_bacc'],
            $data['annee_bacc'],
            $data['recu_paiement']
        ]);
        
        return $db->lastInsertId();
    }
}

class Concours {
    public static function getAll() {
        $db = (new Database())->getConnection();
        $stmt = $db->query("SELECT * FROM Concours WHERE statut = 'ouvert'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class Inscription {
    public static function add($candidat_id, $concours_id) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("INSERT INTO Inscriptions 
            (candidat_id, concours_id, date_inscription, statut) 
            VALUES (?, ?, NOW(), 'en_attente')");
        return $stmt->execute([$candidat_id, $concours_id]);
    }
}

/***************************
* CONTROLEUR : Methode de validation de notre formulaire inscription : 
***************************/
class InscriptionController {
    public function processInscription($data) {
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['email']) || empty($data['telephone']) || empty($data['type_bacc']) || empty($data['annee_bacc']) || empty($data['concours_id'])) {
            throw new Exception("Tous les champs doivent être remplis.");
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'email n'est pas valide.");
        }

        if (Candidat::emailExiste($data['email'])) {
            throw new Exception("Cet email est déjà utilisé.");
        }

        if (!$data['recu_paiement']) {
            throw new Exception("Veuillez confirmer votre paiement.");
        }

        $db = (new Database())->getConnection();
        $db->beginTransaction();

        try {
            $candidatId = Candidat::create($data);
            Inscription::add($candidatId, $data['concours_id']);
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}

/***************************
* TRAITEMENT DU FORMULAIRE : Notre formulaire en question 
***************************/
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $controller = new InscriptionController();
        $data = [
            'nom' => htmlspecialchars($_POST['nom']),
            'prenom' => htmlspecialchars($_POST['prenom']),
            'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
            'telephone' => htmlspecialchars($_POST['telephone']),
            'type_bacc' => htmlspecialchars($_POST['type_bacc']),
            'annee_bacc' => intval($_POST['annee_bacc']),
            'recu_paiement' => htmlspecialchars($_POST['recu_paiement']),
            'concours_id' => intval($_POST['concours_id'])
        ];
        
        if ($controller->processInscription($data)) {
            $successMessage = "Inscription réussie !";
        }
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}

$concours = Concours::getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - EMIT Concours</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #1a252f;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --error-color: #c0392b;
            --light-gray: #ecf0f1;
            --dark-gray: #2c3e50;
            --white: #ffffff;
            --text-color: #2c3e50;
            --border-radius: 6px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --overlay-color: rgba(44, 62, 80, 0.85);
            --gold-color: #d4af37;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            color: var(--text-color);
            overflow: hidden;
            height: 100vh;
            display: flex;
        }

        /* Section d'accueil fixe avec l'image de fond */
        .welcome-section {
            position: fixed;
            left: 0;
            top: 0;
            width: 40%;
            height: 100vh;
            background: url('assets/img/inscription.jpg') no-repeat center center;
            background-size: cover;
            padding: 5%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: var(--white);
            z-index: 1;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.9) 0%, rgba(26, 37, 47, 0.8) 100%);
            z-index: -1;
        }

        .welcome-content {
            max-width: 500px;
        }

        .welcome-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            margin-bottom: 1.5rem;
            line-height: 1.3;
            font-weight: 700;
            color: var(--white);
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
            position: relative;
            padding-bottom: 20px;
        }

        .welcome-section h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background-color: var(--gold-color);
        }

        .welcome-section p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .highlight {
            color: var(--gold-color);
            font-weight: 600;
        }

        /* Section formulaire avec défilement */
        .form-container {
            width: 60%;
            margin-left: 40%;
            height: 100vh;
            overflow-y: auto;
            background-color: var(--light-gray);
            padding: 50px 5%;
        }

        .form-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 40px;
            margin-bottom: 30px;
        }

        .form-title {
            color: var(--dark-gray);
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-family: 'Playfair Display', serif;
            position: relative;
            padding-bottom: 15px;
        }

        .form-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--accent-color);
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 15px;
        }

        input, select {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: all 0.3s;
            background-color: var(--light-gray);
        }

        input:focus, select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.1);
            background-color: var(--white);
        }

        button {
            width: 100%;
            padding: 16px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .message {
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 25px;
            text-align: center;
            font-weight: 500;
        }

        .success {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .error {
            background-color: rgba(192, 57, 43, 0.1);
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }

        /* Style pour le défilement */
        .form-container::-webkit-scrollbar {
            width: 8px;
        }

        .form-container::-webkit-scrollbar-track {
            background: var(--light-gray);
        }

        .form-container::-webkit-scrollbar-thumb {
            background-color: var(--primary-color);
            border-radius: 10px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            body {
                flex-direction: column;
                overflow: auto;
            }

            .welcome-section {
                position: relative;
                width: 100%;
                height: auto;
                padding: 40px 20px;
            }

            .form-container {
                width: 100%;
                margin-left: 0;
                height: auto;
                overflow: visible;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <section class="welcome-section">
        <div class="welcome-content">
            <h1>Inscrivez-vous </h1>
            <h2><span class="highlight ">au Concours d'entré L1 à l'EMIT</span></h2>
            <p>Rejoignez l'élite académique et donnez une nouvelle dimension à votre parcours éducatif. Notre institution, reconnue pour son excellence, ouvre ses portes aux esprits les plus brillants.</p>
            <p>Que vous soyez en Droit, Economie ou Multimedia, nous avons conçu des programmes d'études qui répondent aux exigences du monde contemporain. Votre réussite commence par cette simple inscription.</p>
            <p><span class="highlight">Date limite :</span> 25 octobre 2025</p>
        </div>
    </section>

    <div class="form-container">
        <div class="form-card">
            <h1 class="form-title">Formulaire d'inscription</h1>

            <?php if ($successMessage): ?>
                <div class="message success"><?= $successMessage ?></div>
            <?php endif; ?>

            <?php if ($errorMessage): ?>
                <div class="message error"><?= $errorMessage ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input type="text" id="nom" name="nom" required>
                </div>

                <div class="form-group">
                    <label for="prenom">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone *</label>
                    <input type="text" id="telephone" name="telephone" required>
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
                    <label for="annee_bacc">Année d'obtention du Bac *</label>
                    <input type="number" id="annee_bacc" name="annee_bacc" required min="1900" max="<?= date('Y') ?>">
                </div>

                <div class="form-group">
                     <label for="recu_paiement">Id du Paiement effectué *</label>
                     <input type="text" id="recu_paiement" name="recu_paiement" placeholder="Id de votre recu" >
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

                <button type="submit">Soumettre l'inscription</button>
            </form>

            <a href="index.php" class="back-link">← Retour à l'accueil</a>
        </div>
    </div>

    <!-- script pour appeler le inscription.js -->
    <script src="/assets/js/inscription.js"></script>
</body>
</html>