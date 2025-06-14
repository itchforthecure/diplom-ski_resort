<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Получаем список активных инструкторов из базы данных
$query = "SELECT * FROM instructors WHERE is_active = 1 ORDER BY name";
$result = mysqli_query($connection, $query);
$instructors = [];
while ($row = mysqli_fetch_assoc($result)) {
    $instructors[] = $row;
}

require_once '../includes/header.php';
?>

<section class="instructors-section">
    <div class="container">
        <h1>Наши инструкторы</h1>
        <p class="lead">Профессиональные инструкторы с многолетним опытом работы</p>
        
        <div class="instructors-filter">
            <div class="filter-options">
                <button class="filter-btn active" data-filter="all">Все</button>
                <button class="filter-btn" data-filter="ski">Лыжи</button>
                <button class="filter-btn" data-filter="snowboard">Сноуборд</button>
                <button class="filter-btn" data-filter="freestyle">Фристайл</button>
                <button class="filter-btn" data-filter="children">Детские</button>
            </div>
            <div class="search-box">
                <input type="text" id="instructor-search" placeholder="Поиск инструкторов...">
                <button id="search-btn"><i class="fas fa-search"></i></button>
            </div>
        </div>
        
        <div class="instructors-grid">
            <?php foreach ($instructors as $instructor): ?>
                <div class="instructor-card" data-specialization="<?php echo $instructor['specialization']; ?>">
                    <div class="instructor-image">
                        <?php if ($instructor['image']): ?>
                            <img src="/assets/images/instructors/<?php echo $instructor['image']; ?>" alt="<?php echo htmlspecialchars($instructor['name']); ?>">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        <?php endif; ?>
                        <div class="instructor-badge">
                            <span><?php echo $instructor['experience']; ?>+ лет опыта</span>
                        </div>
                    </div>
                    <div class="instructor-info">
                        <h3><?php echo htmlspecialchars($instructor['name']); ?></h3>
                        <div class="specialization">
                            <span><?php echo getSpecializationName($instructor['specialization']); ?></span>
                        </div>
                        <div class="instructor-meta">
                            <span><i class="fas fa-star"></i> <?php echo $instructor['rating'] ?? '4.8'; ?>/5</span>
                            <span><i class="fas fa-ruble-sign"></i> <?php echo $instructor['price_per_hour'] ?? '1500'; ?>/час</span>
                        </div>
                        <p class="short-bio"><?php echo htmlspecialchars(truncateText($instructor['bio'], 120)); ?></p>
                        <div class="instructor-actions">
                            <button class="btn btn-details" data-id="<?php echo $instructor['id']; ?>">Подробнее</button>
                            <?php if (isLoggedIn()): ?>
                                <button class="btn btn-book" data-id="<?php echo $instructor['id']; ?>">Забронировать</button>
                            <?php else: ?>
                                <a href="/account/login.php" class="btn btn-book">Войти для брони</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Модальное окно с деталями инструктора -->
<div class="modal" id="instructor-modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div id="modal-content"></div>
    </div>
</div>

<!-- Модальное окно бронирования -->
<div class="modal" id="booking-modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div id="booking-content"></div>
    </div>
</div>

<style>
.instructors-section {
    padding: 60px 0;
    background-color: #f8f9fa;
}

.instructors-filter {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.filter-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-btn {
    padding: 8px 15px;
    background: #e9ecef;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s;
}

.filter-btn.active {
    background: #3498db;
    color: white;
}

.filter-btn:hover {
    background: #dee2e6;
}

.search-box {
    display: flex;
}

#instructor-search {
    padding: 8px 15px;
    border: 1px solid #ddd;
    border-radius: 4px 0 0 4px;
    width: 250px;
}

#search-btn {
    padding: 8px 15px;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
}

.instructors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
}

.instructor-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
    display: none; /* Сначала скрываем, потом покажем через JS */
}

.instructor-card:hover {
    transform: translateY(-5px);
}

