<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact - Baobab Market</title>

<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.contact-section {
    padding: 80px 20px;
    display: flex;
    justify-content: center;
    background: #f8f8f8;
}

.contact-container {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 800px;
}

.contact-container h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #ff4081;
}

.contact-info {
    display: grid;
    gap: 20px;
}

.contact-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    border-radius: 15px;
    background: linear-gradient(135deg,#ff4081,#ff80ab);
    color: white;
    font-size: 18px;
    transition: 0.3s;
}

.contact-card:hover {
    transform: translateY(-5px);
}

.contact-card i {
    font-size: 24px;
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
        <div class="logo">
            <h1>Baobab Market</h1>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="produits.php">Produits</a></li>
            <li><a href="panier.php">Panier</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
</header>

<section class="contact-section">

<div class="contact-container">

<h2>📞 Contactez-nous</h2>

<div class="contact-info">

<div class="contact-card">
<i class="fas fa-envelope"></i>
<span>baobabmarket@gmail.com</span>
</div>

<div class="contact-card">
<i class="fab fa-whatsapp"></i>
<a href="https://wa.me/221787105956" target="_blank" style="color:white;text-decoration:none;">
+221 78 710 59 56 (WhatsApp)
</a>
</div>

<div class="contact-card">
<i class="fab fa-instagram"></i>
<a href="https://instagram.com/baobab-market" target="_blank" style="color:white;text-decoration:none;">
@baobab-market
</a>
</div>

</div>

</div>

</section>

<footer class="footer">
© 2026 Baobab Market
</footer>

</body>
</html>
