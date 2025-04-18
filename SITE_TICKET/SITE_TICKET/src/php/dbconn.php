<?php
try {
    // Connexion à la base
    $db = new PDO('mysql:host=localhost;dbname=ticket_233', 'root', 'root');
    $db->exec('SET NAMES "UTF8"');
} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
    die();
}