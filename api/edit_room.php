<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    header('Location: /account/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: rooms.php');
    exit;
}

$room_id = intval($_GET['id']);

// Получение данных о номере
$query = "SELECT * FROM rooms WHERE id = $room_id";
$result = mysqli_query($connection, $query);
$room = mysqli_fetch_assoc($result);

if (!$room) {
    header('Location: rooms.php');
    exit;
}

// Обработка формы редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_room'])) {
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $price = floatval($_POST['price']);
    $capacity = intval($_POST['capacity']);
    
    // Обработка загрузки нового изображения
    $image = $room['image']; // По умолчанию оставляем старое изображение
    
    if (!empty($_FILES['image']['name'])) {
        // Удаляем старое изображение
        if (!empty($room['image'])) {
            $old_image_path = "../uploads/rooms/" . $room['image'];
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
        
        // Загружаем новое изображение
        $upload_dir = '../uploads/rooms/';
        $file_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $file_name;
        }
    }
    
    $query = "UPDATE rooms SET 
              name = '$name', 
              description = '$description', 
              price_per_night = $price, 
              capacity = $capacity, 
              image = '$image' 
              WHERE id = $room_id";
    
    if (mysqli_query($connection, $query)) {
        $_SESSION['success_message'] = 'Номер успешно обновлен';
        header('Location: rooms.php');
        exit;
    } else {
        $error = 'Ошибка при обновлении номера: ' . mysqli_error($connection);
    }
}

require_once '../includes/header.php';
?>

<div class="admin-container">
    <h2>Редактирование номера</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label>Название номера</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($room['name']); ?>" required>
        </div>
        <div class="form-group">
            <label>Описание</label>
            <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($room['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label>Цена за ночь (руб.)</label>
            <input type="number" name="price" class="form-control" min="0" step="0.01" value="<?php echo $room['price_per_night']; ?>" required>
        </div>
        <div class="form-group">
            <label>Вместимость (чел.)</label>
            <input type="number" name="capacity" class="form-control" min="1" value="<?php echo $room['capacity']; ?>" required>
        </div>
        <div class="form-group">
            <label>Текущее изображение</label>
            <?php if (!empty($room['image'])): ?>
                <img src="../uploads/rooms/<?php echo $room['image']; ?>" alt="Current image" class="img-thumbnail mb-2" style="max-height: 200px;">
                <div class="form-check">
                    <input type="checkbox" name="delete_image" id="delete_image" class="form-check-input">
                    <label class="form-check-label" for="delete_image">Удалить текущее изображение</label>
                </div>
            <?php else: ?>
                <p>Нет изображения</p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label>Новое изображение</label>
            <input type="file" name="image" class="form-control-file" accept="image/*">
        </div>
        <button type="submit" name="edit_room" class="btn btn-primary">Сохранить изменения</button>
        <a href="rooms.php" class="btn btn-secondary">Отмена</a>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>