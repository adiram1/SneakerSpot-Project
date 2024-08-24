<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include('DBconnect.php');
    include('functions.php');

    $items = getCartItems($_SESSION['user'] ?? 0);
?>

<div id="cartSidebar" class="cart-sidebar">
    <h4>Your Cart</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Product</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Price</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            if (!empty($items)): 
                foreach ($items as $item):
                    $total += $item['subtotal'];
            ?>
                <tr>
                    <td>
                        <img src="assets/images/<?php echo $item['product']['image']; ?>" style="width: 50px; height: auto;" alt="<?php echo $item['product']['name']; ?>">
                        <?php echo $item['product']['name']; ?>
                    </td>
                    <td><?php echo $item['size']; ?></td>
                    <td>
                        <form action="products.php" method="GET" style="display: flex; align-items: center;">
                            <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
                            <input type="hidden" name="size" value="<?php echo $item['size']; ?>">
                            <button type="submit" name="update_quantity" value="<?php echo $item['quantity'] - 1; ?>" class="btn btn-sm btn-outline-secondary">-</button>
                            <span style="margin: 0 10px;"><?php echo $item['quantity']; ?></span>
                            <button type="submit" name="update_quantity" value="<?php echo $item['quantity'] + 1; ?>" class="btn btn-sm btn-outline-secondary">+</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                    <td><a href="products.php?remove_from_cart=<?php echo $item['product']['id']; ?>&size=<?php echo $item['size']; ?>&cart_open=true" class="remove-product">X</a></td>
                </tr>
            <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                    <td></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="5">Your cart is empty.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="products.php?empty_cart=true&cart_open=true" class="btn btn-danger btn-block mt-3 btn-empty-cart">EMPTY CART</a>
    <a href="checkout.php" class="btn btn-success btn-block mt-3 btn-checkout">CHECKOUT</a>
</div>

<script>
    // פונקציה לשמירת מצב העגלה ב localStorage
    function toggleCart() {
        const cartSidebar = document.getElementById('cartSidebar');
        cartSidebar.classList.toggle('open');
        localStorage.setItem('cart_open', cartSidebar.classList.contains('open'));
    }

    // שמירת מצב העגלה בטעינת העמוד
    document.addEventListener('DOMContentLoaded', function() {
        if (localStorage.getItem('cart_open') === 'true' || window.location.search.includes('cart_open=true')) {
            document.getElementById('cartSidebar').classList.add('open');
        }
    });
</script>
<style>
.cart-sidebar {
    position: fixed;
    top: 0;
    right: -400px;
    width: 400px;
    height: 100%;
    background: #fff;
    border-left: 2px solid #ddd;
    transition: right 0.3s ease;
    padding: 20px;
    box-shadow: -2px 0 5px rgba(0,0,0,0.1);
    overflow-x: hidden;
    overflow-y: auto;
    z-index: 1050;
}
.cart-sidebar.open {
    right: 0;
}
.remove-product {
    color: #c44950;
    cursor: pointer;
    margin-left: 10px;
    text-decoration: none;
}
.remove-product:hover {
    color: #ff0000;
    text-decoration: none;
}
.btn-empty-cart {
    background-color: #c44950;
    color: white;
    padding: 10px 20px;
    text-transform: uppercase;
    font-size: 14px;
    border-radius: 5px;
    display: block;
    width: 100%;
    text-align: center;
    margin-top: 10px;
    transition: background-color 0.3s ease;
}

.btn-empty-cart:hover {
    background-color: #b73b45;
}
</style>
