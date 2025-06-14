<?php 
require_once 'includes/config.php';
require_once 'includes/header.php'; 
?>

<div class="slideshow">
        <div class="slideshow-item">
            <img src="/assets/images/index/pexels-photo-416714.jpeg" alt="">
            <div class="slideshow-item-text">
                <h5>Снежная вершина</h5>
                <p>Горнолыжный курорт</p>
            </div>
        </div>
        <div class="slideshow-item">
            <img src="/assets/images/index/2.jpg" alt="">
            <div class="slideshow-item-text">
                <h5>Зона отеля</h5>
                <p>Предоставляем вам возможность насладиться отдыхом и красотой<br> горного пейзажа в комфортных условиях.</p>
            </div>
        </div>
        <div class="slideshow-item">
            <img src="/assets/images/index/3.jpg" alt="">
            <div class="slideshow-item-text">
                <h5>Трассы и прокат</h5>
                <p>Предлагаем прокатиться по горным трассам и получить незабываемые впечатления,<br> наш прокат предоставит все необходимое.</p>
            </div>
        </div>
        <div class="slideshow-item">
            <img src="/assets/images/index/4.jpeg" alt="">
            <div class="slideshow-item-text">
                <h5>Прочий досуг</h5>
                <p>Так же курорт имеет кафе, рестораны и зоны отдыха для всей семьи.<br> Для посетителей спортивных мероприятий и тренеров доступны экстремальные трассы.</p>            
            </div>
        </div>
    </div>


<section class="features">
    <h2>Почему выбирают нас</h2>
    <div class="features-grid">
        <div class="feature">
            <i class="fas fa-snowflake"></i>
            <h3>Современные трассы</h3>
            <p>Более 50 км трасс разного уровня сложности</p>
        </div>
        <div class="feature">
            <i class="fas fa-hotel"></i>
            <h3>Комфортабельные номера</h3>
            <p>Размещение на любой вкус и бюджет</p>
        </div>
        <div class="feature">
            <i class="fas fa-user-tie"></i>
            <h3>Профессиональные инструкторы</h3>
            <p>Обучение для взрослых и детей</p>
        </div>
        <div class="feature">
            <i class="fas fa-utensils"></i>
            <h3>Рестораны и кафе</h3>
            <p>Вкусная кухня после активного отдыха</p>
        </div>
    </div>
</section>

