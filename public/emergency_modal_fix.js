// Emergency Modal Fix - Run this in browser console to unstuck modals
console.log('Running Emergency Modal Fix...');

// Function to force close all modals and remove overlays
function emergencyModalFix() {
    console.log('Attempting to fix modal overlay issues...');
    
    // 1. Remove all modal backdrops
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        console.log('Removing modal backdrop:', backdrop);
        backdrop.remove();
    });
    
    // 2. Hide all modals
    document.querySelectorAll('.modal').forEach(modal => {
        console.log('Hiding modal:', modal.id);
        modal.classList.remove('show');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        modal.removeAttribute('aria-modal');
    });
    
    // 3. Restore body scroll and remove modal classes
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    
    // 4. Remove any blocking overlays
    document.querySelectorAll('[style*="z-index"]').forEach(element => {
        const zIndex = parseInt(window.getComputedStyle(element).zIndex);
        if (zIndex > 1000) {
            console.log('Removing high z-index element:', element, 'z-index:', zIndex);
            element.style.display = 'none';
        }
    });
    
    // 5. Enable pointer events on all elements
    document.querySelectorAll('*').forEach(element => {
        if (element.style.pointerEvents === 'none') {
            element.style.pointerEvents = '';
        }
    });
    
    // 6. Clear any Bootstrap modal instances
    if (typeof bootstrap !== 'undefined') {
        document.querySelectorAll('.modal').forEach(modalEl => {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.dispose();
            }
        });
    }
    
    // 7. Reset any problematic CSS
    const style = document.createElement('style');
    style.textContent = `
        body { pointer-events: auto !important; }
        .modal-backdrop { display: none !important; }
        .modal { display: none !important; }
        * { pointer-events: auto !important; }
    `;
    document.head.appendChild(style);
    
    console.log('Emergency fix complete! Try clicking elements now.');
}

// Run the fix
emergencyModalFix();

// Add hotkey Ctrl+Shift+E to run emergency fix
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.shiftKey && e.key === 'E') {
        e.preventDefault();
        console.log('Emergency hotkey pressed - running modal fix...');
        emergencyModalFix();
    }
});

console.log('Emergency Modal Fix loaded. Press Ctrl+Shift+E to run the fix anytime.');
