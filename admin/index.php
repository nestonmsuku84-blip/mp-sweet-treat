<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../db_connect.php";

/* Get order statistics */
$totalOrders = 0;
$pendingOrders = 0;
$processingOrders = 0;
$completedOrders = 0;

$result = $conn->query("SELECT COUNT(*) AS total FROM orders");
if ($result) {
    $row = $result->fetch_assoc();
    $totalOrders = $row['total'];
}

$result = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'Pending'");
if ($result) {
    $row = $result->fetch_assoc();
    $pendingOrders = $row['total'];
}

$result = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'Processing'");
if ($result) {
    $row = $result->fetch_assoc();
    $processingOrders = $row['total'];
}

$result = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'Completed'");
if ($result) {
    $row = $result->fetch_assoc();
    $completedOrders = $row['total'];
}

/* Get recent orders */
$orders = $conn->query("
    SELECT id, customer_name, phone, total_amount, status, order_date
    FROM orders
    ORDER BY order_date DESC
    LIMIT 10
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | MP Sweet Treats</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        .header {
            background: #5a3825;
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
        }

        .header span {
            font-size: 14px;
        }

        .container {
            padding: 30px;
        }

        .welcome {
            margin-bottom: 25px;
        }

        .welcome h2 {
            color: #333;
            margin-bottom: 5px;
        }

        .welcome p {
            color: #777;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .card h3 {
            color: #777;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .card .number {
            font-size: 32px;
            font-weight: bold;
            color: #5a3825;
        }

        .orders-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .orders-section h2 {
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f8f8;
            color: #555;
        }

        td {
            color: #444;
        }

        .status {
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .processing {
            background: #cfe2ff;
            color: #084298;
        }

        .completed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }

        @media (max-width: 900px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .orders-section {
                overflow-x: auto;
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .header {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<header class="header">
    <h1>MP | Sweet Treats</h1>
    <span>Admin Dashboard</span>
</header>

<main class="container">

    <div class="welcome">
        <h2>Dashboard Overview</h2>
        <p>Manage and monitor customer orders.</p>
    </div>

    <section class="stats">

        <div class="card">
            <h3>Total Orders</h3>
            <div class="number">
                <?php echo $totalOrders; ?>
            </div>
        </div>

        <div class="card">
            <h3>Pending Orders</h3>
            <div class="number">
                <?php echo $pendingOrders; ?>
            </div>
        </div>

        <div class="card">
            <h3>Processing Orders</h3>
            <div class="number">
                <?php echo $processingOrders; ?>
            </div>
        </div>

        <div class="card">
            <h3>Completed Orders</h3>
            <div class="number">
                <?php echo $completedOrders; ?>
            </div>
        </div>

    </section>

    <section class="orders-section">

        <h2>Recent Orders</h2>

        <?php if ($orders && $orders->num_rows > 0): ?>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                    <?php while ($order = $orders->fetch_assoc()): ?>

                        <tr>

                            <td>
                                #<?php echo $order['id']; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($order['customer_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($order['phone']); ?>
                            </td>

                            <td>
                                TZS <?php echo number_format($order['total_amount']); ?>
                            </td>

                            <td>

                                <?php
                                $status = strtolower($order['status']);
                                ?>

                                <span class="status <?php echo $status; ?>">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>

                            </td>

                            <td>
                                <?php echo htmlspecialchars($order['order_date']); ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        <?php else: ?>

            <div class="empty">
                No orders found.
            </div>

        <?php endif; ?>

    </section>

</main>

</body>
</html>