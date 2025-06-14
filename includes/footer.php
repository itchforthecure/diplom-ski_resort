</main>
    
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3 class="footer-title">Контакты</h3>
                    <ul class="footer-contacts">
                        <li><i class="fas fa-map-marker-alt"></i> г. Горнолыжск, ул. Снежная, 15</li>
                        <li><i class="fas fa-phone"></i> +7 (123) 456-78-90</li>
                        <li><i class="fas fa-envelope"></i> info@snowpeak.com</li>
                    </ul>
                </div>
                
                
                <div class="footer-section">
                    <h3 class="footer-title">Обратная связь</h3>
                    <form id="feedback-form" class="feedback-form" action="/process_feedback.php" method="POST">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Ваше имя" required class="form-input">
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Ваш email" required class="form-input">
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Ваше сообщение" required class="form-textarea"></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Отправить</button>
                    </form>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2023 Горнолыжный курорт "Снежная вершина". Все права защищены.</p>
            </div>
        </div>
    </footer>

<style>
.site-footer {
    background: linear-gradient(135deg, #2b5876, #4e4376);
    color: #fff;
    padding: 40px 0 20px;
    font-family: 'Open Sans', sans-serif;
}

.container-footer {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-bottom: 30px;
}

.footer-title {
    font-size: 18px;
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 10px;
}

.footer-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 50px;
    height: 2px;
    background: #4facfe;
}

.footer-contacts li, .social-links li {
    margin-bottom: 12px;
    display: flex;
    align-items: center;
}

.footer-contacts i, .social-links i {
    margin-right: 10px;
    width: 20px;
    color: #4facfe;
}

.social-link {
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    color: #4facfe;
    transform: translateX(5px);
}

.feedback-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-group {
    position: relative;
}

.form-input, .form-textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid rgba(255,255,255,0.2);
    background: rgba(255,255,255,0.1);
    color: #fff;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.form-input:focus, .form-textarea:focus {
    outline: none;
    border-color: #4facfe;
    background: rgba(255,255,255,0.2);
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
}

.submit-btn {
    background: #4facfe;
    color: #fff;
    border: none;
    padding: 12px 25px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    align-self: flex-start;
}

.submit-btn:hover {
    background: #3a8fd4;
    transform: translateY(-2px);
}

.copyright {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.1);
    font-size: 14px;
    color: rgba(255,255,255,0.7);
}

/* Сообщения об успехе/ошибке */
.feedback-message {
    padding: 10px;
    margin-top: 15px;
    border-radius: 4px;
    display: none;
}

.success-message {
    background: rgba(46, 204, 113, 0.2);
    color: #2ecc71;
}

.error-message {
    background: rgba(231, 76, 60, 0.2);
    color: #e74c3c;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedbackForm = document.getElementById('feedback-form');
    
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.textContent;
            
            submitBtn.textContent = 'Отправка...';
            submitBtn.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Создаем или находим элемент для сообщения
                let messageEl = this.querySelector('.feedback-message');
                if (!messageEl) {
                    messageEl = document.createElement('div');
                    this.appendChild(messageEl);
                }
                
                if (data.success) {
                    messageEl.className = 'feedback-message success-message';
                    messageEl.textContent = data.message || 'Спасибо! Ваше сообщение отправлено.';
                    this.reset();
                } else {
                    messageEl.className = 'feedback-message error-message';
                    messageEl.textContent = data.message || 'Произошла ошибка. Пожалуйста, попробуйте позже.';
                }
                
                messageEl.style.display = 'block';
                submitBtn.textContent = originalBtnText;
                submitBtn.disabled = false;
                
                // Скрываем сообщение через 5 секунд
                setTimeout(() => {
                    messageEl.style.display = 'none';
                }, 5000);
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.textContent = originalBtnText;
                submitBtn.disabled = false;
            });
        });
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/script.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>