<?php
$include_header = true;
$include_footer = true;

if (isset($include_header) && $include_header) {
    include_once(resource_path('views/layouts/header.php'));
}

$form_submitted = false;
$form_success = false;
$form_errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {
    $form_submitted = true;
    
    if (empty($_POST['name'])) {
        $form_errors[] = 'Please enter your name';
    }
    
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $form_errors[] = 'Please enter a valid email address';
    }
    
    if (empty($_POST['message'])) {
        $form_errors[] = 'Please enter your message';
    }
    
    if (empty($form_errors)) {
        $form_success = true;
      
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="text-center mb-4" style="color: #2c3e50;">Contact Us</h1>
                    
                    <?php if ($form_submitted && $form_success): ?>
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Thank you for contacting us!</h4>
                            <p>We have received your message and will respond to you as soon as possible.</p>
                        </div>
                    <?php else: ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h4 style="color: #2c3e50;">Contact Information</h4>
                            <p><i class="fas fa-envelope me-2" style="color: #2c3e50;"></i> support@c2cecommerce.com</p>
                            <p><i class="fas fa-phone me-2" style="color: #2c3e50;"></i> (555) 123-4567</p>
                            <p><i class="fas fa-map-marker-alt me-2" style="color: #2c3e50;"></i> 123 E-commerce Street<br>San Francisco, CA 94105</p>
                            <p><i class="fas fa-clock me-2" style="color: #2c3e50;"></i> Mon-Fri: 9am-5pm PST</p>
                        </div>
                        
                        <div class="col-md-6">
                            <?php if ($form_submitted && !empty($form_errors)): ?>
                                <div class="alert alert-danger mb-3" role="alert">
                                    <ul class="mb-0">
                                        <?php foreach ($form_errors as $error): ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <form method="post" action="<?php echo my_url('/contact'); ?>">
                                <input type="hidden" name="contact_form" value="1">
                                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?? ''; ?>">
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Your Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $_POST['name'] ?? ''; ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" required><?php echo $_POST['message'] ?? ''; ?></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary" style="background-color: #2c3e50; border-color: #2c3e50;">Send Message</button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
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
