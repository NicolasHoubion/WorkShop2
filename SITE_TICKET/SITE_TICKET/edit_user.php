<?php
session_start();
require_once './src/php/dbconn.php';

if (!isset($_GET['id'])) {
    die("Aucun utilisateur spécifié.");
}

$userId = intval($_GET['id']);

// Récupérer les informations de l'utilisateur
$stmt = $db->prepare("SELECT * FROM Users WHERE Id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur introuvable.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Par exemple, mise à jour du nom d'utilisateur
    $username = $_POST['username'];

    $updateStmt = $db->prepare("UPDATE Users SET Username = ? WHERE Id = ?");
    if($updateStmt->execute([$username, $userId])){
        header("Location: admin.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'utilisateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-800 text-white p-6">
    <h1 class="text-3xl font-bold mb-4">Modifier l'utilisateur</h1>
    <form method="POST" class="max-w-md mx-auto">
        <div class="mb-4">
            <label class="block mb-2">Nom d'utilisateur</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['Username']) ?>" class="w-full p-2 text-gray-900">
        </div>
        <button type="submit" class="bg-green-500 px-4 py-2 rounded">Mettre à jour</button>
    </form>
</body>
</html>
