<?php
require 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Zarządzanie Ogłoszeniami";
require 'partials/header.php';

// Pobierz ogłoszenia użytkownika
$stmt = $pdo->prepare("SELECT * FROM ads WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$ads = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obsługa akcji usuwania
if (isset($_GET['delete'])) {
    $adId = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM ads WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $adId, 'user_id' => $_SESSION['user_id']]);
    header("Location: manage-ads.php");
    exit;
}
?>

<div class="ads-container">
    <h2 class="ads-title">Zarządzaj swoimi ogłoszeniami</h2>

    <?php if (count($ads) > 0): ?>
        <table class="ads-table">
            <thead>
                <tr>
                    <th>Marka</th>
                    <th>Model</th>
                    <th>Cena (PLN)</th>
                    <th>Data dodania</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ads as $ad): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ad['make']); ?></td>
                        <td><?php echo htmlspecialchars($ad['model']); ?></td>
                        <td><?php echo htmlspecialchars($ad['price']); ?></td>
                        <td><?php echo htmlspecialchars($ad['created_at']); ?></td>
                        <td>
                            <a href="edit-ad.php?id=<?php echo $ad['id']; ?>" class="btn-edit">Edytuj</a>
                            <a href="manage-ads.php?delete=<?php echo $ad['id']; ?>" class="btn-delete" onclick="return confirm('Czy na pewno chcesz usunąć to ogłoszenie?');">Usuń</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-ads">
            <p>Nie masz jeszcze żadnych ogłoszeń.</p>
        </div>
    <?php endif; ?>
</div>

<style>
/* Główne kontener */
.ads-container {
    max-width: 900px;
    margin: 20px auto;
    padding: 30px;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.ads-title {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

/* Tabela ogłoszeń */
.ads-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.ads-table th, .ads-table td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

.ads-table th {
    background: #007bff;
    color: #fff;
    font-weight: bold;
}

.ads-table tr:hover {
    background: #f9f9f9;
}

/* Przyciski akcji */
.btn-edit, .btn-delete {
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    display: inline-block;
}

.btn-edit {
    background: #28a745;
    color: #fff;
}

.btn-delete {
    background: #dc3545;
    color: #fff;
}

.btn-edit:hover {
    background: #218838;
}

.btn-delete:hover {
    background: #c82333;
}

/* Pusty stan */
.no-ads {
    text-align: center;
    font-size: 18px;
    color: #666;
    margin-top: 30px;
}
</style>

<?php
require 'partials/footer.php';
?>