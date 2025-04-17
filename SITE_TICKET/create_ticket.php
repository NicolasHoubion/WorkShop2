<?php
session_start();
require_once 'src/php/dbconn.php';
require_once 'src/components/header.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération et nettoyage des champs
    $title   = trim($_POST["title"]);
    $message = trim($_POST["message"]);
    $userId  = $_SESSION["id"];

    // Vérification rapide des champs
    if (empty($title) || empty($message)) {
        $_SESSION["error_message"] = "Veuillez remplir tous les champs.";
    } else {
        // Insertion du ticket dans la table Ticket.
        // On insère aussi Created_by avec l'id de l'utilisateur.
        $stmtTicket = $db->prepare("INSERT INTO Ticket (Title, User_id, Created_by) VALUES (:title, :userId, :userId)");
        $stmtTicket->bindParam(":title", $title);
        $stmtTicket->bindParam(":userId", $userId, PDO::PARAM_INT);
        
        if ($stmtTicket->execute()) {
            // Récupération de l'ID du ticket nouvellement créé
            $ticketId = $db->lastInsertId();
            
            // Insertion du premier message dans la table Messages
            $stmtMsg = $db->prepare("INSERT INTO Messages (Ticket_id, Message, Created_by) VALUES (:ticketId, :message, :userId)");
            $stmtMsg->bindParam(":ticketId", $ticketId, PDO::PARAM_INT);
            $stmtMsg->bindParam(":message", $message);
            $stmtMsg->bindParam(":userId", $userId, PDO::PARAM_INT);
            
            if ($stmtMsg->execute()) {
                $_SESSION["success_message"] = "Ticket créé avec succès.";
                header("Location: ticket_view.php?id=" . $ticketId);
                exit;
            } else {
                $_SESSION["error_message"] = "Erreur lors de la création du message initial.";
            }
        } else {
            $_SESSION["error_message"] = "Erreur lors de la création du ticket.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Créer un Ticket</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans">
  <?php require_once 'src/components/header.php'; ?>

  <main class="max-w-7xl mx-auto p-6">
      <h2 class="text-3xl font-bold mb-4">Créer un Ticket</h2>

      <?php if (isset($_SESSION["error_message"])): ?>
          <div class="bg-red-100 text-red-700 p-4 mb-4 rounded"><?= htmlspecialchars($_SESSION["error_message"]); ?></div>
          <?php unset($_SESSION["error_message"]); ?>
      <?php endif; ?>

      <form action="create_ticket.php" method="post" class="bg-white p-6 rounded shadow">
          <div class="mb-4">
              <label for="title" class="block font-semibold mb-2">Titre</label>
              <input type="text" name="title" id="title" class="w-full p-2 border rounded" required>
          </div>
          <div class="mb-4">
              <label for="message" class="block font-semibold mb-2">Message</label>
              <textarea name="message" id="message" rows="5" class="w-full p-2 border rounded" required></textarea>
          </div>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer le ticket</button>
      </form>
  </main>

  <?php require_once 'src/components/footer.php'; ?>
</body>
</html>
