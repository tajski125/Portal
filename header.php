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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? "Portal Ogłoszeniowy"; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
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
                    <li><a href="dashboard.php">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="register.php">Rejestracja</a></li>
                <li><a href="login.php">Logowanie</a></li>
            </ul>
        </nav>
        <div class="user-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>Witaj, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="logout.php">Wyloguj się</a>
            <?php else: ?>
                <a href="login.php">Zaloguj się</a>
                <a href="register.php">Zarejestruj się</a>
            <?php endif; ?>
        </div>
    </header>
    <main>