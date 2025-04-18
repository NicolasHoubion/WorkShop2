<?php
session_start();
require_once './src/php/dbconn.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['id'];

// On récupère les tickets et le nombre de messages non lus (créés par les admins/helpers/etc.)
$sql = "
  SELECT
    t.*,
    (SELECT COUNT(*) FROM Messages m WHERE m.Ticket_id = t.Id AND m.Created_by != :user_id) AS unread_count
  FROM Ticket t
  WHERE t.User_id = :user_id AND t.Deleted_at IS NULL
  ORDER BY t.Created_at DESC
";
$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mes Conversations</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans min-h-screen flex flex-col">
  <?php require_once './src/components/header.php'; ?>

  <main class="flex-grow max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-blue-700">🎫 Mes Conversations</h1>

    <?php if (empty($tickets)): ?>
      <p class="italic text-gray-500">Vous n'avez pas encore créé de ticket.</p>
    <?php else: ?>
      <ul class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($tickets as $t): ?>
          <li class="relative bg-white rounded-xl shadow-md hover:shadow-lg transition p-6 group">
            <?php if ($t['unread_count'] > 0): ?>
              <span class="absolute top-4 right-4 bg-blue-600 text-white text-xs font-semibold px-2 py-1 rounded-full">
                <?= $t['unread_count'] ?>  
              </span>
            <?php endif; ?>

            <a href="ticket_view.php?id=<?= $t['Id'] ?>" class="block h-full">
              <h2 class="text-xl font-semibold text-blue-600 group-hover:text-blue-800 transition mb-2">
                <?= htmlspecialchars($t['Title']) ?>
              </h2>
              <p class="text-sm text-gray-600">
                Créé le <?= date('d/m/Y à H:i', strtotime($t['Created_at'])) ?>
              </p>

              <div class="mt-6">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                  Voir la conversation
                </button>
              </div>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </main>

  <?php require_once './src/components/footer.php'; ?>
</body>
</html>
