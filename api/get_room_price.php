<?php
require_once '../includes/config.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $room_id = intval($_GET['id']);
    $query = "SELECT price_per_night FROM rooms WHERE id = $room_id";
    $result = mysqli_query($connection, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $room = mysqli_fetch_assoc($result);
        echo json_encode(['success' => true, 'price' => $room['price_per_night']]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Room not found']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>