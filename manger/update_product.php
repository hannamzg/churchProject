<?php 
    session_start();
    include '../connect.php';
    if (!$_SESSION['adminUserName']) {
        header("Location: LogInToAdmin.php");
        exit();
    }
?>
<?php
session_start();
include '../../church/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id = $conn->real_escape_string($_POST['id']);
    $newName = $conn->real_escape_string($_POST['editName']);
    $newDescription = $conn->real_escape_string($_POST['editDescription']);
    $newPrice = $conn->real_escape_string($_POST['editPrice']);
    $newStock = $conn->real_escape_string($_POST['editStock']);

    // Update query
    $updateSql = "UPDATE products SET name = '$newName', description = '$newDescription', price = '$newPrice', stock_quantity = '$newStock' WHERE product_id = '$Id'";

    // Execute the query
    if ($conn->query($updateSql) === TRUE) {
        die("Product updated successfully!" . $conn->error);
    }
    else{
        die("Error" . $conn->error);

    }

    header("Location: product_management.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
