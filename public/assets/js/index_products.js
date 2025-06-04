document.addEventListener('DOMContentLoaded', function() {
    console.log('Product index page JS loaded');
    
    initViewToggle();
    
    initWishlistButtons();
    
    initAddToCartButtons();
});

function initViewToggle() {
    const gridViewBtn = document.querySelector('.view-grid');
    const listViewBtn = document.querySelector('.view-list');
    const productsGrid = document.querySelector('.products-grid');
    const productsList = document.querySelector('.products-list');
    
    if (gridViewBtn && listViewBtn && productsGrid && productsList) {
        gridViewBtn.addEventListener('click', function() {
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');
            productsGrid.classList.add('active');
            productsList.classList.remove('active');
            localStorage.setItem('productView', 'grid');
        });
        
        listViewBtn.addEventListener('click', function() {
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');
            productsList.classList.add('active');
            productsGrid.classList.remove('active');
            localStorage.setItem('productView', 'list');
        });
        
        const savedView = localStorage.getItem('productView');
        if (savedView === 'list') {
            listViewBtn.click();
        } else {
            gridViewBtn.click();
        }
    }
}

function initWishlistButtons() {
    const wishlistButtons = document.querySelectorAll('.btn-wishlist');
    
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            
            this.classList.toggle('active');
            
            console.log('Toggling wishlist for product ID: ' + productId);
        });
    });
}

function initAddToCartButtons() {
    const addToCartButtons = document.querySelectorAll('.btn-add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            
            this.classList.add('loading');
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
            
            console.log('Adding product ID to cart: ' + productId);
            
            setTimeout(() => {
               
                this.classList.remove('loading');
                this.innerHTML = originalText;
                
                const toast = document.createElement('div');
                toast.className = 'toast show position-fixed bottom-0 end-0 m-3';
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');
                toast.innerHTML = `
                    <div class="toast-header">
                        <strong class="me-auto">Success</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Product added to cart!
                    </div>
                `;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }, 800);
        });
    });
}
