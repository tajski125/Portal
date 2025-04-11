<?php
$pageTitle = "Strona Główna";
require 'partials/header.php';
?>
<section class="hero-section">
    <h1>Witaj na Portalu Ogłoszeniowym!</h1>
    <p>Znajdź najlepsze ogłoszenia już teraz.</p>
    <div class="search-bar">
        <input type="text" class="search-input" placeholder="Wyszukaj ogłoszenia...">
        <button class="search-btn">Szukaj</button>
    </div>
</section>
<section class="featured-latest-ads">
    <h2>Ostatnie ogłoszenia</h2>
    <div class="ad-grid large">
        <div class="ad-card">
            <img src="assets/images/ad1.jpg" alt="Ogłoszenie 1">
            <h3>Ogłoszenie 1</h3>
            <p>Opis ogłoszenia 1</p>
        </div>
        <div class="ad-card">
            <img src="assets/images/ad2.jpg" alt="Ogłoszenie 2">
            <h3>Ogłoszenie 2</h3>
            <p>Opis ogłoszenia 2</p>
        </div>
    </div>
</section>
<?php
require 'partials/footer.php';
?>