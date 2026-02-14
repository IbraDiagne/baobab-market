<?php
/*
=====================================================
FICHIER : database.php
ROLE :
- Gère la connexion à la base de données
- Fournit la variable $pdo pour tout le backend
=====================================================
*/

$host = "localhost";
$dbname = "baobab_market";
$username = "baobab_user";
$password = "Baobab2026!"; // Mets ton mot de passe MySQL si tu en as un

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erreur connexion : " . $e->getMessage());
}
