<?php
// Настройки базы данных
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'ski_resort');

// Настройки сессии
session_start();

// Подключение к базе данных
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$connection) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

// Функция для защиты от XSS
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Проверка авторизации
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Проверка роли администратора
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>