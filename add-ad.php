<?php
require 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Dodaj Ogłoszenie";
require 'partials/header.php';

// Obsługa formularza
$errorMessages = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $make = $_POST['make'] ?? '';
    $model = $_POST['model'] ?? '';
    $year = $_POST['year'] ?? '';
    $mileage = $_POST['mileage'] ?? '';
    $price = $_POST['price'] ?? '';
    $location = $_POST['location'] ?? '';
    $description = $_POST['description'] ?? '';

    if (empty($make) || empty($model) || empty($year) || empty($mileage) || empty($price) || empty($location)) {
        $errorMessages[] = 'Wszystkie pola są wymagane.';
    }

    if (empty($errorMessages)) {
        $stmt = $pdo->prepare("INSERT INTO ads (user_id, make, model, year, mileage, price, location, description)
                               VALUES (:user_id, :make, :model, :year, :mileage, :price, :location, :description)");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'make' => $make,
            'model' => $model,
            'year' => $year,
            'mileage' => $mileage,
            'price' => $price,
            'location' => $location,
            'description' => $description,
        ]);

        header("Location: dashboard.php");
        exit;
    }
}
?>

<div class="form-container">
    <div class="form-card">
        <h2 class="form-title">Dodaj nowe ogłoszenie</h2>
        <p class="form-description">Wypełnij poniższy formularz, aby dodać swoje ogłoszenie.</p>

        <?php if (!empty($errorMessages)): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($errorMessages as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="add-ad.php" method="POST" enctype="multipart/form-data" class="styled-form">
            <!-- Marka -->
            <div class="form-group">
                <label for="make">Marka:</label>
                <select id="make" name="make" required>
                    <option value="">Wybierz markę</option>
                    <!-- Dynamically populated by JavaScript -->
                </select>
            </div>

            <!-- Model -->
            <div class="form-group">
                <label for="model">Model:</label>
                <select id="model" name="model" required>
                    <option value="">Najpierw wybierz markę</option>
                </select>
            </div>

            <!-- Rok produkcji -->
            <div class="form-group">
                <label for="year">Rok produkcji:</label>
                <input type="number" id="year" name="year" placeholder="Wprowadź rok produkcji" required>
            </div>

            <!-- Przebieg -->
            <div class="form-group">
                <label for="mileage">Przebieg (km):</label>
                <input type="number" id="mileage" name="mileage" placeholder="Wprowadź przebieg" required>
            </div>

            <!-- Cena -->
            <div class="form-group">
                <label for="price">Cena (PLN):</label>
                <input type="number" id="price" name="price" placeholder="Wprowadź cenę" required>
            </div>

            <!-- Lokalizacja -->
            <div class="form-group">
                <label for="location">Lokalizacja:</label>
                <input type="text" id="location" name="location" placeholder="Wpisz miejscowość" required>
            </div>

            <!-- Opis -->
            <div class="form-group">
                <label for="description">Opis:</label>
                <textarea id="description" name="description" rows="5" placeholder="Wpisz opis samochodu"></textarea>
            </div>

            <button type="submit" class="btn-primary">Dodaj ogłoszenie</button>
        </form>
    </div>
</div>
<style>
/* Stylizacja formularza */
.form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f4f4f4;
    padding: 20px;
    box-sizing: border-box;
}

.form-card {
    background-color: #fff;
    border-radius: 10px;
    padding: 30px;
    width: 100%;
    max-width: 600px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-title {
    font-size: 24px;
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
}

.form-description {
    font-size: 16px;
    text-align: center;
    margin-bottom: 20px;
    color: #666;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

select, input, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

select:disabled {
    background-color: #f4f4f4;
    color: #999;
}

button.btn-primary {
    display: block;
    background-color: #007bff;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    transition: background-color 0.3s ease;
    width: 100%;
}

button.btn-primary:hover {
    background-color: #0056b3;
}
</style>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const makeSelect = document.getElementById('make');
    const modelSelect = document.getElementById('model');
    const locationInput = document.getElementById('location');

    // Pobierz marki samochodów z API
    fetch('https://api-ninjas.com/api/cars?apikey=YOUR_API_KEY')
        .then(response => response.json())
        .then(data => {
            data.forEach(car => {
                const option = document.createElement('option');
                option.value = car.make;
                option.textContent = car.make;
                makeSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Błąd podczas pobierania marek:', error));

    // Pobierz modele dla wybranej marki
    makeSelect.addEventListener('change', function () {
        const selectedMake = this.value;

        fetch(`https://api-ninjas.com/api/cars?make=${encodeURIComponent(selectedMake)}&apikey=YOUR_API_KEY`)
            .then(response => response.json())
            .then(data => {
                modelSelect.innerHTML = '<option value="">Wybierz model</option>';
                data.forEach(car => {
                    const option = document.createElement('option');
                    option.value = car.model;
                    option.textContent = car.model;
                    modelSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Błąd podczas pobierania modeli:', error));
    });

    // Autouzupełnianie lokalizacji
    locationInput.addEventListener('input', function () {
        const query = this.value;

        fetch(`https://api.geoapify.com/v1/geocode/autocomplete?text=${encodeURIComponent(query)}&apiKey=YOUR_API_KEY`)
            .then(response => response.json())
            .then(data => {
                console.log('Propozycje lokalizacji:', data);
            })
            .catch(error => console.error('Błąd podczas autouzupełniania lokalizacji:', error));
    });
});
</script>