<?php
require 'config.php';

// Rozpoczęcie sesji
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jeśli użytkownik nie jest zalogowany, przekieruj na stronę logowania
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Panel użytkownika";
require 'partials/header.php';

// Pobranie danych użytkownika z bazy
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    echo "<p class='error-message'>Nie znaleziono użytkownika.</p>";
    require 'partials/footer.php';
    exit;
}
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h2>Witaj, <?php echo htmlspecialchars($user['username']); ?>!</h2>
        <p>Wybierz jedną z dostępnych opcji:</p>
    </div>

    <div class="dashboard-content">
        <!-- Menu w formie kart -->
        <div class="dashboard-card">
            <h3>Edytuj dane</h3>
            <p>Aktualizuj swoje dane osobowe.</p>
            <a href="?action=edit-profile" class="btn-primary">Przejdź</a>
        </div>
        <div class="dashboard-card">
            <h3>Zarządzaj ogłoszeniami</h3>
            <p>Przeglądaj i zarządzaj swoimi ogłoszeniami.</p>
            <a href="?action=manage-ads" class="btn-primary">Przejdź</a>
        </div>
        <div class="dashboard-card">
            <h3>Usuń konto</h3>
            <p>Usuń swoje konto z portalu.</p>
            <a href="?action=delete-account" class="btn-danger">Przejdź</a>
        </div>
        <div class="dashboard-card">
            <h3>Wyloguj się</h3>
            <p>Bezpiecznie zakończ sesję.</p>
            <a href="logout.php" class="btn-primary">Przejdź</a>
        </div>
    </div>

    <div class="dashboard-content">
        <?php
        // Obsługa akcji w menu
        $action = $_GET['action'] ?? 'default';
        switch ($action) {
            case 'edit-profile':
                include 'partials/edit-profile.php';
                break;
            case 'manage-ads':
                include 'partials/manage-ads.php';
                break;
            case 'delete-account':
                include 'partials/delete-account.php';
                break;
            default:
                echo "<p>Wybierz opcję z menu, aby zarządzać swoim kontem.</p>";
                break;
        }
        ?>
    </div>
</div>

<?php
require 'partials/footer.php';
?>