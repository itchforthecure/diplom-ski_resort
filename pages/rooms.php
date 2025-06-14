<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Массив с данными о номерах
$rooms = [
    [
        'id' => 1,
        'name' => 'Стандартный номер',
        'description' => 'Уютный номер с видом на горы. В номере: двуспальная кровать, телевизор, мини-бар, ванная комната с душем.',
        'capacity' => 2,
        'price_per_night' => 5000,
        'images' => [
            'https://iv.kommersant.ru/Issues.photo/Partners_Projects/2020/12/03/KMO_111307_33734_1_t222_161957.jpg',
            'https://avatars.mds.yandex.net/get-altay/13757584/2a00000191c54d0e64ae44c4bca5d62cdc6c/XXXL',
            'https://avatars.mds.yandex.net/get-altay/11522875/2a0000018e0fb4e5e4d7ad99fcd239a64eaa/XXXL'
        ],
        'amenities' => [
            'Двуспальная кровать',
            'Телевизор',
            'Мини-бар',
            'Ванная комната',
            'Wi-Fi',
            'Кондиционер'
        ]
    ],
    [
        'id' => 2,
        'name' => 'Люкс с балконом',
        'description' => 'Просторный номер с панорамным видом на горы. В номере: двуспальная кровать, диван, телевизор, мини-бар, ванная комната с джакузи.',
        'capacity' => 3,
        'price_per_night' => 8000,
        'images' => [
            'https://tez-moscow.ru/images/hotels/gallery/603574/0-0_96621679103843.jpg',
            'https://s01.cdn-pegast.net/get/62/cc/91/7bdb3f6721ccef089cb89f9a5e09eba2531d7d5c89b7f124dccd284ae3/61a9f3b099429.jpg',
            'https://avatars.mds.yandex.net/i?id=d3c16ab3bb2c8b5bd3318616eb67885f_l-5232177-images-thumbs&ref=rim&n=13&w=1600&h=1000'
        ],
        'amenities' => [
            'Двуспальная кровать',
            'Диван',
            'Телевизор',
            'Мини-бар',
            'Ванная комната с джакузи',
            'Балкон',
            'Wi-Fi',
            'Кондиционер'
        ]
    ],
    [
        'id' => 3,
        'name' => 'Семейный номер',
        'description' => 'Большой номер для семьи. В номере: две двуспальные кровати, телевизор, мини-бар, ванная комната с душем.',
        'capacity' => 4,
        'price_per_night' => 10000,
        'images' => [
            'https://i.tez-tour.travel/img/hotels/80487/13.jpg',
            'https://avatars.dzeninfra.ru/get-zen_doc/9116192/pub_644a68864da1351cb79da470_644a6bdb283fb472598d67c8/scale_1200',
            'https://buduvsochi.ru/up/files/images/gostinicty/Mercure_04.jpg'
        ],
        'amenities' => [
            'Две двуспальные кровати',
            'Телевизор',
            'Мини-бар',
            'Ванная комната',
            'Wi-Fi',
            'Кондиционер'
        ]
    ]
];

// Обработка бронирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_room'])) {
    if (!isLoggedIn()) {
        $_SESSION['redirect_url'] = '/pages/rooms.php';
        header('Location: /account/login.php');
        exit;
    }
    
    $room_id = intval($_POST['room_id']);
    $check_in = sanitize($_POST['check_in']);
    $check_out = sanitize($_POST['check_out']);
    $guests = intval($_POST['guests']);
    $user_id = $_SESSION['user_id'];
    
    // Проверка доступности номера
    $query = "SELECT id FROM room_bookings 
              WHERE room_id = $room_id 
              AND (
                  (check_in <= '$check_in' AND check_out >= '$check_in')
                  OR (check_in <= '$check_out' AND check_out >= '$check_out')
                  OR (check_in >= '$check_in' AND check_out <= '$check_out')
              )
              AND status != 'cancelled'";
    $result = mysqli_query($connection, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $error = "Выбранные даты уже заняты. Пожалуйста, выберите другие даты.";
    } else {
        // Расчет стоимости
        $room = array_filter($rooms, function($r) use ($room_id) {
            return $r['id'] == $room_id;
        });
        $room = reset($room);
        
        $date1 = new DateTime($check_in);
        $date2 = new DateTime($check_out);
        $nights = $date2->diff($date1)->days;
        $total_price = $nights * $room['price_per_night'];
        
        // Создание брони
        $query = "INSERT INTO room_bookings (user_id, room_id, check_in, check_out, guests, total_price) 
                  VALUES ($user_id, $room_id, '$check_in', '$check_out', $guests, $total_price)";
        
        if (mysqli_query($connection, $query)) {
            $_SESSION['success_message'] = 'Номер успешно забронирован!';
            header('Location: /account/profile.php');
            exit;
        } else {
            $error = 'Ошибка при бронировании: ' . mysqli_error($connection);
        }
    }
}

