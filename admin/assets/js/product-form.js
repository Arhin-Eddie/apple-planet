document.addEventListener('DOMContentLoaded', function() {
    // QoL: Live character count for description
    const descInput = document.querySelector('textarea[name="description"]');
    if (descInput) {
        const charCount = document.createElement('small');
        charCount.className = 'text-muted float-end mt-1';
        descInput.parentNode.appendChild(charCount);
        
        const updateCount = () => {
            charCount.textContent = descInput.value.length + ' characters';
        };
        descInput.addEventListener('input', updateCount);
        updateCount();
    }

    // Function to setup a dropzone
    function setupDropzone(dropzoneId, inputId, previewContainerId, previewImgId, previewNameId) {
        const dropzone = document.getElementById(dropzoneId);
        const fileInput = document.getElementById(inputId);
        const previewContainer = document.getElementById(previewContainerId);
        const previewImg = document.getElementById(previewImgId);
        const previewName = document.getElementById(previewNameId);
        
        if (!dropzone || !fileInput) return;

        // Click to open file dialog
        dropzone.addEventListener('click', () => fileInput.click());

        // Drag & Drop events
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropzone.classList.add('dropzone-highlight');
        }

        function unhighlight(e) {
            dropzone.classList.remove('dropzone-highlight');
        }

        dropzone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length) {
                fileInput.files = files; // Assign files to input
                handleFiles(files[0]);
            }
        }

        fileInput.addEventListener('change', function() {
            if (this.files.length) {
                handleFiles(this.files[0]);
            }
        });

        function handleFiles(file) {
            // Validation: Image type
            const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPG, PNG, WebP).');
                fileInput.value = ''; // Reset
                return;
            }

            // Validation: File size (e.g., max 2MB)
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                alert('File is too large. Please select an image under 2MB.');
                fileInput.value = ''; // Reset
                return;
            }

            // Display compact preview
            if (previewName) previewName.textContent = file.name;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                if (previewImg) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                }
                if (previewContainer) {
                    previewContainer.classList.remove('d-none');
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // Setup Main Image Dropzone
    setupDropzone('image-dropzone', 'image-input', 'image-preview-container', 'image-preview', 'image-name');
    
    // Setup Hover Image Dropzone
    setupDropzone('hover-dropzone', 'hover-input', 'hover-preview-container', 'hover-preview', 'hover-name');
});
