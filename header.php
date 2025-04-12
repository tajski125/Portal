<?php
// Rozpoczęcie sesji tylko raz
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debugowanie sesji (opcjonalne)
// echo '<pre>' . print_r($_SESSION, true) . '</pre>';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<!-- Dodaj bibliotekę Algolia Places -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/places.js@1.19.0/dist/cdn/places.min.css">
<script src="https://cdn.jsdelivr.net/npm/places.js@1.19.0/dist/cdn/places.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? "Portal Ogłoszeniowy"; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
	<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputLocation = document.getElementById('location');
        const suggestions = document.createElement('ul');
        suggestions.setAttribute('id', 'location-suggestions');
        inputLocation.parentNode.appendChild(suggestions);

        inputLocation.addEventListener('input', function () {
            const query = inputLocation.value;

            if (query.length < 3) return; // Minimalna liczba znaków do wyszukiwania

            fetch(`https://nominatim.openstreetmap.org/search?q=${query}&format=json&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    suggestions.innerHTML = ''; // Wyczyść istniejące sugestie

                    data.forEach(location => {
                        const li = document.createElement('li');
                        li.textContent = location.display_name;
                        li.classList.add('suggestion-item');
                        li.addEventListener('click', function () {
                            inputLocation.value = location.display_name; // Ustaw wybraną lokalizację
                            suggestions.innerHTML = ''; // Wyczyść listę sugestii
                        });
                        suggestions.appendChild(li);
                    });
                });
        });
    });
</script>
</head>
<body>
    <header>
        <div class="logo">
            <img src="assets/images/logo.png" alt="Logo Portalu">
        </div>
        <nav class="menu">
            <ul>
                <li><a href="index.php">Strona główna</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">Moje Konto</a></li>
                    <li><a href="add-ad.php" class="btn-primary">Dodaj Ogłoszenie</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="user-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>
                    Witaj, 
                    <a href="dashboard.php" style="text-decoration: none; color: inherit;">
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a>
                    !
                </span>
                <a href="logout.php">Wyloguj się</a>
            <?php else: ?>
                <a href="login.php">Zaloguj się</a>
                <a href="register.php">Zarejestruj się</a>
            <?php endif; ?>
        </div>
    </header>
    <main>