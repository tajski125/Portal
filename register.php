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

// Generowanie prostej captchy
if (!isset($_SESSION['captcha'])) {
    $_SESSION['captcha'] = [
        'num1' => rand(1, 10),
        'num2' => rand(1, 10),
    ];
}

$pageTitle = "Rejestracja";
require 'partials/header.php';

$registrationSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $phonePrefix = $_POST['phone_prefix'] ?? '';
    $phoneNumber = $_POST['phone_number'] ?? '';
    $phone = $phonePrefix . $phoneNumber;
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $captchaAnswer = $_POST['captcha'] ?? '';

    if (empty($username) || empty($email) || empty($phoneNumber) || empty($password) || empty($confirmPassword)) {
        $error = "Wszystkie pola są wymagane.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Nieprawidłowy adres e-mail.";
    } elseif (!preg_match('/^\+?[0-9]{11,15}$/', $phone)) {
        $error = "Nieprawidłowy numer telefonu. Upewnij się, że jest to numer w formacie międzynarodowym.";
    } elseif ($password !== $confirmPassword) {
        $error = "Hasła nie pasują do siebie.";
    } elseif ((int)$captchaAnswer !== ($_SESSION['captcha']['num1'] + $_SESSION['captcha']['num2'])) {
        $error = "Nieprawidłowa odpowiedź w polu Captcha.";
    } else {
        // Sprawdzenie, czy użytkownik już istnieje
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR phone = :phone");
        $stmt->execute(['email' => $email, 'phone' => $phone]);
        if ($stmt->rowCount() > 0) {
            $error = "Konto o podanych danych już istnieje.";
        } else {
            // Rejestracja nowego użytkownika
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, password) VALUES (:username, :email, :phone, :password)");
            $result = $stmt->execute([
                'username' => $username,
                'email' => $email,
                'phone' => $phone,
                'password' => $hashedPassword,
            ]);

            if ($result) {
                unset($_SESSION['captcha']); // Usuwanie captchy po udanej rejestracji
                $registrationSuccess = true; // Flaga dla dymka
                header("refresh:3;url=login.php"); // Przekierowanie po 3 sekundach
            } else {
                $error = "Wystąpił błąd podczas rejestracji.";
            }
        }
    }
}
?>
<section class="form-container">
    <h2>Rejestracja</h2>
    <?php if ($registrationSuccess): ?>
        <div class="success-message">
            Rejestracja przebiegła pomyślnie! Możesz się już <a href="login.php">zalogować</a>.
        </div>
    <?php else: ?>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="register.php" class="styled-form">
            <div class="form-group">
                <label for="username">Login:</label>
                <input type="text" id="username" name="username" placeholder="Wpisz swój login" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" placeholder="Podaj swój e-mail" required>
            </div>
            <div class="form-group">
                <label for="phone">Numer telefonu:</label>
                <div class="phone-input">
                    <select name="phone_prefix" id="phone_prefix" required>
                        <option value="+48">+48 (Polska)</option>
                        <option value="+44">+44 (Wielka Brytania)</option>
                        <option value="+49">+49 (Niemcy)</option>
                        <option value="+1">+1 (USA/Kanada)</option>
                        <!-- Dodaj więcej prefiksów według potrzeb -->
                    </select>
                    <input type="text" id="phone_number" name="phone_number" placeholder="123456789" required pattern="\d{9}" title="Podaj dokładnie 9 cyfr">
                </div>
            </div>
            <div class="form-group">
                <label for="password">Hasło:</label>
                <input type="password" id="password" name="password" placeholder="Wprowadź hasło" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Potwierdź Hasło:</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Potwierdź hasło" required>
            </div>
            <div class="form-group">
                <label for="captcha">Captcha: Ile to <?php echo $_SESSION['captcha']['num1']; ?> + <?php echo $_SESSION['captcha']['num2']; ?>?</label>
                <input type="text" id="captcha" name="captcha" placeholder="Podaj wynik" required>
            </div>
            <button type="submit" class="btn-primary">Zarejestruj się</button>
        </form>
        <p class="form-footer">Masz już konto? <a href="login.php">Zaloguj się</a></p>
    <?php endif; ?>
</section>
<?php
require 'partials/footer.php';
?>