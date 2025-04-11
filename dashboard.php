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

<div class="form-container">
    <h2>Witaj, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <p>Wybierz jedną z dostępnych opcji:</p>

    <div class="dashboard-menu">
        <ul>
            <li><a href="?action=edit-profile" class="btn-primary">Edytuj dane</a></li>
            <li><a href="?action=manage-ads" class="btn-primary">Zarządzaj ogłoszeniami</a></li>
            <li><a href="?action=delete-account" class="btn-danger">Usuń konto</a></li>
            <li><a href="logout.php" class="btn-primary">Wyloguj się</a></li>
        </ul>
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