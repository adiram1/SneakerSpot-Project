<?php
    session_start();
    include('DBconnect.php');

    // בדיקה אם המשתמש מחובר
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
    
    // קבלת נתוני המשתמש מהמסד נתונים
    $userId = $_SESSION['user'];
    $stmt = $conn->prepare("SELECT username, email FROM Users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($dbUsername, $dbEmail);
    $stmt->fetch();
    $stmt->close();

    // קבלת היסטוריית הזמנות
    $orders = [];
    $stmt = $conn->prepare("SELECT id, total, status, created_at FROM Orders WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) $orders[] = $row;
    $stmt->close();

    // קבלת נתוני גרף סטטיסטיקה
    $stats = [];
    $stmt = $conn->prepare("SELECT MONTH(created_at) as month, SUM(total) as total FROM Orders WHERE user_id = ? AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) GROUP BY MONTH(created_at)");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) $stats[] = $row;
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - SneakerSpot</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container {
            max-width: 1200px;
        }
        .card {
            margin-bottom: 30px;
            border-radius: 1rem;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
        .form-control {
            margin-bottom: 1rem;
        }
        .chart-container {
            width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>
    <?php 
        include('navbar.php');
        include('accessibility_menu.php');
    ?>
    <div class="container py-5">
        <h2 class="text-center mb-5">User Profile Dashboard</h2>
        <div class="row">
            <!-- User Information Card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">User Information</h4>
                        <form action="update_profile.php" method="POST">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($dbUsername); ?>" required>
                            
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($dbEmail); ?>" required>
                            
                            <button type="submit" class="btn btn-custom">Update</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order History Card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Order History</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['id']; ?></td>
                                        <td><?php echo $order['total']; ?> USD</td>
                                        <td><?php echo $order['status']; ?></td>
                                        <td><?php echo $order['created_at']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Chart -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Purchase Statistics</h4>
                        <div class="chart-container">
                            <canvas id="statsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include('footer.php'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('statsChart').getContext('2d');
            const statsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [<?php foreach ($stats as $stat) { echo '"' . date('F', mktime(0, 0, 0, $stat['month'], 10)) . '",'; } ?>],
                    datasets: [{
                        label: 'Total Purchases (USD)',
                        data: [<?php foreach ($stats as $stat) { echo $stat['total'] . ','; } ?>],
                        backgroundColor: 'rgba(76, 132, 255, 0.2)',
                        borderColor: 'rgba(76, 132, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
