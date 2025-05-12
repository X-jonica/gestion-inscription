<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - EMIT Concours</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body, html {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: url('assets/img/bgImage.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .overlay {
            background-color: rgba(0, 0, 0, 0.6);
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }
        .container {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .buttons {
            display: flex;
            gap: 20px;
        }
        .buttons a {
            text-decoration: none;
            background: #007BFF;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1.1rem;
            transition: background 0.3s ease;
        }
        .buttons a:hover {
            background: #0056b3;
        }
        @media (max-width: 600px) {
            h1 {
                font-size: 2rem;
            }
            .buttons {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <h1>Bienvenue sur la plateforme de concours de l'EMIT</h1>
        <div class="buttons">
            <a href="inscription.php">S'inscrire</a>
            <a href="admin/login.php">Se connecter</a>
        </div>
    </div>

    <!-- script pour appeler le index.js -->
    <script src="/assets/js/index.js"></script>
</body>
</html>
