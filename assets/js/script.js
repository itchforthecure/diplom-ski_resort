document.addEventListener('DOMContentLoaded', function() {
    // Плавная прокрутка для якорных ссылок
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    
    // Форма подписки на новости
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            
            if (emailInput.value) {
                alert('Спасибо за подписку! Мы будем отправлять вам новости о курорте.');
                emailInput.value = '';
            }
        });
    }
    
    // Имитация загрузки погоды
    function updateWeather() {
        const conditions = ['sun', 'cloud-sun', 'cloud', 'cloud-rain', 'snowflake'];
        const icons = document.querySelectorAll('.weather-icon i, .forecast-day i');
        
        icons.forEach(icon => {
            const randomCondition = conditions[Math.floor(Math.random() * conditions.length)];
            icon.className = `fas fa-${randomCondition}`;
        });
        
        const tempElement = document.querySelector('.temp');
        if (tempElement) {
            const randomTemp = Math.floor(Math.random() * 10) - 5; // от -5 до +5
            tempElement.textContent = `${randomTemp}°C`;
        }
    }
    
    // Обновляем погоду каждые 10 секунд для демонстрации
    setInterval(updateWeather, 10000);
    
    // Обработка форм в личном кабинете
    const changePasswordForm = document.querySelector('.change-password form');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(e) {
            const newPassword = this.querySelector('#new_password').value;
            const confirmPassword = this.querySelector('#confirm_new_password').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Новый пароль и подтверждение не совпадают!');
            }
        });
    }
    
    // Инициализация календарей в формах бронирования
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.min = tomorrow.toISOString().split('T')[0];
    });
    
    // Анимация при скролле
    function animateOnScroll() {
        const elements = document.querySelectorAll('.feature, .room-card, .lift-card');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;
            
            if (elementPosition < screenPosition) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    }
    
    // Инициализация анимации
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Запустить сразу для видимых элементов
    
    // Предзагрузка анимации
    document.querySelectorAll('.feature, .room-card, .lift-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Элементы календаря
    const calendarDays = document.querySelector('.calendar-days');
    const currentMonthEl = document.querySelector('.current-month');
    const prevMonthBtn = document.querySelector('.prev-month');
    const nextMonthBtn = document.querySelector('.next-month');
    const eventsList = document.querySelector('.events-list');
    const modal = document.querySelector('.event-modal');
    const closeModal = document.querySelector('.close-modal');
    
    // Текущая дата
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    
    // Функция для загрузки событий с сервера
    async function fetchEvents(year, month) {
        try {
            const response = await fetch(`/api/get_events.php?year=${year}&month=${month}`);
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            
            // Преобразуем данные в нужный формат
            return data.map(event => ({
                id: event.id,
                title: event.title,
                date: event.date,
                time: event.time || '',
                location: event.location || '',
                description: event.description || '',
                image: event.image || '/assets/images/default_event.jpg',
                type: event.type || 'regular'
            }));
            
        } catch (error) {
            console.error('Error fetching events:', error);
            return [];
        }
    }

    // Инициализация календаря
    async function initCalendar() {
        // Очистка календаря
        calendarDays.innerHTML = '';
        
        // Установка заголовка месяца
        const monthNames = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", 
                          "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
        currentMonthEl.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        
        // Получение событий из базы данных
        const events = await fetchEvents(currentYear, currentMonth + 1);
        
        // Первый день месяца
        const firstDay = new Date(currentYear, currentMonth, 1);
        // Последний день месяца
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        // День недели первого дня месяца (0 - воскресенье, 1 - понедельник и т.д.)
        const firstDayIndex = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;
        // Последний день предыдущего месяца
        const prevLastDay = new Date(currentYear, currentMonth, 0).getDate();
        
        // Пустые дни в начале календаря
        for (let i = firstDayIndex; i > 0; i--) {
            const dayEl = document.createElement('div');
            dayEl.className = 'calendar-day empty';
            dayEl.innerHTML = `<span class="day-number">${prevLastDay - i + 1}</span>`;
            calendarDays.appendChild(dayEl);
        }
        
        // Дни текущего месяца
        for (let i = 1; i <= lastDay.getDate(); i++) {
            const dayEl = document.createElement('div');
            dayEl.className = 'calendar-day';
            
            // Проверка на сегодняшний день
            const today = new Date();
            if (i === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear()) {
                dayEl.classList.add('today');
            }
            
            dayEl.innerHTML = `<span class="day-number">${i}</span>`;
            
            // Добавление событий для этого дня
            const dayEvents = events.filter(event => {
                const eventDate = new Date(event.date);
                return eventDate.getDate() === i && 
                       eventDate.getMonth() === currentMonth && 
                       eventDate.getFullYear() === currentYear;
            });
            
            if (dayEvents.length > 0) {
                const eventsDots = document.createElement('div');
                eventsDots.className = 'day-events';
                
                dayEvents.forEach(event => {
                    const dot = document.createElement('div');
                    dot.className = `event-dot ${event.type === 'special' ? 'special' : ''}`;
                    dot.dataset.eventId = event.id;
                    eventsDots.appendChild(dot);
                });
                
                dayEl.appendChild(eventsDots);
                dayEl.addEventListener('click', () => showEventsForDay(i));
            }
            
            calendarDays.appendChild(dayEl);
        }
        
        // Оставшиеся дни в следующем месяце
        const totalDays = firstDayIndex + lastDay.getDate();
        const remainingDays = 7 - (totalDays % 7);
        
        if (remainingDays < 7) {
            for (let i = 1; i <= remainingDays; i++) {
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day empty';
                dayEl.innerHTML = `<span class="day-number">${i}</span>`;
                calendarDays.appendChild(dayEl);
            }
        }
        
        // Обновление списка ближайших событий
        updateEventsPreview(events);
    }
    
    // Показать события для выбранного дня
    function showEventsForDay(day) {
        // Форматируем дату для сравнения (YYYY-MM-DD)
        const formattedDate = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        // Загружаем события для конкретного дня
        fetch(`/api/get_events.php?date=${formattedDate}`)
            .then(response => response.json())
            .then(events => {
                if (events.length === 1) {
                    showEventModal(events[0]);
                } else if (events.length > 1) {
                    // Показываем список событий для этого дня
                    showEventsListModal(events, formattedDate);
                } else {
                    // Нет событий на этот день
                    alert('На этот день событий не запланировано.');
                }
            })
            .catch(error => {
                console.error('Error fetching day events:', error);
                alert('Произошла ошибка при загрузке событий.');
            });
    }
    
    // Показать модальное окно со списком событий на день
    function showEventsListModal(events, date) {
        const modalContent = `
            <h3>События на ${formatDisplayDate(date)}</h3>
            <div class="events-list-modal">
                ${events.map(event => `
                    <div class="event-item" data-event-id="${event.id}">
                        <div class="event-time">${event.time || 'Весь день'}</div>
                        <div class="event-title">${event.title}</div>
                    </div>
                `).join('')}
            </div>
        `;
        
        document.querySelector('.event-modal-content').innerHTML = modalContent;
        document.querySelector('.event-modal-content').classList.add('list-view');
        
        // Добавляем обработчики для каждого события
        document.querySelectorAll('.event-item').forEach(item => {
            item.addEventListener('click', function() {
                const eventId = this.dataset.eventId;
                const event = events.find(e => e.id == eventId);
                showEventModal(event);
            });
        });
        
        modal.style.display = 'block';
    }
    
    // Обновление списка ближайших событий
    function updateEventsPreview(events) {
        eventsList.innerHTML = '';
        
        // Фильтруем только будущие события
        const now = new Date();
        const upcomingEvents = events
            .filter(event => new Date(event.date) >= now)
            .sort((a, b) => new Date(a.date) - new Date(b.date))
            .slice(0, 3); // Берем ближайшие 3 события
        
        if (upcomingEvents.length === 0) {
            eventsList.innerHTML = '<p class="no-events">Нет предстоящих событий</p>';
            return;
        }
        
        upcomingEvents.forEach(event => {
            const eventDate = new Date(event.date);
            const monthNames = ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", 
                              "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"];
            
            const eventEl = document.createElement('div');
            eventEl.className = 'event-preview';
            eventEl.innerHTML = `
                <div class="event-preview-date">
                    <div class="event-preview-day">${eventDate.getDate()}</div>
                    <div class="event-preview-month">${monthNames[eventDate.getMonth()]}</div>
                </div>
                <div class="event-preview-title">${event.title}</div>
            `;
            
            eventEl.addEventListener('click', () => showEventModal(event));
            eventsList.appendChild(eventEl);
        });
    }
    
    // Показать модальное окно события
    function showEventModal(event) {
        const modalContent = document.querySelector('.event-modal-content');
        modalContent.classList.remove('list-view');
        
        const eventDate = new Date(event.date);
        const monthNames = ["Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", 
                          "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"];
        
        modalContent.innerHTML = `
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
        `;
        
        modal.style.display = 'block';
    }
    
    // Форматирование даты для отображения
    function formatDisplayDate(dateString) {
        const date = new Date(dateString);
        const day = date.getDate();
        const month = date.toLocaleString('ru', { month: 'long' });
        const year = date.getFullYear();
        return `${day} ${month} ${year}`;
    }
    
    // Закрыть модальное окно
    function closeEventModal() {
        modal.style.display = 'none';
    }
    
    // Навигация по месяцам
    prevMonthBtn.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        initCalendar();
    });
    
    nextMonthBtn.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        initCalendar();
    });
    
    // Закрытие модального окна
    closeModal.addEventListener('click', closeEventModal);
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeEventModal();
        }
    });
    
    // Инициализация календаря при загрузке
    initCalendar();
});


    // HERO SLIDER
    var menu = [];
    $('.swiper-slide').each( function(index){
        menu.push( $(this).find('.slide-inner').attr("data-text") );
    });
    
    var interleaveOffset = 0.5;
    var swiperOptions = {
        loop: true,
        speed: 1000,
        parallax: true,
        autoplay: {
            delay: 6500,
            disableOnInteraction: false,
        },
        watchSlidesProgress: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        
        
        on: {
            progress: function() {
                var swiper = this;
                for (var i = 0; i < swiper.slides.length; i++) {
                    var slideProgress = swiper.slides[i].progress;
                    var innerOffset = swiper.width * interleaveOffset;
                    var innerTranslate = slideProgress * innerOffset;
                    swiper.slides[i].querySelector(".slide-inner").style.transform =
                    "translate3d(" + innerTranslate + "px, 0, 0)";
                }      
            },
            touchStart: function() {
                var swiper = this;
                for (var i = 0; i < swiper.slides.length; i++) {
                    swiper.slides[i].style.transition = "";
                }
            },
            setTransition: function(speed) {
                var swiper = this;
                for (var i = 0; i < swiper.slides.length; i++) {
                    swiper.slides[i].style.transition = speed + "ms";
                    swiper.slides[i].querySelector(".slide-inner").style.transition =
                    speed + "ms";
                }
            }
        }
    };

    var swiper = new Swiper(".swiper-container", swiperOptions);

    // DATA BACKGROUND IMAGE
    var sliderBgSetting = $(".slide-bg-image");
    sliderBgSetting.each(function(indx){
        if ($(this).attr("data-background")){
            $(this).css("background-image", "url(" + $(this).data("background") + ")");
        }
    });

    