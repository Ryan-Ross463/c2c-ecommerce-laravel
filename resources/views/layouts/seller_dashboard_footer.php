<?php

?>
                </div> 
            </div> 
        </div> 
    </div>
</main>

<footer id="main-footer" class="seller-dashboard-footer">
    <div class="container">
        <div class="footer-brand">
            <h5><i class="fas fa-shopping-cart me-2"></i>C2C E-commerce</h5>
            <p class="text-muted mb-0">C2C E-commerce. All rights reserved.</p>
        </div>
        
        <div class="row g-3">
            <div class="col-lg-6 col-md-6">
                <div class="footer-section">
                    <h5 class="footer-heading">Quick Links</h5>
                    <ul class="footer-nav">
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/'); ?>" class="footer-link">Home</a>
                        </li>
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/products'); ?>" class="footer-link">Products</a>
                        </li>
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/categories'); ?>" class="footer-link">Categories</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="footer-section">
                    <h5 class="footer-heading">Seller Resources</h5>
                    <ul class="footer-nav">
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/seller/products'); ?>" class="footer-link">My Products</a>
                        </li>
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/seller/orders'); ?>" class="footer-link">Orders</a>
                        </li>
                        <li class="footer-nav-item">
                            <a href="<?php echo my_url('/seller/profile'); ?>" class="footer-link">Seller Profile</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    const selectAllCheckbox = document.getElementById('select-all-items');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            
            updateBulkActionsState();
        });
        
        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActionsState);
        });
    }
    
    function updateBulkActionsState() {
        const selectedCount = document.querySelectorAll('.item-checkbox:checked').length;
        const bulkActionsBtn = document.querySelector('.bulk-actions-btn');
        
        if (bulkActionsBtn) {
            if (selectedCount > 0) {
                bulkActionsBtn.classList.remove('disabled');
                bulkActionsBtn.querySelector('.selected-count').textContent = selectedCount;
            } else {
                bulkActionsBtn.classList.add('disabled');
                bulkActionsBtn.querySelector('.selected-count').textContent = '0';
            }
        }
    }
    
    const imageInputs = document.querySelectorAll('.product-image-upload');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const previewContainer = document.querySelector(this.dataset.previewTarget);
            if (!previewContainer) return;
            
            if (!this.dataset.multiple || this.dataset.multiple === 'false') {
                previewContainer.innerHTML = '';
            }
            
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                if (!file.type.startsWith('image/')) continue;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.classList.add('preview-item', 'position-relative', 'me-2', 'mb-2');
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.height = '100px';
                    
                    previewItem.appendChild(img);
                    previewContainer.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            }
        });
    });
    
    if (typeof ClassicEditor !== 'undefined') {
        const editorElements = document.querySelectorAll('.rich-editor');
        editorElements.forEach(element => {
            ClassicEditor
                .create(element)
                .catch(error => {
                    console.error('Error initializing editor:', error);
                });
        });
    }
});
</script>

</body>
</html>
