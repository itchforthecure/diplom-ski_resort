<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: /account/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = getUserById($user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $phone = sanitize($_POST['phone']);
    $email = sanitize($_POST['email']);
    
    $query = "UPDATE users SET full_name = '$full_name', phone = '$phone', email = '$email' WHERE id = $user_id";
    if (mysqli_query($connection, $query)) {
        $_SESSION['success_message'] = 'Профиль успешно обновлен';
        header('Location: profile.php');
        exit;
    } else {
        $error = 'Ошибка при обновлении профиля: ' . mysqli_error($connection);
    }
}

require_once '../includes/header.php';
?>

<style>
.profile-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.profile-tabs {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    border-bottom: 1px solid rgba(26, 115, 232, 0.1);
    padding-bottom: 1rem;
}

.tab-btn {
    padding: 0.8rem 1.5rem;
    border: none;
    background: none;
    color: var(--text-color);
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    border-radius: 5px;
}

.tab-btn:hover {
    background: rgba(26, 115, 232, 0.1);
    color: var(--primary-color);
}

.tab-btn.active {
    background: var(--primary-color);
    color: var(--white);
}

.tab-content {
    display: none;
    animation: fadeIn 0.3s ease;
}

.tab-content.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Стили для карточек бронирований */
.bookings-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.booking-card {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    border: 1px solid rgba(26, 115, 232, 0.1);
}

.booking-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(26, 115, 232, 0.15);
    border-color: var(--primary-color);
}

.booking-image {
    height: 200px;
    overflow: hidden;
}

.booking-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.booking-card:hover .booking-image img {
    transform: scale(1.05);
}

.booking-info {
    padding: 1.5rem;
}

.booking-info h4 {
    color: var(--primary-color);
    margin: 0 0 1rem;
    font-size: 1.3rem;
}

.booking-details {
    margin-bottom: 1.5rem;
}

.booking-details p {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0.5rem 0;
    color: var(--text-color);
}

.booking-details i {
    color: var(--primary-color);
    width: 20px;
    text-align: center;
}

.status-pending {
    color: #f39c12;
}

.status-confirmed {
    color: #27ae60;
}

.status-cancelled {
    color: #e74c3c;
}

.status-completed {
    color: #3498db;
}

.btn-danger {
    background: #dc3545;
    color: var(--white);
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: var(--transition);
    font-size: 0.9rem;
    text-decoration: none;
    display: inline-block;
}

.btn-danger:hover {
    background: #c82333;
    transform: translateY(-2px);
}

