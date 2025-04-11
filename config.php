<?php
// Ustawienia bazy danych
$host = 'localhost'; // Adres serwera bazy danych
$db = 'portal_ogloszeniowy'; // Nazwa bazy danych
$user = 'root'; // Użytkownik bazy danych
$pass = ''; // Hasło do bazy danych

try {
    // Połączenie z bazą danych przy użyciu PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Ustawienie trybu błędów na wyjątki
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}
?>