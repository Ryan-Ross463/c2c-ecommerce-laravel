        </div>
</main>

<footer id="main-footer">
    <div class="container">
        <div class="footer-brand">
            <h5><i class="fas fa-shopping-cart me-2"></i>C2C E-commerce</h5>
            <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> C2C E-commerce. All rights reserved.</p>
        </div>
        
        <div class="row g-3">
            <div class="col-lg-6 col-md-6">
                <div class="footer-section">
                    <h5 class="footer-heading">Quick Links</h5>
                    <ul class="footer-nav">
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/'); ?>" class="footer-link">Home</a>
                        </li>
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/products'); ?>" class="footer-link">Products</a>
                        </li>
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/categories'); ?>" class="footer-link">Categories</a>
                        </li>
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/about'); ?>" class="footer-link">About Us</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-6 col-md-6">
                <div class="footer-section">
                    <h5 class="footer-heading">Account</h5>
                    <ul class="footer-nav">
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/login'); ?>" class="footer-link">Login</a>
                        </li>
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/register'); ?>" class="footer-link">Register</a>
                        </li>
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/dashboard'); ?>" class="footer-link">My Account</a>
                        </li>
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/seller/products/create'); ?>" class="footer-link">Sell Item</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function adjustHeight() {
        document.body.style.minHeight = window.innerHeight + 'px';
    }
    
    window.addEventListener('resize', adjustHeight);
    adjustHeight();
});
</script>

<?php if (isset($custom_js)) echo $custom_js; ?>

</body>
</html>
