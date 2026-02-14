<?php
/*
=====================================================
FICHIER : add-product.php
ROLE :
- Ajout produit sécurisé
- Protection CSRF
- Upload image + vidéo sécurisé
- Gestion stock produit
=====================================================
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

session_regenerate_id(true);
require_once("init.php");
require_once("../backend/config/database.php");

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$errorMessage = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])) {
        die("Requête invalide.");
    }

    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = filter_var($_POST["price"], FILTER_VALIDATE_FLOAT);
    $stock = filter_var($_POST["stock"], FILTER_VALIDATE_INT);

    if (!$name || $price === false || $stock === false || $stock < 0) {
        $errorMessage = "Données invalides.";
    }

    /* ======================
       IMAGE (obligatoire)
    ====================== */

    if (!$errorMessage && $_FILES["image"]["error"] === 0) {

        $allowedImg = ["image/jpeg", "image/png", "image/webp"];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES["image"]["tmp_name"]);

        if (!in_array($mime, $allowedImg)) {
            $errorMessage = "Format image non autorisé.";
        }

        if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
            $errorMessage = "Image trop volumineuse (max 5MB).";
        }

        if (!$errorMessage) {

            $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $newImageName = uniqid("product_", true) . "." . $ext;

            move_uploaded_file(
                $_FILES["image"]["tmp_name"],
                "../assets/images/products/" . $newImageName
            );
        }

    } else {
        $errorMessage = "Image obligatoire.";
    }

    /* ======================
       VIDEO (optionnelle)
    ====================== */

    $newVideoName = null;

    if (!$errorMessage && !empty($_FILES["video"]["name"])) {

        if ($_FILES["video"]["error"] === 0) {

            $allowedVid = ["video/mp4"];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES["video"]["tmp_name"]);

            if (!in_array($mime, $allowedVid)) {
                $errorMessage = "Format vidéo non autorisé (MP4).";
            }

            if ($_FILES["video"]["size"] > 20 * 1024 * 1024) {
                $errorMessage = "Vidéo trop volumineuse (max 20MB).";
            }

            if (!$errorMessage) {

                $ext = pathinfo($_FILES["video"]["name"], PATHINFO_EXTENSION);
                $newVideoName = uniqid("video_", true) . "." . $ext;

                move_uploaded_file(
                    $_FILES["video"]["tmp_name"],
                    "../assets/videos/products/" . $newVideoName
                );
            }
        }
    }

    /* ======================
       INSERTION BASE
    ====================== */

    if (!$errorMessage) {

        $stmt = $pdo->prepare("
            INSERT INTO products (name, description, price, stock, image, video)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            htmlspecialchars($name),
            htmlspecialchars($description),
            $price,
            $stock,
            $newImageName,
            $newVideoName
        ]);

        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Ajouter produit</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="admin-container">

<h2>➕ Ajouter un produit</h2>

<?php if ($errorMessage): ?>
    <p style="color:red; font-weight:bold;">
        <?= htmlspecialchars($errorMessage) ?>
    </p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="admin-form">

    <input type="hidden" name="csrf_token"
           value="<?= $_SESSION["csrf_token"] ?>">

    <input type="text" name="name"
           placeholder="Nom du produit" required>

    <textarea name="description"
              placeholder="Description"></textarea>

    <input type="number" step="0.01"
           name="price" placeholder="Prix" required>

    <input type="number"
           name="stock"
           placeholder="Stock disponible"
           min="0"
           required>

    <label>Image :</label>
    <input type="file" name="image"
           accept="image/*" required>

    <label>Vidéo MP4 (optionnelle) :</label>
    <input type="file" name="video"
           accept="video/mp4">

    <button type="submit">Ajouter</button>

</form>

</div>

</body>
</html>
