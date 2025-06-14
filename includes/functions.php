<?php
require_once 'config.php';

// Хеширование пароля
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Проверка пароля
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Валидация email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Получение пользователя по ID
function getUserById($id) {
    global $connection;
    $id = mysqli_real_escape_string($connection, $id);
    $query = "SELECT * FROM users WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($connection, $query);
    return mysqli_fetch_assoc($result);
}

// Добавление отзыва
function addReview($user_id, $content, $rating) {
    global $connection;
    $user_id = mysqli_real_escape_string($connection, $user_id);
    $content = mysqli_real_escape_string($connection, $content);
    $rating = mysqli_real_escape_string($connection, $rating);
    
    $query = "INSERT INTO reviews (user_id, content, rating) VALUES ('$user_id', '$content', '$rating')";
    return mysqli_query($connection, $query);
}

// Получение всех отзывов
function getAllReviews() {
    global $connection;
    $query = "SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id ORDER BY review_date DESC";
    $result = mysqli_query($connection, $query);
    $reviews = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
    return $reviews;
}

/**
 * Получает читаемое название специализации
 */
function getSpecializationName($code) {
    $specializations = [
        'ski' => 'Горные лыжи',
        'snowboard' => 'Сноуборд',
        'freestyle' => 'Фристайл',
        'children' => 'Детский инструктор',
        'beginner' => 'Для начинающих',
        'pro' => 'Профессиональное обучение'
    ];
    
    return $specializations[$code] ?? $code;
}



/**
 * Обрезает текст до указанной длины
 */
function truncateText($text, $length) {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . '...';
}

function getRoleName($role) {
    $roles = [
        'user' => 'Пользователь',
        'instructor' => 'Инструктор',
        'admin' => 'Администратор'
    ];
    return $roles[$role] ?? $role;
}

function getBookingCount($user_id) {
    global $connection;
    $query = "SELECT COUNT(*) as count FROM room_bookings WHERE user_id = $user_id";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getReviewsCount($user_id) {
    global $connection;
    $query = "SELECT COUNT(*) as count FROM reviews WHERE user_id = $user_id";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getRoomBookings($user_id) {
    global $connection;
    $query = "SELECT * FROM room_bookings WHERE user_id = $user_id ORDER BY booking_date DESC";
    $result = mysqli_query($connection, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getLiftPasses($user_id) {
    global $connection;
    $query = "SELECT lp.*, l.name as lift_name FROM lift_passes lp 
              JOIN lifts l ON lp.lift_id = l.id 
              WHERE lp.user_id = $user_id ORDER BY lp.booking_date DESC";
    $result = mysqli_query($connection, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getInstructorBookings($user_id) {
    global $connection;
    $query = "SELECT ib.*, i.name as instructor_name, i.photo as instructor_photo, i.rating 
              FROM instructor_bookings ib
              JOIN instructors i ON ib.instructor_id = i.id
              WHERE ib.user_id = $user_id ORDER BY ib.booking_date DESC";
    $result = mysqli_query($connection, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getUserReviews($user_id) {
    global $connection;
    $query = "SELECT * FROM reviews WHERE user_id = $user_id ORDER BY review_date DESC";
    $result = mysqli_query($connection, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
// Другие функции для работы с базой данных...
?>