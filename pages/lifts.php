<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Проверка соединения с базой данных
if (!$connection) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}

// Обработка AJAX-запроса на бронирование
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_pass'])) {
    header('Content-Type: application/json');
    
    // Проверка авторизации
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Для бронирования необходимо авторизоваться']);
        exit;
    }

    // Валидация данных
    $pass_id = isset($_POST['pass_id']) ? intval($_POST['pass_id']) : 0;
    $start_date = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
    $end_date = isset($_POST['end_date']) ? trim($_POST['end_date']) : '';
    $user_id = $_SESSION['user_id'];
    $booking_date = date('Y-m-d H:i:s');
    $status = 'active';

    // Проверка обязательных полей
    if ($pass_id <= 0 || empty($start_date) || empty($end_date)) {
        echo json_encode(['success' => false, 'message' => 'Не все обязательные поля заполнены']);
        exit;
    }

    try {
        // Проверка валидности дат
        $date1 = new DateTime($start_date);
        $date2 = new DateTime($end_date);
        
        if ($date2 <= $date1) {
            echo json_encode(['success' => false, 'message' => 'Дата окончания должна быть позже даты начала']);
            exit;
        }

        // Получаем данные ски-пасса
        $stmt = mysqli_prepare($connection, "SELECT price_per_day, duration FROM ski_passes WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $pass_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) === 0) {
            echo json_encode(['success' => false, 'message' => 'Ски-пасс не найден']);
            exit;
        }

        $pass = mysqli_fetch_assoc($result);
        $days = $date2->diff($date1)->days;
        $total_price = $days * $pass['price_per_day'];

        // Создание брони
        $stmt = mysqli_prepare($connection, 
            "INSERT INTO pass_bookings (user_id, pass_id, start_date, end_date, price, status, booking_date) 
             VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iissdss", $user_id, $pass_id, $start_date, $end_date, $total_price, $status, $booking_date);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode([
                'success' => true, 
                'message' => 'Ски-пасс успешно оформлен!',
                'booking' => [
                    'id' => mysqli_insert_id($connection),
                    'pass_id' => $pass_id,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'price' => $total_price,
                    'days' => $days,
                    'booking_date' => $booking_date,
                    'status' => $status
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ошибка при бронировании: ' . mysqli_error($connection)]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Произошла ошибка: ' . $e->getMessage()]);
    }
    exit;
}

// Получение списка ски-пассов
$query = "SELECT * FROM ski_passes ORDER BY duration, price_per_day";
$result = mysqli_query($connection, $query);
$passes = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $passes[] = $row;
    }
}

require_once '../includes/header.php';
?>

<section class="passes-section">
    <h2>Ски-пассы</h2>
    
    <div class="passes-grid">
        <?php foreach ($passes as $pass): ?>
            <div class="pass-card">
                <div class="pass-info">
                    <h3><?php echo htmlspecialchars($pass['name']); ?></h3>
                    <p><?php echo htmlspecialchars($pass['description']); ?></p>
                    <div class="pass-price">
                        <i class="fas fa-tag"></i> <?php echo number_format($pass['price_per_day'], 2); ?> руб./день
                    </div>
                    <div class="pass-duration">
                        <i class="fas fa-calendar-alt"></i> <?php echo $pass['duration']; ?> дней
                    </div>
                    <?php if (!empty($pass['benefits'])): ?>
                        <div class="pass-benefits">
                            <i class="fas fa-star"></i> <?php echo htmlspecialchars($pass['benefits']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form class="booking-form" data-pass-id="<?php echo htmlspecialchars($pass['id']); ?>">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Дата начала:</label>
                                <input type="date" name="start_date" class="start-date" required>
                            </div>
                            <div class="form-group">
                                <label>Дата окончания:</label>
                                <input type="date" name="end_date" class="end-date" required>
                            </div>
                        </div>
                        
                        <div class="price-summary">
                            <div class="total-days">Дней: <span>0</span></div>
                            <div class="total-price">Итого: <span>0</span> руб.</div>
                        </div>
                        
                        <?php if (isLoggedIn()): ?>
                            <button type="submit" class="btn book-btn">Забронировать</button>
                        <?php else: ?>
                            <a href="/account/login.php" class="btn">Войти для бронирования</a>
                        <?php endif; ?>
                        
                        <div class="booking-result"></div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Модальное окно для сообщений -->
<div id="messageModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div id="modalMessage" class="modal-message"></div>
        <button id="confirmModal" class="btn modal-btn">OK</button>
    </div>
</div>

<style>
/* Основные стили */
.passes-section {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.passes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 25px;
    margin-top: 30px;
}

.pass-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    padding: 20px;
    display: flex; /* Добавляем flex-контейнер */
    flex-direction: column; /* Вертикальное расположение элементов */
    height: 100%; /* Занимаем всю доступную высоту */
    position: relative; /* Для абсолютного позиционирования формы */
}

.pass-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.pass-info {
    padding: 0;
    flex: 1; /* Занимаем все доступное пространство */
    display: flex;
    flex-direction: column;
}

.pass-price, .pass-duration {
    margin: 10px 0;
    font-weight: bold;
    color: #2a6496;
}

.pass-benefits {
    margin: 10px 0;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 4px;
    font-size: 0.9em;
}

.pass-benefits i {
    margin-right: 5px;
    color: #ffc107;
}

.booking-form {
    margin-top: 15px;
    border-top: 1px solid #eee;
    padding-top: 15px;
}

.form-row {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.form-group {
    flex: 1;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 0.9em;
    color: #555;
}

.form-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-family: inherit;
}

.price-summary {
    margin: 15px 0;
    font-size: 0.95em;
}

