<?php
/*
=====================================================
FICHIER : orders.php
ROLE :
- Affiche toutes les commandes
- Permet modification du statut
- Version sécurisée et professionnelle
=====================================================
*/

session_start();
require_once("../backend/config/database.php");

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

/* Récupérer commandes */
$orders = $pdo->query("SELECT * FROM orders ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Commandes Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="admin-container">

<h2>📦 Liste des Commandes</h2>

<div class="admin-actions">
    <a href="dashboard.php" class="btn-admin btn-secondary">
        ⬅ Retour Dashboard
    </a>

    <a href="logout.php" class="btn-admin btn-danger">
        🔒 Déconnexion
    </a>
</div>

<hr>

<?php if (empty($orders)): ?>
    <p>Aucune commande pour le moment.</p>
<?php endif; ?>

<?php foreach ($orders as $order): ?>

    <div class="order-card">

        <!-- HEADER -->
        <div class="order-header">
            <h3>
                Commande #<?= htmlspecialchars($order["order_number"] ?? $order["id"]) ?>
            </h3>

            <span class="order-total">
                <?= htmlspecialchars($order["total"]) ?> FCFA
            </span>
        </div>

        <!-- INFOS CLIENT -->
        <div class="order-details">
            <p><strong>Client :</strong> <?= htmlspecialchars($order["customer_name"]) ?></p>
            <p><strong>Téléphone :</strong> <?= htmlspecialchars($order["phone"]) ?></p>
            <p><strong>Adresse :</strong> <?= htmlspecialchars($order["address"]) ?></p>
            <p><strong>Paiement :</strong> <?= htmlspecialchars($order["payment_method"]) ?></p>
            <p><strong>Date :</strong> <?= htmlspecialchars($order["created_at"]) ?></p>
        </div>

        <!-- PRODUITS -->
        <div class="order-products">
            <h4>Produits :</h4>

            <ul>
                <?php
                $stmt = $pdo->prepare("
                    SELECT * FROM order_items 
                    WHERE order_id = ?
                ");
                $stmt->execute([$order["id"]]);
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php foreach ($items as $item): ?>
                    <li>
                        <?= htmlspecialchars($item["product_name"]) ?>
                        — <?= htmlspecialchars($item["price"]) ?> FCFA
                        (x<?= htmlspecialchars($item["quantity"]) ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- STATUT -->
        <div class="order-status">

            <p>
                <strong>Statut actuel :</strong>
                <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $order["status"])) ?>">
                    <?= htmlspecialchars($order["status"]) ?>
                </span>
            </p>

            <form method="POST" action="update-status.php" class="status-form">

                <input type="hidden" name="id"
                       value="<?= htmlspecialchars($order["id"]) ?>">

                <select name="status">

                    <option value="En attente"
                        <?= $order["status"] === "En attente" ? "selected" : "" ?>>
                        En attente
                    </option>

                    <option value="Traitée"
                        <?= $order["status"] === "Traitée" ? "selected" : "" ?>>
                        Traitée
                    </option>

                    <option value="Livrée"
                        <?= $order["status"] === "Livrée" ? "selected" : "" ?>>
                        Livrée
                    </option>

                </select>

                <button type="submit" class="btn-admin btn-secondary">
                    Mettre à jour
                </button>

            </form>

        </div>

    </div>

<?php endforeach; ?>

</div>

</body>
</html>
