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

// Получение ID активности из URL
$activity_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Получение данных активности для подтверждения удаления
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

// Обработка подтверждения удаления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // Сначала получаем путь к изображению
    $query = "SELECT image FROM activities WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $activity_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    // Удаляем запись из базы данных
    $query = "DELETE FROM activities WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $activity_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Удаляем связанное изображение, если оно существует
        if (!empty($row['image'])) {
            $image_path = '../assets/images/activities/' . $row['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        $success = 'Активность успешно удалена';
        // Перенаправляем через 2 секунды
        header('Refresh: 2; URL=/admin/activities.php');
    } else {
        $error = 'Ошибка при удалении активности: ' . mysqli_error($connection);
    }
    mysqli_stmt_close($stmt);
}

require_once '../includes/header.php';
?>

<div class="admin-container">
    <h2>Удаление активности</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <a href="/admin/activities.php" class="btn btn-back">Вернуться к списку</a>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <p>Вы будете перенаправлены на страницу управления активностями...</p>
    <?php elseif ($activity): ?>
        <div class="confirmation-box">
            <h3>Подтверждение удаления</h3>
            <p>Вы действительно хотите удалить активность <strong>"<?php echo htmlspecialchars($activity['name']); ?>"</strong>?</p>
            
            <div class="activity-info">
                <?php if (!empty($activity['image'])): ?>
                    <img src="/assets/images/activities/<?php echo htmlspecialchars($activity['image']); ?>" alt="<?php echo htmlspecialchars($activity['name']); ?>" width="200">
                <?php endif; ?>
                <p><strong>Категория:</strong> 
                    <?php 
                    switch ($activity['category']) {
                        case 'winter': echo 'Зимние'; break;
                        case 'summer': echo 'Летние'; break;
                        case 'family': echo 'Семейные'; break;
                        case 'extreme': echo 'Экстрим'; break;
                        default: echo htmlspecialchars($activity['category']);
                    }
                    ?>
                </p>
                <p><strong>Цена:</strong> <?php echo htmlspecialchars($activity['price']); ?> руб.</p>
            </div>
            
            <form method="POST" action="">
                <button type="submit" name="confirm_delete" class="btn btn-danger">Да, удалить</button>
                <a href="activities.php" class="btn">Отмена</a>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
.confirmation-box {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 5px;
    text-align: center;
}

.activity-info {
    margin: 20px 0;
    padding: 15px;
    background: white;
    border-radius: 5px;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
}
</style>

<?php require_once '../includes/footer.php'; ?>