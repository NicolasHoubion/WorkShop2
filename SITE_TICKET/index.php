<?php
session_start(); 
require_once 'src/php/dbconn.php';
require_once 'src/components/header.php';

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YourTicket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex flex-col bg-gray-100 text-gray-900 font-sans">

    <main class="flex-grow main-content max-w-7xl mx-auto p-6">

    <?php if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true): ?>
        <div id="login-alert" class="max-w-7xl mx-auto mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                ✅ Connexion réussie ! Bienvenue 👋
            </div>
        </div>
        <?php unset($_SESSION['login_success']); ?>
    <?php endif; ?>

    <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
        <div id="logout-alert" class="max-w-7xl mx-auto mt-4">
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                🔒 Vous avez été déconnecté avec succès.
            </div>
        </div>
    <?php endif; ?>

        <h2 class="text-3xl font-bold mb-4">Bienvenue sur YourTicket</h2>
        <p class="text-gray-700 mb-6">Discutez avec les administrateurs et gérez vos tickets facilement.</p>

        <!-- Vérification du rôle de l'utilisateur -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Option pour créer un ticket -->
            <div class="bg-white p-4 shadow rounded">
                <h3 class="text-xl font-bold mb-2">Créer un Ticket</h3>
                <p class="text-gray-600">Soumettez un nouveau ticket pour obtenir de l'aide.</p>
                <a href="create_ticket.php" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer</a>
            </div>

            <!-- Option pour consulter les conversations -->
            <div class="bg-white p-4 shadow rounded">
                <h3 class="text-xl font-bold mb-2">Mes Conversations</h3>
                <p class="text-gray-600">Consultez vos discussions avec les administrateurs.</p>
                <a href="conversations.php" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Voir</a>
            </div>
        </div>

        <!-- Section Admin et Helper -->
        <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'helper'])): ?>
            <div class="mt-6">
                <h3 class="text-xl font-bold mb-2">Gestion des Tickets</h3>
                <p class="text-gray-600 mb-4">Accédez à la gestion des tickets pour aider les utilisateurs.</p>
                <a href="admin.php" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Voir la gestion des tickets</a>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once 'src/components/footer.php'; ?>

</body>

</html>
