<?php

require_once '../includes/config.php';
require_once '../includes/functions.php';

// Обработка формы добавления события
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $event_date = $_POST['event_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $location = sanitize($_POST['location']);
    $event_type = $_POST['event_type'];
    
    // Загрузка изображения
    $image_path = '';
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/events/';
        $file_name = uniqid() . '_' . basename($_FILES['event_image']['name']);
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['event_image']['tmp_name'], $target_path)) {
            $image_path = '/assets/images/events/' . $file_name;
        }
    }
    
    // Добавление в базу данных
    $query = "INSERT INTO calendar_events (title, description, event_date, start_time, end_time, location, image_path, event_type) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssss', $title, $description, $event_date, $start_time, $end_time, $location, $image_path, $event_type);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = 'Событие успешно добавлено!';
    } else {
        $_SESSION['error_message'] = 'Ошибка при добавлении события: ' . mysqli_error($connection);
    }
}

// Получение списка событий
$events = [];
$query = "SELECT * FROM calendar_events ORDER BY event_date DESC";
$result = mysqli_query($connection, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление событиями</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Управление событиями</h1>
        
        <div class="admin-panel">
            <div class="add-event-form">
                <h2>Добавить новое событие</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Название события:</label>
                        <input type="text" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Описание:</label>
                        <textarea name="description" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Дата события:</label>
                            <input type="date" name="event_date" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Время начала:</label>
                            <input type="time" name="start_time">
                        </div>
                        
                        <div class="form-group">
                            <label>Время окончания:</label>
                            <input type="time" name="end_time">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Место проведения:</label>
                        <input type="text" name="location">
                    </div>
                    
                    <div class="form-group">
                        <label>Тип события:</label>
                        <select name="event_type">
                            <option value="regular">Обычное</option>
                            <option value="special">Специальное</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Изображение:</label>
                        <input type="file" name="event_image" accept="image/*">
                    </div>
                    
                    <button type="submit" class="btn">Добавить событие</button>
                </form>
            </div>
            
            <div class="events-list">
                <h2>Список событий</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Название</th>
                            <th>Место</th>
                            <th>Тип</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?= date('d.m.Y', strtotime($event['event_date'])) ?></td>
                            <td><?= htmlspecialchars($event['title']) ?></td>
                            <td><?= htmlspecialchars($event['location']) ?></td>
                            <td><?= $event['event_type'] == 'special' ? 'Спец.' : 'Обычное' ?></td>
                            <td>
                                <a href="edit_event.php?id=<?= $event['id'] ?>" class="btn-sm">Редактировать</a>
                                <a href="delete_event.php?id=<?= $event['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Удалить это событие?')">Удалить</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>