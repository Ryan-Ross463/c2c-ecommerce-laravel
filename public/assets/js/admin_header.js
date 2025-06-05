document.addEventListener('DOMContentLoaded', function() {
    // Menu toggle functionality
    const menuToggle = document.getElementById('adminMenuToggle');
    const adminNav = document.getElementById('adminNav');
    
    if (menuToggle && adminNav) {
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            adminNav.classList.toggle('show');
            console.log("Menu toggled:", adminNav.classList.contains('show'));
        });
    }

    // Dropdown functionality
    const dropdownToggles = document.querySelectorAll('.admin-dropdown-toggle');
    
    dropdownToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const parentDropdown = this.closest('.admin-dropdown');
            const isCurrentlyActive = parentDropdown.classList.contains('active');
            
            // Close all other dropdowns
            document.querySelectorAll('.admin-dropdown').forEach(function(dropdown) {
                if (dropdown !== parentDropdown) {
                    dropdown.classList.remove('active');
                }
            });
            
            // Toggle current dropdown
            if (!isCurrentlyActive) {
                parentDropdown.classList.add('active');
            } else {
                parentDropdown.classList.remove('active');
            }
        });
    });

    // Close dropdowns and mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        // Don't close if clicking on modal-related elements
        if (e.target.closest('.modal') || 
            e.target.closest('[data-bs-toggle="modal"]') || 
            e.target.closest('.delete-user-btn') || 
            document.querySelector('.modal.show')) {
            return;
        }
        
        // Close mobile menu if clicking outside nav area
        if (!e.target.closest('.admin-nav') && !e.target.closest('#adminMenuToggle')) {
            if (window.innerWidth <= 992) {
                adminNav.classList.remove('show');
            }
        }
        
        // Close all dropdowns if clicking outside
        if (!e.target.closest('.admin-dropdown')) {
            document.querySelectorAll('.admin-dropdown').forEach(function(dropdown) {
                dropdown.classList.remove('active');
            });
        }
    });

    // Handle responsive behavior
    function handleResponsive() {
        if (window.innerWidth > 992) {
            adminNav.classList.remove('show');
            document.querySelectorAll('.admin-dropdown').forEach(function(dropdown) {
                dropdown.classList.remove('active');
            });
        }
    }
    
    // Modal overlay management
    function forceRemoveOverlay() {
        // Don't interfere if modals are open
        if (document.querySelector('.modal.show') || 
            document.querySelector('.modal-backdrop') || 
            document.body.classList.contains('modal-open')) {
            return;
        }
    }
    
    // Event listeners
    window.addEventListener('resize', handleResponsive);
    window.addEventListener('scroll', forceRemoveOverlay);
    
    // Initialize
    forceRemoveOverlay();
    
    // Cleanup interval
    setInterval(function() {
        try {
            forceRemoveOverlay();
        } catch (e) {
            console.warn('forceRemoveOverlay error:', e);
        }
    }, 3000);

    console.log('Admin header JavaScript loaded successfully');
});