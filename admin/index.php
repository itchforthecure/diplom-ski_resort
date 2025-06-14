<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: /account/login.php');
    exit;
}

// Статистика для админ-панели
$query = "SELECT COUNT(*) as total FROM users";
$result = mysqli_query($connection, $query);
$totalUsers = mysqli_fetch_assoc($result)['total'];

$query = "SELECT COUNT(*) as total FROM room_bookings WHERE status = 'confirmed'";
$result = mysqli_query($connection, $query);
$activeBookings = mysqli_fetch_assoc($result)['total'];

$query = "SELECT SUM(total_price) as revenue FROM room_bookings WHERE status = 'completed'";
$result = mysqli_query($connection, $query);
$revenue = mysqli_fetch_assoc($result)['revenue'] ?? 0;

$query = "SELECT COUNT(*) as total FROM reviews";
$result = mysqli_query($connection, $query);
$totalReviews = mysqli_fetch_assoc($result)['total'];

require_once '../includes/header.php';
?>

<div class="admin-container">
    <h2>Админ-панель</h2>
    
    <div class="admin-nav">
        <a href="bookings.php" class="admin-nav-item">
            <i class="fas fa-calendar-alt"></i>
            <span>Бронирования</span>
        </a>
        <a href="instructors.php" class="admin-nav-item">
            <i class="fas fa-user-tie"></i>
            <span>Инструкторы</span>
        </a>
        <a href="lifts.php" class="admin-nav-item">
            <i class="fas fa-chair"></i>
            <span>Подъемники</span>
        </a>
        <a href="../account/profile.php" class="admin-nav-item">
            <i class="fas fa-user"></i>
            <span>Мой профиль</span>
        </a>
    </div>
    
    <div class="admin-stats">
        <div class="stat-card">
            <div class="stat-value"><?php echo $totalUsers; ?></div>
            <div class="stat-label">Пользователей</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $activeBookings; ?></div>
            <div class="stat-label">Активных броней</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo number_format($revenue, 2); ?> руб.</div>
            <div class="stat-label">Доход</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $totalReviews; ?></div>
            <div class="stat-label">Отзывов</div>
        </div>
    </div>
    
    <div class="recent-activity">
        <h3>Последние бронирования</h3>
        <?php
        $query = "SELECT rb.*, u.username, r.name as room_name 
                  FROM room_bookings rb 
                  JOIN users u ON rb.user_id = u.id 
                  JOIN rooms r ON rb.room_id = r.id 
                  ORDER BY booking_date DESC LIMIT 5";
        $result = mysqli_query($connection, $query);
        
        if (mysqli_num_rows($result) > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Пользователь</th>
                        <th>Номер</th>
                        <th>Даты</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $booking['id']; ?></td>
                            <td><?php echo $booking['username']; ?></td>
                            <td><?php echo $booking['room_name']; ?></td>
                            <td><?php echo date('d.m.Y', strtotime($booking['check_in'])); ?> - <?php echo date('d.m.Y', strtotime($booking['check_out'])); ?></td>
                            <td><?php echo $booking['total_price']; ?> руб.</td>
                            <td><span class="status-<?php echo $booking['status']; ?>"><?php echo $booking['status']; ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Нет данных о бронированиях.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>