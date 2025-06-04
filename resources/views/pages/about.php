<?php
// filepath: c:\xampp\htdocs\c2c_ecommerce\C2C_ecommerce_laravel\resources\views\pages\about.php
// Check if layout files should be included
$include_header = true;
$include_footer = true;

if (isset($include_header) && $include_header) {
    include_once(resource_path('views/layouts/header.php'));
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="text-center mb-4" style="color: #2c3e50;">About Us</h1>
                    
                    <div class="mb-4">
                        <h2 style="color: #2c3e50;">Our Story</h2>
                        <p class="lead">Welcome to C2C E-commerce, where individuals connect to buy and sell with ease.</p>
                        <p>Founded in 2025, our platform was created with a simple mission: to provide a safe, efficient, and user-friendly marketplace where consumers can trade directly with each other.</p>
                        <p>What began as a small community has now grown into a thriving ecosystem of buyers and sellers from all walks of life, sharing their passion for quality products and fair trade.</p>
                    </div>
                    
                    <div class="bg-light p-4 rounded mb-4">
                        <h2 class="mb-3" style="color: #2c3e50;">Our Mission</h2>
                        <p class="mb-0">To empower individuals by providing a trusted platform where they can easily buy and sell goods, creating economic opportunities and building sustainable communities.</p>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p class="lead">Start buying and selling on our platform today!</p>
                        <a href="<?php echo my_url('/register'); ?>" class="btn btn-primary me-2" style="background-color: #2c3e50; border-color: #2c3e50;">Sign Up Now</a>
                        <a href="<?php echo my_url('/contact'); ?>" class="btn btn-outline-secondary">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($include_footer) && $include_footer) {
    include_once(resource_path('views/layouts/footer.php'));
}
?>
