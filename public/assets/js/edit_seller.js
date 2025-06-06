document.addEventListener('DOMContentLoaded', function() {
    initFormValidation();
    initCKEditor();
    initImagePreviews();
    setupImageRemovalEvents();
});

function initFormValidation() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submission triggered');
            
            const submitBtn = document.getElementById('update-product-btn');
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
                submitBtn.disabled = true;
            }
            
            const requiredFields = form.querySelectorAll('[required]');
            let hasErrors = false;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    console.error(`Field ${field.name} is required but empty`);
                    field.classList.add('is-invalid');
                    hasErrors = true;
                    
                    let nextSibling = field.nextElementSibling;
                    if (!nextSibling || !nextSibling.classList.contains('invalid-feedback')) {
                        const feedbackDiv = document.createElement('div');
                        feedbackDiv.className = 'invalid-feedback';
                        feedbackDiv.textContent = 'This field is required';
                        field.parentNode.insertBefore(feedbackDiv, field.nextSibling);
                    }
                } else {
                    field.classList.remove('is-invalid');
                    const nextSibling = field.nextElementSibling;
                    if (nextSibling && nextSibling.classList.contains('invalid-feedback')) {
                        nextSibling.remove();
                    }
                }
            });
            
            if (hasErrors) {
                console.error('Form has validation errors');
                e.preventDefault();
                alert('Please fill in all required fields before submitting.');
                return false;
            }
            
            if (window.editor && window.editor.getData().trim() === '') {
                console.error('CKEditor content is empty');
                document.getElementById('editor-container').classList.add('border', 'border-danger');
                e.preventDefault();
                alert('Please provide a product description');
                return false;
            }
             
            const fileInput = document.getElementById('product_images');
            if (fileInput && fileInput.files.length > 0) {
                console.log(`${fileInput.files.length} images selected for upload`);
                
                const supportedTypes = [
                    'image/jpeg', 
                    'image/png', 
                    'image/jpg'
                ];
                
                let oversizedFiles = [];
                let unsupportedFiles = [];
                
                Array.from(fileInput.files).forEach(file => {
                    const fileSizeMB = file.size / (1024 * 1024);
                    console.log(`File: ${file.name}, Type: ${file.type}, Size: ${fileSizeMB.toFixed(2)} MB`);
                    
                    if (fileSizeMB > 2) {
                        oversizedFiles.push(`${file.name} (${fileSizeMB.toFixed(2)} MB)`);
                    }
                    
                    if (!supportedTypes.includes(file.type)) {
                        unsupportedFiles.push(`${file.name} (${file.type || 'unknown type'})`);
                    }
                });
                
                if (oversizedFiles.length > 0) {
                    console.error('Some files exceed the 2MB limit:', oversizedFiles);
                    e.preventDefault();
                    alert(`The following files exceed the 2MB limit:\n${oversizedFiles.join('\n')}\n\nPlease select smaller images.`);
                    return false;
                }

                if (unsupportedFiles.length > 0) {
                    console.error('Unsupported file types:', unsupportedFiles);
                    e.preventDefault();
                    alert(`The following files have unsupported formats:\n${unsupportedFiles.join('\n')}\n\nPlease select JPEG, PNG, or JPG images.`);
                    return false;
                }
            }
            
            console.log('Form is valid, submitting...');
        });
    }
}

function initCKEditor() {
    if (typeof ClassicEditor !== 'undefined') {
        const editorElement = document.getElementById('description');
        if (editorElement) {
            if (window.editor) {
                window.editor.destroy();
            }
            
            ClassicEditor
                .create(editorElement)
                .then(editor => {
                    window.editor = editor;
                    
                    editor.model.document.on('change:data', () => {
                        const data = editor.getData();
                        console.log('CKEditor content updated:', data);
                        editorElement.value = data;
                        editorElement.dispatchEvent(new Event('input', { bubbles: true }));
                    });
                })
                .catch(error => {
                    console.error('CKEditor initialization error:', error);
                });
        }
    }
}

function initImagePreviews() {
    const imageInputs = document.querySelectorAll('.product-image-upload');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const previewContainer = document.querySelector(this.dataset.previewTarget);
            if (!previewContainer) return;
            
            previewContainer.innerHTML = '';
            const isMultiple = this.dataset.multiple === 'true';
            
            if (this.files && this.files.length > 0) {
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    if (!file.type.startsWith('image/')) continue;
                    
                    const reader = new FileReader();
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'image-preview-item position-relative';
                    
                    reader.onload = function(e) {
                        imgContainer.innerHTML = `
                            <img src="${e.target.result}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;" alt="Product image preview">
                            <button type="button" class="btn-close remove-image position-absolute" style="top: 5px; right: 5px; background-color: white;" aria-label="Remove"></button>
                        `;
                    };
                    
                    reader.readAsDataURL(file);
                    previewContainer.appendChild(imgContainer);
                    
                    if (!isMultiple) break;
                }
            }
        });
    });
}

function setupImageRemovalEvents() {
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-image')) {
            const previewItem = e.target.closest('.image-preview-item');
            const previewContainer = previewItem.parentElement;
            previewItem.remove();
            
            if (previewContainer.children.length === 0) {
                const fileInput = document.getElementById('product_images');
                if (fileInput) {
                    fileInput.value = '';  
                    console.log('File input reset');
                }
            }
        }
    });
}

/**
 * Important for the image removal here. 
 * @param {HTMLElement} button -The buttons elements functionaility is here.
 * @param {string} imageName - This is the name of the image file here.
 */
function toggleImageRemoval(button, imageName) {
    const imageItem = button.closest('.current-image-item');
    const hiddenInput = imageItem.querySelector('input[data-image="' + imageName + '"]');
    
    if (imageItem.classList.contains('marked-for-removal')) {
      
        imageItem.classList.remove('marked-for-removal');
        imageItem.style.opacity = '1';
        button.innerHTML = '<i class="fas fa-trash"></i>';
        button.classList.remove('btn-success');
        button.classList.add('btn-danger');
        hiddenInput.value = '';
        hiddenInput.disabled = true;
    } else {

        imageItem.classList.add('marked-for-removal');
        imageItem.style.opacity = '0.5';
        button.innerHTML = '<i class="fas fa-undo"></i>';
        button.classList.remove('btn-danger');
        button.classList.add('btn-success');
        hiddenInput.value = imageName;
        hiddenInput.disabled = false;
    }
}
