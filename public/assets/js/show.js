window.showToast = function(message, type = 'info') {
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type} animate-slide-up`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="${getIconForToastType(type)}"></i>
            <span>${message}</span>
        </div>
        <button class="toast-close">&times;</button>
    `;
    
    toastContainer.appendChild(toast);
    
    const closeBtn = toast.querySelector('.toast-close');
    closeBtn.addEventListener('click', () => {
        toast.classList.add('toast-hiding');
        setTimeout(() => {
            toast.remove();
        }, 300);
    });
    
    setTimeout(() => {
        toast.classList.add('toast-hiding');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
};

function getIconForToastType(type) {
    switch(type) {
        case 'success': return 'fas fa-check-circle';
        case 'warning': return 'fas fa-exclamation-triangle';
        case 'error': return 'fas fa-times-circle';
        case 'info': 
        default: return 'fas fa-info-circle';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Product detail page script loaded');
    
    const addToCartButtons = document.querySelectorAll('.btn-cart, .add-to-cart-btn');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const quantity = document.getElementById('quantity') ? document.getElementById('quantity').value : 1;
            
            console.log(`Adding product ${productId} to cart, quantity: ${quantity}`);
            
            showToast('Product added to cart', 'success');
        });
    });
      const wishlistButtons = document.querySelectorAll('.btn-wishlist');
    
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const isActive = this.classList.contains('active');
            
            this.classList.toggle('active');
            
            if (isActive) {
                console.log(`Removing product ${productId} from wishlist`);
                showToast('Removed from wishlist', 'info');
            } else {
                console.log(`Adding product ${productId} to wishlist`);
                showToast('Added to wishlist', 'success');
            }
        });
    });
    
    const productThumbnails = document.querySelectorAll('.product-thumbnail');
    const productMainImage = document.querySelector('.product-main-image img');
    
    if (productThumbnails.length && productMainImage) {
        productThumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                productMainImage.src = thumbnail.querySelector('img').src;
                
                productThumbnails.forEach(t => t.classList.remove('active'));
                thumbnail.classList.add('active');
            });
        });
    }    const qtyInput = document.getElementById('productQty');
    if (qtyInput) {
        const decrementBtn = document.getElementById('decrementQty');
        const incrementBtn = document.getElementById('incrementQty');
        const maxQty = window.productData ? window.productData.maxStock : parseInt(qtyInput.getAttribute('max') || 99);
        
        if (decrementBtn) {
            decrementBtn.addEventListener('click', function() {
                let currentQty = parseInt(qtyInput.value);
                if (currentQty > 1) {
                    qtyInput.value = currentQty - 1;
                }
            });
        }
        
        if (incrementBtn) {
            incrementBtn.addEventListener('click', function() {
                let currentQty = parseInt(qtyInput.value);
                if (currentQty < maxQty) {
                    qtyInput.value = currentQty + 1;
                }
            });
        }
    }
    
    const addToCartBtn = document.getElementById('addToCartBtn');
    if (addToCartBtn && qtyInput) {
        addToCartBtn.addEventListener('click', function() {
            const productId = window.productData ? window.productData.productId : this.dataset.productId;
            const qty = parseInt(qtyInput.value);
            
            showToast(`Added ${qty} item(s) to cart!`, 'success');
            
            console.log(`Adding product ${productId} to cart, quantity: ${qty}`);
        });
    }
    
    const saveForLaterBtn = document.getElementById('saveForLaterBtn');
    if (saveForLaterBtn) {
        saveForLaterBtn.addEventListener('click', function() {
            this.classList.toggle('active');
            const icon = this.querySelector('i');
            if (this.classList.contains('active')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                icon.style.color = '#ef4444';
                showToast('Product added to wishlist!', 'success');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                icon.style.color = '';
                showToast('Product removed from wishlist!', 'info');
            }
        });
    }
});
