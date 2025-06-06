document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('category');
    const statusFilter = document.getElementById('status');
    const searchInput = document.getElementById('search');
    const searchBtn = document.getElementById('search-btn');
    
    function applyFilters() {
        const url = new URL(window.location.href);
        
        if (categoryFilter.value !== '0') {
            url.searchParams.set('category', categoryFilter.value);
        } else {
            url.searchParams.delete('category');
        }
        
        if (statusFilter.value) {
            url.searchParams.set('status', statusFilter.value);
        } else {
            url.searchParams.delete('status');
        }
        
        if (searchInput.value) {
            url.searchParams.set('search', searchInput.value);
        } else {
            url.searchParams.delete('search');
        }
        
        window.location.href = url.toString();
    }
    
    if (categoryFilter) categoryFilter.addEventListener('change', applyFilters);
    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    }
    
    const deleteForm = document.getElementById('product-delete-form');
    if (deleteForm) {
        document.querySelectorAll('.delete-product-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.id;
                const productName = this.dataset.name;
                
                const modal = document.getElementById('deleteProductModal');
                if (modal) {
                    const modalInstance = new bootstrap.Modal(modal);
                    modal.querySelector('.product-name').textContent = productName;
                    modal.querySelector('#confirm-delete-btn').onclick = function() {
                        deleteForm.action = deleteForm.dataset.baseurl.replace('__ID__', productId);
                        deleteForm.submit();
                    };
                    modalInstance.show();
                }
            });
        });
    }
    
    const selectAllItems = document.getElementById('select-all-items');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const bulkActionsBtn = document.querySelector('.bulk-actions-btn');
    const selectedCountSpan = document.querySelector('.selected-count');
    
    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.item-checkbox:checked').length;
        
        if (selectedCountSpan) {
            selectedCountSpan.textContent = selectedCount > 0 ? `(${selectedCount})` : '0';
        }
        
        if (bulkActionsBtn) {
            if (selectedCount > 0) {
                bulkActionsBtn.classList.remove('disabled');
            } else {
                bulkActionsBtn.classList.add('disabled');
            }
        }
    }
    
    if (selectAllItems) {
        selectAllItems.addEventListener('change', function() {
            const isChecked = this.checked;
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateSelectedCount();
        });
    }
    
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            
            if (!this.checked && selectAllItems) {
                selectAllItems.checked = false;
            } else if (selectAllItems) {
                selectAllItems.checked = Array.from(itemCheckboxes).every(cb => cb.checked);
            }
        });
    });
    
    const bulkForm = document.getElementById('bulk-action-form');
    const bulkProductIds = document.getElementById('bulk-product-ids');
    const bulkActionType = document.getElementById('bulk-action-type');
    
    if (bulkForm) {
        const activateBtn = document.querySelector('.bulk-action-activate');
        const deactivateBtn = document.querySelector('.bulk-action-deactivate');
        const deleteBtn = document.querySelector('.bulk-action-delete');
        
        if (activateBtn) {
            activateBtn.addEventListener('click', function() {
                const selectedIds = getSelectedProductIds();
                if (selectedIds.length > 0) {
                    bulkProductIds.value = JSON.stringify(selectedIds);
                    bulkActionType.value = 'activate';
                    bulkForm.action = bulkActionBaseUrl;
                    bulkForm.submit();
                }
            });
        }
        
        if (deactivateBtn) {
            deactivateBtn.addEventListener('click', function() {
                const selectedIds = getSelectedProductIds();
                if (selectedIds.length > 0) {
                    bulkProductIds.value = JSON.stringify(selectedIds);
                    bulkActionType.value = 'deactivate';
                    bulkForm.action = bulkActionBaseUrl;
                    bulkForm.submit();
                }
            });
        }
        
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                const selectedIds = getSelectedProductIds();
                if (selectedIds.length > 0) {
                    if (confirm(`Are you sure you want to delete ${selectedIds.length} selected products? This action cannot be undone.`)) {
                        bulkProductIds.value = JSON.stringify(selectedIds);
                        bulkActionType.value = 'delete';
                        bulkForm.action = bulkActionBaseUrl;
                        bulkForm.submit();
                    }
                }
            });
        }
    }
    
    function getSelectedProductIds() {
        const selectedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
        return Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
    }
});
