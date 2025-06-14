<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: /account/login.php');
    exit;
}

// Обработка добавления нового инструктора
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_instructor'])) {
    $name = sanitize($_POST['name']);
    $specialization = sanitize($_POST['specialization']);
    $experience = intval($_POST['experience']);
    $bio = sanitize($_POST['bio']);
    $price_per_hour = floatval($_POST['price_per_hour']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Обработка загрузки изображения
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/instructors/';
        $file_name = basename($_FILES['image']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $new_name = uniqid('instructor_', true) . '.' . $file_ext;
            $upload_path = $upload_dir . $new_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image = $new_name;
            }
        }
    }
    
    $query = "INSERT INTO instructors (name, specialization, experience, bio, image, is_active, price_per_hour) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'ssissid', $name, $specialization, $experience, $bio, $image, $is_active, $price_per_hour);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = 'Инструктор успешно добавлен';
        header('Location: instructors.php');
        exit;
    } else {
        $error = 'Ошибка при добавлении инструктора: ' . mysqli_error($connection);
    }
    mysqli_stmt_close($stmt);
}

// Обработка обновления статуса инструктора
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $instructor_id = intval($_POST['instructor_id']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $query = "UPDATE instructors SET is_active = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $is_active, $instructor_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = 'Статус инструктора обновлен';
        header('Location: instructors.php');
        exit;
    } else {
        $error = 'Ошибка при обновлении статуса: ' . mysqli_error($connection);
    }
    mysqli_stmt_close($stmt);
}

// Получение параметров фильтрации
$status_filter = $_GET['status'] ?? 'all';
$specialization_filter = $_GET['specialization'] ?? 'all';
$price_filter = $_GET['price'] ?? 'all';

// Формирование SQL запроса с учетом фильтров
$query = "SELECT * FROM instructors WHERE 1=1";
$params = [];
$types = '';

if ($status_filter !== 'all') {
    $query .= " AND is_active = ?";
    $params[] = ($status_filter === 'active' ? 1 : 0);
    $types .= 'i';
}

if ($specialization_filter !== 'all') {
    $query .= " AND specialization = ?";
    $params[] = $specialization_filter;
    $types .= 's';
}

if ($price_filter !== 'all') {
    if ($price_filter === '1000-2000') {
        $query .= " AND price_per_hour BETWEEN ? AND ?";
        $params[] = 1000;
        $params[] = 2000;
        $types .= 'dd';
    } elseif ($price_filter === '2000-3000') {
        $query .= " AND price_per_hour BETWEEN ? AND ?";
        $params[] = 2000;
        $params[] = 3000;
        $types .= 'dd';
    } elseif ($price_filter === '3000+') {
        $query .= " AND price_per_hour >= ?";
        $params[] = 3000;
        $types .= 'd';
    }
}

$query .= " ORDER BY name";

