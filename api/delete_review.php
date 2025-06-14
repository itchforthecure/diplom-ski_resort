<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Проверка авторизации
if (!isLoggedIn()) {
    header('Location: /account/login.php');
    exit;
}

// Проверка наличия ID отзыва
if (!isset($_POST['review_id']) || empty($_POST['review_id'])) {
    $_SESSION['error'] = 'Не указан ID отзыва';
    header('Location: reviews.php');
    exit;
}

$review_id = intval($_POST['review_id']);

// Получаем информацию об отзыве
$query = "SELECT user_id FROM reviews WHERE id = $review_id";
$result = mysqli_query($connection, $query);
$review = mysqli_fetch_assoc($result);

// Проверка прав на удаление (админ или автор отзыва)
if (!isAdmin() && $_SESSION['user_id'] != $review['user_id']) {
    $_SESSION['error'] = 'У вас нет прав на удаление этого отзыва';
    header('Location: reviews.php');
    exit;
}

// Удаление отзыва
$delete_query = "DELETE FROM reviews WHERE id = $review_id";
if (mysqli_query($connection, $delete_query)) {
    $_SESSION['success'] = 'Отзыв успешно удален';
} else {
    $_SESSION['error'] = 'Ошибка при удалении отзыва: ' . mysqli_error($connection);
}

header('Location: /pages/reviews.php');
exit;
?>