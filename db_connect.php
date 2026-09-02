 <?php

$host = "localhost";
$username = "admin";
$password = "12345";
$database = "sweettreats_clean";

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>