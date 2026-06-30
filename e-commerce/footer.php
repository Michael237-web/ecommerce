<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>MichaelStore</h3>
            <p>Your trusted online shopping destination offering quality products at affordable prices.</p>
            <br>
            <p style="font-size:0.9rem; color:#94a3b8;">
                <strong>📍 Location:</strong> Nairobi, Kenya<br>
                <strong>📞 Phone:</strong> +254 700 000 000<br>
                <strong>✉️ Email:</strong> info@michaelstore.co.ke
            </p>
        </div>

        <div class="footer-section">
            <h3>Quick Links</h3>
            <p><a href="index.php">🏠 Home</a></p>
            <p><a href="products.php">🛍️ Products</a></p>
            <p><a href="about.php">📖 About Us</a></p>
            <p><a href="contact.php">📧 Contact</a></p>
        </div>

        <div class="footer-section">
            <h3>My Account</h3>
            <?php if(isset($_SESSION['user_id'])): ?>
                <p><a href="account.php">👤 My Account</a></p>
                <p><a href="orders.php">📦 My Orders</a></p>
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin"): ?>
                    <p><a href="admin-panel.php">⚙️ Admin Panel</a></p>
                <?php endif; ?>
                <p><a href="logout.php">🚪 Logout</a></p>
            <?php else: ?>
                <p><a href="login.php">🔑 Login</a></p>
                <p><a href="register.php">📝 Register</a></p>
            <?php endif; ?>
            <p><a href="cart.php">🛒 Shopping Cart</a></p>
        </div>

        <div class="footer-section">
            <h3>Customer Support</h3>
            <p><strong>📧 Email:</strong> support@michaelstore.co.ke</p>
            <p><strong>📞 Phone:</strong> +254 700 000 000</p>
            <p><strong>🕐 Hours:</strong> Mon - Sat: 8:00 AM - 8:00 PM</p>
            <p><strong>📅 Sunday:</strong> Closed</p>
        </div>

        <div class="footer-section">
            <h3>Legal</h3>
            <p><a href="privacy-policy.php">🔒 Privacy Policy</a></p>
            <p><a href="terms.php">📜 Terms & Conditions</a></p>
            <p><a href="shipping-policy.php">🚚 Shipping Policy</a></p>
            <p><a href="refund-policy.php">💰 Refund Policy</a></p>
        </div>

        <div class="footer-section">
            <h3>📧 Newsletter</h3>
            <p>Subscribe for offers and new arrivals.</p>
            
            <form action="newsletter.php" method="POST" id="newsletterForm">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit">Subscribe</button>
            </form>
            
            <div id="newsletterMessage" style="margin-top:10px; font-size:0.9rem;"></div>
        </div>
    </div>

    <div class="footer-payments">
        <h4>💳 Secure Payments</h4>
        <p>
            <span style="font-size:1.5rem; margin:0 5px;">💳</span>
            <span style="font-size:1.5rem; margin:0 5px;">🏦</span>
            <span style="font-size:1.5rem; margin:0 5px;">📱</span>
            <span style="font-size:1.5rem; margin:0 5px;">💵</span>
            <br>
            Visa • Mastercard • PayPal • M-Pesa
        </p>
    </div>

    <div class="footer-social">
        <a href="#" aria-label="Facebook">📘 Facebook</a>
        <a href="#" aria-label="Instagram">📸 Instagram</a>
        <a href="#" aria-label="Twitter/X">🐦 X</a>
        <a href="#" aria-label="LinkedIn">💼 LinkedIn</a>
        <a href="#" aria-label="YouTube">▶️ YouTube</a>
    </div>

    <div class="footer-bottom">
        <p>
            © <?php echo date('Y'); ?> MichaelStore. All Rights Reserved.
            <br>
            <span style="font-size:0.8rem; color:#64748b;">
                Made with ❤️ in Kenya
            </span>
        </p>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletterForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[name="email"]').value;
            const messageDiv = document.getElementById('newsletterMessage');
            
            if (!email || !email.includes('@') || !email.includes('.')) {
                messageDiv.innerHTML = '<span style="color:#dc2626;">⚠️ Please enter a valid email address.</span>';
                return;
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Subscribing...';
            submitBtn.disabled = true;
            
            const formData = new FormData();
            formData.append('email', email);
            formData.append('ajax', '1');
            
            fetch('newsletter.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('success')) {
                    messageDiv.innerHTML = '<span style="color:#16a34a;">✅ Thank you for subscribing!</span>';
                    this.querySelector('input[name="email"]').value = '';
                    setTimeout(() => {
                        messageDiv.innerHTML = '';
                    }, 5000);
                } else {
                    messageDiv.innerHTML = '<span style="color:#dc2626;">❌ ' + data + '</span>';
                }
            })
            .catch(error => {
                messageDiv.innerHTML = '<span style="color:#dc2626;">❌ Something went wrong. Please try again.</span>';
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});
</script>