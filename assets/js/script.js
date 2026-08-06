document.addEventListener('DOMContentLoaded', () => {
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData
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
});