.price-summary div {
    margin-bottom: 5px;
}

.price-summary span {
    font-weight: bold;
    color: #2a6496;
}

.btn {
    display: block;
    width: 100%;
    padding: 10px;
    background: #2a6496;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: background 0.3s ease;
    font-family: inherit;
    font-size: 1em;
}

.btn:hover {
    background: #1d4b7b;
}

.btn:disabled {
    background: #cccccc;
    cursor: not-allowed;
}

.booking-result {
    margin-top: 10px;
    min-height: 20px;
    font-size: 0.9em;
    text-align: center;
}
.booking-form {
    margin-top: 15px;
    border-top: 1px solid #eee;
    padding-top: 15px;
    margin-top: auto; /* Прижимаем форму к низу */
}

.loading {
    color: #666;
    font-style: italic;
}

.success-message {
    color: #28a745;
    margin-bottom: 10px;
}

.error-message {
    color: #dc3545;
}

.booking-details {
    margin-top: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 4px;
    font-size: 0.9em;
    text-align: left;
}

/* Стили для модального окна */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s;
}

.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 25px;
    border-radius: 8px;
    width: 80%;
    max-width: 500px;
    position: relative;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.close-modal {
    position: absolute;
    right: 15px;
    top: 10px;
    font-size: 24px;
    cursor: pointer;
    color: #aaa;
}

.close-modal:hover {
    color: #333;
}

.modal-message {
    margin: 20px 0;
    font-size: 1.1em;
    line-height: 1.5;
    text-align: center;
}

.modal-btn {
    margin-top: 20px;
    width: auto;
    padding: 8px 20px;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Анимации карточек */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.pass-card {
    animation: fadeIn 0.5s ease forwards;
    opacity: 0;
}

<?php foreach ($passes as $i => $pass): ?>
.pass-card:nth-child(<?php echo $i + 1; ?>) {
    animation-delay: <?php echo $i * 0.1; ?>s;
}
<?php endforeach; ?>
</style>

<script>
// Глобальные переменные для модального окна
const modal = document.getElementById('messageModal');
const modalMessage = document.getElementById('modalMessage');
const closeModal = document.querySelector('.close-modal');
const confirmModal = document.getElementById('confirmModal');

// Функция для отображения модального окна
function showModal(message, isError = true) {
    modalMessage.textContent = message;
    if (isError) {
        modalMessage.style.color = '#dc3545';
    } else {
        modalMessage.style.color = '#28a745';
    }
    modal.style.display = 'block';
}

// Закрытие модального окна
function closeModalWindow() {
    modal.style.display = 'none';
}

// Обработчики событий для модального окна
closeModal.addEventListener('click', closeModalWindow);
confirmModal.addEventListener('click', closeModalWindow);
window.addEventListener('click', function(event) {
    if (event.target == modal) {
        closeModalWindow();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Установка минимальной даты (завтра)
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const minDate = tomorrow.toISOString().split('T')[0];
    
    document.querySelectorAll('.start-date').forEach(input => {
        input.min = minDate;
        input.addEventListener('change', function() {
            const endDate = this.closest('.form-row').querySelector('.end-date');
            endDate.min = this.value;
        });
    });

    // Обработка бронирования
    document.querySelectorAll('.booking-form').forEach(form => {
        const startDate = form.querySelector('.start-date');
        const endDate = form.querySelector('.end-date');
        const totalDays = form.querySelector('.total-days span');
        const totalPrice = form.querySelector('.total-price span');
        const resultDiv = form.querySelector('.booking-result');
        const bookBtn = form.querySelector('.book-btn');
        
        // Расчет стоимости
        function calculatePrice() {
            if (startDate.value && endDate.value) {
                const start = new Date(startDate.value);
                const end = new Date(endDate.value);
                const days = Math.round((end - start) / (1000 * 60 * 60 * 24));
                const pricePerDay = parseFloat(form.closest('.pass-card').querySelector('.pass-price').textContent.replace(/[^\d.]/g, ''));
                const total = days * pricePerDay;
                
                totalDays.textContent = days;
                totalPrice.textContent = total.toFixed(2);
            }
        }
        
        startDate.addEventListener('change', calculatePrice);
        endDate.addEventListener('change', calculatePrice);
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!startDate.value || !endDate.value) {
                showModal('Пожалуйста, укажите даты бронирования');
                return;
            }
            
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            
            if (end <= start) {
                showModal('Дата окончания должна быть позже даты начала');
                return;
            }
            
            resultDiv.innerHTML = '<div class="loading">Проверка доступности...</div>';
            if (bookBtn) bookBtn.disabled = true;
            
            const formData = new FormData(form);
            formData.append('pass_id', form.getAttribute('data-pass-id'));
            formData.append('book_pass', '1');
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Ошибка сети');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showModal(data.message, false);
                    resultDiv.innerHTML = `
                        <div class="booking-details">
                            <p><strong>${form.closest('.pass-card').querySelector('h3').textContent}</strong></p>
                            <p>Даты: ${data.booking.start_date} - ${data.booking.end_date}</p>
                            <p>Дней: ${data.booking.days}</p>
                            <p>Сумма: ${data.booking.price.toFixed(2)} руб.</p>
                        </div>
                    `;
                    if (bookBtn) {
                        bookBtn.disabled = true;
                        bookBtn.textContent = 'Забронировано';
                    }
                } else {
                    showModal(data.message);
                    if (bookBtn) bookBtn.disabled = false;
                }
            })
            .catch(error => {
                showModal('Ошибка соединения с сервером');
                console.error('Error:', error);
                if (bookBtn) bookBtn.disabled = false;
            });
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>