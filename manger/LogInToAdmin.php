<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../connect.php';

    $username = $conn->real_escape_string($_POST["username"]);
    $password = $conn->real_escape_string($_POST["password"]);

    $sql = "SELECT admin_Id, password FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["password"]; 
        //the password = Hanna1234@
        //to change the password go to w3school and use  md5
        if (md5($password) ==  $hashed_password) { 
            $_SESSION['adminUserName'] = true;
            header("Location: index.php");
            exit();
        } else {
            echo "<p class='error-message'>Invalid username or password.</p>";
        }
    } else {
        echo "<p class='error-message'>Invalid username or password.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Admin</title>
    <style> body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; display: flex; align-items: center; justify-content: center; height: 100vh; } .login-container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 300px; text-align: center; } h2 { color: #333; } label { display: block; margin-top: 10px; } input { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 10px; box-sizing: border-box; } input[type="submit"] { background-color: #4caf50; color: white; cursor: pointer; } input[type="submit"]:hover { background-color: #45a049; } p.error-message { color: red; margin-top: 10px; } </style>
</head>
<body>

<div class="login-container">
    <h2>Login to Admin</h2>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <!-- Include CSRF token in the form -->

        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>
