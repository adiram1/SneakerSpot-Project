<?php
    // התחלת סשן
    session_start();

    // ניקוי כל המשתנים של הסשן
    $_SESSION = [];

    // הריסת הסשן
    session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="alert alert-success" role="alert">
            You have successfully logged out. You are being redirected to the homepage.
        </div>
    </div>

    <script>
        // הפניה לדף הבית אחרי 3 שניות
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 3000);
    </script>
</body>
</html>
