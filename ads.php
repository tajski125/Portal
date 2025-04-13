<?php
require 'config.php';

$pageTitle = "Ogłoszenia";
require 'partials/header.php';

// Ustawienia paginacji
$adsPerPage = 10; // Liczba ogłoszeń na stronę
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $adsPerPage;

// Pobierz ogłoszenia z bazy danych (z uwzględnieniem paginacji)
$stmt = $pdo->prepare("SELECT * FROM ads ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $adsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$ads = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pobierz całkowitą liczbę ogłoszeń (do paginacji)
$totalAdsStmt = $pdo->query("SELECT COUNT(*) FROM ads");
$totalAds = $totalAdsStmt->fetchColumn();
$totalPages = ceil($totalAds / $adsPerPage);

?>

<div class="ads-container">
    <h1>Ogłoszenia</h1>
    <p>Przeglądaj najnowsze ogłoszenia dodane przez użytkowników.</p>

    <?php if (!empty($ads)): ?>
        <div class="ads-list">
            <?php foreach ($ads as $ad): ?>
                <div class="ad-card">
                    <h3><?php echo htmlspecialchars($ad['make']) . " " . htmlspecialchars($ad['model']); ?></h3>
                    <p><strong>Cena:</strong> <?php echo htmlspecialchars($ad['price']); ?> PLN</p>
                    <p><strong>Lokalizacja:</strong> <?php echo htmlspecialchars($ad['location']); ?></p>
                    <a href="ad-details.php?id=<?php echo $ad['id']; ?>" class="btn-view-details">Szczegóły</a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Paginacja -->
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="ads.php?page=<?php echo $currentPage - 1; ?>" class="pagination-btn">Poprzednia</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="ads.php?page=<?php echo $i; ?>" class="pagination-btn <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="ads.php?page=<?php echo $currentPage + 1; ?>" class="pagination-btn">Następna</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>Brak ogłoszeń do wyświetlenia.</p>
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

.ads-container h1 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

.ads-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin-bottom: 20px;
}

.ad-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    width: 250px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.ad-card h3 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #007bff;
}

.ad-card p {
    margin: 5px 0;
    font-size: 14px;
    color: #555;
}

.btn-view-details {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 12px;
    background: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
}

.btn-view-details:hover {
    background: #0056b3;
}

/* Paginacja */
.pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
}

.pagination-btn {
    padding: 8px 12px;
    background: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
}

.pagination-btn.active {
    background: #0056b3;
    font-weight: bold;
}

.pagination-btn:hover {
    background: #0056b3;
}
</style>

<?php require 'partials/footer.php'; ?>