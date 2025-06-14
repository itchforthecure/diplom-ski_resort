<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Получение списка трасс
$query = "SELECT * FROM slopes";
$result = mysqli_query($connection, $query);
$slopes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $slopes[] = $row;
}

require_once '../includes/header.php';
?>

<section class="map-section">
    <h2>Карта трасс</h2>
    
    <div class="map-controls">
        <div class="filter-controls">
            <label>Фильтр по сложности:</label>
            <select id="difficulty-filter">
                <option value="all">Все</option>
                <option value="easy">Легкие</option>
                <option value="medium">Средние</option>
                <option value="hard">Сложные</option>
                <option value="expert">Экспертные</option>
            </select>
            
            <label>Сортировка:</label>
            <select id="sort-by">
                <option value="name">По названию (А-Я)</option>
                <option value="name_desc">По названию (Я-А)</option>
                <option value="length">По длине (короткие сначала)</option>
                <option value="length_desc">По длине (длинные сначала)</option>
                <option value="difficulty">По сложности (легкие сначала)</option>
                <option value="difficulty_desc">По сложности (сложные сначала)</option>
            </select>
        </div>
        <div class="search-controls">
            <input type="text" id="slope-search" placeholder="Поиск трасс...">
            <button id="search-btn"><i class="fas fa-search"></i> Найти</button>
            <button id="reset-btn"><i class="fas fa-undo"></i> Сбросить</button>
        </div>
    </div>
    
    <div class="map-container">
        <div class="resort-map" id="resort-map">
            <!-- SVG карта курорта -->
            <svg width="800" height="600" viewBox="0 0 800 600" xmlns="http://www.w3.org/2000/svg">
                <!-- Фон - изображение карты -->
            <image href="https://images3.nplod.ru/gen_images_1/y5Q6VyjYdxZp0teaBdJRsw2uD6tVfddm.jpg" width="100%" height="100%" preserveAspectRatio="xMidYMid meet"/>
                <!-- Трассы -->
                <?php foreach ($slopes as $slope): 
                    $color = '';
                    $difficulty_text = '';
                    switch ($slope['difficulty']) {
                        case 'easy': 
                            $color = '#4caf50'; 
                            $difficulty_text = 'Легкая';
                            break;
                        case 'medium': 
                            $color = '#2196f3'; 
                            $difficulty_text = 'Средняя';
                            break;
                        case 'hard': 
                            $color = '#ff9800'; 
                            $difficulty_text = 'Сложная';
                            break;
                        case 'expert': 
                            $color = '#f44336'; 
                            $difficulty_text = 'Экспертная';
                            break;
                    }
                ?>
                    <path class="slope-path" 
                          data-id="<?php echo $slope['id']; ?>" 
                          data-name="<?php echo htmlspecialchars($slope['name']); ?>" 
                          data-difficulty="<?php echo $slope['difficulty']; ?>"
                          data-difficulty-text="<?php echo $difficulty_text; ?>"
                          data-length="<?php echo $slope['length']; ?>"
                          data-elevation="<?php echo $slope['elevation']; ?>"
                          data-description="<?php echo htmlspecialchars($slope['description']); ?>"
                          d="<?php echo htmlspecialchars($slope['coordinates']); ?>" 
                          stroke="<?php echo $color; ?>" 
                          stroke-width="4" 
                          fill="none" 
                          stroke-linecap="round"
                          title="<?php echo htmlspecialchars($slope['name']); ?>"/>
                <?php endforeach; ?>
        
                
                <!-- Инфраструктура -->
                <rect x="100" y="450" width="80" height="40" fill="#bbdefb" rx="5" class="infra-point" data-type="cafe"/>
                <text x="140" y="475" text-anchor="middle" font-size="12" fill="#0d47a1">Кафе</text>
                
                <rect x="350" y="350" width="80" height="40" fill="#bbdefb" rx="5" class="infra-point" data-type="hotel"/>
                <text x="390" y="375" text-anchor="middle" font-size="12" fill="#0d47a1">Отель</text>
                
                <rect x="510" y="410" width="80" height="40" fill="#bbdefb" rx="5" class="infra-point" data-type="rental"/>
                <text x="548" y="434" text-anchor="middle" font-size="12" fill="#0d47a1">Прокат</text> 
                
                <!-- Точки начала трасс -->
