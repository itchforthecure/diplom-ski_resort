
<?php
header('Content-Type: application/json');

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Недопустимый метод запроса']);
    exit;
}

// Подключение к базе данных
$db_host = 'localhost';
$db_user = 'zqbdxmz0_ski';
$db_pass = 'Root123!';
$db_name = 'zqbdxmz0_ski';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Ошибка подключения к базе данных']);
    exit;
}

// Получение и очистка данных
$name = mysqli_real_escape_string($conn, trim($_POST['name'] ?? ''));
$email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
$message = mysqli_real_escape_string($conn, trim($_POST['message'] ?? ''));

// Валидация
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Все поля обязательны для заполнения']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Укажите корректный email']);
    exit;
}

// SQL запрос
$query = "INSERT INTO feedback (name, email, message, created_at) 
          VALUES ('$name', '$email', '$message', NOW())";

if (mysqli_query($conn, $query)) {
    echo json_encode([
        'success' => true,
        'message' => 'Спасибо за ваше сообщение! Мы свяжемся с вами в ближайшее время.'
    ]);
} else {
    error_log('MySQL error: ' . mysqli_error($conn));
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка при сохранении сообщения. Пожалуйста, попробуйте позже.'
    ]);
}

mysqli_close($conn);