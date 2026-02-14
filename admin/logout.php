<?php
session_start();

/* Supprimer toutes les variables de session */
$_SESSION = [];

/* Détruire la session */
session_destroy();

/* Redirection vers le site public */
header("Location: ../frontend/produits.php");
exit();
