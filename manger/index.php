<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="http://localhost/church/church/img/CrossIcon.png" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }

        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .card {
            margin: 10px;
        }

        .modal-body {
            overflow-y: auto;
        }
    </style>
    <title>Product Management</title>
</head>
<body>

<?php
    session_start();
    include '../connect.php';

    // Check if the admin is logged in
    if (!$_SESSION['adminUserName']) {
        header("Location: LogInToAdmin.php");
        exit();
    }

    // Retrieve all products from the database
    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);

    // Check if products are retrieved successfully
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }
?>

<?php include('../manger/nav.php'); ?>

<!-- Display products and buttons -->
<!-- <div class="product-container">
    <?php while ($productDetails = mysqli_fetch_assoc($result)) : ?>
        <div class="card" style="width: 18rem;">
            <img class="card-img-top" src="<?php echo '/church/church/' . $productDetails['photo']; ?>" alt="Product Photo">
            <div class="card-body">
                <h5 class="card-title"><?php echo $productDetails['name']; ?></h5>
                <p class="card-text"><?php echo $productDetails['description']; ?></p>
                <p class="card-text">Price: <?php echo $productDetails['price']; ?></p>
                <p class="card-text">In Stock: <?php echo $productDetails['stock_quantity']; ?></p>
                <p class="card-text">Created At: <?php echo $productDetails['created_at']; ?></p>
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#productModal_<?php echo $productDetails['id']; ?>">edit</a>
            </div>
        </div>


        <div class="modal fade" id="productModal_<?php echo $productDetails['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel"><?php echo $productDetails['name']; ?> Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="update_product.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $productDetails['product_id']; ?>">
                            <div class="form-group">
                                <label for="editName">Name:</label>
                                <input type="text" class="form-control" id="editName" name="editName" value="<?php echo $productDetails['name']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="editDescription">Description:</label>
                                <textarea class="form-control" id="editDescription" name="editDescription"><?php echo $productDetails['description']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="editPrice">Price:</label>
                                <input type="text" class="form-control" id="editPrice" name="editPrice" value="<?php echo $productDetails['price']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="editStock">In Stock:</label>
                                <input type="text" class="form-control" id="editStock" name="editStock" value="<?php echo $productDetails['stock_quantity']; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    <?php endwhile; ?>
</div> -->

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
