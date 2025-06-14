<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isset($_GET['instructor_id'])) {
    die('<div class="alert alert-danger">Не указан ID инструктора</div>');
}

if (!isLoggedIn()) {
    die('<div class="alert alert-warning">Пожалуйста, <a href="/account/login.php" class="alert-link">войдите</a>, чтобы забронировать инструктора</div>');
}

$instructor_id = intval($_GET['instructor_id']);
$user_id = $_SESSION['user_id'];

$query = "SELECT id, name, price_per_hour FROM instructors WHERE id = ? AND is_active = 1";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $instructor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    die('<div class="alert alert-danger">Инструктор не найден или недоступен</div>');
}

$instructor = mysqli_fetch_assoc($result);
?>

<div class="booking-container">
    <h3 class="booking-title">Бронирование инструктора</h3>
    
    <div class="instructor-info">
        <p><strong>Инструктор:</strong> <?= htmlspecialchars($instructor['name']) ?></p>
        <p><strong>Цена за час:</strong> <span id="price-per-hour"><?= $instructor['price_per_hour'] ?></span> руб.</p>
    </div>

    <form id="booking-form" method="POST" action="/account/book_instructor.php">
        <input type="hidden" name="instructor_id" value="<?= $instructor_id ?>">
        <input type="hidden" name="user_id" value="<?= $user_id ?>">
        
        <div class="form-group">
            <label for="start_datetime">Дата и время начала:</label>
            <input type="datetime-local" id="start_datetime" name="start_datetime" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="duration">Продолжительность (часы):</label>
            <select id="duration" name="duration" class="form-control" required>
                <?php for ($i = 1; $i <= 8; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?> час<?= $i > 1 ? 'а' : '' ?></option>
                <?php endfor; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="notes">Ваши пожелания:</label>
            <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Укажите дополнительные пожелания к занятию"></textarea>
        </div>
        
        <div class="form-group">
            <label>Итого к оплате:</label>
            <div id="total-price" class="total-price"><?= $instructor['price_per_hour'] ?> руб.</div>
            <input type="hidden" id="calculated-price" name="calculated_price" value="<?= $instructor['price_per_hour'] ?>">
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Подтвердить бронирование</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const durationSelect = document.getElementById('duration');
    const totalPriceElement = document.getElementById('total-price');
    const calculatedPriceInput = document.getElementById('calculated-price');
    const pricePerHour = parseFloat(document.getElementById('price-per-hour').textContent);
    const startDatetime = document.getElementById('start_datetime');
    
    // Установка минимальной даты/времени
    const now = new Date();
    now.setHours(now.getHours() + 1);
    const timezoneOffset = now.getTimezoneOffset() * 60000;
    const localISOTime = new Date(now - timezoneOffset).toISOString().slice(0, 16);
    startDatetime.min = localISOTime;
    startDatetime.value = localISOTime;
    
    // Функция пересчета цены
    function updatePrice() {
        const hours = parseInt(durationSelect.value);
        const totalPrice = hours * pricePerHour;
        totalPriceElement.textContent = totalPrice.toLocaleString('ru-RU') + ' руб.';
        calculatedPriceInput.value = totalPrice;
    }
    
    // Обработчик изменения количества часов
    durationSelect.addEventListener('change', updatePrice);
    
    // Инициализация при загрузке
    updatePrice();
});
</script>