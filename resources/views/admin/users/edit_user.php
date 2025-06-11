<?php 
include(resource_path('views/layouts/admin_header.php'));

function getUserProperty($user, $property, $default = '') {
    return isset($user) && is_object($user) && isset($user->$property) ? $user->$property : $default;
}
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/admin_edit_user.css'); ?>">

<div class="admin-content-main">
    <div class="admin-card card">        <div class="admin-header card-header d-flex justify-content-between align-items-center">
            <h1 class="card-title mb-0">Edit User</h1>
            <a href="/admin/users/manage_users" class="btn btn-outline-secondary">Back to Users</a>
        </div>
        
        <div class="card-body">            <?php if(isset($user) && is_object($user)): ?>
                <form action="/admin/users/edit/<?php echo htmlspecialchars(getUserProperty($user, 'user_id')); ?>" method="POST" class="admin-form">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" 
                               value="<?php echo old('name') ?? htmlspecialchars(getUserProperty($user, 'name')); ?>" 
                               required class="form-control <?php echo hasError($errors, 'name') ? 'is-invalid' : ''; ?>">
                        <?php if(hasError($errors, 'name')): ?>
                            <div class="invalid-feedback">
                                <?php echo getError($errors, 'name'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo old('email') ?? htmlspecialchars(getUserProperty($user, 'email')); ?>" 
                               required class="form-control <?php echo hasError($errors, 'email') ? 'is-invalid' : ''; ?>">
                        <?php if(hasError($errors, 'email')): ?>
                            <div class="invalid-feedback">
                                <?php echo getError($errors, 'email'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="role" class="form-label">User Role</label>
                        <?php 
                        $currentRole = getUserProperty($user, 'role_id');
                        $roles = [
                            1 => 'Buyer',
                            2 => 'Seller',
                            3 => 'Admin'
                        ];
                        ?>
                        <select id="role" name="role_id" class="form-select <?php echo hasError($errors, 'role_id') ? 'is-invalid' : ''; ?>">
                            <?php foreach ($roles as $roleId => $roleName): ?>
                                <option value="<?php echo $roleId; ?>" <?php echo ($currentRole == $roleId) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($roleName); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(hasError($errors, 'role_id')): ?>
                            <div class="invalid-feedback">
                                <?php echo getError($errors, 'role_id'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password <small class="text-muted">(Leave empty to keep current password)</small></label>
                        <input type="password" id="password" name="password" 
                               class="form-control <?php echo hasError($errors, 'password') ? 'is-invalid' : ''; ?>">
                        <?php if(hasError($errors, 'password')): ?>
                            <div class="invalid-feedback">
                                <?php echo getError($errors, 'password'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>
                      <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="/admin/users/manage_users" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> User not found or invalid user data provided.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include(resource_path('views/layouts/admin_footer.php'));
?>