require_once '../includes/header.php';
?>

<!-- Подключение Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Подключение Font Awesome для иконок -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<section class="rooms-section">
    <div class="container">
        <h2 class="section-title">Наши номера</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="rooms-grid">
            <?php foreach ($rooms as $room): ?>
                <div class="room-card" 
                     data-room-id="<?php echo $room['id']; ?>"
                     data-room-name="<?php echo htmlspecialchars($room['name']); ?>"
                     data-room-description="<?php echo htmlspecialchars($room['description']); ?>"
                     data-room-capacity="<?php echo $room['capacity']; ?>"
                     data-room-price="<?php echo $room['price_per_night']; ?>"
                     data-room-images='<?php echo json_encode($room['images']); ?>'
                     data-room-amenities='<?php echo json_encode($room['amenities']); ?>'>
                    
                    <div class="room-image">
                        <?php
                        $firstImage = $room['images'][0];
                        $imageSrc = (strpos($firstImage, 'http')) === 0 
                            ? $firstImage 
                            : "/assets/images/rooms/{$firstImage}";
                        ?>
                        <img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($room['name']) ?>" class="img-fluid">
                    </div>
                    
                    <div class="room-info">
                        <h3><?php echo htmlspecialchars($room['name']); ?></h3>
                        <p class="room-description">
                            <?php echo mb_substr($room['description'], 0, 100) . '...'; ?>
                        </p>
                        
                        <div class="room-features">
                            <span class="feature">
                                <i class="fas fa-user-friends"></i>
                                До <?php echo $room['capacity']; ?> гостей
                            </span>
                            <span class="feature">
                                <i class="fas fa-ruble-sign"></i>
                                <?php echo $room['price_per_night']; ?> руб./ночь
                            </span>
                        </div>
                        
                        <div class="room-amenities">
                            <?php foreach (array_slice($room['amenities'], 0, 3) as $amenity): ?>
                                <span class="amenity">
                                    <i class="fas fa-check"></i>
                                    <?php echo $amenity; ?>
                                </span>
                            <?php endforeach; ?>
                            <?php if (count($room['amenities']) > 3): ?>
                                <span class="amenity more">+<?php echo count($room['amenities']) - 3; ?> еще</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="room-actions">
                            <button class="btn btn-outline-primary view-details-btn">
                                Подробнее
                            </button>
                            <button class="btn btn-primary book-btn" 
                                    data-room-id="<?php echo $room['id']; ?>">
                                Забронировать
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Модальное окно слайдера -->
<div class="modal fade" id="roomSliderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRoomName"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators" id="carouselIndicators"></div>
                    <div class="carousel-inner" id="carouselInner"></div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Предыдущий</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Следующий</span>
                    </button>
                </div>
                
                <div class="room-details mt-4">
                    <div class="room-description-full"></div>
                    
                    <div class="room-amenities-full mt-4">
                        <h6>Удобства в номере:</h6>
                        <div class="amenities-grid"></div>
                    </div>
                    
                    <div class="room-features-full mt-4">
                        <div class="d-flex gap-3">
                            <span class="badge bg-primary">
                                <i class="fas fa-user-friends"></i> 
                                До <span id="modalRoomCapacity"></span> гостей
                            </span>
                            <span class="badge bg-success">
                                <i class="fas fa-ruble-sign"></i> 
                                <span id="modalRoomPrice"></span> руб./ночь
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно бронирования -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Бронирование номера</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="room_id" id="modalRoomId">
                    <input type="hidden" name="book_room" value="1">
                    
                    <div class="mb-3">
                        <label for="checkIn" class="form-label">Дата заезда</label>
                        <input type="date" class="form-control" id="checkIn" name="check_in" required>
                    </div>
                    <div class="mb-3">
                        <label for="checkOut" class="form-label">Дата выезда</label>
                        <input type="date" class="form-control" id="checkOut" name="check_out" required>
                    </div>
                    <div class="mb-3">
                        <label for="guests" class="form-label">Количество гостей</label>
                        <input type="number" class="form-control" id="guests" name="guests" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="totalPrice" class="form-label">Итого</label>
                        <input type="text" class="form-control" id="totalPrice" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Подтвердить бронирование</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.rooms-section {
    padding: 4rem 0;
    background: #f8f9fa;
}

