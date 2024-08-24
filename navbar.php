<?php
    // בדיקה אם לא התחיל עדיין הסשן
    if (session_status() === PHP_SESSION_NONE) session_start();

    include 'DBconnect.php';
    include 'functions.php';

    // בדיקת חיבור לדאטאבייס
    if (!$conn) die("Connection failed: " . mysqli_connect_error());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-size: 18px;
            margin: 0;
            display: grid;
            place-items: center;
            height: 100vh;
            background: white;
        }

        ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        nav {
            width: 100%;
        }

        a {
            cursor: pointer;
        }

        .menu {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 70px;
        }

        .menu a img {
            height: 80px;
            width: auto;
            margin-right: auto;
        }

        .menu li {
            position: relative;
            display: inline-block;
        }

        .menu li a {
            display: inline-block;
            width: 110px;
            transition: all 0.3s ease-in-out;
            margin-right: 10px;
            color: black;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            position: relative;
            padding: 0 7px;
        }

        .menu > li:hover > a {
            color: #407e60;
        }

        .menu > li:hover > a::before {
            visibility: visible;
            transform: scale(1, 1);
        }

        .menu > li a::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 3px;
            bottom: 3px;
            left: 0px;
            background-color: black;
            transition: all 0.2s ease-in-out;
            transform: scale(0, 0);
            visibility: hidden;
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #c44950;
            color: white;
            border-radius: 50%;
            padding: 3px 8px;
            font-size: 12px;
        }

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
            overflow-y: auto;
            z-index: 1050;
        }

        .cart-sidebar.open {
            right: 0;
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

        .btn-checkout {
            background-color: black;
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

        .btn-checkout:hover {
            background-color: #407e60;
        } 

        .btn-empty-cart:hover {
            background-color: #b73b45;
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
    </style>
</head>
<body>
    <nav class="navbarStyle">
        <ul class="menu">
            <li>
                <a href="index.php">
                    <img src="assets/images/logo.png" alt="Logo">
                </a>
            </li>
            <li><a href="products.php">Products</a></li>
            <li>
                <a href="javascript:void(0);" onclick="toggleCart()">Cart <i class="fa fa-shopping-cart"></i>
                    <?php if (isset($_SESSION['user']) && isset($_SESSION['cart'][$_SESSION['user']]) && count($_SESSION['cart'][$_SESSION['user']]) > 0): ?>
                        <span class="cart-count"><?php echo array_sum(array_column($_SESSION['cart'][$_SESSION['user']], 'quantity')); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="ourStory.php">Our Story</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="profile.php">Profile</a></li>
                <?php if (isAdmin()): ?>
                    <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Sign Up</a></li>
            <?php endif; ?>
        </ul>
    </nav>

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
                $items = getCartItems($_SESSION['user'] ?? 0);
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
        <a href="products.php?empty_cart=true&cart_open=true" class="btn-empty-cart">EMPTY CART</a>
        <a href="checkout.php" class="btn-checkout">CHECKOUT</a>
    </div>

    <script>
        function toggleCart() {
            const cartSidebar = document.getElementById('cartSidebar');
            cartSidebar.classList.toggle('open');
        }

        // שמירת מצב העגלה בטעינת העמוד
        document.addEventListener('DOMContentLoaded', function() {
            const cartSidebar = document.getElementById('cartSidebar');
            if (localStorage.getItem('cart_open') === 'true') {
                cartSidebar.classList.add('open');
            } else {
                cartSidebar.classList.remove('open');
            }
        });

        // שמירת מצב העגלה ב localStorage
        document.getElementById('cartSidebar').addEventListener('transitionend', function() {
            localStorage.setItem('cart_open', this.classList.contains('open'));
        });
    </script>
</body>
</html>
