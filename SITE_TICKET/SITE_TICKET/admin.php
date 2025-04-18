<?php
session_start();

// Connection à la base de données via PDO
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ticket_233', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Fonction utilitaire pour formater la date en français
function formatDateFr($datetime) {
    // Création d'un IntlDateFormatter pour le français
    $fmt = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::LONG,
        IntlDateFormatter::SHORT,
        'Europe/Brussels',
        IntlDateFormatter::GREGORIAN,
        "d MMMM yyyy 'à' HH:mm"
    );
    return $fmt->format(new DateTime($datetime));
}

// -------------------------
// Récupération des tickets
// -------------------------
try {
    $queryTickets = $pdo->query("
        SELECT t.Id, t.Title, u.Username, t.Created_at 
        FROM Ticket t
        LEFT JOIN Users u ON t.User_id = u.Id
        ORDER BY t.Created_at DESC
    ");
    $tickets = $queryTickets->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $tickets = [];
}

// -------------------------
// Récupération des utilisateurs avec rôles & permissions
// -------------------------
try {
    $queryUsers = $pdo->query("
        SELECT 
            u.Id AS user_id,
            u.Username,
            r.Name AS role_name,
            p.Name AS permission_name
        FROM Users u
        JOIN Roles r ON u.Role_id = r.Id
        JOIN Permission_Roles pr ON r.Id = pr.Role_id
        JOIN Permissions p ON pr.Permission_id = p.Id
        WHERE u.Deleted_at IS NULL
        ORDER BY u.Id, p.Name
    ");
    $results = $queryUsers->fetchAll(PDO::FETCH_ASSOC);

    $users = [];
    foreach ($results as $row) {
        $id = $row['user_id'];
        if (!isset($users[$id])) {
            $users[$id] = [
                'user_id'     => $id,
                'username'    => $row['Username'],
                'role'        => $row['role_name'],
                'permissions' => []
            ];
        }
        $users[$id]['permissions'][] = $row['permission_name'];
    }
} catch (PDOException $e) {
    $users = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Gestion Tickets & Utilisateurs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-800 text-white font-sans">

  <?php require_once './src/components/header.php'; ?>

  <div class="container mx-auto p-6">
    <h1 class="text-4xl font-bold mb-8 text-center">Panneau d'administration</h1>

    <!-- SECTION TICKETS -->
    <section class="mb-12">
    <h2 class="text-3xl font-semibold mb-4">Gestion des Tickets</h2>
    <div class="overflow-y-auto max-h-[500px] bg-gray-700 p-4 rounded-lg">
        <?php if (count($tickets) > 0): ?>
            <table class="min-w-full table-auto">
                <thead class="bg-gray-600">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Titre</th>
                        <th class="px-4 py-2 text-left">Utilisateur</th>
                        <th class="px-4 py-2 text-left">Créé le</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr class="bg-gray-800 hover:bg-gray-700 border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['Id']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['Title']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['Username']) ?></td>
                            <td class="px-4 py-2">
                                <?= formatDateFr($ticket['Created_at']); ?>
                            </td>
                            <td class="px-4 py-2">
                                <a href="ticket_view.php?id=<?= $ticket['Id'] ?>"
                                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                    Voir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-gray-400">Aucun ticket à afficher pour le moment.</p>
        <?php endif; ?>
    </div>
</section>

    <!-- SECTION UTILISATEURS -->
    <section>
      <h2 class="text-3xl font-semibold mb-4">Gestion des Utilisateurs</h2>
      <?php if (count($users) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php foreach ($users as $user): ?>
            <?php
              $roleName = strtolower($user['role']);
              switch ($roleName) {
                case 'admin':  $badgeClass = 'bg-red-100 text-red-700';    break;
                case 'helper': $badgeClass = 'bg-purple-100 text-purple-700'; break;
                case 'dev':    $badgeClass = 'bg-emerald-100 text-emerald-700'; break;
                default:       $badgeClass = 'bg-blue-100 text-blue-700';    break;
              }
            ?>
            <div class="bg-gray-700 p-4 rounded-lg shadow">
              <div class="flex justify-between items-center">
                <h3 class="text-2xl font-bold"><?= htmlspecialchars($user['username']) ?></h3>
                <span class="px-2 py-1 rounded text-sm font-semibold <?= $badgeClass; ?>">
                  <?= htmlspecialchars($user['role']) ?>
                </span>
              </div>
              <div class="mt-2">
                <p class="font-semibold">Permissions :</p>
                <ul class="list-disc list-inside text-sm">
                  <?php foreach ($user['permissions'] as $permission): ?>
                    <li><?= htmlspecialchars($permission) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
              <div class="mt-4 space-x-2">
                <a href="edit_user.php?id=<?= $user['user_id'] ?>"
                   class="px-3 py-1 bg-green-500 rounded hover:bg-green-600 text-sm inline-block">
                  Modifier
                </a>
                <a href="delete_user.php?id=<?= $user['user_id'] ?>"
                   class="px-3 py-1 bg-red-500 rounded hover:bg-red-600 text-sm inline-block">
                  Supprimer
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-center text-gray-400">Aucun utilisateur à gérer.</p>
      <?php endif; ?>
    </section>
  </div>

  <?php require_once './src/components/footer.php'; ?>

</body>
</html>
