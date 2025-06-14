<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';


// Проверка прав администратора
if (!isAdmin()) {
    header('Location: /login.php');
    exit;
}

// Обработка формы добавления/редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = trim($_POST['name']);
    $difficulty = $_POST['difficulty'];
    $length = intval($_POST['length']);
    $elevation = intval($_POST['elevation']);
    $description = trim($_POST['description']);
    $coordinates = trim($_POST['coordinates']);
    $start_point = trim($_POST['start_point']);
    $status = $_POST['status'];
    
    // Валидация данных
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Название трассы обязательно';
    }
    
    if (!in_array($difficulty, ['easy', 'medium', 'hard', 'expert'])) {
        $errors[] = 'Некорректная сложность трассы';
    }
    
    if ($length <= 0) {
        $errors[] = 'Длина трассы должна быть положительным числом';
    }
    
    if ($elevation <= 0) {
        $errors[] = 'Перепад высот должен быть положительным числом';
    }
    
    if (empty($coordinates)) {
        $errors[] = 'Координаты трассы обязательны';
    }
    
    if (empty($errors)) {
        if ($id > 0) {
            // Редактирование существующей трассы
            $stmt = $connection->prepare("UPDATE slopes SET name = ?, difficulty = ?, length = ?, elevation = ?, description = ?, coordinates = ?, start_point = ?, status = ? WHERE id = ?");
            $stmt->bind_param("ssiissssi", $name, $difficulty, $length, $elevation, $description, $coordinates, $start_point, $status, $id);
        } else {
            // Добавление новой трассы
            $stmt = $connection->prepare("INSERT INTO slopes (name, difficulty, length, elevation, description, coordinates, start_point, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiissss", $name, $difficulty, $length, $elevation, $description, $coordinates, $start_point, $status);
        }
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = $id > 0 ? 'Трасса успешно обновлена' : 'Трасса успешно добавлена';
            header('Location: slopes.php');
            exit;
        } else {
            $errors[] = 'Ошибка при сохранении в базу данных: ' . $stmt->error;
        }
    }
}

// Получение данных для редактирования
$slope = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $connection->query("SELECT * FROM slopes WHERE id = $id");
    $slope = $result->fetch_assoc();
}

// Удаление трассы
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $connection->query("DELETE FROM slopes WHERE id = $id");
    $_SESSION['success_message'] = 'Трасса успешно удалена';
    header('Location: slopes.php');
    exit;
}

// Получение списка всех трасс
$slopes = $connection->query("SELECT * FROM slopes ORDER BY name")->fetch_all(MYSQLI_ASSOC);

require_once '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Управление трассами</h2>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <?php echo isset($slope) ? 'Редактирование трассы' : 'Добавление новой трассы'; ?>
        </div>
        <div class="card-body">
            <form method="POST">
                <?php if (isset($slope)): ?>
                    <input type="hidden" name="id" value="<?php echo $slope['id']; ?>">
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Название трассы</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo isset($slope) ? htmlspecialchars($slope['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="difficulty">Сложность</label>
                            <select class="form-control" id="difficulty" name="difficulty" required>
                                <option value="easy" <?php echo (isset($slope) && $slope['difficulty'] === 'easy') ? 'selected' : ''; ?>>Легкая</option>
                                <option value="medium" <?php echo (isset($slope) && $slope['difficulty'] === 'medium') ? 'selected' : ''; ?>>Средняя</option>
                                <option value="hard" <?php echo (isset($slope) && $slope['difficulty'] === 'hard') ? 'selected' : ''; ?>>Сложная</option>
                                <option value="expert" <?php echo (isset($slope) && $slope['difficulty'] === 'expert') ? 'selected' : ''; ?>>Экспертная</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Статус</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="open" <?php echo (isset($slope) && $slope['status'] === 'open') ? 'selected' : ''; ?>>Открыта</option>
                                <option value="closed" <?php echo (isset($slope) && $slope['status'] === 'closed') ? 'selected' : ''; ?>>Закрыта</option>
                                <option value="partial" <?php echo (isset($slope) && $slope['status'] === 'partial') ? 'selected' : ''; ?>>Частично открыта</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="length">Длина трассы (м)</label>
                            <input type="number" class="form-control" id="length" name="length" 
                                   value="<?php echo isset($slope) ? $slope['length'] : ''; ?>" min="1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="elevation">Перепад высот (м)</label>
                            <input type="number" class="form-control" id="elevation" name="elevation" 
                                   value="<?php echo isset($slope) ? $slope['elevation'] : ''; ?>" min="1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="start_point">Координаты начала (x,y)</label>
                            <input type="text" class="form-control" id="start_point" name="start_point" 
                                   value="<?php echo isset($slope) ? htmlspecialchars($slope['start_point']) : ''; ?>" 
                                   placeholder="Например: 100,200">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="coordinates">Координаты трассы (SVG path)</label>
                    <textarea class="form-control" id="coordinates" name="coordinates" rows="3" required><?php echo isset($slope) ? htmlspecialchars($slope['coordinates']) : ''; ?></textarea>
                    <small class="form-text text-muted">Используйте SVG path data, например: M100,200 L150,180 L200,190</small>
                </div>
                
                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo isset($slope) ? htmlspecialchars($slope['description']) : ''; ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <?php echo isset($slope) ? 'Обновить трассу' : 'Добавить трассу'; ?>
                </button>
                
                <?php if (isset($slope)): ?>
                    <a href="slopes.php" class="btn btn-secondary">Отмена</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            Список всех трасс
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Сложность</th>
                            <th>Длина</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($slopes as $slope): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($slope['name']); ?></td>
                                <td>
                                    <span class="badge 
                                        <?php 
                                            switch ($slope['difficulty']) {
                                                case 'easy': echo 'badge-success'; break;
                                                case 'medium': echo 'badge-primary'; break;
                                                case 'hard': echo 'badge-warning'; break;
                                                case 'expert': echo 'badge-danger'; break;
                                            }
                                        ?>">
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
                                <td>
                                    <span class="badge 
                                        <?php 
                                            switch ($slope['status']) {
                                                case 'open': echo 'badge-success'; break;
                                                case 'closed': echo 'badge-danger'; break;
                                                case 'partial': echo 'badge-warning'; break;
                                            }
                                        ?>">
                                        <?php 
                                            switch ($slope['status']) {
                                                case 'open': echo 'Открыта'; break;
                                                case 'closed': echo 'Закрыта'; break;
                                                case 'partial': echo 'Частично'; break;
                                            }
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="slopes.php?edit=<?php echo $slope['id']; ?>" class="btn btn-sm btn-primary">Редактировать</a>
                                    <a href="slopes.php?delete=<?php echo $slope['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить эту трассу?')">Удалить</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>