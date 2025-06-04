document.addEventListener('DOMContentLoaded', function() {
 
    function initializeModalSafely(modalElement, options = {}) {
        if (!modalElement) return null;
        
        const defaultOptions = {
            backdrop: 'static',
            keyboard: false,
            focus: true
        };
        
        const finalOptions = { ...defaultOptions, ...options };
        
        const currentScrollY = window.scrollY;
        
        const modal = new bootstrap.Modal(modalElement, finalOptions);
        
        const handleModalShown = function() {
            document.body.style.overflow = 'hidden';
            document.body.style.paddingRight = '17px';
            document.body.classList.add('modal-open');
            
            modalElement.style.display = 'block';
            modalElement.style.zIndex = '10050';
            
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.style.zIndex = '10049';
                backdrop.style.position = 'fixed';
                backdrop.style.top = '0';
                backdrop.style.left = '0';
                backdrop.style.width = '100vw';
                backdrop.style.height = '100vh';
            }
        };
        
        const handleModalHidden = function() {
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            document.body.classList.remove('modal-open');
            
            window.scrollTo(0, currentScrollY);
            
            modalElement.removeEventListener('shown.bs.modal', handleModalShown);
            modalElement.removeEventListener('hidden.bs.modal', handleModalHidden);
        };
        
        modalElement.addEventListener('shown.bs.modal', handleModalShown);
        modalElement.addEventListener('hidden.bs.modal', handleModalHidden);
        
        return modal;
    }

    function clearProblematicOverlays() {
     
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            if (!backdrop.closest('.modal.show')) {
                backdrop.remove();
            }
        });
        
        if (!document.querySelector('.modal.show')) {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
    }

    clearProblematicOverlays();

    const selectAll = document.getElementById('selectAll');
    const productCheckboxes = document.querySelectorAll('.product-select');
    const bulkActionsContainer = document.querySelector('.bulk-actions-container');
    const selectedCountSpan = document.getElementById('selectedCount');
    const applyBulkActionBtn = document.getElementById('applyBulkAction');
    const cancelBulkActionBtn = document.getElementById('cancelBulkAction');
    const bulkActionSelect = document.querySelector('select[name="bulk_action"]');
    const bulkActionForm = document.getElementById('bulkActionsForm');
    
    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.product-select:checked');
        const selectedCount = checkedBoxes.length;
        
        if (selectedCountSpan) {
            selectedCountSpan.textContent = selectedCount;
        }
        
        if (bulkActionsContainer) {
            if (selectedCount > 0) {
                bulkActionsContainer.classList.remove('d-none');
                if (applyBulkActionBtn) {
                    applyBulkActionBtn.disabled = bulkActionSelect && bulkActionSelect.value === '';
                }
            } else {
                bulkActionsContainer.classList.add('d-none');
                if (applyBulkActionBtn) {
                    applyBulkActionBtn.disabled = true;
                }
            }
        }

        const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
        localStorage.setItem('selectedProductIds', JSON.stringify(selectedIds));
    }
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });
    }
    
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            
            if (selectAll) {
                if (!this.checked) {
                    selectAll.checked = false;
                } else {
                  
                    const allChecked = Array.from(productCheckboxes).every(cb => cb.checked);
                    selectAll.checked = allChecked;
                }
            }
        });
    });
    
    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', function() {
            if (applyBulkActionBtn) {
                applyBulkActionBtn.disabled = this.value === '';
            }
        });
    }
    
    if (cancelBulkActionBtn) {
        cancelBulkActionBtn.addEventListener('click', function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectAll) {
                selectAll.checked = false;
            }
            updateSelectedCount();
            
            localStorage.removeItem('selectedProductIds');
        });
    }

    if (bulkActionForm) {
        bulkActionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const action = bulkActionSelect.value;
            const selectedCount = document.querySelectorAll('.product-select:checked').length;
            
            if (action && selectedCount > 0) {
                let confirmMessage = '';
                let confirmTitle = '';
                
                switch(action) {
                    case 'activate':
                        confirmTitle = 'Activate Products';
                        confirmMessage = `Are you sure you want to activate ${selectedCount} product(s)?`;
                        break;
                    case 'deactivate':
                        confirmTitle = 'Deactivate Products';
                        confirmMessage = `Are you sure you want to deactivate ${selectedCount} product(s)?`;
                        break;
                    case 'delete':
                        confirmTitle = 'Delete Products';
                        confirmMessage = `WARNING: You are about to delete ${selectedCount} product(s). This action cannot be undone!`;
                        break;
                    default:
                        confirmTitle = 'Confirm Action';
                        confirmMessage = `Are you sure you want to ${action} ${selectedCount} product(s)?`;
                }
                
                const modalHtml = `
                <div class="modal fade" id="bulkActionConfirmModal" tabindex="-1" aria-labelledby="bulkActionConfirmModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bulkActionConfirmModalLabel">${confirmTitle}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>${confirmMessage}</p>
                                ${action === 'delete' ? '<p class="text-danger"><strong>Warning:</strong> This will permanently remove the products from the database.</p>' : ''}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-${action === 'delete' ? 'danger' : 'primary'}" id="confirmBulkAction">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>`;
                
                if (!document.getElementById('bulkActionConfirmModal')) {
                    const modalContainer = document.createElement('div');
                    modalContainer.innerHTML = modalHtml;
                    document.body.appendChild(modalContainer);
                }
                
                const confirmModal = new bootstrap.Modal(document.getElementById('bulkActionConfirmModal'));
                confirmModal.show();
                
                document.getElementById('confirmBulkAction').addEventListener('click', function() {
                    confirmModal.hide();
                    bulkActionForm.submit();
                });
            }
        });
    }

    const restoreSelectedProducts = function() {
        const savedSelection = localStorage.getItem('selectedProductIds');
        if (savedSelection) {
            try {
                const selectedIds = JSON.parse(savedSelection);
                if (selectedIds.length > 0) {
                    productCheckboxes.forEach(checkbox => {
                        if (selectedIds.includes(checkbox.value)) {
                            checkbox.checked = true;
                        }
                    });
                    updateSelectedCount();
                }
            } catch (e) {
                console.error('Error restoring product selection:', e);
                localStorage.removeItem('selectedProductIds');
            }
        }
    };
    
    restoreSelectedProducts();

    const toggleColumnsBtn = document.getElementById('toggleColumns');
    if (toggleColumnsBtn) {
      
        const columns = [
            { index: 1, field: 'product-id', label: 'ID' },
            { index: 2, field: 'product-image', label: 'Image' },
            { index: 3, field: 'product-name', label: 'Name' },
            { index: 4, field: 'product-category', label: 'Category' },
            { index: 5, field: 'product-price', label: 'Price' },
            { index: 6, field: 'product-stock', label: 'Stock' },
            { index: 7, field: 'product-seller', label: 'Seller' },
            { index: 8, field: 'product-status', label: 'Status' }
        ];
        
        const createColumnVisibilityDropdown = () => {
           
            const existingDropdown = document.getElementById('columnVisibilityDropdown');
            if (existingDropdown) {
                existingDropdown.remove();
            }
            
            const dropdownMenu = document.createElement('div');
            dropdownMenu.id = 'columnVisibilityDropdown';
            dropdownMenu.className = 'dropdown-menu p-2';
            dropdownMenu.setAttribute('aria-labelledby', 'toggleColumns');
            
            const savedColumnSettings = localStorage.getItem('productColumnVisibility');
            let columnVisibility = {};
            
            if (savedColumnSettings) {
                try {
                    columnVisibility = JSON.parse(savedColumnSettings);
                } catch (e) {
                    console.error('Error parsing saved column visibility:', e);
                }
            }
           
            const title = document.createElement('h6');
            title.className = 'dropdown-header';
            title.textContent = 'Toggle Column Visibility';
            dropdownMenu.appendChild(title);
            
            columns.forEach(column => {
              
                if (column.field === 'product-name') return;
                
                const isVisible = columnVisibility[column.field] !== false; 
                
                const item = document.createElement('div');
                item.className = 'form-check';
                
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'form-check-input column-toggle';
                checkbox.id = `toggle-${column.field}`;
                checkbox.checked = isVisible;
                checkbox.dataset.columnIndex = column.index;
                checkbox.dataset.columnField = column.field;
                
                const label = document.createElement('label');
                label.className = 'form-check-label';
                label.htmlFor = `toggle-${column.field}`;
                label.textContent = column.label;
                
                item.appendChild(checkbox);
                item.appendChild(label);
                dropdownMenu.appendChild(item);
                
                toggleColumnVisibility(column.index, isVisible);
            });
            
            const divider = document.createElement('div');
            divider.className = 'dropdown-divider';
            dropdownMenu.appendChild(divider);
            
            const resetBtn = document.createElement('button');
            resetBtn.className = 'btn btn-sm btn-outline-secondary w-100';
            resetBtn.textContent = 'Reset to Default';
            resetBtn.addEventListener('click', function() {
                
                document.querySelectorAll('.column-toggle').forEach(cb => {
                    cb.checked = true;
                    toggleColumnVisibility(cb.dataset.columnIndex, true);
                });
                
                localStorage.removeItem('productColumnVisibility');
            });
            dropdownMenu.appendChild(resetBtn);
            
            dropdownMenu.addEventListener('change', function(e) {
                if (e.target.classList.contains('column-toggle')) {
                    const columnIndex = parseInt(e.target.dataset.columnIndex);
                    const isVisible = e.target.checked;
                    const columnField = e.target.dataset.columnField;
                    
                    toggleColumnVisibility(columnIndex, isVisible);
                    
                    updateColumnVisibilitySettings(columnField, isVisible);
                }
            });
            
            toggleColumnsBtn.after(dropdownMenu);
            
            const dropdownInstance = new bootstrap.Dropdown(toggleColumnsBtn);
            
            dropdownMenu.addEventListener('click', function(e) {
                if (e.target.tagName !== 'BUTTON') {
                    e.stopPropagation();
                }
            });
        };
        
        const toggleColumnVisibility = (columnIndex, isVisible) => {
            const table = document.querySelector('.product-table table');
            if (!table) return;
            
            const cells = table.querySelectorAll(`th:nth-child(${columnIndex + 1}), td:nth-child(${columnIndex + 1})`);
            
            cells.forEach(cell => {
                if (isVisible) {
                    cell.classList.remove('d-none');
                } else {
                    cell.classList.add('d-none');
                }
            });
        };
        
        const updateColumnVisibilitySettings = (columnField, isVisible) => {
            let columnVisibility = {};
            const savedSettings = localStorage.getItem('productColumnVisibility');
            
            if (savedSettings) {
                try {
                    columnVisibility = JSON.parse(savedSettings);
                } catch (e) {
                    console.error('Error parsing saved column visibility:', e);
                }
            }
            
            columnVisibility[columnField] = isVisible;
            localStorage.setItem('productColumnVisibility', JSON.stringify(columnVisibility));
        };
        
        toggleColumnsBtn.addEventListener('click', function() {
            createColumnVisibilityDropdown();
        });
    }    
    const priceSlider = document.getElementById('price_range');
    const minPriceInput = document.getElementById('min_price');
    const maxPriceInput = document.getElementById('max_price');
    const currentMinPrice = document.getElementById('currentMinPrice');
    const currentMaxPrice = document.getElementById('currentMaxPrice');
    
    if (priceSlider && minPriceInput && maxPriceInput) {
       
        const initMaxPrice = parseInt(maxPriceInput.value) || 1000;
        priceSlider.max = initMaxPrice > 1000 ? initMaxPrice : 1000;
        priceSlider.value = initMaxPrice;
        
        priceSlider.addEventListener('input', function() {
            maxPriceInput.value = this.value;
            if (currentMaxPrice) currentMaxPrice.textContent = this.value;
            updatePriceRangeUI();
        });
        
        maxPriceInput.addEventListener('input', function() {
            const newValue = parseInt(this.value) || 0;
            
            if (newValue > parseInt(priceSlider.max)) {
                priceSlider.max = newValue;
            }
            
            priceSlider.value = newValue;
            if (currentMaxPrice) currentMaxPrice.textContent = this.value;
            updatePriceRangeUI();
        });
        
        minPriceInput.addEventListener('input', function() {
            if (currentMinPrice) currentMinPrice.textContent = this.value;
            updatePriceRangeUI();
        });
        
        function updatePriceRangeUI() {
            const min = parseInt(minPriceInput.value) || 0;
            const max = parseInt(maxPriceInput.value) || parseInt(priceSlider.max);
            
            if (min > max) {
                minPriceInput.value = max;
                if (currentMinPrice) currentMinPrice.textContent = max;
            }
        }
        
        if (currentMinPrice && minPriceInput) {
            currentMinPrice.textContent = minPriceInput.value;
        }
        if (currentMaxPrice) {
            currentMaxPrice.textContent = maxPriceInput.value;
        }
    }   
    const toggleFilters = document.querySelector('.toggle-filters');
    const filtersContainer = document.querySelector('.filters-container');
    
    if (toggleFilters && filtersContainer) {
      
        const filtersVisible = localStorage.getItem('productsFiltersVisible') !== 'hidden';
        
        if (!filtersVisible) {
            filtersContainer.style.display = 'none';
            toggleFilters.innerHTML = '<i class="fas fa-filter me-2"></i>Show Filters';
        } else {
            filtersContainer.style.display = 'block';
            toggleFilters.innerHTML = '<i class="fas fa-filter me-2"></i>Hide Filters';
        }
        
        toggleFilters.addEventListener('click', function() {
            if (filtersContainer.style.display === 'none') {
                filtersContainer.style.display = 'block';
                toggleFilters.innerHTML = '<i class="fas fa-filter me-2"></i>Hide Filters';
                localStorage.setItem('productsFiltersVisible', 'visible');
            } else {
                filtersContainer.style.display = 'none';
                toggleFilters.innerHTML = '<i class="fas fa-filter me-2"></i>Show Filters';
                localStorage.setItem('productsFiltersVisible', 'hidden');
            }
        });
    }    
    const quickEditButtons = document.querySelectorAll('.quick-edit-btn');
    const quickEditModal = document.getElementById('quickEditModal');
    const quickEditForm = document.getElementById('quickEditForm');
    
    function getBaseUrl() {
        const metaBaseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content');
        if (metaBaseUrl) return metaBaseUrl;
    
        return window.location.origin + '/c2c_ecommerce/C2C_ecommerce_laravel/public';
    }

    if (quickEditButtons.length > 0 && quickEditModal) {
        quickEditButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); 
                
                const productId = this.getAttribute('data-id');
                const productName = this.getAttribute('data-name');
                const productPrice = this.getAttribute('data-price');
                const productStock = this.getAttribute('data-stock');
                const productStatus = this.getAttribute('data-status');
                const productCondition = this.getAttribute('data-condition');
                const productDescription = this.getAttribute('data-description') || '';
                
                const originalContent = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                document.getElementById('edit_name').value = productName;
                document.getElementById('edit_price').value = productPrice;
                document.getElementById('edit_stock').value = productStock;
                
                const descriptionField = document.getElementById('edit_description');
                if (descriptionField && productDescription) {
                    descriptionField.value = productDescription;
                } else if (descriptionField) {
                    descriptionField.value = ''; // Clear any previous value
                }
                 
                const categorySelect = document.getElementById('edit_category');
                const categoryId = this.getAttribute('data-category');
                if (categorySelect && categoryId) {
                    for (let i = 0; i < categorySelect.options.length; i++) {
                        if (categorySelect.options[i].value === categoryId) {
                            categorySelect.selectedIndex = i;
                            break;
                        }
                    }
                }
                
                const conditionSelect = document.getElementById('edit_condition');
                if (conditionSelect) {
                    for (let i = 0; i < conditionSelect.options.length; i++) {
                        if (conditionSelect.options[i].value === productCondition) {
                            conditionSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
                
                const statusSelect = document.getElementById('edit_status');
                if (statusSelect) {
                    for (let i = 0; i < statusSelect.options.length; i++) {
                        if (statusSelect.options[i].value === productStatus) {
                            statusSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
                
                const baseUrl = getBaseUrl();
                if (quickEditForm) {
                    quickEditForm.action = `${baseUrl}/admin/products/update/${productId}`;
                }                this.innerHTML = originalContent;
                
                clearProblematicOverlays();
                
                const modal = initializeModalSafely(quickEditModal);
                if (modal) {
                    modal.show();
                } else {
                    console.error('Failed to initialize quick edit modal');
                }
            });
        });
    } else {
        console.warn('Quick edit buttons or modal not found');
    }    
    const saveQuickEditBtn = document.getElementById('saveQuickEdit');
    if (saveQuickEditBtn && quickEditForm) {
        saveQuickEditBtn.addEventListener('click', function() {
            const imageInput = document.getElementById('edit_image');
            if (imageInput && imageInput.files.length > 0) {
                const file = imageInput.files[0];
               
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    alert('Invalid file type. Only JPG, JPEG or PNG files are allowed.');
                    return;
                }
                
                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('File is too large. Maximum size is 2MB.');
                    return;
                }
            }
            
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            this.disabled = true;
            
            const productName = document.getElementById('edit_name').value;
            
            const formData = new FormData(quickEditForm);
            const action = quickEditForm.action;
            fetch(action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Server responded with an error');
                }
                return response.text();
            })
            .then(data => {
              
                bootstrap.Modal.getInstance(quickEditModal).hide();
                
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                successAlert.innerHTML = `
                    <strong>Success!</strong> Product "${productName}" has been updated.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(successAlert);
                
                setTimeout(() => {
                    successAlert.classList.remove('show');
                    setTimeout(() => successAlert.remove(), 500);
                }, 5000);
                
                setTimeout(() => window.location.reload(), 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                
                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3';
                errorAlert.innerHTML = `
                    <strong>Error!</strong> Failed to update product. Please try again.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(errorAlert);
                
                saveQuickEditBtn.innerHTML = 'Save changes';
                saveQuickEditBtn.disabled = false;
                
                setTimeout(() => {
                    errorAlert.classList.remove('show');
                    setTimeout(() => errorAlert.remove(), 500);
                }, 5000);
            });
        });
    }
    
    const deleteButtons = document.querySelectorAll('.delete-product');
    const deleteProductName = document.querySelector('#deleteProductName');
    const deleteProductForm = document.querySelector('#deleteProductForm');
    
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const productName = this.getAttribute('data-name');                if (deleteProductName) {
                    deleteProductName.textContent = productName;
                }                if (deleteProductForm) {
                    const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
                    deleteProductForm.action = `${baseUrl}/admin/products/delete/${productId}`;
                }
                
                clearProblematicOverlays();
                
                const deleteModalElement = document.getElementById('deleteProductModal');
                const deleteModal = initializeModalSafely(deleteModalElement);
                if (deleteModal) {
                    deleteModal.show();
                } else {
                    console.error('Failed to initialize delete product modal');
                }
            });
        });
    }
    const editImageInput = document.getElementById('edit_image');
    if (editImageInput) {
        editImageInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const file = this.files[0];
                
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    alert('Invalid file type. Only JPG, JPEG or PNG files are allowed.');
                    this.value = ''; 
                    return;
                }
                
                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('File is too large. Maximum size is 2MB.');
                    this.value = ''; 
                    return;
                }
            }
        });
    }
});
