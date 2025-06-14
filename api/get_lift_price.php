<?php
require_once '../includes/config.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $lift_id = intval($_GET['id']);
    $query = "SELECT price_per_day FROM lifts WHERE id = $lift_id";
    $result = mysqli_query($connection, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $lift = mysqli_fetch_assoc($result);
        echo json_encode(['success' => true, 'price' => $lift['price_per_day']]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Lift not found']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>