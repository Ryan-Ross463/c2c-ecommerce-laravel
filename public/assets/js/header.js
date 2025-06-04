document.addEventListener('DOMContentLoaded', function() {
        const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
       
        const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
            toggle: false
        });
        
        navbarToggler.addEventListener('click', function() {
            bsCollapse.toggle();
        });
        
        document.addEventListener('click', function(event) {
            if (!navbarToggler.contains(event.target) && 
                !navbarCollapse.contains(event.target) && 
                navbarCollapse.classList.contains('show')) {
                bsCollapse.hide();
            }
        });
    }
});
