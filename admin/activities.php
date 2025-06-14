<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Проверка авторизации и прав администратора
if (!isLoggedIn() || !isAdmin()) {
    header('Location: /account/login.php');
    exit;
}

$error = '';
$success = '';

// Обработка добавления новой активности
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_activity'])) {
    // Валидация и очистка входных данных
    $name = trim($_POST['name']);
    $category = $_POST['category'];
    $short_description = trim($_POST['short_description']);
    $description = trim($_POST['description']);
    $duration = intval($_POST['duration']);
    $price = floatval($_POST['price']);
    $min_people = intval($_POST['min_people']);
    $max_people = intval($_POST['max_people']);
    $min_age = intval($_POST['min_age']);
    $season = $_POST['season'];

    // Проверка обязательных полей
    if (empty($name) || empty($short_description) || empty($description)) {
        $error = 'Все текстовые поля обязательны для заполнения';
    } elseif ($min_people > $max_people) {
        $error = 'Минимальное количество участников не может быть больше максимального';
    } elseif (!in_array($category, ['winter', 'summer', 'family', 'extreme'])) {
        $error = 'Неверная категория активности';
    } elseif (!in_array($season, ['winter', 'summer', 'all'])) {
        $error = 'Неверный сезон';
    }

    // Обработка загрузки изображения
    $image = '';
    if (empty($error) && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/activities/';
        
        // Создаем директорию, если не существует
        if (!file_exists($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                $error = 'Не удалось создать директорию для изображений';
            }
        }

        if (empty($error)) {
            $file_name = basename($_FILES['image']['name']);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            $new_name = uniqid('activity_', true) . '.' . $file_ext;
            $upload_path = $upload_dir . $new_name;
            
            // Проверка расширения файла
            if (!in_array($file_ext, $allowed_ext)) {
                $error = 'Допустимые форматы изображений: JPG, PNG, GIF';
            }
            // Проверка размера файла (макс. 5MB)
            elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                $error = 'Файл слишком большой (максимум 5MB)';
            }
            // Попытка загрузки
            elseif (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image = $new_name;
            } else {
                $error = 'Ошибка при загрузке изображения';
            }
        }
    } elseif (empty($error)) {
        $error = 'Необходимо загрузить изображение';
    }

    // Если ошибок нет, добавляем в базу данных
    if (empty($error)) {
        // Используем подготовленные выражения для безопасности
        $query = "INSERT INTO activities 
                 (name, category, short_description, description, duration, price, 
                  min_people, max_people, min_age, season, image) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($connection, $query);
        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt, 
                'ssssidiiiss', 
                $name, $category, $short_description, $description, $duration, 
                $price, $min_people, $max_people, $min_age, $season, $image
            );
            
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Активность успешно добавлена!';
                $_POST = array(); // Очищаем поля формы
            } else {
                // Удаляем загруженное изображение при ошибке
                if (!empty($image) && file_exists($upload_path)) {
                    unlink($upload_path);
                }
                $error = 'Ошибка при добавлении активности: ' . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Ошибка подготовки запроса: ' . mysqli_error($connection);
        }
    }
}

// Получение параметров фильтрации
$filter_category = isset($_GET['category']) ? $_GET['category'] : '';
$filter_season = isset($_GET['season']) ? $_GET['season'] : '';

// Базовый запрос с подготовленными выражениями
$query = "SELECT * FROM activities WHERE 1=1";
$params = [];
$types = '';

if (!empty($filter_category)) {
    $query .= " AND category = ?";
    $params[] = $filter_category;
    $types .= 's';
}

if (!empty($filter_season)) {
    $query .= " AND season = ?";
    $params[] = $filter_season;
    $types .= 's';
}

// Сортировка
$query .= " ORDER BY name";

// Подготовка и выполнение запроса
$stmt = mysqli_prepare($connection, $query);
if ($stmt) {
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $activities = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $activities[] = $row;
    }
    
    mysqli_stmt_close($stmt);
} else {
    $error = 'Ошибка подготовки запроса: ' . mysqli_error($connection);
}

require_once '../includes/header.php';
?>

