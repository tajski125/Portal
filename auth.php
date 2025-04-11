<?php
require 'config.php'; // Plik konfiguracji bazy danych

// Funkcja rejestracji
function register($username, $email, $password) {
    global $pdo;

    // Sprawdzenie, czy użytkownik już istnieje
    $checkQuery = "SELECT * FROM users WHERE username = :username OR email = :email";
    $stmt = $pdo->prepare($checkQuery);
    $stmt->execute(['username' => $username, 'email' => $email]);

    if ($stmt->rowCount() > 0) {
        return "Użytkownik o podanym loginie lub adresie e-mail już istnieje.";
    }

    // Hashowanie hasła
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Wstawianie użytkownika do bazy danych
    $insertQuery = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($insertQuery);
    $result = $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword
    ]);

    if ($result) {
        return "Rejestracja zakończona sukcesem!";
    } else {
        return "Wystąpił błąd podczas rejestracji.";
    }
}

// Funkcja logowania
function login($usernameOrEmail, $password) {
    global $pdo;

    // Pobranie użytkownika z bazy danych
    $query = "SELECT * FROM users WHERE username = :usernameOrEmail OR email = :usernameOrEmail";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['usernameOrEmail' => $usernameOrEmail]);

    if ($stmt->rowCount() === 0) {
        return "Nieprawidłowy login lub hasło.";
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Weryfikacja hasła
    if (password_verify($password, $user['password'])) {
        // Rozpoczęcie sesji użytkownika
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return "Logowanie zakończone sukcesem!";
    } else {
        return "Nieprawidłowy login lub hasło.";
    }
}
?>