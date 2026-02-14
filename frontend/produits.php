<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


/*
=====================================================
FICHIER : produits.php
ROLE :
- Affiche les produits dynamiquement
- Récupère les données depuis MySQL
- Génère les cartes produits automatiquement
=====================================================
*/

require_once("../backend/config/database.php");
require_once("../backend/process/visitor.php");
/* Récupération des produits */
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Produits - Baobab Market</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/products.css">
</head>
<body>

<header class="main-header">

<div class="delivery-banner">
    <span class="delivery-text">
        Paiement à la livraison partout au Sénégal 🇸🇳
    </span>
</div>

    <nav class="navbar">
        <div class="logo">
            <h1 id="logo-admin">Baobab Market</h1>
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="produits.php">Produits</a></li>
            <li><a href="panier.php">Panier</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="products-section">
        <h2>Nos Produits</h2>

        <div class="products-grid">

            <?php foreach ($products as $product): ?>

                <div class="product-card">

                    <!-- Image -->
                    <img src="../assets/images/products/<?= $product["image"] ?>"
                         alt="<?= $product["name"] ?>">

                    <!-- Nom -->
                    <h3>
			<a href="produit.php?id=<?= $product["id"] ?>">

				<?= $product["name"] ?>
			</a>
		    </h3>

                    <!-- Description -->
                    <p class="product-description">
                        <?= $product["description"] ?>
                    </p>

                    <!-- Prix -->
                    <p class="product-price">
                        <?= $product["price"] ?> FCFA
                    </p>

                    <!-- Bouton -->
                    <button class="btn-primary"
                        onclick="addToCart('<?= $product["name"] ?>', <?= $product["price"] ?>)">
                        Ajouter au panier
                    </button>

                </div>

            <?php endforeach; ?>

        </div>
    </section>
</main>

<footer class="footer">
    <p>&copy; 2026 Baobab Market</p>
<div class="admin-access">
    <a href="/baobab-market/admin/" class="admin-link">
        Espace Admin
    </a>
</div>


</footer>

<script src="../assets/js/cart.js"></script>
<script src="../assets/js/header.js"></script>
</body>
</html>
