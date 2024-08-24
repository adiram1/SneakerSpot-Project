<?php
    session_start();
    include('DBconnect.php');

    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userId = $_SESSION['user'];
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);

        // עדכון פרטי המשתמש בדאטאבייס
        $stmt = $conn->prepare("UPDATE Users SET username = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $email, $userId);

        if ($stmt->execute()) {
            header('Location: profile.php');
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $stmt->close();
    }
?>
