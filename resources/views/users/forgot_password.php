<?php
$page_title = "Forgot Password - C2C eCommerce";
$custom_css = '<link href="' . my_url('/assets/css/forms.css') . '?v=' . time() . '" rel="stylesheet">';
include(resource_path('views/layouts/header.php')); 
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="form-card shadow">
                <h2 class="form-title">Forgot Password</h2>
                  <?php if(session('message') && !empty(session('message'))): ?>
                    <div class="alert alert-<?php echo session('message_type') ?? 'success'; ?>">
                        <?php echo session('message'); ?>
                    </div>
                <?php endif; ?>
                
                <?php if(session('errors.email') || (isset($errors) && isset($errors['email']))): ?>
                    <div class="form-error">
                        <span class="form-error-icon"><i class="fas fa-exclamation-circle"></i></span>
                        <?php echo session('errors.email') ?? ($errors['email'] ?? ''); ?>
                    </div>
                <?php endif; ?>
                
                <form id="forgot-password-form" method="POST" action="<?php echo route('password.email'); ?>" novalidate>
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email Address</label>                        <input 
                            type="email" 
                            class="form-control <?php echo (isset($errors) && isset($errors['email'])) || session('errors.email') ? 'is-invalid' : ''; ?>" 
                            id="email" 
                            name="email" 
                            value="<?php echo old('email', ''); ?>"
                            placeholder="Enter your registered email address"
                            required
                        >
                        <?php if ((isset($errors) && isset($errors['email'])) || session('errors.email')): ?>
                            <div class="invalid-feedback"><?php echo session('errors.email') ?? ($errors['email'] ?? ''); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-text mb-4">
                        Enter your registered email address and we'll send you a link to reset your password.
                    </div>
                    
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-dark w-100 py-2">Send Reset Link</button>
                    </div>
                </form>
                
                <div class="text-center mt-4 pt-3 border-top">
                    <p class="mb-0">Remember your password? <a href="<?php echo route('login'); ?>" class="text-dark fw-semibold text-decoration-none">Back to Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include(resource_path('views/layouts/footer.php')); ?>