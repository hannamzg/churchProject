
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

// Add question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_question"])) {
    $question_text =$conn->real_escape_string( $_POST["question_text"]);
    $option1 = $conn->real_escape_string($_POST["option1"]);
    $option2 = $conn->real_escape_string($_POST["option2"]);
    $option3 = $conn->real_escape_string($_POST["option3"]);
    $correct_option = $conn->real_escape_string($_POST["correct_option"]);

    $sql = "INSERT INTO questions (question_text, option1, option2, option3, correct_option)
            VALUES ('$question_text', '$option1', '$option2', '$option3', '$correct_option')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Edit question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_question"])) {
    $question_id = $conn->real_escape_string($_POST["question_id"]);
    $question_text = $conn->real_escape_string($_POST["question_text"]);
    $option1 = $conn->real_escape_string($_POST["option1"]);
    $option2 = $conn->real_escape_string($_POST["option2"]);
    $option3 = $conn->real_escape_string($_POST["option3"]);
    $correct_option = $conn->real_escape_string($_POST["correct_option"]);

    $sql = "UPDATE questions SET 
            question_text='$question_text', 
            option1='$option1', 
            option2='$option2', 
            option3='$option3', 
            correct_option='$correct_option' 
            WHERE id='$question_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Delete question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_question"])) {
    $question_id = $conn->real_escape_string($_POST["question_id"]);

    $sql = "DELETE FROM questions WHERE id='$question_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Questions</title>
</head>
<body>
    <h1>Add New Question</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="question_text">Question:</label><br>
        <input type="text" id="question_text" name="question_text"><br>
        <label for="option1">Option 1:</label><br>
        <input type="text" id="option1" name="option1"><br>
        <label for="option2">Option 2:</label><br>
        <input type="text" id="option2" name="option2"><br>
        <label for="option3">Option 3:</label><br>
        <input type="text" id="option3" name="option3"><br>
        <label for="correct_option">Correct Option:</label><br>
        <input type="text" id="correct_option" name="correct_option"><br><br>
        <input type="submit" name="add_question" value="Add Question">
    </form>

    <h1>Edit/Delete Question</h1>
    <?php
    // Fetch questions from database
    $sql = "SELECT * FROM questions";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
            echo "<input type='hidden' name='question_id' value='" . $row["id"] . "'>";
            echo "Question: <input type='text' name='question_text' value='" . $row["question_text"] . "'><br>";
            echo "Option 1: <input type='text' name='option1' value='" . $row["option1"] . "'><br>";
            echo "Option 2: <input type='text' name='option2' value='" . $row["option2"] . "'><br>";
            echo "Option 3: <input type='text' name='option3' value='" . $row["option3"] . "'><br>";
            echo "Correct Option: <input type='text' name='correct_option' value='" . $row["correct_option"] . "'><br>";
            echo "<input type='submit' name='edit_question' value='Edit'>";
            echo "<input type='submit' name='delete_question' value='Delete'>";
            echo "</form><br>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    ?>
</body>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .question-container {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
        .question {
            margin-bottom: 10px;
        }
    </style>
</html>
