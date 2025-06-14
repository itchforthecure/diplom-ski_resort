<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';
?>

<div class="activity-container rope-park">
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="animate-text">Веревочный парк</h1>
            <p class="subtitle animate-text">Испытайте себя на высоте!</p>
            <div class="hero-buttons">
                <a href="#routes" class="btn btn-primary pulse">Выбрать маршрут</a>
                <a href="#gallery" class="btn btn-secondary">Фотогалерея</a>
            </div>
        </div>
        <div class="hero-overlay"></div>
    </div>

    <div class="content-section">
        <div class="info-cards">
            <div class="info-card">
                <div class="card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Время работы</h3>
                <p>Ежедневно: 10:00 - 20:00</p>
                <p>Сезон: Круглый год</p>
            </div>
            <div class="info-card">
                <div class="card-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h3>Стоимость</h3>
                <p>Взрослый: 1500 ₽</p>
                <p>Детский: 1000 ₽</p>
            </div>
            <div class="info-card">
                <div class="card-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <h3>Возраст</h3>
                <p>Минимальный возраст: 7 лет</p>
                <p>Минимальный рост: 120 см</p>
            </div>
        </div>

        <div class="description-section">
            <h2>О веревочном парке</h2>
            <p>Наш веревочный парк предлагает захватывающие маршруты различной сложности, 
               расположенные на высоте от 3 до 15 метров. Пройдите через подвесные мосты, 
               качающиеся бревна и другие препятствия, наслаждаясь потрясающими видами на горы.</p>
            
            <div class="features-grid">
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Безопасность</h4>
                    <p>Все маршруты оборудованы системой непрерывной страховки. Инструкторы проводят подробный инструктаж перед началом.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <h4>Виды</h4>
                    <p>С высоты открываются захватывающие панорамы горных вершин и долин.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h4>Фото</h4>
                    <p>На каждом маршруте есть фотограф, который запечатлит ваши достижения.</p>
                </div>
            </div>
        </div>

        <section class="routes-section" id="routes">
            <h2>Маршруты</h2>
            <div class="routes-grid">
                <div class="route-card">
                    <div class="route-image">
                        <img src="../assets/images/rope_park/1.jpg" alt="Детский маршрут">
                        <div class="route-overlay">
                            <span class="difficulty easy">Легкий</span>
                        </div>
                    </div>
                    <div class="route-info">
                        <h3>Детский маршрут "Смельчаки"</h3>
                        <p>Идеальный маршрут для детей от 4 до 8 лет. Высота препятствий не превышает 1 метра, все элементы имеют дополнительные страховочные сетки.</p>
                        <ul class="route-details">
                            <li><i class="fas fa-child"></i> Возраст: 4-8 лет</li>
                            <li><i class="fas fa-ruler-vertical"></i> Высота: до 1 м</li>
                            <li><i class="fas fa-clock"></i> Время прохождения: 20-30 мин</li>
                            <li><i class="fas fa-mountain"></i> Количество препятствий: 8</li>
                        </ul>
                    </div>
                </div>

                <div class="route-card">
                    <div class="route-image">
                        <img src="../assets/images/rope_park/2.jpg" alt="Семейный маршрут">
                        <div class="route-overlay">
                            <span class="difficulty medium">Средний</span>
                        </div>
                    </div>
                    <div class="route-info">
                        <h3>Семейный маршрут "Семейные узы"</h3>
                        <p>Увлекательный маршрут для всей семьи. Подходит для детей от 8 лет и взрослых. Включает разнообразные препятствия средней сложности.</p>
                        <ul class="route-details">
                            <li><i class="fas fa-users"></i> Возраст: 8+ лет</li>
                            <li><i class="fas fa-ruler-vertical"></i> Высота: 2-3 м</li>
                            <li><i class="fas fa-clock"></i> Время прохождения: 40-50 мин</li>
                            <li><i class="fas fa-mountain"></i> Количество препятствий: 12</li>
                        </ul>
                    </div>
                </div>

                <div class="route-card">
                    <div class="route-image">
                        <img src="../assets/images/rope_park/3.jpg" alt="Экстремальный маршрут">
                        <div class="route-overlay">
                            <span class="difficulty hard">Сложный</span>
                        </div>
                    </div>
                    <div class="route-info">
                        <h3>Экстремальный маршрут "Вызов"</h3>
                        <p>Для опытных посетителей, ищущих острых ощущений. Включает сложные препятствия и высокие элементы.</p>
                        <ul class="route-details">
                            <li><i class="fas fa-user"></i> Возраст: 14+ лет</li>
                            <li><i class="fas fa-ruler-vertical"></i> Высота: 4-6 м</li>
                            <li><i class="fas fa-clock"></i> Время прохождения: 60-90 мин</li>
                            <li><i class="fas fa-mountain"></i> Количество препятствий: 15</li>
                        </ul>
                    </div>
                </div>

                <div class="route-card">
                    <div class="route-image">
                        <img src="../assets/images/rope_park/4.jpg" alt="Профессиональный маршрут">
                        <div class="route-overlay">
                            <span class="difficulty expert">Эксперт</span>
                        </div>
                    </div>
                    <div class="route-info">
                        <h3>Профессиональный маршрут "Мастер"</h3>
                        <p>Самый сложный маршрут для опытных альпинистов и любителей экстрима. Требует специальной подготовки.</p>
                        <ul class="route-details">
                            <li><i class="fas fa-user-ninja"></i> Возраст: 16+ лет</li>
                            <li><i class="fas fa-ruler-vertical"></i> Высота: 8-10 м</li>
                            <li><i class="fas fa-clock"></i> Время прохождения: 90-120 мин</li>
                            <li><i class="fas fa-mountain"></i> Количество препятствий: 20</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <div id="gallery" class="gallery-section">
            <h2>Фотогалерея</h2>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../assets/images/rope_park/5.avif" alt="Веревочный парк">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="../assets/images/rope_park/6.jpg" alt="Веревочный парк">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="../assets/images/rope_park/7.webp" alt="Веревочный парк">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="../assets/images/rope_park/8.jpg" alt="Веревочный парк">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="../assets/images/rope_park/9.webp" alt="Веревочный парк">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="../assets/images/rope_park/10.jpg" alt="Веревочный парк">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для галереи -->
