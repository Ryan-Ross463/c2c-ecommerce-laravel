document.addEventListener('DOMContentLoaded', () => {
    initPriceRangeSlider();
    initMobileFilterToggle();
    initFilterAccordion();
    initAddToCartButtons();
    syncFilterValues();
    updateAppliedFiltersCount();
    handleResponsiveFilterPanel();
    
    window.addEventListener('resize', handleResponsiveFilterPanel);
});



function initFilterAccordion() {
   
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded! Loading it dynamically...');
        const bootstrapScript = document.createElement('script');
        bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
        bootstrapScript.onload = initializeCollapseComponents;
        document.head.appendChild(bootstrapScript);
    } else {
        console.log('Bootstrap is loaded');
        initializeCollapseComponents();
    }
    
    function initializeCollapseComponents() {
        var filterHeaders = document.querySelectorAll('.filter-header[data-bs-toggle="collapse"]');
        console.log('Found filter headers:', filterHeaders.length);
        
        filterHeaders.forEach(function(header) {
            var targetId = header.getAttribute('data-bs-target');
            var targetElement = document.querySelector(targetId);
            
            if (!targetElement) {
                console.error('Target element not found for:', targetId);
                return;
            }
            
            var bsCollapse = new bootstrap.Collapse(targetElement, {
                toggle: false
            });
            
            if (!header.querySelector('i.fas')) {
                const icon = document.createElement('i');
                icon.classList.add('fas', 'fa-chevron-down');
                header.appendChild(icon);
            }
            
            const icon = header.querySelector('i');
            if (targetElement.classList.contains('show')) {
                header.setAttribute('aria-expanded', 'true');
            } else {
                header.setAttribute('aria-expanded', 'false');
                if (icon) icon.style.transform = 'rotate(-90deg)';
            }
            
            header.addEventListener('click', function(e) {
                e.preventDefault();
                
                const isExpanded = header.getAttribute('aria-expanded') === 'true';
                header.setAttribute('aria-expanded', !isExpanded);
                
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.transform = isExpanded ? 'rotate(-90deg)' : '';
                }
                
                bsCollapse.toggle();
            });
        });
    }
}

function initMobileFilterToggle() {
    const filterToggle = document.getElementById('mobile-filter-toggle');
    const filterOffcanvas = document.querySelector('.filter-offcanvas');
    const filterBackdrop = document.querySelector('.filter-backdrop');
    const filterClose = document.querySelector('.filter-close');
    
    if (!filterToggle || !filterOffcanvas || !filterBackdrop) {
        console.error('Mobile filter elements not found:', {
            filterToggle: !!filterToggle,
            filterOffcanvas: !!filterOffcanvas,
            filterBackdrop: !!filterBackdrop
        });
        return;
    }
    
    console.log('Initializing mobile filter toggle');
    
    filterToggle.addEventListener('click', function() {
        console.log('Filter toggle clicked');
        filterOffcanvas.style.display = 'flex';
        setTimeout(() => {
            filterOffcanvas.classList.add('show');
            filterBackdrop.style.display = 'block';
            setTimeout(() => {
                filterBackdrop.classList.add('show');
            }, 10);
            document.body.classList.add('filter-open');
            document.body.style.overflow = 'hidden';
        }, 10);
    });
    
    if (filterClose) {
        filterClose.addEventListener('click', function() {
            console.log('Filter close clicked');
            closeFilterPanel();
        });
    }
    
    filterBackdrop.addEventListener('click', function() {
        console.log('Filter backdrop clicked');
        closeFilterPanel();
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && filterOffcanvas.classList.contains('show')) {
            console.log('Escape key pressed');
            closeFilterPanel();
        }
    });
    
    function closeFilterPanel() {
        const filterOffcanvas = document.querySelector('.filter-offcanvas');
        const filterBackdrop = document.querySelector('.filter-backdrop');
        
        if (filterOffcanvas) {
            filterOffcanvas.classList.remove('show');

            setTimeout(() => {
                if (!filterOffcanvas.classList.contains('show')) {
                    filterOffcanvas.style.display = 'none';
                }
            }, 300);
        }
        
        if (filterBackdrop) {
            filterBackdrop.classList.remove('show');
            
            setTimeout(() => {
                if (!filterBackdrop.classList.contains('show')) {
                    filterBackdrop.style.display = 'none';
                }
            }, 300);
        }
        
        document.body.classList.remove('filter-open');
        document.body.style.overflow = ''; 
    }
}

function handleResponsiveFilterPanel() {
    const filterOffcanvas = document.querySelector('.filter-offcanvas');
    const filterBackdrop = document.querySelector('.filter-backdrop');
    
    if (window.innerWidth >= 992) {
        if (filterOffcanvas && filterOffcanvas.classList.contains('show')) {
            filterOffcanvas.classList.remove('show');
            if (filterBackdrop) {
                filterBackdrop.classList.remove('show');
            }
            document.body.classList.remove('filter-open');
            document.body.style.overflow = '';
        }
    }
    
    if (window.innerWidth < 992) {
       
        const mobileFilterToggle = document.getElementById('mobile-filter-toggle');
        if (mobileFilterToggle) {
            mobileFilterToggle.style.display = 'flex';
        }
    }
}

