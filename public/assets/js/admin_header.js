document.addEventListener('DOMContentLoaded', function() {
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

    const dropdownToggles = document.querySelectorAll('.admin-dropdown-toggle');
    
    dropdownToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const parentDropdown = this.closest('.admin-dropdown');
            const isCurrentlyActive = parentDropdown.classList.contains('active');
            
            document.querySelectorAll('.admin-dropdown').forEach(function(dropdown) {
                if (dropdown !== parentDropdown) {
                    dropdown.classList.remove('active');
                }
            });
            
            if (!isCurrentlyActive) {
                parentDropdown.classList.add('active');
            } else {
                parentDropdown.classList.remove('active');
            }
        });
    });

    document.addEventListener('click', function(e) {
       
        if (e.target.closest('.modal') || 
            e.target.closest('[data-bs-toggle="modal"]') || 
            e.target.closest('.delete-user-btn') || 
            document.querySelector('.modal.show')) {
            return;
        }
        
        if (!e.target.closest('.admin-nav') && !e.target.closest('#adminMenuToggle')) {
            if (window.innerWidth <= 992) {
                adminNav.classList.remove('show');
            }
        }
        
        if (!e.target.closest('.admin-dropdown')) {
            document.querySelectorAll('.admin-dropdown').forEach(function(dropdown) {
                dropdown.classList.remove('active');
            });
        }
    });

    function handleResponsive() {
        if (window.innerWidth > 992) {
            adminNav.classList.remove('show');
            document.querySelectorAll('.admin-dropdown').forEach(function(dropdown) {
                dropdown.classList.remove('active');
            });
        }
    }
    
    function forceRemoveOverlay() {
        if (document.querySelector('.modal.show') || 
            document.querySelector('.modal-backdrop') || 
            document.body.classList.contains('modal-open')) {
            return;
        }
    }
    
    window.addEventListener('resize', handleResponsive);
    window.addEventListener('scroll', forceRemoveOverlay);
    
    forceRemoveOverlay();
    
    setInterval(function() {
        try {
            forceRemoveOverlay();
        } catch (e) {
            console.warn('forceRemoveOverlay error:', e);
        }
    }, 3000);

    console.log('Admin header JavaScript loaded successfully');
});