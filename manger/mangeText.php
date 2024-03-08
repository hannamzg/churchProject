<?php 
    session_start();
    include '../connect.php';
    if (!$_SESSION['adminUserName']) {
        header("Location: LogInToAdmin.php");
        exit();
    }
?>
<?php
require('../connect.php'); 
include('../manger/nav.php');

function addContent($conn, $page_id, $title, $content, $img) {
    $stmt = $conn->prepare("INSERT INTO content (pageID, title, content, img) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $page_id, $title, $content, $img);
    
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Function to edit content
function editContent($conn, $id, $title, $content, $img) {
    $stmt = $conn->prepare("UPDATE content SET title=?, content=?, img=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $content, $img, $id);
    
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}

function deleteContent($conn, $id) {
    echo "ID: " . $id; // Check if ID is correct

    // Query to fetch the filename based on the ID
    $img = '';
    $stmt = $conn->prepare("SELECT img FROM content WHERE id=?");
    echo "Statement: " . $stmt->queryString; // Check the SQL statement
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($img);
    
    // Fetch the filename
    $stmt->fetch();
    $stmt->close();

    // Extracting the filename without the directory path
    $filename = basename($img);

    // Delete the file from the server
    $filePath = "../church/uploads/" . $filename;
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Delete the record from the database
    $stmt = $conn->prepare("DELETE FROM content WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Record and file deleted successfully";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
}

// Handle add, edit, delete actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_content"])) {
        $page_id = $conn->real_escape_string( $_POST["page_id"]);
        $title = $conn->real_escape_string($_POST["title"]);
        $content = $conn->real_escape_string($_POST["content"]);
        
        // Handle file upload
        $targetDir = '../church/uploads/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
            print_r(mkdir($targetDir, 0755, true));
        }
        $target_file = $targetDir . basename($_FILES["img"]["name"]); 
        move_uploaded_file($_FILES["img"]["tmp_name"], $target_file); 
        
        $img = $target_file; 
        addContent($conn, $page_id, $title, $content, $img);
    } elseif (isset($_POST["edit_content"])) {
        $id = $conn->real_escape_string($_POST["id"]);
        $title = $conn->real_escape_string($_POST["title"]);
        $content = $conn->real_escape_string($_POST["content"]);
        
        // Handle file upload
        $target_dir = "../church/uploads/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
        
        $img = $target_file;
        editContent($conn, $id, $title, $content, $img);
    } elseif (isset($_POST["delete_content"])) {
        $id = $conn->real_escape_string($_POST["id"]);
        deleteContent($conn, $id);
    }
}

// Fetch pages for selection
$sql_pages = "SELECT * FROM pages";
$result_pages = $conn->query($sql_pages);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Content</title>
    <style>
    </style>
</head>
<body>
    <h1>Add New Content</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="page_id">Page:</label><br>
        <select name="page_id" id="page_id">
            <?php
            if ($result_pages->num_rows > 0) {
                while ($row = $result_pages->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["pageName"]) . "</option>";
                }
            } else {
                echo "<option value=''>No pages available</option>";
            }
            ?>
        </select><br>
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title"><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content"></textarea><br><br>
        <label for="img">Image:</label><br>
        <input type="file" id="img" name="img"><br><br>
        <input type="submit" name="add_content" value="Add Content">
    </form>

    <h1>Edit/Delete Content</h1>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php
        // Fetch content from database
        $sql_content = "SELECT * FROM content";
        $result_content = $conn->query($sql_content);

        if ($result_content->num_rows > 0) {
            while ($row = $result_content->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["title"]) . "</td>"; // Sanitize output
                echo "<td>" . htmlspecialchars($row["content"]) . "</td>"; // Sanitize output
                echo "<td><img src='" . htmlspecialchars($row["img"]) . "' style='max-width: 100px;'></td>"; // Display image
                echo "<td>";
                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<input type='hidden' name='title' value='" . htmlspecialchars($row["title"]) . "'>";
                echo "<input type='hidden' name='content' value='" . htmlspecialchars($row["content"]) . "'>";
                echo "<input type='hidden' name='img' value='" . htmlspecialchars($row["img"]) . "'>";
                echo "<input type='submit' name='edit_content' value='Edit'>";
                echo "<input type='submit' name='delete_content' value='Delete'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No content available</td></tr>";
        }
        ?>
    </table>
</body>
</html>
