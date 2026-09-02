<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../db_connect.php";

$sql = "
    SELECT 
        id,
        customer_name,
        phone,
        address,
        order_details,
        cart_items,
        total_amount,
        status,
        order_date
    FROM orders
    ORDER BY order_date DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Orders | MP Sweet Treats</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f5f5;
            color: #333;
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

        .back-btn {
            color: white;
            text-decoration: none;
            background: #7a5035;
            padding: 10px 15px;
            border-radius: 6px;
        }

        .container {
            padding: 30px;
        }

        .page-title {
            margin-bottom: 25px;
        }

        .page-title h2 {
            color: #5a3825;
            margin-bottom: 5px;
        }

        .page-title p {
            color: #777;
        }

        .orders-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
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
            display: inline-block;
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

        .view-btn {
            background: #5a3825;
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 13px;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }

    </style>

</head>

<body>

<header class="header">

    <h1>MP | Sweet Treats</h1>

    <a href="index.php" class="back-btn">
        Dashboard
    </a>

</header>


<main class="container">

    <div class="page-title">

        <h2>All Orders</h2>

        <p>View all customer orders.</p>

    </div>


    <section class="orders-box">

        <?php if ($result && $result->num_rows > 0): ?>

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                    <?php while ($order = $result->fetch_assoc()): ?>

                        <?php
                        $statusClass = strtolower($order["status"]);
                        ?>

                        <tr>

                            <td>
                                #<?php echo $order["id"]; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($order["customer_name"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($order["phone"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($order["address"]); ?>
                            </td>

                            <td>
                                TZS <?php echo number_format($order["total_amount"]); ?>
                            </td>

                            <td>

                                <span class="status <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($order["status"]); ?>
                                </span>

                            </td>

                           <td>
                       <?php echo htmlspecialchars($order["order_date"]); ?>
                           </td>

                           <td>
                        <a href="view_order.php?id=<?php echo $order["id"]; ?>" class="view-btn">
                          View
                       </a>
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