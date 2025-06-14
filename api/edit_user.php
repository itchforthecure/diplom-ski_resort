<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Проверка авторизации и прав администратора
if (!isLoggedIn() || !isAdmin()) {
    header('Location: /account/login.php');
    exit;
}

// Получаем ID пользователя для редактирования
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$user_id) {
    $_SESSION['error_message'] = 'ID пользователя не указан';
    header('Location: /admin/users.php');
    exit;
}

// Получаем данные пользователя
$query = "SELECT id, username, email, full_name, phone, role, reg_date FROM users WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    $_SESSION['error_message'] = 'Пользователь не найден';
    header('Location: /admin/users.php');
    exit;
}

// Обработка формы редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    // Валидация и санитизация входных данных
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Проверка обязательных полей
    $errors = [];
    if (empty($username)) {
        $errors['username'] = 'Имя пользователя обязательно';
    }
    if (empty($email)) {
        $errors['email'] = 'Email обязателен';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный формат email';
    }
    if (!empty($password) && strlen($password) < 6) {
        $errors['password'] = 'Пароль должен содержать минимум 6 символов';
    }

    // Проверка уникальности email
    if (!isset($errors['email'])) {
        $email_check_query = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = mysqli_prepare($connection, $email_check_query);
        mysqli_stmt_bind_param($stmt, 'si', $email, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors['email'] = 'Этот email уже используется другим пользователем';
        }
    }

    // Если нет ошибок - обновляем данные
    if (empty($errors)) {
        // Санитизация данных
        $username = sanitize($username);
        $email = sanitize($email);
        $full_name = sanitize($full_name);
        $phone = sanitize($phone);
        $role = sanitize($role);

        // Обновление данных пользователя
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET username = ?, email = ?, full_name = ?, phone = ?, role = ?, password = ? WHERE id = ?";
            $stmt = mysqli_prepare($connection, $update_query);
            mysqli_stmt_bind_param($stmt, 'ssssssi', $username, $email, $full_name, $phone, $role, $hashed_password, $user_id);
        } else {
            $update_query = "UPDATE users SET username = ?, email = ?, full_name = ?, phone = ?, role = ? WHERE id = ?";
            $stmt = mysqli_prepare($connection, $update_query);
            mysqli_stmt_bind_param($stmt, 'sssssi', $username, $email, $full_name, $phone, $role, $user_id);
        }

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_message'] = 'Данные пользователя успешно обновлены';
            header('Location: /admin/users.php');
            exit;
        } else {
            $error = 'Ошибка при обновлении данных: ' . mysqli_error($connection);
        }
    }
}

require_once '../includes/header.php';
?>

<div class="admin-container">
    <h2>Редактирование пользователя</h2>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        
        <div class="form-group">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            <?php if (isset($errors['username'])): ?>
                <small class="text-danger"><?= $errors['username'] ?></small>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <?php if (isset($errors['email'])): ?>
                <small class="text-danger"><?= $errors['email'] ?></small>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="full_name">Полное имя:</label>
            <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="phone">Телефон:</label>
            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="role">Роль:</label>
            <select id="role" name="role" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Пользователь</option>
                <option value="instructor" <?= $user['role'] === 'instructor' ? 'selected' : '' ?>>Инструктор</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Администратор</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="password">Новый пароль (оставьте пустым, чтобы не менять):</label>
            <input type="password" id="password" name="password">
            <?php if (isset($errors['password'])): ?>
                <small class="text-danger"><?= $errors['password'] ?></small>
            <?php endif; ?>
        </div>
        
        <div class="form-actions">
            <button type="submit" name="update_user" class="btn btn-primary">Сохранить изменения</button>
            <a href="users.php" class="btn btn-back">Отмена</a>
        </div>
    </form>
</div>

<style>
.admin-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.alert {
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 4px;
    border: 1px solid transparent;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #495057;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="tel"],
.form-group input[type="password"],
.form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
    transition: border-color 0.15s ease-in-out;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.text-danger {
    color: #dc3545;
    font-size: 14px;
    margin-top: 5px;
    display: block;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 25px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.btn {
    padding: 10px 18px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    font-weight: 400;
    transition: all 0.15s ease-in-out;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0069d9;
}

.btn-back {
    background-color: #6c757d;
    color: white;
}

.btn-back:hover {
    background-color: #5a6268;
}
</style>

<?php require_once '../includes/footer.php'; ?>