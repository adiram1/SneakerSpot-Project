<?php
session_start();
include('DBconnect.php');
include('functions.php'); // הנחה שזו הקובץ שמכיל פונקציות כלליות כמו getCartItems

$cartItems = getCartItems($_SESSION['user'] ?? 0);
$total = 0;
$items = [];

foreach ($cartItems as $item) {
    if (!is_array($item) || !isset($item['product']['id'], $item['size'], $item['quantity'])) {
        continue; 
    }

    $items[] = [
        'product' => $item['product'],
        'size' => $item['size'],
        'quantity' => $item['quantity'],
        'subtotal' => $item['product']['price'] * $item['quantity']
    ];
    $total += $item['product']['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user'] ?? 0;
    $status = 'Pending';

    // הכנסת ההזמנה לטבלת Orders
    $stmt = $conn->prepare("INSERT INTO Orders (user_id, total, status) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $user_id, $total, $status);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // הכנסת פריטים בטבלת OrderItems
    foreach ($cartItems as $item) {
        if (!is_array($item) || !isset($item['product']['id'], $item['size'], $item['quantity'])) {
            continue;
        }

        $stmt = $conn->prepare("INSERT INTO OrderItems (order_id, product_id, quantity, size) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $order_id, $item['product']['id'], $item['quantity'], $item['size']);
        $stmt->execute();
    }

    // ריקון העגלה
    $_SESSION['cart'] = [];
    header('Location: order_summary.php?order_id=' . $order_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SneakerSpot</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        .checkout-container {
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            border: 2px solid #c44950;
        }
        .product-image {
            width: 50px;
            height: auto;
            border-radius: 8px;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .total-row {
            font-size: 18px;
            font-weight: bold;
        }
        .btn-place-order {
            background-color: #407e60;
            color: #fff;
            border-radius: 5px;
            padding: 10px 20px;
            text-transform: uppercase;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            width: 100%;
        }
        .btn-place-order:hover {
            background-color: #2f5e43;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #407e60;
        }
        .payment-details {
            margin-top: 30px;
        }
        .form-label {
            font-weight: bold;
        }
        .product-name {
            font-size: 16px;
            font-weight: bold;
        }
        .order-summary {
            margin-bottom: 30px;
        }
        .cart-sidebar:not(.open) {
            display: none;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="checkout-container">
        <div class="text-center mb-4">
            <img src="assets/images/logo.png" class="h-75 w-75" alt="Logo">
        </div>
        <h2 class="mb-4 text-center">Checkout</h2>
        <div class="order-summary">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <img src="assets/images/<?php echo $item['product']['image']; ?>" class="product-image me-2" alt="<?php echo $item['product']['name']; ?>">
                                <span class="product-name"><?php echo $item['product']['name']; ?></span>
                            </td>
                            <td><?php echo $item['size']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['product']['price'], 2); ?></td>
                            <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="4" class="text-end">Total:</td>
                        <td>$<?php echo number_format($total, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="payment-details">
            <h4 class="mb-4">Payment Details</h4>
            <form method="POST">
                <div class="mb-3 col-12">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter your full name" required>
                </div>
                <div class="mb-3 col-12">
                    <label for="cardNumber" class="form-label">Credit Card Number</label>
                    <input type="text" class="form-control" id="cardNumber" placeholder="4580 1234 1234 1234 " required>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="expiry" class="form-label">Expiration Date</label>
                        <input type="text" class="form-control" id="expiry" placeholder="MM/YY" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="cvv" class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cvv" placeholder="123" required>
                    </div>
                </div>
                <div class="mb-3 col-12">
                    <label for="address" class="form-label">Shipping Address</label>
                    <textarea class="form-control" id="address" rows="3" placeholder="Enter your shipping address" required></textarea>
                </div>
                <button type="submit" class="btn btn-place-order">Place Order</button>
            </form>
        </div>
    </div>
    <?php include('footer.php'); ?>
</body>
</html>
