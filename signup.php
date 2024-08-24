<?php
    session_start();
    include('DBconnect.php');

    // אם המשתמש כבר מחובר, נבצע הפניה לפרופיל שלו
    if (isset($_SESSION['user'])) {
        header('Location: profile.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fullName = htmlspecialchars($_POST['fullName']);
        $address = htmlspecialchars($_POST['address']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $password = htmlspecialchars($_POST['password']);
        $confirmPassword = htmlspecialchars($_POST['confirmPassword']);

        // בדיקת סיסמאות תואמות
        if ($password !== $confirmPassword) {
            $error = 'ERROR! Passwords do not match';
        } else {
            // הכנסת משתמש חדש לדאטאבייס
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

            if ($stmt->execute()) {
                header('Location: login.php');
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - SneakerSpot</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .container {
            max-width: 800px;
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
            max-height: 100%;
            display: flex;
            align-items: flex-start;
            margin-top: 11%;
        }
        .image-container img {
            width: 100%;
            height: auto;
            border-radius: 2rem;
            object-fit: cover;
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
                                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Create Your Account</p>
                                <form class="mx-1 mx-md-4" action="signUp.php" method="POST">
                                    <div class="form-outline mb-4">
                                        <label class="form-label">Full Name:</label>
                                        <input type="text" name="fullName" class="form-control form-control-lg" required />
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label class="form-label">Address:</label>
                                        <input type="text" name="address" class="form-control form-control-lg" required />
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label class="form-label">Email address:</label>
                                        <input type="email" name="email" class="form-control form-control-lg" required />
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label class="form-label">Phone Number:</label>
                                        <input type="tel" name="phone" class="form-control form-control-lg" required />
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label class="form-label">Password:</label>
                                        <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('password', 'togglePasswordIcon')">
                                                <i class="fa fa-eye-slash" id="togglePasswordIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label class="form-label">Confirm Password:</label>
                                        <input type="password" name="confirmPassword" id="confirmPassword" class="form-control form-control-lg" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('confirmPassword', 'toggleConfirmPasswordIcon')">
                                                <i class="fa fa-eye-slash" id="toggleConfirmPasswordIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <?php if (isset($error)) { echo '<p id="error">' . $error . '</p>'; } ?>
                                    <div class="d-flex justify-content-center pt-1 mb-4">
                                        <button class="btn btn-custom btn-lg" type="submit">Sign Up</button>
                                    </div>
                                    <p class="text-center">Already have an account? <a href="login.php" style="color: #c44950;">Login here</a></p>
                                </form>
                            </div>
                            <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2 image-container">
                                <img src="assets/images/signup.png" alt="Sign Up Image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script>
        function togglePasswordVisibility(passwordFieldId, iconId) {
            const passwordField = document.getElementById(passwordFieldId);
            const passwordIcon = document.getElementById(iconId);
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>