document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('adminMenuToggle');
    const adminNav = document.getElementById('adminNav');
    
    menuToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        adminNav.classList.toggle('show');
        
        if (window.innerWidth <= 992) {
            document.body.classList.toggle('menu-active');
        }
        
        console.log("Menu toggled:", adminNav.classList.contains('show'));
    });

    const dropdowns = document.querySelectorAll('.admin-dropdown');
    
    dropdowns.forEach(dropdown => {
        const toggleBtn = dropdown.querySelector('.admin-dropdown-toggle');
        const container = dropdown.querySelector('.admin-dropdown-container');
        
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); 
            
            container.classList.toggle('show');
            dropdown.classList.toggle('show');
            
            if (window.innerWidth <= 992) {
                dropdowns.forEach(otherDropdown => {
                    if (otherDropdown !== dropdown && container.classList.contains('show')) {
                        const otherContainer = otherDropdown.querySelector('.admin-dropdown-container');
                        otherContainer.classList.remove('show');
                        otherDropdown.classList.remove('show');
                    }
                });
            }
        });
    });

    document.addEventListener('click', function(e) {
      
        if (e.target.closest('.modal') || e.target.closest('[data-bs-toggle="modal"]') || 
            e.target.closest('.delete-user-btn') || document.querySelector('.modal.show')) {
            return;
        }
        
        if (!e.target.closest('.admin-nav') && !e.target.closest('#adminMenuToggle')) {
            if (window.innerWidth <= 992) {
                adminNav.classList.remove('show');
                document.body.classList.remove('menu-active');
            }
            
            document.querySelectorAll('.admin-dropdown-container').forEach(container => {
                container.classList.remove('show');
            });
            document.querySelectorAll('.admin-dropdown').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
        
        else if (!e.target.closest('.admin-dropdown')) {
            document.querySelectorAll('.admin-dropdown-container').forEach(container => {
                container.classList.remove('show');
            });
            document.querySelectorAll('.admin-dropdown').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });

    function handleResponsive() {
        if (window.innerWidth > 992) {
            adminNav.classList.remove('show');
            document.body.classList.remove('menu-active');
            document.querySelectorAll('.admin-dropdown-container').forEach(container => {
                container.classList.remove('show');
            });
            document.querySelectorAll('.admin-dropdown').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    }
    
    function forceRemoveOverlay() {
       
        if (document.querySelector('.modal.show') || document.querySelector('.modal-backdrop')) {
            return;
        }
       
        if (!document.body.classList.contains('modal-open')) {
            document.body.classList.remove('menu-active');
        }
    }
    
    forceRemoveOverlay();
    setInterval(function() {
        try {
            forceRemoveOverlay();
        } catch (e) {
            console.warn('forceRemoveOverlay error:', e);
        }
    }, 3000); 

    window.addEventListener('resize', handleResponsive);
  
    window.addEventListener('scroll', function() {
        if (!document.querySelector('.modal.show') && !document.querySelector('.modal-backdrop') && !document.body.classList.contains('modal-open')) {
            forceRemoveOverlay();
        }
    });
    
    window.addEventListener('click', function(e) {
        if (e.target.closest('.modal') || 
            e.target.closest('.modal-backdrop') || 
            e.target.closest('[data-bs-toggle="modal"]') || 
            e.target.closest('[data-bs-target]') ||
            e.target.closest('.delete-user-btn') ||
            e.target.closest('.quick-edit-btn') ||
            e.target.closest('#quickEditModal') ||
            e.target.closest('#deleteProductModal') ||
            e.target.closest('#deleteUserModal') ||
            document.querySelector('.modal.show') ||
            document.querySelector('.modal-backdrop') ||
            document.body.classList.contains('modal-open')) {
            return;
        }
        forceRemoveOverlay();
    });
});