<h2>Edytuj dane</h2>
<form method="POST" action="dashboard.php?action=edit-profile" class="styled-form">
    <div class="form-group">
        <label for="username">Login:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </div>
    <div class="form-group">
        <label for="phone">Numer telefonu:</label>
        <div class="phone-input">
            <select name="phone_prefix" id="phone_prefix">
                <option value="+48" <?php echo substr($user['phone'], 0, 3) === '+48' ? 'selected' : ''; ?>>+48 (Polska)</option>
                <option value="+44" <?php echo substr($user['phone'], 0, 3) === '+44' ? 'selected' : ''; ?>>+44 (Wielka Brytania)</option>
                <option value="+49" <?php echo substr($user['phone'], 0, 3) === '+49' ? 'selected' : ''; ?>>+49 (Niemcy)</option>
                <option value="+1" <?php echo substr($user['phone'], 0, 2) === '+1' ? 'selected' : ''; ?>>+1 (USA/Kanada)</option>
            </select>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars(substr($user['phone'], strpos($user['phone'], '+') !== false ? 3 : 0)); ?>" required pattern="\d{9}" title="Podaj dokładnie 9 cyfr">
        </div>
    </div>
    <div class="form-group">
        <label for="new_password">Nowe hasło:</label>
        <input type="password" id="new_password" name="new_password" placeholder="Wprowadź nowe hasło">
    </div>
    <div class="form-group">
        <label for="confirm_password">Potwierdź nowe hasło:</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Potwierdź nowe hasło">
    </div>
    <button type="submit" class="btn-primary">Zapisz zmiany</button>
</form>