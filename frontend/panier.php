<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mon Panier - Baobab Market</title>

<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body {
    background: linear-gradient(135deg,#ffe0ec,#f8f8f8);
}

.cart-wrapper {
    max-width: 1100px;
    margin: 60px auto;
    padding: 40px;
    background: white;
    border-radius: 25px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.15);
}

.cart-title {
    text-align: center;
    font-size: 28px;
    margin-bottom: 40px;
    color: #ff4081;
}

.cart-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    align-items: center;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 15px;
    background: #fafafa;
    transition: 0.3s;
}

.cart-item:hover {
    transform: scale(1.02);
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.qty-btn {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: none;
    background: #ff4081;
    color: white;
    cursor: pointer;
}

.cart-total-box {
    margin-top: 40px;
    padding: 30px;
    border-radius: 20px;
    background: linear-gradient(135deg,#ff4081,#ff80ab);
    color: white;
    text-align: right;
    font-size: 22px;
    font-weight: bold;
}

.checkout-btn {
    margin-top: 20px;
    padding: 15px 40px;
    border-radius: 50px;
    border: none;
    background: white;
    color: #ff4081;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

.checkout-btn:hover {
    transform: scale(1.05);
}

.empty-cart {
    text-align: center;
    font-size: 18px;
    padding: 40px;
}

</style>

</head>

<body>

<header class="main-header">

<div class="delivery-banner">
    <div class="delivery-track">
        <span class="delivery-text">
            Paiement à la livraison partout au Sénégal 🇸🇳
        </span>
    </div>
</div>

<nav class="navbar">
<div class="logo"><h1>Baobab Market</h1></div>
<ul class="nav-links">
<li><a href="index.php">Accueil</a></li>
<li><a href="produits.php">Produits</a></li>
<li><a href="panier.php">Panier</a></li>
</ul>
</nav>

</header>

<div class="cart-wrapper">

<h2 class="cart-title">🛒 Votre Panier</h2>

<div id="cart-items"></div>

<div class="cart-total-box">
Total : <span id="cart-total">0</span> FCFA
<br>
<button class="checkout-btn" onclick="window.location.href='checkout.php'">
Passer la commande
</button>
</div>

</div>

<script src="../assets/js/cart.js"></script>

<script>
displayCart(); // assure affichage automatique
</script>

</body>
</html>
