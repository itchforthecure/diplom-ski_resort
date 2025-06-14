<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';
?>

<div class="activity-container zoo">
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="animate-text">Зоопарк</h1>
            <p class="subtitle animate-text">Познакомьтесь с обитателями горного края</p>
            <div class="hero-buttons">
                <a href="#animals" class="btn btn-primary pulse">Наши обитатели</a>
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
                <p>Ежедневно: 09:00 - 19:00</p>
                <p>Касса закрывается в 18:00</p>
            </div>
            <div class="info-card">
                <div class="card-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h3>Стоимость</h3>
                <p>Взрослый: 800 ₽</p>
                <p>Детский: 500 ₽</p>
                <p>Дети до 3 лет: Бесплатно</p>
            </div>
            <div class="info-card">
                <div class="card-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3>Расположение</h3>
                <p>Центральная часть курорта</p>
                <p>Рядом с рестораном "Горный"</p>
            </div>
        </div>

        <div class="description-section">
            <h2>О нашем зоопарке</h2>
            <p>Наш зоопарк - это уникальное место, где вы можете познакомиться с обитателями горных лесов и долин. 
               Мы создали максимально комфортные условия для животных, максимально приближенные к их естественной среде обитания.</p>
            
            <div class="features-grid">
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h4>Природная среда</h4>
                    <p>Все вольеры спроектированы с учетом естественной среды обитания животных</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h4>Обучение</h4>
                    <p>Ежедневные лекции и экскурсии с профессиональными зоологами</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Забота</h4>
                    <p>Все животные находятся под постоянным наблюдением ветеринаров</p>
                </div>
            </div>
        </div>

        <div class="zoo-map-section">
            <h2>Карта зоопарка</h2>
            <div class="zoo-map">
                <div class="map-container">
                    <img src="../assets/images/zoo/zoo.jpg" alt="Карта зоопарка" class="map-image">
                    <div class="map-point" data-animal="bears" style="top: 30%; left: 40%;">
                        <div class="point-tooltip">Медведи</div>
                    </div>
                    <div class="map-point" data-animal="wolves" style="top: 45%; left: 60%;">
                        <div class="point-tooltip">Волки</div>
                    </div>
                    <div class="map-point" data-animal="lynx" style="top: 60%; left: 35%;">
                        <div class="point-tooltip">Рыси</div>
                    </div>
                    <div class="map-point" data-animal="birds" style="top: 25%; left: 70%;">
                        <div class="point-tooltip">Птицы</div>
                    </div>
                </div>
            </div>
        </div>

        <div id="animals" class="animals-section">
            <h2>Наши животные</h2>
            <div class="animals-grid">
                <div class="animal-card">
                    <div class="animal-image">
                        <img src="../assets/images/zoo/medved.jpeg" alt="Бурый медведь">
                    </div>
                    <div class="animal-info">
                        <h3>Бурый медведь</h3>
                        <p class="scientific-name">Ursus arctos</p>
                        <div class="animal-details">
                            <p><strong>Возраст:</strong> 8 лет</p>
                            <p><strong>Вес:</strong> 350 кг</p>
                            <p><strong>Особенности:</strong> Очень дружелюбный, любит играть с мячами и плавать в бассейне</p>
                            <p><strong>Рацион:</strong> Рыба, мясо, овощи, фрукты</p>
                            <p><strong>Время кормления:</strong> 10:00 и 16:00</p>
                        </div>
                    </div>
                </div>

                <div class="animal-card">
                    <div class="animal-image">
                        <img src="../assets/images/zoo/volk.webp" alt="Волк">
                    </div>
                    <div class="animal-info">
                        <h3>Волк</h3>
                        <p class="scientific-name">Canis lupus</p>
                        <div class="animal-details">
                            <p><strong>Возраст:</strong> 5 лет</p>
                            <p><strong>Вес:</strong> 45 кг</p>
                            <p><strong>Особенности:</strong> Живет в стае из 6 особей, очень социальный</p>
                            <p><strong>Рацион:</strong> Мясо, субпродукты</p>
                            <p><strong>Время кормления:</strong> 9:00 и 17:00</p>
                        </div>
                    </div>
                </div>

                <div class="animal-card">
                    <div class="animal-image">
                        <img src="../assets/images/zoo/ris.jpg" alt="Рысь">
                    </div>
                    <div class="animal-info">
                        <h3>Рысь</h3>
                        <p class="scientific-name">Lynx lynx</p>
                        <div class="animal-details">
                            <p><strong>Возраст:</strong> 4 года</p>
                            <p><strong>Вес:</strong> 25 кг</p>
                            <p><strong>Особенности:</strong> Отличный охотник, любит высоту</p>
                            <p><strong>Рацион:</strong> Мясо, птица</p>
                            <p><strong>Время кормления:</strong> 11:00 и 15:00</p>
                        </div>
                    </div>
                </div>

                <div class="animal-card">
                    <div class="animal-image">
                        <img src="../assets/images/zoo/lisa.webp" alt="Лисица">
                    </div>
                    <div class="animal-info">
                        <h3>Лисица</h3>
                        <p class="scientific-name">Vulpes vulpes</p>
                        <div class="animal-details">
                            <p><strong>Возраст:</strong> 3 года</p>
                            <p><strong>Вес:</strong> 8 кг</p>
                            <p><strong>Особенности:</strong> Очень умная и игривая</p>
                            <p><strong>Рацион:</strong> Мясо, яйца, фрукты</p>
                            <p><strong>Время кормления:</strong> 10:30 и 15:30</p>
                        </div>
                    </div>
                </div>

                <div class="animal-card">
                    <div class="animal-image">
                        <img src="../assets/images/zoo/los.jpg" alt="Лось">
                    </div>
                    <div class="animal-info">
                        <h3>Лось</h3>
                        <p class="scientific-name">Alces alces</p>
                        <div class="animal-details">
                            <p><strong>Возраст:</strong> 6 лет</p>
                            <p><strong>Вес:</strong> 500 кг</p>
                            <p><strong>Особенности:</strong> Величественное животное, любит водные процедуры</p>
                            <p><strong>Рацион:</strong> Трава, листья, кора</p>
                            <p><strong>Время кормления:</strong> 9:30 и 16:30</p>
                        </div>
                    </div>
                </div>

                <div class="animal-card">
                    <div class="animal-image">
                        <img src="../assets/images/zoo/zaits.webp" alt="Заяц">
                    </div>
                    <div class="animal-info">
                        <h3>Заяц-беляк</h3>
                        <p class="scientific-name">Lepus timidus</p>
                        <div class="animal-details">
                            <p><strong>Возраст:</strong> 2 года</p>
                            <p><strong>Вес:</strong> 4 кг</p>
                            <p><strong>Особенности:</strong> Быстрый и пугливый</p>
                            <p><strong>Рацион:</strong> Трава, овощи, фрукты</p>
                            <p><strong>Время кормления:</strong> 8:00, 12:00 и 18:00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="gallery" class="gallery-section">
            <h2>Фотогалерея</h2>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../assets/images/zoo/1.jpg" alt="Зоопарк">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="../assets/images/zoo/2.jpeg" alt="Зоопарк">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="../assets/images/zoo/3.jpg" alt="Зоопарк">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="../assets/images/zoo/4.jpg" alt="Зоопарк">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="../assets/images/zoo/5.jpg" alt="Зоопарк">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="../assets/images/zoo/6.jpg" alt="Зоопарк">
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

