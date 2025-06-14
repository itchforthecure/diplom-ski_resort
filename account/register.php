<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (isLoggedIn()) {
    header('Location: /account/profile.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = sanitize($_POST['full_name']);
    $phone = sanitize($_POST['phone']);
    
    // Валидация
    if (empty($username)) $errors[] = 'Имя пользователя обязательно';
    if (empty($email)) $errors[] = 'Email обязателен';
    if (!isValidEmail($email)) $errors[] = 'Некорректный email';
    if (empty($password)) $errors[] = 'Пароль обязателен';
    if ($password !== $confirm_password) $errors[] = 'Пароли не совпадают';
    if (strlen($password) < 6) $errors[] = 'Пароль должен быть не менее 6 символов';
    
    // Проверка уникальности username и email
    $query = "SELECT id FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) $errors[] = 'Имя пользователя уже занято';
    
    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) $errors[] = 'Email уже используется';
    
    if (empty($errors)) {
        $hashed_password = hashPassword($password);
        $query = "INSERT INTO users (username, email, password, full_name, phone) 
                  VALUES ('$username', '$email', '$hashed_password', '$full_name', '$phone')";
        
        if (mysqli_query($connection, $query)) {
            $_SESSION['success_message'] = 'Регистрация прошла успешно. Теперь вы можете войти.';
            header('Location: login.php');
            exit;
        } else {
            $errors[] = 'Ошибка при регистрации: ' . mysqli_error($connection);
        }
    }
}

require_once '../includes/header.php';
?>

<div class="auth-container">
    <h2>Регистрация</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Подтвердите пароль:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <div class="form-group">
            <label for="full_name">ФИО:</label>
            <input type="text" id="full_name" name="full_name">
        </div>
        <div class="form-group">
            <label for="phone">Телефон:</label>
            <input type="text" id="phone" name="phone">
        </div>
        <button type="submit" class="btn">Зарегистрироваться</button>
    </form>
    
    <div class="auth-links">
        <a href="login.php">Уже есть аккаунт? Войти</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>