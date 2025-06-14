<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$error = '';
$success = '';

// Обработка отправки отзыва (только для авторизованных пользователей)
if (isLoggedIn() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $content = sanitize($_POST['content']);
    $rating = intval($_POST['rating']);
    $user_id = $_SESSION['user_id'];

    // Валидация данных
    if (empty($content)) {
        $error = 'Текст отзыва не может быть пустым';
    } elseif ($rating < 1 || $rating > 5) {
        $error = 'Оценка должна быть от 1 до 5';
    } else {
        // Добавление отзыва в базу данных
        $query = "INSERT INTO reviews (user_id, content, rating) VALUES ('$user_id', '$content', '$rating')";
        if (mysqli_query($connection, $query)) {
            $success = 'Ваш отзыв успешно добавлен!';
        } else {
            $error = 'Ошибка при добавлении отзыва: ' . mysqli_error($connection);
        }
    }
}

// Получение всех отзывов (доступно всем)
$query = "SELECT r.*, u.username 
          FROM reviews r 
          JOIN users u ON r.user_id = u.id 
          ORDER BY r.review_date DESC";
$result = mysqli_query($connection, $query);
$reviews = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reviews[] = $row;
}

require_once '../includes/header.php';
?>

<section class="reviews-section">
    <div class="container">
        <h2>Отзывы о курорте</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isLoggedIn()): ?>
            <div class="add-review-form">
                <h3>Оставить отзыв</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="rating">Ваша оценка:</label>
                        <select id="rating" name="rating" class="form-control" required>
                            <option value="">Выберите оценку</option>
                            <option value="5">5 - Отлично</option>
                            <option value="4">4 - Хорошо</option>
                            <option value="3">3 - Удовлетворительно</option>
                            <option value="2">2 - Плохо</option>
                            <option value="1">1 - Ужасно</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="content">Ваш отзыв:</label>
                        <textarea id="content" name="content" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" name="submit_review" class="btn btn-primary">Отправить отзыв</button>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Чтобы оставить отзыв, пожалуйста, <a href="/account/login.php">войдите</a> или <a href="/account/register.php">зарегистрируйтесь</a>.
            </div>
        <?php endif; ?>
        
        <div class="reviews-list">
            <h3>Последние отзывы</h3>
            
            <?php if (empty($reviews)): ?>
                <p>Пока нет отзывов. Будьте первым!</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-author">
                                <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                            </div>
                            <div class="review-date">
                                <?php echo date('d.m.Y H:i', strtotime($review['review_date'])); ?>
                            </div>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star<?php echo $i <= $review['rating'] ? '' : '-alt'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="review-content">
                            <?php echo nl2br(htmlspecialchars($review['content'])); ?>
                        </div>
                        
                        <?php if (isLoggedIn() && (isAdmin() || $_SESSION['user_id'] == $review['user_id'])): ?>
                            <div class="review-actions">
                                <form method="POST" action="/api/delete_review.php" style="display: inline;">
                                    <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.reviews-section {
    padding: 4rem 2rem;
    background: var(--light-bg);
}

.reviews-section h2 {
    color: var(--primary-color);
    text-align: center;
    margin-bottom: 2rem;
    font-size: 2.5rem;
}

.add-review-form {
    background: var(--white);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: var(--shadow);
    margin-bottom: 3rem;
    border: 1px solid rgba(26, 115, 232, 0.1);
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.add-review-form h3 {
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-color);
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid rgba(26, 115, 232, 0.2);
    border-radius: 5px;
    transition: var(--transition);
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.1);
    outline: none;
}

.btn-primary {
    background: var(--primary-color);
    color: var(--white);
    padding: 0.8rem 2rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: var(--transition);
    font-weight: 500;
}

.btn-primary:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
}

.reviews-list {
    max-width: 1000px;
    margin: 0 auto;
}

.reviews-list h3 {
    color: var(--primary-color);
    margin-bottom: 2rem;
    font-size: 2rem;
    text-align: center;
}

.review-card {
    background: var(--white);
    border-radius: 10px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow);
    border: 1px solid rgba(26, 115, 232, 0.1);
    transition: var(--transition);
}

.review-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(26, 115, 232, 0.15);
    border-color: var(--primary-color);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.review-author {
    font-size: 1.2rem;
    color: var(--primary-color);
    font-weight: 500;
}

.review-date {
    color: var(--text-color);
    opacity: 0.8;
    font-size: 0.9rem;
}

.review-rating {
    color: #FFD700;
    font-size: 1.2rem;
}

.review-content {
    line-height: 1.8;
    color: var(--text-color);
    margin-bottom: 1.5rem;
}

.review-actions {
    text-align: right;
}

.btn-danger {
    background: #dc3545;
    color: var(--white);
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: var(--transition);
    font-size: 0.9rem;
}

.btn-danger:hover {
    background: #c82333;
    transform: translateY(-2px);
}

.alert {
    padding: 1rem;
    border-radius: 5px;
    margin-bottom: 1.5rem;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-info {
    background: #e7f5fe;
    color: #0c5460;
    border: 1px solid #bee5eb;
    max-width: 800px;
    margin: 0 auto 3rem;
    text-align: center;
}

.alert-info a {
    color: var(--primary-color);
    text-decoration: underline;
}

@media (max-width: 768px) {
    .reviews-section {
        padding: 2rem 1rem;
    }

    .review-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .review-date {
        order: -1;
    }

    .review-card {
        padding: 1.5rem;
    }

    .add-review-form {
        padding: 1.5rem;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>