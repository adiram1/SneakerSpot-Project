<?php
    session_start();
    include('DBconnect.php');

    //  בדיקה אם קיים
    //  order_id
    //   בפרמטרים של ה 
    //   GET
    if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
        header('Location: index.php');
        exit();
    }

    $orderId = intval($_GET['order_id']);

    // הבאת פרטי ההזמנה מהדאטאבייס
    $stmt = $conn->prepare("SELECT * FROM Orders WHERE id = ?");
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();

    // בדיקה אם פרטי ההזמנה נמצאו
    if (!$order) {
        header('Location: index.php');
        exit();
    }

    // הבאת המוצרים מההזמנה
    $stmt = $conn->prepare("SELECT OrderItems.*, Products.image, Products.name, Products.price FROM OrderItems INNER JOIN Products ON OrderItems.product_id = Products.id WHERE order_id = ?");
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $orderItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary - SneakerSpot</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        .order-summary-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            border: 2px solid #c44950;
        }
        .order-summary-container h2 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .list-group-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .product-image {
            width: 60px;
            height: auto;
            margin-right: 15px;
        }
        .total-amount {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <?php include('accessibility_menu.php'); ?>

    <div class="order-summary-container">
        <div class="text-center mb-4">
            <img src="assets/images/logo.png" class="h-75 w-75" alt="Logo">
        </div>
        <h2>Order Summary</h2>
        <p>Thank you for your purchase! Here is the summary of your order:</p>
        <ul class="list-group mb-4">
            <?php foreach ($orderItems as $item): ?>
                <li class="list-group-item">
                    <div>
                        <img src="assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="product-image">
                        <span><?php echo $item['name']; ?></span>
                        <span>Size: <?php echo $item['size']; ?></span>
                    </div>
                    <span><?php echo $item['quantity']; ?> x $<?php echo number_format($item['price'], 2); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <h4 class="total-amount">Total: $<?php echo number_format($order['total'], 2); ?></h4>
    </div>
    <?php include('footer.php'); ?>
</body>
</html>