function initPriceRangeSlider() {
    const priceSlider = document.getElementById('price_range');
    const minPriceInput = document.getElementById('min_price');
    const maxPriceInput = document.getElementById('max_price');
    
    if (priceSlider && maxPriceInput) {
        priceSlider.addEventListener('input', function() {
            maxPriceInput.value = this.value;
        });
        
        if (maxPriceInput) {
            maxPriceInput.addEventListener('input', function() {
                priceSlider.value = this.value;
            });
        }
    }
}

function initWishlistButtons() {
    const wishlistBtns = document.querySelectorAll('.btn-wishlist, .product-wishlist');
    
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const icon = this.querySelector('i');
            if (icon) {
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    icon.style.color = '#ff6b6b';
                    showToast('Added to wishlist!');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    icon.style.color = '';
                    showToast('Removed from wishlist');
                }
            }
            
            const productId = this.getAttribute('data-product-id');
            console.log(`Toggled wishlist for product ID: ${productId}`);
        });
    });
}

function initAddToCartButtons() {
    const addToCartBtns = document.querySelectorAll('.add-to-cart, .btn-cart, .product-actions-btn');
    
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            let productId = this.getAttribute('data-product-id');
            
            if (!productId) {
                const productCard = this.closest('.product-card');
                if (productCard) {
                    const productLink = productCard.querySelector('a[href*="/products/"]');
                    if (productLink) {
                        productId = productLink.href.split('/').pop();
                    }
                }
            }
            
            if (productId) {
               
                const qtyInput = document.getElementById('productQty');
                const quantity = qtyInput ? parseInt(qtyInput.value) : 1;
                
                console.log(`Adding product ID: ${productId}, quantity: ${quantity} to cart`);
                
                showToast(`Added ${quantity} item(s) to cart!`);
                
                this.classList.add('btn-success');
                setTimeout(() => {
                    this.classList.remove('btn-success');
                }, 1000);
            }
        });
    });
}

function syncFilterValues() {
    
    const searchField = document.getElementById('search');
    const sidePanelSearchField = document.getElementById('side-search');
    
    if (searchField && sidePanelSearchField) {
        searchField.addEventListener('input', function() {
            sidePanelSearchField.value = this.value;
        });
        
        sidePanelSearchField.addEventListener('input', function() {
            searchField.value = this.value;
        });
    }
    
    const minPriceInput = document.getElementById('min_price');
    const maxPriceInput = document.getElementById('max_price');
    const sidePanelMinPrice = document.getElementById('min_price_side');
    const sidePanelMaxPrice = document.getElementById('max_price_side');
    
    if (minPriceInput && sidePanelMinPrice) {
        minPriceInput.addEventListener('input', function() {
            sidePanelMinPrice.value = this.value;
        });
        
        sidePanelMinPrice.addEventListener('input', function() {
            minPriceInput.value = this.value;
        });
    }
    
    if (maxPriceInput && sidePanelMaxPrice) {
        maxPriceInput.addEventListener('input', function() {
            sidePanelMaxPrice.value = this.value;
        });
        
        sidePanelMaxPrice.addEventListener('input', function() {
            maxPriceInput.value = this.value;
        });
    }
    
    const priceRangeSlider = document.getElementById('price_range');
    const sidePanelPriceRangeSlider = document.getElementById('price_range_side');
    
    if (priceRangeSlider && sidePanelPriceRangeSlider) {
        priceRangeSlider.addEventListener('input', function() {
            sidePanelPriceRangeSlider.value = this.value;
            if (maxPriceInput) maxPriceInput.value = this.value;
            if (sidePanelMaxPrice) sidePanelMaxPrice.value = this.value;
        });
        
        sidePanelPriceRangeSlider.addEventListener('input', function() {
            priceRangeSlider.value = this.value;
            if (sidePanelMaxPrice) sidePanelMaxPrice.value = this.value;
            if (maxPriceInput) maxPriceInput.value = this.value;
        });
    }
}

function updateAppliedFiltersCount() {
    const params = new URLSearchParams(window.location.search);
    let count = 0;
    
    if (params.has('search') && params.get('search')) count++;
    if (params.has('category') && params.get('category')) count++;
    if (params.has('min_price') && params.get('min_price')) count++;
    if (params.has('max_price') && params.get('max_price')) count++;
    if (params.has('condition') && params.get('condition')) count++;
    if (params.has('in_stock') && params.get('in_stock')) count++;
    if (params.has('sort') && params.get('sort') !== 'newest') count++;
    
    const filterCountElement = document.getElementById('filterCount');
    if (filterCountElement) {
        filterCountElement.textContent = count;
        filterCountElement.style.display = count > 0 ? 'inline-block' : 'none';
    }
}

function showToast(message) {
    
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    const toastEl = document.createElement('div');
    toastEl.className = 'toast show';
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    
    toastEl.innerHTML = `
        <div class="toast-header">
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;
    
    toastContainer.appendChild(toastEl);
    
    const closeButton = toastEl.querySelector('.btn-close');
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            toastEl.remove();
        });
    }

    setTimeout(() => {
        toastEl.remove();
    }, 3000);
}

function my_url(path = '') {
    const baseUrl = window.location.origin + '/c2c_ecommerce/C2C_ecommerce_laravel';
    return baseUrl + (path ? '/' + path.replace(/^\//, '') : '');
}

let resizeTimer;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
        handleResponsiveFilterPanel();
    }, 250); 
});
