<?php
include('DBconnect.php');

// הוספת מוצרים לדוגמה
$products = [
    ['Nike Air Max', 'Comfortable and stylish sports shoes', 120.00, 'nike_air_max.png', 'Nike'],
    ['Adidas Ultraboost', 'High performance running shoes', 180.00, 'adidas_ultraboost.png', 'Adidas'],
    ['Puma RS-X', 'Retro style sneakers', 90.00, 'puma_rsx.png', 'Puma'],
    ['Converse Chuck Taylor', 'Classic canvas sneakers', 50.00, 'converse_chuck_taylor.png', 'Converse'],
    ['New Balance 990', 'Premium quality running shoes', 150.00, 'new_balance_990.png', 'New Balance'],
    ['Reebok Classic', 'Timeless style sneakers', 70.00, 'reebok_classic.png', 'Reebok'],
];

$sizes = range(36, 46); // מידות מ-36 עד 46

foreach ($products as $product) {
    $stmt = $conn->prepare("INSERT INTO Products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $product[0], $product[1], $product[2], $product[3]);
    if ($stmt->execute()) {
        echo "Product {$product[0]} added successfully.<br>";

        $productId = $conn->insert_id;

        // הוספת מידות עבור המוצר
        foreach ($sizes as $size) {
            $sizeStmt = $conn->prepare("INSERT INTO ProductSizes (product_id, size) VALUES (?, ?)");
            $sizeStmt->bind_param("ii", $productId, $size);
            $sizeStmt->execute();
            $sizeStmt->close();
        }

    } else {
        echo "Error adding product {$product[0]}: " . $stmt->error . "<br>";
    }
    $stmt->close();
}

$conn->close();
?>
