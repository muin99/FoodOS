(function () {
    const form = document.getElementById('checkoutForm');
    const savedAddress = document.getElementById('savedAddress');
    const addressInput = document.getElementById('deliveryAddress');
    const cityInput = document.getElementById('city');
    const message = document.getElementById('checkoutMessage');

    if (savedAddress) {
        savedAddress.addEventListener('change', function () {
            const selected = savedAddress.options[savedAddress.selectedIndex];
            addressInput.value = selected.value || '';
            cityInput.value = selected.dataset.city || 'Dhaka';
        });
    }

    if (!form) return;

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const cart = window.BiteBuddyCart.getCart();
        if (!cart.items.length || !cart.restaurant_id) {
            message.textContent = 'Your cart is empty.';
            return;
        }

        fetch('../controller/createOrder.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                restaurant_id: cart.restaurant_id,
                items: cart.items,
                delivery_address: addressInput.value,
                city: cityInput.value,
                payment_method: document.getElementById('paymentMethod').value
            })
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            message.textContent = data.message;
            if (data.success) {
                localStorage.removeItem('bitebuddy_customer_cart');
                window.location.href = data.redirect;
            }
        })
        .catch(function () {
            message.textContent = 'Order failed. Please try again.';
        });
    });
})();
