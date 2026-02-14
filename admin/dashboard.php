<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../backend/config/database.php");



/*
=====================================================
FICHIER : dashboard.php
ROLE :
- Tableau de bord admin sécurisé
- Affiche les produits
- Permet modification / suppression
=====================================================
*/


/* Sécurisation session */
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

/* Protection contre fixation session */
session_regenerate_id(true);

require_once("../backend/config/database.php");
/* ===========================
   STATISTIQUES
=========================== */

$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")
                     ->fetchColumn();

$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")
                   ->fetchColumn();

$totalRevenue = $pdo->query("SELECT SUM(total) FROM orders")
                    ->fetchColumn();

$pendingOrders = $pdo->query("
    SELECT COUNT(*) FROM orders 
    WHERE status = 'En attente'
")->fetchColumn();

$totalVisitors = $pdo->query("
    SELECT COUNT(DISTINCT ip_address) FROM visitors
")->fetchColumn();


/* Récupération produits */
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="admin-container">

    <h2>📊 Tableau de bord</h2>

<div class="stats-grid">

    <div class="stat-card">
        <h3><?= $totalProducts ?></h3>
        <p>Produits</p>
    </div>

    <div class="stat-card">
        <h3><?= $totalOrders ?></h3>
        <p>Commandes</p>
    </div>

    <div class="stat-card">
        <h3><?= $totalRevenue ?: 0 ?> FCFA</h3>
        <p>Total Ventes</p>
    </div>

    <div class="stat-card">
        <h3><?= $pendingOrders ?></h3>
        <p>En attente</p>
    </div>

<div class="stat-card">
    <h3><?= $totalVisitors ?></h3>
    <p>Visiteurs</p>
</div>


</div>


    <!-- Actions principales -->
    <div class="admin-actions">
        <a href="add-product.php" class="btn-admin btn-primary">
            ➕ Ajouter un produit
        </a>

        <a href="orders.php" class="btn-admin btn-secondary">
            📦 Voir les commandes
        </a>

        <a href="logout.php" class="btn-admin btn-danger">
            🔒 Déconnexion
        </a>
    </div>

    <h3>Liste des produits</h3>

    <?php if (empty($products)): ?>
        <p>Aucun produit disponible.</p>
    <?php else: ?>

        <?php foreach ($products as $product): ?>

            <div class="admin-card">

                <h4><?= htmlspecialchars($product["name"]) ?></h4>

                <p>
                    <strong>Prix :</strong>
                    <?= htmlspecialchars($product["price"]) ?> FCFA
                </p>

                <!-- Actions produit -->
                <div class="product-actions">

                    <a href="edit-product.php?id=<?= htmlspecialchars($product["id"]) ?>"
                       class="btn-admin btn-edit">
                        ✏️ Modifier
                    </a>

                    <!-- Suppression via POST (sécurisée plus tard) -->
                    <form method="POST"
                          action="delete-product.php"
                          style="display:inline-block;">

                        <input type="hidden"
                               name="id"
                               value="<?= htmlspecialchars($product["id"]) ?>">

                        <button type="submit"
                                class="btn-admin btn-delete"
                                onclick="return confirm('Supprimer ce produit ?');">
                            🗑 Supprimer
                        </button>
                    </form>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

</body>
</html>
