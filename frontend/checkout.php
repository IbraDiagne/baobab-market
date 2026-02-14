<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("../backend/config/database.php");
require_once("../backend/process/visitor.php");

/* =========================
   TRAITEMENT COMMANDE
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
        $_SESSION["error_message"] = "Votre panier est vide.";
        header("Location: panier.php");
        exit();
    }

    $customerName = htmlspecialchars(trim($_POST["customer_name"]));
    $phone = htmlspecialchars(trim($_POST["phone"]));
    $address = htmlspecialchars(trim($_POST["address"]));
    $paymentMethod = htmlspecialchars(trim($_POST["payment_method"]));

    if (!$customerName || !$phone || !$address || !$paymentMethod) {
        $_SESSION["error_message"] = "Tous les champs sont obligatoires.";
        header("Location: checkout.php");
        exit();
    }

    $cart = $_SESSION["cart"];

    try {

        $pdo->beginTransaction();
        $total = 0;

        foreach ($cart as $item) {

            $stmt = $pdo->prepare("SELECT stock, price FROM products WHERE id = ?");
            $stmt->execute([$item["id"]]);
            $product = $stmt->fetch();

            if (!$product) {
                throw new Exception("Produit introuvable.");
            }

            if ($product["stock"] < $item["quantity"]) {
                throw new Exception("Stock insuffisant pour " . $item["name"]);
            }

            $total += $product["price"] * $item["quantity"];
        }

        $orderNumber = "BM-" . strtoupper(uniqid());

        $stmt = $pdo->prepare("
            INSERT INTO orders
            (order_number, customer_name, phone, address, payment_method, total, status)
            VALUES (?, ?, ?, ?, ?, ?, 'en_attente')
        ");

        $stmt->execute([
            $orderNumber,
            $customerName,
            $phone,
            $address,
            $paymentMethod,
            $total
        ]);

        $orderId = $pdo->lastInsertId();

        foreach ($cart as $item) {

            $stmt = $pdo->prepare("
                INSERT INTO order_items
                (order_id, product_id, product_name, price, quantity)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $orderId,
                $item["id"],
                $item["name"],
                $item["price"],
                $item["quantity"]
            ]);

            $updateStock = $pdo->prepare("
                UPDATE products
                SET stock = stock - ?
                WHERE id = ?
            ");

            $updateStock->execute([
                $item["quantity"],
                $item["id"]
            ]);
        }

        $pdo->commit();

        unset($_SESSION["cart"]);

        header("Location: success.php?order=" . $orderNumber);
        exit();

    } catch (Exception $e) {

        $pdo->rollBack();
        $_SESSION["error_message"] = $e->getMessage();
        header("Location: checkout.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Finaliser la commande</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css">
<link rel="stylesheet" href="../assets/css/checkout.css">
</head>
<body>

<header class="main-header">
    <div class="delivery-banner">
        <span class="delivery-text">
            Paiement à la livraison partout au Sénégal 🇸🇳
        </span>
    </div>
</header>

<main class="checkout-container">

    <div class="checkout-card">

        <h2>🧾 Finaliser votre commande</h2>

        <?php if (isset($_SESSION["error_message"])): ?>
            <div class="error-box">
                <?= htmlspecialchars($_SESSION["error_message"]) ?>
            </div>
            <?php unset($_SESSION["error_message"]); ?>
        <?php endif; ?>

        <form method="POST" class="checkout-form">

            <input type="text" name="customer_name"
                   placeholder="Nom complet" required>

            <input type="text" name="phone"
                   placeholder="Téléphone" required>

            <textarea name="address"
                      placeholder="Adresse complète"
                      required></textarea>

            <select name="payment_method" required>
                <option value="">Méthode de paiement</option>
                <option value="Paiement à la livraison">Paiement à la livraison</option>
                <option value="Wave">Wave</option>
                <option value="Orange Money">Orange Money</option>
            </select>

            <button type="submit">
                Confirmer la commande
            </button>

        </form>

    </div>

</main>

</body>
</html>

