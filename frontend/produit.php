<?php
/*
=====================================================
FICHIER : produit.php
ROLE :
- Affiche un produit spécifique
- Gère image + vidéo locale (MP4)
- Vidéo autoplay + loop
=====================================================
*/

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("../backend/config/database.php");
require_once("../backend/process/visitor.php");
/* Vérifier ID */
if (!isset($_GET["id"])) {
    die("Produit introuvable.");
}

$id = intval($_GET["id"]);

/* Récupération produit */
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Produit non trouvé.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product["name"]) ?> - Baobab Market</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/product-detail.css">
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
            <li><a href="produits.php">Produits</a></li>
            <li><a href="panier.html">Panier</a></li>
        </ul>
    </nav>

</header>

<main>

<section class="product-detail">

    <!-- IMAGE + VIDEO -->

<div class="product-media">

    <!-- MEDIA PRINCIPAL -->
    <div class="main-media">

        <!-- IMAGE PRINCIPALE -->
        <img id="main-image"
             class="active-media"
             src="../assets/images/products/<?= htmlspecialchars($product["image"]) ?>"
             alt="<?= htmlspecialchars($product["name"]) ?>">

        <!-- VIDEO PRINCIPALE -->
        <?php if (!empty($product["video"])): ?>
            <video id="main-video"
                   muted loop playsinline>
                <source src="../assets/videos/products/<?= htmlspecialchars($product["video"]) ?>" type="video/mp4">
            </video>
        <?php endif; ?>

    </div>

    <!-- MINIATURES -->
    <div class="media-thumbnails">

        <img class="thumb active"
             src="../assets/images/products/<?= htmlspecialchars($product["image"]) ?>"
             onclick="switchToImage(this)">

        <?php if (!empty($product["video"])): ?>
            <div class="video-thumb" onclick="switchToVideo(this)">
                ▶
            </div>
        <?php endif; ?>

    </div>

</div>


    <!-- Infos produit -->
<div class="product-info">

    <h2><?= htmlspecialchars($product["name"]) ?></h2>

    <p class="product-price">
        <?= htmlspecialchars($product["price"]) ?> FCFA
    </p>

    <p class="product-description">
        <?= htmlspecialchars($product["description"]) ?>
    </p>

    <!-- STOCK -->
    <?php if ($product["stock"] > 0): ?>

        <p class="product-stock in-stock">
            ✔ En stock (<?= htmlspecialchars($product["stock"]) ?> disponibles)
        </p>

        <button class="btn-primary"
            onclick="addToCart(
                <?= $product['id'] ?>,
                '<?= htmlspecialchars($product["name"]) ?>',
                <?= htmlspecialchars($product["price"]) ?>
            )">
            Ajouter au panier
        </button>

    <?php else: ?>

        <p class="product-stock out-stock">
            ❌ Rupture de stock
        </p>

        <button class="btn-primary" disabled>
            Indisponible
        </button>

    <?php endif; ?>

</div>


</section>

</main>

<footer class="footer">
    <p>&copy; 2026 Baobab Market</p>
</footer>

<script src="../assets/js/cart.js"></script>
<script src="../assets/js/header.js"></script>


<script>
function switchToImage(element) {

    const image = document.getElementById("main-image");
    const video = document.getElementById("main-video");

    image.classList.add("fade");
    image.style.display = "block";

    if (video) {
        video.pause();
        video.style.display = "none";
    }

    setTimeout(() => image.classList.remove("fade"), 300);
}

function switchToVideo(element) {

    const image = document.getElementById("main-image");
    const video = document.getElementById("main-video");

    if (!video) return;

    image.style.display = "none";

    video.style.display = "block";
    video.play();
    video.classList.add("fade");

    setTimeout(() => video.classList.remove("fade"), 300);
}
</script>


</body>
</html>
