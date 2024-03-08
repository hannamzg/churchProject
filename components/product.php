<?php
$productId = $_GET['id'];

function getProductDetails($productId) {
    require('../connect.php');
    $productId = $conn->real_escape_string($productId);

    $query = "SELECT * FROM products WHERE product_id = $productId";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $conn->close();
        return $row;
    } else {
        $conn->close();
        return false;
    }
}

$productDetails = getProductDetails($productId);

if ($productDetails) {
    ?>
    <head>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <style>
 
        </style>
    </head>
    <div class="card" style="width: 18rem;">
        <img class="card-img-top" src="<?php echo '/shop/shop/' . $productDetails['photo']; ?>" alt="Product Photo">
        <div class="card-body">
            <h5 class="card-title"><?php echo $productDetails['name']; ?></h5>
            <p class="card-text"><?php echo $productDetails['description']; ?></p>
            <p class="card-text">Price: <?php echo $productDetails['price']; ?></p>
            <p class="card-text">In Stock: <?php echo $productDetails['stock_quantity']; ?></p>
            <p class="card-text">Created At: <?php echo $productDetails['created_at']; ?></p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>

    <?php
} else {
    // Product not found
    echo "<p>Product not found</p>";
}
?>

<!-- Add Bootstrap JS and Popper.js scripts here -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>