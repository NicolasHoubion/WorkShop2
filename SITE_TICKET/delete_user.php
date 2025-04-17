<?php
session_start();
require_once './src/php/dbconn.php';

if (!isset($_GET['id'])) {
    die("Aucun utilisateur spécifié.");
}

$userId = intval($_GET['id']);

$stmt = $db->prepare("DELETE FROM Users WHERE Id = ?");
if ($stmt->execute([$userId])) {
    header("Location: admin.php");
    exit;
} else {
    echo "Erreur lors de la suppression de l'utilisateur.";
}
?>
