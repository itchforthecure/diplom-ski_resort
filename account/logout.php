<?php
// Подключаем конфигурационный файл
require_once '../includes/config.php';

// Начинаем сессию (если еще не начата)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Удаляем все данные сессии
$_SESSION = array();

// 2. Удаляем куки сессии
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 3. Удаляем remember_token из базы данных и куки
if (isset($_COOKIE['remember_token'])) {
    // Подключаемся к базе данных
    require_once '../includes/config.php';
    
    $token = $_COOKIE['remember_token'];
    $token = mysqli_real_escape_string($connection, $token);
    
    // Обнуляем remember_token в базе данных
    $query = "UPDATE users SET remember_token = NULL WHERE remember_token = '$token'";
    mysqli_query($connection, $query);
    
    // Удаляем куку
    setcookie('remember_token', '', time() - 3600, '/');
}

// 4. Уничтожаем сессию
session_destroy();

// 5. Перенаправляем на главную страницу
header('Location: /index.php');
exit;
?>