<div class="admin-container">
    <h2>Управление активностями</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <div class="admin-actions">
        <a href="index.php" class="btn btn-back">Назад в админ-панель</a>
        <button id="show-form-btn" class="btn">Добавить активность</button>
    </div>
    
    <div id="add-activity-form" style="display: none;">
        <h3>Добавить новую активность</h3>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Название:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="category">Категория:</label>
                    <select id="category" name="category" required>
                        <option value="winter" <?php echo ($_POST['category'] ?? '') === 'winter' ? 'selected' : ''; ?>>Зимние</option>
                        <option value="summer" <?php echo ($_POST['category'] ?? '') === 'summer' ? 'selected' : ''; ?>>Летние</option>
                        <option value="family" <?php echo ($_POST['category'] ?? '') === 'family' ? 'selected' : ''; ?>>Семейные</option>
                        <option value="extreme" <?php echo ($_POST['category'] ?? '') === 'extreme' ? 'selected' : ''; ?>>Экстрим</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="short_description">Краткое описание:</label>
                <textarea id="short_description" name="short_description" rows="2" required><?php echo htmlspecialchars($_POST['short_description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="description">Полное описание:</label>
                <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="duration">Продолжительность (мин):</label>
                    <input type="number" id="duration" name="duration" min="15" value="<?php echo htmlspecialchars($_POST['duration'] ?? 60); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="price">Цена (руб):</label>
                    <input type="number" id="price" name="price" min="0" step="0.01" value="<?php echo htmlspecialchars($_POST['price'] ?? 1000); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="min_people">Мин. участников:</label>
                    <input type="number" id="min_people" name="min_people" min="1" value="<?php echo htmlspecialchars($_POST['min_people'] ?? 1); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="max_people">Макс. участников:</label>
                    <input type="number" id="max_people" name="max_people" min="1" value="<?php echo htmlspecialchars($_POST['max_people'] ?? 10); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="min_age">Мин. возраст:</label>
                    <input type="number" id="min_age" name="min_age" min="0" value="<?php echo htmlspecialchars($_POST['min_age'] ?? 6); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="season">Сезон:</label>
                    <select id="season" name="season" required>
                        <option value="winter" <?php echo ($_POST['season'] ?? '') === 'winter' ? 'selected' : ''; ?>>Зима</option>
                        <option value="summer" <?php echo ($_POST['season'] ?? '') === 'summer' ? 'selected' : ''; ?>>Лето</option>
                        <option value="all" <?php echo ($_POST['season'] ?? '') === 'all' ? 'selected' : ''; ?>>Круглый год</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Изображение:</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif" required>
                <small>Рекомендуемый размер: 800x600px, формат JPG/PNG (макс. 5MB)</small>
            </div>
            
            <button type="submit" name="add_activity" class="btn">Добавить активность</button>
        </form>
    </div>
    
    <div class="admin-table-container">
        <h3>Список активностей</h3>
        
        <!-- Форма фильтрации -->
        <div class="filter-form">
            <form method="GET" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="filter_category">Категория:</label>
                        <select id="filter_category" name="category" class="form-control">
                            <option value="">Все категории</option>
                            <option value="winter" <?= isset($_GET['category']) && $_GET['category'] == 'winter' ? 'selected' : '' ?>>Зимние</option>
                            <option value="summer" <?= isset($_GET['category']) && $_GET['category'] == 'summer' ? 'selected' : '' ?>>Летние</option>
                            <option value="family" <?= isset($_GET['category']) && $_GET['category'] == 'family' ? 'selected' : '' ?>>Семейные</option>
                            <option value="extreme" <?= isset($_GET['category']) && $_GET['category'] == 'extreme' ? 'selected' : '' ?>>Экстрим</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="filter_season">Сезон:</label>
                        <select id="filter_season" name="season" class="form-control">
                            <option value="">Все сезоны</option>
                            <option value="winter" <?= isset($_GET['season']) && $_GET['season'] == 'winter' ? 'selected' : '' ?>>Зима</option>
                            <option value="summer" <?= isset($_GET['season']) && $_GET['season'] == 'summer' ? 'selected' : '' ?>>Лето</option>
                            <option value="all" <?= isset($_GET['season']) && $_GET['season'] == 'all' ? 'selected' : '' ?>>Круглый год</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-filter">Фильтровать</button>
                        <a href="activities.php" class="btn btn-reset">Сбросить</a>
                    </div>
                </div>
            </form>
        </div>
        
        <?php if (!empty($activities)): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Изображение</th>
                        <th>Название</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($activity['id']); ?></td>
                            <td>
                                <?php if (!empty($activity['image'])): ?>
                                    <img src="/assets/images/activities/<?php echo htmlspecialchars($activity['image']); ?>" alt="<?php echo htmlspecialchars($activity['name']); ?>" width="60">
                                <?php else: ?>
                                    <span class="no-image">Нет изображения</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($activity['name']); ?></td>
                            <td>
                                <?php 
                                switch ($activity['category']) {
                                    case 'winter': echo 'Зимние'; break;
                                    case 'summer': echo 'Летние'; break;
                                    case 'family': echo 'Семейные'; break;
                                    case 'extreme': echo 'Экстрим'; break;
                                    default: echo htmlspecialchars($activity['category']);
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($activity['price']); ?> руб.</td>
                            <td>
                                <a href="/api/edit_activity.php?id=<?php echo htmlspecialchars($activity['id']); ?>" class="btn btn-sm">Редактировать</a>
                                <a href="/api/delete_activity.php?id=<?php echo htmlspecialchars($activity['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить эту активность?')">Удалить</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Нет доступных активностей</p>
        <?php endif; ?>
    </div>
</div>

<style>
/* Стили для формы фильтрации */
.filter-form {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.filter-form .form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: flex-end;
}

.filter-form .form-group {
    margin-bottom: 0;
}

.btn-filter {
    background-color: #3498db;
    color: white;
}

.btn-reset {
    background-color: #6c757d;
    color: white;
}

.no-image {
    color: #6c757d;
    font-style: italic;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const showFormBtn = document.getElementById('show-form-btn');
    const addForm = document.getElementById('add-activity-form');
    
    showFormBtn.addEventListener('click', function() {
        addForm.style.display = addForm.style.display === 'none' ? 'block' : 'none';
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>