<div id="gallery-modal" class="modal">
    <span class="modal-close">&times;</span>
    <img class="modal-content" id="modal-image">
    <div class="modal-caption"></div>
</div>

<style>
:root {
    --primary-color: #2C3E50;
    --secondary-color: #E74C3C;
    --accent-color: #3498DB;
    --text-color: #2C3E50;
    --light-bg: #ECF0F1;
    --white: #FFFFFF;
    --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

.rope-park {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.hero-section {
    position: relative;
    background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/rope-park-hero.jpg');
    background-size: cover;
    background-position: center;
    color: var(--white);
    text-align: center;
    padding: 150px 20px;
    border-radius: 15px;
    margin-bottom: 40px;
    overflow: hidden;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(44, 62, 80, 0.9), rgba(52, 152, 219, 0.9));
    opacity: 0.8;
}

.hero-section h1 {
    font-size: 4em;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.subtitle {
    font-size: 1.8em;
    opacity: 0.9;
    margin-bottom: 30px;
}

.hero-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
}

.btn {
    padding: 15px 30px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: var(--transition);
}

.btn-primary {
    background: var(--secondary-color);
    color: var(--white);
}

.btn-secondary {
    background: transparent;
    color: var(--white);
    border: 2px solid var(--white);
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow);
}

.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.info-card {
    background: var(--white);
    padding: 30px;
    border-radius: 15px;
    box-shadow: var(--shadow);
    text-align: center;
    transition: var(--transition);
}

.info-card:hover {
    transform: translateY(-5px);
}

.card-icon {
    width: 80px;
    height: 80px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.card-icon i {
    font-size: 2em;
    color: var(--white);
}

.description-section {
    background: var(--white);
    padding: 40px;
    border-radius: 15px;
    margin-bottom: 40px;
    box-shadow: var(--shadow);
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.feature {
    text-align: center;
    padding: 30px;
    background: var(--light-bg);
    border-radius: 15px;
    transition: var(--transition);
}

.feature:hover {
    transform: translateY(-5px);
}

.feature-icon {
    width: 70px;
    height: 70px;
    background: var(--accent-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.feature-icon i {
    font-size: 2em;
    color: var(--white);
}

.routes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.route-card {
    background: var(--white);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.route-card:hover {
    transform: translateY(-5px);
}

.route-image {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.route-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.route-card:hover .route-image img {
    transform: scale(1.1);
}

.route-overlay {
    position: absolute;
    top: 20px;
    right: 20px;
    background: var(--secondary-color);
    padding: 8px 15px;
    border-radius: 20px;
    color: var(--white);
    font-weight: bold;
}

.route-content {
    padding: 25px;
}

.route-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
    margin: 15px 0;
}

.route-details p {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-color);
}

.route-details i {
    color: var(--accent-color);
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.gallery-item {
    position: relative;
    height: 250px;
    border-radius: 15px;
    overflow: hidden;
    cursor: pointer;
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--transition);
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-overlay i {
    color: var(--white);
    font-size: 2em;
}

/* Модальное окно */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
}

.modal-content {
    margin: auto;
    display: block;
    max-width: 90%;
    max-height: 90vh;
    margin-top: 5vh;
}

.modal-close {
    position: absolute;
    top: 20px;
    right: 30px;
    color: var(--white);
    font-size: 40px;
    cursor: pointer;
}

.modal-caption {
    margin: auto;
    display: block;
    width: 80%;
    text-align: center;
    color: var(--white);
    padding: 10px 0;
}

/* Удаляем стили для анимаций */
[data-aos] {
    opacity: 1;
    transform: none;
}

.animate-text {
    opacity: 1;
    transform: none;
    animation: none;
}

/* Оставляем только анимацию для кнопки pulse */
.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}
</style>

<script>
$(document).ready(function() {
    // Удаляем инициализацию AOS
    // AOS.init({
    //     duration: 800,
    //     once: true
    // });

    // Плавная прокрутка к секциям
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.hash);
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });

    // Обработка галереи
    const modal = document.getElementById('gallery-modal');
    const modalImg = document.getElementById('modal-image');
    const closeBtn = document.getElementsByClassName('modal-close')[0];

    $('.gallery-item').click(function() {
        const img = $(this).find('img');
        modal.style.display = "block";
        modalImg.src = img.attr('src');
    });

    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Анимация при наведении на карточки маршрутов
    $('.route-card').hover(
        function() {
            $(this).find('.route-image img').css('transform', 'scale(1.1)');
        },
        function() {
            $(this).find('.route-image img').css('transform', 'scale(1)');
        }
    );
});
</script>

<?php require_once '../includes/footer.php'; ?> 