<?php foreach ($slopes as $slope): 
    if (!empty($slope['start_point'])) {
        $coords = explode(',', $slope['start_point']);
        // Проверяем, что получили 2 координаты
        if (count($coords) === 2 && is_numeric($coords[0]) && is_numeric($coords[1])) {
            $x = trim($coords[0]);
            $y = trim($coords[1]);
?>
    <circle cx="<?php echo $x; ?>" cy="<?php echo $y; ?>" r="5" fill="#ffffff" stroke="#000000" stroke-width="1" class="slope-start" data-slope-id="<?php echo $slope['id']; ?>"/>
<?php 
        }
    }
endforeach; ?>
            </svg>
        </div>
        
        <div class="slope-info" id="slope-info">
            <h3>Информация о трассе</h3>
            <div id="no-slope-selected">
                <p>Выберите трассу на карте для просмотра информации</p>
            </div>
            <div id="slope-details" style="display: none;">
                <h4 id="slope-name"></h4>
                <div class="difficulty-badge" id="slope-difficulty-badge"></div>
                <p><strong>Длина:</strong> <span id="slope-length"></span> м</p>
                <p><strong>Перепад высот:</strong> <span id="slope-elevation"></span> м</p>
                <p><strong>Статус:</strong> <span id="slope-status"></span></p>
                <p id="slope-description"></p>
            </div>
            
            <div class="infra-info" id="infra-info" style="display: none;">
                <h4 id="infra-name"></h4>
                <p id="infra-description"></p>
                <div id="infra-hours"></div>
                <div id="infra-contacts"></div>
            </div>
        </div>
    </div>
    
    <div class="slopes-list">
        <h3>Список трасс</h3>
        <div class="table-responsive">
            <table class="table" id="slopes-table">
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Сложность</th>
                        <th>Длина</th>
                        <th>Перепад высот</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($slopes as $slope): 
                        $status_class = '';
                        switch ($slope['status']) {
                            case 'open': $status_class = 'status-open'; break;
                            case 'closed': $status_class = 'status-closed'; break;
                            case 'partial': $status_class = 'status-partial'; break;
                        }
                    ?>
                    <tr class="slope-row" 
                        data-id="<?php echo $slope['id']; ?>"
                        data-name="<?php echo htmlspecialchars($slope['name']); ?>"
                        data-difficulty="<?php echo $slope['difficulty']; ?>"
                        data-length="<?php echo $slope['length']; ?>"
                        data-elevation="<?php echo $slope['elevation']; ?>">
                        <td><?php echo htmlspecialchars($slope['name']); ?></td>
                        <td>
                            <span class="difficulty-badge difficulty-<?php echo $slope['difficulty']; ?>">
                                <?php 
                                    switch ($slope['difficulty']) {
                                        case 'easy': echo 'Легкая'; break;
                                        case 'medium': echo 'Средняя'; break;
                                        case 'hard': echo 'Сложная'; break;
                                        case 'expert': echo 'Экспертная'; break;
                                    }
                                ?>
                            </span>
                        </td>
                        <td><?php echo $slope['length']; ?> м</td>
                        <td><?php echo $slope['elevation']; ?> м</td>
                        <td><span class="status-badge <?php echo $status_class; ?>">
                            <?php 
                                switch ($slope['status']) {
                                    case 'open': echo 'Открыта'; break;
                                    case 'closed': echo 'Закрыта'; break;
                                    case 'partial': echo 'Частично'; break;
                                }
                            ?>
                        </span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slopePaths = document.querySelectorAll('.slope-path');
    const slopeRows = document.querySelectorAll('.slope-row');
    const difficultyFilter = document.getElementById('difficulty-filter');
    const sortBy = document.getElementById('sort-by');
    const searchInput = document.getElementById('slope-search');
    const searchBtn = document.getElementById('search-btn');
    const resetBtn = document.getElementById('reset-btn');
    const slopeDetails = document.getElementById('slope-details');
    const noSlopeSelected = document.getElementById('no-slope-selected');
    const infraInfo = document.getElementById('infra-info');
    const slopesTable = document.getElementById('slopes-table');
    
    // Информация об инфраструктуре
    const infraData = {
        cafe: {
            name: "Горное кафе",
            description: "Уютное кафе с панорамным видом на горы. Предлагаем горячие напитки, выпечку и полноценные обеды.",
            hours: "Ежедневно с 9:00 до 18:00",
            contacts: "Тел: +7 (123) 456-7890"
        },
        hotel: {
            name: "Горный отель",
            description: "Комфортабельный отель у подножия склонов. Номера различных категорий, ресторан, спа-зона.",
            hours: "Круглосуточно",
            contacts: "Тел: +7 (123) 456-7891<br>Email: hotel@resort.com"
        },
        rental: {
            name: "Прокат снаряжения",
            description: "Аренда лыж, сноубордов и другого снаряжения. Профессиональная подгонка оборудования.",
            hours: "Ежедневно с 8:00 до 20:00",
            contacts: "Тел: +7 (123) 456-7892"
        }
    };
    
    // Текущая выбранная трасса
    let selectedSlopeId = null;
    
    // Функция для отображения информации о трассе
    function showSlopeInfo(slopeId) {
        const path = document.querySelector(`.slope-path[data-id="${slopeId}"]`);
        if (!path) return;
        
        selectedSlopeId = slopeId;
        
        // Обновляем информацию
        document.getElementById('slope-name').textContent = path.getAttribute('data-name');
        
        const difficulty = path.getAttribute('data-difficulty');
        const difficultyText = path.getAttribute('data-difficulty-text');
        const difficultyBadge = document.getElementById('slope-difficulty-badge');
        
        difficultyBadge.textContent = difficultyText;
        difficultyBadge.className = 'difficulty-badge difficulty-' + difficulty;
        
        document.getElementById('slope-length').textContent = path.getAttribute('data-length');
        document.getElementById('slope-elevation').textContent = path.getAttribute('data-elevation');
        document.getElementById('slope-description').textContent = path.getAttribute('data-description');
        
        // Получаем статус из таблицы
        const row = document.querySelector(`.slope-row[data-id="${slopeId}"]`);
        if (row) {
            const statusBadge = row.querySelector('.status-badge');
            document.getElementById('slope-status').textContent = statusBadge.textContent;
            document.getElementById('slope-status').className = 'status-' + statusBadge.classList[1];
        }
        
        // Показываем блок с информацией
        slopeDetails.style.display = 'block';
        noSlopeSelected.style.display = 'none';
        infraInfo.style.display = 'none';
        
        // Подсвечиваем выбранную трассу
        slopePaths.forEach(p => {
            p.style.strokeWidth = p.getAttribute('data-id') === slopeId ? '6px' : '4px';
        });
        
        // Подсвечиваем строку в таблице
        slopeRows.forEach(row => {
            if (row.getAttribute('data-id') === slopeId) {
                row.classList.add('table-active');
                row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                row.classList.remove('table-active');
            }
        });
    }
    
    // Функция для отображения информации об инфраструктуре
    function showInfraInfo(infraType) {
        const infra = infraData[infraType];
        if (!infra) return;
        
        document.getElementById('infra-name').textContent = infra.name;
        document.getElementById('infra-description').textContent = infra.description;
        document.getElementById('infra-hours').innerHTML = `<strong>Часы работы:</strong> ${infra.hours}`;
        document.getElementById('infra-contacts').innerHTML = `<strong>Контакты:</strong> ${infra.contacts}`;
        
        infraInfo.style.display = 'block';
        slopeDetails.style.display = 'none';
        noSlopeSelected.style.display = 'none';
    }
    
    // Обработчик клика по трассе
    slopePaths.forEach(path => {
        path.addEventListener('click', function() {
            const slopeId = this.getAttribute('data-id');
            showSlopeInfo(slopeId);
        });
    });
    
    // Обработчик клика по точке инфраструктуры
    document.querySelectorAll('.infra-point').forEach(point => {
        point.addEventListener('click', function(e) {
            e.stopPropagation();
            const infraType = this.getAttribute('data-type');
            showInfraInfo(infraType);
        });
    });
    
    // Обработчик клика по строке таблицы
    slopeRows.forEach(row => {
        row.addEventListener('click', function() {
            const slopeId = this.getAttribute('data-id');
            showSlopeInfo(slopeId);
            
            // Прокручиваем карту к началу трассы
            const startPoint = document.querySelector(`.slope-start[data-slope-id="${slopeId}"]`);
            if (startPoint) {
                const svg = document.querySelector('#resort-map svg');
                const viewBox = svg.getAttribute('viewBox').split(' ');
                const x = parseFloat(startPoint.getAttribute('cx'));
                const y = parseFloat(startPoint.getAttribute('cy'));
                
                // Анимация плавного перемещения
                svg.style.transition = 'viewBox 0.5s ease-in-out';
                svg.setAttribute('viewBox', `${x-200} ${y-150} ${viewBox[2]} ${viewBox[3]}`);
                
                setTimeout(() => {
                    svg.style.transition = '';
                }, 500);
            }
        });
    });
    
    // Фильтрация и сортировка трасс
    function updateSlopes() {
        const filterValue = difficultyFilter.value;
        const searchTerm = searchInput.value.toLowerCase();
        const sortValue = sortBy.value;
        
        // Фильтрация
        slopePaths.forEach(path => {
            const slopeName = path.getAttribute('data-name').toLowerCase();
            const slopeDifficulty = path.getAttribute('data-difficulty');
            
            const matchesFilter = filterValue === 'all' || slopeDifficulty === filterValue;
            const matchesSearch = searchTerm === '' || slopeName.includes(searchTerm);
            
            if (matchesFilter && matchesSearch) {
                path.style.display = 'inline';
            } else {
                path.style.display = 'none';
                if (path.getAttribute('data-id') === selectedSlopeId) {
                    noSlopeSelected.style.display = 'block';
                    slopeDetails.style.display = 'none';
                    selectedSlopeId = null;
                }
            }
        });
        
        // Сортировка таблицы
        const rows = Array.from(slopeRows);
        
        rows.sort((a, b) => {
            const aName = a.getAttribute('data-name').toLowerCase();
            const bName = b.getAttribute('data-name').toLowerCase();
            const aLength = parseInt(a.getAttribute('data-length'));
            const bLength = parseInt(b.getAttribute('data-length'));
            const aDifficulty = a.getAttribute('data-difficulty');
            const bDifficulty = b.getAttribute('data-difficulty');
            const difficultyOrder = ['easy', 'medium', 'hard', 'expert'];
            
            switch (sortValue) {
                case 'name':
                    return aName.localeCompare(bName);
                case 'name_desc':
                    return bName.localeCompare(aName);
                case 'length':
                    return aLength - bLength;
                case 'length_desc':
                    return bLength - aLength;
                case 'difficulty':
                    return difficultyOrder.indexOf(aDifficulty) - difficultyOrder.indexOf(bDifficulty);
                case 'difficulty_desc':
                    return difficultyOrder.indexOf(bDifficulty) - difficultyOrder.indexOf(aDifficulty);
                default:
                    return 0;
            }
        });
        
        // Перестраиваем таблицу
        const tbody = slopesTable.querySelector('tbody');
        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));
    }
    
    // Обработчики событий для фильтров
    difficultyFilter.addEventListener('change', updateSlopes);
    sortBy.addEventListener('change', updateSlopes);
    
    // Поиск трасс
    function searchSlopes() {
        updateSlopes();
        
        // Если есть результаты поиска, подсвечиваем их
        if (searchInput.value !== '') {
            slopePaths.forEach(path => {
                if (path.style.display !== 'none') {
                    path.style.animation = 'highlight 1s';
                    setTimeout(() => {
                        path.style.animation = '';
                    }, 1000);
                }
            });
        }
    }
    
    searchBtn.addEventListener('click', searchSlopes);
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            searchSlopes();
        }
    });
    
    // Сброс фильтров
    resetBtn.addEventListener('click', function() {
        difficultyFilter.value = 'all';
        sortBy.value = 'name';
        searchInput.value = '';
        updateSlopes();
    });
    
    // Инициализация - выделить первую трассу
    if (slopePaths.length > 0) {
        showSlopeInfo(slopePaths[0].getAttribute('data-id'));
    }
    
    // Обработчик для кнопки "В избранное"
    document.getElementById('favorite-btn').addEventListener('click', function() {
        if (!selectedSlopeId) return;
        
        this.innerHTML = '<i class="fas fa-heart"></i> В избранном';
        this.classList.remove('btn-outline-primary');
        this.classList.add('btn-primary');
        
        // Здесь можно добавить AJAX-запрос для сохранения в избранное
    });
    
    // Обработчик для кнопки "Сообщить о проблеме"
    document.getElementById('report-btn').addEventListener('click', function() {
        if (!selectedSlopeId) return;
        
        // Здесь можно реализовать модальное окно для отправки сообщения
        alert(`Сообщить о проблеме на трассе ${selectedSlopeId}`);
    });
});
</script>