<!-- Секция погоды с улучшенным слайдером -->
<section class="weather-section">
    <div class="container">
        <h2 class="section-title"><i class="fas fa-snowflake"></i> Погода на курорте</h2>
        
        <!-- Текущая погода -->
        <div class="current-weather">
            <div class="current-temp">-3°C</div>
            <div class="current-details">
                <div class="current-icon"><i class="fas fa-snowflake"></i></div>
                <div class="current-desc">Легкий снег</div>
            </div>
        </div>

        <!-- Слайдер с прогнозом -->
        <div class="weather-slider-wrapper">
            <div class="weather-slider">
                <?php
                $forecast = [
                    ['day' => 'Сегодня', 'date' => date('d M'), 'temp_day' => '-3°', 'temp_night' => '-7°', 'icon' => 'snowflake', 'desc' => 'Снег'],
                    ['day' => 'Завтра', 'date' => date('d M', strtotime('+1 day')), 'temp_day' => '-1°', 'temp_night' => '-5°', 'icon' => 'cloud-sun', 'desc' => 'Переменная облачность'],
                    ['day' => 'Ср', 'date' => date('d M', strtotime('+2 day')), 'temp_day' => '+1°', 'temp_night' => '-3°', 'icon' => 'cloud-rain', 'desc' => 'Дождь со снегом'],
                    ['day' => 'Чт', 'date' => date('d M', strtotime('+3 day')), 'temp_day' => '-5°', 'temp_night' => '-9°', 'icon' => 'wind', 'desc' => 'Метель'],
                    ['day' => 'Пт', 'date' => date('d M', strtotime('+4 day')), 'temp_day' => '-8°', 'temp_night' => '-12°', 'icon' => 'sun', 'desc' => 'Ясно'],
                    ['day' => 'Сб', 'date' => date('d M', strtotime('+5 day')), 'temp_day' => '-4°', 'temp_night' => '-8°', 'icon' => 'cloud-sun-rain', 'desc' => 'Снегопад'],
                    ['day' => 'Вс', 'date' => date('d M', strtotime('+6 day')), 'temp_day' => '-2°', 'temp_night' => '-6°', 'icon' => 'snowman', 'desc' => 'Снежно']
                ];

                foreach ($forecast as $day): ?>
                <div class="weather-day">
                    <div class="day-header">
                        <div class="day-name"><?= $day['day'] ?></div>
                        <div class="day-date"><?= $day['date'] ?></div>
                    </div>
                    <div class="day-weather">
                        <div class="weather-icon"><i class="fas fa-<?= $day['icon'] ?>"></i></div>
                        <div class="weather-temp">
                            <span class="temp-day"><?= $day['temp_day'] ?></span>
                            <span class="temp-night"><?= $day['temp_night'] ?></span>
                        </div>
                    </div>
                    <div class="weather-desc"><?= $day['desc'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Дополнительные параметры -->
        <div class="weather-params">
            <div class="param">
                <i class="fas fa-wind"></i>
                <span>Ветер <strong>5 м/с, С-З</strong></span>
            </div>
            <div class="param">
                <i class="fas fa-tint"></i>
                <span>Влажность <strong>78%</strong></span>
            </div>
            <div class="param">
                <i class="fas fa-layer-group"></i>
                <span>Снег <strong>120 см</strong></span>
            </div>
            <div class="param">
                <i class="fas fa-temperature-low"></i>
                <span>Ощущается как <strong>-8°C</strong></span>
            </div>
        </div>
    </div>
</section>


<!-- Кафе и бары -->
<section class="promotions-section">
    <div class="container">
        <h2 class="section-title">Кафе и бары</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="promotion-card">
                    <img src="/assets/images/index/bar.avif" alt="Ресторан 'Снежная вершина'" class="img-fluid">
                    <div class="card-body">
                        <h3>Ресторан "Снежная вершина"</h3>
                        <p>Главный ресторан курорта с панорамным видом на горы. Европейская и русская кухня, широкий выбор вин.</p>
                        <div class="venue-info">
                            <span><i class="fas fa-clock"></i> 08:00 - 23:00</span>
                            <span><i class="fas fa-map-marker-alt"></i> Главное здание, 2 этаж</span>
                        </div>
                        <!-- <a href="#" class="btn btn-primary">Меню</a> -->
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="promotion-card">
                    <img src="/assets/images/index/33.avif" alt="Бар 'Альпийский'" class="img-fluid">
                    <div class="card-body">
                        <h3>Бар "Альпийский"</h3>
                        <p>Уютный бар с камином и террасой. Коктейли, горячие напитки, легкие закуски.</p>
                        <div class="venue-info">
                            <span><i class="fas fa-clock"></i> 12:00 - 02:00</span>
                            <span><i class="fas fa-map-marker-alt"></i> Зона отдыха</span>
                        </div>
                        <!-- <a href="#" class="btn btn-primary">Карта бара</a> -->
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="promotion-card">
                    <img src="/assets/images/index/kafe.avif" alt="Кафе 'Снежинка'" class="img-fluid">
                    <div class="card-body">
                        <h3>Кафе "Снежинка"</h3>
                        <p>Быстрое питание на склоне. Горячие супы, сэндвичи, выпечка, горячие напитки.</p>
                        <div class="venue-info">
                            <span><i class="fas fa-clock"></i> 09:00 - 20:00</span>
                            <span><i class="fas fa-map-marker-alt"></i> Средняя станция</span>
                        </div>
                        <!-- <a href="#" class="btn btn-primary">Меню</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Календарь событий -->
<section class="events-section">
    <div class="container">
        <h2 class="section-title">Календарь событий</h2>
        
        <?php
        // Получаем события из базы данных
        $currentMonth = date('n');
        $currentYear = date('Y');
        
        $query = "SELECT * FROM calendar_events 
                 WHERE MONTH(event_date) = ? AND YEAR(event_date) = ?
                 ORDER BY event_date, start_time";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $currentMonth, $currentYear);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        // Группируем события по дням
        $eventsByDay = [];
        foreach ($events as $event) {
            $day = date('j', strtotime($event['event_date']));
            $eventsByDay[$day][] = $event;
        }
        ?>
        
        <div class="modern-calendar">
            <div class="calendar-header">
                <h3 class="current-month"><?= date('F Y') ?></h3>
            </div>
            
            <div class="calendar-weekdays">
                <div>Пн</div>
                <div>Вт</div>
                <div>Ср</div>
                <div>Чт</div>
                <div>Пт</div>
                <div>Сб</div>
                <div>Вс</div>
            </div>
            
            <div class="calendar-days">
                <?php
                // Определяем первый день месяца и количество дней
                $firstDay = date('N', strtotime(date('Y-m-01')));
                $daysInMonth = date('t');
                
                // Пустые ячейки для дней предыдущего месяца
                for ($i = 1; $i < $firstDay; $i++) {
                    echo '<div class="calendar-day empty"></div>';
                }
                
                // Ячейки с днями текущего месяца
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $isToday = ($day == date('j') && $currentMonth == date('n'));
                    $hasEvents = isset($eventsByDay[$day]);
                    
                    echo '<div class="calendar-day ' . ($isToday ? 'today' : '') . '">';
                    echo '<span class="day-number">' . $day . '</span>';
                    
                    if ($hasEvents) {
                        echo '<div class="day-events">';
                        foreach ($eventsByDay[$day] as $event) {
                            $eventClass = $event['event_type'] == 'special' ? 'special' : '';
                            echo '<div class="event-dot ' . $eventClass . '" 
                                 data-event-id="' . $event['id'] . '"></div>';
                        }
                        echo '</div>';
                    }
                    
                    echo '</div>';
                }
                ?>
            </div>
            
            <div class="calendar-events-preview">
                <h4>Ближайшие события</h4>
                <div class="events-list">
                    <?php
                    // Ближайшие 3 события
                    $upcomingQuery = "SELECT * FROM calendar_events 
                                    WHERE event_date >= CURDATE()
                                    ORDER BY event_date, start_time
                                    LIMIT 3";
                    $upcomingResult = mysqli_query($connection, $upcomingQuery);
                    
                    if (mysqli_num_rows($upcomingResult) > 0) {
                        while ($event = mysqli_fetch_assoc($upcomingResult)) {
                            echo '<div class="event-preview" data-event-id="' . $event['id'] . '">';
                            echo '<div class="event-preview-date">';
                            echo '<div class="event-preview-day">' . date('j', strtotime($event['event_date'])) . '</div>';
                            echo '<div class="event-preview-month">' . date('M', strtotime($event['event_date'])) . '</div>';
                            echo '</div>';
                            echo '<div class="event-preview-title">' . htmlspecialchars($event['title']) . '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="no-events">Нет предстоящих событий</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Модальное окно события -->
<div class="modal event-modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="event-modal-content">
            <!-- Содержимое будет заполнено JavaScript -->
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Обработчик клика на событие
    $('.event-preview, .event-dot').on('click', function() {
        const eventId = $(this).data('event-id');
        showEventModal(eventId);
    });
    
    // Функция показа модального окна
    function showEventModal(eventId) {
        $.ajax({
            url: '/api/get_event.php',
            method: 'GET',
            data: { id: eventId },
            success: function(response) {
                if (response.success) {
                    const event = response.event;
                    const eventDate = new Date(event.event_date);
                    const monthNames = ["Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", 
                                      "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"];
                    
                    let modalContent = `
                        ${event.image_path ? `<img src="${event.image_path}" class="event-image">` : ''}
                        <div class="event-details">
                            <h3>${event.title}</h3>
                            <div class="event-meta">
                                <span><i class="far fa-calendar-alt"></i> ${eventDate.getDate()} ${monthNames[eventDate.getMonth()]} ${eventDate.getFullYear()}</span>
                                ${event.start_time ? `<span><i class="far fa-clock"></i> ${event.start_time}</span>` : ''}
                                ${event.location ? `<span><i class="fas fa-map-marker-alt"></i> ${event.location}</span>` : ''}
                            </div>
                            <p>${event.description || 'Описание отсутствует.'}</p>
                        </div>
                    `;
                    
                    $('.event-modal-content').html(modalContent);
                    $('.event-modal').fadeIn();
                }
            }
        });
    }
    
    // Закрытие модального окна
    $('.close-modal, .event-modal').on('click', function(e) {
        if ($(e.target).is('.close-modal') || $(e.target).is('.event-modal')) {
            $('.event-modal').fadeOut();
        }
    });
});
</script>

