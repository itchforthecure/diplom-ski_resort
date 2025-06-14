<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (isLoggedIn()) {
    header('Location: /account/profile.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);
    
    if (empty($username) || empty($password)) {
        $error = 'Все поля обязательны для заполнения';
    } else {
        $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
        $result = mysqli_query($connection, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            if (verifyPassword($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token', $token, time() + 60*60*24*30, '/');
                    $query = "UPDATE users SET remember_token = '$token' WHERE id = {$user['id']}";
                    mysqli_query($connection, $query);
                }
                
                header('Location: /account/profile.php');
                exit;
            } else {
                $error = 'Неверное имя пользователя или пароль';
            }
        } else {
            $error = 'Неверное имя пользователя или пароль';
        }
    }
}

require_once '../includes/header.php';
?>

<div class="auth-container">
    <h2>Вход в личный кабинет</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="remember"> Запомнить меня
            </label>
        </div>
        <button type="submit" class="btn">Войти</button>
    </form>
    
    <div class="auth-links">
        <a href="register.php">Регистрация</a>
        <a href="forgot.php">Забыли пароль?</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>