<?php 
$custom_css = '<link rel="stylesheet" href="' . asset('assets/css/create_admin.css') . '">';
include(resource_path('views/layouts/admin_header.php'));

?>

<div class="admin-content-main">
    <h1>Create Admin User</h1>
    
    <div class="admin-card card">        <div class="admin-header card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">New Admin User</h2>
            <a href="/admin/users/manage_users" class="btn btn-outline-secondary">Back to Users</a>
        </div>
        
        <div class="admin-content card-body">
            <form action="/admin/users/create_admin" method="POST" class="admin-form">
                <?php echo csrf_field(); ?>
                
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" 
                           value="<?php echo old('name') ?? ''; ?>" 
                           required class="form-control <?php echo hasError($errors, 'name') ? 'is-invalid' : ''; ?>">
                    <?php if(hasError($errors, 'name')): ?>
                        <div class="invalid-feedback">
                            <?php echo getError($errors, 'name'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo old('email') ?? ''; ?>" 
                           required class="form-control <?php echo hasError($errors, 'email') ? 'is-invalid' : ''; ?>">
                    <?php if(hasError($errors, 'email')): ?>
                        <div class="invalid-feedback">
                            <?php echo getError($errors, 'email'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" 
                           required class="form-control <?php echo hasError($errors, 'password') ? 'is-invalid' : ''; ?>">
                    <?php if(hasError($errors, 'password')): ?>
                        <div class="invalid-feedback">
                            <?php echo getError($errors, 'password'); ?>
                        </div>
                    <?php else: ?>
                        <div class="form-text text-muted">
                            Password must include uppercase, lowercase, number and special character.
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           required class="form-control <?php echo hasError($errors, 'password_confirmation') ? 'is-invalid' : ''; ?>">
                    <?php if(hasError($errors, 'password_confirmation')): ?>
                        <div class="invalid-feedback">
                            <?php echo getError($errors, 'password_confirmation'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                  <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Create Admin
                    </button>
                    <a href="/admin/users/manage_users" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include(resource_path('views/layouts/admin_footer.php'));
?>
