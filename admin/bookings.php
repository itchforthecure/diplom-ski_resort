<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: /account/login.php');
    exit;
}

// Обработка изменения статуса брони
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $booking_id = intval($_POST['booking_id']);
    $new_status = sanitize($_POST['new_status']);
    
    $query = "UPDATE room_bookings SET status = '$new_status' WHERE id = $booking_id";
    if (mysqli_query($connection, $query)) {
        $_SESSION['success_message'] = 'Статус бронирования успешно обновлен';
        header('Location: bookings.php');
        exit;
    } else {
        $error = 'Ошибка при обновлении статуса: ' . mysqli_error($connection);
    }
}

// Получение списка бронирований
$query = "SELECT rb.*, u.username, u.email, r.name as room_name 
          FROM room_bookings rb 
          JOIN users u ON rb.user_id = u.id 
          JOIN rooms r ON rb.room_id = r.id 
          ORDER BY booking_date DESC";
$result = mysqli_query($connection, $query);
$bookings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bookings[] = $row;
}

require_once '../includes/header.php';
?>

<div class="admin-container">
    <h2>Управление бронированиями</h2>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="admin-actions">
        <a href="index.php" class="btn btn-back">Назад в админ-панель</a>
    </div>
    
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Пользователь</th>
                    <th>Email</th>
                    <th>Номер</th>
                    <th>Даты</th>
                    <th>Гости</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo $booking['id']; ?></td>
                        <td><?php echo $booking['username']; ?></td>
                        <td><?php echo $booking['email']; ?></td>
                        <td><?php echo $booking['room_name']; ?></td>
                        <td><?php echo date('d.m.Y', strtotime($booking['check_in'])); ?> - <?php echo date('d.m.Y', strtotime($booking['check_out'])); ?></td>
                        <td><?php echo $booking['guests']; ?></td>
                        <td><?php echo $booking['total_price']; ?> руб.</td>
                        <td><span class="status-<?php echo $booking['status']; ?>"><?php echo $booking['status']; ?></span></td>
                        <td>
                            <form method="POST" action="" class="status-form">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                <select name="new_status" class="status-select">
                                    <option value="pending" <?php echo $booking['status'] === 'pending' ? 'selected' : ''; ?>>Ожидание</option>
                                    <option value="confirmed" <?php echo $booking['status'] === 'confirmed' ? 'selected' : ''; ?>>Подтверждено</option>
                                    <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>>Отменено</option>
                                    <option value="completed" <?php echo $booking['status'] === 'completed' ? 'selected' : ''; ?>>Завершено</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-sm">Обновить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>