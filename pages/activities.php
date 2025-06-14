<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Получаем список активностей из базы данных
$query = "SELECT * FROM activities ORDER BY name";
$result = mysqli_query($connection, $query);
$activities = [];
while ($row = mysqli_fetch_assoc($result)) {
    $activities[] = $row;
}

require_once '../includes/header.php';
?>

<section class="activities-section">
    <div class="container">
        <h1>Активности на курорте</h1>
        <p class="lead">Выберите, чем вы хотите заняться во время отдыха</p>
        
        <div class="activities-filter">
            <div class="filter-options">
                <button class="filter-btn active" data-filter="all">Все</button>
                <button class="filter-btn" data-filter="winter">Зимние</button>
                <button class="filter-btn" data-filter="summer">Летние</button>
                <button class="filter-btn" data-filter="family">Семейные</button>
                <button class="filter-btn" data-filter="extreme">Экстрим</button>
            </div>
            <div class="search-box">
                <input type="text" id="activity-search" placeholder="Поиск активностей...">
                <button id="search-btn"><i class="fas fa-search"></i></button>
            </div>
        </div>
        
        <div class="activities-grid">
            <?php foreach ($activities as $activity): ?>
                <div class="activity-card" data-category="<?php echo $activity['category']; ?>">
                    <div class="activity-image">
                        <img src="/assets/images/activities/<?php echo $activity['image']; ?>" alt="<?php echo htmlspecialchars($activity['name']); ?>">
                        <div class="activity-badge"><?php echo $activity['duration']; ?> мин</div>
                    </div>
                    <div class="activity-info">
                        <h3><?php echo htmlspecialchars($activity['name']); ?></h3>
                        <div class="activity-meta">
                            <span><i class="fas fa-tag"></i> <?php echo $activity['category']; ?></span>
                            <span><i class="fas fa-ruble-sign"></i> <?php echo $activity['price']; ?></span>
                            <span><i class="fas fa-users"></i> <?php echo $activity['min_people']; ?>-<?php echo $activity['max_people']; ?> чел</span>
                        </div>
                        <p><?php echo htmlspecialchars($activity['short_description']); ?></p>
                        <button class="btn btn-details" data-id="<?php echo $activity['id']; ?>">Подробнее</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Модальное окно с деталями активности -->
<div class="modal" id="activity-modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div id="modal-content"></div>
    </div>
</div>

<style>
.activities-section {
    padding: 60px 0;
    background-color: #f8f9fa;
}

.activities-filter {
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

#activity-search {
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

.activities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
}

.activity-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
    display: none;
}

.activity-card:hover {
    transform: translateY(-5px);
}

.activity-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.activity-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.activity-card:hover .activity-image img {
    transform: scale(1.05);
}

.activity-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 14px;
}

.activity-info {
    padding: 20px;
}

.activity-info h3 {
    margin-bottom: 10px;
    color: #2c3e50;
}

.activity-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
    font-size: 14px;
    color: #6c757d;
}

.activity-meta i {
    margin-right: 5px;
}

.btn-details {
    display: block;
    width: 100%;
    margin-top: 15px;
    padding: 10px;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-details:hover {
    background: #2980b9;
}

/* Стили для модального окна */
body.modal-open {
    overflow: hidden;
    position: fixed;
    width: 100%;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    backdrop-filter: blur(3px);
    animation: fadeIn 0.3s ease;
    overflow-y: auto;
}

.modal-content {
    background-color: white;
    margin: 20px auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 900px;
    position: relative;
    min-height: 80vh;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    border: 1px solid #e0e0e0;
}

.close-modal {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 28px;
    cursor: pointer;
    color: #6c757d;
    background: rgba(255,255,255,0.8);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    transition: all 0.3s;
}

.close-modal:hover {
    color: #3498db;
    transform: rotate(90deg);
}

/* Стили для контента модального окна */
.activity-details {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

.detail-image {
    height: 400px;
    overflow: hidden;
    border-radius: 12px 12px 0 0;
    position: relative;
}

.detail-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.activity-details:hover .detail-image img {
    transform: scale(1.03);
}

.detail-content {
    padding: 0 30px 30px;
}

.activity-details h2 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 28px;
    position: relative;
    padding-bottom: 10px;
}

.activity-details h2:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: #3498db;
}

.detail-meta {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin: 30px 0;
    padding: 25px;
    background: #f8f9fa;
    border-radius: 8px;
}

.detail-meta p {
    margin: 0;
    font-size: 15px;
    color: #495057;
}

.detail-meta strong {
    color: #2c3e50;
    display: inline-block;
    min-width: 120px;
}

.detail-description {
    line-height: 1.7;
    color: #495057;
    font-size: 16px;
    white-space: pre-line;
    margin-bottom: 30px;
}

.additional-info {
    margin-top: 30px;
}

.additional-info h3 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 22px;
}

/* Анимации */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Адаптивность */
@media (max-width: 768px) {
    .modal-content {
        margin: 10px auto;
        width: 95%;
    }
    
    .detail-image {
        height: 250px;
    }
    
    .detail-meta {
        grid-template-columns: 1fr;
    }
    
    .activity-details h2 {
        font-size: 24px;
    }
}

@media (max-width: 480px) {
    .detail-content {
        padding: 0 15px 15px;
    }
    
    .detail-image {
        height: 200px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация фильтрации
    const filterButtons = document.querySelectorAll('.filter-btn');
    const activityCards = document.querySelectorAll('.activity-card');
    
    // Показываем все карточки при загрузке
    activityCards.forEach(card => {
        card.style.display = 'block';
    });
    
    // Фильтрация по категориям
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            activityCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-category') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Поиск активностей
    const searchInput = document.getElementById('activity-search');
    const searchBtn = document.getElementById('search-btn');
    
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        
        activityCards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();
            
            if (name.includes(searchTerm) || description.includes(searchTerm)) {
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
    
    // Модальное окно
    const modal = document.getElementById('activity-modal');
    const closeModal = document.querySelector('.close-modal');
    const detailButtons = document.querySelectorAll('.btn-details');
    const body = document.body;
    
    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const activityId = this.getAttribute('data-id');
            
            // Блокируем прокрутку страницы
            body.classList.add('modal-open');
            
            // Загружаем данные активности через AJAX
            fetch(`/api/get_activity.php?id=${activityId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('modal-content').innerHTML = data;
                    modal.style.display = 'block';
                    modal.scrollTop = 0;
                    
                    
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('modal-content').innerHTML = '<p>Произошла ошибка при загрузке данных</p>';
                    modal.style.display = 'block';
                    modal.scrollTop = 0;
                });
        });
    });
    
    // Обработчики закрытия модального окна
    function closeModalWindow() {
        modal.style.display = 'none';
        body.classList.remove('modal-open');
    }
    
    closeModal.addEventListener('click', closeModalWindow);
    
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModalWindow();
        }
    });
    
    // Закрытие по ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.style.display === 'block') {
            closeModalWindow();
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>