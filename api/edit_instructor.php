<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: /account/login.php');
    exit;
}

// Получаем ID инструктора из параметра URL
$instructor_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($instructor_id <= 0) {
    header('Location: instructors.php');
    exit;
}

// Получаем данные инструктора
$query = "SELECT * FROM instructors WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 'i', $instructor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$instructor = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$instructor) {
    $_SESSION['error_message'] = 'Инструктор не найден';
    header('Location: instructors.php');
    exit;
}

// Обработка обновления данных инструктора
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_instructor'])) {
    $name = sanitize($_POST['name']);
    $specialization = sanitize($_POST['specialization']);
    $experience = intval($_POST['experience']);
    $bio = sanitize($_POST['bio']);
    $price_per_hour = floatval($_POST['price_per_hour']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Обработка загрузки нового изображения
    $image = $instructor['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/instructors/';
        $file_name = basename($_FILES['image']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $new_name = uniqid('instructor_', true) . '.' . $file_ext;
            $upload_path = $upload_dir . $new_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Удаляем старое изображение, если оно существует
                if ($image && file_exists($upload_dir . $image)) {
                    unlink($upload_dir . $image);
                }
                $image = $new_name;
            }
        }
    }
    
    $query = "UPDATE instructors SET 
              name = ?, 
              specialization = ?, 
              experience = ?, 
              bio = ?, 
              image = ?, 
              is_active = ?, 
              price_per_hour = ? 
              WHERE id = ?";
    
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'ssissidi', 
        $name, 
        $specialization, 
        $experience, 
        $bio, 
        $image, 
        $is_active, 
        $price_per_hour, 
        $instructor_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = 'Данные инструктора успешно обновлены';
        header('Location: /admin/instructors.php');
        exit;
    } else {
        $error = 'Ошибка при обновлении данных: ' . mysqli_error($connection);
    }
    mysqli_stmt_close($stmt);
}

// Обработка удаления инструктора
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_instructor'])) {
    // Удаляем изображение, если оно существует
    if ($instructor['image']) {
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/instructors/';
        if (file_exists($upload_dir . $instructor['image'])) {
            unlink($upload_dir . $instructor['image']);
        }
    }
    
    $query = "DELETE FROM instructors WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $instructor_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = 'Инструктор успешно удален';
        header('Location: /admin/instructors.php');
        exit;
    } else {
        $error = 'Ошибка при удалении инструктора: ' . mysqli_error($connection);
    }
    mysqli_stmt_close($stmt);
}

require_once '../includes/header.php';
?>
<div class="admin-container">
    <h2>Редактирование инструктора</h2>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="admin-actions">
        <a href="instructors.php" class="btn btn-back">Назад к списку</a>
    </div>
    
    <div class="edit-form">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Имя:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($instructor['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="specialization">Специализация:</label>
                <select id="specialization" name="specialization" required>
                    <option value="ski" <?= $instructor['specialization'] === 'ski' ? 'selected' : '' ?>>Лыжи</option>
                    <option value="snowboard" <?= $instructor['specialization'] === 'snowboard' ? 'selected' : '' ?>>Сноуборд</option>
                    <option value="freestyle" <?= $instructor['specialization'] === 'freestyle' ? 'selected' : '' ?>>Фристайл</option>
                    <option value="children" <?= $instructor['specialization'] === 'children' ? 'selected' : '' ?>>Детские</option>
                </select>
            </div>
            <div class="form-group">
                <label for="experience">Опыт (лет):</label>
                <input type="number" id="experience" name="experience" min="0" value="<?= $instructor['experience'] ?>" required>
            </div>
            <div class="form-group">
                <label for="price_per_hour">Цена за час (₽):</label>
                <input type="number" id="price_per_hour" name="price_per_hour" min="0" step="100" value="<?= $instructor['price_per_hour'] ?>" required>
            </div>
            <div class="form-group">
                <label for="bio">Биография:</label>
                <textarea id="bio" name="bio" rows="4" required><?= htmlspecialchars($instructor['bio']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">Фото:</label>
                <?php if ($instructor['image']): ?>
                    <div class="current-image">
                        <img src="/assets/images/instructors/<?= $instructor['image'] ?>" alt="<?= htmlspecialchars($instructor['name']) ?>" width="100">
                        <p>Текущее изображение</p>
                    </div>
                <?php endif; ?>
                <input type="file" id="image" name="image" accept="image/*">
                <p class="hint">Оставьте пустым, чтобы сохранить текущее изображение</p>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" <?= $instructor['is_active'] ? 'checked' : '' ?>> Активен
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" name="update_instructor" class="btn btn-primary">Сохранить изменения</button>
                <button type="button" id="delete-btn" class="btn btn-danger">Удалить инструктора</button>
            </div>
        </form>
        
        <!-- Форма для удаления (скрытая) -->
        <form id="delete-form" method="POST" action="" style="display: none;">
            <input type="hidden" name="delete_instructor" value="1">
        </form>
    </div>
</div>

<style>
.edit-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 4px;
    margin-top: 20px;
}

.current-image {
    margin-bottom: 15px;
}

.current-image img {
    border-radius: 4px;
    border: 1px solid #ddd;
}

.hint {
    font-size: 13px;
    color: #6c757d;
    margin-top: 5px;
}


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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteBtn = document.getElementById('delete-btn');
    const deleteForm = document.getElementById('delete-form');
    
    deleteBtn.addEventListener('click', function() {
        if (confirm('Вы уверены, что хотите удалить этого инструктора? Это действие нельзя отменить.')) {
            deleteForm.submit();
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>