<style>
.map-section {
    padding: 20px;
    background-color: #f5f5f5;
    border-radius: 8px;
    margin-bottom: 30px;
}

.map-controls {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: 20px;
    padding: 15px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.filter-controls, .search-controls {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.search-controls input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    min-width: 250px;
}

.map-container {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}

.resort-map {
    flex: 2;
    background-color: #fff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.slope-info {
    flex: 1;
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    min-height: 300px;
}

.slopes-list {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.difficulty-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.9em;
    font-weight: bold;
    color: white;
    margin-bottom: 10px;
}

.difficulty-easy {
    background-color: #4caf50;
}

.difficulty-medium {
    background-color: #2196f3;
}

.difficulty-hard {
    background-color: #ff9800;
}

.difficulty-expert {
    background-color: #f44336;
}

.status-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.9em;
    font-weight: bold;
}

.status-open {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.status-closed {
    background-color: #ffebee;
    color: #c62828;
}

.status-partial {
    background-color: #fff8e1;
    color: #f57f17;
}

.slope-path {
    transition: stroke-width 0.2s;
}

.slope-path:hover {
    stroke-width: 6px !important;
    cursor: pointer;
}

.infra-point:hover {
    stroke: #0d47a1;
    stroke-width: 2px;
    cursor: pointer;
}

.slope-start:hover {
    stroke-width: 2px;
    cursor: pointer;
}

.table-active {
    background-color: #e3f2fd !important;
}

.slope-actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
}

@keyframes highlight {
    0% { stroke-width: 4px; opacity: 1; }
    50% { stroke-width: 8px; opacity: 0.8; }
    100% { stroke-width: 4px; opacity: 1; }
}

@media (max-width: 992px) {
    .map-container {
        flex-direction: column;
    }
    
    .resort-map, .slope-info {
        flex: auto;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>