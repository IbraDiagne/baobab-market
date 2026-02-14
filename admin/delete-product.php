<?php
/*
=====================================================
FICHIER : delete-product.php
ROLE :
- Supprime un produit de manière sécurisée
- Supprime aussi image + vidéo associées
- Protégé contre accès non autorisé
=====================================================
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Vérifier admin connecté */
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

/* Refuser méthode GET */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Accès non autorisé.");
}

/* Vérifier présence ID */
if (!isset($_POST["id"])) {
    die("Produit invalide.");
}

$id = intval($_POST["id"]);

require_once("../backend/config/database.php");
require_once("init.php");
/* Vérifier que produit existe */
$stmt = $pdo->prepare("SELECT image, video FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Produit introuvable.");
}

/* Supprimer image si existe */
if (!empty($product["image"])) {
    $imagePath = "../assets/images/products/" . $product["image"];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

/* Supprimer vidéo si existe */
if (!empty($product["video"])) {
    $videoPath = "../assets/videos/products/" . $product["video"];
    if (file_exists($videoPath)) {
        unlink($videoPath);
    }
}

/* Supprimer en base */
$delete = $pdo->prepare("DELETE FROM products WHERE id = ?");
$delete->execute([$id]);

/* Redirection */
header("Location: dashboard.php");
exit();
