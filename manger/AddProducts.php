<?php include('../manger/nav.php'); ?>
<?php 
    session_start();
    include '../connect.php';
    if (!$_SESSION['adminUserName']) {
        header("Location: LogInToAdmin.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        input[type="file"] {
            margin-top: 8px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            border: none;
            padding: 10px;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }
    </style>
</head>

<body>

    <div style="text-align: center;">
        <h1>Add Product</h1>
    </div>

    <div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require('../connect.php');

            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $stock_quantity = (int)$_POST['stock_quantity'];
            $price = (float)$_POST['price'];

            $targetDir = '../church/uploads/';

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
                echo "in";
                print_r(mkdir($targetDir, 0755, true));
            }

            $targetFile = $targetDir . basename($_FILES["photo"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (!empty($_FILES["photo"]["name"])) {
                $check = getimagesize($_FILES["photo"]["tmp_name"]);
                if ($check === false) {
                    echo '<p class="error-message">Error: File is not an image.</p>';
                } else {
                    if ($_FILES["photo"]["size"] > 5000000) {
                        echo '<p class="error-message">Error: Sorry, your file is too large.</p>';
                    } else {
                        $allowedExtensions = array("jpg", "jpeg", "png", "gif");
                        if (!in_array($imageFileType, $allowedExtensions)) {
                            echo '<p class="error-message">Error: Sorry, only JPG, JPEG, PNG, and GIF files are allowed.</p>';
                        } else {
                            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                                $photoName = basename($_FILES["photo"]["name"]);
                                $sql = "INSERT INTO products (name, description, photo, stock_quantity, price) VALUES ('$name', '$description', 'church/uploads/$photoName', $stock_quantity, $price)";

                                if ($conn->query($sql) === TRUE) {
                                    echo '<p class="success-message">Product added successfully!</p>';
                                } else {
                                    echo '<p class="error-message">Error adding product to database: ' . $conn->error . '</p>';
                                }
                            } else {
                                echo '<p class="error-message">Error: Unable to move the uploaded file. Check directory permissions.</p>';
                            }
                        }
                    }
                }
            }

            $conn->close();
        }
        ?>

        <form action="/church/church/manger/addProducts.php" method="post" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" name="name" required>
            <br>
            <label for="description">Product Description:</label>
            <textarea name="description" required></textarea>
            <br>
            <label for="photo">Product Photo:</label>
            <input type="file" name="photo" accept="image/*" required>
            <br>
            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" name="stock_quantity" required>
            <br>
            <label for="price">Product Price:</label>
            <input type="number" step="0.01" name="price" required>
            <br>
            <input type="submit" value="Add Product">
        </form>
    </div>

</body>

</html>
