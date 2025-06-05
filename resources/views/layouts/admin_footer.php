</div>    <footer class="admin-footer">
        <div class="admin-footer-content">
            <div class="admin-footer-copyright">
                &copy; <?php echo date('Y'); ?> C2C E-commerce Platform. All rights reserved.
            </div>
            <div class="admin-footer-links">
                <a href="<?php echo my_url('/admin'); ?>">Dashboard</a>
                <span class="footer-separator">|</span>
                <a href="<?php echo my_url('/admin/reports'); ?>">Reports</a>
                <span class="footer-separator">|</span>
                <a href="<?php echo my_url('/admin/profile'); ?>">Profile</a>
            </div>
        </div>
    </footer>

    <style>
        .admin-footer {
            background-color: #34495e !important;
            color: #ecf0f1 !important;
            padding: 1rem 0 !important;
            margin-top: auto !important;
            border-top: 1px solid #2c3e50 !important;
        }

        .admin-footer-content {
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 0 1rem !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            flex-wrap: wrap !important;
        }

        .admin-footer-copyright {
            font-size: 0.9rem !important;
            color: #bdc3c7 !important;
        }

        .admin-footer-links {
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
        }

        .admin-footer-links a {
            color: #ecf0f1 !important;
            text-decoration: none !important;
            font-size: 0.9rem !important;
            transition: color 0.3s ease !important;
        }

        .admin-footer-links a:hover {
            color: #3498db !important;
        }

        .footer-separator {
            color: #7f8c8d !important;
            font-size: 0.8rem !important;
        }

        /* Mobile responsive footer */
        @media (max-width: 768px) {
            .admin-footer-content {
                flex-direction: column !important;
                text-align: center !important;
                gap: 0.5rem !important;
            }

            .admin-footer-links {
                font-size: 0.8rem !important;
            }
        }

        /* Ensure body has proper layout for sticky footer */
        body.admin-body {
            display: flex !important;
            flex-direction: column !important;
            min-height: 100vh !important;
        }

        .admin-main-content {
            flex: 1 !important;
        }
    </style>    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<script>
(function() {
    let emergencyFixApplied = false;
    
    function emergencyModalFix() {
        if (emergencyFixApplied) return;
        emergencyFixApplied = true;
        
        console.log('Emergency Modal Fix: Cleaning up stuck modals...');
        
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.remove();
        });
        
        document.querySelectorAll('.modal').forEach(modal => {
            modal.classList.remove('show');
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            modal.removeAttribute('aria-modal');
            
            if (typeof bootstrap !== 'undefined') {
                const instance = bootstrap.Modal.getInstance(modal);
                if (instance) {
                    instance.dispose();
                }
            }
        });
        
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        document.querySelectorAll('*').forEach(element => {
            if (element.style.pointerEvents === 'none') {
                element.style.pointerEvents = '';
            }
        });
        
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
    
    function detectStuckModal() {
        const backdrop = document.querySelector('.modal-backdrop');
        const openModal = document.querySelector('.modal.show');
        const bodyModalOpen = document.body.classList.contains('modal-open');
        
        if ((backdrop && !openModal) || (bodyModalOpen && !openModal)) {
            console.warn('Stuck modal detected - applying emergency fix...');
            emergencyModalFix();
        }
    }
    
    setInterval(detectStuckModal, 2000);
    
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'F') {
            e.preventDefault();
            console.log('Emergency modal fix hotkey pressed (Ctrl+Shift+F)');
            emergencyModalFix();
        }
    });
    
    document.addEventListener('dblclick', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            console.log('Double-click on backdrop detected - applying emergency fix...');
            emergencyModalFix();
        }
    });
    
    console.log('Emergency Modal Fix loaded. Use Ctrl+Shift+F or double-click modal backdrop to force close stuck modals.');
})();
</script>

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
