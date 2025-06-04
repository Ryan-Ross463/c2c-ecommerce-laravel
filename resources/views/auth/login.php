<?php
$page_title = "Login - C2C eCommerce";
$custom_css = '<link href="' . my_url('/assets/css/forms.css') . '?v=' . time() . '" rel="stylesheet">';
include(resource_path('views/layouts/header.php'));
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">            <div class="form-card shadow">
                <h2 class="form-title">Welcome Back</h2>
                
                <?php if(session('error')): ?>
                    <div class="form-error">
                        <span class="form-error-icon"><i class="fas fa-exclamation-circle"></i></span>
                        <?php echo session('error'); ?>
                    </div>
                <?php endif; ?>
                <form id="login-form" class="login-form" action="<?php echo route('login'); ?>" method="POST" novalidate>
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            type="email" 
                            class="form-control <?php echo (isset($errors) && is_array($errors) && isset($errors['email'])) ? 'is-invalid' : ''; ?>" 
                            id="email" 
                            name="email" 
                            value="<?php echo old('email', ''); ?>"
                            placeholder="you@example.com"
                            required
                        >
                        <?php if(isset($errors) && is_array($errors) && isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            type="password" 
                            class="form-control <?php echo (isset($errors) && is_array($errors) && isset($errors['password'])) ? 'is-invalid' : ''; ?>" 
                            id="password" 
                            name="password"
                            placeholder="Enter your password"
                            required
                        >
                        <?php if(isset($errors) && is_array($errors) && isset($errors['password'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-dark w-100 py-2">Sign In</button>
                    </div>                    
                </form>
                
                <div class="text-center mt-4 pt-3 border-top">
                    <p class="mb-2">Don't have an account? <a href="<?php echo route('register'); ?>" class="text-dark fw-semibold text-decoration-none">Register here</a></p>
                    <p class="mb-0"><a href="<?php echo route('password.request'); ?>" class="text-secondary text-decoration-none">Forgot your password?</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include(resource_path('views/layouts/footer.php')); ?>
