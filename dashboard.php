<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html"); // Rediriger vers la connexion si l'utilisateur n'est pas connecté
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            width: 80%;
            margin: 20px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .logout-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            background: #dc3545;
            padding: 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2>Bienvenue, <?= $_SESSION['user']['username']; ?> !</h2>
        <h3>Utilisateurs enregistrés</h3>
        
        <?php
        // Connexion à la base de données
        $conn = new mysqli('localhost:3308', 'root', 'root', 'login_system');
        
        if ($conn->connect_error) {
            die("Échec de la connexion : " . $conn->connect_error);
        }

        // Récupération des utilisateurs
        $result = $conn->query("SELECT id, username, rule FROM users");

        if ($result->num_rows > 0):
        ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Rôle</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['rule']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <?php else: ?>
            <p>Aucun utilisateur trouvé.</p>
        <?php endif; ?>

        <a href="logout.php" class="logout-btn">Déconnexion</a>
    </div>
</body>
</html>