<style>
    .slideshow {
    width: 100%;
    height: 500px;
    position: relative;
    overflow: hidden;
    background: #000;
    margin: 20px 0;
}
.slideshow-item {
    width: 100%;
    height: 100%;
    position: absolute;
    opacity: 0;
    animation: slideanim 40s infinite;
    pointer-events: none;
}
.slideshow-item:nth-child(1),
.slideshow-item:nth-child(1) img {
    animation-delay: 0;
}
.slideshow-item:nth-child(2),
.slideshow-item:nth-child(2) img {
    animation-delay: 10s;
}
.slideshow-item:nth-child(3),
.slideshow-item:nth-child(3) img {
    animation-delay: 20s;
}
.slideshow-item:nth-child(4),
.slideshow-item:nth-child(4) img {
    animation-delay: 30s;
}
.slideshow-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    animation: zoom 40s infinite;
}
.slideshow-item-text {
    max-width: 50%;
    position: absolute;
    bottom: 20px;
    left: 20px;
    background-color: rgba(0,0,0,0.7);
    color: #fff;
    padding: 20px 30px;
    font-family: Verdana, sans-serif;   
}
.slideshow-item-text h5 {
    font-size: 22px;
    margin: 0 0 10px 0;
    color: #BFE2FF;
}
.slideshow-item-text p {
    font-size: 15px;
    margin-bottom: 10px;
}
@keyframes slideanim {
    12.5%{
        opacity: 1;
        pointer-events: auto;
    }
    25%{
        opacity: 1;
        pointer-events: auto;
    }    
    37.5%{
        opacity: 0;
    }
}
@keyframes zoom {
    50%{
        transform: scale(1.3);
    }    
}
@media screen and (max-width: 1100px){
    .slideshow-item-text{
        max-width: 75%;
    }
}
@media screen and (max-width: 456px){
    .slideshow-item-text {
        bottom: 0;
        left: 0;
        max-width: 100%;
    }
    .slideshow-item-text h5 {
        font-size: 18px;
    }
    .slideshow-item-text p {
        font-size: 13px;
    }
}

