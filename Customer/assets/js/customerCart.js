(function () {
    const cartKey = 'bitebuddy_customer_cart';

    function getCart() {
        try {
            return JSON.parse(localStorage.getItem(cartKey)) || { restaurant_id: null, restaurant_name: '', items: [] };
        } catch (error) {
            return { restaurant_id: null, restaurant_name: '', items: [] };
        }
    }

    function saveCart(cart) {
        localStorage.setItem(cartKey, JSON.stringify(cart));
    }

    function money(value) {
        return '৳' + Number(value || 0).toFixed(2);
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function subtotal(cart) {
        return cart.items.reduce(function (sum, item) {
            return sum + Number(item.price) * Number(item.quantity);
        }, 0);
    }

    function renderCart() {
        const cart = getCart();
        const cartItems = document.getElementById('cartItems');
        const cartSubtotal = document.getElementById('cartSubtotal');
        const cartGrandTotal = document.getElementById('cartGrandTotal');

        if (!cartItems) return;

        if (!cart.items.length) {
            cartItems.innerHTML = '<p class="muted">Your cart is empty.</p>';
        } else {
            cartItems.innerHTML = cart.items.map(function (item) {
                return '<div class="cart-line">' +
                    '<div><strong>' + escapeHtml(item.name) + '</strong><span>' + money(item.price) + ' x ' + item.quantity + '</span></div>' +
                    '<div class="quantity-actions">' +
                    '<button type="button" data-cart-decrease="' + item.id + '">-</button>' +
                    '<button type="button" data-cart-increase="' + item.id + '">+</button>' +
                    '</div>' +
                    '</div>';
            }).join('');
        }

        const currentSubtotal = subtotal(cart);
        if (cartSubtotal) cartSubtotal.textContent = money(currentSubtotal);
        if (cartGrandTotal) cartGrandTotal.textContent = money(currentSubtotal + 50);
    }

    document.addEventListener('click', function (event) {
        const addButton = event.target.closest('.add-cart-btn');
        if (addButton) {
            const cart = getCart();
            const restaurantId = Number(addButton.dataset.restaurantId);

            if (cart.restaurant_id && Number(cart.restaurant_id) !== restaurantId) {
                if (!confirm('Your cart has items from another restaurant. Clear it and start a new cart?')) {
                    return;
                }
                cart.items = [];
            }

            cart.restaurant_id = restaurantId;
            cart.restaurant_name = addButton.dataset.restaurantName;

            const itemId = Number(addButton.dataset.id);
            const existing = cart.items.find(function (item) {
                return Number(item.id) === itemId;
            });

            if (existing) {
                existing.quantity += 1;
            } else {
                cart.items.push({
                    id: itemId,
                    name: addButton.dataset.name,
                    price: Number(addButton.dataset.price),
                    quantity: 1
                });
            }

            saveCart(cart);
            renderCart();
        }

        const increase = event.target.closest('[data-cart-increase]');
        if (increase) {
            const cart = getCart();
            const item = cart.items.find(function (entry) {
                return Number(entry.id) === Number(increase.dataset.cartIncrease);
            });
            if (item) item.quantity += 1;
            saveCart(cart);
            renderCart();
        }

        const decrease = event.target.closest('[data-cart-decrease]');
        if (decrease) {
            const cart = getCart();
            const id = Number(decrease.dataset.cartDecrease);
            cart.items = cart.items.map(function (item) {
                if (Number(item.id) === id) item.quantity -= 1;
                return item;
            }).filter(function (item) {
                return item.quantity > 0;
            });
            if (!cart.items.length) {
                cart.restaurant_id = null;
                cart.restaurant_name = '';
            }
            saveCart(cart);
            renderCart();
        }
    });

    window.BiteBuddyCart = {
        getCart: getCart,
        saveCart: saveCart,
        renderCart: renderCart,
        subtotal: subtotal
    };

    renderCart();
})();
