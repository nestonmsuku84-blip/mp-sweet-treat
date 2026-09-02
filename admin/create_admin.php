 <?php

require_once "../db_connect.php";

$username = "admin";
$password = "12345";

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO admin_users (username, password_hash)
     VALUES (?, ?)"
);

$stmt->bind_param("ss", $username, $passwordHash);

if ($stmt->execute()) {
    echo "Admin account created successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>
