<?php
session_start();
require_once 'src/php/dbconn.php';

// Vérifie si l'ID du ticket est fourni
if (!isset($_GET['id'])) {
    die("Ticket non spécifié.");
}
$ticketId = intval($_GET['id']);

// Récupération du ticket
$stmtTicket = $db->prepare("SELECT t.*, u.Username FROM Ticket t LEFT JOIN Users u ON t.User_id = u.Id WHERE t.Id = :ticketId");
$stmtTicket->bindParam(":ticketId", $ticketId, PDO::PARAM_INT);
$stmtTicket->execute();
$ticket = $stmtTicket->fetch(PDO::FETCH_ASSOC);
if (!$ticket) {
    die("Ticket non trouvé.");
}

// Récupération des messages
$stmtMessages = $db->prepare("SELECT m.*, u.Username FROM Messages m LEFT JOIN Users u ON m.Created_by = u.Id WHERE m.Ticket_id = :ticketId ORDER BY m.Created_at ASC");
$stmtMessages->bindParam(":ticketId", $ticketId, PDO::PARAM_INT);
$stmtMessages->execute();
$messages = $stmtMessages->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire d'ajout de message
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["new_message"])) {
    $newMessage = trim($_POST["new_message"]);
    $userId = $_SESSION["id"];

    if (!empty($newMessage)) {
        $stmtNewMsg = $db->prepare("INSERT INTO Messages (Ticket_id, Message, Created_by) VALUES (:ticketId, :message, :userId)");
        $stmtNewMsg->bindParam(":ticketId", $ticketId, PDO::PARAM_INT);
        $stmtNewMsg->bindParam(":message", $newMessage);
        $stmtNewMsg->bindParam(":userId", $userId, PDO::PARAM_INT);

        if ($stmtNewMsg->execute()) {
            $_SESSION["success_message"] = "Message ajouté avec succès.";
            header("Location: ticket_view.php?id=" . $ticketId);
            exit;
        } else {
            $_SESSION["error_message"] = "Erreur lors de l'ajout du message.";
        }
    } else {
        $_SESSION["error_message"] = "Le message ne peut être vide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ticket #<?= htmlspecialchars($ticket["Id"]); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans min-h-screen flex flex-col">
  <?php require_once 'src/components/header.php'; ?>

  <main class="flex-grow max-w-4xl mx-auto p-6">
    <!-- Détails du ticket -->
    <header class="mb-6 border-b pb-4">
      <h1 class="text-3xl font-bold mb-1">🎫 Ticket #<?= htmlspecialchars($ticket["Id"]); ?> - <?= htmlspecialchars($ticket["Title"]); ?></h1>
      <p class="text-sm text-gray-600">
        Créé par <span class="text-blue-600 font-medium"><?= htmlspecialchars($ticket["Username"]); ?></span>
        le <?= htmlspecialchars($ticket["Created_at"]); ?>
      </p>
    </header>

    <!-- Affichage des messages -->
    <section class="mb-10">
      <h2 class="text-2xl font-semibold mb-4">💬 Messages</h2>
      <?php if ($messages): ?>
        <ul class="space-y-4">
          <?php foreach ($messages as $msg): ?>
            <li class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500">
              <p class="text-gray-800 whitespace-pre-line"><?= htmlspecialchars($msg["Message"]); ?></p>
              <p class="text-xs text-gray-500 mt-2">Par <strong><?= htmlspecialchars($msg["Username"]); ?></strong> le <?= htmlspecialchars($msg["Created_at"]); ?></p>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="italic text-gray-500">Aucun message pour ce ticket.</p>
      <?php endif; ?>
    </section>

    <!-- Formulaire d'ajout d'un message -->
    <section class="mb-12">
      <?php if (isset($_SESSION["error_message"])): ?>
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
          <?= htmlspecialchars($_SESSION["error_message"]); ?>
        </div>
        <?php unset($_SESSION["error_message"]); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION["success_message"])): ?>
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-4">
          <?= htmlspecialchars($_SESSION["success_message"]); ?>
        </div>
        <?php unset($_SESSION["success_message"]); ?>
      <?php endif; ?>

      <form method="post" action="ticket_view.php?id=<?= htmlspecialchars($ticketId); ?>" class="bg-white p-6 rounded-lg shadow-md">
        <label for="new_message" class="block font-medium mb-2">Nouveau message</label>
        <textarea name="new_message" id="new_message" rows="4" required
                  class="w-full p-3 border border-gray-300 rounded resize-y focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"></textarea>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
          ✉️ Envoyer
        </button>
      </form>
    </section>

    <!-- Actions admin/helper -->
    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'helper'])): ?>
      <section>
        <h2 class="text-2xl font-semibold mb-4">🔧 Actions administratives</h2>
        <a href="update_ticket_status.php?id=<?= htmlspecialchars($ticketId); ?>&action=close"
           class="inline-block bg-red-600 text-white px-5 py-2 rounded hover:bg-red-700 transition">
          🚫 Fermer le Ticket
        </a>
      </section>
    <?php endif; ?>
  </main>

  <?php require_once 'src/components/footer.php'; ?>
</body>
</html>
