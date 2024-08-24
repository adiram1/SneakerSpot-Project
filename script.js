let cart = [];

function addToCart(productId) {
    const existingProduct = cart.find(item => item.productId === productId);
    if (existingProduct) {
        existingProduct.quantity += 1;
    } else {
        cart.push({ productId, quantity: 1 });
    }
    updateCart();
}

function updateCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
    // Update the cart display on the page if needed
}

function loadCart() {
    const storedCart = localStorage.getItem('cart');
    if (storedCart) {
        cart = JSON.parse(storedCart);
    }
}

function clearCart() {
    cart = [];
    localStorage.removeItem('cart');
    updateCart();
}

document.addEventListener('DOMContentLoaded', loadCart);
