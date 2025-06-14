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
$pass_id = intval($_GET['id'] ?? 0);

// Получение данных о ски-пассе
$query = "SELECT * FROM ski_passes WHERE id = $pass_id";
$result = mysqli_query($connection, $query);
$pass = mysqli_fetch_assoc($result);

if (!$pass) {
    header('Location: lifts.php');
    exit;
}

// Обработка обновления данных
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_pass'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price_per_day = floatval($_POST['price_per_day']);
    $duration = intval($_POST['duration']);
    $benefits = trim($_POST['benefits']);
    
    // Валидация данных
    if (empty($name) || empty($description)) {
        $error = 'Название и описание обязательны для заполнения';
    } elseif ($price_per_day <= 0) {
        $error = 'Цена должна быть положительным числом';
    } elseif ($duration <= 0) {
        $error = 'Длительность должна быть положительным числом';
    }

    if (empty($error)) {
        $query = "UPDATE ski_passes SET 
                 name = ?, 
                 description = ?, 
                 price_per_day = ?, 
                 duration = ?, 
                 benefits = ? 
                 WHERE id = ?";
        
        $stmt = mysqli_prepare($connection, $query);
        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt, 
                'ssdisi', 
                $name, $description, $price_per_day, $duration, $benefits, $pass_id
            );
            
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Ски-пасс успешно обновлен!';
                // Обновляем данные для отображения
                $pass = array_merge($pass, [
                    'name' => $name,
                    'description' => $description,
                    'price_per_day' => $price_per_day,
                    'duration' => $duration,
                    'benefits' => $benefits
                ]);
            } else {
                $error = 'Ошибка при обновлении ски-пасса: ' . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Ошибка подготовки запроса: ' . mysqli_error($connection);
        }
    }
}

// Обработка удаления ски-пасса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_pass'])) {
    $query = "DELETE FROM ski_passes WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $pass_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_message'] = 'Ски-пасс успешно удален!';
            header('Location: /admin/lifts.php');
            exit;
        } else {
            $error = 'Ошибка при удалении ски-пасса: ' . mysqli_error($connection);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = 'Ошибка подготовки запроса на удаление: ' . mysqli_error($connection);
    }
}

require_once '../includes/header.php';
?>
<div class="admin-container">
    <h2>Редактирование ски-пасса</h2>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <div class="admin-actions">
        <a href="/admin/lifts.php" class="btn btn-back">Назад к списку</a>
    </div>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($pass['name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="description">Описание:</label>
            <textarea id="description" name="description" rows="3" required><?php echo htmlspecialchars($pass['description']); ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="price_per_day">Цена (руб.):</label>
                <input type="number" id="price_per_day" name="price_per_day" min="0" step="0.01" 
                       value="<?php echo htmlspecialchars($pass['price_per_day']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="duration">Длительность (дней):</label>
                <input type="number" id="duration" name="duration" min="1" 
                       value="<?php echo htmlspecialchars($pass['duration']); ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="benefits">Преимущества:</label>
            <textarea id="benefits" name="benefits" rows="2"><?php echo htmlspecialchars($pass['benefits']); ?></textarea>
            <small>Укажите преимущества через запятую</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" name="update_pass" class="btn btn-primary">Сохранить изменения</button>
            <button type="button" id="delete-btn" class="btn btn-danger">Удалить ски-пасс</button>
        </div>
    </form>
    
    <!-- Скрытая форма для удаления -->
    <form id="delete-form" method="POST" action="" style="display: none;">
        <input type="hidden" name="delete_pass" value="1">
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteBtn = document.getElementById('delete-btn');
    const deleteForm = document.getElementById('delete-form');
    
    deleteBtn.addEventListener('click', function() {
        if (confirm('Вы уверены, что хотите удалить этот ски-пасс? Это действие нельзя отменить.')) {
            deleteForm.submit();
        }
    });
});
</script>

<style>
.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.btn-primary {
    background-color: #28a745;
}

.btn-danger {
    background-color: #dc3545;
}

.form-row {
    display: flex;
    gap: 15px;
}

.form-row .form-group {
    flex: 1;
}

.alert {
    padding: 10px 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
}

.admin-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

.form-group textarea {
    min-height: 100px;
}

.btn {
    padding: 8px 15px;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.btn-back {
    background-color: #6c757d;
    margin-bottom: 20px;
}
</style>

<?php require_once '../includes/footer.php'; ?>