  <?php

header("Content-Type: application/json");


// ============================================================
// DATABASE CONNECTION
// ============================================================

include "db_connect.php";


// ============================================================
// ONLY POST REQUEST
// ============================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);

    exit;

}


// ============================================================
// GET ORDER TYPE
// ============================================================

$orderType = trim($_POST["order_type"] ?? "");


// ============================================================
// GET CUSTOMER INFORMATION
// ============================================================

$name =
    trim($_POST["name"] ?? "");

$phone =
    trim($_POST["phone"] ?? "");

$address =
    trim($_POST["address"] ?? "");

$details =
    trim($_POST["details"] ?? "");


// ============================================================
// VALIDATE NAME
// ============================================================

if ($name === "") {

    echo json_encode([
        "success" => false,
        "message" => "Please enter your name."
    ]);

    exit;

}


// ============================================================
// VALIDATE PHONE
// ============================================================

if (!preg_match('/^[0-9]{10}$/', $phone)) {

    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid 10-digit phone number."
    ]);

    exit;

}


// ============================================================
// CUSTOM ORDER
// ============================================================

if ($orderType === "custom") {


    // Custom order requires details
    if ($details === "") {

        echo json_encode([
            "success" => false,
            "message" => "Please explain the cake you need."
        ]);

        exit;

    }


    // Custom orders do not have a fixed price
    $totalAmount = 0;


    // Save custom order as JSON
    $cartItems = json_encode([
        [
            "name" => "Custom Cake Request",
            "price" => 0
        ]
    ], JSON_UNESCAPED_UNICODE);


    $status = "Pending";


    // SQL
    $sql = "INSERT INTO orders
            (
                customer_name,
                phone,
                address,
                order_details,
                cart_items,
                total_amount,
                status,
                order_date
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";


    $stmt = $conn->prepare($sql);


    if (!$stmt) {

        echo json_encode([
            "success" => false,
            "message" => "Database error: " . $conn->error
        ]);

        exit;

    }


    $stmt->bind_param(
        "sssssds",
        $name,
        $phone,
        $address,
        $details,
        $cartItems,
        $totalAmount,
        $status
    );


    if ($stmt->execute()) {

        echo json_encode([
            "success" => true,
            "message" => "Your custom order request has been received successfully. We will contact you soon."
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => "Failed to save custom order."
        ]);

    }


    $stmt->close();
    $conn->close();

    exit;

}


// ============================================================
// NORMAL CART ORDER
// ============================================================

if ($orderType === "cart") {


    // Address required
    if ($address === "") {

        echo json_encode([
            "success" => false,
            "message" => "Please enter your delivery address."
        ]);

        exit;

    }


    // Get cart
    $cart =
        $_POST["cart"] ?? "";


    if ($cart === "") {

        echo json_encode([
            "success" => false,
            "message" => "Your cart is empty."
        ]);

        exit;

    }


    // Decode JSON
    $cartArray =
        json_decode($cart, true);


    if (
        !is_array($cartArray) ||
        count($cartArray) === 0
    ) {

        echo json_encode([
            "success" => false,
            "message" => "Invalid cart data."
        ]);

        exit;

    }


    // ========================================================
    // RECALCULATE TOTAL
    // ========================================================

    $calculatedTotal = 0;


    foreach ($cartArray as $item) {


        if (
            !isset($item["name"]) ||
            !isset($item["price"])
        ) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid product information."
            ]);

            exit;

        }


        $itemName =
            trim($item["name"]);


        $itemPrice =
            floatval($item["price"]);


        if (
            $itemName === "" ||
            $itemPrice <= 0
        ) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid product price."
            ]);

            exit;

        }


        $calculatedTotal += $itemPrice;

    }


    // ========================================================
    // CONVERT CART TO JSON
    // ========================================================

    $cartItems =
        json_encode(
            $cartArray,
            JSON_UNESCAPED_UNICODE
        );


    // ========================================================
    // ORDER STATUS
    // ========================================================

    $status = "Pending";


    // ========================================================
    // INSERT ORDER
    // ========================================================

    $sql = "INSERT INTO orders
            (
                customer_name,
                phone,
                address,
                order_details,
                cart_items,
                total_amount,
                status,
                order_date
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";


    $stmt =
        $conn->prepare($sql);


    if (!$stmt) {

        echo json_encode([
            "success" => false,
            "message" => "Database error: " . $conn->error
        ]);

        exit;

    }


    // ========================================================
    // BIND DATA
    // ========================================================

    $stmt->bind_param(
        "sssssds",
        $name,
        $phone,
        $address,
        $details,
        $cartItems,
        $calculatedTotal,
        $status
    );


    // ========================================================
    // EXECUTE
    // ========================================================

    if ($stmt->execute()) {

        echo json_encode([
            "success" => true,
            "message" => "Order placed successfully! We will contact you soon."
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => "Failed to place order."
        ]);

    }


    // ========================================================
    // CLOSE
    // ========================================================

    $stmt->close();

    $conn->close();

    exit;

}


// ============================================================
// INVALID ORDER TYPE
// ============================================================

echo json_encode([
    "success" => false,
    "message" => "Invalid order type."
]);


$conn->close();

?>