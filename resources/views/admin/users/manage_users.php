<?php 
$custom_css = '<link rel="stylesheet" href="' . asset('assets/css/manage_users.css') . '">';
include(resource_path('views/layouts/admin_header.php'));
?>

<div class="admin-content-main">
    <h1 class="mb-4">User Management</h1>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo session('success'); ?>
        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo session('error'); ?>
        </div>
    <?php endif; ?>      <div class="admin-card card">
        <div class="admin-header card-header d-flex justify-content-between align-items-center">        <h2 class="card-title mb-0 fw-bold">All Users</h2>
            <a href="/admin/users/create_admin" class="admin-btn admin-btn-primary btn btn-primary btn-sm">
                <i class="fas fa-user-plus me-2"></i> Create Admin User
            </a>
        </div><div class="admin-search-container p-3 bg-light border-bottom">
            <form action="/admin/users/manage_users" method="GET" class="admin-search-form" id="userSearchForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search:</label>
                        <input 
                            type="text" 
                            id="search" 
                            name="search" 
                            class="form-control"
                            placeholder="Search by name or email"
                            value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>"
                        >
                    </div>
                    
                    <div class="col-md-3">
                        <label for="role" class="form-label">Role:</label>
                        <select id="role" name="role" class="form-select">
                            <option value="">All Roles</option>
                            <?php if(isset($roles) && count($roles) > 0): ?>
                                <?php foreach($roles as $role): ?>
                                    <option value="<?php echo htmlspecialchars($role->name); ?>" <?php echo (isset($role_filter) && $role_filter == $role->name) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($role->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status:</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" <?php echo (isset($status_filter) && $status_filter == 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo (isset($status_filter) && $status_filter == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            <option value="pending" <?php echo (isset($status_filter) && $status_filter == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        </select>
                    </div>                    <div class="col-md-3 d-flex align-items-end">
                        <div class="d-grid gap-2 d-md-flex w-100 justify-content-md-end">
                            <button type="submit" class="btn btn-sm btn-primary search-action-btn">
                                <i class="fas fa-search me-1"></i> Search
                            </button>                            <a href="/admin/users/manage_users" class="btn btn-sm btn-secondary search-action-btn">
                                <i class="fas fa-undo me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>          <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($users) && count($users) > 0): ?>
                        <?php foreach($users as $user): ?>
                            <tr>
                                <td data-title="ID"><?php echo $user->user_id; ?></td>
                                <td data-title="Name"><?php echo htmlspecialchars($user->name); ?></td>
                                <td data-title="Email"><?php echo htmlspecialchars($user->email); ?></td>
                                <td data-title="Role">
                                    <span class="badge bg-secondary">
                                        <?php echo isset($user->role_name) ? htmlspecialchars($user->role_name) : 'User'; ?>
                                    </span>
                                </td>
                                <td data-title="Status">
                                    <span class="badge <?php 
                                        echo match(strtolower($user->status ?? 'active')) {
                                            'active' => 'bg-success',
                                            'inactive' => 'bg-danger',
                                            'pending' => 'bg-warning',
                                            default => 'bg-secondary'
                                        }; 
                                    ?>">
                                        <?php echo ucfirst($user->status ?? 'Active'); ?>
                                    </span>
                                </td>                                <td data-title="Actions" class="text-end">
                                    <div class="btn-group">                                        <a href="/admin/users/view/<?php echo $user->user_id; ?>" class="btn btn-sm btn-info action-btn-custom" title="View User">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/users/edit/<?php echo $user->user_id; ?>" class="btn btn-sm btn-warning action-btn-custom" title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </a><button type="button" 
                                           class="btn btn-sm btn-danger action-btn-custom delete-user-btn" 
                                           title="Delete User"
                                           data-user-id="<?php echo $user->user_id; ?>"
                                           data-user-name="<?php echo htmlspecialchars($user->name); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                                        <p class="mb-3">No users found<?php echo isset($search) && !empty($search) ? ' matching your search criteria' : ''; ?></p>
                                        <?php if(isset($search) && !empty($search) || isset($role_filter) && !empty($role_filter) || isset($status_filter) && !empty($status_filter)): ?>                                            <a href="/admin/users/manage_users" class="btn btn-outline-secondary">
                                                <i class="fas fa-undo me-1"></i> Clear Filters
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>          <div class="card-footer bg-light">
            <?php if(isset($total_users) && isset($users)): ?>
                <div class="small text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Showing <?php echo count($users); ?> of <?php echo $total_users; ?> users
                    <?php if(isset($search) && !empty($search)): ?>
                        matching "<strong><?php echo htmlspecialchars($search); ?></strong>"
                    <?php endif; ?>
                    <?php if(isset($role_filter) && !empty($role_filter)): ?>
                        with role "<strong><?php echo htmlspecialchars($role_filter); ?></strong>"
                    <?php endif; ?>
                    <?php if(isset($status_filter) && !empty($status_filter)): ?>
                        with status "<strong><?php echo htmlspecialchars($status_filter); ?></strong>"
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
          <?php if(isset($users) && count($users) > 0 && isset($total_pages) && $total_pages > 1): ?>
            <?php
                // Cast to integers to avoid "string - int" errors
                $current_page = (int) $current_page;
                $total_pages = (int) $total_pages;
            ?>
            <nav aria-label="User pagination" class="mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted mb-0">Page <?php echo $current_page; ?> of <?php echo $total_pages; ?></p>
                    <ul class="pagination mb-0">
                        <?php if($current_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo my_url('/admin/users/manage_users?' . http_build_query(array_merge($_GET, ['page' => 1]))); ?>" aria-label="First page">
                                    <i class="fas fa-angle-double-left"></i>
                                    <span class="visually-hidden">First</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo my_url('/admin/users/manage_users?' . http_build_query(array_merge($_GET, ['page' => $current_page - 1]))); ?>" aria-label="Previous page">
                                    <i class="fas fa-angle-left"></i>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                            </li>                        <?php endif; ?>
                        
                        <?php
                            $startPage = max(1, $current_page - 2);
                            $endPage = min($total_pages, $startPage + 4);
                            if ($endPage - $startPage < 4) {
                                $startPage = max(1, $endPage - 4);
                            }
                        ?>
                        
                        <?php for($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                <a class="page-link" href="/admin/users/manage_users?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if($current_page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo my_url('/admin/users/manage_users?' . http_build_query(array_merge($_GET, ['page' => $current_page + 1]))); ?>" aria-label="Next page">
                                    <i class="fas fa-angle-right"></i>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo my_url('/admin/users/manage_users?' . http_build_query(array_merge($_GET, ['page' => $total_pages]))); ?>" aria-label="Last page">
                                    <i class="fas fa-angle-double-right"></i>
                                    <span class="visually-hidden">Last</span>                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        <?php endif; ?>    </div>
</div>

<script>
// Handle delete user modal
document.addEventListener('DOMContentLoaded', function() {
    const deleteUserName = document.getElementById('deleteUserName');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    let userIdToDelete = null;    // Add click handlers to delete buttons
    document.querySelectorAll('.delete-user-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            userIdToDelete = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            deleteUserName.textContent = userName;
            
            // Store current scroll position
            const currentScrollY = window.scrollY;
            
            // Create modal instance
            const modalElement = document.getElementById('deleteUserModal');
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });
            
            // Prevent body scrolling when modal opens
            modalElement.addEventListener('shown.bs.modal', function () {
                document.body.style.overflow = 'hidden';
                document.body.style.paddingRight = '17px'; // Prevent layout shift
            });
            
            // Restore body scrolling when modal closes
            modalElement.addEventListener('hidden.bs.modal', function () {
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                // Restore original scroll position
                window.scrollTo(0, currentScrollY);
            });
            
            // Show modal
            modal.show();
        });
    });

    // Handle confirm delete button click
    confirmDeleteBtn.addEventListener('click', function() {
        if (userIdToDelete) {
            // Close modal first
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteUserModal'));
            modal.hide();
            
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/users/delete/' + userIdToDelete;
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '<?php echo csrf_token(); ?>';
            form.appendChild(csrfInput);
            
            // Add DELETE method
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Add return_to parameter if there are URL parameters
            const currentUrl = new URL(window.location.href);
            const searchParams = currentUrl.searchParams;
            if (searchParams.toString()) {
                const returnInput = document.createElement('input');
                returnInput.type = 'hidden';
                returnInput.name = 'return_to';
                returnInput.value = '?' + searchParams.toString();
                form.appendChild(returnInput);
            }
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>

<?php
include(resource_path('views/layouts/admin_footer.php'));
?>
