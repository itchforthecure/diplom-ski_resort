<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid event ID']);
    exit;
}

$eventId = (int)$_GET['id'];
$query = "SELECT * FROM calendar_events WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 'i', $eventId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($event = mysqli_fetch_assoc($result)) {
    // Преобразуем время для корректного отображения
    if ($event['start_time']) {
        $event['start_time'] = date('H:i', strtotime($event['start_time']));
    }
    echo json_encode(['success' => true, 'event' => $event]);
} else {
    echo json_encode(['success' => false, 'error' => 'Event not found']);
}