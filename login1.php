
<?php
session_start();

// Replace these with database credentials
$host = "localhost";
$username = "root";
$password = "";
$database = "database";

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $username = $_POST["name"];
    $password = $_POST["password"];


    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM crud2 WHERE name = ?");
    $stmt->bind_param('s', $username);

    // Execute the statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Check if a user with the given username exists
    if ($result->num_rows > 0) {
        // Fetch the user's information
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variable to mark user as authenticated
            $_SESSION["authenticated"] = true;

            // Redirect to the main page or wherever you want
            header("Location: index.php");
            exit();
        } else {
            // Invalid password, redirect back to the login page with an error message
            header("Location: login.php?error=1");
            exit();
        }
    } else {
        echo "Hello";
        die;
        // User not found, redirect back to the login page with an error message
        header("Location: login.php?error=1");
        exit();
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
