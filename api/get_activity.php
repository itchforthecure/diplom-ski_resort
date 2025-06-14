<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

header('Content-Type: text/html; charset=utf-8');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('<p>Неверный ID активности</p>');
}

$activityId = (int)$_GET['id'];
$query = "SELECT * FROM activities WHERE id = $activityId";
$result = mysqli_query($connection, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die('<p>Активность не найдена</p>');
}

$activity = mysqli_fetch_assoc($result);
?>

<div class="activity-details">
    <h2><?php echo htmlspecialchars($activity['name']); ?></h2>
    <div class="detail-image">
        <img src="/assets/images/activities/<?php echo $activity['image']; ?>" alt="<?php echo htmlspecialchars($activity['name']); ?>">
    </div>
    <div class="detail-meta">
        <p><strong>Категория:</strong> <?php echo $activity['category']; ?></p>
        <p><strong>Цена:</strong> <?php echo $activity['price']; ?> руб.</p>
        <p><strong>Продолжительность:</strong> <?php echo $activity['duration']; ?> минут</p>
        <p><strong>Количество участников:</strong> <?php echo $activity['min_people']; ?>-<?php echo $activity['max_people']; ?> человек</p>
    </div>
    <div class="detail-description">
        <?php echo nl2br(htmlspecialchars($activity['description'])); ?>
    </div>
</div>