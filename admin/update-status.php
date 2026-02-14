<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

require_once("../backend/config/database.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Accès non autorisé.");
}

$id = intval($_POST["id"]);
$status = $_POST["status"];

$allowed = ["En attente", "Traitée", "Livrée"];

if (!in_array($status, $allowed)) {
    die("Statut invalide.");
}

$stmt = $pdo->prepare("UPDATE orders SET status=? WHERE id=?");
$stmt->execute([$status, $id]);

header("Location: orders.php");
exit();
