<?php
session_start();
require_once 'src/php/dbconn.php';
require_once 'src/components/header.php';

if (!isset($_SESSION['id'])) {
    echo "<div class='bg-red-100 text-red-700 p-4 mb-4 rounded text-center w-full'>Vous devez être connecté pour accéder à cette page.</div>";
    exit;
}

$userId = $_SESSION['id'];

// récupérer les informations de l'utilisateur
$stmt = $db->prepare("SELECT Users.*, Roles.Name AS RoleName 
                      FROM Users 
                      JOIN Roles ON Users.Role_id = Roles.Id 
                      WHERE Users.Id = :id");
$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<div class='bg-red-100 text-red-700 p-4 mb-4 rounded text-center w-full'>Utilisateur non trouvé.</div>";
    exit;
}

// Affichage des messages stockés temporairement dans la session
$successMessage = $_SESSION['success_message'] ?? '';
$errorMessage = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

if (isset($_POST['submit'])) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileInfo = pathinfo($_FILES['profile_image']['name']);
        $extension = strtolower($fileInfo['extension']);

        if (in_array($extension, $allowedExtensions)) {
            $newFileName = uniqid() . '.' . $extension;
            $uploadDir = 'src/images/';
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                $stmt = $db->prepare("UPDATE Users SET Image = :image WHERE Id = :id");
                $stmt->bindParam(':image', $newFileName, PDO::PARAM_STR);
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['success_message'] = "Photo de profil mise à jour avec succès!";
            } else {
                $_SESSION['error_message'] = "Erreur lors de l'upload de l'image.";
            }
        } else {
            $_SESSION['error_message'] = "Seules les images JPG, JPEG et PNG sont autorisées.";
        }
    } else {
        $_SESSION['error_message'] = "Aucune image n'a été envoyée ou une erreur est survenue.";
    }

    // Redirection vers la même page pour éviter le repost en cas de refresh
    header("Location: profil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex flex-col bg-gray-100 text-gray-900 font-sans">

    <?php require_once 'src/components/header.php'; ?>

    <main class="flex-grow max-w-7xl mx-auto p-6">
        <?php if (!empty($errorMessage)): ?>
            <div class="bg-red-100 text-red-700 p-4 mb-4 rounded text-center w-full">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="bg-green-100 text-green-700 p-4 mb-4 rounded text-center w-full">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <h2 class="text-3xl font-bold mb-4">Profil de <?php echo htmlspecialchars($user['Firstname']); ?></h2>

        <div class="mb-6">
            <img src="src/images/<?php echo htmlspecialchars($user['Image'] ?: 'image_defaut.avif'); ?>" alt="Photo de profil" class="w-32 h-32 rounded-full mx-auto mb-4">
        </div>

        <div class="bg-white p-6 shadow rounded mb-6">
            <h3 class="text-2xl font-semibold mb-4">Détails du profil</h3>
            <div class="space-y-4">
                <p><strong>Prénom :</strong> <?php echo htmlspecialchars($user['Firstname']); ?></p>
                <p><strong>Nom :</strong> <?php echo htmlspecialchars($user['Lastname'] ?: 'Non renseigné'); ?></p>
                <p><strong>Email :</strong> <?php echo htmlspecialchars($user['mail']); ?></p>
                <p><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($user['Username']); ?></p>
                <?php
                $roleName = $user['RoleName'];
                $badgeClass = match (strtolower($roleName)) {
                    'admin' => 'bg-red-100 text-red-700',
                    'helper' => 'bg-purple-100 text-purple-700',
                    'dev' => 'bg-emerald-100 text-emerald-700',
                    default => 'bg-blue-100 text-blue-700',
                };
                ?>
                <p>
                    <strong>Rôle :</strong>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold <?php echo $badgeClass; ?>">
                        <?php echo htmlspecialchars($roleName); ?>
                    </span>
                </p>
            </div>
        </div>

        <form action="profil.php" method="post" enctype="multipart/form-data" class="bg-white p-6 shadow rounded">
            <div class="mb-6">
                <label for="profile_image" class="block text-gray-700 font-semibold mb-2 text-center">Changer la photo de profil :</label>
                <div class="flex justify-center">
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden">
                    <label for="profile_image" class="cursor-pointer bg-blue-600 text-white py-3 px-8 rounded-lg text-center hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 transition duration-300">
                        Sélectionner une image
                    </label>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" name="submit" class="w-full bg-blue-600 text-white py-3 px-8 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 transition duration-300 mb-4">
                    Mettre à jour
                </button>
            </div>
            <div class="mt-4">
                <a href="index.php" class="w-full block text-center bg-gray-300 text-gray-700 py-3 px-8 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-600 transition duration-300">
                    Annuler
                </a>
            </div>
        </form>

    </main>

    <?php require_once 'src/components/footer.php'; ?>

</body>

</html>
