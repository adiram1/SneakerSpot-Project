<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!function_exists('isLoggedIn')) {
        function isLoggedIn() {
            return isset($_SESSION['user']);
        }
    }

    if (!function_exists('isAdmin')) {
        function isAdmin() {
            return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
        }
    }

    if (!function_exists('checkDbConnection')) {
        function checkDbConnection() {
            global $conn;
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
        }
    }

    if (!function_exists('getUserById')) {
        function getUserById($id) {
            global $conn;
            checkDbConnection();
            
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->num_rows > 0 ? $result->fetch_assoc() : null;
        }
    }

    if (!function_exists('getProducts')) {
        function getProducts() {
            global $conn;
            checkDbConnection();
            
            $result = $conn->query("SELECT * FROM products");
            return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
        }
    }

    if (!function_exists('getProductById')) {
        function getProductById($id) {
            global $conn;
            checkDbConnection();
            
            $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->num_rows > 0 ? $result->fetch_assoc() : null;
        }
    }

    if (!function_exists('addToCart')) {
        function addToCart($userId, $productId, $size) {
            if (!isset($_SESSION['cart'][$userId])) {
                $_SESSION['cart'][$userId] = [];
            }
            
            $cartKey = $productId . '_' . $size;
            
            if (!isset($_SESSION['cart'][$userId][$cartKey])) {
                $_SESSION['cart'][$userId][$cartKey] = ['product_id' => $productId, 'size' => $size, 'quantity' => 1];
            } else {
                $_SESSION['cart'][$userId][$cartKey]['quantity']++;
            }
        }
    }

    if (!function_exists('getCartItems')) {
        function getCartItems($userId) {
            global $conn;
            $items = [];

            if (isset($_SESSION['cart'][$userId]) && !empty($_SESSION['cart'][$userId])) {
                foreach ($_SESSION['cart'][$userId] as $cartKey => $details) {
                    list($productId, $size) = explode('_', $cartKey);
                    
                    $stmt = $conn->prepare("SELECT * FROM Products WHERE id = ?");
                    $stmt->bind_param('i', $productId);
                    $stmt->execute();
                    $product = $stmt->get_result()->fetch_assoc();
                    
                    if ($product) {
                        $items[] = [
                            'product' => $product,
                            'size' => $size,
                            'quantity' => $details['quantity'],
                            'subtotal' => $product['price'] * $details['quantity']
                        ];
                    }
                }
            }
            return $items;
        }
    }
?>