$stmt = mysqli_prepare($connection, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$instructors = [];
while ($row = mysqli_fetch_assoc($result)) {
    $instructors[] = $row;
}
mysqli_stmt_close($stmt);

require_once '../includes/header.php';
?>

<div class="admin-container">
    <h2>Управление инструкторами</h2>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="admin-actions">
        <a href="index.php" class="btn btn-back">Назад в админ-панель</a>
        <button id="show-form-btn" class="btn">Добавить инструктора</button>
    </div>

    <!-- Фильтры -->
    <div class="admin-filters">
        <form method="GET" action="">
            <div class="filter-group">
                <label for="status">Статус:</label>
                <select id="status" name="status" onchange="this.form.submit()">
                    <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>Все</option>
                    <option value="active" <?= $status_filter === 'active' ? 'selected' : '' ?>>Активные</option>
                    <option value="inactive" <?= $status_filter === 'inactive' ? 'selected' : '' ?>>Неактивные</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="specialization">Специализация:</label>
                <select id="specialization" name="specialization" onchange="this.form.submit()">
                    <option value="all" <?= $specialization_filter === 'all' ? 'selected' : '' ?>>Все</option>
                    <option value="ski" <?= $specialization_filter === 'ski' ? 'selected' : '' ?>>Лыжи</option>
                    <option value="snowboard" <?= $specialization_filter === 'snowboard' ? 'selected' : '' ?>>Сноуборд</option>
                    <option value="freestyle" <?= $specialization_filter === 'freestyle' ? 'selected' : '' ?>>Фристайл</option>
                    <option value="children" <?= $specialization_filter === 'children' ? 'selected' : '' ?>>Детские</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="price">Цена:</label>
                <select id="price" name="price" onchange="this.form.submit()">
                    <option value="all" <?= $price_filter === 'all' ? 'selected' : '' ?>>Любая</option>
                    <option value="1000-2000" <?= $price_filter === '1000-2000' ? 'selected' : '' ?>>1 000 - 2 000 ₽</option>
                    <option value="2000-3000" <?= $price_filter === '2000-3000' ? 'selected' : '' ?>>2 000 - 3 000 ₽</option>
                    <option value="3000+" <?= $price_filter === '3000+' ? 'selected' : '' ?>>От 3 000 ₽</option>
                </select>
            </div>
        </form>
    </div>
    
    <div id="add-instructor-form" style="display: none;">
        <h3>Добавить нового инструктора</h3>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Имя:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="specialization">Специализация:</label>
                <select id="specialization" name="specialization" required>
                    <option value="ski">Лыжи</option>
                    <option value="snowboard">Сноуборд</option>
                    <option value="freestyle">Фристайл</option>
                    <option value="children">Детские</option>
                </select>
            </div>
            <div class="form-group">
                <label for="experience">Опыт (лет):</label>
                <input type="number" id="experience" name="experience" min="0" required>
            </div>
            <div class="form-group">
                <label for="price_per_hour">Цена за час (₽):</label>
                <input type="number" id="price_per_hour" name="price_per_hour" min="0" step="100" value="1500" required>
            </div>
            <div class="form-group">
                <label for="bio">Биография:</label>
                <textarea id="bio" name="bio" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Фото:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" checked> Активен
                </label>
            </div>
            <button type="submit" name="add_instructor" class="btn">Добавить</button>
        </form>
    </div>
    
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Фото</th>
                    <th>Имя</th>
                    <th>Специализация</th>
                    <th>Опыт</th>
                    <th>Цена/час</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($instructors as $instructor): ?>
                    <tr>
                        <td><?= $instructor['id'] ?></td>
                        <td>
                            <?php if ($instructor['image']): ?>
                                <img src="/assets/images/instructors/<?= $instructor['image'] ?>" alt="<?= $instructor['name'] ?>" width="50">
                            <?php else: ?>
                                <i class="fas fa-user-circle fa-2x"></i>
                            <?php endif; ?>
                        </td>
                        <td><?= $instructor['name'] ?></td>
                        <td><?= getSpecializationName($instructor['specialization']) ?></td>
                        <td><?= $instructor['experience'] ?> лет</td>
                        <td><?= number_format($instructor['price_per_hour'], 0, '', ' ') ?> ₽</td>
                        <td>
                            <form method="POST" action="" class="status-form">
                                <input type="hidden" name="instructor_id" value="<?= $instructor['id'] ?>">
                                <label class="switch">
                                    <input type="checkbox" name="is_active" <?= $instructor['is_active'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                    <span class="slider round"></span>
                                </label>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td>
                            <a href="/api/edit_instructor.php?id=<?= $instructor['id'] ?>" class="btn btn-sm">Редактировать</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* Стили остаются без изменений */
.admin-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.alert {
    padding: 10px 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}

.admin-actions {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.btn {
    padding: 8px 15px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.btn-back {
    background-color: #6c757d;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 14px;
}

.admin-filters {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.filter-group {
    margin-bottom: 10px;
    display: inline-block;
    margin-right: 15px;
}

.filter-group label {
    margin-right: 8px;
}

.filter-group select {
    padding: 6px 10px;
    border-radius: 4px;
    border: 1px solid #ced4da;
}

#add-instructor-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

.form-group textarea {
    min-height: 100px;
}

.admin-table-container {
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th,
.admin-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.admin-table th {
    background-color: #f8f9fa;
    font-weight: bold;
}

.admin-table tr:hover {
    background-color: #f8f9fa;
}

.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
}

input:checked + .slider {
    background-color: #28a745;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.slider.round {
    border-radius: 24px;
}

.slider.round:before {
    border-radius: 50%;
}

.fa-user-circle {
    color: #6c757d;
}

@media (max-width: 768px) {
    .admin-actions {
        flex-direction: column;
        gap: 10px;
    }
    
    .filter-group {
        display: block;
        margin-right: 0;
        margin-bottom: 10px;
    }
    
    .admin-table th,
    .admin-table td {
        padding: 8px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const showFormBtn = document.getElementById('show-form-btn');
    const addForm = document.getElementById('add-instructor-form');
    
    showFormBtn.addEventListener('click', function() {
        addForm.style.display = addForm.style.display === 'none' ? 'block' : 'none';
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>