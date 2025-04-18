<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$hasAdminPermission = false;
if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
    $hasAdminPermission = true;
}
?>

<nav class="bg-blue-600 p-4 shadow-lg">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <h1 class="text-white text-2xl font-bold">
            <a href="/index.php" class="hover:text-yellow-300">YourTicket</a>
        </h1>
        <ul class="flex space-x-6 text-white items-center">
            <li><a href="#" class="hover:text-yellow-300">Ticket</a></li>
            <li><a href="#" class="hover:text-yellow-300">Paramètre</a></li>

            <?php if (isset($_SESSION['fname'])): ?>
                <li class="relative group" id="profile-menu">
                    <button class="flex items-center space-x-2 hover:text-yellow-300 focus:outline-none">
                        <span>👤 <?= htmlspecialchars($_SESSION['fname']) ?></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul class="absolute left-0 mt-2 hidden bg-white border border-gray-300 shadow-lg rounded-lg text-gray-700">
                        <li><a href="./profil.php" class="block px-4 py-2 hover:bg-gray-100">Mon Profil</a></li>
                        <li><a href="/src/php/logout.php" class="block px-4 py-2 hover:bg-gray-100">Déconnexion</a></li>
                    </ul>
                </li>

                <?php if ($hasAdminPermission): ?>
                    <li><a href="/admin.php" class="hover:text-yellow-300">Admin</a></li>
                <?php endif; ?>
            <?php else: ?>
                <li><a href="/login.php" class="hover:text-yellow-300">Connexion</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const profileMenu = document.getElementById('profile-menu');
        const dropdown = profileMenu.querySelector('ul');
        let timeout;

        profileMenu.addEventListener('mouseenter', () => {
            clearTimeout(timeout);
            dropdown.classList.remove('hidden');
        });

        profileMenu.addEventListener('mouseleave', () => {
            timeout = setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 300);
        });
    });
</script>
