<?php
$conn = new mysqli("localhost", "root", "", "books");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$bookId = $_POST['bookId'];
$action = $_POST['action']; // Action: update or delete

// Check if Book ID is numeric, a positive integer, and not a decimal
if (!is_numeric($bookId) || (int)$bookId <= 0) {
    echo "<p style='color: red;'>Invalid Book ID. It must be a positive number.</p>";
    exit;
}

// Check if book ID exists in the database
$sql_check = "SELECT * FROM books_db WHERE book_id = '$bookId'";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows == 0) {
    echo "<p style='color: red;'>Book ID not found in the database.</p>";
    exit;
}

if ($action === "update") {
    // Retrieve update form fields
    $bookTitle = $_POST['bookTitle'];
    $bookCategory = $_POST['bookCategory'];
    $bookAuthor = $_POST['bookAuthor'];
    $bookISBN = $_POST['bookISBN'];
    $bookCopies = $_POST['bookCopies'];

    // Input validations
    if (empty($bookTitle) || empty($bookCategory) || empty($bookAuthor)) {
        echo "<p style='color: red;'>Book Title, Category, and Author must not be empty.</p>";
        exit;
    }

    if (!is_numeric($bookISBN) || (int)$bookISBN <= 0) {
        echo "<p style='color: red;'>Invalid ISBN. It must be a positive number.</p>";
        exit;
    }

    if (!is_numeric($bookCopies) || (int)$bookCopies < 0) {
        echo "<p style='color: red;'>Available Copies must be 0 or greater.</p>";
        exit;
    }

    // Update query
    $sql = "UPDATE books_db 
            SET Title = '$bookTitle', Category = '$bookCategory', Author = '$bookAuthor', ISBN = '$bookISBN', Available = '$bookCopies' 
            WHERE book_id = '$bookId'";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Book updated successfully. <a href='index.php'>Go back to Home</a></p>";
    } else {
        echo "<p style='color: red;'>Error updating book: " . $conn->error . "</p>";
    }
} elseif ($action === "delete") {
    // Perform deletion directly on the server-side
    $sql = "DELETE FROM books_db WHERE book_id = '$bookId'";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Book deleted successfully. <a href='index.php'>Go back to Home</a></p>";
    } else {
        echo "<p style='color: red;'>Error deleting book: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: red;'>Invalid action.</p>";
}

$conn->close();
?>
