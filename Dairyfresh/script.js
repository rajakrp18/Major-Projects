document.addEventListener('DOMContentLoaded', () => {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const cartCount = document.getElementById('cart-count');
    const checkoutBtn = document.getElementById('checkout-btn');
    const paymentForm = document.getElementById('payment-form');
    const paymentMethod = document.getElementById('payment-method');
    const cardDetails = document.getElementById('card-details');
    const upiDetails = document.getElementById('upi-details');
    const deliveryForm = document.getElementById('delivery-form');

    // Add to cart
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const name = button.dataset.name;
            const price = parseFloat(button.dataset.price);
            const item = cart.find(i => i.id === id);
            if (item) {
                item.quantity += 1;
            } else {
                cart.push({ id, name, price, quantity: 1 });
            }
            updateCart();
        });
    });

    // Update cart display
    function updateCart() {
        cartItems.innerHTML = '';
        let total = 0;
        cart.forEach(item => {
            total += item.price * item.quantity;
            const div = document.createElement('div');
            div.className = 'cart-item';
            div.innerHTML = `
                <p>${item.name} - ₹${item.price} x ${item.quantity}</p>
                <button class="btn" onclick="removeFromCart('${item.id}')">Remove</button>
            `;
            cartItems.appendChild(div);
        });
        cartTotal.textContent = total.toFixed(2);
        cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    // Remove from cart
    window.removeFromCart = id => {
        const index = cart.findIndex(item => item.id === id);
        if (index !== -1) {
            cart.splice(index, 1);
            updateCart();
        }
    };

    // Payment method toggle
    paymentMethod.addEventListener('change', () => {
        cardDetails.classList.add('hidden');
        upiDetails.classList.add('hidden');
        if (paymentMethod.value === 'card') {
            cardDetails.classList.remove('hidden');
        } else if (paymentMethod.value === 'upi') {
            upiDetails.classList.remove('hidden');
        }
    });

    // Payment form submission
    paymentForm.addEventListener('submit', e => {
        e.preventDefault();

        if (cart.length === 0) {
            alert('Cart is empty!');
            return;
        }

        const method = paymentMethod.value;
        if (method === 'card') {
            const number = document.getElementById('card-number').value;
            const expiry = document.getElementById('card-expiry').value;
            const cvc = document.getElementById('card-cvc').value;
            if (!number || !expiry || !cvc) {
                alert('Please fill all card details.');
                return;
            }
        }

        if (method === 'upi') {
            const upi = document.getElementById('upi-id').value;
            if (!upi) {
                alert('Please enter UPI ID.');
                return;
            }
        }

        // Send cart data to server
        fetch('checkout.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(cart)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Payment successful!');
                cart = [];
                localStorage.removeItem('cart');
                updateCart();
                location.reload();
            } else {
                alert('Error: Order could not be completed. Check stock.');
            }
        })
        .catch(() => {
            alert('Server error. Please try again later.');
        });
    });

    // Delivery form submission
    deliveryForm.addEventListener('submit', e => {
        e.preventDefault();
        if (cart.length === 0) {
            alert('Cart is empty!');
            return;
        }
        const slot = document.getElementById('delivery-slot').value;
        alert(`Order confirmed for ${slot} delivery!`);
    });

    // Checkout button
    checkoutBtn.addEventListener('click', () => {
        if (cart.length === 0) {
            alert('Cart is empty!');
            return;
        }
        document.getElementById('payment').scrollIntoView({ behavior: 'smooth' });
    });

    // On load
    updateCart();
});