.instructor-image {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.instructor-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.no-image {
    width: 100%;
    height: 100%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 60px;
}

.instructor-card:hover .instructor-image img {
    transform: scale(1.05);
}

.instructor-badge {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 14px;
}

.instructor-info {
    padding: 20px;
}

.instructor-info h3 {
    margin-bottom: 5px;
    color: #2c3e50;
}

.specialization {
    margin-bottom: 10px;
}

.specialization span {
    background: #e9ecef;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 14px;
    color: #495057;
}

.instructor-meta {
    display: flex;
    gap: 15px;
    margin: 10px 0;
    font-size: 14px;
    color: #6c757d;
}

.instructor-meta i {
    margin-right: 5px;
    color: #3498db;
}

.short-bio {
    line-height: 1.5;
    margin-bottom: 15px;
    color: #495057;
}

.instructor-actions {
    display: flex;
    gap: 10px;
}

.btn-details {
    flex: 1;
    padding: 10px;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-book {
    flex: 1;
    padding: 10px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-details:hover {
    background: #2980b9;
}

.btn-book:hover {
    background: #218838;
}

/* Модальные окна */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 30px;
    border-radius: 8px;
    width: 90%;
    max-width: 800px;
    position: relative;
    max-height: 80vh;
    overflow-y: auto;
}

.close-modal {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #6c757d;
}

@media (max-width: 768px) {
    .instructors-filter {
        flex-direction: column;
    }
    
    .search-box {
        width: 100%;
    }
    
    #instructor-search {
        width: 100%;
    }
    
    .modal-content {
        margin: 10% auto;
        width: 95%;
    }
    
    .instructor-actions {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация фильтрации
    const filterButtons = document.querySelectorAll('.filter-btn');
    const instructorCards = document.querySelectorAll('.instructor-card');
    
    // Показываем все карточки при загрузке
    instructorCards.forEach(card => {
        card.style.display = 'block';
    });
    
    // Фильтрация по специализации
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Удаляем активный класс у всех кнопок
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Добавляем активный класс текущей кнопке
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            instructorCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-specialization') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Поиск инструкторов
    const searchInput = document.getElementById('instructor-search');
    const searchBtn = document.getElementById('search-btn');
    
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        
        instructorCards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const bio = card.querySelector('.short-bio').textContent.toLowerCase();
            const specialization = card.querySelector('.specialization span').textContent.toLowerCase();
            
            if (name.includes(searchTerm) || bio.includes(searchTerm) || specialization.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    
    // Модальное окно с деталями
    const detailModal = document.getElementById('instructor-modal');
    const closeDetailModal = detailModal.querySelector('.close-modal');
    const detailButtons = document.querySelectorAll('.btn-details');
    
    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const instructorId = this.getAttribute('data-id');
            
            // Загружаем данные инструктора через AJAX
            fetch(`/api/get_instructor.php?id=${instructorId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('modal-content').innerHTML = data;
                    detailModal.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('modal-content').innerHTML = '<p>Произошла ошибка при загрузке данных</p>';
                    detailModal.style.display = 'block';
                });
        });
    });
    
    closeDetailModal.addEventListener('click', function() {
        detailModal.style.display = 'none';
    });
    
    // Модальное окно бронирования
const bookingModal = document.getElementById('booking-modal');
const closeBookingModal = bookingModal.querySelector('.close-modal');
const bookButtons = document.querySelectorAll('.btn-book');

bookButtons.forEach(button => {
    button.addEventListener('click', function() {
        const instructorId = this.getAttribute('data-id');
        
        fetch(`/api/get_booking_form.php?instructor_id=${instructorId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('booking-content').innerHTML = data;
                bookingModal.style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('booking-content').innerHTML = 
                    '<div class="alert alert-danger">Ошибка при загрузке формы</div>';
                bookingModal.style.display = 'block';
            });
    });
});
                // Загружаем форму бронирования через AJAX
                
    
    closeBookingModal.addEventListener('click', function() {
        bookingModal.style.display = 'none';
    });
    
    window.addEventListener('click', function(event) {
        if (event.target === detailModal) {
            detailModal.style.display = 'none';
        }
        if (event.target === bookingModal) {
            bookingModal.style.display = 'none';
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>