/* Адаптивность */
@media (max-width: 768px) {
    .profile-container {
        padding: 1rem;
    }

    .profile-tabs {
        flex-wrap: wrap;
    }

    .tab-btn {
        flex: 1;
        text-align: center;
    }

    .bookings-list {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="profile-container">
    <h2>Личный кабинет</h2>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="profile-tabs">
        <button class="tab-btn active" data-tab="profile">Профиль</button>
        <button class="tab-btn" data-tab="bookings">Мои бронирования</button>
        <button class="tab-btn" data-tab="reviews">Мои отзывы</button>
    </div>
    
    <div class="tab-content active" id="profile-tab">
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Имя пользователя:</label>
                <input type="text" id="username" value="<?php echo $user['username']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="full_name">ФИО:</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>">
            </div>
            <div class="form-group">
                <label for="phone">Телефон:</label>
                <input type="text" id="phone" name="phone" value="<?php echo $user['phone']; ?>">
            </div>
            <button type="submit" class="btn">Сохранить изменения</button>
        </form>
        
    </div>
    
    <div class="tab-content" id="bookings-tab">
        <h3>Мои бронирования</h3>
        <?php
        $query = "SELECT rb.*, r.name as room_name, r.images as room_images 
                  FROM room_bookings rb 
                  JOIN rooms r ON rb.room_id = r.id 
                  WHERE rb.user_id = $user_id 
                  ORDER BY rb.booking_date DESC";
        $result = mysqli_query($connection, $query);
        
        if (mysqli_num_rows($result) > 0): ?>
            <div class="bookings-list">
                <?php while ($booking = mysqli_fetch_assoc($result)): ?>
                    <div class="booking-card">
                        
                        <div class="booking-info">
                            <h4><?php echo htmlspecialchars($booking['room_name']); ?></h4>
                            <div class="booking-details">
                                <p><i class="fas fa-calendar"></i> Даты: <?php echo date('d.m.Y', strtotime($booking['check_in'])); ?> - <?php echo date('d.m.Y', strtotime($booking['check_out'])); ?></p>
                                <p><i class="fas fa-user-friends"></i> Гости: <?php echo $booking['guests']; ?></p>
                                <p><i class="fas fa-ruble-sign"></i> Сумма: <?php echo $booking['total_price']; ?> руб.</p>
                                <p><i class="fas fa-info-circle"></i> Статус: <span class="status-<?php echo $booking['status']; ?>"><?php echo $booking['status']; ?></span></p>
                            </div>
                            <?php if ($booking['status'] === 'pending'): ?>
                                <a href="cancel_booking.php?id=<?php echo $booking['id']; ?>&type=room" class="btn btn-sm btn-danger">Отменить</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>У вас нет бронирований номеров.</p>
        <?php endif; ?>
        
        <h4>Мои ски-пассы</h4>
    <?php
    $query = "SELECT pb.*, sp.name as pass_name, sp.image as pass_image, sp.duration as pass_duration
              FROM pass_bookings pb
              JOIN ski_passes sp ON pb.pass_id = sp.id
              WHERE pb.user_id = $user_id 
              ORDER BY pb.booking_date DESC";
    $result = mysqli_query($connection, $query);
    
    if (mysqli_num_rows($result) > 0): ?>
        <div class="bookings-list">
            <?php while ($pass = mysqli_fetch_assoc($result)): ?>
                <div class="booking-card">
                    <div class="booking-image">
                        <?php if (!empty($pass['pass_image'])): ?>
                            <img src="/assets/images/passes/<?php echo $pass['pass_image']; ?>" 
                                 alt="<?php echo htmlspecialchars($pass['pass_name']); ?>">
                        <?php else: ?>
                            <div class="no-image"><i class="fas fa-ticket-alt"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="booking-info">
                        <h4><?php echo htmlspecialchars($pass['pass_name']); ?></h4>
                        <div class="booking-details">
                            <p><i class="fas fa-calendar-alt"></i> 
                                Даты: <?php echo date('d.m.Y', strtotime($pass['start_date'])); ?> - <?php echo date('d.m.Y', strtotime($pass['end_date'])); ?>
                            </p>
                            <p><i class="fas fa-clock"></i> 
                                Длительность: <?php echo $pass['pass_duration']; ?> дней
                            </p>
                            <p><i class="fas fa-ruble-sign"></i> 
                                Сумма: <?php echo number_format($pass['price'], 2); ?> руб.
                            </p>
                            <p><i class="fas fa-info-circle"></i> 
                                Статус: <span class="status-<?php echo $pass['status']; ?>">
                                    <?php 
                                    $statuses = [
                                        'active' => 'Активен',
                                        'cancelled' => 'Отменен',
                                        'completed' => 'Завершен'
                                    ];
                                    echo $statuses[$pass['status']] ?? $pass['status'];
                                    ?>
                                </span>
                            </p>
                            <p><i class="fas fa-calendar-check"></i> 
                                Дата бронирования: <?php echo date('d.m.Y H:i', strtotime($pass['booking_date'])); ?>
                            </p>
                        </div>
                        
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>У вас нет активных ски-пассов.</p>
    <?php endif; ?>
    </div>
    
    <div class="tab-content" id="reviews-tab">
        <h3>Мои отзывы</h3>
        <!-- <button class="btn" id="add-review-btn">Добавить отзыв</button> -->
        
        <div class="add-review-form" id="add-review-form" style="display: none;">
            <form method="POST" action="add_review.php">
                <div class="form-group">
                    <label for="review-content">Ваш отзыв:</label>
                    <textarea id="review-content" name="content" required></textarea>
                </div>
                <div class="form-group">
                    <label for="review-rating">Оценка:</label>
                    <select id="review-rating" name="rating" required>
                        <option value="5">5 - Отлично</option>
                        <option value="4">4 - Хорошо</option>
                        <option value="3">3 - Удовлетворительно</option>
                        <option value="2">2 - Плохо</option>
                        <option value="1">1 - Ужасно</option>
                    </select>
                </div>
                <button type="submit" class="btn">Отправить отзыв</button>
            </form>
        </div>
        
        <?php
        $query = "SELECT * FROM reviews WHERE user_id = $user_id ORDER BY review_date DESC";
        $result = mysqli_query($connection, $query);
        
        if (mysqli_num_rows($result) > 0): ?>
            <div class="reviews-list">
                <?php while ($review = mysqli_fetch_assoc($result)): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star<?php echo $i <= $review['rating'] ? '' : '-empty'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="review-date"><?php echo date('d.m.Y H:i', strtotime($review['review_date'])); ?></div>
                        </div>
                        <div class="review-content">
                            <?php echo nl2br($review['content']); ?>
                        </div>
                        <a href="delete_review.php?id=<?php echo $review['id']; ?>" class="btn btn-sm btn-danger">Удалить</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Вы еще не оставляли отзывов.</p>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Удаляем активный класс у всех кнопок и контента
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Добавляем активный класс текущей кнопке и контенту
            this.classList.add('active');
            document.getElementById(tabId + '-tab').classList.add('active');
        });
    });
    
    // Обработчик для кнопки добавления отзыва
    const addReviewBtn = document.getElementById('add-review-btn');
    const addReviewForm = document.getElementById('add-review-form');
    
    if (addReviewBtn && addReviewForm) {
        addReviewBtn.addEventListener('click', function() {
            addReviewForm.style.display = addReviewForm.style.display === 'none' ? 'block' : 'none';
        });
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>