<?php
    session_start();
    include('DBconnect.php');

    // אם המשתמש כבר מחובר, יועבר לפרופיל שלו
    if (isset($_SESSION['user'])) {
        header('Location: profile.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        // בדיקה אם המשתמש קיים בדאטאבייס
        $stmt = $conn->prepare("SELECT id, password FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($userId, $hashedPassword);
            $stmt->fetch();
            
            // בדיקת סיסמא
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user'] = $userId;
                header('Location: profile.php');
                exit();
            } else {
                $error = 'ERROR! Invalid email or password';
            }
        } else {
            $error = 'ERROR! Invalid email or password';
        }

        $stmt->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SneakerSpot</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .container {
            max-width: 900px;
        }
        .card {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .form-outline {
            margin-bottom: 1.5rem;
        }
        .btn-custom {
            background-color: #c44950;
            color: #fff;
            border-radius: 50px;
            padding: 10px 30px;
        }
        .btn-custom:hover {
            background-color: #a83a40;
            color: #fff;
        }
        #error {
            color: red;
        }
        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .image-container img {
            border-radius: 1rem 0 0 1rem;
        }
    </style>
</head>
<body>
    <?php 
        include('navbar.php');
        include("accessibility_menu.php");
    ?>
    <div class="container py-5">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign into your account</p>
                                <form action="login.php" method="POST">
                                    <div class="form-outline mb-4">
                                        <label class="form-label">Email address</label>
                                        <input type="email" name="email" class="form-control form-control-lg" required />
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility()">
                                                    <i class="fa fa-eye-slash" id="togglePasswordIcon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (isset($error)) { echo '<p id="error">' . $error . '</p>'; } ?>
                                    <div class="pt-1 mb-4">
                                        <button class="btn btn-custom btn-lg btn-block" type="submit">Login</button>
                                    </div>
                                    <p class="mb-5 pb-lg-2" style="color: #c44950;">Don't have an account? <a href="signUp.php" style="color: #c44950;">Sign Up here</a></p>
                                    <p class="mb-5 pb-lg-2" style="color: #c44950;"><a href="forgot_password.php" style="color: #c44950;">Forgot Password?</a></p>
                                </form>
                            </div>
                            <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2 image-container">
                                <img src="assets/images/login.png" class="img-fluid" alt="Login Image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('togglePasswordIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            } else {
                passwordField.type = 'password';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
    <?php include('footer.php');?>
</body>
</html>