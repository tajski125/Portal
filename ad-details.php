<?php
require 'config.php';

// Sprawdzenie, czy ID ogłoszenia zostało podane w URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ads.php"); // Przekieruj na stronę główną ogłoszeń, jeśli brak ID
    exit;
}

$adId = (int)$_GET['id'];

// Pobierz dane ogłoszenia i użytkownika z bazy danych na podstawie ID ogłoszenia
$stmt = $pdo->prepare("SELECT ads.*, users.phone FROM ads JOIN users ON ads.user_id = users.id WHERE ads.id = :id");
$stmt->execute(['id' => $adId]);
$ad = $stmt->fetch(PDO::FETCH_ASSOC);

// Jeśli ogłoszenie nie istnieje, przekieruj na stronę ogłoszeń
if (!$ad) {
    header("Location: ads.php");
    exit;
}

$pageTitle = "Szczegóły Ogłoszenia";
require 'partials/header.php';

// Wyciągnij numer telefonu i zamaskuj część numeru
$phone = $ad['phone'] ?? '';
if (strlen($phone) >= 3) {
    $maskedPhone = substr($phone, 0, 3) . str_repeat('*', strlen($phone) - 3); // Maskowanie numeru
} else {
    $maskedPhone = $phone; // Jeśli numer jest krótszy niż 3 znaki, pokaż go w całości
}
?>

<div class="ad-details-container">
    <h1><?php echo htmlspecialchars($ad['make']) . " " . htmlspecialchars($ad['model']); ?></h1>

    <div class="ad-details">
        <p><strong>Rok produkcji:</strong> <?php echo htmlspecialchars($ad['year']); ?></p>
        <p><strong>Cena:</strong> <?php echo htmlspecialchars($ad['price']); ?> PLN</p>
        <p><strong>Lokalizacja:</strong> <?php echo htmlspecialchars($ad['location']); ?></p>
        <p><strong>Przebieg:</strong> <?php echo htmlspecialchars($ad['mileage']); ?> km</p>
        <p><strong>Opis:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($ad['description'])); ?></p>
        <p><strong>Telefon:</strong> 
            <span id="masked-phone"><?php echo htmlspecialchars($maskedPhone); ?></span>
            <button id="show-phone" onclick="revealPhone()">Pokaż</button>
        </p>
    </div>

    <a href="ads.php" class="btn-back">Powrót do ogłoszeń</a>
</div>

<script>
function revealPhone() {
    // Wyświetl pełny numer telefonu
    const fullPhoneNumber = "<?php echo htmlspecialchars($phone); ?>";
    document.getElementById('masked-phone').textContent = fullPhoneNumber;
    document.getElementById('show-phone').style.display = 'none'; // Ukryj przycisk "Pokaż"
}
</script>

<style>
/* Stylizacja szczegółów ogłoszenia */
.ad-details-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 30px;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.ad-details-container h1 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #333;
}

.ad-details {
    text-align: left;
    margin-bottom: 20px;
}

.ad-details p {
    font-size: 16px;
    line-height: 1.5;
    color: #555;
    margin: 10px 0;
}

.btn-back {
    display: inline-block;
    padding: 10px 15px;
    background: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    font-size: 16px;
}

.btn-back:hover {
    background: #0056b3;
}

button#show-phone {
    margin-left: 10px;
    padding: 5px 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button#show-phone:hover {
    background-color: #0056b3;
}
</style>

<?php require 'partials/footer.php'; ?>