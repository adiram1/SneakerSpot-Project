<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "SneakerSpotDB";

    // יצירת חיבור למסד הנתונים
    $conn = new mysqli($servername, $username, $password, $dbname);

    // בדיקת החיבור
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error . "<br>");
    } else {
        echo "Connected successfully<br>";

        // יצירת מסד נתונים במידה והוא לא קיים
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
        if ($conn->query($sql) === TRUE) {
            echo "Database created successfully<br>";
        } else {
            echo "Error creating database: " . $conn->error . "<br>";
        }

        // שימוש במסד הנתונים
        $conn->select_db($dbname);

        // יצירת טבלת Users
        $tblUsers = "CREATE TABLE IF NOT EXISTS Users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            password VARCHAR(255) NOT NULL,
            is_admin TINYINT(1) DEFAULT 0
        )";

        if ($conn->query($tblUsers) === TRUE) {
            echo "Table Users created successfully<br>";
        } else {
            echo "Error creating Users table: " . $conn->error . "<br>";
        }

        // יצירת טבלת Products
        $tblProducts = "CREATE TABLE IF NOT EXISTS Products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            price DECIMAL(10, 2) NOT NULL,
            image VARCHAR(255),
            brand VARCHAR(255) NOT NULL
        )";

        if ($conn->query($tblProducts) === TRUE) {
            echo "Table Products created successfully<br>";
        } else {
            echo "Error creating Products table: " . $conn->error . "<br>";
        }

        // יצירת טבלת ProductSizes
        $tblProductSizes = "CREATE TABLE IF NOT EXISTS ProductSizes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            size INT NOT NULL,
            FOREIGN KEY (product_id) REFERENCES Products(id)
        )";

        if ($conn->query($tblProductSizes) === TRUE) {
            echo "Table ProductSizes created successfully<br>";
        } else {
            echo "Error creating ProductSizes table: " . $conn->error . "<br>";
        }

        // יצירת טבלת Orders
        $tblOrders = "CREATE TABLE IF NOT EXISTS Orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            total DECIMAL(10, 2) NOT NULL,
            status VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES Users(id)
        )";

        if ($conn->query($tblOrders) === TRUE) {
            echo "Table Orders created successfully<br>";
        } else {
            echo "Error creating Orders table: " . $conn->error . "<br>";
        }

        // יצירת טבלת OrderItems
        $tblOrderItems = "CREATE TABLE IF NOT EXISTS OrderItems (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            size INT NOT NULL,
            FOREIGN KEY (order_id) REFERENCES Orders(id),
            FOREIGN KEY (product_id) REFERENCES Products(id)
        )";

        if ($conn->query($tblOrderItems) === TRUE) {
            echo "Table OrderItems created successfully<br>";
        } else {
            echo "Error creating OrderItems table: " . $conn->error . "<br>";
        }

        // יצירת טבלת Categories
        $tblCategories = "CREATE TABLE IF NOT EXISTS Categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL
        )";

        if ($conn->query($tblCategories) === TRUE) {
            echo "Table Categories created successfully<br>";
        } else {
            echo "Error creating Categories table: " . $conn->error . "<br>";
        }

        // הכנסת נתוני קטגוריות לדוגמה
        $categories = ['Men', 'Women', 'Kids', 'Sports', 'Casual', 'Formal'];
        foreach ($categories as $category) {
            $sql = "INSERT INTO Categories (name) VALUES ('$category')";
            if ($conn->query($sql) === TRUE) {
                echo "Category '$category' created successfully<br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
            }
        }

        // יצירת טבלת ProductCategories
        $tblProductCategories = "CREATE TABLE IF NOT EXISTS ProductCategories (
            product_id INT NOT NULL,
            category_id INT NOT NULL,
            FOREIGN KEY (product_id) REFERENCES Products(id),
            FOREIGN KEY (category_id) REFERENCES Categories(id),
            PRIMARY KEY (product_id, category_id)
        )";

        if ($conn->query($tblProductCategories) === TRUE) {
            echo "Table ProductCategories created successfully<br>";
        } else {
            echo "Error creating ProductCategories table: " . $conn->error . "<br>";
        }

        // יצירת טבלת Reviews
        $tblReviews = "CREATE TABLE IF NOT EXISTS Reviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            product_id INT NOT NULL,
            rating INT NOT NULL,
            comment TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES Users(id),
            FOREIGN KEY (product_id) REFERENCES Products(id)
        )";

        if ($conn->query($tblReviews) === TRUE) {
            echo "Table Reviews created successfully<br>";
        } else {
            echo "Error creating Reviews table: " . $conn->error . "<br>";
        }

        // יצירת טבלת Cart
        $tblCart = "CREATE TABLE IF NOT EXISTS Cart (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            size INT NOT NULL,
            FOREIGN KEY (user_id) REFERENCES Users(id),
            FOREIGN KEY (product_id) REFERENCES Products(id)
        )";

        if ($conn->query($tblCart) === TRUE) {
            echo "Table Cart created successfully<br>";
        } else {
            echo "Error creating Cart table: " . $conn->error . "<br>";
        }

    }

    $conn->close();
?>
