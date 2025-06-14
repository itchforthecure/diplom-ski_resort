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
$activity = null;

// Получение ID активности из URL
$activity_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Получение данных активности
if ($activity_id > 0) {
    $query = "SELECT * FROM activities WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $activity_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $activity = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if (!$activity) {
        $error = 'Активность не найдена';
    }
} else {
    $error = 'Неверный ID активности';
}

// Обработка формы редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_activity']) && $activity) {
    // Валидация и очистка данных
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
    
    // Обработка загрузки нового изображения
    $image = $activity['image'];
    if (empty($error) && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/activities/';
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
            // Удаляем старое изображение, если оно существует
            if (!empty($image)) {
                $old_image_path = $upload_dir . $image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            $image = $new_name;
        } else {
            $error = 'Ошибка при загрузке изображения';
        }
    }
    
    // Обновление данных в базе
    if (empty($error)) {
        $query = "UPDATE activities SET 
                 name = ?, category = ?, short_description = ?, description = ?, 
                 duration = ?, price = ?, min_people = ?, max_people = ?, 
                 min_age = ?, season = ?, image = ?
                 WHERE id = ?";
        
        $stmt = mysqli_prepare($connection, $query);
        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt, 
                'ssssidiiissi', 
                $name, $category, $short_description, $description, $duration, 
                $price, $min_people, $max_people, $min_age, $season, $image,
                $activity_id
            );
            
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Активность успешно обновлена!';
                // Обновляем данные активности для отображения
                $activity = array_merge($activity, [
                    'name' => $name,
                    'category' => $category,
                    'short_description' => $short_description,
                    'description' => $description,
                    'duration' => $duration,
                    'price' => $price,
                    'min_people' => $min_people,
                    'max_people' => $max_people,
                    'min_age' => $min_age,
                    'season' => $season,
                    'image' => $image
                ]);
            } else {
                $error = 'Ошибка при обновлении активности: ' . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Ошибка подготовки запроса: ' . mysqli_error($connection);
        }
    }
}

require_once '../includes/header.php';
?>

<div class="admin-container">
    <h2>Редактирование активности</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <div class="admin-actions">
        <a href="activities.php" class="btn btn-back">Назад к списку</a>
    </div>
    
    <?php if ($activity): ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Название:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($activity['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="category">Категория:</label>
                    <select id="category" name="category" required>
                        <option value="winter" <?= $activity['category'] === 'winter' ? 'selected' : '' ?>>Зимние</option>
                        <option value="summer" <?= $activity['category'] === 'summer' ? 'selected' : '' ?>>Летние</option>
                        <option value="family" <?= $activity['category'] === 'family' ? 'selected' : '' ?>>Семейные</option>
                        <option value="extreme" <?= $activity['category'] === 'extreme' ? 'selected' : '' ?>>Экстрим</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="short_description">Краткое описание:</label>
                <textarea id="short_description" name="short_description" rows="2" required><?php echo htmlspecialchars($activity['short_description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="description">Полное описание:</label>
                <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($activity['description']); ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="duration">Продолжительность (мин):</label>
                    <input type="number" id="duration" name="duration" min="15" value="<?php echo htmlspecialchars($activity['duration']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="price">Цена (руб):</label>
                    <input type="number" id="price" name="price" min="0" step="0.01" value="<?php echo htmlspecialchars($activity['price']); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="min_people">Мин. участников:</label>
                    <input type="number" id="min_people" name="min_people" min="1" value="<?php echo htmlspecialchars($activity['min_people']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="max_people">Макс. участников:</label>
                    <input type="number" id="max_people" name="max_people" min="1" value="<?php echo htmlspecialchars($activity['max_people']); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="min_age">Мин. возраст:</label>
                    <input type="number" id="min_age" name="min_age" min="0" value="<?php echo htmlspecialchars($activity['min_age']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="season">Сезон:</label>
                    <select id="season" name="season" required>
                        <option value="winter" <?= $activity['season'] === 'winter' ? 'selected' : '' ?>>Зима</option>
                        <option value="summer" <?= $activity['season'] === 'summer' ? 'selected' : '' ?>>Лето</option>
                        <option value="all" <?= $activity['season'] === 'all' ? 'selected' : '' ?>>Круглый год</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Изображение:</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif">
                <small>Оставьте пустым, чтобы сохранить текущее изображение</small>
                <?php if (!empty($activity['image'])): ?>
                    <div class="current-image">
                        <p>Текущее изображение:</p>
                        <img src="/assets/images/activities/<?php echo htmlspecialchars($activity['image']); ?>" alt="<?php echo htmlspecialchars($activity['name']); ?>" width="200">
                    </div>
                <?php endif; ?>
            </div>
            
            <button type="submit" name="update_activity" class="btn">Обновить активность</button>
        </form>
    <?php endif; ?>
</div>

<style>
.current-image {
    margin-top: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
    display: inline-block;
}

.current-image p {
    margin-bottom: 5px;
    font-size: 0.9em;
    color: #6c757d;
}
</style>

<?php require_once '../includes/footer.php'; ?>