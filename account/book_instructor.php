<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: /account/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['instructor_id'])) {
    header('Location: /pages/instructors.php');
    exit;
}

$instructor_id = intval($_POST['instructor_id']);
$user_id = $_SESSION['user_id'];
$start_datetime = mysqli_real_escape_string($connection, $_POST['start_datetime']);
$duration = intval($_POST['duration']);
$notes = mysqli_real_escape_string($connection, $_POST['notes'] ?? '');
$total_price = floatval($_POST['calculated_price']);

// Проверка инструктора
$query = "SELECT id, price_per_hour FROM instructors WHERE id = ? AND is_active = 1";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $instructor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    $_SESSION['error_message'] = 'Инструктор не найден или недоступен';
    header('Location: /pages/instructors.php');
    exit;
}

$instructor = mysqli_fetch_assoc($result);

// Проверка доступности
$end_datetime = date('Y-m-d H:i:s', strtotime("$start_datetime + $duration hours"));

$check_query = "SELECT id FROM instructor_bookings 
                WHERE instructor_id = ?
                AND (
                    (start_datetime <= ? AND end_datetime >= ?)
                    OR (start_datetime <= ? AND end_datetime >= ?)
                    OR (start_datetime >= ? AND end_datetime <= ?)
                )";
$check_stmt = mysqli_prepare($connection, $check_query);
mysqli_stmt_bind_param($check_stmt, "issssss", 
    $instructor_id,
    $start_datetime, $start_datetime,
    $end_datetime, $end_datetime,
    $start_datetime, $end_datetime
);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) > 0) {
    $_SESSION['error_message'] = 'Инструктор уже занят в выбранное время';
    header('Location: /pages/instructors.php');
    exit;
}

// Создание бронирования
$insert_query = "INSERT INTO instructor_bookings 
                (user_id, instructor_id, start_datetime, end_datetime, duration, total_price, notes, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'confirmed')";
$insert_stmt = mysqli_prepare($connection, $insert_query);
mysqli_stmt_bind_param($insert_stmt, "iissids",
    $user_id,
    $instructor_id,
    $start_datetime,
    $end_datetime,
    $duration,
    $total_price,
    $notes
);

if (mysqli_stmt_execute($insert_stmt)) {
    $_SESSION['success_message'] = 'Бронирование успешно создано!';
    header('Location: /account/profile.php');
} else {
    $_SESSION['error_message'] = 'Ошибка при создании бронирования: ' . mysqli_error($connection);
    header('Location: /pages/instructors.php');
}
exit;
?>