/* Общие стили для секции погоды */
.weather-section {
    background: linear-gradient(to bottom, #1e88e5, #0d47a1);
    color: white;
    padding: 2rem 0;
    border-radius: 15px;
    margin: 2rem auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    max-width: 1200px;
}

/* .container {
    width: 95%;
    max-width: 1200px;
    margin: 0 auto;
} */

.section-title {
    text-align: center;
    font-size: 2.2rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.section-title i {
    font-size: 1.8rem;
}

/* Стили для текущей погоды */
.current-weather {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
}

.current-temp {
    font-size: 4rem;
    font-weight: 300;
    line-height: 1;
}

.current-details {
    text-align: center;
}

.current-icon i {
    font-size: 3.5rem;
    margin-bottom: 0.5rem;
}

.current-desc {
    font-size: 1.2rem;
    opacity: 0.9;
}

/* Стили для слайдера погоды */
.weather-slider-wrapper {
    position: relative;
    margin: 0 auto 2rem;
    max-width: 100%;
    padding: 0 40px;
}

.weather-slider {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
    padding: 15px 0;
}

.weather-slider::-webkit-scrollbar {
    display: none;
}

.weather-day {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.2rem;
    min-width: 120px;
    text-align: center;
    backdrop-filter: blur(5px);
    transition: all 0.3s ease;
    flex: 0 0 auto;
}

.weather-day:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-5px);
}

.day-header {
    margin-bottom: 1rem;
}

.day-name {
    font-weight: bold;
    font-size: 1.1rem;
}

.day-date {
    font-size: 0.9rem;
    opacity: 0.8;
}

.weather-icon i {
    font-size: 2.2rem;
    margin: 0.5rem 0;
}

.weather-temp {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin: 0.5rem 0;
}

.temp-day {
    font-weight: bold;
}

.temp-night {
    opacity: 0.7;
}

