<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');
session_start();

// Проверка аутентификации
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit;
}

// Проверка CSRF-токена (рекомендуется добавить)
// if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
//     http_response_code(403);
//     echo json_encode(['success' => false, 'message' => 'Недействительный токен безопасности']);
//     exit;
// }

// Проверка обязательных полей
$requiredFields = ['activity_id', 'booking_date', 'participants'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Не заполнено обязательное поле: $field"]);
        exit;
    }
}

// Валидация данных
$activityId = (int)$_POST['activity_id'];
$userId = (int)$_SESSION['user_id'];
$bookingDate = trim($_POST['booking_date']);
$participants = (int)$_POST['participants'];

// Проверка даты
if (!DateTime::createFromFormat('Y-m-d\TH:i', $bookingDate)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Неверный формат даты']);
    exit;
}

// Получаем информацию об активности
try {
    $stmt = $connection->prepare("SELECT * FROM activities WHERE id = ?");
    $stmt->bind_param("i", $activityId);
    $stmt->execute();
    $activity = $stmt->get_result()->fetch_assoc();
    
    if (!$activity) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Активность не найдена']);
        exit;
    }
    
    // Проверка участников
    if ($participants < $activity['min_people'] || $participants > $activity['max_people']) {
        http_response_code(400);
        echo json_encode([
            'success' => false, 
            'message' => "Количество участников должно быть от {$activity['min_people']} до {$activity['max_people']}"
        ]);
        exit;
    }
    
    // Проверка доступности даты (можно добавить дополнительную логику)
    $bookingDateTime = new DateTime($bookingDate);
    $currentDateTime = new DateTime();
    
    if ($bookingDateTime < $currentDateTime) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Нельзя забронировать на прошедшую дату']);
        exit;
    }
    
    // Создаем бронирование
    $stmt = $connection->prepare("
        INSERT INTO activity_bookings (
            activity_id, user_id, booking_date, participants, 
            status, created_at, total_price
        ) VALUES (?, ?, ?, ?, 'confirmed', NOW(), ?)
    ");
    
    $totalPrice = $activity['price'] * $participants;
    $stmt->bind_param(
        "issid", 
        $activityId, 
        $userId, 
        $bookingDate, 
        $participants,
        $totalPrice
    );
    
    if ($stmt->execute()) {
        $bookingId = $stmt->insert_id;
        
        // Можно добавить отправку email или другие действия
        
        echo json_encode([
            'success' => true,
            'message' => 'Бронирование успешно создано',
            'booking_id' => $bookingId,
            'total_price' => $totalPrice
        ]);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка сервера при создании бронирования',
        'error' => $e->getMessage() // Только для разработки, в продакшене убрать
    ]);
    error_log("Booking error: " . $e->getMessage());
}