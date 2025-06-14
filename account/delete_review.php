<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: /account/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_id'])) {
    $review_id = intval($_POST['review_id']);
    $user_id = $_SESSION['user_id'];
    
    // Проверяем, может ли пользователь удалить этот отзыв
    $query = "SELECT user_id FROM reviews WHERE id = $review_id";
    $result = mysqli_query($connection, $query);
    $review = mysqli_fetch_assoc($result);
    
    if ($review && (isAdmin() || $review['user_id'] == $user_id)) {
        $delete_query = "DELETE FROM reviews WHERE id = $review_id";
        mysqli_query($connection, $delete_query);
    }
}

// Возвращаем обратно на страницу отзывов
header('Location: /pages/reviews.php');
exit;
?>