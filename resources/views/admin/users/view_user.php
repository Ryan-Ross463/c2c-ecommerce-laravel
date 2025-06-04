<?php 
$custom_css = '<link rel="stylesheet" href="' . asset('assets/css/view_user.css') . '">';
include(resource_path('views/layouts/admin_header.php'));

function getValue($data, $key, $default = 'N/A') {
    if (is_object($data)) {
        return isset($data->$key) ? $data->$key : $default;
    }
    return isset($data[$key]) ? $data[$key] : $default;
}
?>

<div class="admin-content-main">
    <div class="container">
        <h1 class="page-title mb-4">View User</h1>
        
        <div class="card">
            <div class="card-header">
                        <h2 class="card-title">User Details</h2>
                        <a href="<?php echo my_url('/admin/users/manage_users'); ?>" class="btn btn-outline-secondary back-button">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                    </div>
                
                <?php if(isset($user)): ?>
                    <div class="user-details">
                        <div class="detail-row">
                            <span class="detail-label">ID:</span>
                            <span class="detail-value"><?php echo getValue($user, 'user_id'); ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Name:</span>
                            <span class="detail-value"><?php echo htmlspecialchars(getValue($user, 'name')); ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value"><?php echo htmlspecialchars(getValue($user, 'email')); ?></span>
                        </div>
                        
                        <?php 
                        $roleName = getValue($user, 'role_name', 'user');
                        $roleClass = strtolower($roleName);
                        ?>
                        <div class="detail-row">
                            <span class="detail-label">Role:</span>
                            <span class="detail-value">
                                <span class="user-role-badge role-<?php echo $roleClass; ?>">
                                    <?php echo htmlspecialchars($roleName); ?>
                                </span>
                            </span>
                        </div>
                        
                        <?php 
                        $status = getValue($user, 'status', 'active');
                        $statusClass = strtolower($status);
                        ?>
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span class="detail-value">
                                <span class="status-badge status-<?php echo $statusClass; ?>">
                                    <?php echo ucfirst($status); ?>
                                </span>
                            </span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Created:</span>
                            <span class="detail-value"><?php echo getValue($user, 'created_at'); ?></span>
                        </div>
                        
                        <?php if(getValue($user, 'updated_at')): ?>
                        <div class="detail-row">
                            <span class="detail-label">Last Updated:</span>
                            <span class="detail-value"><?php echo getValue($user, 'updated_at'); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="user-actions p-3">
                        <a href="<?php echo my_url('/admin/users/edit/' . getValue($user, 'user_id')); ?>" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-2"></i> Edit User
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete('<?php echo getValue($user, 'user_id'); ?>', '<?php echo htmlspecialchars(getValue($user, 'name')); ?>')">
                            <i class="fas fa-trash me-2"></i> Delete User
                        </button>
                    </div>
                <?php else: ?>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> User not found
                        </div>
                    </div>                <?php endif; ?>                </div>
            </div>
        </div>
    </div>
</div>

<div id="deleteModal" class="modal fade" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the user <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form id="deleteUserForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const BASE_URL = '<?php echo my_url(''); ?>';
</script>
<script src="<?php echo asset('assets/js/view_user.js'); ?>"></script>

<?php
include(resource_path('views/layouts/admin_footer.php'));
?>
