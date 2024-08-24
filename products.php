<?php
    ob_start();
    session_start();
    include('DBconnect.php');
    include('cart.php');

    // פונקציה להוספת מוצר לעגלה לפי המשתמש
    function addToCart($userId, $productId, $size) {
        global $conn;
        
        // הוספת מוצר לעגלה בסשן
        $cartKey = $productId . '_' . $size; // יצירת מפתח ייחודי למוצר לפי מזהה מוצר ומידה
        if (!isset($_SESSION['cart'][$userId])) {
            $_SESSION['cart'][$userId] = [];
        }
        if (!isset($_SESSION['cart'][$userId][$cartKey])) {
            $_SESSION['cart'][$userId][$cartKey] = ['quantity' => 1, 'size' => $size];
        } else {
            $_SESSION['cart'][$userId][$cartKey]['quantity']++;
        }
        
        // הוספת או עדכון מוצר בטבלת cart
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
        $stmt->bind_param("iii", $userId, $productId, $size);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // עדכון כמות המוצר
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ? AND size = ?");
            $stmt->bind_param("iii", $userId, $productId, $size);
            $stmt->execute();
        } else {
            // הוספת מוצר חדש
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, size) VALUES (?, ?, 1, ?)");
            $stmt->bind_param("iii", $userId, $productId, $size);
            $stmt->execute();
        }    
        return true;
    }

    // פונקציה למחיקת מוצר מהעגלה
    function removeFromCart($userId, $productId, $size) {
        global $conn;
        $cartKey = $productId . '_' . $size;
        
        // מחיקת מוצר מהעגלה בסשן
        if (isset($_SESSION['cart'][$userId][$cartKey])) {
            unset($_SESSION['cart'][$userId][$cartKey]);
            
            // מחיקת מוצר מהטבלה cart
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
            $stmt->bind_param("iii", $userId, $productId, $size);
            $stmt->execute();
            
            return true;
        }
        return false;
    }

    // פונקציה לעדכון כמות מוצר בעגלה
    function updateCart($userId, $productId, $size, $quantity) {
        global $conn;
        $cartKey = $productId . '_' . $size;
        
        if ($quantity > 0) {
            // עדכון כמות בסשן
            $_SESSION['cart'][$userId][$cartKey]['quantity'] = $quantity;

            // עדכון כמות בטבלת cart
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ? AND size = ?");
            $stmt->bind_param("iiii", $quantity, $userId, $productId, $size);
            $stmt->execute();

            return true;
        } else {
            // מחיקת מוצר אם הכמות היא 0
            return removeFromCart($userId, $productId, $size);
        }
    }

    // פונקציה לריקון העגלה
    function emptyCart($userId) {
        global $conn;
        
        // ריקון עגלה בסשן
        if (isset($_SESSION['cart'][$userId])) {
            unset($_SESSION['cart'][$userId]);
            
            // ריקון עגלה בטבלת cart
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            
            return true;
        }
        return false;
    }

    // בדיקה אם התבצעה בקשה להוספת מוצר לעגלה
    if (isset($_GET['add_to_cart']) && isset($_GET['size']) && isset($_SESSION['user'])) {
        $productId = $_GET['add_to_cart'];
        $size = $_GET['size'];
        $userId = $_SESSION['user'];

        if (addToCart($userId, $productId, $size)) {
            header('Location: products.php?cart_open=true'); // שמירת העגלה פתוחה אחרי הוספה
            exit();
        }
    }

    // בדיקה אם התבצעה בקשה לעדכון כמות מוצר בעגלה
    if (isset($_GET['update_quantity']) && isset($_GET['product_id']) && isset($_GET['size']) && isset($_SESSION['user'])) {
        $quantity = intval($_GET['update_quantity']);
        $productId = intval($_GET['product_id']);
        $size = $_GET['size'];
        $userId = $_SESSION['user'];

        if (updateCart($userId, $productId, $size, $quantity)) {
            header('Location: products.php?cart_open=true'); // שמירת העגלה פתוחה אחרי עדכון כמות
            exit();
        }
    }

    // בדיקה אם התבצעה בקשה למחיקת מוצר מהעגלה
    if (isset($_GET['remove_from_cart']) && isset($_GET['size']) && isset($_SESSION['user'])) {
        $productId = $_GET['remove_from_cart'];
        $size = $_GET['size'];
        $userId = $_SESSION['user'];

        if (removeFromCart($userId, $productId, $size)) {
            header('Location: products.php?cart_open=true'); // שמירת העגלה פתוחה אחרי מחיקה
            exit();
        }
    }

    // בדיקה אם התבצעה בקשה לריקון העגלה
    if (isset($_GET['empty_cart']) && isset($_SESSION['user'])) {
        $userId = $_SESSION['user'];
        if (emptyCart($userId)) {
            header('Location: products.php?cart_open=true'); // שמירת העגלה פתוחה אחרי ריקון
            exit();
        }
    }

    // בדיקה אם יש פרמטר של מותג ב URL
    $brand = isset($_GET['brand']) ? $_GET['brand'] : '';

    // הכנת שאילתה דינמית בהתאם למותג הנבחר
    if ($brand) {
        $stmt = $conn->prepare("SELECT * FROM Products WHERE brand = ?");
        $stmt->bind_param("s", $brand);
    } else {
        $stmt = $conn->prepare("SELECT * FROM Products");
    }

    $stmt->execute();
    $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - SneakerSpot</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: white;
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 50px;
            max-width: 1200px;
        }
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .product-card {
            flex: 1 0 21%;
            max-width: 300px;
            margin-bottom: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            background-color: #fff;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .product-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .product-card img:hover {
            transform: scale(1.05);
        }
        .product-info {
            padding: 15px;
            text-align: center;
        }
        .product-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
        }
        .product-description {
            font-size: 14px;
            color: #777;
            margin-bottom: 15px;
        }
        .product-price {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #407e60;
        }
        .btn-add-to-cart {
            background-color: #000;
            color: #fff;
            border-radius: 5px;
            padding: 10px 20px;
            text-transform: uppercase;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .btn-add-to-cart:hover {
            background-color: #a83a40;
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
        #cartToggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1100;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        #cartToggle:hover {
            background-color: #2f5e43;
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
        .list-group-item {
            border: none;
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
</head>
<body>
    <?php include('navbar.php'); include('accessibility_menu.php') ?>
    <div class="container">
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                    <div class="product-info">
                        <h5 class="product-title"><?php echo $product['name']; ?></h5>
                        <p class="product-description"><?php echo $product['description']; ?></p>
                        <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                        <form action="products.php" method="GET">
                            <input type="hidden" name="add_to_cart" value="<?php echo $product['id']; ?>">
                            <select name="size" class="form-control mb-3" required>
                                <option value="">Select Size</option>
                                <?php for ($size = 36; $size <= 46; $size++): ?>
                                    <option value="<?php echo $size; ?>"><?php echo $size; ?></option>
                                <?php endfor; ?>
                            </select>
                            <button type="submit" class="btn btn-add-to-cart">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <button id="cartToggle" class="btn btn-secondary" onclick="toggleCart()">Cart <i class="fa fa-shopping-cart"></i>
        <?php if (isset($_SESSION['user']) && isset($_SESSION['cart'][$_SESSION['user']]) && count($_SESSION['cart'][$_SESSION['user']]) > 0): ?>
            <span class="cart-count"><?php echo array_sum(array_column($_SESSION['cart'][$_SESSION['user']], 'quantity')); ?></span>
        <?php endif; ?>
    </button>
    <script>
        function toggleCart() {
            const cartSidebar = document.getElementById('cartSidebar');
            cartSidebar.classList.toggle('open');
            localStorage.setItem('cart_open', cartSidebar.classList.contains('open'));
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('cart_open') === 'true' || window.location.search.includes('cart_open=true')) {
                document.getElementById('cartSidebar').classList.add('open');
            }
        });
    </script>
    <?php include('cart.php'); ?>
    <?php include('footer.php'); ?>
</body>
</html>
<?php
ob_end_flush();
?>
