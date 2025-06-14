<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (isset($_GET['id'])) {
    $instructor_id = intval($_GET['id']);
    $query = "SELECT * FROM instructors WHERE id = $instructor_id";
    $result = mysqli_query($connection, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $instructor = mysqli_fetch_assoc($result);
        ?>
        <div class="instructor-details">
            <div class="row">
                <div class="col-md-4">
                    <?php if ($instructor['image']): ?>
                        <img src="/assets/images/instructors/<?php echo $instructor['image']; ?>" 
                             alt="<?php echo htmlspecialchars($instructor['name']); ?>" 
                             class="img-fluid rounded">
                    <?php else: ?>
                        <div class="no-image-big">
                            <i class="fas fa-user-tie fa-5x"></i>
                        </div>
                    <?php endif; ?>
                    <div class="instructor-stats mt-3">
                        <div class="stat-item">
                            <i class="fas fa-star"></i>
                            <span>Рейтинг: <?php echo $instructor['rating'] ?? '4.8'; ?>/5</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-ruble-sign"></i>
                            <span>Цена: <?php echo $instructor['price_per_hour'] ?? '1500'; ?> руб./час</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-clock"></i>
                            <span>Опыт: <?php echo $instructor['experience']; ?> лет</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <h2><?php echo htmlspecialchars($instructor['name']); ?></h2>
                    <div class="specialization-badge">
                        <?php echo getSpecializationName($instructor['specialization']); ?>
                    </div>
                    
                    <div class="bio-section mt-4">
                        <h4>О себе</h4>
                        <p><?php echo nl2br(htmlspecialchars($instructor['bio'])); ?></p>
                    </div>
                    
                    <div class="certifications mt-4">
                        <h4>Сертификаты и квалификации</h4>
                        <p><?php echo nl2br(htmlspecialchars($instructor['certifications'] ?? 'Информация отсутствует')); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<p>Инструктор не найден</p>';
    }
} else {
    echo '<p>Не указан ID инструктора</p>';
}
?>