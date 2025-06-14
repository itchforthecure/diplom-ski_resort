<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Проверка прав администратора
if (!isLoggedIn() || !isAdmin()) {
    header('Location: /account/login.php');
    exit;
}

$error = '';
$success = '';

// Обработка добавления нового ски-пасса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_pass'])) {
    // Валидация и очистка данных
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price_per_day = floatval($_POST['price_per_day']);
    $duration = intval($_POST['duration']);
    $benefits = trim($_POST['benefits']);
    
    // Проверка обязательных полей
    if (empty($name) || empty($description)) {
        $error = 'Название и описание обязательны для заполнения';
    } elseif ($price_per_day <= 0) {
        $error = 'Цена должна быть положительным числом';
    } elseif ($duration <= 0) {
        $error = 'Длительность должна быть положительным числом';
    }

    // Если ошибок нет, добавляем в базу данных
    if (empty($error)) {
        $query = "INSERT INTO ski_passes (name, description, price_per_day, duration, benefits) 
                 VALUES (?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($connection, $query);
        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt, 
                'ssdis', 
                $name, $description, $price_per_day, $duration, $benefits
            );
            
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Ски-пасс успешно добавлен!';
                $_POST = array(); // Очищаем поля формы
            } else {
                $error = 'Ошибка при добавлении ски-пасса: ' . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Ошибка подготовки запроса: ' . mysqli_error($connection);
        }
    }
}

// Обработка удаления ски-пасса
if (isset($_GET['delete'])) {
    $pass_id = intval($_GET['delete']);
    
    // Проверяем, есть ли активные бронирования
    $check_query = "SELECT id FROM lift_passes WHERE ski_pass_id = $pass_id AND status != 'cancelled'";
    $check_result = mysqli_query($connection, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error = 'Нельзя удалить ски-пасс с активными бронированиями';
    } else {
        $delete_query = "DELETE FROM ski_passes WHERE id = $pass_id";
        if (mysqli_query($connection, $delete_query)) {
            $success = 'Ски-пасс успешно удален';
        } else {
            $error = 'Ошибка при удалении ски-пасса: ' . mysqli_error($connection);
        }
    }
}

// Получение списка ски-пассов
$query = "SELECT * FROM ski_passes ORDER BY duration, price_per_day";
$result = mysqli_query($connection, $query);
$passes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $passes[] = $row;
}

require_once '../includes/header.php';
?>

<div class="admin-container">
    <h2>Управление ски-пассами</h2>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <div class="admin-actions">
        <a href="index.php" class="btn btn-back">Назад в админ-панель</a>
        <button id="show-form-btn" class="btn">Добавить ски-пасс</button>
    </div>
    
    <div id="add-pass-form" style="display: none;">
        <h3>Добавить новый ски-пасс</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Название:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea id="description" name="description" rows="3" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="price_per_day">Цена (руб.):</label>
                    <input type="number" id="price_per_day" name="price_per_day" min="0" step="0.01" value="<?php echo htmlspecialchars($_POST['price_per_day'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="duration">Длительность (дней):</label>
                    <input type="number" id="duration" name="duration" min="1" value="<?php echo htmlspecialchars($_POST['duration'] ?? '1'); ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="benefits">Преимущества:</label>
                <textarea id="benefits" name="benefits" rows="2"><?php echo htmlspecialchars($_POST['benefits'] ?? ''); ?></textarea>
                <small>Укажите преимущества через запятую (например: "Быстрый подъем, Скидки в кафе")</small>
            </div>
            
            <button type="submit" name="add_pass" class="btn">Добавить</button>
        </form>
    </div>
    
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Длительность</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($passes as $pass): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pass['id']); ?></td>
                        <td><?php echo htmlspecialchars($pass['name']); ?></td>
                        <td><?php echo number_format($pass['price_per_day'], 2); ?> руб.</td>
                        <td><?php echo htmlspecialchars($pass['duration']); ?> дн.</td>
                        <td>
                            <a href="/api/edit_pass.php?id=<?php echo htmlspecialchars($pass['id']); ?>" class="btn btn-sm">Редактировать</a>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.admin-container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.admin-actions {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.btn {
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
    background: #2a6496;
    color: white;
    border: none;
}

.btn:hover {
    background: #1d4b7b;
}

.btn-back {
    background: #6c757d;
}

.btn-back:hover {
    background: #5a6268;
}

#add-pass-form {
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.form-row .form-group {
    flex: 1;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-group textarea {
    min-height: 100px;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th, .admin-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

.admin-table th {
    background-color: #f8f9fa;
    font-weight: 500;
}

.admin-table tr:hover {
    background-color: #f8f9fa;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 0.9em;
}

.btn-danger {
    background: #dc3545;
}

.btn-danger:hover {
    background: #c82333;
}

.alert {
    padding: 10px 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    
    .admin-table {
        display: block;
        overflow-x: auto;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const showFormBtn = document.getElementById('show-form-btn');
    const addForm = document.getElementById('add-pass-form');
    
    showFormBtn.addEventListener('click', function() {
        addForm.style.display = addForm.style.display === 'none' ? 'block' : 'none';
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>