.section-title {
    text-align: center;
    color: #1a73e8;
    margin-bottom: 3rem;
    font-size: 2.5rem;
}

.rooms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
}

.room-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 1px solid rgba(26, 115, 232, 0.1);
    flex-direction: column;
}

.room-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(26, 115, 232, 0.15);
    border-color: #1a73e8;
}

.room-image {
    height: 250px;
    overflow: hidden;
    position: relative;
}

.room-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.room-card:hover .room-image img {
    transform: scale(1.05);
}

.room-info {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.room-info h3 {
    color: #1a73e8;
    margin: 0 0 1rem;
    font-size: 1.5rem;
}

.room-description {
    color: #333;
    margin-bottom: 1rem;
    line-height: 1.6;
    font-size: 1rem;
}

.room-features {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.feature {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #333;
    font-size: 0.9rem;
}

.feature i {
    color: #1a73e8;
    font-size: 1rem;
}

.room-amenities {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.amenity {
    background: rgba(26, 115, 232, 0.1);
    color: #1a73e8;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.amenity.more {
    background: #1a73e8;
    color: white;
}

.room-actions {
    display: flex;
    gap: 1rem;
    margin-top: auto;
}

.btn {
    padding: 0.8rem 1.5rem;
    border-radius: 5px;
    font-weight: 500;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.btn-primary {
    background: #1a73e8;
    color: white;
    border: none;
}

.btn-primary:hover {
    background: #0d5bbc;
    transform: translateY(-2px);
}

.btn-outline-primary {
    color: #1a73e8;
    border: 1px solid #1a73e8;
    background: transparent;
}

.btn-outline-primary:hover {
    background: #1a73e8;
    color: white;
    transform: translateY(-2px);
}

#roomSliderModal .modal-dialog {
    max-width: 90%;
}

#roomSliderModal .carousel-item img {
    height: 500px;
    object-fit: cover;
    width: 100%;
}

.carousel-indicators {
    bottom: -50px;
}

.carousel-indicators button {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #1a73e8;
}

.amenities-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

@media (max-width: 768px) {
    .rooms-grid {
        grid-template-columns: 1fr;
    }
    
    #roomSliderModal .carousel-item img {
        height: 300px;
    }
    
    .room-actions {
        flex-direction: column;
    }
}
</style>

<!-- Подключение Bootstrap JS и зависимостей -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Передаем данные о комнатах из PHP в JavaScript
    const rooms = <?php echo json_encode($rooms); ?>;
    
    // Инициализация модальных окон
    const roomSliderModal = new bootstrap.Modal(document.getElementById('roomSliderModal'));
    const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
    
    // Обработчик клика на карточку или кнопку "Подробнее"
    document.querySelectorAll('.room-card, .view-details-btn').forEach(element => {
        element.addEventListener('click', function(e) {
            if (e.target.classList.contains('book-btn')) return;
            
            const roomCard = e.target.closest('.room-card');
            const roomId = roomCard.dataset.roomId;
            const roomName = roomCard.dataset.roomName;
            const roomDescription = roomCard.dataset.roomDescription;
            const roomCapacity = roomCard.dataset.roomCapacity;
            const roomPrice = roomCard.dataset.roomPrice;
            const roomImages = JSON.parse(roomCard.dataset.roomImages);
            const roomAmenities = JSON.parse(roomCard.dataset.roomAmenities);
            
            // Заполняем информацию о номере
            document.getElementById('modalRoomName').textContent = roomName;
            document.querySelector('.room-description-full').textContent = roomDescription;
            document.getElementById('modalRoomCapacity').textContent = roomCapacity;
            document.getElementById('modalRoomPrice').textContent = roomPrice;
            
            // Очищаем слайдер
            const carouselInner = document.getElementById('carouselInner');
            const carouselIndicators = document.getElementById('carouselIndicators');
            carouselInner.innerHTML = '';
            carouselIndicators.innerHTML = '';
            
            // Добавляем слайды
            roomImages.forEach((image, index) => {
                const isExternal = image.startsWith('http');
                const imageSrc = isExternal ? image : `/assets/images/rooms/${image}`;
                
                // Добавляем индикатор
                const indicator = document.createElement('button');
                indicator.type = 'button';
                indicator.dataset.bsTarget = '#roomCarousel';
                indicator.dataset.bsSlideTo = index;
                if (index === 0) indicator.classList.add('active');
                indicator.setAttribute('aria-label', 'Slide ' + (index + 1));
                carouselIndicators.appendChild(indicator);
                
                // Добавляем слайд
                const slide = document.createElement('div');
                slide.className = `carousel-item ${index === 0 ? 'active' : ''}`;
                slide.innerHTML = `
                    <img src="${imageSrc}" class="d-block w-100" alt="Фото номера" style="height: 500px; object-fit: cover;">
                `;
                carouselInner.appendChild(slide);
            });
            
            // Инициализируем карусель
            const carousel = new bootstrap.Carousel(document.getElementById('roomCarousel'), {
                interval: false
            });
            
            // Добавляем удобства
            const amenitiesGrid = document.querySelector('.amenities-grid');
            amenitiesGrid.innerHTML = '';
            roomAmenities.forEach(amenity => {
                const amenityElement = document.createElement('div');
                amenityElement.className = 'amenity';
                amenityElement.innerHTML = `
                    <i class="fas fa-check"></i>
                    ${amenity}
                `;
                amenitiesGrid.appendChild(amenityElement);
            });
            
            // Показываем модальное окно
            roomSliderModal.show();
        });
    });
    
    // Обработчик клика на кнопки бронирования
    document.querySelectorAll('.book-btn, .book-from-slider-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const roomId = this.dataset.roomId || this.closest('[data-room-id]').dataset.roomId;
            document.getElementById('modalRoomId').value = roomId;
            
            // Устанавливаем минимальную дату (завтра)
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);
            document.getElementById('checkIn').min = tomorrow.toISOString().split('T')[0];
            
            // Показываем модальное окно бронирования
            bookingModal.show();
        });
    });
    
    // Расчет стоимости бронирования
    function calculatePrice() {
        const checkIn = document.getElementById('checkIn').value;
        const checkOut = document.getElementById('checkOut').value;
        const guests = document.getElementById('guests').value;
        const roomId = document.getElementById('modalRoomId').value;
        
        if (checkIn && checkOut && guests && roomId) {
            const startDate = new Date(checkIn);
            const endDate = new Date(checkOut);
            
            if (endDate <= startDate) {
                document.getElementById('totalPrice').value = 'Дата выезда должна быть позже даты заезда';
                return;
            }
            
            const nights = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
            const room = rooms.find(r => r.id == roomId);
            
            if (room) {
                const total = nights * room.price_per_night;
                document.getElementById('totalPrice').value = 
                    `${total} руб. (${nights} ночей × ${room.price_per_night} руб./ночь)`;
            }
        }
    }
    
    // Слушатели событий для расчета цены
    document.getElementById('checkIn').addEventListener('change', calculatePrice);
    document.getElementById('checkOut').addEventListener('change', calculatePrice);
    document.getElementById('guests').addEventListener('input', calculatePrice);
    
    // Обработчики закрытия модальных окон
    document.getElementById('roomSliderModal').addEventListener('hidden.bs.modal', function () {
        // Убедимся, что body не заблокирован
        document.body.style.overflow = 'auto';
        document.body.style.paddingRight = '0';
    });
    
    document.getElementById('bookingModal').addEventListener('hidden.bs.modal', function () {
        // Убедимся, что body не заблокирован
        document.body.style.overflow = 'auto';
        document.body.style.paddingRight = '0';
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>