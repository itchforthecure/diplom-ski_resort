<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Горнолыжный курорт "Снежная вершина"</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Rajdhani&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2b5876;
            --secondary-color: #4e4376;
            --accent-color: #4facfe;
            --text-light: #ffffff;
            --text-dark: #333333;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .header-main {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            width: 100%;
            top: 0;
            z-index: 1000;
            padding: 12px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .container-header {
            width: 95%;
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .logo-title {
            font-size: 24px;
            color: var(--text-light);
            font-weight: 700;
            margin: 0;
        }

        .logo-subtitle {
            font-size: 12px;
            color: rgba(255,255,255,0.7);
            margin: 0;
            letter-spacing: 1px;
        }

        .nav-list {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            align-items: center;
            gap: 15px;
        }

        .nav-link {
            color: var(--text-light);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            padding: 8px 0;
            position: relative;
            display: inline-flex;
            align-items: center;
            transition: var(--transition);
            white-space: nowrap;
        }

        .nav-link i {
            margin-right: 6px;
            font-size: 14px;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 4px;
            left: 0;
            background-color: var(--accent-color);
            transition: var(--transition);
            transform-origin: left;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: var(--accent-color);
            transform: translateY(-2px);
        }

        /* Выпадающее меню */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            background: white;
            border-radius: 4px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            min-width: 200px;
            padding: 8px 0;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            transform: translateY(10px);
            z-index: 1000;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-link {
            color: var(--text-dark);
            padding: 8px 15px;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }

        .dropdown-link:hover {
            background: rgba(79,172,254,0.1);
            color: var(--accent-color);
            padding-left: 20px;
        }

        .dropdown-icon {
            margin-right: 8px;
            width: 20px;
            text-align: center;
        }

        /* Админ меню */
        .admin-menu {
            position: relative;
        }

        .admin-submenu {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border-radius: 4px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            min-width: 200px;
            padding: 8px 0;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            transform: translateY(10px);
        }

        .admin-menu:hover .admin-submenu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .admin-submenu a {
            color: var(--text-dark);
            padding: 8px 15px;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }

        .admin-submenu a:hover {
            background: rgba(79,172,254,0.1);
            color: var(--accent-color);
            padding-left: 20px;
        }

        .admin-submenu i {
            width: 18px;
            margin-right: 10px;
        }

        /* Разделитель */
        .menu-divider {
            color: rgba(255,255,255,0.3);
            font-weight: bold;
        }

        /* Бургер-меню для мобильных */
        .burger {
            display: none;
            cursor: pointer;
            flex-direction: column;
            justify-content: space-between;
            width: 30px;
            height: 21px;
            z-index: 1001;
        }

        .burger-line {
            width: 100%;
            height: 3px;
            background-color: var(--text-light);
            transition: all 0.3s ease;
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            max-width: 350px;
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: -5px 0 15px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: right 0.3s ease;
            padding: 80px 20px 20px;
            overflow-y: auto;
        }

        .mobile-menu.active {
            right: 0;
        }

        .mobile-nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-nav-item {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .mobile-nav-link {
            color: var(--text-light);
            text-decoration: none;
            font-size: 16px;
            padding: 12px 0;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }

        .mobile-nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .dropdown-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .dropdown-toggle i.fa-chevron-down {
            transition: transform 0.3s;
        }

        .mobile-dropdown {
            display: none;
            padding-left: 20px;
            list-style: none;
        }

        .mobile-dropdown.active {
            display: block;
        }

        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }

        .menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Анимации бургера */
        .burger.active .burger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .burger.active .burger-line:nth-child(2) {
            opacity: 0;
        }

        .burger.active .burger-line:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        /* Адаптация */
        @media (max-width: 992px) {
            .nav-list {
                display: none;
            }
            
            .burger {
                display: flex;
            }
        }

        @media (max-width: 576px) {
            .logo-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
<header class="header-main">
    <div class="container-header">
        <div class="logo">
            <h1 class="logo-title">Снежная вершина</h1>
            <p class="logo-subtitle">Горнолыжный курорт</p>
        </div>
        
        <!-- Бургер-иконка для мобильных -->
        <div class="burger">
            <div class="burger-line"></div>
            <div class="burger-line"></div>
            <div class="burger-line"></div>
        </div>
        
        <!-- Основное меню для десктопов -->
        <nav class="main-nav">
            <ul class="nav-list">
                <!-- Основное меню для всех пользователей -->
                <li class="nav-item"><a href="/index.php" class="nav-link"><i class="fas fa-home nav-icon"></i><span class="nav-text">Главная</span></a></li>
                <li class="nav-item"><a href="/pages/rooms.php" class="nav-link"><i class="fas fa-bed nav-icon"></i><span class="nav-text">Номера</span></a></li>
                <li class="nav-item"><a href="/pages/reviews.php" class="nav-link"><i class="fas fa-comments nav-icon"></i><span class="nav-text">Отзывы</span></a></li>
                
                <!-- Раздел активностей с выпадающими меню -->
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link"><i class="fas fa-skiing nav-icon"></i><span class="nav-text">Зимние развлечения</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/pages/activities.php" class="dropdown-link"><i class="fas fa-skiing dropdown-icon"></i>Все зимние активности</a></li>
                        <li><a href="/pages/lifts.php" class="dropdown-link"><i class="fas fa-ticket-alt dropdown-icon"></i>Ски-пассы</a></li>
                        <li><a href="/pages/instructors.php" class="dropdown-link"><i class="fas fa-user-tie dropdown-icon"></i>Инструкторы</a></li>
                        <li><a href="/pages/map.php" class="dropdown-link"><i class="fas fa-map-marked-alt dropdown-icon"></i>Карта трасс</a></li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link"><i class="fas fa-sun nav-icon"></i><span class="nav-text">Летние развлечения</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/pages/zoo.php" class="dropdown-link"><i class="fas fa-paw dropdown-icon"></i>Наш зоопарк</a></li>
                        <li><a href="/pages/rope_park.php" class="dropdown-link"><i class="fas fa-tree dropdown-icon"></i>Верёвочный парк</a></li>
                    </ul>
                </li>
                
                <!-- Меню для авторизованных пользователей -->
                <?php if (isLoggedIn()): ?>
                    <li class="menu-divider">|</li>
                    <li class="nav-item"><a href="/account/profile.php" class="nav-link"><i class="fas fa-user-circle nav-icon"></i><span class="nav-text">Личный кабинет</span></a></li>
                    <li class="nav-item"><a href="/account/logout.php" class="nav-link"><i class="fas fa-sign-out-alt nav-icon"></i><span class="nav-text">Выйти</span></a></li>
                    
                    <!-- Дополнительное меню для администраторов -->
                    <?php if (isAdmin()): ?>
                        <li class="menu-divider">|</li>
                        <li class="nav-item admin-menu">
                            <a href="#" class="nav-link"><i class="fas fa-cog nav-icon"></i><span class="nav-text">Администрирование</span></a>
                            <ul class="admin-submenu">
                                <li><a href="/admin/" class="dropdown-link"><i class="fas fa-tachometer-alt dropdown-icon"></i> Панель управления</a></li>
                                <li><a href="/admin/activities.php" class="dropdown-link"><i class="fas fa-skiing dropdown-icon"></i> Активности</a></li>
                                <li><a href="/admin/lifts.php" class="dropdown-link"><i class="fas fa-ski-lift dropdown-icon"></i> Ски-пассы</a></li>
                                <li><a href="/admin/instructors.php" class="dropdown-link"><i class="fas fa-user-tie dropdown-icon"></i> Инструкторы</a></li>
                                <li><a href="/admin/bookings.php" class="dropdown-link"><i class="fas fa-calendar-check dropdown-icon"></i> Бронирования</a></li>
                                <li><a href="/admin/users.php" class="dropdown-link"><i class="fas fa-users dropdown-icon"></i> Пользователи</a></li>
                                <li><a href="/admin/events.php" class="dropdown-link"><i class="fas fa-calendar-alt dropdown-icon"></i> События</a></li>
                                <li><a href="/admin/slopes.php" class="dropdown-link"><i class="fas fa-mountain dropdown-icon"></i> Трассы</a></li>
                                <li><a href="/admin/rooms.php" class="dropdown-link"><i class="fas fa-hotel dropdown-icon"></i> Номера</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Меню для гостей -->
                    <li class="menu-divider">|</li>
                    <li class="nav-item"><a href="/account/login.php" class="nav-link"><i class="fas fa-sign-in-alt nav-icon"></i><span class="nav-text">Вход</span></a></li>
                    <li class="nav-item"><a href="/account/register.php" class="nav-link"><i class="fas fa-user-plus nav-icon"></i><span class="nav-text">Регистрация</span></a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<!-- Мобильное меню -->
<div class="mobile-menu">
    <ul class="mobile-nav-list">
        <li class="mobile-nav-item">
            <a href="/index.php" class="mobile-nav-link">
                <i class="fas fa-home"></i> Главная
            </a>
        </li>
        
        <li class="mobile-nav-item">
            <a href="/pages/rooms.php" class="mobile-nav-link">
                <i class="fas fa-bed"></i> Номера
            </a>
        </li>
        
        <li class="mobile-nav-item">
            <a href="/pages/reviews.php" class="mobile-nav-link">
                <i class="fas fa-comments"></i> Отзывы
            </a>
        </li>
        
        <!-- Зимние развлечения с выпадающим меню -->
        <li class="mobile-nav-item">
            <div class="dropdown-toggle mobile-nav-link">
                <span><i class="fas fa-skiing"></i> Зимние развлечения</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <ul class="mobile-dropdown">
                <li><a href="/pages/activities.php" class="mobile-dropdown-link"><i class="fas fa-skiing"></i> Все активности</a></li>
                <li><a href="/pages/lifts.php" class="mobile-dropdown-link"><i class="fas fa-ticket-alt"></i> Ски-пассы</a></li>
                <li><a href="/pages/instructors.php" class="mobile-dropdown-link"><i class="fas fa-user-tie"></i> Инструкторы</a></li>
                <li><a href="/pages/map.php" class="mobile-dropdown-link"><i class="fas fa-map-marked-alt"></i> Карта трасс</a></li>
            </ul>
        </li>
        
        <!-- Летние развлечения с выпадающим меню -->
        <li class="mobile-nav-item">
            <div class="dropdown-toggle mobile-nav-link">
                <span><i class="fas fa-sun"></i> Летние развлечения</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <ul class="mobile-dropdown">
                <li><a href="/pages/zoo.php" class="mobile-dropdown-link"><i class="fas fa-paw"></i> Наш зоопарк</a></li>
                <li><a href="/pages/rope_park.php" class="mobile-dropdown-link"><i class="fas fa-tree"></i> Верёвочный парк</a></li>
            </ul>
        </li>
        
        <!-- Меню для авторизованных пользователей -->
        <?php if (isLoggedIn()): ?>
            <li class="menu-divider">|</li>
            <li class="mobile-nav-item">
                <a href="/account/profile.php" class="mobile-nav-link">
                    <i class="fas fa-user-circle"></i> Личный кабинет
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="/account/logout.php" class="mobile-nav-link">
                    <i class="fas fa-sign-out-alt"></i> Выйти
                </a>
            </li>
            
            <!-- Админ-меню (только для администраторов) -->
            <?php if (isAdmin()): ?>
                <li class="mobile-nav-item">
                    <div class="dropdown-toggle mobile-nav-link">
                        <span><i class="fas fa-cog"></i> Администрирование</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <ul class="mobile-dropdown">
                        <li><a href="/admin/" class="mobile-dropdown-link"><i class="fas fa-tachometer-alt"></i> Панель управления</a></li>
                        <li><a href="/admin/activities.php" class="mobile-dropdown-link"><i class="fas fa-skiing"></i> Активности</a></li>
                        <li><a href="/admin/lifts.php" class="mobile-dropdown-link"><i class="fas fa-ski-lift"></i> Ски-пассы</a></li>
                        <li><a href="/admin/instructors.php" class="mobile-dropdown-link"><i class="fas fa-user-tie"></i> Инструкторы</a></li>
                        <li><a href="/admin/bookings.php" class="mobile-dropdown-link"><i class="fas fa-calendar-check"></i> Бронирования</a></li>
                        <li><a href="/admin/users.php" class="mobile-dropdown-link"><i class="fas fa-users"></i> Пользователи</a></li>
                        <li><a href="/admin/events.php" class="mobile-dropdown-link"><i class="fas fa-calendar-alt"></i> События</a></li>
                        <li><a href="/admin/slopes.php" class="mobile-dropdown-link"><i class="fas fa-mountain"></i> Трассы</a></li>
                        <li><a href="/admin/rooms.php" class="mobile-dropdown-link"><i class="fas fa-hotel"></i> Номера</a></li>
                    </ul>
                </li>
            <?php endif; ?>
        <?php else: ?>
            <!-- Меню для гостей -->
            <li class="menu-divider">|</li>
            <li class="mobile-nav-item">
                <a href="/account/login.php" class="mobile-nav-link">
                    <i class="fas fa-sign-in-alt"></i> Вход
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="/account/register.php" class="mobile-nav-link">
                    <i class="fas fa-user-plus"></i> Регистрация
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>

<!-- Оверлей для мобильного меню -->
<div class="menu-overlay"></div>

<main class="container">
   

<script>
$(document).ready(function() {
    const burger = $('.burger');
    const mobileMenu = $('.mobile-menu');
    const menuOverlay = $('.menu-overlay');
    const dropdownToggles = $('.dropdown-toggle');
    
    // Открытие/закрытие мобильного меню
    burger.on('click', function() {
        $(this).toggleClass('active');
        mobileMenu.toggleClass('active');
        menuOverlay.toggleClass('active');
        $('body').css('overflow', mobileMenu.hasClass('active') ? 'hidden' : '');
    });
    
    // Закрытие меню при клике на оверлей
    menuOverlay.on('click', function() {
        burger.removeClass('active');
        mobileMenu.removeClass('active');
        $(this).removeClass('active');
        $('body').css('overflow', '');
    });
    
    // Работа выпадающих меню в мобильной версии
    dropdownToggles.on('click', function(e) {
        e.preventDefault();
        $(this).toggleClass('active');
        $(this).next('.mobile-dropdown').toggleClass('active');
        
        // Закрытие других открытых выпадающих меню
        dropdownToggles.not(this).removeClass('active');
        dropdownToggles.not(this).next('.mobile-dropdown').removeClass('active');
    });
    
    // Закрытие меню при клике на пункт (кроме выпадающих)
    $(document).on('click', '.mobile-nav-link:not(.dropdown-toggle)', function() {
        burger.removeClass('active');
        mobileMenu.removeClass('active');
        menuOverlay.removeClass('active');
        $('body').css('overflow', '');
    });
    
    // Адаптация к изменению размера экрана
    $(window).on('resize', function() {
        if ($(window).width() > 992) {
            burger.removeClass('active');
            mobileMenu.removeClass('active');
            menuOverlay.removeClass('active');
            $('body').css('overflow', '');
            
            // Закрытие всех выпадающих меню
            dropdownToggles.removeClass('active');
            $('.mobile-dropdown').removeClass('active');
        }
    });
    
    // Улучшенный обработчик для админ-меню
    const adminMenu = $('.admin-menu');
    if (adminMenu.length) {
        let menuTimeout;
        
        // Для десктопов
        if ($(window).width() > 768) {
            adminMenu.on('mouseenter', function() {
                clearTimeout(menuTimeout);
                $(this).find('.admin-submenu').show();
            });
            
            adminMenu.on('mouseleave', function() {
                const submenu = $(this).find('.admin-submenu');
                menuTimeout = setTimeout(() => {
                    submenu.hide();
                }, 300); // Задержка перед закрытием
            });
            
            // Не даем меню закрыться при наведении на подменю
            const submenu = adminMenu.find('.admin-submenu');
            submenu.on('mouseenter', function() {
                clearTimeout(menuTimeout);
            });
            
            submenu.on('mouseleave', function() {
                menuTimeout = setTimeout(() => {
                    $(this).hide();
                }, 200);
            });
        }
    }
});
</script>
</body>
</html>