.zoo {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.hero-section {
    position: relative;
    background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/zoo-hero.jpg');
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

.zoo-map-section {
    background: var(--white);
    padding: 40px;
    border-radius: 15px;
    margin-bottom: 40px;
    box-shadow: var(--shadow);
}

.map-container {
    position: relative;
    width: 100%;
    height: 500px;
    margin-top: 20px;
    border-radius: 15px;
    overflow: hidden;
}

.map-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.map-point {
    position: absolute;
    width: 20px;
    height: 20px;
    background: var(--secondary-color);
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition);
    z-index: 2;
}

.map-point:hover {
    transform: scale(1.2);
}

.point-tooltip {
    position: absolute;
    background: var(--white);
    padding: 8px 15px;
    border-radius: 20px;
    box-shadow: var(--shadow);
    white-space: nowrap;
    top: -40px;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: var(--transition);
    font-weight: bold;
    color: var(--text-color);
}

.map-point:hover .point-tooltip {
    opacity: 1;
    top: -45px;
}

.animals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.animal-card {
    background: var(--white);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.animal-card:hover {
    transform: translateY(-5px);
}

.animal-image {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.animal-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.animal-card:hover .animal-image img {
    transform: scale(1.1);
}

.animal-overlay {
    position: absolute;
    top: 20px;
    right: 20px;
    background: var(--secondary-color);
    padding: 8px 15px;
    border-radius: 20px;
    color: var(--white);
    font-weight: bold;
}

.animal-content {
    padding: 25px;
}

.animal-description {
    margin: 15px 0;
    color: var(--text-color);
}

.animal-info {
    background: var(--light-bg);
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-item i {
    color: var(--accent-color);
    font-size: 1.2em;
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

    // Обработка точек на карте
    $('.map-point').click(function() {
        const animalType = $(this).data('animal');
        $('html, body').animate({
            scrollTop: $(`.animal-card[data-animal="${animalType}"]`).offset().top - 100
        }, 800);
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

    // Анимация при наведении на карточки животных
    $('.animal-card').hover(
        function() {
            $(this).find('.animal-image img').css('transform', 'scale(1.1)');
        },
        function() {
            $(this).find('.animal-image img').css('transform', 'scale(1)');
        }
    );
});
</script>

<?php require_once '../includes/footer.php'; ?> 