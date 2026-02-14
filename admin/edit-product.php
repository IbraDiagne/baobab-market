<?php
/*
=====================================================
FICHIER : edit-product.php
ROLE :
- Modification produit sécurisée
- Protection CSRF
- Upload image/vidéo sécurisé
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

require_once("../backend/config/database.php");
require_once("init.php");
/* Vérifier ID */
if (!isset($_GET["id"])) {
    die("Produit invalide.");
}

$id = intval($_GET["id"]);

/* Récupération produit */
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Produit introuvable.");
}

/* Générer token CSRF */
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

    if (!$name || !$price) {
        $errorMessage = "Données invalides.";
    }

    /* IMAGE (si nouvelle) */
    $newImageName = $product["image"];

    if (!$errorMessage && !empty($_FILES["image"]["name"])) {

        if ($_FILES["image"]["error"] === 0) {

            $allowedImg = ["image/jpeg", "image/png", "image/webp"];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES["image"]["tmp_name"]);

            if (!in_array($mime, $allowedImg)) {
                $errorMessage = "Format image non autorisé.";
            }

            if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
                $errorMessage = "Image trop volumineuse.";
            }

            if (!$errorMessage) {

                /* Supprimer ancienne image */
                if (!empty($product["image"])) {
                    $oldPath = "../assets/images/products/" . $product["image"];
                    if (file_exists($oldPath)) unlink($oldPath);
                }

                $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $newImageName = uniqid("product_", true) . "." . $ext;

                move_uploaded_file(
                    $_FILES["image"]["tmp_name"],
                    "../assets/images/products/" . $newImageName
                );
            }
        }
    }

    /* VIDEO (si nouvelle) */
    $newVideoName = $product["video"];

    if (!$errorMessage && !empty($_FILES["video"]["name"])) {

        if ($_FILES["video"]["error"] === 0) {

            $allowedVid = ["video/mp4"];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES["video"]["tmp_name"]);

            if (!in_array($mime, $allowedVid)) {
                $errorMessage = "Format vidéo non autorisé.";
            }

            if ($_FILES["video"]["size"] > 20 * 1024 * 1024) {
                $errorMessage = "Vidéo trop volumineuse.";
            }

            if (!$errorMessage) {

                /* Supprimer ancienne vidéo */
                if (!empty($product["video"])) {
                    $oldVideo = "../assets/videos/products/" . $product["video"];
                    if (file_exists($oldVideo)) unlink($oldVideo);
                }

                $ext = pathinfo($_FILES["video"]["name"], PATHINFO_EXTENSION);
                $newVideoName = uniqid("video_", true) . "." . $ext;

                move_uploaded_file(
                    $_FILES["video"]["tmp_name"],
                    "../assets/videos/products/" . $newVideoName
                );
            }
        }
    }

    /* UPDATE */
    if (!$errorMessage) {

        $update = $pdo->prepare("
            UPDATE products
            SET name=?, description=?, price=?, image=?, video=?
            WHERE id=?
        ");

        $update->execute([
            htmlspecialchars($name),
            htmlspecialchars($description),
            $price,
            $newImageName,
            $newVideoName,
            $id
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
<title>Modifier produit</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="admin-container">

<h2>Modifier produit</h2>

<?php if ($errorMessage): ?>
    <p style="color:red; font-weight:bold;">
        <?= htmlspecialchars($errorMessage) ?>
    </p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="admin-form">

    <input type="hidden" name="csrf_token"
           value="<?= $_SESSION["csrf_token"] ?>">

    <input type="text" name="name"
           value="<?= htmlspecialchars($product["name"]) ?>" required>

    <textarea name="description"><?= htmlspecialchars($product["description"]) ?></textarea>

    <input type="number" step="0.01"
           name="price"
           value="<?= htmlspecialchars($product["price"]) ?>" required>

    <label>Changer image :</label>
    <input type="file" name="image" accept="image/*">

    <label>Changer vidéo :</label>
    <input type="file" name="video" accept="video/mp4">

    <button type="submit">Enregistrer modifications</button>

</form>

</div>

</body>
</html>
