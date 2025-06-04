</div>     <footer class="admin-footer">        <div class="admin-footer-content">
            <div class="admin-footer-copyright">
                 C2C E-commerce Platform.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        
            const dropdownToggles = document.querySelectorAll('.admin-dropdown-toggle');
            
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const dropdown = this.closest('.admin-dropdown');
                    dropdown.classList.toggle('active');
                    
                    dropdownToggles.forEach(otherToggle => {
                        if (otherToggle !== toggle) {
                            otherToggle.closest('.admin-dropdown').classList.remove('active');
                        }
                    });
                });
            });
            
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.admin-dropdown')) {
                    dropdownToggles.forEach(toggle => {
                        toggle.closest('.admin-dropdown').classList.remove('active');
                    });
                }
            });
        });
    </script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<!-- Emergency Modal Fix Script -->
<script>
// Emergency Modal Fix - Global handler for stuck modals
(function() {
    let emergencyFixApplied = false;
    
    function emergencyModalFix() {
        if (emergencyFixApplied) return;
        emergencyFixApplied = true;
        
        console.log('Emergency Modal Fix: Cleaning up stuck modals...');
        
        // Remove all modal backdrops
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.remove();
        });
        
        // Hide all modals properly
        document.querySelectorAll('.modal').forEach(modal => {
            modal.classList.remove('show');
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            modal.removeAttribute('aria-modal');
            
            // Dispose Bootstrap modal instances
            if (typeof bootstrap !== 'undefined') {
                const instance = bootstrap.Modal.getInstance(modal);
                if (instance) {
                    instance.dispose();
                }
            }
        });
        
        // Restore body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        // Force enable all interactions
        document.querySelectorAll('*').forEach(element => {
            if (element.style.pointerEvents === 'none') {
                element.style.pointerEvents = '';
            }
        });
        
        // Add emergency CSS
        const emergencyStyle = document.createElement('style');
        emergencyStyle.id = 'emergency-modal-fix';
        emergencyStyle.textContent = `
            body { pointer-events: auto !important; }
            .modal-backdrop { display: none !important; }
            .modal.show { display: none !important; }
            * { pointer-events: auto !important; }
        `;
        document.head.appendChild(emergencyStyle);
        
        setTimeout(() => {
            emergencyFixApplied = false;
            if (document.getElementById('emergency-modal-fix')) {
                document.getElementById('emergency-modal-fix').remove();
            }
        }, 5000);
        
        console.log('Emergency Modal Fix: Complete!');
    }
    
    // Auto-detect stuck modals
    function detectStuckModal() {
        const backdrop = document.querySelector('.modal-backdrop');
        const openModal = document.querySelector('.modal.show');
        const bodyModalOpen = document.body.classList.contains('modal-open');
        
        if ((backdrop && !openModal) || (bodyModalOpen && !openModal)) {
            console.warn('Stuck modal detected - applying emergency fix...');
            emergencyModalFix();
        }
    }
    
    // Run detection periodically
    setInterval(detectStuckModal, 2000);
    
    // Add emergency hotkey (Ctrl+Shift+F)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'F') {
            e.preventDefault();
            console.log('Emergency modal fix hotkey pressed (Ctrl+Shift+F)');
            emergencyModalFix();
        }
    });
    
    // Add double-click on backdrop to force close
    document.addEventListener('dblclick', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            console.log('Double-click on backdrop detected - applying emergency fix...');
            emergencyModalFix();
        }
    });
    
    console.log('Emergency Modal Fix loaded. Use Ctrl+Shift+F or double-click modal backdrop to force close stuck modals.');
})();
</script>

<!-- Delete User Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteUserModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirm Delete User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-user-times fa-3x text-danger mb-3"></i>
                    <h6>Are you sure you want to delete this user?</h6>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>User:</strong> <span id="deleteUserName"></span>
                </div>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. All user data will be permanently deleted.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-1"></i> Delete User
                </button>
            </div>
        </div>
    </div>
</div>
     
</body>
</html>
