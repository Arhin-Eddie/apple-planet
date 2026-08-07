document.addEventListener('DOMContentLoaded', () => {
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => response.text())
            .then(data => {
                fetchCartCount();
                
                const toastEl = document.getElementById('liveToast');
                if (toastEl) {
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    function fetchCartCount() {
        const badge = document.querySelector('.cart-count');
        if (badge) {
            let current = parseInt(badge.textContent) || 0;
            const qtyInput = document.querySelector('input[name="quantity"]');
            let added = qtyInput ? parseInt(qtyInput.value) : 1;
            badge.textContent = current + added;
        }
    }

    // Quick View Logic
    const quickViewBtns = document.querySelectorAll('.quick-view-btn');
    const quickViewModal = document.getElementById('quickViewModal');
    
    if (quickViewModal) {
        const bsModal = new bootstrap.Modal(quickViewModal);
        
        quickViewBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = btn.getAttribute('data-id');
                
                // Assuming BASE_URL is the root. We can use relative path if in root, but it's safer to extract from window location or hardcode if in subdirectory.
                // Since this runs in XAMPP where path varies, we use a relative path trick or inject BASE_URL globally.
                // We injected BASE_URL in header.php
                fetch(BASE_URL + 'api/get_product.php?id=' + productId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        
                        document.getElementById('qv-image').src = data.image ? data.image_url : 'https://placehold.co/400x400/eeeeee/cccccc?text=No+Image';
                        document.getElementById('qv-brand').textContent = data.brand;
                        document.getElementById('qv-title').textContent = data.product_name;
                        document.getElementById('qv-price').textContent = data.formatted_price;
                        document.getElementById('qv-description').textContent = data.description;
                        document.getElementById('qv-id').value = data.id;
                        
                        bsModal.show();
                    })
                    .catch(err => console.error('Error fetching quick view:', err));
            });
        });
    }
});