.weather-desc {
    font-size: 0.9rem;
    opacity: 0.9;
}

.slider-controls {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between;
    pointer-events: none;
}

.slider-prev, .slider-next {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: all;
    transition: all 0.3s ease;
}

.slider-prev:hover, .slider-next:hover {
    background: rgba(255,255,255,0.3);
    transform: scale(1.1);
}

/* Стили для параметров погоды */
.weather-params {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 2rem;
}

.param {
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.95rem;
}

.param i {
    font-size: 1.2rem;
    opacity: 0.8;
}

.param strong {
    font-weight: 600;
}

/* Адаптивные стили */
@media (max-width: 768px) {
    .current-weather {
        flex-direction: column;
        gap: 1rem;
    }
    
    .current-temp {
        font-size: 3.5rem;
    }
    
    .weather-slider-wrapper {
        padding: 0 30px;
    }
    
    .weather-day {
        min-width: 100px;
        padding: 1rem 0.8rem;
    }
    
    .weather-params {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 480px) {
    .section-title {
        font-size: 1.8rem;
    }
    
    .weather-params {
        grid-template-columns: 1fr;
    }
    
    .slider-prev, .slider-next {
        width: 35px;
        height: 35px;
    }
}

/* Стили для календаря событий */
.modern-calendar {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 25px;
    margin-bottom: 40px;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.current-month {
    font-size: 1.4rem;
    color: #2c3e50;
    margin: 0 15px;
}

.nav-btn {
    background: #f8f9fa;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-btn:hover {
    background: #e9ecef;
    transform: scale(1.1);
}

.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 15px;
}

.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
}

.calendar-day {
    height: 90px;
    padding: 8px;
    border-radius: 8px;
    background: #f8f9fa;
    position: relative;
    transition: all 0.3s;
    cursor: pointer;
    overflow: hidden;
}

.calendar-day:hover {
    background: #e9ecef;
    transform: translateY(-3px);
}

.calendar-day.empty {
    background: transparent;
    cursor: default;
}

.calendar-day.today {
    background: #d4edff;
    font-weight: bold;
}

.day-number {
    font-size: 1.1rem;
    font-weight: 500;
}

.day-events {
    position: absolute;
    bottom: 5px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
}

.event-dot {
    width: 6px;
    height: 6px;
    background: #3498db;
    border-radius: 50%;
    margin: 0 1px;
}

.event-dot.special {
    background: #e74c3c;
}

.calendar-events-preview {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.events-list {
    margin-top: 15px;
}

.event-preview {
    display: flex;
    align-items: center;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 10px;
    background: #f8f9fa;
    transition: all 0.3s;
    cursor: pointer;
}

.event-preview:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.event-preview-date {
    min-width: 50px;
    text-align: center;
    margin-right: 15px;
}

.event-preview-day {
    font-size: 1.4rem;
    font-weight: bold;
    color: #3498db;
}

.event-preview-month {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
}

.event-preview-title {
    font-weight: 500;
}

/* Стили для модального окна события */
.event-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
}

.event-modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 0;
    width: 70%;
    max-width: 800px;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    animation: modalFadeIn 0.4s;
}

@keyframes modalFadeIn {
    from {opacity: 0; transform: translateY(-50px);}
    to {opacity: 1; transform: translateY(0);}
}

.event-image {
    width: 40%;
    object-fit: cover;
}

.event-details {
    width: 60%;
    padding: 30px;
}

.event-title {
    font-size: 1.8rem;
    margin-bottom: 15px;
    color: #2c3e50;
}

.event-meta {
    margin-bottom: 20px;
}

.event-meta span {
    display: block;
    margin-bottom: 8px;
    color: #6c757d;
}

.event-meta i {
    width: 20px;
    text-align: center;
    margin-right: 8px;
    color: #3498db;
}

.event-description {
    line-height: 1.6;
    margin-bottom: 25px;
}

.close-modal {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 28px;
    font-weight: bold;
    color: #aaa;
    cursor: pointer;
    transition: all 0.3s;
}

.close-modal:hover {
    color: #333;
    transform: rotate(90deg);
}

.btn-book {
    background: #3498db;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s;
}

.btn-book:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
}

