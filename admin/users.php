<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: /account/login.php');
    exit;
}

// Обработка удаления пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']);
    
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = 'Пользователь успешно удален';
        header('Location: users.php');
        exit;
    } else {
        $error = 'Ошибка при удалении пользователя: ' . mysqli_error($connection);
    }
    mysqli_stmt_close($stmt);
}

// Получение списка всех пользователей
$query = "SELECT id, username, email, reg_date, role, full_name, phone FROM users ORDER BY reg_date DESC";
$result = mysqli_query($connection, $query);

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

require_once '../includes/header.php';
?>

<div class="admin-container">
    <h2>Управление пользователями</h2>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="admin-actions">
        <a href="index.php" class="btn btn-back">Назад в админ-панель</a>
    </div>
    
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя пользователя</th>
                    <th>Полное имя</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Роль</th>
                    <th>Дата регистрации</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['full_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone'] ?? '') ?></td>
                        <td><?= getRoleName($user['role']) ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($user['reg_date'])) ?></td>
                        <td>
                            <a href="/api/edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm">Редактировать</a>
                            <form method="POST" action="" style="display: inline-block;" onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?');">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" name="delete_user" class="btn btn-sm btn-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>