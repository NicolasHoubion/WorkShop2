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
    // Redirection vers la page de profil
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

<!-- On ne modifie que la partie HTML à partir du <body> -->

<body class="min-h-screen bg-white text-gray-900 font-sans">

    <?php require_once 'src/components/header.php'; ?>

    <main class="flex-grow max-w-6xl mx-auto p-8">
        <?php if (!empty($errorMessage)): ?>
            <div class="bg-red-600/20 text-red-400 border border-red-500 p-4 mb-4 rounded text-center w-full">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="bg-green-600/20 text-green-400 border border-green-500 p-4 mb-4 rounded text-center w-full">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <h2 class="text-4xl font-bold mb-6 border-b border-gray-300 pb-2">Profil de <?php echo htmlspecialchars($user['Firstname']); ?></h2>

        <div class="mb-10 flex flex-col items-center">
            <img src="src/images/<?php echo htmlspecialchars($user['Image'] ?: 'image_defaut.avif'); ?>" alt="Photo de profil" class="w-36 h-36 rounded-full shadow-lg border-4 border-white object-cover">
        </div>

        <div class="bg-white p-8 shadow-xl rounded-2xl mb-10">
            <h3 class="text-2xl font-semibold mb-4 border-b border-gray-300 pb-2">Détails du profil</h3>
            <div class="space-y-4 text-lg">
                <p><strong class="text-blue-600">Nom complet :</strong> <?php echo htmlspecialchars($user['Firstname']); ?></p>
                <p><strong class="text-blue-600">Email :</strong> <?php echo htmlspecialchars($user['mail']); ?></p>
                <p><strong class="text-blue-600">Nom d'utilisateur :</strong> <?php echo htmlspecialchars($user['Username']); ?></p>
                <?php
                $roleName = $user['RoleName'];
                $badgeClass = match (strtolower($roleName)) {
                    'admin' => 'bg-red-500/20 text-red-600 border border-red-400',
                    'helper' => 'bg-purple-500/20 text-purple-600 border border-purple-400',
                    'dev' => 'bg-emerald-500/20 text-emerald-600 border border-emerald-400',
                    default => 'bg-blue-500/20 text-blue-600 border border-blue-400',
                };
                ?>
                <p>
                    <strong class="text-blue-600">Rôle :</strong>
                    <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold <?php echo $badgeClass; ?>">
                        <?php echo htmlspecialchars($roleName); ?>
                    </span>
                </p>
            </div>
        </div>

        <form action="profil.php" method="post" enctype="multipart/form-data" class="bg-white p-8 shadow-xl rounded-2xl mb-10">
            <div class="mb-6">
                <label for="profile_image" class="block text-lg font-semibold mb-2 text-center">Changer la photo de profil :</label>
                <div class="flex justify-center">
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden">
                    <label for="profile_image" class="cursor-pointer bg-blue-600 text-white py-3 px-8 rounded-full text-center hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300">
                        Sélectionner une image
                    </label>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" name="submit" class="w-full bg-blue-600 text-white py-3 px-8 rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300 mb-4">
                    Mettre à jour
                </button>
            </div>
            <div class="mt-4">
                <a href="index.php" class="w-full block text-center bg-gray-300 text-gray-700 py-3 px-8 rounded-full hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-300">
                    Annuler
                </a>
            </div>
        </form>

    </main>

    <?php require_once 'src/components/footer.php'; ?>
</body>



</html>