/* Адаптивность */
@media (max-width: 768px) {
    .event-modal-content {
        flex-direction: column;
        width: 90%;
    }
    
    .event-image, .event-details {
        width: 100%;
    }
    
    .calendar-day {
        height: 60px;
    }
}

.promotions-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.promotion-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    margin-bottom: 30px;
}

.promotion-card:hover {
    transform: translateY(-10px);
}

.promotion-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.card-body {
    padding: 20px;
}

.card-body h3 {
    font-size: 1.4rem;
    margin-bottom: 10px;
    color: #2c3e50;
}

.card-body p {
    color: #666;
    margin-bottom: 15px;
    line-height: 1.6;
}

.venue-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 15px;
    color: #666;
}

.venue-info span {
    display: flex;
    align-items: center;
    gap: 8px;
}

.venue-info i {
    color: #4facfe;
    width: 20px;
}

.btn-primary {
    background: #4facfe;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #3a8fd4;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
}

@media (max-width: 768px) {
    .promotion-card {
        margin-bottom: 20px;
    }
    
    .card-body h3 {
        font-size: 1.2rem;
    }
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Инициализация слайдера погоды
    const $weatherSlider = $('.weather-slider');
    const $prevBtn = $('.slider-prev');
    const $nextBtn = $('.slider-next');
    const slideWidth = $('.weather-day').outerWidth(true);
    const visibleSlides = Math.floor($('.weather-slider-wrapper').width() / slideWidth);
    
    // Прокрутка слайдера
    function scrollWeatherSlider(direction) {
        const scrollAmount = direction === 'next' ? slideWidth * 3 : -slideWidth * 3;
        $weatherSlider.animate({scrollLeft: $weatherSlider.scrollLeft() + scrollAmount}, 300);
    }
    
    $prevBtn.on('click', function() {
        scrollWeatherSlider('prev');
    });
    
    $nextBtn.on('click', function() {
        scrollWeatherSlider('next');
    });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Инициализация слайдера погоды
    const $weatherSlider = $('.weather-slider');
    const $prevBtn = $('.slider-prev');
    const $nextBtn = $('.slider-next');
    const slideWidth = $('.weather-day').outerWidth(true);
    const sliderWrapperWidth = $('.weather-slider-wrapper').width();
    let currentPosition = 0;
    
    // Функция для обновления позиции слайдера
    function updateSliderPosition() {
        $weatherSlider.css('transform', `translateX(-${currentPosition}px)`);
    }
    
    // Прокрутка вперёд
    $nextBtn.on('click', function() {
        const maxPosition = $weatherSlider.width() - sliderWrapperWidth;
        currentPosition += slideWidth * 3; // Прокручиваем на 3 слайда
        
        if (currentPosition > maxPosition) {
            currentPosition = maxPosition;
        }
        
        updateSliderPosition();
    });
    
    // Прокрутка назад
    $prevBtn.on('click', function() {
        currentPosition -= slideWidth * 3; // Прокручиваем на 3 слайда назад
        
        if (currentPosition < 0) {
            currentPosition = 0;
        }
        
        updateSliderPosition();
    });
    // Инициализация календаря
    async function initCalendar() {
        calendarDays.empty();
        
        const monthNames = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", 
                          "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
        currentMonthEl.text(`${monthNames[currentMonth]} ${currentYear}`);
        
        const events = fetchEvents(currentYear, currentMonth + 1);
        
        const firstDay = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        const firstDayIndex = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;
        const prevLastDay = new Date(currentYear, currentMonth, 0).getDate();
        
        // Пустые дни в начале
        for (let i = firstDayIndex; i > 0; i--) {
            calendarDays.append(`<div class="calendar-day empty"><span class="day-number">${prevLastDay - i + 1}</span></div>`);
        }
        
        // Дни текущего месяца
        for (let i = 1; i <= lastDay.getDate(); i++) {
            let dayClass = 'calendar-day';
            const today = new Date();
            if (i === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear()) {
                dayClass += ' today';
            }
            
            let dayHtml = `<span class="day-number">${i}</span>`;
            
            // Добавление событий
            const dayEvents = events.filter(event => {
                const eventDate = new Date(event.date);
                return eventDate.getDate() === i && 
                       eventDate.getMonth() === currentMonth && 
                       eventDate.getFullYear() === currentYear;
            });
            
            if (dayEvents.length > 0) {
                let dotsHtml = '<div class="day-events">';
                dayEvents.forEach(event => {
                    dotsHtml += `<div class="event-dot ${event.type === 'special' ? 'special' : ''}" data-event-id="${event.id}"></div>`;
                });
                dotsHtml += '</div>';
                dayHtml += dotsHtml;
            }
            
            const dayEl = $(`<div class="${dayClass}">${dayHtml}</div>`);
            
            if (dayEvents.length > 0) {
                dayEl.on('click', () => showEventsForDay(i, events));
            }
            
            calendarDays.append(dayEl);
        }
        
        // Оставшиеся дни
        const totalDays = firstDayIndex + lastDay.getDate();
        const remainingDays = 7 - (totalDays % 7);
        
        if (remainingDays < 7) {
            for (let i = 1; i <= remainingDays; i++) {
                calendarDays.append(`<div class="calendar-day empty"><span class="day-number">${i}</span></div>`);
            }
        }
        
        // Обновление списка событий
        updateEventsPreview(events);
    }
    
    // Показать события дня
    function showEventsForDay(day, events) {
        const dayEvents = events.filter(event => {
            const eventDate = new Date(event.date);
            return eventDate.getDate() === day && 
                   eventDate.getMonth() === currentMonth && 
                   eventDate.getFullYear() === currentYear;
        });
        
        if (dayEvents.length === 1) {
            showEventModal(dayEvents[0]);
        } else if (dayEvents.length > 1) {
            showEventsListModal(dayEvents);
        }
    }
    
    // Показать модальное окно события
    function showEventModal(event) {
        const eventDate = new Date(event.date);
        const monthNames = ["Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", 
                          "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"];
        
        $('.event-modal-content').html(`
            <img src="${event.image}" alt="${event.title}" class="event-image">
            <div class="event-details">
                <h3 class="event-title">${event.title}</h3>
                <div class="event-meta">
                    <span class="event-date"><i class="far fa-calendar-alt"></i> ${eventDate.getDate()} ${monthNames[eventDate.getMonth()]} ${eventDate.getFullYear()}</span>
                    ${event.time ? `<span class="event-time"><i class="far fa-clock"></i> ${event.time}</span>` : ''}
                    ${event.location ? `<span class="event-location"><i class="fas fa-map-marker-alt"></i> ${event.location}</span>` : ''}
                </div>
                <p class="event-description">${event.description || 'Описание отсутствует.'}</p>
                <button class="btn btn-book">Записаться</button>
            </div>
        `);
        
        modal.fadeIn();
    }
    
    // Обновление списка событий
    function updateEventsPreview(events) {
        eventsList.empty();
        
        const now = new Date();
        const upcomingEvents = events
            .filter(event => new Date(event.date) >= now)
            .sort((a, b) => new Date(a.date) - new Date(b.date))
            .slice(0, 3);
        
        if (upcomingEvents.length === 0) {
            eventsList.append('<p class="no-events">Нет предстоящих событий</p>');
            return;
        }
        
        upcomingEvents.forEach(event => {
            const eventDate = new Date(event.date);
            const monthNames = ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", 
                              "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"];
            
            const eventEl = $(`
                <div class="event-preview">
                    <div class="event-preview-date">
                        <div class="event-preview-day">${eventDate.getDate()}</div>
                        <div class="event-preview-month">${monthNames[eventDate.getMonth()]}</div>
                    </div>
                    <div class="event-preview-title">${event.title}</div>
                </div>
            `);
            
            eventEl.on('click', () => showEventModal(event));
            eventsList.append(eventEl);
        });
    }
    
    // Навигация по месяцам
    prevMonthBtn.on('click', function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        initCalendar();
    });
    
    nextMonthBtn.on('click', function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        initCalendar();
    });
    
    // Закрытие модального окна
    closeModal.on('click', function() {
        modal.fadeOut();
    });
    
    $(window).on('click', function(e) {
        if ($(e.target).is(modal)) {
            modal.fadeOut();
        }
    });
    
    // Инициализация
    initCalendar();
});
</script>

<?php require_once 'includes/footer.php'; ?>