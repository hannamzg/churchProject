<?php
    if (isset($_GET['logout'])) {
        unset($_SESSION['adminUserName']);
    
        session_destroy();
    
        header("Location: LogInToAdmin.php");
        exit();
    }
?>



    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        nav {
            background-color: #333;
            overflow: hidden;
        }

        nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #ddd;
            color: black;
        }

        @media (max-width: 600px) {
            nav a {
                float: none;
                display: block;
                text-align: left;
            }
        }
    </style>
<link rel="icon" href="http://localhost/church/church/img/CrossIcon.png" >

<nav>
    <a href="../manger/index.php">Home</a>
    <!-- <a href="../manger/AddProducts.php">Add Product</a> -->
    <a href="../manger/AddToMainImgSilder.php">Add to main silder img </a>
    <a href="../manger/AddToQuestions.php">Add to main AddToQuestions </a>
    <a href="../manger/mangeText.php">Add to main mangeText </a>
    <a href="../index.php">normal web site </a>
    <a href="?logout" style="float: right; color: red;">Logout</a>

</nav>