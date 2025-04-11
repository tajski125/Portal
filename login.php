<?php
require 'config.php';

// Rozpoczęcie sesji tylko raz
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jeśli użytkownik jest już zalogowany, przekieruj na dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$pageTitle = "Logowanie";
require 'partials/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Wszystkie pola są wymagane.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Zalogowanie użytkownika
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Nieprawidłowy e-mail lub hasło.";
        }
    }
}
?>
<section class="form-container">
    <h2>Logowanie</h2>
    <?php if (isset($error)): ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php" class="styled-form">
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" placeholder="Wpisz swój e-mail" required>
        </div>
        <div class="form-group">
            <label for="password">Hasło:</label>
            <input type="password" id="password" name="password" placeholder="Wprowadź hasło" required>
        </div>
        <button type="submit" class="btn-primary">Zaloguj się</button>
    </form>
    <p class="form-footer">Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
</section>
<?php
require 'partials/footer.php';
?>