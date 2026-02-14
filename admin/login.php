<?php
/*
=====================================================
FICHIER : login.php
ROLE :
- Connexion admin sécurisée
- Protection brute force
- Protection CSRF
- Compatible password_hash()
=====================================================
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../backend/config/database.php");

/* ==============================
   PROTECTION BRUTE FORCE
============================== */

$maxAttempts = 5;
$lockTime = 300; // 5 minutes

if (!isset($_SESSION["login_attempts"])) {
    $_SESSION["login_attempts"] = 0;
    $_SESSION["last_attempt_time"] = time();
}

if ($_SESSION["login_attempts"] >= $maxAttempts) {
    if (time() - $_SESSION["last_attempt_time"] < $lockTime) {
        die("Trop de tentatives. Réessayez plus tard.");
    } else {
        $_SESSION["login_attempts"] = 0;
    }
}

/* ==============================
   CSRF TOKEN
============================== */

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$errorMessage = null;

/* ==============================
   TRAITEMENT FORMULAIRE
============================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_POST["csrf_token"]) ||
        !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])) {
        die("Requête invalide.");
    }

    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST["password"]);

    if (!$email || empty($password)) {
        $errorMessage = "Identifiants invalides.";
    } else {

        $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin["password"])) {

            $_SESSION["login_attempts"] = 0;
            session_regenerate_id(true);

            $_SESSION["admin"] = $admin["email"];

            header("Location: dashboard.php");
            exit();

        } else {
            $_SESSION["login_attempts"]++;
            $_SESSION["last_attempt_time"] = time();
            $errorMessage = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Connexion Admin</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="login-container">

<h2>🔐 Connexion Admin</h2>

<?php if ($errorMessage): ?>
    <p style="color:red; font-weight:bold;">
        <?= htmlspecialchars($errorMessage) ?>
    </p>
<?php endif; ?>

<form method="POST" class="login-form">

    <input type="hidden"
           name="csrf_token"
           value="<?= $_SESSION["csrf_token"] ?>">

    <input type="email"
           name="email"
           placeholder="Email"
           required>

    <input type="password"
           name="password"
           placeholder="Mot de passe"
           required>

    <button type="submit">
        Se connecter
    </button>

</form>

</div>

</body>
</html>
